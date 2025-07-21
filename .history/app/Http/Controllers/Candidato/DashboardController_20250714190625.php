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
        
        // ✅ CORREÇÃO: Garante que as relações corretas sejam carregadas no utilizador.
        $user->load(['candidato.curso', 'candidatoAtividades']);

        $candidato = $user->candidato;

        // A contagem é feita na relação do UTILIZADOR, que está correta.
        $itensRejeitadosCount = $user->candidatoAtividades
                                     ->where('status', 'Rejeitada')
                                     ->count();

        $classificacaoDoCurso = collect();
        $regrasDePontuacao = collect();

        if ($candidato && $candidato->curso) {
            $regrasDePontuacao = TipoDeAtividade::all();

            // A relação é carregada através do 'user' em cada candidato da lista.
            $candidatosAprovadosNoCurso = Candidato::where('status', 'Aprovado')
                                                  ->where('curso_id', $candidato->curso_id)
                                                  ->with('user.candidatoAtividades.tipoDeAtividade') 
                                                  ->get();

            $classificacaoDoCurso = $candidatosAprovadosNoCurso->map(function ($c) use ($regrasDePontuacao) {
                // A chamada para o cálculo continua a mesma, pois o método no Model está correto.
                $resultado = $c->calcularPontuacaoDetalhada();
                
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
        ]);
    }
}
