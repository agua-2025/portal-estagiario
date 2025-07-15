<?php

namespace App\Http\Controllers;

use App\Models\Curso; // ✅ Importa o Model de Curso
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Mostra a página inicial do portal.
     */
    public function index()
    {
        // Busca todos os cursos cadastrados no banco de dados
        $cursos = Curso::all();

        // Envia a lista de cursos para a view 'welcome'
        return view('welcome', [
            'cursos' => $cursos
        ]);
    }
}
