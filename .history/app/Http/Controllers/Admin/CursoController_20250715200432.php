<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Instituicao; // Manter, pois pode ser usado em outras partes do Admin (ex: Candidatos)


class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ✅ ATUALIZADO: Removido with('instituicao') pois Curso não tem mais instituicao_id
        $cursos = Curso::all(); 
        return view('admin.cursos.index', compact('cursos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ✅ ATUALIZADO: Instituições não são mais diretamente vinculadas ao curso no cadastro.
        // Removida a busca por instituicoes e a passagem para a view.
        // return view('admin.cursos.create', compact('instituicoes')); // Linha original
        return view('admin.cursos.create'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validar os dados, REMOVENDO a validação de instituicao_id
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            // 'instituicao_id' => 'required|exists:instituicoes,id', // ✅ REMOVIDO: Coluna não existe mais em cursos
            'descricao' => 'nullable|string',
            'detalhes' => 'nullable|string',
            'valor_bolsa_auxilio' => 'nullable|numeric|min:0',
            'valor_auxilio_transporte' => 'nullable|numeric|min:0',
            'requisitos' => 'nullable|string',
            'beneficios' => 'nullable|string',
            'carga_horaria' => 'nullable|string|max:255',
            'local_estagio' => 'nullable|string|max:255',
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
        // Por enquanto, não é usado diretamente pelo "Saber Mais" público.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curso $curso)
    {
        // ✅ ATUALIZADO: Instituições não são mais diretamente vinculadas ao curso na edição.
        // Removida a busca por instituicoes e a passagem para a view.
        // $instituicoes = Instituicao::orderBy('nome')->get(); // Linha original
        // return view('admin.cursos.edit', compact('curso', 'instituicoes')); // Linha original
        return view('admin.cursos.edit', compact('curso')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Curso $curso)
    {
        // 1. Validar os dados, REMOVENDO a validação de instituicao_id
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            // 'instituicao_id' => 'required|exists:instituicoes,id', // ✅ REMOVIDO: Coluna não existe mais em cursos
            'descricao' => 'nullable|string',
            'detalhes' => 'nullable|string',
            'valor_bolsa_auxilio' => 'nullable|numeric|min:0',
            'valor_auxilio_transporte' => 'nullable|numeric|min:0',
            'requisitos' => 'nullable|string',
            'beneficios' => 'nullable|string',
            'carga_horaria' => 'nullable|string|max:255',
            'local_estagio' => 'nullable|string|max:255',
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