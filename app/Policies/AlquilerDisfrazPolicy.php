<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\AlquilerDisfraz;
use App\Models\User;

class AlquilerDisfrazPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any AlquilerDisfraz');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AlquilerDisfraz $alquilerdisfraz): bool
    {
        return $user->checkPermissionTo('view AlquilerDisfraz');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create AlquilerDisfraz');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AlquilerDisfraz $alquilerdisfraz): bool
    {
        return $user->checkPermissionTo('update AlquilerDisfraz');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AlquilerDisfraz $alquilerdisfraz): bool
    {
        return $user->checkPermissionTo('delete AlquilerDisfraz');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any AlquilerDisfraz');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AlquilerDisfraz $alquilerdisfraz): bool
    {
        return $user->checkPermissionTo('restore AlquilerDisfraz');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any AlquilerDisfraz');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, AlquilerDisfraz $alquilerdisfraz): bool
    {
        return $user->checkPermissionTo('replicate AlquilerDisfraz');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder AlquilerDisfraz');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AlquilerDisfraz $alquilerdisfraz): bool
    {
        return $user->checkPermissionTo('force-delete AlquilerDisfraz');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any AlquilerDisfraz');
    }
}
