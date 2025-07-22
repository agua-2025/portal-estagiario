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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // ✅ ADICIONADO: Essencial para transações

class AtividadeController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        $candidato = $user->candidato;
        $regrasDePontuacao = TipoDeAtividade::all();
        
        // ✅ AJUSTE: Busca as atividades a partir do candidato
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

        $validationRules = [
            'tipo_de_atividade_id' => 'required|exists:tipos_de_atividade,id',
            'descricao_customizada' => 'nullable|string|max:255',
            'comprovativo' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ];

        $regra = TipoDeAtividade::find($request->tipo_de_atividade_id);
        Log::debug('Regra de Tipo de Atividade encontrada: ' . json_encode($regra));

        $isSemestresRule = (strtolower($regra->nome) === 'número de semestres cursados' || $regra->unidade_medida === 'semestre');
        $isAproveitamentoAcademicoRule = (strtolower($regra->nome) === 'aproveitamento acadêmico');
        $isHorasRule = ($regra->unidade_medida === 'horas');
        $isMesesRule = ($regra->unidade_medida === 'meses');

        if ($isSemestresRule) {
            $validationRules['semestres_declarados'] = 'required|integer|min:1';
        } elseif ($isAproveitamentoAcademicoRule) {
            $validationRules['media_declarada_atividade'] = 'required|numeric|between:0,10.00';
        } elseif ($isHorasRule) {
            $validationRules['carga_horaria'] = 'required|integer|min:1';
        } elseif ($isMesesRule) {
            $validationRules['data_inicio'] = 'required|date';
            $validationRules['data_fim'] = 'required|date|after_or_equal:data_inicio';
        }

        $request->validate($validationRules);

        $dadosParaSalvar = [
            'user_id' => $user->id,
            'tipo_de_atividade_id' => $request->tipo_de_atividade_id,
            'descricao_customizada' => $request->descricao_customizada,
            'status' => 'Em Análise',
            'carga_horaria' => $request->input('carga_horaria'),
            'data_inicio' => $request->input('data_inicio'),
            'data_fim' => $request->input('data_fim'),
            'semestres_declarados' => $request->input('semestres_declarados'),
            'media_declarada_atividade' => $request->input('media_declarada_atividade'),
        ];
        
        // ✅ CORRIGIDO: O nome da coluna no seu Model é 'path', não 'comprovativo_path'
        $path = $request->file('comprovativo')->store('candidato_atividades/user_' . $user->id, 'public');
        $dadosParaSalvar['path'] = $path;

        DB::beginTransaction();
        try {
            // ✅ AJUSTE: Cria a atividade diretamente na relação do candidato
            $candidato->atividades()->create($dadosParaSalvar);
            Log::debug('Atividade criada com sucesso. Dados: ' . json_encode($dadosParaSalvar));

            if (in_array($previousStatus, ['Homologado', 'Aprovado', 'Em Análise'])) {
                $candidato->status = 'Em Análise';
                $candidato->ato_homologacao = null;
                $candidato->homologado_em = null;
                $candidato->homologacao_observacoes = null;
                
                // ✅ AJUSTE: Salva o histórico no formato de array correto
                $revertHistory = $candidato->revert_reason ?? [];
                if (!is_array($revertHistory)) { $revertHistory = []; }
                $revertHistory[] = [
                    'timestamp' => Carbon::now()->toDateTimeString(),
                    'reason' => "Atividade '{$regra->nome}' adicionada pelo candidato.",
                    'action' => 'activity_create',
                    'previous_status' => $previousStatus,
                ];
                $candidato->revert_reason = $revertHistory;
                
                $candidato->save();
                Log::info("Candidato ID {$candidato->id} (Status: {$previousStatus}) adicionou atividade e voltou para 'Em Análise'.");
            }
            
            DB::commit();
            return redirect()->route('candidato.atividades.index')->with('success', 'Atividade adicionada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao criar atividade: " . $e->getMessage() . " Dados: " . json_encode($request->all()));
            return redirect()->back()->with('error', 'Ocorreu um erro ao adicionar a atividade. Por favor, tente novamente. Detalhes: ' . $e->getMessage());
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

        $validationRules = [
            'tipo_de_atividade_id' => 'required|exists:tipos_de_atividade,id',
            'descricao_customizada' => 'nullable|string|max:255',
            'comprovativo' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ];

        $regra = TipoDeAtividade::find($request->tipo_de_atividade_id);
        Log::debug('Regra de Tipo de Atividade encontrada: ' . json_encode($regra));

        $isSemestresRule = (strtolower($regra->nome) === 'número de semestres cursados' || $regra->unidade_medida === 'semestre');
        $isAproveitamentoAcademicoRule = (strtolower($regra->nome) === 'aproveitamento acadêmico');
        $isHorasRule = ($regra->unidade_medida === 'horas');
        $isMesesRule = ($regra->unidade_medida === 'meses');

        if ($isSemestresRule) {
            $validationRules['semestres_declarados'] = 'required|integer|min:1';
        } elseif ($isAproveitamentoAcademicoRule) {
            $validationRules['media_declarada_atividade'] = 'required|numeric|between:0,10.00';
        } elseif ($isHorasRule) {
            $validationRules['carga_horaria'] = 'required|integer|min:1';
        } elseif ($isMesesRule) {
            $validationRules['data_inicio'] = 'required|date';
            $validationRules['data_fim'] = 'required|date|after_or_equal:data_inicio';
        }

        $request->validate($validationRules);

        $dadosParaAtualizar = $request->only([
            'tipo_de_atividade_id', 'descricao_customizada', 'carga_horaria', 'data_inicio', 'data_fim', 'semestres_declarados', 'media_declarada_atividade'
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('comprovativo')) {
                if ($atividade->path && Storage::disk('public')->exists($atividade->path)) {
                    Storage::disk('public')->delete($atividade->path);
                    Log::debug('Comprovativo antigo apagado: ' . $atividade->path);
                }
                // ✅ CORRIGIDO: Usa o nome de coluna correto 'path'
                $dadosParaAtualizar['path'] = $request->file('comprovativo')->store('candidato_atividades/user_' . Auth::id(), 'public');
                Log::debug('Novo comprovativo salvo: ' . $dadosParaAtualizar['path']);
            }

            $dadosParaAtualizar['status'] = 'Em Análise';
            $dadosParaAtualizar['motivo_rejeicao'] = null;
            $dadosParaAtualizar['prazo_recurso_ate'] = null;

            $atividade->update($dadosParaAtualizar);
            Log::debug('Atividade ID ' . $atividade->id . ' atualizada com sucesso.');

            if (in_array($previousStatus, ['Homologado', 'Aprovado', 'Em Análise'])) {
                $candidato->status = 'Em Análise';
                $candidato->ato_homologacao = null;
                $candidato->homologado_em = null;
                $candidato->homologacao_observacoes = null;
                
                // ✅ AJUSTE: Salva o histórico no formato de array correto
                $revertHistory = $candidato->revert_reason ?? [];
                if (!is_array($revertHistory)) { $revertHistory = []; }
                $revertHistory[] = [
                    'timestamp' => Carbon::now()->toDateTimeString(),
                    'reason' => "Atividade '{$regra->nome}' foi alterada pelo candidato.",
                    'action' => 'activity_update',
                    'previous_status' => $previousStatus,
                ];
                $candidato->revert_reason = $revertHistory;
                
                $candidato->save();
                Log::info("Candidato ID {$candidato->id} (Status: {$previousStatus}) atualizou atividade e voltou para 'Em Análise'.");
            }
            
            DB::commit();
            return redirect()->route('candidato.atividades.index')->with('success', 'Atividade atualizada e enviada para reanálise!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao atualizar atividade ID {$atividade->id}: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao atualizar a atividade.')->withInput();
        }
    }

    public function show(CandidatoAtividade $atividade)
    {
        $this->authorize('view', $atividade);

        // ✅ CORRIGIDO: Usa o nome de coluna correto 'path'
        $pathFromDb = $atividade->path;

        if (empty($pathFromDb)) {
            Log::warning("Documento ID {$atividade->id} tem caminho nulo ou vazio no banco de dados.");
            abort(404, 'Arquivo não encontrado ou caminho inválido.');
        }

        if (Storage::disk('public')->exists($pathFromDb)) {
            return Storage::disk('public')->response($pathFromDb);
        }

        $cleanedPath = str_replace('public/', '', $pathFromDb);
        if (Storage::disk('public')->exists($cleanedPath)) {
            return Storage::disk('public')->response($cleanedPath);
        }
        
        abort(404, 'Ficheiro não encontrado no armazenamento após todas as verificações.');
    }

    public function destroy(CandidatoAtividade $atividade)
    {
        $this->authorize('delete', $atividade);

        $user = Auth::user();
        $candidato = $user->candidato;
        $previousStatus = $candidato->status;

        try {
            // ✅ CORRIGIDO: Usa o nome de coluna correto 'path'
            if ($atividade->path && Storage::disk('public')->exists($atividade->path)) {
                Storage::disk('public')->delete($atividade->path);
                Log::debug('Comprovativo da atividade ID ' . $atividade->id . ' apagado: ' . $atividade->path);
            }

            $atividade->delete();
            Log::debug('Atividade ID ' . $atividade->id . ' excluída com sucesso.');

            if (in_array($previousStatus, ['Homologado', 'Aprovado'])) {
                $candidato->status = 'Em Análise';
                $candidato->ato_homologacao = null;
                $candidato->homologado_em = null;
                $candidato->homologacao_observacoes = null;
                
                // ✅ AJUSTE: Salva o histórico no formato de array correto
                $revertHistory = $candidato->revert_reason ?? [];
                if (!is_array($revertHistory)) { $revertHistory = []; }
                $revertHistory[] = [
                    'timestamp' => Carbon::now()->toDateTimeString(),
                    'reason' => "Atividade '{$atividade->tipoDeAtividade->nome}' removida pelo candidato (status anterior: {$previousStatus}).",
                    'action' => 'activity_delete',
                    'previous_status' => $previousStatus,
                ];
                $candidato->revert_reason = $revertHistory;

                $candidato->save();
                Log::info("Candidato ID {$candidato->id} (Status: {$previousStatus}) removeu atividade e voltou para 'Em Análise'.");
                return redirect()->back()->with('success', 'Atividade removida com sucesso! Sua inscrição (anteriormente homologada/aprovada) voltou para "Em Análise" devido à remoção de uma atividade.');
            }

            return redirect()->route('candidato.atividades.index')->with('success', 'Atividade excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao apagar atividade ID {$atividade->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao remover a atividade.');
        }
    }
}