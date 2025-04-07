<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Alquiler;
use App\Models\User;

class AlquilerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Alquiler');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Alquiler $alquiler): bool
    {
        return $user->checkPermissionTo('view Alquiler');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Alquiler');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Alquiler $alquiler): bool
    {
        return $user->checkPermissionTo('update Alquiler');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Alquiler $alquiler): bool
    {
        return $user->checkPermissionTo('delete Alquiler');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Alquiler');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Alquiler $alquiler): bool
    {
        return $user->checkPermissionTo('restore Alquiler');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Alquiler');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Alquiler $alquiler): bool
    {
        return $user->checkPermissionTo('replicate Alquiler');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Alquiler');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Alquiler $alquiler): bool
    {
        return $user->checkPermissionTo('force-delete Alquiler');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Alquiler');
    }
}
