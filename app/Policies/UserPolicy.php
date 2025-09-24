<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Détermine si l'utilisateur peut voir tous les utilisateurs
     * Seuls les admin et manager peuvent accéder à la gestion des utilisateurs
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Détermine si l'utilisateur peut voir un utilisateur spécifique
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) || $user->id === $model->id;
    }

    /**
     * Détermine si l'utilisateur peut créer des utilisateurs
     * Seuls les admin et manager peuvent créer des utilisateurs
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Détermine si l'utilisateur peut modifier un utilisateur
     * Seuls les admin et manager peuvent modifier, mais pas leur propre compte via cette interface
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Détermine si l'utilisateur peut supprimer un utilisateur
     * Seuls les admin et manager peuvent supprimer, mais pas leur propre compte
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && $user->id !== $model->id;
    }

    /**
     * Détermine si l'utilisateur peut restaurer un utilisateur
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Détermine si l'utilisateur peut supprimer définitivement un utilisateur
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('admin') && $user->id !== $model->id;
    }

    /**
     * Détermine si l'utilisateur peut activer/désactiver un utilisateur
     */
    public function toggleStatus(User $user, User $model): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && $user->id !== $model->id;
    }

    /**
     * Détermine si l'utilisateur peut assigner des rôles
     */
    public function assignRoles(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }
}
