<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use App\Models\Documento;
use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Instituicao;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CandidatoController extends Controller
{
    // ... (outros métodos como index, show, etc., permanecem inalterados) ...

    /**
     * Atualiza o status de um documento específico (Aprovado/Rejeitado).
     */
    public function updateDocumentStatus(Request $request, Documento $documento)
    {
        // ✅ TESTE DEFINITIVO: Se esta mensagem aparecer, estamos no arquivo certo.
        dd("Estamos executando o arquivo Admin/CandidatoController.php CORRETO!");

        $validated = $request->validate([
            'status' => 'required|in:aprovado,rejeitado',
            'motivo_rejeicao' => 'required_if:status,rejeitado|nullable|string|min:10',
        ]);

        DB::beginTransaction();
        try {
            $documento->status = $validated['status'];

            if ($documento->status === 'rejeitado') {
                $documento->motivo_rejeicao = $validated['motivo_rejeicao'];
                
                $candidato = $documento->user->candidato;
                if ($candidato) {
                    $candidato->status = 'Inscrição Incompleta';
                    $candidato->admin_observacao = "A Comissão Organizadora do Processo de Seleção solicitou uma correção. Verifique os detalhes nos itens abaixo e faça os ajustes necessários.";
                    $candidato->save();
                }
            } else {
                $documento->motivo_rejeicao = null;
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
            'user.documentos', 
            'user.candidatoAtividades.tipoDeAtividade', 
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

        $documentosEnviados = $candidato->user->documentos->keyBy('tipo_documento');

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
            $prazosAtivos = $candidato->user->candidatoAtividades()
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
        try {
            $candidato->delete();
            return redirect()->route('admin.candidatos.index')->with('success', 'Candidato apagado com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao apagar candidato ID {$candidato->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao apagar o candidato.');
        }
    }

    /**
     * Atualiza o status de um documento específico (Aprovado/Rejeitado).
     */
    public function updateDocumentStatus(Request $request, Documento $documento)
    {
        $validated = $request->validate([
            'status' => 'required|in:aprovado,rejeitado',
            'motivo_rejeicao' => 'required_if:status,rejeitado|nullable|string|min:10',
        ]);

        DB::beginTransaction();
        try {
            $documento->status = $validated['status'];

            if ($documento->status === 'rejeitado') {
                $documento->motivo_rejeicao = $validated['motivo_rejeicao'];
                
                $candidato = $documento->user->candidato;
                if ($candidato) {
                    $candidato->status = 'Inscrição Incompleta';
                    
                    // ✅ CORREÇÃO DEFINITIVA:
                    // Define uma mensagem genérica e profissional para o alerta principal.
                    $candidato->admin_observacao = "A Comissão Organizadora do Processo de Seleção solicitou uma correção. Verifique os detalhes nos itens abaixo e faça os ajustes necessários.";
                    
                    $candidato->save();
                }

            } else { // Se for 'aprovado'
                $documento->motivo_rejeicao = null;
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
        $prazosAtivos = $candidato->user->candidatoAtividades()
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
            return redirect()->back()->with('error', 'Não é possível homologar um candidato que não esteja no status "Aprovado".');
        }

        try {
            $candidato->status = 'Homologado';
            $candidato->ato_homologacao = $request->input('ato_homologacao');
            $candidato->homologado_em = now();
            $candidato->homologacao_observacoes = $request->input('homologacao_observacoes');
            $candidato->save();

            Log::info("Candidato ID {$candidato->id} homologado por " . auth()->user()->name, [
                'ato_homologacao' => $candidato->ato_homologacao,
                'homologacao_observacoes' => $candidato->homologacao_observacoes
            ]);

            return redirect()->back()->with('success', 'Candidato homologado com sucesso!');

        } catch (\Exception | \Throwable $e) {
            Log::error("Erro ao homologar candidato ID {$candidato->id}: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao homologar o candidato.');
        }
    }
}
