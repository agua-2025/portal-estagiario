<?php

namespace App\Http\Controllers\Candidato;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use App\Models\TipoDeAtividade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $user->load(['candidato.curso', 'candidatoAtividades']);

        $candidato = $user->candidato;

        // A contagem de itens rejeitados (lógica existente, mantida)
        $itensRejeitadosCount = $user->candidatoAtividades
                                        ->where('status', 'Rejeitada')
                                        ->count();

        // ✅ INÍCIO DO AJUSTE: Lógica para verificar pendências, conforme o plano de ação.
        $temRecursoPendente = false;
        $temInscricaoIncompleta = false;

        if ($candidato) {
            // Verifica se há atividades com prazo de recurso ativo
            $temRecursoPendente = $user->candidatoAtividades()
                                    ->where('status', 'Rejeitada')
                                    ->where('prazo_recurso_ate', '>', now())
                                    ->exists();

            // Verifica se o perfil/documentos foram rejeitados pelo admin
            $temInscricaoIncompleta = ($candidato->status === 'Inscrição Incompleta' && !empty($candidato->admin_observacao));
        }

        $temPendencias = $temRecursoPendente || $temInscricaoIncompleta;
        // ✅ FIM DO AJUSTE

        $classificacaoDoCurso = collect();
        $regrasDePontuacao = collect();

        if ($candidato && $candidato->curso) {
            $regrasDePontuacao = TipoDeAtividade::all();

            $candidatosAprovadosNoCurso = Candidato::where('status', 'Aprovado')
                                                ->where('curso_id', $candidato->curso_id)
                                                ->with('user.candidatoAtividades.tipoDeAtividade') 
                                                ->get();

            $classificacaoDoCurso = $candidatosAprovadosNoCurso->map(function ($c) use ($regrasDePontuacao) {
                // Garante que o método exista antes de chamar
                $resultado = method_exists($c, 'calcularPontuacaoDetalhada') 
                    ? $c->calcularPontuacaoDetalhada() 
                    : ['total' => 0, 'detalhes' => []];
                
                $pontosPorAtividade = [];
                foreach ($regrasDePontuacao as $regra) {
                    $pontosPorAtividade[$regra->nome] = 0;
                }
                foreach ($resultado['detalhes'] as $detalhe) {
                    $pontosPorAtividade[$detalhe['nome']] = $detalhe['pontos'];
                }

                $c->pontuacao_final = $resultado['total'];
                $c->boletim_pontos = $pontosPorAtividade;
                $c->pontuacao_detalhes = $resultado['detalhes'];

                return $c;
            })
            ->sortByDesc('pontuacao_final')
            ->sortBy('data_nascimento')
            ->values();
        }

        return view('dashboard', [
            'user' => $user,
            'candidato' => $candidato,
            'itensRejeitadosCount' => $itensRejeitadosCount,
            'classificacaoDoCurso' => $classificacaoDoCurso,
            'regrasDePontuacao' => $regrasDePontuacao,
            'temPendencias' => $temPendencias, // ✅ ADICIONADO: Passa a variável para a view
        ]);
    }
}