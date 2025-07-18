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
use Illuminate\Support\Facades\Log; // Para logs de erros, se necessário


class ProfileController extends Controller
{
    /**
     * Mostra o formulário para o candidato editar seu perfil.
     */
    public function edit(Request $request)
    {
        // Carrega o perfil do candidato OU cria um novo, se não existir.
        // ✅ AJUSTADO: Adicionado 'status' no firstOrCreate para garantir que seja definido na criação.
        $candidato = $request->user()->candidato()->with('curso')->firstOrCreate(
            ['user_id' => Auth::id()], // Critério para encontrar o candidato
            [
                'nome_completo' => $request->user()->name, // Dados para criar se não encontrar
                'status' => 'Inscrição Incompleta' // ✅ CRUCIAL: Status padrão na primeira criação
            ]
        );
        
        // Carrega os dados necessários para os menus de seleção
        $instituicoes = Instituicao::orderBy('nome')->get();
        $cursos = Curso::orderBy('nome')->get();
        $estados = Estado::orderBy('nome')->get();
        $cidades = Cidade::all(); // Carrega todas as cidades ou filtra por estado se preferir

        // LISTA 1: Para o cálculo da porcentagem de conclusão (Modelo Candidato)
        $completableFields = Candidato::getCompletableFields();

        // LISTA 2: Para enviar dados para o frontend (inclui opcionais para pré-preencher).
        $frontendFields = array_merge($completableFields, [
            'nome_pai', 
            'rg', 
            'rg_orgao_expedidor', 
        ]);

        // Envia todas as variáveis necessárias para a view
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
     * ✅ AJUSTADO: Implementa a lógica de retorno para 'Em Análise' se o status era 'Homologado'.
     */
    public function update(Request $request)
    {
        $candidato = $request->user()->candidato;

        // Guarda o status anterior para verificar se era 'Homologado'
        $previousStatus = $candidato->status; 

        // Validação completa de todos os campos do formulário
        $validatedData = $request->validate([
            'nome_completo' => 'required|string|max:255',
            'nome_mae' => 'required|string|max:255',
            'nome_pai' => 'nullable|string|max:255',
            'data_nascimento' => 'required|date',
            'sexo' => 'required|string',
            // Validação de CPF: Ignora o próprio CPF do candidato se ele não for alterado
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
        if ($previousStatus === 'Homologado') {
            $validatedData['status'] = 'Em Análise';
            // Opcional: Você pode querer limpar os campos de homologação se ele voltar para análise
            $validatedData['ato_homologacao'] = null;
            $validatedData['homologado_em'] = null;
            $validatedData['homologacao_observacoes'] = null;
            Log::info("Candidato ID {$candidato->id} (Homologado) alterou dados e voltou para 'Em Análise'.");
        }
        // Se o status não for 'Homologado', o status não é alterado aqui pelo update do perfil
        // Ele será alterado pelos métodos do admin (Aprovar, Rejeitar, Homologar) ou pelo fluxo de documentos.
        
        try {
            $candidato->update($validatedData);
            return redirect()->route('dashboard')->with('success', 'Seu perfil foi salvo com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar perfil do candidato ID {$candidato->id}: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao salvar seu perfil. Por favor, tente novamente. Detalhes: ' . $e->getMessage())->withInput();
        }
    }
}