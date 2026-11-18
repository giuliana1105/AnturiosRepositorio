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
                    Usuarios del Sistema
                </li>
            </ol>
        </nav>
    </div>

    <!-- Encabezado de Página -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 pb-3 mb-4 border-bottom" style="border-color: #e2e8f0 !important;">
        <div>
            <h2 class="fw-bold mb-1 text-dark tracking-tight" style="letter-spacing: -0.025em;">
                Gestión de Usuarios
            </h2>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Administración de credenciales de acceso, perfiles de seguridad y asignación de roles.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                <i class="fas fa-shield-alt me-1.5 text-primary"></i> Ver Roles y Permisos
            </a>
            @can('crear empleado')
            <a href="{{ route('empleados.create') }}" class="btn btn-sm btn-primary rounded-3 px-3 py-2 fw-semibold shadow-sm">
                <i class="fas fa-user-plus me-1.5"></i> Nuevo Usuario (vía Empleado)
            </a>
            @endcan
        </div>
    </div>

    <!-- Alertas de Sesión -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tarjetas de Resumen Ejecutivo (Estilo Impeccable) -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Total Usuarios -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Total Usuarios</span>
                        <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold" style="font-size: 11px;">
                            Cuentas
                        </span>
                    </div>
                    <div class="metric-value">
                        {{ $totalUsuarios ?? $users->total() }}
                        <span class="metric-unit">usuarios</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-users text-primary me-1"></i> Accesos registrados
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Usuarios Activos -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Usuarios Activos</span>
                        <span class="badge rounded-pill bg-success-subtle text-success fw-semibold" style="font-size: 11px;">
                            Habilitados
                        </span>
                    </div>
                    <div class="metric-value text-success font-mono">
                        {{ $usuariosActivos ?? '—' }}
                        <span class="metric-unit">activos</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-check-circle text-success me-1"></i> Clave regular configurada
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Clave Pendiente -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Clave Inicial / Pendiente</span>
                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis fw-semibold" style="font-size: 11px;">
                            Por Cambiar
                        </span>
                    </div>
                    <div class="metric-value font-mono {{ ($usuariosPendientesClave ?? 0) > 0 ? 'text-warning-emphasis' : 'text-dark' }}">
                        {{ $usuariosPendientesClave ?? 0 }}
                        <span class="metric-unit">pendientes</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        @if(($usuariosPendientesClave ?? 0) > 0)
                            <span class="text-warning-emphasis fw-semibold">
                                <i class="fas fa-key me-1"></i> Deben renovar clave al entrar
                            </span>
                        @else
                            <i class="fas fa-check-circle text-success me-1"></i> Todas las claves actualizadas
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 4: Roles Disponibles -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Roles Disponibles</span>
                        <span class="badge rounded-pill bg-info-subtle text-info fw-semibold" style="font-size: 11px;">
                            Perfiles
                        </span>
                    </div>
                    <div class="metric-value font-mono">
                        {{ $totalRoles ?? count($roles ?? []) }}
                        <span class="metric-unit">roles</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-shield-alt text-info me-1"></i> Perfiles de autorización
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="card border rounded-3 mb-4 bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-3 p-md-3.5">
            <form action="{{ route('users.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-search" style="font-size: 12px;"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-1" 
                               placeholder="Buscar por nombre o correo electrónico..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-filter" style="font-size: 12px;"></i>
                        </span>
                        <select name="role" class="form-select border-start-0 ps-1">
                            <option value="">Todos los roles</option>
                            @if(isset($roles))
                                @foreach($roles as $r)
                                    <option value="{{ $r->id }}" {{ request('role') == $r->id ? 'selected' : '' }}>
                                        {{ $r->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-3 px-3 w-100 fw-semibold">
                        <i class="fas fa-search me-1"></i> Filtrar
                    </button>
                    @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary rounded-3 px-3" title="Limpiar filtros">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Nota Informativa de Flujo Operativo -->
    <div class="card border rounded-3 mb-4 bg-light" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background: rgba(0, 151, 167, 0.1); color: var(--primary);">
                <i class="fas fa-info" style="font-size: 13px;"></i>
            </div>
            <div style="font-size: 13px;" class="text-secondary">
                <strong>¿Cómo funciona el ciclo de usuarios?</strong> Las cuentas se crean automáticamente al dar de alta un empleado (la contraseña inicial es su cédula). Desde aquí puedes asignar o cambiar su <strong>rol y permisos</strong>. Para restablecer contraseñas o modificar datos personales, accede al módulo de <a href="{{ route('empleados.index') }}" class="fw-semibold text-primary text-decoration-none">Empleados</a>.
            </div>
        </div>
    </div>

    <!-- Tabla Principal de Usuarios -->
    <div class="card border rounded-3 overflow-hidden shadow-xs bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 operational-table">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3" style="width: 280px;">Usuario</th>
                            <th class="py-3" style="width: 170px;">Rol Principal</th>
                            <th class="py-3">Permisos Directos</th>
                            <th class="py-3" style="width: 170px;">Estado de Seguridad</th>
                            <th class="text-end pe-4 py-3" style="width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            @php
                                $initial = strtoupper(substr($user->name, 0, 1));
                                $firstRole = $user->roles->first();
                                $roleName = $firstRole ? $firstRole->name : 'Sin rol';
                                
                                $badgeStyles = [
                                    'Administrador'   => ['bg' => '#fee2e2', 'color' => '#991b1b', 'border' => '#fecaca', 'icon' => 'fa-user-shield'],
                                    'Jefe de Bodega'  => ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'icon' => 'fa-warehouse'],
                                    'Vendedor'        => ['bg' => '#dbeafe', 'color' => '#1e40af', 'border' => '#bfdbfe', 'icon' => 'fa-shopping-bag'],
                                ];
                                $currentStyle = $badgeStyles[$roleName] ?? ['bg' => '#f1f5f9', 'color' => '#334155', 'border' => '#e2e8f0', 'icon' => 'fa-user-tag'];
                            @endphp
                            <tr class="operational-row">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                                             style="width: 38px; height: 38px; background: {{ $currentStyle['bg'] }}; color: {{ $currentStyle['color'] }}; font-weight: 700; font-size: 14px;">
                                            {{ $initial }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 13.5px;">{{ $user->name }}</div>
                                            <div class="text-muted d-flex align-items-center gap-1" style="font-size: 12px;">
                                                <i class="far fa-envelope text-muted" style="font-size: 10px;"></i>
                                                <span>{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    @if($user->roles->isNotEmpty())
                                        @foreach($user->roles as $role)
                                            @php
                                                $style = $badgeStyles[$role->name] ?? ['bg' => '#f1f5f9', 'color' => '#334155', 'border' => '#e2e8f0', 'icon' => 'fa-user-tag'];
                                            @endphp
                                            <span class="badge rounded-pill px-2.5 py-1 fw-semibold me-1" style="background: {{ $style['bg'] }}; color: {{ $style['color'] }}; border: 1px solid {{ $style['border'] }}; font-size: 11.5px;">
                                                <i class="fas {{ $style['icon'] }} me-1" style="font-size: 9px;"></i>{{ $role->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 11.5px;">
                                            <i class="fas fa-user-slash me-1"></i>Sin rol
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @php $directPerms = $user->getDirectPermissions(); @endphp
                                    @if($directPerms->isNotEmpty())
                                        <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(0, 151, 167, 0.1); color: var(--primary); font-size: 11.5px; border: 1px solid rgba(0, 151, 167, 0.2);">
                                            <i class="fas fa-plus-circle me-1"></i>{{ $directPerms->count() }} personalizado{{ $directPerms->count() > 1 ? 's' : '' }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size: 12px;">
                                            <i class="fas fa-minus text-muted opacity-50 me-1"></i>Hereda solo de su rol
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if($user->must_change_password ?? false)
                                        <span class="status-badge status-warning" title="El usuario debe cambiar su clave inicial al ingresar">
                                            <span class="status-indicator-dot"></span> Clave Inicial
                                        </span>
                                    @else
                                        <span class="status-badge status-success">
                                            <span class="status-indicator-dot"></span> Activo
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4 py-3">
                                    @can('editar usuario')
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary rounded-3 px-3 py-1.5 fw-medium" title="Administrar rol y permisos">
                                        <i class="fas fa-user-shield me-1"></i> Configurar
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: #f1f5f9; color: #94a3b8;">
                                            <i class="fas fa-users-slash fs-4"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No se encontraron usuarios</h6>
                                        <p class="text-muted mb-3" style="font-size: 13.5px;">Intenta cambiar los términos de búsqueda o registra un nuevo empleado.</p>
                                        @if(request()->hasAny(['search', 'role']))
                                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3">
                                                <i class="fas fa-undo me-1"></i> Restablecer filtros
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($users, 'links') && $users->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-center bg-white" style="border-color: #e2e8f0 !important;">
                    {{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
</style>
@endsection
