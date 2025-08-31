<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'nro_identificacion';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nombreemp',
        'apellidoemp',
        'email',
        'tipo_identificacion',
        'nro_identificacion',
        'idbodega',
        'codigocargo', // Cambio de idcargo a codigocargo
        'nro_telefono',
        'direccionemp'
    ];

    public function bodega()
    {
        return $this->belongsTo(Bodega::class, 'idbodega', 'idbodega');
    }

    // public function cargo()
    // {
    //     return $this->belongsTo(Cargo::class, 'codigocargo', 'codigocargo'); // Cambio en la relación
    // }

    public function cargoNombre()
    {
        $cargos = [
            1 => 'Administrador',
            2 => 'Vendedor camión',
            3 => 'Vendedor',
            4 => 'Jefe de bodega',
            5 => 'Gerente',
        ];
        return $cargos[$this->codigocargo] ?? 'Desconocido';
    }

    protected static function booted()
    {
        static::created(function ($empleado) {
            // Solo crear si no existe usuario con ese email
            if (!User::where('email', $empleado->email)->exists()) {
                User::create([
                    'name' => $empleado->nombreemp . ' ' . $empleado->apellidoemp,
                    'email' => $empleado->email,
                    'username' => $empleado->email,
                    'password' => $empleado->nro_identificacion,
                    'must_change_password' => true, // <--- importante
                ]);
            }
        });

        static::updated(function ($empleado) {
            $user = User::where('email', $empleado->getOriginal('email'))->first();
            if ($user) {
                $user->name = $empleado->nombreemp . ' ' . $empleado->apellidoemp;
                $user->email = $empleado->email;
                $user->save();
            }
        });

        static::deleted(function ($empleado) {
            $user = User::where('email', $empleado->email)->first();
            if ($user) {
                $user->delete();
            }
        });
    }
}
