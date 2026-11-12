<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request): View
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $search = strtolower(trim($request->search));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role);
            });
        }

        $users = $query->orderBy('name', 'asc')->paginate(10)->appends($request->query());
        $roles = Role::orderBy('name')->get();

        $totalUsuarios = User::count();
        $totalRoles = Role::count();
        $usuariosPendientesClave = User::where('must_change_password', true)->count();
        $usuariosActivos = $totalUsuarios - $usuariosPendientesClave;

        return view('users.index', compact('users', 'roles', 'totalUsuarios', 'totalRoles', 'usuariosActivos', 'usuariosPendientesClave'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::orderBy('name')->get();
        $permissionsGrouped = $this->agruparPermisos($permissions);
        $directPermissions = $user->getDirectPermissions()->pluck('id')->toArray();

        return view('users.edit', compact('user', 'roles', 'permissionsGrouped', 'directPermissions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,id',
            'direct_permissions' => 'nullable|array',
            'direct_permissions.*' => 'exists:permissions,id',
        ]);

        // Sincronizar rol
        $role = Role::findById($request->role);
        $user->syncRoles([$role]);

        // Sincronizar el rol con el empleado (si existe) para mantener la coherencia
        if ($user->empleado) {
            \App\Models\Empleado::where('email', $user->email)->update(['codigocargo' => $role->id]);
        }

        // Sincronizar permisos directos
        if ($request->has('direct_permissions')) {
            $permissionNames = Permission::whereIn('id', $request->direct_permissions)->pluck('name')->toArray();
            $user->syncPermissions($permissionNames);
        } else {
            $user->syncPermissions([]); // Quitar todos los permisos directos
        }

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('users.index')->with('success', 'Rol y permisos del usuario actualizados exitosamente.');
    }

    /**
     * Agrupar permisos por módulo para mostrar organizados en la vista.
     */
    private function agruparPermisos($permissions)
    {
        $grupos = [
            'Dashboard y KPIs'     => ['dashboard', 'kpis', 'reporte'],
            'Catálogo y Productos' => ['producto'],
            'Clientes'             => ['cliente'],
            'Empleados y Personal' => ['empleado'],
            'Bodegas y Almacén'    => ['bodega'],
            'Notas y Solicitudes'  => ['nota', 'solicitud'],
            'Movimientos'          => ['transacci', 'transferencia', 'historial'],
            'Ventas y Cobros'      => ['venta', 'cuentas cobrar', 'liquidacion', 'cobrar'],
            'Usuarios'             => ['usuario'],
            'Roles y Seguridad'    => ['rol', 'auditoria'],
        ];

        $grouped = [];
        $assigned = [];

        foreach ($grupos as $label => $keywords) {
            $matched = $permissions->filter(function ($perm) use ($keywords, &$assigned) {
                foreach ($keywords as $keyword) {
                    if (stripos($perm->name, $keyword) !== false && !in_array($perm->id, $assigned)) {
                        $assigned[] = $perm->id;
                        return true;
                    }
                }
                return false;
            });

            if ($matched->isNotEmpty()) {
                $grouped[$label] = $matched;
            }
        }

        // Permisos que no encajaron en ningún grupo
        $otros = $permissions->filter(function ($perm) use ($assigned) {
            return !in_array($perm->id, $assigned);
        });
        if ($otros->isNotEmpty()) {
            $grouped['Otros'] = $otros;
        }

        return $grouped;
    }
}
