@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Roles</h3>
            <p class="page-subtitle">Gestión de roles y permisos del sistema</p>
        </div>
        <a href="{{ route('roles.create') }}" class="btn btn-info">
            <i class="fas fa-plus"></i> Crear Rol
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Permisos</th>
                            <th style="width: 120px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td class="fw-medium">{{ $role->name }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($role->permissions as $permission)
                                            <span class="badge bg-secondary">{{ $permission->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Estás seguro de eliminar este rol?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4" style="color: var(--muted);">
                                    <i class="fas fa-shield-alt d-block mb-2" style="font-size: 24px; opacity: 0.4;"></i>
                                    No hay roles registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $roles->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
