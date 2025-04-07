<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Disfraz;
use App\Models\User;

class DisfrazPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Disfraz');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Disfraz $disfraz): bool
    {
        return $user->checkPermissionTo('view Disfraz');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Disfraz');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Disfraz $disfraz): bool
    {
        return $user->checkPermissionTo('update Disfraz');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Disfraz $disfraz): bool
    {
        return $user->checkPermissionTo('delete Disfraz');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Disfraz');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Disfraz $disfraz): bool
    {
        return $user->checkPermissionTo('restore Disfraz');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Disfraz');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Disfraz $disfraz): bool
    {
        return $user->checkPermissionTo('replicate Disfraz');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Disfraz');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Disfraz $disfraz): bool
    {
        return $user->checkPermissionTo('force-delete Disfraz');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Disfraz');
    }
}
