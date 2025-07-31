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

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'codigocargo', 'codigocargo'); // Cambio en la relación
    }

}
