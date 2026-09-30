<?php

namespace App\Helpers;

use App\Models\Auditoria;
use Illuminate\Support\Facades\Auth;

class AuditoriaHelper
{
    /**
     * Registra una nueva acción en la tabla de auditorías.
     *
     * @param string $modulo El módulo donde ocurrió la acción (ej. 'Ventas', 'Inventario')
     * @param string $accion El tipo de acción (ej. 'Crear', 'Eliminar', 'Aprobar')
     * @param string $detalles Detalles descriptivos de la acción
     * @return void
     */
    public static function registrar($modulo, $accion, $detalles)
    {
        Auditoria::create([
            'user_id'    => Auth::id(), // Usuario autenticado
            'modulo'     => $modulo,
            'accion'     => $accion,
            'detalles'   => $detalles,
            'ip_address' => request()->ip(), // IP desde donde se hizo la solicitud
        ]);
    }
}
