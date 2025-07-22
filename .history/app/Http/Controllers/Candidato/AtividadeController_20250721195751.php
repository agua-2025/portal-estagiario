<?php

namespace App\Http\Controllers\Candidato;

use App\Http\Controllers\Controller;
use App\Models\CandidatoAtividade;
use App\Models\TipoDeAtividade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ✅ CORRIGIDO: Sintaxe do namespace
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log; // ✅ CORRIGIDO: Sintaxe do namespace
use App\Models\Candidato; // Importar o modelo Candidato
use App\Models\User;     // Importar o modelo User para acessar o candidato



class AtividadeController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        $regrasDePontuacao = TipoDeAtividade::all();
        $atividadesEnviadas = $user->candidatoAtividades()->with('tipoDeAtividade')->latest()->get();
        return view('candidato.atividades.index', compact('regrasDePontuacao', 'atividadesEnviadas'));
    }

    public function store(Request $request)
    {
        Log::debug('Iniciando store de atividade. Request data: ' . json_encode($request->all()));

        $user = Auth::user();
        $candidato = $user->candidato; // Pega o modelo Candidato associado ao usuário
        $previousStatus = $candidato->status; // Guarda o status anterior do candidato

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
            'tipo_de_atividade_id' => $request->tipo_de_atividade_id,
            'descricao_customizada' => $request->descricao_customizada,
            'status' => 'Em Análise', // Atividade individual é enviada para análise
            'carga_horaria' => $request->input('carga_horaria'),
            'data_inicio' => $request->input('data_inicio'),
            'data_fim' => $request->input('data_fim'),
            'semestres_declarados' => $request->input('semestres_declarados'),
            'media_declarada_atividade' => $request->input('media_declarada_atividade'),
        ];
        
        $path = $request->file('comprovativo')->store('candidato_atividades/user_' . $user->id, 'public');
        $dadosParaSalvar['path'] = $path; // Adiciona o path ao array de dados

        try {
            $user->candidatoAtividades()->create($dadosParaSalvar);
            Log::debug('Atividade criada com sucesso. Dados: ' . json_encode($dadosParaSalvar));

            // ✅ LÓGICA DE REVERSÃO DE STATUS DO CANDIDATO GERAL APÓS ADIÇÃO DE ATIVIDADE
            if ($previousStatus === 'Homologado' || $previousStatus === 'Aprovado' || $previousStatus === 'Em Análise') {
                $candidato->status = 'Em Análise'; // Volta para "Em Análise"
                // Limpa os campos de homologação
                $candidato->ato_homologacao = null;
                $candidato->homologado_em = null;
                $candidato->homologacao_observacoes = null;
                // Registra o motivo da reversão
                $candidato->revert_reason = "Atividade '{$regra->nome}' adicionada pelo candidato."; // Motivo da reversão
                $candidato->save();
                Log::info("Candidato ID {$candidato->id} (Status: {$previousStatus}) adicionou atividade e voltou para 'Em Análise'. Motivo: {$candidato->revert_reason}");
                return redirect()->route('candidato.atividades.index')->with('success', 'Atividade adicionada com sucesso! Sua inscrição (anteriormente ' . $previousStatus . ') voltou para "Em Análise" devido à alteração.');
            }

            return redirect()->route('candidato.atividades.index')->with('success', 'Atividade adicionada com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao criar atividade: " . $e->getMessage() . " Dados: " . json_encode($request->all()));
            return redirect()->back()->with('error', 'Ocorreu um erro ao adicionar a atividade. Por favor, tente novamente. Detalhes: ' . $e->getMessage());
        }
    }

    public function edit(CandidatoAtividade $atividade)
    {
        $this->authorize('update', $atividade);

        $regrasDePontuacao = TipoDeAtividade::all();
        $user = Auth::user();
        $atividadesEnviadas = $user->candidatoAtividades()->with('tipoDeAtividade')->latest()->get();

        return view('candidato.atividades.edit', compact('atividade', 'regrasDePontuacao', 'atividadesEnviadas'));
    }

    public function update(Request $request, CandidatoAtividade $atividade)
    {
        Log::debug('Iniciando update de atividade. Request data: ' . json_encode($request->all()));

        $this->authorize('update', $atividade);

        $user = Auth::user();
        $candidato = $user->candidato; // Pega o modelo Candidato associado ao usuário
        $previousStatus = $candidato->status; // Guarda o status anterior do candidato

        // Validação base para campos comuns
        $validationRules = [
            'tipo_de_atividade_id' => 'required|exists:tipos_de_atividade,id',
            'descricao_customizada' => 'nullable|string|max:255',
            'comprovativo' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048', // Nullable para update
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

        $dadosParaAtualizar = [
            'tipo_de_atividade_id' => $request->tipo_de_atividade_id,
            'descricao_customizada' => $request->descricao_customizada,
            'carga_horaria' => $request->input('carga_horaria'),
            'data_inicio' => $request->input('data_inicio'),
            'data_fim' => $request->input('data_fim'),
            'semestres_declarados' => $request->input('semestres_declarados'),
            'media_declarada_atividade' => $request->input('media_declarada_atividade'),
        ];

        // Lida com o upload do comprovativo
        if ($request->hasFile('comprovativo')) {
            if ($atividade->path && Storage::disk('public')->exists($atividade->path)) {
                Storage::disk('public')->delete($atividade->path);
                Log::debug('Comprovativo antigo apagado: ' . $atividade->path);
            }
            $dadosParaAtualizar['path'] = $request->file('comprovativo')->store('candidato_atividades/user_' . Auth::id(), 'public');
            Log::debug('Novo comprovativo salvo: ' . $dadosParaAtualizar['path']);
        }

        // Define o status para 'Em Análise' e zera o motivo de rejeição para a atividade individual
        $dadosParaAtualizar['status'] = 'Em Análise';
        $dadosParaAtualizar['motivo_rejeicao'] = null; // Limpa motivo de rejeição ao atualizar/submeter
        $dadosParaAtualizar['rejected_at'] = null; // Limpa data de rejeição ao atualizar/submeter

        try {
            $atividade->update($dadosParaAtualizar);
            Log::debug('Atividade ID ' . $atividade->id . ' atualizada com sucesso.');

            // ✅ LÓGICA DE REVERSÃO DE STATUS DO CANDIDATO GERAL
            if ($previousStatus === 'Homologado' || $previousStatus === 'Aprovado' || $previousStatus === 'Em Análise') {
                $candidato->status = 'Em Análise'; // Volta para "Em Análise"
                // Limpa os campos de homologação
                $candidato->ato_homologacao = null;
                $candidato->homologado_em = null;
                $candidato->homologacao_observacoes = null;
                // Registra o motivo da reversão
                $candidato->revert_reason = "Atividade '{$regra->nome}' adicionada/alterada pelo candidato."; // Motivo da reversão
                $candidato->save();
                Log::info("Candidato ID {$candidato->id} (Status: {$previousStatus}) atualizou atividade e voltou para 'Em Análise'. Motivo: {$candidato->revert_reason}");
                return redirect()->route('candidato.atividades.index')->with('success', 'Atividade atualizada com sucesso! Sua inscrição (anteriormente ' . $previousStatus . ') voltou para "Em Análise" devido à alteração.');
            }

            return redirect()->route('candidato.atividades.index')->with('success', 'Atividade atualizada e enviada para reanálise!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar atividade ID {$atividade->id}: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao atualizar a atividade. Por favor, tente novamente. Detalhes: ' . $e->getMessage())->withInput();
        }
    }

    public function show(CandidatoAtividade $atividade)
    {
        $this->authorize('view', $atividade);

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

    /**
     * Remove uma atividade da base de dados e do armazenamento.
     */
    public function destroy(CandidatoAtividade $atividade)
    {
        $this->authorize('delete', $atividade);

        $user = Auth::user();
        $candidato = $user->candidato; // Pega o modelo Candidato associado ao usuário
        $previousStatus = $candidato->status; // Guarda o status anterior

        try {
            // Apaga o ficheiro do armazenamento
            if ($atividade->path && Storage::disk('public')->exists($atividade->path)) {
                Storage::disk('public')->delete($atividade->path);
                Log::debug('Comprovativo da atividade ID ' . $atividade->id . ' apagado: ' . $atividade->path);
            }

            // Apaga o registo da atividade do banco de dados
            $atividade->delete();
            Log::debug('Atividade ID ' . $atividade->id . ' excluída com sucesso.');

            // ✅ LÓGICA DE REVERSÃO DE STATUS DO CANDIDATO GERAL APÓS EXCLUSÃO DE ATIVIDADE
            // Se o candidato estava Homologado ou Aprovado e removeu uma atividade
            if ($previousStatus === 'Homologado' || $previousStatus === 'Aprovado') {
                $candidato->status = 'Em Análise'; // Volta para "Em Análise"
                // Limpa os campos de homologação
                $candidato->ato_homologacao = null;
                $candidato->homologado_em = null;
                $candidato->homologacao_observacoes = null;
                // Registra o motivo da reversão
                $candidato->revert_reason = "Atividade '{$atividade->tipoDeAtividade->nome}' removida pelo candidato (status anterior: {$previousStatus})."; // Motivo da reversão
                $candidato->save();
                Log::info("Candidato ID {$candidato->id} (Status: {$previousStatus}) removeu atividade e voltou para 'Em Análise'. Motivo: {$candidato->revert_reason}");
                return redirect()->back()->with('success', 'Atividade removida com sucesso! Sua inscrição (anteriormente homologada/aprovada) voltou para "Em Análise" devido à remoção de uma atividade.');
            }
            // Se o candidato estava Em Análise e removeu uma atividade, o status permanece Em Análise ou pode ser reavaliado.
            // Por simplicidade, se não era Homologado/Aprovado, o status não muda aqui.

            return redirect()->route('candidato.atividades.index')->with('success', 'Atividade excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao apagar atividade ID {$atividade->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao remover a atividade. Por favor, tente novamente.');
        }
    }
}
