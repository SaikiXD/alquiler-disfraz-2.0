<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\AlquilerDisfrazPieza;
use App\Models\User;

class AlquilerDisfrazPiezaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any AlquilerDisfrazPieza');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AlquilerDisfrazPieza $alquilerdisfrazpieza): bool
    {
        return $user->checkPermissionTo('view AlquilerDisfrazPieza');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create AlquilerDisfrazPieza');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AlquilerDisfrazPieza $alquilerdisfrazpieza): bool
    {
        return $user->checkPermissionTo('update AlquilerDisfrazPieza');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AlquilerDisfrazPieza $alquilerdisfrazpieza): bool
    {
        return $user->checkPermissionTo('delete AlquilerDisfrazPieza');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any AlquilerDisfrazPieza');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AlquilerDisfrazPieza $alquilerdisfrazpieza): bool
    {
        return $user->checkPermissionTo('restore AlquilerDisfrazPieza');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any AlquilerDisfrazPieza');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, AlquilerDisfrazPieza $alquilerdisfrazpieza): bool
    {
        return $user->checkPermissionTo('replicate AlquilerDisfrazPieza');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder AlquilerDisfrazPieza');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AlquilerDisfrazPieza $alquilerdisfrazpieza): bool
    {
        return $user->checkPermissionTo('force-delete AlquilerDisfrazPieza');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any AlquilerDisfrazPieza');
    }
}
