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

    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::orderBy('name')->get();

        // Agrupar permisos por módulo para la vista
        $permissionsGrouped = $this->agruparPermisos($permissions);

        return view('users.create', compact('roles', 'permissionsGrouped'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,id',
            'direct_permissions' => 'nullable|array',
            'direct_permissions.*' => 'exists:permissions,id',
        ]);

        // El mutator del modelo User se encarga de encriptar la contraseña
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // Asignamos el rol al usuario
        $role = Role::findById($request->role);
        $user->assignRole($role);

        // Asignar permisos directos adicionales si se seleccionaron
        if ($request->filled('direct_permissions')) {
            $permissionNames = Permission::whereIn('id', $request->direct_permissions)->pluck('name')->toArray();
            $user->givePermissionTo($permissionNames);
        }

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|exists:roles,id',
            'direct_permissions' => 'nullable|array',
            'direct_permissions.*' => 'exists:permissions,id',
        ]);

        // Actualizar datos básicos (sin contraseña si no se proporcionó)
        $user->name = $request->name;
        $user->email = $request->email;

        // Solo actualizar contraseña si se proporcionó una nueva
        if ($request->filled('password')) {
            $user->password = $request->password; // El mutator encripta automáticamente
        }

        $user->save();

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

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $user)
    {
        // Proteger al administrador principal
        if ($user->esAdministrador() && User::role('Administrador')->count() <= 1) {
            return redirect()->route('users.index')
                ->with('error', 'No se puede eliminar al único administrador del sistema.');
        }

        // No permitir que un usuario se elimine a sí mismo
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
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
