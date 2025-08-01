<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidato; // Modelo do Candidato
use App\Models\Documento; // Para a função updateDocumentStatus
use Illuminate\Http\Request;
use App\Models\Curso; // Para listas de cursos em create/edit
use App\Models\Instituicao; // Para listas de instituições em create/edit
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Essencial para a transação
use Carbon\Carbon;

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
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nome_completo' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:candidatos,cpf',
        ], [
            'user_id.required' => 'O usuário associado é obrigatório.',
            'user_id.exists' => 'O usuário associado não existe.',
            'nome_completo.required' => 'O nome completo é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
        ]);

        $validatedData['status'] = 'Inscrição Incompleta'; 

        try {
            Candidato::create($validatedData);
            return redirect()->route('admin.candidatos.index')->with('success', 'Candidato criado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao criar candidato: " . $e->getMessage(), ['request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao criar o candidato.')->withInput();
        }
    }

    /**
     * Mostra o perfil completo de um candidato para análise.
     */
    public function show(Candidato $candidato)
    {
        $candidato->load([
            'documentos', 
            'atividades.tipoDeAtividade', 
            'curso',
            'instituicao'
        ]);

        $documentosNecessarios = [
            'HISTORICO_ESCOLAR' => 'Histórico Escolar',
            'DECLARACAO_MATRICULA' => 'Declaração de Matrícula',
            'DECLARACAO_ELEITORAL' => 'Declaração de Quitação Eleitoral',
        ];

        if ($candidato->sexo === 'Masculino') {
            $documentosNecessarios['RESERVISTA'] = 'Comprovante de Reservista';
        }
        if ($candidato->possui_deficiencia) {
            $documentosNecessarios['LAUDO_MEDICO'] = 'Laudo Médico (PCD)';
        }

        $documentosEnviados = $candidato->documentos->keyBy('tipo_documento');

        $pontuacaoDetalhada = method_exists($candidato, 'calcularPontuacaoDetalhada') 
            ? $candidato->calcularPontuacaoDetalhada() 
            : ['total' => 0, 'detalhes' => []];

        return view('admin.candidatos.show', [
            'candidato' => $candidato,
            'pontuacaoTotal' => $pontuacaoDetalhada['total'],
            'detalhesPontuacao' => $pontuacaoDetalhada['detalhes'],
            'documentosNecessarios' => $documentosNecessarios,
            'documentosEnviados' => $documentosEnviados,
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
     */
    public function update(Request $request, Candidato $candidato)
    {
        if ($request->input('status') === 'Aprovado') {
            $prazosAtivos = $candidato->atividades()
                                     ->where('status', 'Rejeitada')
                                     ->where('prazo_recurso_ate', '>', now())
                                     ->exists();

            if ($prazosAtivos) {
                return redirect()->back()->with('error', 'Não é possível aprovar. O candidato possui atividades com prazo de recurso em andamento.');
            }
        }
        
        $validatedData = $request->validate([
            'status' => 'required|in:Em Análise,Aprovado,Rejeitado,Inscrição Incompleta',
            'admin_observacao' => 'nullable|string',
        ]);

        $novoStatus = $validatedData['status'];

        if ($novoStatus === 'Rejeitado') {
            $candidato->status = 'Inscrição Incompleta';
            Log::info("Admin rejeitou a inscrição do candidato ID {$candidato->id}. Status movido para 'Inscrição Incompleta'.");
        } else {
            $candidato->status = $novoStatus;
        }
        
        $candidato->admin_observacao = $validatedData['admin_observacao'];
        
        if ($candidato->status === 'Aprovado') {
            $candidato->revert_reason = null;
        }
        
        try {
            $candidato->save();
            return redirect()->route('admin.candidatos.show', $candidato)->with('success', 'Status do candidato atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar candidato ID {$candidato->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao atualizar o candidato.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidato $candidato)
    {
        DB::beginTransaction();
        try {
            $user = $candidato->user;
            $candidato->delete();
            
            if ($user) {
                $user->delete();
            }

            DB::commit();
            
            return redirect()->route('admin.candidatos.index')->with('success', 'Candidato e todos os seus dados foram apagados com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao apagar candidato ID {$candidato->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao apagar o candidato.');
        }
    }

    /**
     * Atualiza o status de um documento específico (Aprovado/Rejeitado).
     */
/**
     * Atualiza o status de um documento específico (Aprovado/Rejeitado).
     */
   public function updateDocumentStatus(Request $request, Documento $documento)
{
    $validated = $request->validate([
        'status' => 'required|in:aprovado,rejeitado',
        'motivo_rejeicao' => 'required_if:status,rejeitado|nullable|string|min:10',
    ]);

    // ✅ BLOQUEIO: Não permite aprovar documento rejeitado
    if ($documento->status === 'rejeitado' && $validated['status'] === 'aprovado') {
        return back()->with('error', 'Documento rejeitado não pode ser aprovado. O candidato deve reenviar o documento corrigido.');
    }
        $validated = $request->validate([
            'status' => 'required|in:aprovado,rejeitado',
            'motivo_rejeicao' => 'required_if:status,rejeitado|nullable|string|min:10',
        ]);

        DB::beginTransaction();
        try {
            $documento->status = $validated['status'];

            if ($documento->status === 'rejeitado') {
                $documento->motivo_rejeicao = $validated['motivo_rejeicao'];
                
                $candidato = $documento->candidato;
                if ($candidato) {
                    $candidato->status = 'Inscrição Incompleta';
                    $candidato->admin_observacao = "A Comissão Organizadora solicitou correções. Verifique o motivo em cada item rejeitado e reenvie os documentos necessários.";
                    $candidato->save();
                }

            } else {
                // ✅ QUANDO APROVADO
                $documento->motivo_rejeicao = null;
                
                // ✅ VERIFICAR SE PODE VOLTAR PARA "EM ANÁLISE"
                $candidato = $documento->candidato;
                if ($candidato && $candidato->status === 'Inscrição Incompleta') {
                    
                    // Lista de documentos obrigatórios
                    $documentosObrigatorios = ['HISTORICO_ESCOLAR', 'DECLARACAO_MATRICULA', 'DECLARACAO_ELEITORAL'];
                    if ($candidato->sexo === 'Masculino') {
                        $documentosObrigatorios[] = 'RESERVISTA';
                    }
                    if ($candidato->possui_deficiencia) {
                        $documentosObrigatorios[] = 'LAUDO_MEDICO';
                    }
                    
                    // Verifica se todos os documentos obrigatórios estão aprovados
                    $todosAprovados = true;
                    foreach ($documentosObrigatorios as $tipo) {
                        $doc = $candidato->documentos()->where('tipo_documento', $tipo)->first();
                        if (!$doc || $doc->status !== 'aprovado') {
                            $todosAprovados = false;
                            break;
                        }
                    }
                    
                    // Se todos estão aprovados, volta para "Em Análise"
                    if ($todosAprovados) {
                        $candidato->status = 'Em Análise';
                        $candidato->admin_observacao = null; // Limpa a mensagem de correção
                        $candidato->save();
                        Log::info("Candidato ID {$candidato->id} voltou para 'Em Análise' - todos os documentos aprovados.");
                    }
                }
            }
            
            $documento->save();
            DB::commit();

            return back()->with('success', 'Status do documento atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao atualizar status do documento ID {$documento->id}: " . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao atualizar o status do documento.');
        }
    }

    /**
     * Homologa um candidato específico.
     */
    public function homologar(Request $request, Candidato $candidato)
    {
        $prazosAtivos = $candidato->atividades()
                                ->where('status', 'Rejeitada')
                                ->where('prazo_recurso_ate', '>', now())
                                ->exists();

        if ($prazosAtivos) {
            return redirect()->back()->with('error', 'Não é possível homologar. O candidato possui atividades com prazo de recurso em andamento.');
        }

        $request->validate([
            'ato_homologacao' => 'required|string|max:255',
            'homologacao_observacoes' => 'nullable|string',
        ], [
            'ato_homologacao.required' => 'O campo "Número/Referência do Ato de Homologação" é obrigatório.',
        ]);

        if ($candidato->status !== 'Aprovado') {
            return redirect()->back()->with('error', 'Apenas candidatos "Aprovados" podem ser homologados.');
        }

        try {
            $candidato->status = 'Homologado';
            $candidato->ato_homologacao = $request->input('ato_homologacao');
            $candidato->homologado_em = now();
            $candidato->homologacao_observacoes = $request->input('homologacao_observacoes');
            $candidato->revert_reason = null;
            
            $candidato->recurso_prazo_ate = $this->calcularDiasUteis(2);
            $candidato->recurso_status = 'pendente';
            $candidato->recurso_tipo = 'classificacao';

            $candidato->save();
            return redirect()->back()->with('success', 'Candidato homologado com sucesso! O prazo para recurso de classificação foi aberto.');
        } catch (\Exception $e) {
            Log::error("Erro ao homologar candidato ID {$candidato->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao homologar o candidato.');
        }
    }

    /**
     * Defere (aceita) um recurso específico do histórico do candidato.
     */
    public function deferirRecurso(Request $request, Candidato $candidato, $recurso_index)
    {
        $historico = $candidato->recurso_historico ?? [];

        if (!isset($historico[$recurso_index])) {
            return back()->with('error', 'Recurso não encontrado no histórico.');
        }

        // Não permite uma nova decisão se uma já foi tomada.
        if (!empty($historico[$recurso_index]['decisao_admin'])) {
            return back()->with('error', 'Este recurso já foi decidido.');
        }

        $historico[$recurso_index]['decisao_admin'] = 'deferido';
        $historico[$recurso_index]['justificativa_admin'] = $request->input('justificativa_admin');
        $historico[$recurso_index]['data_decisao_admin'] = now()->toDateTimeString();
        $historico[$recurso_index]['admin_id'] = auth()->id();

        $candidato->recurso_historico = $historico;
        
        $candidato->save();

        return redirect()->route('admin.candidatos.show', $candidato)->with('success', 'Recurso deferido com sucesso! Lembre-se de reavaliar os itens do candidato.');
    }

    /**
     * Indefere (nega) um recurso específico do histórico do candidato.
     */
    public function indeferirRecurso(Request $request, Candidato $candidato, $recurso_index)
    {
        $request->validate(['justificativa_admin' => 'required|string|min:10'], 
        ['justificativa_admin.required' => 'A justificativa para indeferir o recurso é obrigatória.']);

        $historico = $candidato->recurso_historico ?? [];

        if (!isset($historico[$recurso_index])) {
            return back()->with('error', 'Recurso não encontrado no histórico.');
        }
        
        if (!empty($historico[$recurso_index]['decisao_admin'])) {
            return back()->with('error', 'Este recurso já foi decidido.');
        }

        $historico[$recurso_index]['decisao_admin'] = 'indeferido';
        $historico[$recurso_index]['justificativa_admin'] = $request->input('justificativa_admin');
        $historico[$recurso_index]['data_decisao_admin'] = now()->toDateTimeString();
        $historico[$recurso_index]['admin_id'] = auth()->id();

        $candidato->recurso_historico = $historico;
        $candidato->save();

        return redirect()->route('admin.candidatos.show', $candidato)->with('success', 'Recurso indeferido com sucesso.');
    }

private function calcularDiasUteis(int $diasUteisParaAdicionar): Carbon
{
    $data = Carbon::now();
    $diasAdicionados = 0;
    while ($diasAdicionados < $diasUteisParaAdicionar) {
        $data->addDay();
        if ($data->isWeekday()) {
            $diasAdicionados++;
        }
    }
   // ✅ AQUI ESTÁ A CORREÇÃO FINAL
        return $data->setTime(17, 0, 0); 
    }
}