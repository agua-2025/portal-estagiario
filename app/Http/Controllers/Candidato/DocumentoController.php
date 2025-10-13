<?php

namespace App\Http\Controllers\Candidato;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Candidato;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DocumentoController extends Controller
{
    use AuthorizesRequests;

    private const DOCUMENTOS_OBRIGATORIOS = [
        'HISTORICO_ESCOLAR',
        'DECLARACAO_MATRICULA',
        'DECLARACAO_ELEITORAL',
    ];

    /**
     * Lista de documentos do candidato (bloqueia se perfil incompleto).
     */
    public function index()
    {
        $user = Auth::user();
        $candidato = $user?->candidato;

        // 🔒 Bloqueio no INDEX
        $profileIsComplete = false;
        if ($candidato) {
            if (method_exists($candidato, 'isComplete')) {
                $profileIsComplete = $candidato->isComplete();
            } elseif (method_exists($candidato, 'isProfileComplete')) {
                $profileIsComplete = $candidato->isProfileComplete();
            }
        }

        if (!$user || !$user->hasRole('candidato') || !$candidato || !$profileIsComplete) {
            return redirect()
                ->route('candidato.profile.edit')
                ->with('warn', 'Complete seu perfil (dados obrigatórios) antes de acessar Documentos.');
        }

        // ✔️ Daqui pra baixo só roda com perfil válido
        $documentosNecessarios = [
            'HISTORICO_ESCOLAR'    => 'Histórico Escolar (para comprovar média e semestres)',
            'DECLARACAO_MATRICULA' => 'Declaração de Matrícula',
            'DECLARACAO_ELEITORAL' => 'Declaração de Quitação Eleitoral',
        ];
        if ($candidato->sexo === 'Masculino') {
            $documentosNecessarios['RESERVISTA'] = 'Comprovante de Reservista';
        }
        if ($candidato->possui_deficiencia) {
            $documentosNecessarios['LAUDO_MEDICO'] = 'Laudo Médico (PCD)';
        }

        $documentosEnviados = $candidato->documentos->keyBy('tipo_documento');

        return view('candidato.documentos.index', compact('candidato', 'documentosNecessarios', 'documentosEnviados'));
    }

