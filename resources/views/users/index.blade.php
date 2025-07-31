@extends('layouts.app')

@section('content')

    <div class="container">
        <h2 class="text-center">Listado de Usuarios</h2>

        <!-- Botón para crear un nuevo usuario -->
        <div class="mb-3 text-right">
            <a href="{{ route('users.create') }}" class="btn btn-primary" style="background-color: #88022D">Crear Usuario</a>
        </div>

        <!-- Tabla de usuarios -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Editar</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación (si aplica) -->
        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>

@endsection
