<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function productosPorBodega($id)
    {
        // Obtiene los códigos de productos con stock en la bodega seleccionada
        $codigos = DB::table('productos_bodega')
            ->where('bodega_id', $id)
            ->where('cantidad', '>', 0)
            ->pluck('producto_id');

        // Devuelve los productos filtrados
        $productos = Producto::whereIn('codigo', $codigos)
            ->get(['codigo', 'nombre', 'cantidad', 'tipoempaque']);

        return response()->json($productos);
    }

    public function productosMaster()
    {
        $masterBodega = Bodega::where('nombrebodega', 'MASTER')->first();
        $productos = collect();
        if ($masterBodega) {
            $codigos = DB::table('productos_bodega')
                ->where('bodega_id', $masterBodega->idbodega)
                ->where('cantidad', '>', 0)
                ->pluck('producto_id');

            $productos = Producto::whereIn('codigo', $codigos)
                ->get(['codigo', 'nombre', 'cantidad', 'tipoempaque']);
        }
        return response()->json($productos);
    }
}
