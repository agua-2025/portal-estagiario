<?php

namespace App\Http\Controllers;

use App\Models\Curso;     // Importa o Model de Curso
use App\Models\Candidato; // ✅ Importa o Model de Candidato
use App\Models\Instituicao; // ✅ Importa o Model de Instituicao, necessário para o eager loading
use Illuminate\Http\Request; // Manter, mesmo que não seja usado diretamente no 'index' agora

class WelcomeController extends Controller
{
    /**
     * Mostra a página inicial do portal com cursos e um resumo da classificação de candidatos.
     */
    public function index()
    {
        // 1. Busca todos os cursos cadastrados no banco de dados (para a seção de Áreas de Atuação)
        $cursos = Curso::all();

        // 2. Busca um número limitado de candidatos para a seção de classificação da página inicial
        // (Ex: os 5 primeiros candidatos por pontuação)
        // Carrega os relacionamentos 'curso' e 'instituicao' para evitar problemas de N+1 queries.
        $candidatosClassificacao = Candidato::with(['curso', 'instituicao'])
                                           ->orderBy('pontuacao_final', 'desc') // Ordena do maior para o menor
                                           ->orderBy('nome_completo')    // Critério de desempate
                                           ->take(5)                     // Limita a 5 resultados para a página inicial
                                           ->get();

        // 3. Envia os dados de cursos e classificação para a view 'welcome'
        return view('welcome', [
            'cursos' => $cursos,
            'candidatosClassificacao' => $candidatosClassificacao
        ]);
    }
}