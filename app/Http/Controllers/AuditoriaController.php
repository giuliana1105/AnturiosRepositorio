<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditoria;
use App\Models\User;

class AuditoriaController extends Controller
{
    /**
     * Constructor para aplicar middleware de permisos.
     */
    public function __construct()
    {
        // Solo usuarios con este permiso pueden acceder
        $this->middleware('permission:ver auditorias');
    }

    /**
     * Muestra la lista de auditorías con filtros.
     */
    public function index(Request $request)
    {
        $query = Auditoria::with('user')->orderBy('created_at', 'desc');

        // Filtro por usuario
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtro por módulo
        if ($request->filled('modulo')) {
            $query->where('modulo', $request->modulo);
        }

        // Filtro por acción
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        // Filtro por fecha inicio
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }

        // Filtro por fecha fin
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $auditorias = $query->paginate(20)->withQueryString();
        
        $users = User::orderBy('name')->get();

        return view('auditorias.index', compact('auditorias', 'users'));
    }
}
