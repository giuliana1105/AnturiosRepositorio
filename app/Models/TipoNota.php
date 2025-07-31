<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoNota extends Model
{
    use HasFactory;

    protected $table = 'tipo_nota';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'codigo',
        'tiponota',
        'nro_identificacion',
        'idbodega',
        'fechanota',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleTipoNota::class, 'tipo_nota_id', 'codigo');
    }

    public function transaccion()
    {
        return $this->hasOne(TransaccionProducto::class, 'tipo_nota_id', 'codigo');
    }

    // 🔹 Agregar la relación con Empleado (responsableEmpleado)
    public function responsableEmpleado()
    {
        return $this->belongsTo(Empleado::class, 'nro_identificacion', 'nro_identificacion');
    }

    public function bodega()
    {
        return $this->belongsTo(Bodega::class, 'idbodega', 'idbodega');
    }
}
