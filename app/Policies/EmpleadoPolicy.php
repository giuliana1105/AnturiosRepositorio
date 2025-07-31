<?php

namespace App\Policies;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Auth\Access\Response;

//Maneja la autorización de los usuarios
class EmpleadoPolicy
{
    //Toma como parámatro el usuario autenticado
    public function viewAny(User $user): bool
    {
        return $user-> can('ver empleado');
    }
    public function view(User $user, Empleado $empleado): bool
    {
        return $user-> can('ver Empleado');
    }
    public function create(User $user): bool
    {
        return $user-> can('crear Empleado');
    }
    public function update(User $user, Empleado $empleado): bool
    {
        return $user-> can('editar Empleado');
    }
    public function delete(User $user, Empleado $empleado): bool
    {
        return $user-> can('eliminar Empleado');
    }
    public function restore(User $user, Empleado $empleado): bool
    {
        return false;
    }
    public function forceDelete(User $user, Empleado $empleado): bool
    {
        return false;
    }
}
