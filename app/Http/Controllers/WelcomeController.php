<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Candidato;
use App\Models\PublicDocument; // <-- [ADD]
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $cursos = Curso::orderBy('nome')->get();

        $homologados = Candidato::where('status', 'Homologado')
            ->with('curso')
            ->get()
            ->map(function($candidato) {
                $pontuacao = $candidato->calcularPontuacaoDetalhada();
                $candidato->pontuacao_final = $pontuacao['total'];
                return $candidato;
            })
            ->sort(function ($a, $b) {
                if ($a->pontuacao_final !== $b->pontuacao_final) {
                    return $b->pontuacao_final <=> $a->pontuacao_final;
                }
                return $a->data_nascimento <=> $b->data_nascimento;
            })
            ->values();

        $convocados = Candidato::where('status', 'Convocado')
            ->with('curso')
            ->orderBy('convocado_em', 'desc')
            ->get();

        // <-- [ADD] Documentos publicados para a seção “Editais e Documentos”
        $docs = PublicDocument::published()
            ->latest('published_at')
            ->take(3) // use ->get() para todos
            ->get();

        return view('welcome', compact('cursos', 'homologados', 'convocados', 'docs')); // <-- [ADD docs]
    }
}
