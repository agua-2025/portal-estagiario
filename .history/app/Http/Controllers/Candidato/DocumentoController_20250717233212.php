<?php

namespace App\Http\Controllers\Candidato;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Candidato; // Adicionado: Importar Candidato para atualizar o status
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log; // Adicionado para logs


class DocumentoController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        $candidato = $user->candidato()->firstOrCreate([]); // Garante que o candidato exista

        // Lógica para definir documentos necessários (baseada no perfil do candidato)
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
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $candidato = $user->candidato; 

        // 1. Validação do arquivo e tipo
        $request->validate([
            'tipo_documento' => 'required|string',
            'documento' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048', // Max 2MB
        ]);

        $tipoDocumento = $request->input('tipo_documento');

        // 2. Verifica se já existe um documento do mesmo tipo e o apaga
        $documentoAntigo = $user->documentos()->where('tipo_documento', $tipoDocumento)->first();
        if ($documentoAntigo) {
            Storage::disk('public')->delete($documentoAntigo->path); // ✅ CORRIGIDO: Usar $documentoAntigo->path
            Log::info("Documento antigo do tipo '{$tipoDocumento}' substituído para o usuário ID {$user->id}.");
        }

        // 3. Salva o novo documento no storage
        $filePath = $request->file('documento')->store('documentos/user_' . $user->id, 'public');

        // 4. Cria ou atualiza o registro do documento no banco de dados
        $documento = $user->documentos()->updateOrCreate(
            ['tipo_documento' => $tipoDocumento],
            [
                'path' => $filePath, // ✅ CORRETO: 'path' é o nome da coluna no BD
                'nome_original' => $request->file('documento')->getClientOriginalName(),
                'status' => 'enviado', // Status inicial do documento individual
            ]
        );
        Log::info("Documento '{$tipoDocumento}' enviado por usuário ID {$user->id}. Caminho: {$filePath}");


        // 5. LÓGICA CRÍTICA: ATUALIZA O STATUS DO CANDIDATO APÓS ENVIO DE DOCUMENTOS

        // Primeiro, precisamos saber quais documentos SÃO OBRIGATÓRIOS para este candidato ESPECÍFICO.
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

        // Segundo, pegamos todos os tipos de documentos que o candidato JÁ ENVIOU (após o upload atual)
        $tiposDocumentosEnviados = $user->documentos->pluck('tipo_documento')->unique()->toArray();
        
        // Terceiro, verificamos se TODOS os documentos obrigatórios estão entre os enviados
        $todosObrigatoriosEnviados = true;
        foreach (array_keys($documentosNecessarios) as $docObrigatorioKey) {
            if (!in_array($docObrigatorioKey, $tiposDocumentosEnviados)) {
                $todosObrigatoriosEnviados = false;
                break;
            }
        }

        // Se todos os documentos obrigatórios foram enviados E o status atual é 'Inscrição Incompleta'
        if ($todosObrigatoriosEnviados && $candidato->status === 'Inscrição Incompleta') {
            $candidato->status = 'Em Análise'; // Muda o status para "Em Análise"
            $candidato->save();
            Log::info("Candidato ID {$candidato->id} mudou para 'Em Análise' após enviar todos os documentos obrigatórios.");
            return redirect()->back()->with('success', 'Documento enviado com sucesso! Sua inscrição agora está "Em Análise" e aguardando revisão.');
        }

        return redirect()->back()->with('success', 'Documento enviado com sucesso!');
    }

    /**
     * Exibe um documento específico.
     */
    public function show(Documento $documento)
    {
        // Garante que apenas o dono do documento ou um admin possa visualizá-lo
        $this->authorize('view', $documento); 

        $pathFromDb = $documento->path; // ✅ CORRIGIDO: Usar $documento->path

        // Verifica se o caminho do arquivo é nulo ou vazio antes de tentar usá-lo.
        if (empty($pathFromDb)) {
            Log::warning("Documento ID {$documento->id} tem caminho nulo ou vazio no banco de dados.");
            abort(404, 'Arquivo não encontrado ou caminho inválido.');
        }

        // Tenta retornar o arquivo diretamente do disco 'public'
        if (Storage::disk('public')->exists($pathFromDb)) {
            return Storage::disk('public')->response($pathFromDb);
        }

        // Tenta um caminho alternativo (se o caminho salvo no DB já incluir 'public/' ou não)
        $cleanedPath = str_replace('public/', '', $pathFromDb); 
        if (Storage::disk('public')->exists($cleanedPath)) {
            return Storage::disk('public')->response($cleanedPath);
        }
        
        // Se o arquivo não foi encontrado após todas as tentativas
        Log::warning("Documento físico não encontrado para o caminho: {$pathFromDb} (ID: {$documento->id})");
        abort(404, 'Arquivo não encontrado.');
    }

    /**
     * Remove um documento específico.
     */
    public function destroy(Documento $documento)
    {
        $this->authorize('delete', $documento); // Garante permissão para apagar

        try {
            Storage::disk('public')->delete($documento->path); // ✅ CORRIGIDO: Usar $documento->path
            $documento->delete(); // Apaga o registro do banco
            Log::info("Documento ID {$documento->id} apagado. Tipo: {$documento->tipo_documento}.");

            // Opcional: Se um documento obrigatório foi apagado, o status do candidato pode voltar para 'Inscrição Incompleta'
            // Isso dependeria da sua regra de negócio.

            return redirect()->back()->with('success', 'Documento removido com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao apagar documento ID {$documento->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao remover o documento. Por favor, tente novamente.');
        }
    }
}