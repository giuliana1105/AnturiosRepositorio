@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Roles y Permisos</h3>
            <p class="page-subtitle">Gestión de roles y permisos del sistema</p>
        </div>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Rol
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-3">
        @forelse ($roles as $role)
            <div class="col-12">
                <div class="card role-card {{ in_array($role->name, $rolesProtegidos) ? 'role-protected' : '' }}">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center">
                                <div class="role-icon me-3">
                                    @if($role->name === 'Administrador')
                                        <i class="fas fa-crown"></i>
                                    @elseif($role->name === 'Jefe de Bodega')
                                        <i class="fas fa-warehouse"></i>
                                    @elseif($role->name === 'Vendedor')
                                        <i class="fas fa-shopping-bag"></i>
                                    @else
                                        <i class="fas fa-user-tag"></i>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold" style="color: var(--foreground);">
                                        {{ $role->name }}
                                        @if(in_array($role->name, $rolesProtegidos))
                                            <span class="badge bg-warning text-dark ms-2" style="font-size: 11px; font-weight: 500;">
                                                <i class="fas fa-lock me-1"></i>Protegido
                                            </span>
                                        @endif
                                    </h5>
                                    <span class="text-muted" style="font-size: 13px;">
                                        <i class="fas fa-users me-1"></i>{{ $role->users_count }} {{ $role->users_count === 1 ? 'usuario' : 'usuarios' }}
                                        &nbsp;·&nbsp;
                                        <i class="fas fa-key me-1"></i>{{ $role->permissions->count() }} permisos
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </a>
                                @if(!in_array($role->name, $rolesProtegidos))
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-role-btn" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="permissions-grid">
                            @foreach ($role->permissions as $permission)
                                @php
                                    $colorMap = [
                                        'dashboard' => ['bg' => '#e8f5e9', 'color' => '#2e7d32', 'icon' => 'fa-tachometer-alt'],
                                        'producto' => ['bg' => '#e3f2fd', 'color' => '#1565c0', 'icon' => 'fa-cube'],
                                        'empleado' => ['bg' => '#fce4ec', 'color' => '#c62828', 'icon' => 'fa-id-card'],
                                        'bodega' => ['bg' => '#fff3e0', 'color' => '#e65100', 'icon' => 'fa-warehouse'],
                                        'nota' => ['bg' => '#f3e5f5', 'color' => '#6a1b9a', 'icon' => 'fa-file-invoice'],
                                        'solicitud' => ['bg' => '#f3e5f5', 'color' => '#6a1b9a', 'icon' => 'fa-file-invoice'],
                                        'transacci' => ['bg' => '#e0f2f1', 'color' => '#00695c', 'icon' => 'fa-exchange-alt'],
                                        'historial' => ['bg' => '#e0f2f1', 'color' => '#00695c', 'icon' => 'fa-history'],
                                        'venta' => ['bg' => '#fff8e1', 'color' => '#f57f17', 'icon' => 'fa-cash-register'],
                                        'cuentas' => ['bg' => '#fff8e1', 'color' => '#f57f17', 'icon' => 'fa-file-invoice-dollar'],
                                        'liquidacion' => ['bg' => '#fff8e1', 'color' => '#f57f17', 'icon' => 'fa-calculator'],
                                        'usuario' => ['bg' => '#e8eaf6', 'color' => '#283593', 'icon' => 'fa-users-cog'],
                                        'rol' => ['bg' => '#efebe9', 'color' => '#4e342e', 'icon' => 'fa-user-shield'],
                                    ];
                                    $style = ['bg' => '#f5f5f5', 'color' => '#616161', 'icon' => 'fa-key'];
                                    foreach ($colorMap as $key => $val) {
                                        if (stripos($permission->name, $key) !== false) {
                                            $style = $val;
                                            break;
                                        }
                                    }
                                @endphp
                                <span class="permission-badge" style="background: {{ $style['bg'] }}; color: {{ $style['color'] }};">
                                    <i class="fas {{ $style['icon'] }} me-1" style="font-size: 10px;"></i>{{ $permission->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5" style="color: var(--muted);">
                        <i class="fas fa-shield-alt d-block mb-2" style="font-size: 48px; opacity: 0.3;"></i>
                        No hay roles registrados
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $roles->links() }}
    </div>
</div>

<style>
.role-card {
    transition: all 0.2s ease;
    border-left: 4px solid var(--primary);
}
.role-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}
.role-card.role-protected {
    border-left-color: #f59e0b;
}
.role-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-sm);
    background: var(--accent-subtle);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}
.role-protected .role-icon {
    background: #fef3c7;
    color: #d97706;
}
.permissions-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}
.permission-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-role-btn').forEach(button => {
        button.addEventListener('click', function() {
            if(confirm('¿Está seguro de que desea eliminar este rol? Los usuarios asignados perderán sus permisos.')) {
                this.closest('form').submit();
            }
        });
    });
});
</script>
@endsection
