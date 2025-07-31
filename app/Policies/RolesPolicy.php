<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Role;

class RolePolicy
{
    public function view(User $user, Role $role)
    {
        // Verificar si el usuario tiene permiso para ver una bodega
        return $user-> can('ver rol');
        return $user->role === 'admin'; // Ejemplo
    }

    public function create(User $user)
    {
        // Permitir solo a los administradores crear una bodega
        return $user->role === 'admin';
    }

    public function update(User $user, Role $role)
    {
        // Permitir solo al propietario o admin editar
        return $user->id === $role->user_id || $user->role === 'admin';
    }

    public function delete(User $user, Role $role)
    {
        // Solo el propietario o admin puede eliminar
        return $user->id === $role->user_id || $user->role === 'admin';
    }
}
