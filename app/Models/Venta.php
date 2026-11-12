<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'bodega_id',
        'cliente_id',
        'nro_venta',
        'fecha',
        'cliente',
        'ciudad',
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

    public function abonos()
    {
        return $this->hasMany(\App\Models\Abono::class, 'venta_id');
    }

    /**
     * Relación con el modelo Cliente registrado.
     */
    public function clienteRelacion()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Alias de relación para compatibilidad.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Accesor para el nombre del cliente:
     * Si existe en la relación o en la columna string histórica, lo retorna de forma transparente.
     */
    public function getNombreClienteAttribute(): string
    {
        if ($this->relationLoaded('clienteRelacion') && $this->clienteRelacion) {
            return $this->clienteRelacion->nombre_completo;
        }
        return $this->attributes['cliente'] ?? ($this->clienteRelacion?->nombre_completo ?? 'Sin cliente');
    }
}
