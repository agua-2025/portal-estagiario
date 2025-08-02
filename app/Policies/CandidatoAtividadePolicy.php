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
        // Ajustado para usar hasRole para consistência.
        return $user->id === $candidatoAtividade->user_id || $user->hasRole('admin');
    }

    /**
     * ✅ NOVO MÉTODO
     * Determine whether the user can create new activities.
     */
    public function create(User $user): bool
    {
        // REGRA DE BLOQUEIO: Se o candidato foi convocado, não pode mais criar atividades.
        if ($user->candidato?->status === 'Convocado') {
            return false;
        }

        // Se não estiver convocado, pode criar.
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CandidatoAtividade $candidatoAtividade): bool
    {
        // ✅ REGRA DE BLOQUEIO ADICIONADA
        // Se o candidato foi convocado, não pode mais editar.
        if ($user->candidato?->status === 'Convocado') {
            return false;
        }

        // Apenas o dono da atividade pode editá-la.
        return $user->id === $candidatoAtividade->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CandidatoAtividade $candidatoAtividade): bool
    {
        // ✅ REGRA DE BLOQUEIO ADICIONADA
        // Se o candidato foi convocado, não pode mais apagar.
        if ($user->candidato?->status === 'Convocado') {
            return false;
        }

        // O candidato só pode apagar uma atividade se for o dono
        // e se ela ainda não foi avaliada (Aprovada/Rejeitada).
        return $user->id === $candidatoAtividade->user_id && 
               in_array($candidatoAtividade->status, ['enviado', 'Em Análise']);
    }
}