@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de la página -->
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Roles y Permisos</h3>
            <p class="page-subtitle mb-0 text-muted">Gestión de perfiles de usuario y niveles de autorización en el sistema</p>
        </div>
        @can('crear rol')
        <a href="{{ route('roles.create') }}" class="btn btn-primary px-3 rounded-pill shadow-sm">
            <i class="fas fa-plus me-1"></i> Crear Nuevo Rol
        </a>
        @endcan
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

    <!-- Tarjetas de Resumen KPI (Principios de Información General UX) -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 46px; height: 46px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                        <i class="fas fa-shield-alt fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Roles Configurados</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $roles->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 46px; height: 46px; background: rgba(59, 130, 246, 0.12); color: #2563eb;">
                        <i class="fas fa-users fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Usuarios Asignados</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalUsuarios ?? '—' }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 46px; height: 46px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                        <i class="fas fa-key fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Permisos Globales</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalPermisos ?? '—' }}</h4>
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
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden role-card {{ $esProtegido ? 'border-start border-4 border-warning' : 'border-start border-4 border-primary' }}">
                    <div class="card-body p-4">
                        <!-- Cabecera del Rol -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center mb-2 mb-md-0">
                                <div class="role-icon-box me-3 {{ $esProtegido ? 'role-icon-protected' : 'role-icon-custom' }}">
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
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <h5 class="mb-0 fw-bold text-dark">{{ $role->name }}</h5>
                                        @if($esProtegido)
                                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning" style="font-size: 11px;">
                                                <i class="fas fa-lock me-1 text-warning"></i>Rol del Sistema
                                            </span>
                                        @else
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary" style="font-size: 11px;">
                                                <i class="fas fa-user-tag me-1"></i>Personalizado
                                            </span>
                                        @endif
                                        @if($esAdmin)
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success" style="font-size: 11px;">
                                                <i class="fas fa-star me-1"></i>Acceso Total
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mt-1 text-muted" style="font-size: 13px;">
                                        <span><i class="fas fa-users text-primary me-1"></i><strong>{{ $role->users_count }}</strong> {{ $role->users_count === 1 ? 'usuario asignado' : 'usuarios asignados' }}</span>
                                        <span class="mx-2 text-muted">·</span>
                                        <span><i class="fas fa-shield-alt text-success me-1"></i><strong>{{ $role->permissions->count() }}</strong> permisos en <strong>{{ count($gruposValidos) }}</strong> módulos</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 align-items-center">
                                @can('editar rol')
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-medium">
                                    <i class="fas fa-edit me-1"></i> Editar Permisos
                                </a>
                                @endcan

                                @can('eliminar rol')
                                @if(!$esProtegido)
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 delete-role-btn" title="Eliminar este rol">
                                            <i class="fas fa-trash-alt me-1"></i> Eliminar
                                        </button>
                                    </form>
                                @endif
                                @endcan
                            </div>
                        </div>

                        <!-- Sección de Permisos Optimizada (Principios de Progresión y Jerarquía UX) -->
                        @if($esAdmin)
                            <!-- CASO ADMINISTRADOR: Banner elegante de Control Total sin saturar visualmente -->
                            <div class="p-3 rounded-4 border d-flex flex-wrap justify-content-between align-items-center mb-2" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.04) 0%, rgba(5, 150, 105, 0.08) 100%); border-color: rgba(16, 185, 129, 0.25) !important;">
                                <div class="d-flex align-items-center mb-2 mb-md-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px; background: rgba(16, 185, 129, 0.15); color: #059669; flex-shrink: 0;">
                                        <i class="fas fa-check-double fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-success" style="font-size: 14px;">
                                            Control Total Irrestricto
                                        </div>
                                        <div class="text-muted" style="font-size: 13px;">
                                            Este perfil cuenta con todos los privilegios administrativos, operativos, financieros y de configuración habilitados.
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePermisos-{{ $role->id }}" aria-expanded="false">
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
                                        <div class="module-group-card p-3 rounded-3 border">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas {{ $meta['icon'] }} me-2" style="color: {{ $meta['color'] }}; font-size: 14px;"></i>
                                                <span class="fw-bold text-dark" style="font-size: 13px;">{{ $nombreModulo }}</span>
                                                <span class="badge rounded-pill ms-auto" style="background: {{ $meta['bg'] }}; color: {{ $meta['color'] }}; font-size: 11px;">
                                                    {{ $permisosModulo->count() }}
                                                </span>
                                            </div>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($permisosModulo as $perm)
                                                    <span class="badge bg-light text-secondary border fw-normal py-1 px-2" style="font-size: 11px;">
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
                                        <div class="module-group-card p-3 rounded-3 border">
                                            <div class="d-flex align-items-center mb-2 pb-1 border-bottom">
                                                <i class="fas {{ $meta['icon'] }} me-2" style="color: {{ $meta['color'] }}; font-size: 13px;"></i>
                                                <span class="fw-bold text-dark" style="font-size: 13px;">{{ $nombreModulo }}</span>
                                                <span class="badge rounded-pill ms-auto" style="background: {{ $meta['bg'] }}; color: {{ $meta['color'] }}; font-size: 11px;">
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
                <div class="card border-0 shadow-sm rounded-4 text-center py-5">
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
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    background: #ffffff;
}
.role-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.07) !important;
}
.role-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.role-icon-protected {
    background: #fef3c7;
    color: #d97706;
}
.role-icon-custom {
    background: rgba(0, 151, 167, 0.12);
    color: var(--primary);
}
.modules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
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
