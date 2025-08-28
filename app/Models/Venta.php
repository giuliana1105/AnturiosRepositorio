<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'bodega_id',
        'nro_venta',      // <-- Agrega esto
        'fecha',
        'cliente',
        'ciudad',         // <-- Ya lo tienes, pero falta en migración
        'total_venta',
        'tipo_pago',
    ];

    public function bodega()
    {
        return $this->belongsTo(Bodega::class, 'bodega_id', 'idbodega');
    }
    public function detalles()
    {
        return $this->hasMany(DetalleVentaBodega::class, 'venta_id');
    }
}
