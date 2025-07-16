<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use App\Models\Documento; // ✅ Adicionado para a nova função
use Illuminate\Http\Request;
use App\Models\Curso; // Manter, pois candidato tem curso_id
use App\Models\Instituicao; // Manter, pois candidato tem instituicao_id

class CandidatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // ✅ CORRIGIDO: Carrega 'curso' e 'instituicao' diretamente do Model Candidato.
        // Removido 'curso.instituicao' pois Curso não tem mais instituicao_id.
        $query = Candidato::query()->with(['user', 'curso', 'instituicao']);

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
        // Se você tiver uma tela de criação de candidato no admin,
        // e ela precisar de listas de cursos e instituições, você as buscaria aqui.
        $cursos = Curso::orderBy('nome')->get();
        $instituicoes = Instituicao::orderBy('nome')->get();
        return view('admin.candidatos.create', compact('cursos', 'instituicoes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Lógica para armazenar um novo candidato
        // Exemplo:
        // $validatedData = $request->validate([...]);
        // Candidato::create($validatedData);
        // return redirect()->route('admin.candidatos.index')->with('success', 'Candidato criado com sucesso!');
    }

    /**
     * Mostra o perfil completo de um candidato para análise.
     */
    public function show(Candidato $candidato)
    {
        // Carrega todas as relações necessárias para exibir o perfil completo
        // ✅ CORRIGIDO: Carrega 'curso' e 'instituicao' diretamente do Candidato.
        // Removido 'curso.instituicao' pois Curso não tem mais instituicao_id.
        $candidato->load([
            'user.documentos', 
            'user.candidatoAtividades.tipoDeAtividade', 
            'curso', // Carrega o curso genérico
            'instituicao' // Carrega a instituição diretamente do candidato
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
        // Lógica para exibir o formulário de edição de candidato no admin
        $cursos = Curso::orderBy('nome')->get();
        $instituicoes = Instituicao::orderBy('nome')->get();
        return view('admin.candidatos.edit', compact('candidato', 'cursos', 'instituicoes'));
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
        // Lógica para apagar um candidato
        // Exemplo:
        // $candidato->delete();
        // return redirect()->route('admin.candidatos.index')->with('success', 'Candidato apagado com sucesso!');
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