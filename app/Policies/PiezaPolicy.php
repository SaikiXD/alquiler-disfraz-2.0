<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Pieza;
use App\Models\User;

class PiezaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Pieza');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pieza $pieza): bool
    {
        return $user->checkPermissionTo('view Pieza');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Pieza');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pieza $pieza): bool
    {
        return $user->checkPermissionTo('update Pieza');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pieza $pieza): bool
    {
        return $user->checkPermissionTo('delete Pieza');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Pieza');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pieza $pieza): bool
    {
        return $user->checkPermissionTo('restore Pieza');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Pieza');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Pieza $pieza): bool
    {
        return $user->checkPermissionTo('replicate Pieza');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Pieza');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pieza $pieza): bool
    {
        return $user->checkPermissionTo('force-delete Pieza');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Pieza');
    }
}
