<?php

namespace App\Policies;

use App\Models\TransaccionProducto;
use App\Models\User;
use Illuminate\Auth\Access\Response;

//Clases que definen los permisos que tiene un uusario en un modelo específico 

class TransaccionProductoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user-> can('ver TransaccionProducto');
    }
    public function view(User $user, TransaccionProducto $transaccionProducto): bool
    {
        return $user-> can('ver TransaccionProducto');
    }
    public function create(User $user): bool
    {
        return $user-> can('crear TransaccionProducto');
    }
    public function update(User $user, TransaccionProducto $TransaccionProducto): bool
    {
        return $user-> can('editar TransaccionProducto');
    }
    public function delete(User $user, TransaccionProducto $TransaccionProducto): bool
    {
        return $user-> can('eliminar TransaccionProducto');
    }
    public function restore(User $user, TransaccionProducto $TransaccionProducto): bool
    {
        return false;
    }
    public function forceDelete(User $user, TransaccionProducto $TransaccionProducto): bool
    {
        return false;
    }
}
