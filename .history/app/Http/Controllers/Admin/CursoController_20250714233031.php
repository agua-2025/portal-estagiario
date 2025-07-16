<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Instituicao;


class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Pega todos os cursos e já carrega a informação da instituição de cada um
        $cursos = Curso::with('instituicao')->get();
        return view('admin.cursos.index', compact('cursos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Busca todas as instituições em ordem alfabética para popular o <select>
        $instituicoes = Instituicao::orderBy('nome')->get();

        return view('admin.cursos.create', compact('instituicoes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validar os dados, incluindo os novos campos
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'instituicao_id' => 'required|exists:instituicoes,id',
            'descricao' => 'nullable|string', // Novo campo
            'detalhes' => 'nullable|string', // Novo campo
            'valor_bolsa_auxilio' => 'nullable|numeric|min:0', // Novo campo
            'valor_auxilio_transporte' => 'nullable|numeric|min:0', // Novo campo
            'requisitos' => 'nullable|string', // Novo campo
            'beneficios' => 'nullable|string', // Novo campo
            'carga_horaria' => 'nullable|string|max:255', // Novo campo
            'local_estagio' => 'nullable|string|max:255', // Novo campo
        ]);

        // 2. Criar o curso no banco
        Curso::create($validatedData);

        // 3. Redirecionar com mensagem de sucesso
        return redirect()->route('admin.cursos.index')->with('success', 'Curso cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Este método é para exibir um recurso específico.
        // Se você precisar que este controlador Admin também exiba detalhes
        // (por exemplo, para uma página de visualização interna do admin),
        // você pode implementar a lógica aqui.
        // Ex: $curso = Curso::findOrFail($id); return view('admin.cursos.show', compact('curso'));
        // Por enquanto, não é usado diretamente pelo "Saber Mais" público.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curso $curso)
    {
        // Buscamos todas as instituições para popular o dropdown
        $instituicoes = Instituicao::orderBy('nome')->get();

        return view('admin.cursos.edit', compact('curso', 'instituicoes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Curso $curso)
    {
        // 1. Validar os dados, incluindo os novos campos
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'instituicao_id' => 'required|exists:instituicoes,id',
            'descricao' => 'nullable|string', // Novo campo
            'detalhes' => 'nullable|string', // Novo campo
            'valor_bolsa_auxilio' => 'nullable|numeric|min:0', // Novo campo
            'valor_auxilio_transporte' => 'nullable|numeric|min:0', // Novo campo
            'requisitos' => 'nullable|string', // Novo campo
            'beneficios' => 'nullable|string', // Novo campo
            'carga_horaria' => 'nullable|string|max:255', // Novo campo
            'local_estagio' => 'nullable|string|max:255', // Novo campo
        ]);

        // 2. Atualizar o curso no banco
        $curso->update($validatedData);

        // 3. Redirecionar com mensagem de sucesso
        return redirect()->route('admin.cursos.index')->with('success', 'Curso atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curso $curso)
    {
        $curso->delete();

        return redirect()->route('admin.cursos.index')->with('success', 'Curso apagado com sucesso!');
    }
}
