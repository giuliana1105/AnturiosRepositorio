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

    public function index(): View
    {
        $users = User::with('roles')->paginate(10);

        return view('users.index', compact('users'));
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
            'Dashboard' => ['dashboard'],
            'Productos' => ['producto'],
            'Empleados' => ['empleado'],
            'Bodegas' => ['bodega'],
            'Notas / Solicitudes' => ['nota', 'solicitud'],
            'Transacciones' => ['transacci', 'historial transferencias'],
            'Ventas' => ['venta', 'cuentas cobrar', 'liquidacion'],
            'Usuarios' => ['usuario'],
            'Roles' => ['rol'],
        ];

        $grouped = [];
        $assigned = [];

        foreach ($grupos as $label => $keywords) {
            $grouped[$label] = $permissions->filter(function ($perm) use ($keywords, &$assigned) {
                foreach ($keywords as $keyword) {
                    if (stripos($perm->name, $keyword) !== false && !in_array($perm->id, $assigned)) {
                        $assigned[] = $perm->id;
                        return true;
                    }
                }
                return false;
            });
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
