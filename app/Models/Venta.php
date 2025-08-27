<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'bodega_id',
        'fecha',
        'cliente',
        'ciudad', // <-- Nuevo campo
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
