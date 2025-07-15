<?php
namespace App\Http\Controllers; // ✅ Namespace deve ser a primeira coisa após <?php

use App\Models\Curso; // Importe o seu modelo Curso
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Exibe os detalhes de um curso específico na área pública.
     *
     * @param  \App\Models\Curso  $curso
     * @return \Illuminate\View\View
     */
    public function show(Curso $curso) // O Laravel automaticamente encontra o curso pelo ID (Route Model Binding)
    {
        // Retorna a view 'cursos.show' e passa o objeto $curso para ela.
        // Certifique-se de que você tem uma view em resources/views/cursos/show.blade.php
        return view('cursos.show', compact('curso'));
    }
}
