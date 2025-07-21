<?php

namespace App\Http\Controllers\Candidato;

use App\Http\Controllers\Controller;
use App\Models\CandidatoAtividade;
use App\Models\TipoDeAtividade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate->Support->Facades->Log; // Importação corrigida para Log
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
```
Você está absolutamente certo! Agradeço por me guiar com tanta precisão.

O código que você me forneceu é do `App\Http\Controllers\Candidato\AtividadeController.php`. Minhas desculpas por ter me confundido.

Você está perguntando sobre a reversão de status para **documentos obrigatórios** e como o administrador verá **qual documento foi alterado**.

A lógica para isso está no `App\Http\Controllers\Candidato\DocumentoController.php`, não no `AtividadeController`.

Vamos ajustar o `DocumentoController` para:
1.  Garantir que a reversão de status funcione quando um candidato "Homologado" altera um documento.
2.  Registrar o motivo da reversão (qual documento foi alterado) na nova coluna `revert_reason` da tabela `candidatos`.
3.  Exibir essa informação no painel do administrador.

---

### **Ajuste 1: No `app/Models/Candidato.php` (Adicionar ao `$fillable`)**

Se você já fez isso na última vez, ótimo. Caso contrário, abra `app/Models/Candidato.php` e adicione `'revert_reason'` ao array `$fillable`.

```php
// app/Models/Candidato.php

// ... (código existente) ...

protected $fillable = [
    // ... (seus campos existentes) ...
    'homologacao_observacoes',
    'revert_reason', // ✅ ADICIONADO: Nova coluna para o motivo da reversão de status
];

// ... (restante do código) ...
```

---

### **Ajuste 2: No `app/Http/Controllers/Candidato\DocumentoController.php`**

Vamos adicionar a lógica para preencher `revert_reason` e mais logs para depuração.


```php
<?php

namespace App\Http\Controllers\Candidato;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Candidato; 
use Illuminate\Support\Facades\Auth;
use Illuminate->Support->Facades->Storage; // Importação corrigida
use Illuminate->Foundation->Auth->Access->AuthorizesRequests;
use Illuminate->Support->Facades->Log; 


class DocumentoController extends Controller
{
    use AuthorizesRequests;

    // DEFINA SEUS DOCUMENTOS OBRIGATÓRIOS AQUI - AJUSTE ESTA LISTA!
    private const DOCUMENTOS_OBRIGATORIOS = [
        'HISTORICO_ESCOLAR',
        'DECLARACAO_MATRICULA',
        'DECLARACAO_ELEITORAL',
        // 'RESERVISTA', // Exemplo: descomente se for obrigatório para homens
        // 'LAUDO_MEDICO', // Exemplo: descomente se for para PCD
    ];

    public function index()
    {
        $user = Auth::user();
        $candidato = $user->candidato()->firstOrCreate([]); 

        $documentosNecessarios = [
            'HISTORICO_ESCOLAR' => 'Histórico Escolar (para comprovar média e semestres)',
            'DECLARACAO_MATRICULA' => 'Declaração de Matrícula',
            'DECLARACAO_ELEITORAL' => 'Declaração de Quitação Eleitoral',
        ];

        if ($candidato->sexo === 'Masculino') {
            $documentosNecessarios['RESERVISTA'] = 'Comprovante de Reservista';
        }
        if ($candidato->possui_deficiencia) {
            $documentosNecessarios['LAUDO_MEDICO'] = 'Laudo Médico (PCD)';
        }

        $documentosEnviados = $user->documentos->keyBy('tipo_documento');

        return view('candidato.documentos.index', compact('candidato', 'documentosNecessarios', 'documentosEnviados'));
    }

