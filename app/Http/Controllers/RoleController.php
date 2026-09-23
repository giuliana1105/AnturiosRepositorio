<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RoleController extends Controller
{
    use AuthorizesRequests;

    // Roles del sistema que no se pueden eliminar
    protected $rolesProtegidos = ['Administrador', 'Jefe de Bodega', 'Vendedor'];

    public function __construct()
    {
        $this->authorizeResource(Role::class, 'role');
    }

    public function index()
    {
        $roles = Role::with('permissions')->withCount('users')->paginate(10);
        $rolesProtegidos = $this->rolesProtegidos;
        return view('roles.index', compact('roles', 'rolesProtegidos'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        $permissionsGrouped = $this->agruparPermisos($permissions);
        return view('roles.create', compact('permissionsGrouped'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $request->name]);
        $permissionsIds = array_map('intval', $request->permissions);
        $role->syncPermissions($permissionsIds);

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', 'El rol ha sido creado exitosamente.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $permissionsGrouped = $this->agruparPermisos($permissions);
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();
        $esProtegido = in_array($role->name, $this->rolesProtegidos);

        return view('roles.edit', compact('role', 'permissionsGrouped', 'rolePermissionIds', 'esProtegido'));
    }

    public function update(Request $request, Role $role)
    {
        $esProtegido = in_array($role->name, $this->rolesProtegidos);

        $rules = [
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ];

        // Si el rol es protegido, no se puede cambiar el nombre
        if (!$esProtegido) {
            $rules['name'] = 'required|string|unique:roles,name,' . $role->id;
        }

        $request->validate($rules);

        // Solo actualizar el nombre si no es un rol protegido
        if (!$esProtegido && $request->filled('name')) {
            $role->update(['name' => $request->name]);
        }

        // Sincronizar los permisos seleccionados
        $permissionsIds = array_map('intval', $request->permissions);
        $role->syncPermissions($permissionsIds);

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente.');
    }

    public function destroy(Role $role)
    {
        // No permitir eliminar roles protegidos del sistema
        if (in_array($role->name, $this->rolesProtegidos)) {
            return redirect()->route('roles.index')
                ->with('error', 'El rol "' . $role->name . '" es un rol del sistema y no puede ser eliminado.');
        }

        // Verificar si hay usuarios asignados a este rol
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'No se puede eliminar el rol "' . $role->name . '" porque tiene usuarios asignados. Reasígnelos primero.');
        }

        $role->delete();

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente.');
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
