<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests; // Asegúrate de incluir este trait

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user'); // Cambié 'users' a 'user', el nombre del modelo en singular
    }

    public function index(): View
    {
        // Paginación de los usuarios
        $users = User::paginate(10);  // Limitamos los resultados por página

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all(); // Cargamos todos los roles para la vista
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,id', // Validamos que el rol exista en la base de datos
        ]);

        // Creamos el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Aseguramos que la contraseña se cifre
        ]);

        // Asignamos el rol al usuario
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $user)
    {
        $roles = Role::all(); // Cargamos todos los roles para la vista
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Validación de los datos del formulario para la actualización
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id, // Aseguramos que el email sea único, exceptuando el usuario actual
            'password' => 'nullable|string|min:8', // La contraseña es opcional al actualizar
            'role' => 'required|exists:roles,id', // Validamos que el rol exista en la base de datos
        ]);

        // Actualizamos los datos del usuario
        $user->update($request->only('name', 'email', 'password'));

        // Si se proporcionó una nueva contraseña, la ciframos
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Actualizamos los roles del usuario
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $user)
    {
        // Eliminamos el usuario
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
