<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\DevolucionDisfrazPieza;
use App\Models\User;

class DevolucionDisfrazPiezaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any DevolucionDisfrazPieza');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DevolucionDisfrazPieza $devoluciondisfrazpieza): bool
    {
        return $user->checkPermissionTo('view DevolucionDisfrazPieza');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create DevolucionDisfrazPieza');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DevolucionDisfrazPieza $devoluciondisfrazpieza): bool
    {
        return $user->checkPermissionTo('update DevolucionDisfrazPieza');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DevolucionDisfrazPieza $devoluciondisfrazpieza): bool
    {
        return $user->checkPermissionTo('delete DevolucionDisfrazPieza');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any DevolucionDisfrazPieza');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DevolucionDisfrazPieza $devoluciondisfrazpieza): bool
    {
        return $user->checkPermissionTo('restore DevolucionDisfrazPieza');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any DevolucionDisfrazPieza');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, DevolucionDisfrazPieza $devoluciondisfrazpieza): bool
    {
        return $user->checkPermissionTo('replicate DevolucionDisfrazPieza');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder DevolucionDisfrazPieza');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DevolucionDisfrazPieza $devoluciondisfrazpieza): bool
    {
        return $user->checkPermissionTo('force-delete DevolucionDisfrazPieza');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any DevolucionDisfrazPieza');
    }
}
