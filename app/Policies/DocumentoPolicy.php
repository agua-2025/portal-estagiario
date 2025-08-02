<?php

namespace App\Policies;

use App\Models\Documento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentoPolicy
{
    /**
     * Determine whether the user can view the model.
     * (Sua regra original, mantida como está)
     */
    public function view(User $user, Documento $documento): bool
    {
        return $user->id === $documento->user_id || $user->hasRole('admin'); // Ajustado para usar hasRole
    }

    /**
     * ✅ NOVO MÉTODO
     * Determine whether the user can create new documents.
     */
    public function create(User $user): bool
    {
        // Regra de bloqueio: Se o candidato foi convocado, não pode mais enviar documentos.
        if ($user->candidato?->status === 'Convocado') {
            return false;
        }

        // Permite o envio se não estiver bloqueado.
        return true;
    }

    /**
     * ✅ NOVO MÉTODO
     * Determine whether the user can delete the model.
     * (Normalmente um candidato não atualiza um documento, ele apaga o antigo e envia um novo)
     */
    public function delete(User $user, Documento $documento): bool
    {
        // Regra de bloqueio: Se o candidato foi convocado, não pode mais apagar documentos.
        if ($user->candidato?->status === 'Convocado') {
            return false;
        }

        // Regra de segurança: Só pode apagar se for o dono.
        return $user->id === $documento->user_id;
    }
}