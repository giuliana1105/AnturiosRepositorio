<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Asegúrate de importar esto
class RoleController extends Controller
{
    
    use AuthorizesRequests; 
    public function __construct()
{
    
    $this->authorizeResource(Role::class, 'roles'); // ✅ Debe coincidir con la ruta
}
    public function index()
    {
        $roles = Role::paginate(10);
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|unique:roles,name',
    //         'permissions' => 'required|array',
    //     ]);

    //     $role = Role::create(['name' => $request->name]);
    //     $role->permissions()->sync($request->permissions);

    //     return redirect()->route('roles.index');
    // }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|unique:roles,name',
        'permissions' => 'required|array',
    ]);

    $role = Role::create(['name' => $request->name]);
    $role->permissions()->sync($request->permissions);

    // Mensaje de éxito
    return redirect()->route('roles.index')->with('success', 'El rol ha sido creado exitosamente.');
}


    // public function edit(Role $role)
    // {
        
    //     $permissions = Permission::all();
    //     return view('roles.edit', compact('role', 'permissions'));
    // }


    public function edit(Role $role)
{
    $permissions = Permission::all();
    return view('roles.edit', compact('role', 'permissions'));
}

    // public function update(Request $request, Role $role)
    // {
    //     $request->validate([
    //         'name' => 'required|string|unique:roles,name,' . $role->id,
    //         'permissions' => 'required|array',
    //     ]);

    //     $role->update(['name' => $request->name]);
    //     $role->permissions()->sync($request->permissions);

    //     return redirect()->route('roles.index');
    // }


    public function update(Request $request, Role $role)
{
    $request->validate([
        'name' => 'required|string|unique:roles,name,' . $role->id,  // Validación para que el nombre sea único, pero se permita el nombre actual.
        'permissions' => 'required|array',  // Validación para asegurarse de que se seleccionen permisos
    ]);

    // Actualizar el nombre del rol
    $role->update(['name' => $request->name]);

    // Sincronizar los permisos seleccionados
    $role->permissions()->sync($request->permissions);

    // Redirigir a la lista de roles con un mensaje de éxito
    return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente');
}

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index');
    }
}
