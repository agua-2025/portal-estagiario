<?php

namespace App\Policies;

use App\Models\Documento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentoPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Documento $documento): bool
    {
        // Permite a visualização se o utilizador for o dono do documento OU um admin.
        return $user->id === $documento->user_id || $user->role === 'admin';
    }
}
