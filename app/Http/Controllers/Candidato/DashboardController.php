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
        
        // ✅ AJUSTE: Carrega as relações a partir do candidato
        $user->load(['candidato.curso', 'candidato.atividades']);

        $candidato = $user->candidato;

        // Se o candidato não existir, inicializa as variáveis para evitar erros
        if (!$candidato) {
            return view('dashboard', [
                'user' => $user,
                'candidato' => null,
                'itensRejeitadosCount' => 0,
                'temPendencias' => false,
                'classificacaoDoCurso' => collect(),
                'regrasDePontuacao' => collect(),
            ]);
        }

        // ✅ AJUSTE: A contagem é feita na nova relação do candidato
        $itensRejeitadosCount = $candidato->atividades
                                        ->where('status', 'Rejeitada')
                                        ->count();

        // Lógica de verificação de pendências
        $temRecursoPendente = $candidato->atividades()
                                    ->where('status', 'Rejeitada')
                                    ->where('prazo_recurso_ate', '>', now())
                                    ->exists();
        $temInscricaoIncompleta = ($candidato->status === 'Inscrição Incompleta' && !empty($candidato->admin_observacao));
        $temPendencias = $temRecursoPendente || $temInscricaoIncompleta;

        $classificacaoDoCurso = collect();
        $regrasDePontuacao = collect();

        if ($candidato && $candidato->curso) {
            $regrasDePontuacao = TipoDeAtividade::all();

            // ✅ AJUSTE: Carrega a nova relação 'atividades' para o cálculo, diretamente do Candidato
            $candidatosAprovadosNoCurso = Candidato::where('status', 'Aprovado')
                                                ->where('curso_id', $candidato->curso_id)
                                                ->with('atividades.tipoDeAtividade') 
                                                ->get();

            $classificacaoDoCurso = $candidatosAprovadosNoCurso->map(function ($c) use ($regrasDePontuacao) {
                // A chamada para o cálculo continua a mesma, pois o método no Model já foi corrigido
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
            'temPendencias' => $temPendencias,
        ]);
    }
}