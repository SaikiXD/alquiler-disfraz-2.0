<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TipoPieza;
use App\Models\User;

class TipoPiezaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any TipoPieza');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TipoPieza $tipopieza): bool
    {
        return $user->checkPermissionTo('view TipoPieza');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create TipoPieza');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TipoPieza $tipopieza): bool
    {
        return $user->checkPermissionTo('update TipoPieza');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TipoPieza $tipopieza): bool
    {
        return $user->checkPermissionTo('delete TipoPieza');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any TipoPieza');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TipoPieza $tipopieza): bool
    {
        return $user->checkPermissionTo('restore TipoPieza');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any TipoPieza');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, TipoPieza $tipopieza): bool
    {
        return $user->checkPermissionTo('replicate TipoPieza');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder TipoPieza');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TipoPieza $tipopieza): bool
    {
        return $user->checkPermissionTo('force-delete TipoPieza');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any TipoPieza');
    }
}
