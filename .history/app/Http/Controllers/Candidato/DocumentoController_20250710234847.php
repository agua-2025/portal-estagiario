<?php

namespace App\Http\Controllers\Candidato;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentoController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        $candidato = $user->candidato()->firstOrCreate([]);

        // ✅ CORRIGIDO: A lista de documentos agora inclui o Histórico Escolar no sítio certo.
        $documentosNecessarios = [
            'HISTORICO_ESCOLAR' => 'Histórico Escolar (para comprovar média e semestres)',
            'DECLARACAO_MATRICULA' => 'Declaração de Matrícula',
            'DECLARACAO_ELEITORAL' => 'Declaração de Quitação Eleitoral',
            // A linha 'COMPROVANTE_CONCLUSAO_SEMESTRES' foi removida por ser redundante,
            // uma vez que o Histórico Escolar já comprova isso.
        ];

        if ($candidato->sexo === 'Masculino') {
            $documentosNecessarios['RESERVISTA'] = 'Comprovante de Reservista';
        }
        if ($candidato->possui_deficiencia) { // A verificação foi simplificada, pois o campo já é booleano.
            $documentosNecessarios['LAUDO_MEDICO'] = 'Laudo Médico (PCD)';
        }

        $documentosEnviados = $user->documentos->keyBy('tipo_documento');

        return view('candidato.documentos.index', compact('candidato', 'documentosNecessarios', 'documentosEnviados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_documento' => 'required|string',
            'documento' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ]);

        $user = Auth::user();
        $tipoDocumento = $request->input('tipo_documento');

        $documentoAntigo = $user->documentos()->where('tipo_documento', $tipoDocumento)->first();
        if ($documentoAntigo) {
            Storage::disk('public')->delete($documentoAntigo->path);
        }

        $path = $request->file('documento')->store('documentos/user_' . $user->id, 'public');

        $user->documentos()->updateOrCreate(
            ['tipo_documento' => $tipoDocumento],
            [
                'path' => $path,
                'nome_original' => $request->file('documento')->getClientOriginalName(),
                'status' => 'enviado',
            ]
        );

        return redirect()->route('candidato.documentos.index')->with('success', 'Documento enviado com sucesso!');
    }

    public function show(Documento $documento)
    {
        $this->authorize('view', $documento);

        $pathFromDb = $documento->path;

        if (Storage::disk('public')->exists($pathFromDb)) {
            return Storage::disk('public')->response($pathFromDb);
        }

        $cleanedPath = str_replace('public/', '', $pathFromDb);
        if (Storage::disk('public')->exists($cleanedPath)) {
            return Storage::disk('public')->response($cleanedPath);
        }
        
        abort(404, 'Ficheiro não encontrado no armazenamento após todas as verificações.');
    }
}
