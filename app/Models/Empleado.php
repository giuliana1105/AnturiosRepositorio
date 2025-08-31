<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
