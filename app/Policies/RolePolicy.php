<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    /**
     * Determine whether the user can view any roles.
     *
     * @param  User  $user
     */

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param  User  $user
     * @param  Role  $role
     */

    public function view(User $user, Role $role): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create roles.
     */

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the role.
     */

    public function update(User $user, Role $role): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the role.
     */

    public function delete(User $user, Role $role): bool
    {
        return $user->hasRole('admin');
    }
}
