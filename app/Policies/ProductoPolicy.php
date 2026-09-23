<?php

namespace App\Policies;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ver productos');
    }

    public function view(User $user, Producto $producto): bool
    {
        return $user->can('ver productos');
    }

    public function create(User $user): bool
    {
        return $user->can('crear producto');
    }

    public function update(User $user, Producto $producto): bool
    {
        return $user->can('editar producto');
    }

    public function delete(User $user, Producto $producto): bool
    {
        return $user->can('eliminar producto');
    }

    public function restore(User $user, Producto $producto): bool
    {
        return false;
    }

    public function forceDelete(User $user, Producto $producto): bool
    {
        return false;
    }
}
