<?php

namespace App\Policies;

use App\Models\Toon;
use App\Models\User;

class ToonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_toon') || $user->can('view_any_user');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Toon $toon): bool
    {
        return (
            ($user->can('view_toon') && ($toon->user->id == $user->id)) ||
            $user->can('view_any_user')
        );
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_toon');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Toon $toon): bool
    {
        return (
            ($user->can('update_toon') && ($toon->user->id == $user->id)) ||
            $user->can('update_any_user')
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Toon $toon): bool
    {
        return (
            ($user->can('delete_toon') && ($toon->user->id == $user->id)) ||
            $user->can('update_any_user')
        );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Toon $toon): bool
    {
        return (
            ($user->can('restore_toon') && ($toon->user->id == $user->id)) ||
            $user->can('update_any_user')
        );
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Toon $toon): bool
    {
        return (
            ($user->can('force_delete_toon') && ($toon->user->id == $user->id)) ||
            $user->can('update_any_user')
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteAny(User $user): bool
    {
        return (
            $user->can('delete_any_toon') ||
            $user->can('update_any_user')
        );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restoreAny(User $user): bool
    {
        return (
            $user->can('restore_any_toon') ||
            $user->can('update_any_user')
        );
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDeleteAny(User $user): bool
    {
        return (
            $user->can('force_delete_any_toon') ||
            $user->can('update_any_user')
        );
    }
}
