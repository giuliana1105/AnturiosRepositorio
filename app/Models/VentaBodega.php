<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaBodega extends Model
{
    protected $table = 'venta_bodegas';

    protected $fillable = [
        'bodega_id',
        'producto_id',
        'fecha',
        'cantidad',
        'tipoempaque',
        'precio_unitario',
        'precio_total',
    ];

    public function bodega()
    {
        return $this->belongsTo(\App\Models\Bodega::class, 'bodega_id', 'idbodega');
    }

    public function producto()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'producto_id', 'codigo');
    }
}
