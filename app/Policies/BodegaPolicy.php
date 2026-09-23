<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Bodega;

class BodegaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ver bodegas');
    }

    public function view(User $user, Bodega $bodega): bool
    {
        return $user->can('ver bodegas');
    }

    public function create(User $user): bool
    {
        return $user->can('crear bodega');
    }

    public function update(User $user, Bodega $bodega): bool
    {
        return $user->can('editar bodega');
    }

    public function delete(User $user, Bodega $bodega): bool
    {
        return $user->can('eliminar bodega');
    }
}
