@extends('layouts.app')

@section('content')

    <div class="container">
        <h2 class="text-center">Listado de Roles</h2>

        <!-- Alertas de éxito o error -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Botón para crear un nuevo rol -->
        <div class="mb-3 text-right">
            <a href="{{ route('roles.create') }}" class="btn btn-primary" style="background-color: #88022D">Crear Rol</a>
        </div>

        <!-- Tabla de roles -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Permisos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            <ul>
                                @foreach ($role->permissions as $permission)
                                    <li>{{ $permission->name }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <!-- Botón de Editar -->
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <!-- Formulario de Eliminar -->
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Estás seguro de eliminar este rol?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No hay roles registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="d-flex justify-content-center">
            {{ $roles->links() }} <!-- Esto ahora funciona con la paginación -->
        </div>
    </div>

@endsection