    /**
     * Armazena um novo documento enviado pelo candidato.
     * ✅ AJUSTADO: Implementa a reversão de status para 'Em Análise' se o candidato era 'Homologado'.
     */
    public function store(Request $request)
    {
        Log::debug('Iniciando store de documento. Request data: ' . json_encode($request->all()));

        $user = Auth::user();
        $candidato = $user->candidato; 
        $previousStatus = $candidato->status; 

        Log::debug("Status do candidato ANTES da operação (DocumentoController@store): {$previousStatus}");
        Log::debug("ID do Candidato: {$candidato->id}");

        // 1. Validação do arquivo e tipo
        $request->validate([
            'tipo_documento' => 'required|string',
            'documento' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048', // Max 2MB
        ]);

        $tipoDocumento = $request->input('tipo_documento');

        // 2. Verifica se já existe um documento do mesmo tipo e o apaga
        $documentoAntigo = $user->documentos()->where('tipo_documento', $tipoDocumento)->first();
        if ($documentoAntigo) {
            Storage::disk('public')->delete($documentoAntigo->path); 
            Log::info("Documento antigo do tipo '{$tipoDocumento}' substituído para o usuário ID {$user->id}. Caminho antigo: {$documentoAntigo->path}");
        }

        // 3. Salva o novo documento no storage
        $filePath = $request->file('documento')->store('documentos/' . $user->id, 'public'); // Corrigido para 'documentos/user_ID' se preferir

        // 4. Cria ou atualiza o registro do documento no banco de dados
        $documento = $user->documentos()->updateOrCreate(
            ['tipo_documento' => $tipoDocumento],
            [
                'path' => $filePath, 
                'nome_original' => $request->file('documento')->getClientOriginalName(),
                'status' => 'enviado', // Status inicial do documento individual
            ]
        );
        Log::info("Documento '{$tipoDocumento}' enviado por usuário ID {$user->id}. Caminho: {$filePath}");


        // 5. LÓGICA CRÍTICA: ATUALIZA O STATUS DO CANDIDATO GERAL APÓS ENVIO DE DOCUMENTOS

        // Primeiro, precisamos saber quais documentos SÃO OBRIGATÓRIOS para este candidato ESPECÍFICO.
        $documentosNecessariosParaVerificar = [
            'HISTORICO_ESCOLAR',
            'DECLARACAO_MATRICULA',
            'DECLARACAO_ELEITORAL',
        ];
        if ($candidato->sexo === 'Masculino') {
            $documentosNecessariosParaVerificar[] = 'RESERVISTA';
        }
        if ($candidato->possui_deficiencia) {
            $documentosNecessariosParaVerificar[] = 'LAUDO_MEDICO';
        }

        // Segundo, pegamos todos os tipos de documentos que o candidato JÁ ENVIOU (após o upload atual)
        $tiposDocumentosEnviados = $user->documentos->pluck('tipo_documento')->unique()->toArray();
        
        // Terceiro, verificamos se TODOS os documentos obrigatórios estão entre os enviados
        $todosObrigatoriosEnviados = true;
        foreach ($documentosNecessariosParaVerificar as $docObrigatorioKey) {
            if (!in_array($docObrigatorioKey, $tiposDocumentosEnviados)) {
                $todosObrigatoriosEnviados = false;
                break;
            }
        }
        Log::debug("Verificação de documentos obrigatórios: Todos enviados? " . ($todosObrigatoriosEnviados ? 'Sim' : 'Não'));

        // ✅ Lógica de REVERSÃO DE STATUS para Homologado (ou transição de Incompleta para Análise)
        if ($previousStatus === 'Homologado') {
            $candidato->status = 'Em Análise'; // Volta para "Em Análise"
            // Limpa os campos de homologação
            $candidato->ato_homologacao = null;
            $candidato->homologado_em = null;
            $candidato->homologacao_observacoes = null;
            // Registra o motivo da reversão
            $candidato->revert_reason = "Documento obrigatório '{$tipoDocumento}' alterado/substituído pelo candidato."; // ✅ NOVO: Motivo da reversão
            $candidato->save();
            Log::info("Candidato ID {$candidato->id} (Homologado) alterou documento '{$tipoDocumento}' e voltou para 'Em Análise'. Motivo: {$candidato->revert_reason}");
            return redirect()->back()->with('success', 'Documento enviado com sucesso! Sua inscrição (anteriormente homologada) voltou para "Em Análise" devido à alteração.');
        } 
        // Lógica de transição de Inscrição Incompleta para Em Análise (mantida)
        elseif ($todosObrigatoriosEnviados && $candidato->status === 'Inscrição Incompleta') {
            $candidato->status = 'Em Análise'; 
            $candidato->revert_reason = null; // Limpa motivo se a transição é para Em Análise por completar
            $candidato->save();
            Log::info("Candidato ID {$candidato->id} mudou para 'Em Análise' após enviar todos os documentos obrigatórios.");
            return redirect()->back()->with('success', 'Documento enviado com sucesso! Sua inscrição agora está "Em Análise" e aguardando revisão.');
        } else {
            // Se o status não for alterado, apenas loga para depuração
            Log::debug("Candidato ID {$candidato->id} status não alterado. Status anterior: {$previousStatus}, Todos obrigatórios enviados: " . ($todosObrigatoriosEnviados ? 'Sim' : 'Não') . ", Status atual Inscrição Incompleta: " . ($candidato->status === 'Inscrição Incompleta' ? 'Sim' : 'Não'));
        }

        return redirect()->back()->with('success', 'Documento enviado com sucesso!');
    }

