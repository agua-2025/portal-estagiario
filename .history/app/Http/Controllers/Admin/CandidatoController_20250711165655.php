<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use App\Models\Documento; // ✅ Adicionado para a nova função
use Illuminate\Http\Request;

class CandidatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Candidato::query()->with(['user', 'curso.instituicao']);

        if ($search) {
            $query->where('nome_completo', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%");
        }

        $candidatos = $query->latest()->paginate(15);

        return view('admin.candidatos.index', compact('candidatos', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Mostra o perfil completo de um candidato para análise.
     */
    public function show(Candidato $candidato)
    {
        // Carrega todas as relações necessárias para exibir o perfil completo
        $candidato->load([
            'user.documentos', 
            'user.candidatoAtividades.tipoDeAtividade', 
            'curso.instituicao'
        ]);

        // ✅ AJUSTE CIRÚRGICO AQUI:
        // Chama o método com o nome correto e obtém os dados detalhados.
        $resultadoPontuacao = $candidato->calcularPontuacaoDetalhada();

        // Envia os dados corretos para a view.
        return view('admin.candidatos.show', [
            'candidato' => $candidato,
            'pontuacaoTotal' => $resultadoPontuacao['total'],
            'detalhesPontuacao' => $resultadoPontuacao['detalhes'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidato $candidato)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Candidato $candidato)
    {
        $request->validate([
            'status' => 'required|in:Aprovado,Rejeitado',
            'admin_observacao' => 'nullable|string',
        ]);

        $candidato->status = $request->input('status');
        $candidato->admin_observacao = $request->input('admin_observacao');
        
        $candidato->save();

        return redirect()->route('admin.candidatos.index')
                         ->with('success', 'Status do candidato ' . $candidato->nome_completo . ' atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidato $candidato)
    {
        //
    }

    /**
     * ✅ SUA FUNÇÃO ORIGINAL MANTIDA INTACTA
     * Atualiza o status de um documento específico (Aprovado/Rejeitado).
     */
    public function updateDocumentStatus(Request $request, Documento $documento)
    {
        // Valida se o status enviado é um dos valores permitidos.
        $request->validate([
            'status' => 'required|in:aprovado,rejeitado',
        ]);

        // Atualiza o status do documento e salva no banco de dados.
        $documento->status = $request->input('status');
        $documento->save();

        // Redireciona de volta para a página anterior com uma mensagem de sucesso.
        return back()->with('success', 'Status do documento atualizado com sucesso!');
    }
}