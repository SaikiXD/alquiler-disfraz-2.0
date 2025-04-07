<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Permission');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->checkPermissionTo('view Permission');
    }

    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Permission');
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->checkPermissionTo('update Permission');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->checkPermissionTo('delete Permission');
    }

    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Permission');
    }

    public function restore(User $user, Permission $permission): bool
    {
        return $user->checkPermissionTo('restore Permission');
    }

    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Permission');
    }

    public function replicate(User $user, Permission $permission): bool
    {
        return $user->checkPermissionTo('replicate Permission');
    }

    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Permission');
    }

    public function forceDelete(User $user, Permission $permission): bool
    {
        return $user->checkPermissionTo('force-delete Permission');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Permission');
    }
}