    /**
     * Exibe um documento específico.
     */
    public function show(Documento $documento)
    {
        $this->authorize('view', $documento); 

        $pathFromDb = $documento->path; 

        if (empty($pathFromDb)) {
            Log::warning("Documento ID {$documento->id} tem caminho nulo ou vazio no banco de dados.");
            abort(404, 'Arquivo não encontrado ou caminho inválido.');
        }

        if (Storage::disk('public')->exists($pathFromDb)) {
            return Storage::disk('public')->response($pathFromDb);
        }

        $cleanedPath = str_replace('public/', '', $pathFromDb); 
        if (Storage::disk('public')->exists($cleanedPath)) {
            return Storage::disk('public')->response($cleanedPath);
        }
        
        Log::warning("Documento físico não encontrado para o caminho: {$pathFromDb} (ID: {$documento->id})");
        abort(404, 'Arquivo não encontrado.');
    }

    /**
     * Remove um documento específico.
     * ✅ AJUSTADO: Implementa a reversão de status se um documento obrigatório é removido por um candidato homologado.
     */
    public function destroy(Documento $documento)
    {
        $this->authorize('delete', $documento); 

        $user = Auth::user();
        $candidato = $user->candidato;
        $previousStatus = $candidato->status; 

        try {
            // 1. Apaga o arquivo físico e o registro do banco
            Storage::disk('public')->delete($documento->path); 
            $documento->delete(); 
            Log::info("Documento ID {$documento->id} apagado. Tipo: {$documento->tipo_documento}.");

            // 2. Lógica para verificar se um documento obrigatório foi apagado e reverter status
            // Replicar a lógica de documentos necessários
            $documentosNecessariosParaVerificar = [
                'HISTORICO_ESCOLAR',
                'DECLARACAO_MATRICULA',
                'DECLARACAO_ELEITORAL',
            ];
            if ($candidato->sexo === 'Masculino') {
                $documentosNecessariosParaVerificar[] = 'RESERVISTA';
            }
            if ($candidato->possui_deficiencia) {
                $documentosNecessariosParaVerificar[] = 'LAUDO_MEDICO';
            }

            // Pega os documentos restantes após a exclusão
            $tiposDocumentosRestantes = $user->documentos()->pluck('tipo_documento')->unique()->toArray();
            
            $todosObrigatoriosAindaPresentes = true;
            foreach ($documentosNecessariosParaVerificar as $docObrigatorioKey) {
                if (!in_array($docObrigatorioKey, $tiposDocumentosRestantes)) {
                    $todosObrigatoriosAindaPresentes = false;
                    break;
                }
            }

            // ✅ Lógica de REVERSÃO DE STATUS DO CANDIDATO GERAL APÓS EXCLUSÃO DE DOCUMENTO
            if ($previousStatus === 'Homologado' && !$todosObrigatoriosAindaPresentes) {
                $candidato->status = 'Em Análise'; // Volta para "Em Análise"
                // Limpa os campos de homologação
                $candidato->ato_homologacao = null;
                $candidato->homologado_em = null;
                $candidato->homologacao_observacoes = null;
                // Registra o motivo da reversão
                $candidato->revert_reason = "Documento obrigatório '{$documento->tipo_documento}' removido pelo candidato (status anterior: Homologado)."; // ✅ NOVO: Motivo da reversão
                $candidato->save();
                Log::info("Candidato ID {$candidato->id} (Homologado) removeu documento obrigatório '{$documento->tipo_documento}' e voltou para 'Em Análise'. Motivo: {$candidato->revert_reason}");
                return redirect()->back()->with('success', 'Documento removido com sucesso! Sua inscrição (anteriormente homologada) voltou para "Em Análise" devido à remoção de um documento obrigatório.');
            } 
            elseif (($previousStatus === 'Em Análise' || $previousStatus === 'Aprovado') && !$todosObrigatoriosAindaPresentes) {
                $candidato->status = 'Inscrição Incompleta'; // Volta para "Inscrição Incompleta"
                $candidato->revert_reason = "Documento obrigatório '{$documento->tipo_documento}' removido pelo candidato (status anterior: {$previousStatus})."; // ✅ NOVO: Motivo da reversão
                $candidato->save();
                Log::info("Candidato ID {$candidato->id} (Status: {$previousStatus}) removeu documento obrigatório '{$documento->tipo_documento}' e voltou para 'Inscrição Incompleta'. Motivo: {$candidato->revert_reason}");
                return redirect()->back()->with('success', 'Documento removido com sucesso! Sua inscrição voltou para "Inscrição Incompleta" pois um documento obrigatório foi removido.');
            }


            return redirect()->route('candidato.documentos.index')->with('success', 'Documento removido com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao apagar documento ID {$documento->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao remover o documento. Por favor, tente novamente.');
        }
    }
}