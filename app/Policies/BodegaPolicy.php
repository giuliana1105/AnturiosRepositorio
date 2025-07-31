<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Bodega;

class BodegaPolicy
{
    public function view(User $user, Bodega $bodega)
    {
        // Verificar si el usuario tiene permiso para ver una bodega
        return $user-> can('ver bodega');
        return $user->role === 'admin'; // Ejemplo
    }

    public function create(User $user)
    {
        // Permitir solo a los administradores crear una bodega
        return $user->role === 'admin';
    }

    public function update(User $user, Bodega $bodega)
    {
        // Permitir solo al propietario o admin editar
        return $user->id === $bodega->user_id || $user->role === 'admin';
    }

    public function delete(User $user, Bodega $bodega)
    {
        // Solo el propietario o admin puede eliminar
        return $user->id === $bodega->user_id || $user->role === 'admin';
    }
}
