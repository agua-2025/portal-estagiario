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
use Illuminate\Support\Facades\DB;

class AtividadeController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        $candidato = $user->candidato;
        $regrasDePontuacao = TipoDeAtividade::all();
        
        // ✅ AJUSTE: Busca as atividades a partir da nova relação correta no candidato
        $atividadesEnviadas = $candidato ? $candidato->atividades()->with('tipoDeAtividade')->latest()->get() : collect();

        return view('candidato.atividades.index', compact('regrasDePontuacao', 'atividadesEnviadas'));
    }

public function store(Request $request)
{
    $this->authorize('create', CandidatoAtividade::class);

    Log::debug('Iniciando store de atividade. Request data: ' . json_encode($request->all()));

    $user = Auth::user();
    $candidato = $user?->candidato;

    // ✅ Blindagem: exige papel + candidato existente + perfil completo
    if (! $user || ! $user->hasRole('candidato') || ! $candidato || ! $candidato->isComplete()) {
        return redirect()
            ->route('candidato.profile.edit')
            ->with('warn', 'Complete seu perfil (dados obrigatórios) antes de enviar atividades.');
    }

    $previousStatus = $candidato->status;

    $validationRules = [
        'tipo_de_atividade_id' => 'required|exists:tipos_de_atividade,id',
        'descricao_customizada' => 'nullable|string|max:255',
        'comprovativo' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
    ];

    $regra = TipoDeAtividade::find($request->tipo_de_atividade_id);
    if (! $regra) {
        return redirect()->back()->with('error', 'Tipo de atividade inválido.')->withInput();
    }
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

    $dadosParaSalvar = $request->only([
        'tipo_de_atividade_id', 'descricao_customizada', 'carga_horaria', 'data_inicio', 'data_fim', 'semestres_declarados', 'media_declarada_atividade'
    ]);
    $dadosParaSalvar['user_id'] = $user->id;
    $dadosParaSalvar['status'] = 'Em Análise';
    $dadosParaSalvar['path'] = $request->file('comprovativo')->store('candidato_atividades/user_' . $user->id, 'public');

    DB::beginTransaction();
    try {
        // cria pela relação para setar candidato_id automaticamente
        $candidato->atividades()->create($dadosParaSalvar);
        Log::debug('Atividade criada com sucesso. Dados: ' . json_encode($dadosParaSalvar));

        if (in_array($previousStatus, ['Homologado', 'Aprovado', 'Em Análise'])) {
            $candidato->status = 'Em Análise';
            $candidato->homologado_em = null;
            $candidato->homologacao_observacoes = null;

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

            DB::commit();
            return redirect()->route('candidato.atividades.index')->with('success', 'Atividade adicionada! Sua inscrição voltou para "Em Análise".');
        }

        DB::commit();
        return redirect()->route('candidato.atividades.index')->with('success', 'Atividade adicionada com sucesso!');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Erro ao criar atividade: " . $e->getMessage() . " Dados: " . json_encode($request->all()));
        return redirect()->back()->with('error', 'Ocorreu um erro ao adicionar a atividade.')->withInput();
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
    $candidato = $user?->candidato;

    // ✅ Blindagem: exige papel + candidato existente + perfil completo
    if (! $user || ! $user->hasRole('candidato') || ! $candidato || ! $candidato->isComplete()) {
        return redirect()
            ->route('candidato.profile.edit')
            ->with('warn', 'Complete seu perfil antes de alterar atividades.');
    }

    $previousStatus = $candidato->status;

    $validationRules = [
        'tipo_de_atividade_id' => 'required|exists:tipos_de_atividade,id',
        'descricao_customizada' => 'nullable|string|max:255',
        'comprovativo' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
    ];

    $regra = TipoDeAtividade::find($request->tipo_de_atividade_id);
    if (! $regra) {
        return redirect()->back()->with('error', 'Tipo de atividade inválido.')->withInput();
    }
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
            }
            $dadosParaAtualizar['path'] = $request->file('comprovativo')->store('candidato_atividades/user_' . Auth::id(), 'public');
        }

        $dadosParaAtualizar['status'] = 'Em Análise';
        $dadosParaAtualizar['motivo_rejeicao'] = null;
        $dadosParaAtualizar['prazo_recurso_ate'] = null;

        $atividade->update($dadosParaAtualizar);

        if (in_array($previousStatus, ['Homologado', 'Aprovado', 'Em Análise'])) {
            $candidato->status = 'Em Análise';
            $candidato->homologado_em = null;
            $candidato->homologacao_observacoes = null;

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
        $pathFromDb = $atividade->path;
        if (empty($pathFromDb) || !Storage::disk('public')->exists($pathFromDb)) {
            abort(404, 'Ficheiro não encontrado.');
        }
        return Storage::disk('public')->response($pathFromDb);
    }

    public function destroy(CandidatoAtividade $atividade)
{
    $this->authorize('delete', $atividade);

    $user = Auth::user();
    $candidato = $user?->candidato;

    // ✅ Blindagem: exige papel + candidato existente + perfil completo
    if (! $user || ! $user->hasRole('candidato') || ! $candidato || ! $candidato->isComplete()) {
        return redirect()
            ->route('candidato.profile.edit')
            ->with('warn', 'Complete seu perfil antes de remover atividades.');
    }

    $previousStatus = $candidato->status;

    try {
        if ($atividade->path && Storage::disk('public')->exists($atividade->path)) {
            Storage::disk('public')->delete($atividade->path);
        }
        $atividade->delete();

        if (in_array($previousStatus, ['Homologado', 'Aprovado'])) {
            $candidato->status = 'Em Análise';
            $candidato->homologado_em = null;
            $candidato->homologacao_observacoes = null;

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
        }

        return redirect()->route('candidato.atividades.index')->with('success', 'Atividade excluída com sucesso!');
    } catch (\Exception $e) {
        Log::error("Erro ao apagar atividade ID {$atividade->id}: " . $e->getMessage());
        return redirect()->back()->with('error', 'Ocorreu um erro ao remover a atividade.');
    }
}
