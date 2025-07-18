<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidato; // Modelo do Candidato
use App\Models\Documento; // Para a função updateDocumentStatus
use Illuminate\Http\Request;
use App\Models\Curso; // Para listas de cursos em create/edit
use App\Models\Instituicao; // Para listas de instituições em create/edit
use Illuminate\Support\Facades\Log; // ✅ ADICIONADO: Para usar a funcionalidade de log

class CandidatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

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
        $cursos = Curso::orderBy('nome')->get();
        $instituicoes = Instituicao::orderBy('nome')->get();
        return view('admin.candidatos.create', compact('cursos', 'instituicoes'));
    }

    /**
     * Store a newly created resource in storage.
     * ✅ AJUSTADO: Lógica para criar o candidato com status inicial 'Inscrição Incompleta'.
     */
/**
     * Store a newly created resource in storage.
     * ✅ AJUSTADO: Lógica para criar o candidato com status inicial 'Inscrição Incompleta'.
     */
    public function store(Request $request)
    {
        // 1. Validação dos dados do formulário
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id', // Assumindo que o user_id é passado pelo formulário ou outro método
            'nome_completo' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:candidatos,cpf',
            // Adicione AQUI a validação para TODOS os outros campos que são passados pelo formulário de criação de candidato.
            // Ex: 'curso_id' => 'nullable|exists:cursos,id',
            // 'nome_pai' => 'nullable|string|max:255',
            // 'data_nascimento' => 'nullable|date',
            // ... (restante das suas validações) ...
        ], [
            'user_id.required' => 'O usuário associado é obrigatório.',
            'user_id.exists' => 'O usuário associado não existe.',
            'nome_completo.required' => 'O nome completo é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
        ]);

        // 2. Definir o status inicial como 'Inscrição Incompleta'
        // ESTE É O PASSO QUE RESOLVE O ERRO "Field 'status' doesn't have a default value"
        $validatedData['status'] = 'Inscrição Incompleta'; 

        try {
            // 3. Criar o candidato no banco de dados
            Candidato::create($validatedData);

            return redirect()->route('admin.candidatos.index')->with('success', 'Candidato criado com sucesso e status inicial "Inscrição Incompleta"!');
        } catch (\Exception $e) {
            // 4. Logar e exibir erro em caso de falha
            Log::error("Erro ao criar candidato: " . $e->getMessage() . " Dados da Requisição: " . json_encode($request->all()));
            return redirect()->back()->with('error', 'Ocorreu um erro ao criar o candidato. Por favor, tente novamente. Detalhes: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Mostra o perfil completo de um candidato para análise.
     */
    public function show(Candidato $candidato)
    {
        $candidato->load([
            'user.documentos', 
            'user.candidatoAtividades.tipoDeAtividade', 
            'curso',
            'instituicao'
        ]);

        $resultadoPontuacao = $candidato->calcularPontuacaoDetalhada();

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
        $cursos = Curso::orderBy('nome')->get();
        $instituicoes = Instituicao::orderBy('nome')->get();
        return view('admin.candidatos.edit', compact('candidato', 'cursos', 'instituicoes'));
    }

    /**
     * Update the specified resource in storage.
     * ✅ AJUSTADO: Validação do status para incluir 'Homologado'.
     */
    public function update(Request $request, Candidato $candidato)
    {
        $request->validate([
            'status' => 'required|in:Em Análise,Aprovado,Rejeitado,Homologado', // ✅ ADICIONADO 'Homologado'
            'admin_observacao' => 'nullable|string',
            // Adicione outras validações para os campos editáveis pelo admin aqui
        ]);

        $candidato->status = $request->input('status');
        $candidato->admin_observacao = $request->input('admin_observacao');
        
        try {
            $candidato->save();
            return redirect()->route('admin.candidatos.index')->with('success', 'Status do candidato ' . $candidato->nome_completo . ' atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar candidato ID {$candidato->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao atualizar o candidato. Por favor, tente novamente. Detalhes: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidato $candidato)
    {
        try {
            $candidato->delete();
            return redirect()->route('admin.candidatos.index')->with('success', 'Candidato apagado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao apagar candidato ID {$candidato->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao apagar o candidato. Por favor, tente novamente. Detalhes: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza o status de um documento específico (Aprovado/Rejeitado).
     */
    public function updateDocumentStatus(Request $request, Documento $documento)
    {
        $request->validate([
            'status' => 'required|in:aprovado,rejeitado',
        ]);

        $documento->status = $request->input('status');
        
        try {
            $documento->save();
            return back()->with('success', 'Status do documento atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar status do documento ID {$documento->id}: " . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao atualizar o status do documento. Por favor, tente novamente. Detalhes: ' . $e->getMessage());
        }
    }

    /**
     * Homologa um candidato específico.
     * ✅ NOVO MÉTODO PARA HOMOLOGAÇÃO
     */
    public function homologar(Request $request, Candidato $candidato)
    {
        $request->validate([
            'ato_homologacao' => 'required|string|max:255',
            'homologacao_observacoes' => 'nullable|string',
        ], [
            'ato_homologacao.required' => 'O campo "Número/Referência do Ato de Homologação" é obrigatório.',
        ]);

        if ($candidato->status !== 'Aprovado') {
            return redirect()->back()->with('error', 'Não é possível homologar um candidato que não esteja no status "Aprovado". O status atual é: ' . $candidato->status);
        }

        try {
            $candidato->status = 'Homologado';
            $candidato->ato_homologacao = $request->input('ato_homologacao');
            $candidato->homologado_em = now(); // Define a data e hora atuais automaticamente
            $candidato->homologacao_observacoes = $request->input('homologacao_observacoes');
            $candidato->save();

            Log::info("Candidato ID {$candidato->id} homologado por " . auth()->user()->name . " (ID: " . auth()->id() . ")", [
                'ato_homologacao' => $candidato->ato_homologacao,
                'homologacao_observacoes' => $candidato->homologacao_observacoes
            ]);

            return redirect()->back()->with('success', 'Candidato homologado com sucesso!');

        } catch (\Exception | \Throwable $e) { // Captura exceções e erros fatais
            Log::error("Erro ao homologar candidato ID {$candidato->id}: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao homologar o candidato. Por favor, tente novamente. Detalhes: ' . $e->getMessage());
        }
    }
}