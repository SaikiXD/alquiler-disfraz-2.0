<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Devolucion;
use App\Models\User;

class DevolucionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Devolucion');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Devolucion $devolucion): bool
    {
        return $user->checkPermissionTo('view Devolucion');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Devolucion');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Devolucion $devolucion): bool
    {
        return $user->checkPermissionTo('update Devolucion');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Devolucion $devolucion): bool
    {
        return $user->checkPermissionTo('delete Devolucion');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Devolucion');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Devolucion $devolucion): bool
    {
        return $user->checkPermissionTo('restore Devolucion');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Devolucion');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Devolucion $devolucion): bool
    {
        return $user->checkPermissionTo('replicate Devolucion');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Devolucion');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Devolucion $devolucion): bool
    {
        return $user->checkPermissionTo('force-delete Devolucion');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Devolucion');
    }
}
