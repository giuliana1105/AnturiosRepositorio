<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaccionProducto extends Model
{
    use HasFactory;

    protected $table = 'transaccion_producto';
    protected $fillable = ['tipo_nota_id', 'estado'];

    public function tipoNota()
    {
        return $this->belongsTo(TipoNota::class, 'tipo_nota_id', 'codigo');
    }
}
