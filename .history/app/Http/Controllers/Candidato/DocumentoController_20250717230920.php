<?php

namespace App\Http\Controllers\Candidato;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Candidato; // ✅ Adicionado: Importar Candidato para atualizar o status
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log; // ✅ Adicionado para logs


class DocumentoController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        $candidato = $user->candidato()->firstOrCreate([]); // Garante que o candidato exista

        // ✅ Lógica para definir documentos necessários (baseada no perfil do candidato)
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
        $candidato = $user->candidato; // Pega o perfil do candidato associado ao usuário logado

        // 1. Validação do arquivo e tipo
        $request->validate([
            'tipo_documento' => 'required|string',
            'documento' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048', // Max 2MB
        ]);

        $tipoDocumento = $request->input('tipo_documento');

        // 2. Verifica se já existe um documento do mesmo tipo e o apaga
        $documentoAntigo = $user->documentos()->where('tipo_documento', $tipoDocumento)->first();
        if ($documentoAntigo) {
            Storage::disk('public')->delete($documentoAntigo->caminho_arquivo); // Usar 'caminho_arquivo'
            Log::info("Documento antigo do tipo '{$tipoDocumento}' substituído para o usuário ID {$user->id}.");
        }

        // 3. Salva o novo documento no storage
        $path = $request->file('documento')->store('documentos/user_' . $user->id, 'public');

        // 4. Cria ou atualiza o registro do documento no banco de dados
        $documento = $user->documentos()->updateOrCreate(
            ['tipo_documento' => $tipoDocumento],
            [
                'caminho_arquivo' => $path, // Corrigido 'path' para 'caminho_arquivo'
                'nome_original' => $request->file('documento')->getClientOriginalName(),
                'status' => 'enviado', // Status inicial do documento individual
            ]
        );
        Log::info("Documento '{$tipoDocumento}' enviado por usuário ID {$user->id}. Caminho: {$path}");


        // 5. ✅ LÓGICA CRÍTICA: ATUALIZA O STATUS DO CANDIDATO APÓS ENVIO DE DOCUMENTOS

        // Primeiro, precisamos saber quais documentos SÃO OBRIGATÓRIOS para este candidato ESPECÍFICO.
        // Essa lógica precisa ser a mesma do método index().
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
        $tiposDocumentosEnviados = $user->documentos()->pluck('tipo_documento')->unique()->toArray();
        
        // Terceiro, verificamos se TODOS os documentos obrigatórios estão entre os enviados
        $todosObrigatoriosEnviados = true;
        foreach (array_keys($documentosNecessarios) as $docObrigatorioKey) { // Itera sobre as CHAVES dos docs necessários
            if (!in_array($docObrigatorioKey, $tiposDocumentosEnviados)) {
                $todosObrigatoriosEnviados = false;
                break;
            }
        }

        // Se todos os documentos obrigatórios foram enviados E o status atual do candidato é 'Inscrição Incompleta'
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
        $this->authorize('view', $documento); // Garante que apenas o dono veja

        $pathFromDb = $documento->caminho_arquivo; // Corrigido 'path' para 'caminho_arquivo'

        if (Storage::disk('public')->exists($pathFromDb)) {
            return Storage::disk('public')->response($pathFromDb);
        }

        // Se o path no DB estiver como 'public/documentos/user_X/nome.pdf'
        $cleanedPath = str_replace('public/', '', $pathFromDb); 
        if (Storage::disk('public')->exists($cleanedPath)) {
            return Storage::disk('public')->response($cleanedPath);
        }
        
        Log::warning("Documento não encontrado para o caminho: {$pathFromDb}");
        abort(404, 'Arquivo não encontrado.');
    }

    /**
     * Remove um documento específico.
     */
    public function destroy(Documento $documento)
    {
        $this->authorize('delete', $documento); // Garante permissão para apagar

        try {
            Storage::disk('public')->delete($documento->caminho_arquivo); // Apaga o arquivo físico
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