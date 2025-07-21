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
use Illuminate\Support\Facades\DB; // ✅ ADICIONADO: Essencial para a transação

class ProfileController extends Controller
{
    /**
     * Mostra o formulário para o candidato editar seu perfil.
     * (Este método não foi alterado)
     */
    public function edit(Request $request)
    {
        $candidato = $request->user()->candidato()->with('curso')->firstOrCreate(
            ['user_id' => Auth::id()], 
            [
                'nome_completo' => $request->user()->name, 
                'status' => 'Inscrição Incompleta' 
            ]
        );
        
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
     * ✅ AJUSTE CIRÚRGICO: A lógica agora está dentro de uma transação de banco de dados
     * para garantir que todas as alterações sejam salvas juntas ou nenhuma seja.
     */
    public function update(Request $request)
    {
        $candidato = $request->user()->candidato;

        $validatedData = $request->validate([
            'nome_completo' => 'required|string|max:255',
            'nome_mae' => 'required|string|max:255',
            'nome_pai' => 'nullable|string|max:255',
            'data_nascimento' => 'required|date',
            'sexo' => 'required|string',
            'cpf' => ['required', 'string', 'max:14', Rule::unique('candidatos')->ignore($candidato->id)], 
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
        
        try {
            // Inicia uma transação. Ou tudo funciona, ou nada é salvo.
            DB::transaction(function () use ($candidato, $validatedData) {
                $previousStatus = $candidato->status; 

                // 1. Preenche o modelo com os dados validados do formulário
                $candidato->fill($validatedData); 
                
                // 2. Verifica se o status precisa ser revertido
                if (in_array($previousStatus, ['Homologado', 'Aprovado', 'Em Análise'])) {
                    $candidato->status = 'Em Análise'; 
                    
                    // Limpa os campos de homologação
                    $candidato->ato_homologacao = null;
                    $candidato->homologado_em = null;
                    $candidato->homologacao_observacoes = null;

                    // Adiciona o motivo da reversão ao histórico
                    $revertHistory = $candidato->revert_reason ?? []; // Usa o histórico existente ou cria um novo array
                    $revertHistory[] = [
                        'timestamp' => Carbon::now()->toDateTimeString(),
                        'reason' => "Perfil alterado pelo candidato.",
                        'action' => 'profile_update',
                        'previous_status' => $previousStatus,
                    ];
                    $candidato->revert_reason = $revertHistory; 

                    Log::info("Candidato ID {$candidato->id} (Status anterior: {$previousStatus}) alterou perfil. Status revertido para 'Em Análise'.");
                }
                
                // 3. Salva todas as alterações (dados do perfil, status e histórico) de uma só vez.
                $candidato->save();
            });

        } catch (\Exception $e) {
            Log::error("Erro na transação ao atualizar perfil do candidato ID {$candidato->id}: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao salvar seu perfil. Por favor, tente novamente.')->withInput();
        }

        return redirect()->route('dashboard')->with('success', 'Seu perfil foi salvo com sucesso!');
    }
}