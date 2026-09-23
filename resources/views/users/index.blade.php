@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Gestión de Usuarios</h3>
            <p class="page-subtitle">Administración de accesos y cuentas del sistema</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Nuevo Usuario
        </a>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Usuario</th>
                            <th>Rol</th>
                            <th>Permisos Directos</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-placeholder me-3">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="color: var(--foreground);">{{ $user->name }}</div>
                                            <div class="text-muted" style="font-size: 13px;">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->roles->isNotEmpty())
                                        @foreach($user->roles as $role)
                                            @php
                                                $roleColors = [
                                                    'Administrador' => 'background: #fee2e2; color: #991b1b;',
                                                    'Jefe de Bodega' => 'background: #fef3c7; color: #92400e;',
                                                    'Vendedor' => 'background: #dbeafe; color: #1e40af;',
                                                ];
                                                $roleStyle = $roleColors[$role->name] ?? 'background: #f3f4f6; color: #374151;';
                                            @endphp
                                            <span class="badge rounded-pill" style="{{ $roleStyle }} font-size: 12px; padding: 5px 12px; font-weight: 500;">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted" style="font-size: 13px;">Sin rol</span>
                                    @endif
                                </td>
                                <td>
                                    @php $directPerms = $user->getDirectPermissions(); @endphp
                                    @if($directPerms->isNotEmpty())
                                        <span class="badge bg-info" style="font-size: 11px;">
                                            {{ $directPerms->count() }} adicional{{ $directPerms->count() > 1 ? 'es' : '' }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size: 12px;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->must_change_password ?? false)
                                        <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 11px;">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Cambiar contraseña
                                        </span>
                                    @else
                                        <span class="badge" style="background: #dcfce7; color: #166534; font-size: 11px;">
                                            <i class="fas fa-check-circle me-1"></i>Activo
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-icon btn-info" title="Editar" style="color: white;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-icon btn-danger delete-btn" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="fas fa-users" style="font-size: 48px; color: var(--border-color);"></i>
                                    </div>
                                    No hay usuarios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($users, 'links'))
                <div class="p-3 border-top">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: var(--accent-subtle);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            if(confirm('¿Está seguro de que desea eliminar este usuario?')) {
                this.closest('form').submit();
            }
        });
    });
});
</script>
@endsection
