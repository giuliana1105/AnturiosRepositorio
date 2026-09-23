<?php

namespace App\Policies;

use App\Models\TransaccionProducto;
use App\Models\User;
use Illuminate\Auth\Access\Response;

//Clases que definen los permisos que tiene un usuario en un modelo específico

class TransaccionProductoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ver transacciones');
    }

    public function view(User $user, TransaccionProducto $transaccionProducto): bool
    {
        return $user->can('ver transacciones');
    }

    public function create(User $user): bool
    {
        return $user->can('crear nota');
    }

    public function update(User $user, TransaccionProducto $transaccionProducto): bool
    {
        return $user->can('aprobar solicitudes');
    }

    public function delete(User $user, TransaccionProducto $transaccionProducto): bool
    {
        return $user->can('aprobar solicitudes');
    }

    public function restore(User $user, TransaccionProducto $transaccionProducto): bool
    {
        return false;
    }

    public function forceDelete(User $user, TransaccionProducto $transaccionProducto): bool
    {
        return false;
    }
}
