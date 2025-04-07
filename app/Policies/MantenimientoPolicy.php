<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Mantenimiento;
use App\Models\User;

class MantenimientoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Mantenimiento');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Mantenimiento $mantenimiento): bool
    {
        return $user->checkPermissionTo('view Mantenimiento');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Mantenimiento');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Mantenimiento $mantenimiento): bool
    {
        return $user->checkPermissionTo('update Mantenimiento');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Mantenimiento $mantenimiento): bool
    {
        return $user->checkPermissionTo('delete Mantenimiento');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Mantenimiento');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Mantenimiento $mantenimiento): bool
    {
        return $user->checkPermissionTo('restore Mantenimiento');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Mantenimiento');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Mantenimiento $mantenimiento): bool
    {
        return $user->checkPermissionTo('replicate Mantenimiento');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Mantenimiento');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Mantenimiento $mantenimiento): bool
    {
        return $user->checkPermissionTo('force-delete Mantenimiento');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Mantenimiento');
    }
}
