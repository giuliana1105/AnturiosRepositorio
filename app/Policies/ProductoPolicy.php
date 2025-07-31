<?php

namespace App\Policies;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductoPolicy
{
      public function viewAny(User $user): bool
    {
        return $user-> can('ver Producto');
    }
    public function view(User $user, Producto $Producto): bool
    {
        return $user-> can('ver Producto');
    }
    public function create(User $user): bool
    {
        return $user-> can('crear Producto');
    }
    public function update(User $user, Producto $Producto): bool
    {
        return $user-> can('editar Producto');
    }
    public function delete(User $user, Producto $Producto): bool
    {
        return $user-> can('eliminar Producto');
    }
    public function restore(User $user, Producto $Producto): bool
    {
        return false;
    }
    public function forceDelete(User $user, Producto $Producto): bool
    {
        return false;
    }
}
