<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Adicionamos esta linha para a validação

class InstituicaoController extends Controller
{
    public function index()
    {
        $instituicoes = Instituicao::all();
        return view('admin.instituicoes.index', compact('instituicoes'));
    }

    public function create()
    {
        return view('admin.instituicoes.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255|unique:instituicoes',
            'sigla' => 'nullable|string|max:20',
            'endereco' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            'telefone_contato' => 'required|string|max:20',
        ]);

        Instituicao::create($validatedData);

        return redirect()->route('admin.instituicoes.index')->with('success', 'Instituição cadastrada com sucesso!');
    }

    public function show(Instituicao $instituico) // <-- Variável corrigida aqui
    {
        //
    }

    public function edit(Instituicao $instituico) // <-- Variável corrigida aqui
    {
        return view('admin.instituicoes.edit', compact('instituico'));
    }

    public function update(Request $request, Instituicao $instituico) // <-- Variável corrigida aqui
    {
        $validatedData = $request->validate([
            'nome' => ['required', 'string', 'max:255', Rule::unique('instituicoes')->ignore($instituico->id)],
            'sigla' => 'nullable|string|max:20',
            'endereco' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            'telefone_contato' => 'required|string|max:20',
        ]);

        $instituico->update($validatedData);

        return redirect()->route('admin.instituicoes.index')->with('success', 'Instituição atualizada com sucesso!');
    }

    public function destroy(Instituicao $instituico)
{
    $instituico->delete();

    return redirect()->route('admin.instituicoes.index')->with('success', 'Instituição apagada com sucesso!');
}
}