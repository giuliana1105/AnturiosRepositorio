<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Mutator para encriptar la contraseña al asignarla
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Verificar si el usuario es Administrador.
     * Método centralizado para eliminar verificaciones hardcodeadas.
     */
    public function esAdministrador(): bool
    {
        return $this->hasRole('Administrador') || ($this->roles && $this->roles->contains('name', 'Administrador'));
    }

    public function empleado()
    {
        return $this->hasOne(\App\Models\Empleado::class, 'email', 'email');
    }

    public function cargoNombre()
    {
        if ($this->esAdministrador()) {
            return 'Administrador';
        }
        if ($this->empleado) {
            return $this->empleado->cargoNombre();
        }
        if ($this->roles->isNotEmpty()) {
            return $this->roles->first()->name;
        }
        return 'Sin Rol';
    }
}
