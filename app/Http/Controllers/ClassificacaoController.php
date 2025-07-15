<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use Illuminate\Http\Request;

class ClassificacaoController extends Controller
{
    public function index()
    {
        $candidatosAvaliados = Candidato::whereIn('status', ['Aprovado', 'Rejeitado'])
                                        ->with(['user', 'curso'])
                                        ->get();

        $candidatosComPontuacao = $candidatosAvaliados->map(function ($candidato) {
            $resultado = $candidato->calcularPontuacaoDetalhada();
            
            return (object) [ // Converte para objeto para facilitar o acesso na view
                'nome' => $candidato->user->name,
                'cpf' => $candidato->cpf,
                'status' => $candidato->status,
                'curso_nome' => $candidato->curso->nome,
                'data_nascimento' => $candidato->data_nascimento, // ✅ Adicionado para o critério de desempate
                'pontuacao_final' => $resultado['total'],
                'pontuacao_detalhes' => $resultado['detalhes'],
            ];
        });

        $classificacaoPorCurso = $candidatosComPontuacao
            ->groupBy('curso_nome')
            ->map(function ($candidatosDoCurso) {
                // ✅ ORDENAÇÃO DUPLA: Primeiro por pontos (maior para o menor), depois por idade (mais velho primeiro)
                return $candidatosDoCurso
                    ->sortByDesc('pontuacao_final')
                    ->sortBy('data_nascimento') 
                    ->values();
            });

        return view('classificacao.index', compact('classificacaoPorCurso'));
    }
}