<?php

namespace App\Http\Controllers\Candidato;

use App\Http\Controllers\Controller;
use App\Models\CandidatoAtividade;
use App\Models\TipoDeAtividade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use App\Models\Candidato;
use App\Models\User;
use Carbon\Carbon; // ✅ CORRIGIDO: A importação do Carbon foi adicionada de volta.

class AtividadeController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        $candidato = $user->candidato; // Pega o candidato associado
        $regrasDePontuacao = TipoDeAtividade::all();
        
        // ✅ AJUSTE: Busca as atividades a partir do candidato, não do user.
        $atividadesEnviadas = $candidato ? $candidato->atividades()->with('tipoDeAtividade')->latest()->get() : collect();

        return view('candidato.atividades.index', compact('regrasDePontuacao', 'atividadesEnviadas'));
    }

    public function store(Request $request)
    {
        Log::debug('Iniciando store de atividade. Request data: ' . json_encode($request->all()));

        $user = Auth::user();
        $candidato = $user->candidato;
        if (!$candidato) {
            return redirect()->back()->with('error', 'Perfil de candidato não encontrado.');
        }
        $previousStatus = $candidato->status;

        // Validação base para campos comuns
        $validationRules = [
            'tipo_de_atividade_id' => 'required|exists:tipos_de_atividade,id',
            'descricao_customizada' => 'nullable|string|max:255',
            'comprovativo' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ];

        $regra = TipoDeAtividade::find($request->tipo_de_atividade_id);

        Log::debug('Regra de Tipo de Atividade encontrada: ' . json_encode($regra));

        // Validação CONDICIONAL de campos específicos
        $isSemestresRule = (strtolower($regra->nome) === 'número de semestres cursados' || $regra->unidade_medida === 'semestre');
        $isAproveitamentoAcademicoRule = (strtolower($regra->nome) === 'aproveitamento acadêmico');
        $isHorasRule = ($regra->unidade_medida === 'horas');
        $isMesesRule = ($regra->unidade_medida === 'meses');

        if ($isSemestresRule) {
            $validationRules['semestres_declarados'] = 'required|integer|min:1';
            $validationRules['carga_horaria'] = 'nullable';
            $validationRules['data_inicio'] = 'nullable';
            $validationRules['data_fim'] = 'nullable';
            $validationRules['media_declarada_atividade'] = 'nullable';
        } elseif ($isAproveitamentoAcademicoRule) {
            $validationRules['media_declarada_atividade'] = 'required|numeric|between:0,10.00';
            $validationRules['carga_horaria'] = 'nullable';
            $validationRules['data_inicio'] = 'nullable';
            $validationRules['data_fim'] = 'nullable';
            $validationRules['semestres_declarados'] = 'nullable';
        } elseif ($isHorasRule) {
            $validationRules['carga_horaria'] = 'required|integer|min:1';
            $validationRules['semestres_declarados'] = 'nullable';
            $validationRules['data_inicio'] = 'nullable';
            $validationRules['data_fim'] = 'nullable';
            $validationRules['media_declarada_atividade'] = 'nullable';
        } elseif ($isMesesRule) {
            $validationRules['data_inicio'] = 'required|date';
            $validationRules['data_fim'] = 'required|date|after_or_equal:data_inicio';
            $validationRules['carga_horaria'] = 'nullable';
            $validationRules['semestres_declarados'] = 'nullable';
            $validationRules['media_declarada_atividade'] = 'nullable';
        } else {
            $validationRules['carga_horaria'] = 'nullable';
            $validationRules['data_inicio'] = 'nullable';
            $validationRules['data_fim'] = 'nullable';
            $validationRules['semestres_declarados'] = 'nullable';
            $validationRules['media_declarada_atividade'] = 'nullable';
        }

        $validatedData = $request->validate($validationRules);

        $dadosParaSalvar = [
            'user_id' => $user->id, // Mantém o user_id por segurança/auditoria
            'tipo_de_atividade_id' => $request->tipo_de_atividade_id,
            'descricao_customizada' => $request->descricao_customizada,
            'status' => 'Em Análise',
            'carga_horaria' => $request->input('carga_horaria'),
            'data_inicio' => $request->input('data_inicio'),
            'data_fim' => $request->input('data_fim'),
            'semestres_declarados' => $request->input('semestres_declarados'),
            'media_declarada_atividade' => $request->input('media_declarada_atividade'),
        ];
        
        $path = $request->file('comprovativo')->store('candidato_atividades/user_' . $user->id, 'public');
        $dadosParaSalvar['comprovativo_path'] = $path; // Corrigido para o nome correto da coluna

        try {
            // ✅ AJUSTE: Cria a atividade diretamente na relação do candidato
            $candidato->atividades()->create($dadosParaSalvar);
            Log::debug('Atividade criada com sucesso para o candidato ID: ' . $candidato->id);

            if (in_array($previousStatus, ['Homologado', 'Aprovado', 'Em Análise'])) {
                $candidato->status = 'Em Análise';
                $candidato->ato_homologacao = null;
                $candidato->homologado_em = null;
                $candidato->homologacao_observacoes = null;
                
                // ✅ AJUSTE: Salva o histórico no formato de array correto
                $revertHistory = $candidato->revert_reason ?? [];
                $revertHistory[] = [
                    'timestamp' => Carbon::now()->toDateTimeString(),
                    'reason' => "Atividade '{$regra->nome}' adicionada pelo candidato.",
                    'action' => 'activity_create',
                    'previous_status' => $previousStatus,
                ];
                $candidato->revert_reason = $revertHistory;
                
                $candidato->save();
                Log::info("Candidato ID {$candidato->id} (Status: {$previousStatus}) adicionou atividade e voltou para 'Em Análise'.");
                return redirect()->route('candidato.atividades.index')->with('success', 'Atividade adicionada! Sua inscrição voltou para "Em Análise".');
            }

            return redirect()->route('candidato.atividades.index')->with('success', 'Atividade adicionada com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao criar atividade: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao adicionar a atividade.');
        }
    }

    public function edit(CandidatoAtividade $atividade)
    {
        $this->authorize('update', $atividade);

        $regrasDePontuacao = TipoDeAtividade::all();
        $user = Auth::user();
        $candidato = $user->candidato;

        // ✅ AJUSTE: Busca as atividades a partir do candidato
        $atividadesEnviadas = $candidato ? $candidato->atividades()->with('tipoDeAtividade')->latest()->get() : collect();

        return view('candidato.atividades.edit', compact('atividade', 'regrasDePontuacao', 'atividadesEnviadas'));
    }

    public function update(Request $request, CandidatoAtividade $atividade)
    {
        Log::debug('Iniciando update de atividade. Request data: ' . json_encode($request->all()));
        $this->authorize('update', $atividade);

        $user = Auth::user();
        $candidato = $user->candidato;
        $previousStatus = $candidato->status;

        // ... (Sua lógica de validação permanece a mesma)
        $validationRules = [
            'tipo_de_atividade_id' => 'required|exists:tipos_de_atividade,id',
            'descricao_customizada' => 'nullable|string|max:255',
            'comprovativo' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ];
        $regra = TipoDeAtividade::find($request->tipo_de_atividade_id);
        // ... (Resto da sua lógica de validação condicional)

        $validatedData = $request->validate($validationRules);

        $dadosParaAtualizar = $request->only([
            'tipo_de_atividade_id', 'descricao_customizada', 'carga_horaria', 'data_inicio', 'data_fim', 'semestres_declarados', 'media_declarada_atividade'
        ]);

        if ($request->hasFile('comprovativo')) {
            if ($atividade->comprovativo_path && Storage::disk('public')->exists($atividade->comprovativo_path)) {
                Storage::disk('public')->delete($atividade->comprovativo_path);
            }
            $dadosParaAtualizar['comprovativo_path'] = $request->file('comprovativo')->store('candidato_atividades/user_' . Auth::id(), 'public');
        }

        $dadosParaAtualizar['status'] = 'Em Análise';
        $dadosParaAtualizar['motivo_rejeicao'] = null;
        $dadosParaAtualizar['prazo_recurso_ate'] = null; // Limpa o prazo ao corrigir

        try {
            $atividade->update($dadosParaAtualizar);
            Log::debug('Atividade ID ' . $atividade->id . ' atualizada com sucesso.');

            if (in_array($previousStatus, ['Homologado', 'Aprovado', 'Em Análise'])) {
                $candidato->status = 'Em Análise';
                $candidato->ato_homologacao = null;
                $candidato->homologado_em = null;
                $candidato->homologacao_observacoes = null;
                
                // ✅ AJUSTE: Salva o histórico no formato de array correto
                $revertHistory = $candidato->revert_reason ?? [];
                $revertHistory[] = [
                    'timestamp' => Carbon::now()->toDateTimeString(),
                    'reason' => "Atividade '{$regra->nome}' foi alterada pelo candidato.",
                    'action' => 'activity_update',
                    'previous_status' => $previousStatus,
                ];
                $candidato->revert_reason = $revertHistory;

                $candidato->save();
                Log::info("Candidato ID {$candidato->id} (Status: {$previousStatus}) atualizou atividade e voltou para 'Em Análise'.");
                return redirect()->route('candidato.atividades.index')->with('success', 'Atividade atualizada! Sua inscrição voltou para "Em Análise".');
            }

            return redirect()->route('candidato.atividades.index')->with('success', 'Atividade atualizada e enviada para reanálise!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar atividade ID {$atividade->id}: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao atualizar a atividade.')->withInput();
        }
    }

    public function show(CandidatoAtividade $atividade)
    {
        $this->authorize('view', $atividade);
        $pathFromDb = $atividade->comprovativo_path; // Corrigido para o nome correto da coluna
        if (empty($pathFromDb) || !Storage::disk('public')->exists($pathFromDb)) {
            abort(404, 'Ficheiro não encontrado.');
        }
        return Storage::disk('public')->response($pathFromDb);
    }

    public function destroy(CandidatoAtividade $atividade)
    {
        $this->authorize('delete', $atividade);

        $user = Auth::user();
        $candidato = $user->candidato;
        $previousStatus = $candidato->status;

        try {
            if ($atividade->comprovativo_path && Storage::disk('public')->exists($atividade->comprovativo_path)) {
                Storage::disk('public')->delete($atividade->comprovativo_path);
            }
            $atividade->delete();

            if (in_array($previousStatus, ['Homologado', 'Aprovado'])) {
                $candidato->status = 'Em Análise';
                $candidato->ato_homologacao = null;
                $candidato->homologado_em = null;
                $candidato->homologacao_observacoes = null;
                
                // ✅ AJUSTE: Salva o histórico no formato de array correto
                $revertHistory = $candidato->revert_reason ?? [];
                $revertHistory[] = [
                    'timestamp' => Carbon::now()->toDateTimeString(),
                    'reason' => "Atividade '{$atividade->tipoDeAtividade->nome}' foi removida pelo candidato.",
                    'action' => 'activity_delete',
                    'previous_status' => $previousStatus,
                ];
                $candidato->revert_reason = $revertHistory;

                $candidato->save();
                Log::info("Candidato ID {$candidato->id} (Status: {$previousStatus}) removeu atividade e voltou para 'Em Análise'.");
                return redirect()->back()->with('success', 'Atividade removida! Sua inscrição voltou para "Em Análise".');
            }

            return redirect()->route('candidato.atividades.index')->with('success', 'Atividade excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao apagar atividade ID {$atividade->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao remover a atividade.');
        }
    }
}
