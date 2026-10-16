<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVentaBodega extends Model
{
    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'tipoempaque',
        'precio_unitario',
        'precio_total',
        'es_extra',
        'nombre_extra',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'codigo');
    }
}
