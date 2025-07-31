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
}
