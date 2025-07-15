<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoDeAtividade;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TipoDeAtividadeController extends Controller
{
    public function index()
    {
        $atividades = TipoDeAtividade::all();
        return view('admin.tipos-de-atividade.index', compact('atividades'));
    }

    /**
     * Mostra o formulário para criar uma nova regra de pontuação.
     */
    public function create()
    {
        return view('admin.tipos-de-atividade.create');
    }

    /**
     * Guarda a nova regra de pontuação no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:tipos_de_atividade',
            'descricao' => 'nullable|string',
            // ✅ AJUSTE: Adicionado 'semestre' à regra de validação 'in'
            'unidade_medida' => 'required|in:horas,meses,fixo,semestre', 
            'pontos_por_unidade' => 'required|numeric|min:0',
            'divisor_unidade' => 'nullable|integer|min:1',
            'pontuacao_maxima' => 'nullable|integer|min:1',
        ]);

        TipoDeAtividade::create($request->all());

        return redirect()->route('admin.tipos-de-atividade.index')->with('success', 'Regra de pontuação criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoDeAtividade $tipos_de_atividade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoDeAtividade $tipos_de_atividade)
    {
        $atividade = $tipos_de_atividade;
        return view('admin.tipos-de-atividade.edit', compact('atividade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoDeAtividade $tipos_de_atividade)
    {
        $atividade = $tipos_de_atividade;

        $request->validate([
            'nome' => ['required','string','max:255', Rule::unique('tipos_de_atividade')->ignore($atividade->id)],
            'descricao' => 'nullable|string',
            // ✅ AJUSTE: Adicionado 'semestre' à regra de validação 'in'
            'unidade_medida' => 'required|in:horas,meses,fixo,semestre', 
            'pontos_por_unidade' => 'required|numeric|min:0',
            'divisor_unidade' => 'nullable|integer|min:1',
            'pontuacao_maxima' => 'nullable|integer|min:1',
        ]);

        $atividade->update($request->all());

        return redirect()->route('admin.tipos-de-atividade.index')->with('success', 'Regra de pontuação atualizada com sucesso!');
    }

    /**
     * ✅ MÉTODO ATUALIZADO COM A VERIFICAÇÃO DE SEGURANÇA
     * Remove the specified resource from storage.
     */
    public function destroy(TipoDeAtividade $tipos_de_atividade)
    {
        // 1. Verifica se a regra já foi utilizada por algum candidato.
        if ($tipos_de_atividade->candidatoAtividades()->exists()) {
            // 2. Se sim, redireciona de volta com uma mensagem de erro amigável.
            return redirect()->route('admin.tipos-de-atividade.index')
                               ->with('error', 'Esta regra não pode ser apagada, pois já está a ser utilizada por um ou mais candidatos.');
        }

        // 3. Se não estiver em uso, apaga a regra normalmente.
        $tipos_de_atividade->delete();

        // 4. Redireciona com uma mensagem de sucesso.
        return redirect()->route('admin.tipos-de-atividade.index')
                           ->with('success', 'Regra de pontuação apagada com sucesso!');
    }
}
