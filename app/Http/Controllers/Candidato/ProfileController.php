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

class ProfileController extends Controller
{
    /**
     * Mostra o formulário para o candidato editar seu perfil.
     */
    public function edit(Request $request)
    {
        // Carrega o perfil do candidato OU cria um novo.
        // O '.with('curso')' garante que a relação do curso seja carregada
        // para que o accessor 'instituicao_id' no modelo Candidato funcione sem N+1 queries.
        $candidato = $request->user()->candidato()->with('curso')->firstOrCreate(
            ['user_id' => Auth::id()], // Critério para encontrar o candidato
            ['nome_completo' => $request->user()->name] // Dados para criar se não encontrar
        );
        
        // Carrega os dados necessários para os menus de seleção
        $instituicoes = Instituicao::orderBy('nome')->get();
        $cursos = Curso::orderBy('nome')->get();
        $estados = Estado::orderBy('nome')->get();
        $cidades = Cidade::all();

        // LISTA 1: Para o cálculo da porcentagem de conclusão (Modelo Candidato)
        $completableFields = Candidato::getCompletableFields();

        // LISTA 2: Para enviar dados para o frontend (inclui opcionais para pré-preencher).
        $frontendFields = array_merge($completableFields, [
            'nome_pai',              // Campo opcional (Nome do Pai)
            'rg',                    // Campo opcional (RG)
            'rg_orgao_expedidor',    // Campo opcional (Órgão Expedidor)
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
     */
    public function update(Request $request)
    {
        $candidato = $request->user()->candidato;

        // Validação completa de todos os campos do formulário
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
            'curso_data_inicio' => 'required|date',
            'curso_previsao_conclusao' => 'required|date|after:curso_data_inicio',
            'media_aproveitamento' => 'required|numeric|between:0,10.00',
            'semestres_completos' => 'required|integer|min:0',
        ]);

        // A linha 'unset($validatedData['instituicao_id']);' foi removida.
        // Ela não é necessária porque 'instituicao_id' não está na lista de validação,
        // então não fará parte do array $validatedData. O sistema já lida com isso
        // através da relação do curso, que é a maneira correta.

        $candidato->update($validatedData);

        return redirect()->route('dashboard')->with('success', 'Seu perfil foi salvo com sucesso!');
    }
}