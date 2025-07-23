<?php

namespace App\Http\Controllers\Candidato;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidato;
use App\Models\Instituicao;
use App\Models\Curso;
use App\Models\Estado;
use App\Models\Cidade;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Mostra o formulário para o candidato editar seu perfil.
     */
    public function edit(Request $request)
    {
        $candidato = $request->user()->candidato()->firstOrNew([
            'user_id' => Auth::id()
        ]);

        if (!$candidato->exists) {
            $candidato->nome_completo = $request->user()->name;
        }
        
        $instituicoes = Instituicao::orderBy('nome')->get();
        $cursos = Curso::orderBy('nome')->get();
        $estados = Estado::orderBy('nome')->get();
        
        // Carrega todas as cidades para que o JavaScript do dropdown dinâmico funcione.
        $cidades = Cidade::orderBy('nome')->get(); 

        $completableFields = Candidato::getCompletableFields();

        $frontendFields = array_merge($completableFields, [
            'nome_pai', 
            'rg', 
            'rg_orgao_expedidor', 
        ]);

        return view('candidato.profile.edit', [
            'candidato' => $candidato,
            'instituicoes' => $instituicoes,
            'cursos' => $cursos,
            'profileFields' => $frontendFields, 
            'estados' => $estados,
            'cidades' => $cidades,
            'totalProfileFieldsCount' => count($completableFields), 
        ]);
    }

    /**
     * Atualiza o perfil do candidato no banco de dados.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $candidatoId = $user->candidato->id ?? null;

        // 1. Valida os dados que chegam do formulário
        $validatedData = $request->validate([
            'nome_completo' => 'required|string|max:255',
            'nome_mae' => 'required|string|max:255',
            'nome_pai' => 'nullable|string|max:255',
            'data_nascimento' => 'required|date',
            'sexo' => 'required|string',
            'cpf' => ['required', 'string', 'max:14', Rule::unique('candidatos')->ignore($candidatoId)], 
            'rg' => ['nullable', 'string', 'max:20'],
            'rg_orgao_expedidor' => 'nullable|string|max:255',
            'naturalidade_estado' => 'required|exists:estados,id',
            'naturalidade_cidade' => 'required|string|max:255', // <-- AQUI: Se o frontend envia ID, deveria ser 'integer'
            'possui_deficiencia' => 'required|boolean',
            'telefone' => 'required|string|max:20',
            'cep' => 'required|string|max:9',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'bairro' => 'required|string|max:255',
            'estado' => 'required|exists:estados,id', 
            
            // ✅ CORREÇÃO: A regra de validação agora verifica se a cidade pertence ao estado selecionado.
            // Esta é a regra que espera um INTEGER.
            'cidade' => [
                'required',
                'integer', 
                Rule::exists('cidades', 'id')->where('estado_id', $request->input('estado'))
            ],
            
            'curso_id' => 'required|exists:cursos,id',
            'instituicao_id' => 'required|exists:instituicoes,id', 
            'curso_data_inicio' => 'required|date',
            'curso_previsao_conclusao' => 'required|date|after:curso_data_inicio',
            'media_aproveitamento' => 'required|numeric|between:0,10.00',
            'semestres_completos' => 'required|integer|min:0',
        ], [
            // Mensagem de erro personalizada para a nova regra
            'cidade.exists' => 'A cidade selecionada não é válida para o estado escolhido. Por favor, selecione o estado novamente.'
        ]);
        
        DB::beginTransaction();
        try {
            // 2. Prepara os dados para salvar, fazendo o mapeamento correto
            $dataToSave = $validatedData;

            // Mapeia os IDs recebidos para as colunas de ID corretas
            $dataToSave['estado_id'] = $validatedData['estado'];
            $dataToSave['cidade_id'] = $validatedData['cidade'];
            
            // Busca os nomes de Estado e Cidade para salvar nos campos de texto
            $estadoObj = Estado::find($validatedData['estado']);
            $cidadeObj = Cidade::find($validatedData['cidade']);
            if ($estadoObj) $dataToSave['estado'] = $estadoObj->nome;
            if ($cidadeObj) $dataToSave['cidade'] = $cidadeObj->nome;
            
            // 3. Usa updateOrCreate com os dados já preparados e corretos.
            $candidato = $user->candidato()->updateOrCreate(
                ['user_id' => $user->id],
                $dataToSave
            );

            // O resto da sua lógica original...
            $previousStatus = $candidato->getOriginal('status', 'Inscrição Incompleta');
            if (in_array($previousStatus, ['Homologado', 'Aprovado', 'Em Análise'])) {
                $candidato->status = 'Em Análise'; 
                $candidato->ato_homologacao = null;
                $candidato->homologado_em = null;
                $candidato->homologacao_observacoes = null;
                // ... (lógica de histórico)
            }
            if ($candidato->wasRecentlyCreated) {
                $candidato->status = 'Inscrição Incompleta';
            }

            $candidato->save();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            // ✅ ADICIONADO: Log mais detalhado para depuração
            Log::error("Erro na transação ao atualizar perfil do candidato ID {$user->id}: " . $e->getMessage(), ['exception' => $e, 'request_data' => $request->all()]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao salvar seu perfil. Por favor, tente novamente.')->withInput();
        }

        return redirect()->route('dashboard')->with('success', 'Seu perfil foi salvo com sucesso!');
    }
}
