@extends('layouts.app')

@section('content')
<div class="container-fluid py-3 px-3 px-md-4">
    <!-- Breadcrumb & Status Wayfinding -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                        <i class="fas fa-home me-1 text-primary"></i> Inicio
                    </a>
                </li>
                <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                    Roles y Permisos
                </li>
            </ol>
        </nav>
    </div>

    <!-- Encabezado de la página -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 pb-3 mb-4 border-bottom" style="border-color: #e2e8f0 !important;">
        <div>
            <h2 class="fw-bold mb-1 text-dark tracking-tight" style="letter-spacing: -0.025em;">
                Roles y Permisos
            </h2>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Gestión estructurada de perfiles de usuario, niveles de autorización y privilegios operativos.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                <i class="fas fa-arrow-left me-1"></i> Volver al Panel
            </a>
            @can('crear rol')
            <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary rounded-3 px-3 py-2 fw-semibold shadow-sm">
                <i class="fas fa-plus me-1"></i> Crear Nuevo Rol
            </a>
            @endcan
        </div>
    </div>

    <!-- Alertas de Sesión -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tarjetas de Resumen Ejecutivo (Estilo Impeccable) -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Roles Configurados</span>
                        <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold" style="font-size: 11px;">
                            Perfiles
                        </span>
                    </div>
                    <div class="metric-value">
                        {{ $roles->total() }}
                        <span class="metric-unit">activos</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-shield-alt text-primary me-1"></i> Perfiles de acceso asignables
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Usuarios Vinculados</span>
                        <span class="badge rounded-pill bg-info-subtle text-info fw-semibold" style="font-size: 11px;">
                            Cuentas
                        </span>
                    </div>
                    <div class="metric-value">
                        {{ $totalUsuarios ?? '—' }}
                        <span class="metric-unit">usuarios</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-users text-info me-1"></i> Asignados con rol específico
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Permisos Globales</span>
                        <span class="badge rounded-pill bg-success-subtle text-success fw-semibold" style="font-size: 11px;">
                            Capacidades
                        </span>
                    </div>
                    <div class="metric-value">
                        {{ $totalPermisos ?? '—' }}
                        <span class="metric-unit">reglas</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-key text-success me-1"></i> Puntos de control verificados
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mapeo de Colores e Iconos por Módulo -->
    @php
        $moduleMeta = [
            'Dashboard y KPIs'     => ['icon' => 'fa-tachometer-alt', 'color' => '#059669', 'bg' => 'rgba(16, 185, 129, 0.08)'],
            'Catálogo y Productos' => ['icon' => 'fa-cube',           'color' => '#1d4ed8', 'bg' => 'rgba(37, 99, 235, 0.08)'],
            'Clientes'             => ['icon' => 'fa-user-friends',   'color' => '#0891b2', 'bg' => 'rgba(6, 182, 212, 0.08)'],
            'Empleados y Personal' => ['icon' => 'fa-id-card',       'color' => '#be123c', 'bg' => 'rgba(244, 63, 94, 0.08)'],
            'Bodegas y Almacén'    => ['icon' => 'fa-warehouse',     'color' => '#c2410c', 'bg' => 'rgba(234, 88, 12, 0.08)'],
            'Notas y Solicitudes'  => ['icon' => 'fa-file-invoice',   'color' => '#7e22ce', 'bg' => 'rgba(147, 51, 234, 0.08)'],
            'Movimientos'          => ['icon' => 'fa-exchange-alt',   'color' => '#0f766e', 'bg' => 'rgba(20, 184, 166, 0.08)'],
            'Ventas y Cobros'      => ['icon' => 'fa-cash-register',  'color' => '#b45309', 'bg' => 'rgba(217, 119, 6, 0.08)'],
            'Usuarios'             => ['icon' => 'fa-users-cog',      'color' => '#4338ca', 'bg' => 'rgba(99, 102, 241, 0.08)'],
            'Roles y Seguridad'    => ['icon' => 'fa-user-shield',    'color' => '#334155', 'bg' => 'rgba(100, 116, 139, 0.08)'],
            'Otros'                => ['icon' => 'fa-key',            'color' => '#475569', 'bg' => 'rgba(100, 116, 139, 0.08)'],
        ];
    @endphp

    <!-- Lista de Roles -->
    <div class="row g-4">
        @forelse ($roles as $role)
            @php
                $esProtegido = in_array($role->name, $rolesProtegidos);
                $esAdmin = $role->esAdminTotal ?? ($role->name === 'Administrador');
                $gruposValidos = $role->permissionsGrouped ?? [];
            @endphp

            <div class="col-12">
                <div class="card border rounded-3 overflow-hidden shadow-xs role-card bg-white" style="border-color: #e2e8f0 !important;">
                    <div class="card-body p-3.5 p-md-4">
                        <!-- Cabecera del Rol -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-3 border-bottom" style="border-color: #e2e8f0 !important;">
                            <div class="d-flex align-items-center mb-2 mb-md-0">
                                <div class="role-icon-box me-3 {{ $esProtegido ? 'role-icon-protected' : 'role-icon-custom' }}">
                                    @if($role->name === 'Administrador')
                                        <i class="fas fa-user-shield"></i>
                                    @elseif($role->name === 'Jefe de Bodega')
                                        <i class="fas fa-warehouse"></i>
                                    @elseif($role->name === 'Vendedor')
                                        <i class="fas fa-shopping-bag"></i>
                                    @else
                                        <i class="fas fa-user-tag"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <h5 class="mb-0 fw-bold text-dark tracking-tight">{{ $role->name }}</h5>
                                        @if($esProtegido)
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 11px;">
                                                <i class="fas fa-lock me-1 text-warning"></i>Rol del Sistema
                                            </span>
                                        @else
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 11px;">
                                                <i class="fas fa-user-tag me-1"></i>Personalizado
                                            </span>
                                        @endif
                                        @if($esAdmin)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 11px;">
                                                <i class="fas fa-star me-1"></i>Acceso Total
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mt-1 text-muted" style="font-size: 13px;">
                                        <span><i class="fas fa-users text-primary me-1"></i><strong class="font-mono text-dark">{{ $role->users_count }}</strong> {{ $role->users_count === 1 ? 'usuario asignado' : 'usuarios asignados' }}</span>
                                        <span class="mx-2 text-muted">·</span>
                                        <span><i class="fas fa-shield-alt text-success me-1"></i><strong class="font-mono text-dark">{{ $role->permissions->count() }}</strong> permisos en <strong class="font-mono text-dark">{{ count($gruposValidos) }}</strong> módulos</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 align-items-center">
                                @can('editar rol')
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary rounded-3 px-3 py-1.5 fw-medium">
                                    <i class="fas fa-edit me-1"></i> Editar Permisos
                                </a>
                                @endcan

                                @can('eliminar rol')
                                @if(!$esProtegido)
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-3 px-3 py-1.5 delete-role-btn" title="Eliminar este rol">
                                            <i class="fas fa-trash-alt me-1"></i> Eliminar
                                        </button>
                                    </form>
                                @endif
                                @endcan
                            </div>
                        </div>

                        <!-- Sección de Permisos Optimizada (Principios UX: Jerarquía y Progresión) -->
                        @if($esAdmin)
                            <!-- CASO ADMINISTRADOR: Banner elegante de Control Total sin saturar visualmente -->
                            <div class="p-3 rounded-3 border d-flex flex-wrap justify-content-between align-items-center mb-2" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                                <div class="d-flex align-items-center mb-2 mb-md-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background: rgba(16, 185, 129, 0.12); color: #059669; flex-shrink: 0;">
                                        <i class="fas fa-check-double fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 13.5px;">
                                            Control Total Irrestricto
                                        </div>
                                        <div class="text-muted" style="font-size: 12.5px;">
                                            Este perfil cuenta con todos los privilegios administrativos, operativos y de configuración habilitados.
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-1.5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePermisos-{{ $role->id }}" aria-expanded="false">
                                    <i class="fas fa-list-ul me-1"></i> Ver desglose por módulo ({{ $role->permissions->count() }})
                                </button>
                            </div>

                            <!-- Desglose colapsable organizado por módulo -->
                            <div class="collapse mt-3" id="collapsePermisos-{{ $role->id }}">
                                <div class="modules-grid">
                                    @foreach($gruposValidos as $nombreModulo => $permisosModulo)
                                        @php
                                            $meta = $moduleMeta[$nombreModulo] ?? ['icon' => 'fa-folder', 'color' => '#475569', 'bg' => 'rgba(100, 116, 139, 0.08)'];
                                        @endphp
                                        <div class="module-group-card p-3 rounded-3 border" style="border-color: #e2e8f0 !important;">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas {{ $meta['icon'] }} me-2" style="color: {{ $meta['color'] }}; font-size: 13px;"></i>
                                                <span class="fw-bold text-dark" style="font-size: 13px;">{{ $nombreModulo }}</span>
                                                <span class="badge rounded-pill ms-auto font-mono" style="background: {{ $meta['bg'] }}; color: {{ $meta['color'] }}; font-size: 11px;">
                                                    {{ $permisosModulo->count() }}
                                                </span>
                                            </div>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($permisosModulo as $perm)
                                                    <span class="badge bg-white text-secondary border fw-normal py-1 px-2" style="font-size: 11px; border-color: #e2e8f0 !important;">
                                                        {{ $perm->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <!-- CASO ROLES OPERATIVOS: Organizados limpiamente por módulos -->
                            @if(count($gruposValidos) > 0)
                                <div class="modules-grid">
                                    @foreach($gruposValidos as $nombreModulo => $permisosModulo)
                                        @php
                                            $meta = $moduleMeta[$nombreModulo] ?? ['icon' => 'fa-folder', 'color' => '#475569', 'bg' => 'rgba(100, 116, 139, 0.08)'];
                                        @endphp
                                        <div class="module-group-card p-3 rounded-3 border" style="border-color: #e2e8f0 !important;">
                                            <div class="d-flex align-items-center mb-2 pb-1 border-bottom" style="border-color: #edf2f7 !important;">
                                                <i class="fas {{ $meta['icon'] }} me-2" style="color: {{ $meta['color'] }}; font-size: 13px;"></i>
                                                <span class="fw-bold text-dark" style="font-size: 13px;">{{ $nombreModulo }}</span>
                                                <span class="badge rounded-pill ms-auto font-mono" style="background: {{ $meta['bg'] }}; color: {{ $meta['color'] }}; font-size: 11px;">
                                                    {{ $permisosModulo->count() }} {{ $permisosModulo->count() === 1 ? 'permiso' : 'permisos' }}
                                                </span>
                                            </div>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($permisosModulo as $perm)
                                                    <span class="badge bg-white text-dark border fw-medium py-1 px-2 shadow-xs" style="font-size: 11.5px; border-color: #e2e8f0 !important;">
                                                        <i class="fas fa-check text-success me-1" style="font-size: 9px;"></i>{{ $perm->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-muted fst-italic py-2" style="font-size: 13px;">
                                    <i class="fas fa-exclamation-circle me-1"></i> Este rol aún no tiene permisos específicos asignados.
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border rounded-3 text-center py-5 bg-white" style="border-color: #e2e8f0 !important;">
                    <div class="card-body" style="color: var(--muted);">
                        <i class="fas fa-shield-alt d-block mb-3" style="font-size: 48px; opacity: 0.3;"></i>
                        <h5 class="fw-bold text-dark">No hay roles registrados</h5>
                        <p class="text-muted">Crea un nuevo rol para asignar permisos a los usuarios.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($roles->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $roles->onEachSide(1)->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

<style>
.role-card {
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.role-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06) !important;
}
.role-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.role-icon-protected {
    background: #fef3c7;
    color: #d97706;
}
.role-icon-custom {
    background: rgba(0, 151, 167, 0.1);
    color: var(--primary);
}
.modules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 12px;
}
.module-group-card {
    background: #f8fafc;
    transition: background-color 0.15s ease;
}
.module-group-card:hover {
    background: #f1f5f9;
}
.shadow-xs {
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
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
