<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'codigo'; // Asegurar que la clave primaria es 'codigo'
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'cantidad',
        'tipoempaque'
    ];

    public function bodegas()
    {
        return $this->belongsToMany(Bodega::class, 'productos_bodega', 'producto_id', 'bodega_id');
    }

}
