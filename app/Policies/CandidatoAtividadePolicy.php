<?php

namespace App\Policies;

use App\Models\CandidatoAtividade;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CandidatoAtividadePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CandidatoAtividade $candidatoAtividade): bool
    {
        // Permite a visualização se o utilizador for o dono da atividade OU um admin.
        return $user->id === $candidatoAtividade->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CandidatoAtividade $candidatoAtividade): bool
    {
        // Apenas o dono da atividade pode editá-la.
        return $user->id === $candidatoAtividade->user_id;
    }

    /**
     * ✅ NOVO MÉTODO: Determine whether the user can delete the model.
     */
    public function delete(User $user, CandidatoAtividade $candidatoAtividade): bool
    {
        // O candidato só pode apagar uma atividade se for o dono
        // e se ela ainda não foi avaliada (Aprovada/Rejeitada).
        return $user->id === $candidatoAtividade->user_id && 
               in_array($candidatoAtividade->status, ['enviado', 'Em Análise']);
    }
}
