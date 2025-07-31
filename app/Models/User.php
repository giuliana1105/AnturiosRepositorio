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
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value)) {
            throw new \InvalidArgumentException('La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.');
        }
        $this->attributes['password'] = bcrypt($value); // Laravel la encripta automáticamente
    }
}
