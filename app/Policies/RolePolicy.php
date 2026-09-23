<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ver roles');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->can('ver roles');
    }

    public function create(User $user): bool
    {
        return $user->can('crear rol');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->can('editar rol');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->can('eliminar rol');
    }
}
