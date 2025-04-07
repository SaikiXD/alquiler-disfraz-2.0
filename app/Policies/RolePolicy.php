<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-any Role');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('view Role');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create Role');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('update Role');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('delete Role');
    }
}
