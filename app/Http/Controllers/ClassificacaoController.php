<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use Illuminate\Http\Request;

class ClassificacaoController extends Controller
{
    public function index()
    {
        // 1. Busca, calcula a pontuação e ordena os HOMOLOGADOS
        $homologados = Candidato::where('status', 'Homologado')
            ->with('curso')
            ->get()
            ->map(function($candidato) {
                // Calcula a pontuação real para cada candidato
                $pontuacao = $candidato->calcularPontuacaoDetalhada();
                $candidato->pontuacao_final = $pontuacao['total'];
                return $candidato;
            })
            // Lógica de ordenação correta para múltiplos critérios
            ->sort(function ($a, $b) {
                // Critério 1: Compara pela pontuação final (do maior para o menor)
                if ($a->pontuacao_final !== $b->pontuacao_final) {
                    return $b->pontuacao_final <=> $a->pontuacao_final;
                }
                // Critério 2 (Desempate): Se as pontuações forem iguais, compara pela data de nascimento (do mais velho para o mais novo)
                return $a->data_nascimento <=> $b->data_nascimento;
            });

        // 2. Busca os CONVOCADOS, ordenados pela data em que foram convocados
        $convocados = Candidato::where('status', 'Convocado')
            ->with('curso')
            ->orderBy('convocado_em', 'desc')
            ->get();

        // 3. Agrupa os homologados por curso para a view
        $homologadosAgrupados = $homologados->groupBy('curso.nome');

        // 4. Envia as duas listas para a view
        return view('classificacao.index', compact('homologadosAgrupados', 'convocados'));
    }
}