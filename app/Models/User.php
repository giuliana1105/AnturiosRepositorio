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
        'email',      // <-- Agrega esto
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Mutator para validar y encriptar la contraseña al asignarla
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function empleado()
    {
        return $this->hasOne(\App\Models\Empleado::class, 'email', 'email');
    }

    public function cargoNombre()
    {
        return $this->empleado ? $this->empleado->cargoNombre() : null;
    }
}
