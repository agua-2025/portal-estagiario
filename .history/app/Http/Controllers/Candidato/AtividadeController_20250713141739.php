<?php

namespace App\Http\Controllers\Candidato;

use App\Http\Controllers\Controller;
use App\Models\CandidatoAtividade;
use App\Models\TipoDeAtividade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log; // Adicionado para logs de depuração

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

        // ✅ AJUSTE: Validação base para campos comuns
        $validationRules = [
            'tipo_de_atividade_id' => 'required|exists:tipos_de_atividade,id',
            'descricao_customizada' => 'nullable|string|max:255', // Descrição agora é nullable
            'comprovativo' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ];

        $regra = TipoDeAtividade::find($request->tipo_de_atividade_id);

        Log::debug('Regra de Tipo de Atividade encontrada: ' . json_encode($regra));

        // ✅ AJUSTE CRÍTICO: Validação CONDICIONAL de campos específicos
        $isSemestresRule = (strtolower($regra->nome) === 'número de semestres cursados' || $regra->unidade_medida === 'semestre');
        $isAproveitamentoAcademicoRule = (strtolower($regra->nome) === 'aproveitamento acadêmico'); // ✅ NOVO: Condição para Aproveitamento Acadêmico
        $isHorasRule = ($regra->unidade_medida === 'horas');
        $isMesesRule = ($regra->unidade_medida === 'meses');

        Log::debug('É regra de semestres cursados (condição): ' . ($isSemestresRule ? 'true' : 'false'));
        Log::debug('É regra de aproveitamento acadêmico (condição): ' . ($isAproveitamentoAcademicoRule ? 'true' : 'false')); // ✅ NOVO Log
        Log::debug('É regra de horas (condição): ' . ($isHorasRule ? 'true' : 'false'));
        Log::debug('É regra de meses (condição): ' . ($isMesesRule ? 'true' : 'false'));


        if ($isSemestresRule) {
            $validationRules['semestres_declarados'] = 'required|integer|min:1';
            $validationRules['carga_horaria'] = 'nullable';
            $validationRules['data_inicio'] = 'nullable';
            $validationRules['data_fim'] = 'nullable';
            $validationRules['media_declarada_atividade'] = 'nullable'; // ✅ NOVO: Torna nullable para semestres
            Log::debug('Validação "required" para semestres_declarados aplicada.');
        } elseif ($isAproveitamentoAcademicoRule) { // ✅ NOVO: Bloco para Aproveitamento Acadêmico
            $validationRules['media_declarada_atividade'] = 'required|numeric|between:0,10.00';
            $validationRules['carga_horaria'] = 'nullable';
            $validationRules['data_inicio'] = 'nullable';
            $validationRules['data_fim'] = 'nullable';
            $validationRules['semestres_declarados'] = 'nullable'; // Torna nullable para aproveitamento
            Log::debug('Validação "required" para media_declarada_atividade aplicada.');
        } elseif ($isHorasRule) {
            $validationRules['carga_horaria'] = 'required|integer|min:1';
            $validationRules['semestres_declarados'] = 'nullable';
            $validationRules['data_inicio'] = 'nullable';
            $validationRules['data_fim'] = 'nullable';
            $validationRules['media_declarada_atividade'] = 'nullable'; // ✅ NOVO: Torna nullable para horas
            Log::debug('Validação "required" para carga_horaria aplicada.');
        } elseif ($isMesesRule) {
            $validationRules['data_inicio'] = 'required|date';
            $validationRules['data_fim'] = 'required|date|after_or_equal:data_inicio';
            $validationRules['carga_horaria'] = 'nullable';
            $validationRules['semestres_declarados'] = 'nullable';
            $validationRules['media_declarada_atividade'] = 'nullable'; // ✅ NOVO: Torna nullable para meses
            Log::debug('Validação "required" para datas aplicada.');
        } else { // Para 'fixo' ou outros tipos que não usam esses campos
            $validationRules['carga_horaria'] = 'nullable';
            $validationRules['data_inicio'] = 'nullable';
            $validationRules['data_fim'] = 'nullable';
            $validationRules['semestres_declarados'] = 'nullable';
            $validationRules['media_declarada_atividade'] = 'nullable'; // ✅ NOVO: Torna nullable para fixo
            Log::debug('Nenhuma validação específica de carga/data/semestres/media aplicada (regra fixa ou outra), campos tornados nullable.');
        }

        // Executa a validação com as regras construídas
        $validatedData = $request->validate($validationRules);

        // Inicializa os dados para salvar, incluindo os campos que podem ser nulos
        $dadosParaSalvar = [
            'tipo_de_atividade_id' => $request->tipo_de_atividade_id,
            'descricao_customizada' => $request->descricao_customizada,
            'status' => 'Em Análise',
            'carga_horaria' => $request->input('carga_horaria'),
            'data_inicio' => $request->input('data_inicio'),
            'data_fim' => $request->input('data_fim'),
            'semestres_declarados' => $request->input('semestres_declarados'),
            'media_declarada_atividade' => $request->input('media_declarada_atividade'), // ✅ NOVO: Inclui a média declarada da atividade
        ];

        $user = Auth::user();
        
        $path = $request->file('comprovativo')->store('candidato_atividades/user_' . $user->id, 'public');
        $dadosParaSalvar['path'] = $path; // Adiciona o path ao array de dados

        $user->candidatoAtividades()->create($dadosParaSalvar);
        Log::debug('Atividade criada com sucesso. Dados: ' . json_encode($dadosParaSalvar));

        return redirect()->route('candidato.atividades.index')->with('success', 'Atividade adicionada com sucesso!');
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

        // ✅ AJUSTE: Validação base para campos comuns
        $validationRules = [
            'tipo_de_atividade_id' => 'required|exists:tipos_de_atividade,id',
            'descricao_customizada' => 'nullable|string|max:255', // Descrição agora é nullable
            'comprovativo' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ];

        $regra = TipoDeAtividade::find($request->tipo_de_atividade_id);

        Log::debug('Regra de Tipo de Atividade encontrada: ' . json_encode($regra));

        // ✅ AJUSTE CRÍTICO: Validação CONDICIONAL de campos específicos
        $isSemestresRule = (strtolower($regra->nome) === 'número de semestres cursados' || $regra->unidade_medida === 'semestre');
        $isAproveitamentoAcademicoRule = (strtolower($regra->nome) === 'aproveitamento acadêmico'); // ✅ NOVO: Condição para Aproveitamento Acadêmico
        $isHorasRule = ($regra->unidade_medida === 'horas');
        $isMesesRule = ($regra->unidade_medida === 'meses');

        Log::debug('É regra de semestres cursados (condição): ' . ($isSemestresRule ? 'true' : 'false'));
        Log::debug('É regra de aproveitamento acadêmico (condição): ' . ($isAproveitamentoAcademicoRule ? 'true' : 'false')); // ✅ NOVO Log
        Log::debug('É regra de horas (condição): ' . ($isHorasRule ? 'true' : 'false'));
        Log::debug('É regra de meses (condição): ' . ($isMesesRule ? 'true' : 'false'));

        if ($isSemestresRule) {
            $validationRules['semestres_declarados'] = 'required|integer|min:1';
            $validationRules['carga_horaria'] = 'nullable';
            $validationRules['data_inicio'] = 'nullable';
            $validationRules['data_fim'] = 'nullable';
            $validationRules['media_declarada_atividade'] = 'nullable'; // ✅ NOVO: Torna nullable para semestres
            Log::debug('Validação "required" para semestres_declarados aplicada.');
        } elseif ($isAproveitamentoAcademicoRule) { // ✅ NOVO: Bloco para Aproveitamento Acadêmico
            $validationRules['media_declarada_atividade'] = 'required|numeric|between:0,10.00';
            $validationRules['carga_horaria'] = 'nullable';
            $validationRules['data_inicio'] = 'nullable';
            $validationRules['data_fim'] = 'nullable';
            $validationRules['semestres_declarados'] = 'nullable';
            Log::debug('Validação "required" para media_declarada_atividade aplicada.');
        } elseif ($isHorasRule) {
            $validationRules['carga_horaria'] = 'required|integer|min:1';
            $validationRules['semestres_declarados'] = 'nullable';
            $validationRules['data_inicio'] = 'nullable';
            $validationRules['data_fim'] = 'nullable';
            $validationRules['media_declarada_atividade'] = 'nullable';
            Log::debug('Validação "required" para carga_horaria aplicada.');
        } elseif ($isMesesRule) {
            $validationRules['data_inicio'] = 'required|date';
            $validationRules['data_fim'] = 'required|date|after_or_equal:data_inicio';
            $validationRules['carga_horaria'] = 'nullable';
            $validationRules['semestres_declarados'] = 'nullable';
            $validationRules['media_declarada_atividade'] = 'nullable';
            Log::debug('Validação "required" para datas aplicada.');
        } else { // Para 'fixo' ou outros tipos que não usam esses campos
            $validationRules['carga_horaria'] = 'nullable';
            $validationRules['data_inicio'] = 'nullable';
            $validationRules['data_fim'] = 'nullable';
            $validationRules['semestres_declarados'] = 'nullable';
            $validationRules['media_declarada_atividade'] = 'nullable';
            Log::debug('Nenhuma validação específica de carga/data/semestres/media aplicada (regra fixa ou outra), campos tornados nullable.');
        }

        // Executa a validação com as regras construídas
        $validatedData = $request->validate($validationRules);

        // Inicializa os dados para atualizar, incluindo os campos que podem ser nulos
        $dadosParaAtualizar = [
            'tipo_de_atividade_id' => $request->tipo_de_atividade_id,
            'descricao_customizada' => $request->descricao_customizada,
            'carga_horaria' => $request->input('carga_horaria'),
            'data_inicio' => $request->input('data_inicio'),
            'data_fim' => $request->input('data_fim'),
            'semestres_declarados' => $request->input('semestres_declarados'),
            'media_declarada_atividade' => $request->input('media_declarada_atividade'), // ✅ NOVO: Inclui a média declarada da atividade
        ];

        // Lida com o upload do comprovativo
        if ($request->hasFile('comprovativo')) {
            // Apaga o comprovativo antigo se existir
            if ($atividade->path && Storage::disk('public')->exists($atividade->path)) {
                Storage::disk('public')->delete($atividade->path);
                Log::debug('Comprovativo antigo apagado: ' . $atividade->path);
            }
            $dadosParaAtualizar['path'] = $request->file('comprovativo')->store('candidato_atividades/user_' . Auth::id(), 'public');
            Log::debug('Novo comprovativo salvo: ' . $dadosParaAtualizar['path']);
        }

        // Define o status para 'Em Análise' e zera o motivo de rejeição
        $dadosParaAtualizar['status'] = 'Em Análise';
        $dadosParaAtualizar['motivo_rejeicao'] = null;

        Log::debug('Dados para atualização: ' . json_encode($dadosParaAtualizar));
        $atividade->update($dadosParaAtualizar);
        Log::debug('Atividade ID ' . $atividade->id . ' atualizada com sucesso.');

        return redirect()->route('candidato.atividades.index')->with('success', 'Atividade atualizada e enviada para reanálise!');
    }

    public function show(CandidatoAtividade $atividade)
    {
        $this->authorize('view', $atividade);

        $pathFromDb = $atividade->path;

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
     * ✅ NOVO: Remove uma atividade da base de dados e do armazenamento.
     */
    public function destroy(CandidatoAtividade $atividade)
    {
        // Usa a policy para garantir que apenas o dono pode excluir
        $this->authorize('delete', $atividade);

        // Apaga o ficheiro do armazenamento
        if ($atividade->path && Storage::disk('public')->exists($atividade->path)) {
            Storage::disk('public')->delete($atividade->path);
            Log::debug('Comprovativo da atividade ID ' . $atividade->id . ' apagado: ' . $atividade->path);
        }

        // Apaga o registo da atividade do banco de dados
        $atividade->delete();
        Log::debug('Atividade ID ' . $atividade->id . ' excluída com sucesso.');

        return redirect()->route('candidato.atividades.index')->with('success', 'Atividade excluída com sucesso!');
    }
}
