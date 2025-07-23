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
        // ✅ AJUSTE: Usa firstOrNew para não criar um registo vazio no banco.
        $candidato = $request->user()->candidato()->firstOrNew([
            'user_id' => Auth::id()
        ]);

        // Se for um candidato novo (ainda não existe no banco), pré-preenche o nome.
        if (!$candidato->exists) {
            $candidato->nome_completo = $request->user()->name;
        }
        
        $instituicoes = Instituicao::orderBy('nome')->get();
        $cursos = Curso::orderBy('nome')->get();
        $estados = Estado::orderBy('nome')->get();
        $cidades = Cidade::all(); 

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

        $validatedData = $request->validate([
            'nome_completo' => 'required|string|max:255',
            'nome_mae' => 'required|string|max:255',
            'nome_pai' => 'nullable|string|max:255',
            'data_nascimento' => 'required|date',
            'sexo' => 'required|string',
            // ✅ AJUSTE: A validação do CPF agora funciona tanto para criar como para atualizar.
            'cpf' => ['required', 'string', 'max:14', Rule::unique('candidatos')->ignore($candidatoId)], 
            'rg' => ['nullable', 'string', 'max:20'],
            'rg_orgao_expedidor' => 'nullable|string|max:255',
            'naturalidade_estado' => 'required|exists:estados,id',
            'naturalidade_cidade' => 'required|string',
            'possui_deficiencia' => 'required|boolean',
            'telefone' => 'required|string|max:20',
            'cep' => 'required|string|max:9',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'bairro' => 'required|string|max:255',
            'estado' => 'required|exists:estados,id',
            'cidade' => 'required|string',
            'curso_id' => 'required|exists:cursos,id',
            'instituicao_id' => 'required|exists:instituicoes,id', 
            'curso_data_inicio' => 'required|date',
            'curso_previsao_conclusao' => 'required|date|after:curso_data_inicio',
            'media_aproveitamento' => 'required|numeric|between:0,10.00',
            'semestres_completos' => 'required|integer|min:0',
        ]);
        
        DB::beginTransaction();
        try {
            // ✅ AJUSTE: Usa updateOrCreate para criar ou atualizar o candidato de forma segura.
            $candidato = $user->candidato()->updateOrCreate(
                ['user_id' => $user->id],
                $validatedData
            );

            $previousStatus = $candidato->getOriginal('status', 'Inscrição Incompleta');

            if (in_array($previousStatus, ['Homologado', 'Aprovado', 'Em Análise'])) {
                $candidato->status = 'Em Análise'; 
                $candidato->ato_homologacao = null;
                $candidato->homologado_em = null;
                $candidato->homologacao_observacoes = null;

                $revertHistory = $candidato->revert_reason ?? [];
                $revertHistory[] = [
                    'timestamp' => Carbon::now()->toDateTimeString(),
                    'reason' => "Perfil alterado pelo candidato.",
                    'action' => 'profile_update',
                    'previous_status' => $previousStatus,
                ];
                $candidato->revert_reason = $revertHistory; 
            }
            
            if ($candidato->wasRecentlyCreated) {
                $candidato->status = 'Inscrição Incompleta';
            }

            $candidato->save();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro na transação ao atualizar perfil do candidato ID {$user->id}: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao salvar seu perfil. Por favor, tente novamente.')->withInput();
        }

        return redirect()->route('dashboard')->with('success', 'Seu perfil foi salvo com sucesso!');
    }
}
