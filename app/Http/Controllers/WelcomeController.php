<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Candidato;
use App\Models\Instituicao; // Necessário para o eager loading em Candidato
use Illuminate\Http\Request; // Manter, mesmo que não seja usado no 'index'

class WelcomeController extends Controller
{
    /**
     * Mostra a página inicial do portal com cursos e um resumo da classificação de candidatos.
     */
    public function index()
    {
        // 1. Busca TODOS os candidatos do banco de dados (sem ordenação por pontuação ainda)
        // Carrega as relações 'user', 'curso' e 'instituicao' antecipadamente.
        $todosCandidatos = Candidato::with(['user', 'curso', 'instituicao'])->get();

        // 2. Mapeia os candidatos para CALCULAR a pontuação final e os detalhes
        // ESTE É O PASSO CRÍTICO PARA RESOLVER O PROBLEMA DO "0.0"
        $candidatosClassificacao = $todosCandidatos->map(function ($candidato) {
            // Assume que calcularPontuacaoDetalhada() existe no Model Candidato
            // e retorna um array com 'total' e 'detalhes'.
            $resultadoPontuacao = $candidato->calcularPontuacaoDetalhada();

            // Adiciona a pontuação total e os detalhes ao objeto do candidato
            $candidato->pontuacao_final = $resultadoPontuacao['total'];
            $candidato->pontuacao_detalhes = $resultadoPontuacao['detalhes'];
            
            return $candidato; // Retorna o objeto Candidato modificado
        })
        ->sortByDesc('pontuacao_final') // 3. Agora, ordena a coleção pela pontuação calculada
        ->sortBy(function ($candidato) {
            // Critério de desempate: data de nascimento (mais velho primeiro)
            return strtotime($candidato->data_nascimento);
        })
        ->take(5) // 4. Pega os 5 primeiros
        ->values(); // 5. Reseta os índices da coleção

        // 6. Busca todos os cursos para a seção de 'Áreas de Atuação'
        $cursos = Curso::all();

        // 7. Envia os dados para a view 'welcome'
        return view('welcome', [
            'cursos' => $cursos,
            'candidatosClassificacao' => $candidatosClassificacao
        ]);
    }
}