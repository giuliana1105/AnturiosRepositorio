<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    protected $table = 'bodegas'; // Nombre de la tabla
    protected $primaryKey = 'idbodega'; // Clave primaria personalizada
    public $incrementing = false; // La clave primaria no es autoincremental
    protected $keyType = 'string'; // Tipo de clave primaria

    protected $fillable = ['idbodega', 'nombrebodega', 'descripcion'];
    public function tipoNotas()
    {
        return $this->hasMany(TipoNota::class, 'idbodega', 'idbodega');
    }
    public function productosEnviados()
    {
        return $this->belongsToMany(\App\Models\Producto::class, 'productos_bodega', 'bodega_id', 'producto_id')
            ->withPivot('cantidad', 'fecha', 'es_devolucion')
            ->wherePivot('es_devolucion', false);
    }

    public function productosDevueltos()
    {
        return $this->belongsToMany(\App\Models\Producto::class, 'productos_bodega', 'bodega_id', 'producto_id')
            ->withPivot('cantidad', 'fecha', 'es_devolucion')
            ->wherePivot('es_devolucion', true);
    }
    public function productos()
    {
        return $this->belongsToMany(
            \App\Models\Producto::class,
            'productos_bodega',   // tabla pivote
            'bodega_id',          // clave foránea en la pivote hacia bodega
            'producto_id',        // clave foránea en la pivote hacia producto
            'idbodega',           // clave local en bodegas
            'codigo'              // clave local en productos
        );
    }
}
