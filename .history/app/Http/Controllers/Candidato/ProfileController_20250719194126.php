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
use Illuminate->Support->Facades->Log; // Importação corrigida para Log


class ProfileController extends Controller
{
    /**
     * Mostra o formulário para o candidato editar seu perfil.
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
     * ✅ AJUSTADO: Implementa a lógica de retorno para 'Em Análise' se o status era 'Homologado'
     * e armazena o motivo da reversão no formato JSON.
     */
    public function update(Request $request)
    {
        $candidato = $request->user()->candidato;

        $previousStatus = $candidato->status; 

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

        // ✅ Lógica para retornar o candidato para "Em Análise" se ele estava "Homologado"
        // Ou se estava Aprovado/Em Análise e o perfil foi alterado (reforça a revisão)
        if ($previousStatus === 'Homologado' || $previousStatus === 'Aprovado' || $previousStatus === 'Em Análise') {
            Log::debug("Perfil alterado. Status anterior: {$previousStatus}. Candidato ID: {$candidato->id}.");
            $candidato->status = 'Em Análise'; 
            // Limpa os campos de homologação
            $candidato->ato_homologacao = null;
            $candidato->homologado_em = null;
            $candidato->homologacao_observacoes = null;

            // Adiciona o motivo da reversão ao histórico (JSON)
            $revertHistory = is_array($candidato->revert_reason) ? $candidato->revert_reason : [];
            $revertHistory[] = [
                'timestamp' => Carbon::now()->toDateTimeString(),
                'reason' => "Perfil alterado pelo candidato.",
                'action' => 'profile_update',
                'previous_status' => $previousStatus,
            ];
            // Limita o histórico aos últimos 5 eventos, por exemplo
            $candidato->revert_reason = array_slice($revertHistory, -5); 

            // Salva as alterações no candidato (status e revert_reason)
            $candidato->save(); // ✅ Mover save() para aqui para garantir que status e revert_reason sejam salvos

            Log::info("Candidato ID {$candidato->id} (Status: {$previousStatus}) alterou perfil e voltou para 'Em Análise'. Motivo: " . json_encode($candidato->revert_reason));
        }
        // Se o status não for alterado na lógica acima, os dados validados ainda serão atualizados abaixo.
        
        try {
            // Atualiza os dados validados (que não incluem status ou revert_reason)
            $candidato->fill($validatedData); // Usar fill() para preencher os dados validados
            // O save() para status e revert_reason já foi feito acima se o status reverteu.
            // Se o status NÃO reverteu, mas outros campos foram alterados, o save() abaixo garante que eles sejam salvos.
            if ($candidato->isDirty()) { // Verifica se alguma coisa mudou no modelo
                $candidato->save();
            }

            return redirect()->route('dashboard')->with('success', 'Seu perfil foi salvo com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar perfil do candidato ID {$candidato->id}: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao salvar seu perfil. Por favor, tente novamente. Detalhes: ' . $e->getMessage())->withInput();
        }
    }
}