    /**
     * Armazena um novo documento enviado pelo candidato (bloqueia se perfil incompleto).
     */
    public function store(Request $request)
    {
        Log::debug('Iniciando store de documento. Request data: ' . json_encode($request->all()));

        $user = Auth::user();
        $candidato = $user?->candidato;

        // 🔒 Checagem defensiva: papel + perfil existente + completude
        $profileIsComplete = false;
        if ($candidato) {
            if (method_exists($candidato, 'isComplete')) {
                $profileIsComplete = $candidato->isComplete();
            } elseif (method_exists($candidato, 'isProfileComplete')) {
                $profileIsComplete = $candidato->isProfileComplete();
            }
        }

        if (!$user || !$user->hasRole('candidato') || !$candidato || !$profileIsComplete) {
            return redirect()
                ->route('candidato.profile.edit')
                ->with('warn', 'Complete seu perfil (dados obrigatórios) antes de enviar documentos.');
        }

        $previousStatus = $candidato->status;

        Log::debug("Status do candidato ANTES da operação (DocumentoController@store): {$previousStatus}");
        Log::debug("ID do Candidato: {$candidato->id}");

        $request->validate([
            'tipo_documento' => 'required|string',
            'documento' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ]);

        $tipoDocumento = $request->input('tipo_documento');

        DB::beginTransaction();

        try {
            // Substitui documento antigo (se existir)
            $documentoAntigo = $candidato->documentos()->where('tipo_documento', $tipoDocumento)->first();
            if ($documentoAntigo && $documentoAntigo->path) {
                if (Storage::disk('public')->exists($documentoAntigo->path)) {
                    Storage::disk('public')->delete($documentoAntigo->path);
                }
                Log::info("Documento antigo do tipo '{$tipoDocumento}' substituído para o candidato ID {$candidato->id}.");
            }

            $filePath = $request->file('documento')->store('documentos/' . $user->id, 'public');

            // Cria/atualiza o documento
            $candidato->documentos()->updateOrCreate(
                ['tipo_documento' => $tipoDocumento],
                [
                    'user_id'       => $user->id, // retrocompatibilidade/auditoria
                    'path'          => $filePath,
                    'nome_original' => $request->file('documento')->getClientOriginalName(),
                    'status'        => 'enviado',
                ]
            );
            Log::info("Documento '{$tipoDocumento}' enviado por candidato ID {$candidato->id}. Caminho: {$filePath}");

            // Quais são obrigatórios para este candidato
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

            // Recarrega docs e verifica obrigatórios
            $candidato->load('documentos');
            $tiposDocumentosEnviados = $candidato->documentos->pluck('tipo_documento')->unique()->toArray();

            $todosObrigatoriosEnviados = empty(array_diff($documentosNecessariosParaVerificar, $tiposDocumentosEnviados));
            Log::debug("Verificação de documentos obrigatórios: Todos enviados? " . ($todosObrigatoriosEnviados ? 'Sim' : 'Não'));

            if (in_array($tipoDocumento, $documentosNecessariosParaVerificar, true) &&
                in_array($previousStatus, ['Homologado', 'Aprovado', 'Em Análise'], true)) {

                $candidato->status = 'Em Análise';
                $candidato->homologado_em = null;
                $candidato->homologacao_observacoes = null;

                $revertHistory = $candidato->revert_reason ?? [];
                if (!is_array($revertHistory)) { $revertHistory = []; }

                $revertHistory[] = [
                    'timestamp'       => Carbon::now()->toDateTimeString(),
                    'reason'          => "Documento obrigatório '{$tipoDocumento}' alterado/substituído pelo candidato.",
                    'action'          => 'document_update',
                    'document_type'   => $tipoDocumento,
                    'previous_status' => $previousStatus,
                ];
                $candidato->revert_reason = array_slice($revertHistory, -5);

                $candidato->save();
                Log::info("Candidato ID {$candidato->id} (Status anterior: {$previousStatus}) alterou documento '{$tipoDocumento}' e voltou para 'Em Análise'.");

                DB::commit();
                return redirect()->back()->with('success', 'Documento enviado com sucesso! Sua inscrição voltou para "Em Análise" devido à alteração.');
            } elseif ($todosObrigatoriosEnviados && $candidato->status === 'Inscrição Incompleta') {
                $candidato->status = 'Em Análise';
                $candidato->revert_reason = null;
                $candidato->save();
                Log::info("Candidato ID {$candidato->id} mudou para 'Em Análise' após enviar todos os documentos obrigatórios.");

                DB::commit();
                return redirect()->back()->with('success', 'Documento enviado com sucesso! Sua inscrição agora está "Em Análise".');
            } else {
                Log::debug("Candidato ID {$candidato->id} status não alterado.");
            }

            DB::commit();
            return redirect()->back()->with('success', 'Documento enviado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro na transação de store de documento: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao processar sua solicitação.');
        }
    }

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
     * Remove um documento (bloqueia se perfil incompleto).
     */
    public function destroy(Documento $documento)
    {
        $this->authorize('delete', $documento);

        $user = Auth::user();
        $candidato = $user?->candidato;

        // 🔒 Blindagem: papel + candidato + perfil completo
        $profileIsComplete = false;
        if ($candidato) {
            if (method_exists($candidato, 'isComplete')) {
                $profileIsComplete = $candidato->isComplete();
            } elseif (method_exists($candidato, 'isProfileComplete')) {
                $profileIsComplete = $candidato->isProfileComplete();
            }
        }

        if (!$user || !$user->hasRole('candidato') || !$candidato || !$profileIsComplete) {
            return redirect()
                ->route('candidato.profile.edit')
                ->with('warn', 'Complete seu perfil antes de remover documentos.');
        }

        $previousStatus = $candidato->status;

        DB::beginTransaction();
        try {
            $tipoDocumentoRemovido = $documento->tipo_documento;

            // remove arquivo físico (se existir) e o registro
            if ($documento->path && Storage::disk('public')->exists($documento->path)) {
                Storage::disk('public')->delete($documento->path);
            }
            $documento->delete();

            Log::info("Documento ID {$documento->id} apagado. Tipo: {$tipoDocumentoRemovido}.");

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

            // Recarrega e verifica os documentos restantes
            $candidato->load('documentos');
            $tiposDocumentosRestantes = $candidato->documentos()->pluck('tipo_documento')->unique()->toArray();
            $todosObrigatoriosAindaPresentes = empty(array_diff($documentosNecessariosParaVerificar, $tiposDocumentosRestantes));

            if (in_array($tipoDocumentoRemovido, $documentosNecessariosParaVerificar, true)) {
                if (in_array($previousStatus, ['Homologado', 'Aprovado', 'Em Análise'], true)) {
                    $candidato->status = $todosObrigatoriosAindaPresentes ? 'Em Análise' : 'Inscrição Incompleta';

                    $candidato->homologado_em = null;
                    $candidato->homologacao_observacoes = null;

                    $revertHistory = $candidato->revert_reason ?? [];
                    if (!is_array($revertHistory)) { $revertHistory = []; }

                    $revertHistory[] = [
                        'timestamp'       => Carbon::now()->toDateTimeString(),
                        'reason'          => "Documento obrigatório '{$tipoDocumentoRemovido}' removido pelo candidato.",
                        'action'          => 'document_delete',
                        'document_type'   => $tipoDocumentoRemovido,
                        'previous_status' => $previousStatus,
                    ];
                    $candidato->revert_reason = array_slice($revertHistory, -5);

                    $candidato->save();
                    Log::info("Candidato ID {$candidato->id} (Status: {$previousStatus}) removeu documento '{$tipoDocumentoRemovido}' e mudou para '{$candidato->status}'.");

                    DB::commit();
                    return redirect()->back()->with('success', 'Documento removido! Sua inscrição precisa ser reanalisada.');
                }
            }

            DB::commit();
            return redirect()->route('candidato.documentos.index')->with('success', 'Documento removido com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao apagar documento ID {$documento->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao remover o documento.');
        }
    }
}
