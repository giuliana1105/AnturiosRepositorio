<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nombres',
        'apellidos',
        'cedula',
        'direccion',
        'correo',
        'celular',
        'nombre_empresa',
    ];

    /**
     * Accesor para obtener el nombre completo del cliente.
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombres} {$this->apellidos}");
    }

    /**
     * Relación con las ventas realizadas al cliente.
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }
}

