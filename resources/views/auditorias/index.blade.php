@extends('layouts.app')

@section('title', 'Auditorías del Sistema')

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
                    Pista de Auditorías
                </li>
            </ol>
        </nav>
    </div>

    <!-- Encabezado de la página -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 pb-3 mb-4 border-bottom" style="border-color: #e2e8f0 !important;">
        <div>
            <h2 class="fw-bold mb-1 text-dark tracking-tight" style="letter-spacing: -0.025em;">
                Pista de Auditorías del Sistema
            </h2>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Bitácora de seguridad y trazabilidad operacional: registro detallado de logins, creaciones, ediciones y aprobaciones.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                <i class="fas fa-arrow-left me-1"></i> Volver al Panel
            </a>
        </div>
    </div>

    <!-- Tarjetas de Resumen Ejecutivo (Estilo Impeccable) -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Total Eventos -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Total de Eventos</span>
                        <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold" style="font-size: 11px;">
                            Bitácora
                        </span>
                    </div>
                    <div class="metric-value">
                        {{ number_format($totalAuditorias ?? $auditorias->total()) }}
                        <span class="metric-unit">registros</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-history text-primary me-1"></i> Trazabilidad histórica completa
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Acciones Hoy -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Acciones Hoy</span>
                        <span class="badge rounded-pill bg-success-subtle text-success fw-semibold" style="font-size: 11px;">
                            Jornada Actual
                        </span>
                    </div>
                    <div class="metric-value text-success font-mono">
                        {{ number_format($auditoriasHoy ?? 0) }}
                        <span class="metric-unit">eventos</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-calendar-day text-success me-1"></i> Movimientos en las últimas 24 horas
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Módulos Monitorizados -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Módulos Auditados</span>
                        <span class="badge rounded-pill bg-info-subtle text-info fw-semibold" style="font-size: 11px;">
                            Cobertura
                        </span>
                    </div>
                    <div class="metric-value font-mono">
                        {{ $modulosActivos ?? 5 }}
                        <span class="metric-unit">módulos</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-cubes text-info me-1"></i> Ventas, Almacén, Usuarios y Roles
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="card border rounded-3 mb-4 bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-3 p-md-3.5">
            <form method="GET" action="{{ route('auditorias.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-user" style="font-size: 12px;"></i>
                        </span>
                        <select name="user_id" class="form-select border-start-0 ps-1">
                            <option value="">Todos los usuarios</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-12 col-md-2">
                    <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Módulo</label>
                    <select name="modulo" class="form-select">
                        <option value="">Todos</option>
                        <option value="Autenticación" {{ request('modulo') == 'Autenticación' ? 'selected' : '' }}>Autenticación</option>
                        <option value="Ventas" {{ request('modulo') == 'Ventas' ? 'selected' : '' }}>Ventas</option>
                        <option value="Inventario" {{ request('modulo') == 'Inventario' ? 'selected' : '' }}>Inventario</option>
                        <option value="Notas" {{ request('modulo') == 'Notas' ? 'selected' : '' }}>Notas de Pedido</option>
                        <option value="Productos" {{ request('modulo') == 'Productos' ? 'selected' : '' }}>Productos</option>
                        <option value="Clientes" {{ request('modulo') == 'Clientes' ? 'selected' : '' }}>Clientes</option>
                        <option value="Bodegas" {{ request('modulo') == 'Bodegas' ? 'selected' : '' }}>Bodegas</option>
                        <option value="Empleados" {{ request('modulo') == 'Empleados' ? 'selected' : '' }}>Empleados</option>
                        <option value="Usuarios" {{ request('modulo') == 'Usuarios' ? 'selected' : '' }}>Usuarios</option>
                        <option value="Roles" {{ request('modulo') == 'Roles' ? 'selected' : '' }}>Roles</option>
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Acción Realizada</label>
                    <select name="accion" class="form-select">
                        <option value="">Todas</option>
                        <option value="Creación" {{ request('accion') == 'Creación' ? 'selected' : '' }}>Creación</option>
                        <option value="Edición" {{ request('accion') == 'Edición' ? 'selected' : '' }}>Edición</option>
                        <option value="Eliminación" {{ request('accion') == 'Eliminación' ? 'selected' : '' }}>Eliminación</option>
                        <option value="Aprobación" {{ request('accion') == 'Aprobación' ? 'selected' : '' }}>Aprobación</option>
                        <option value="Confirmación" {{ request('accion') == 'Confirmación' ? 'selected' : '' }}>Confirmación</option>
                        <option value="Rechazo" {{ request('accion') == 'Rechazo' ? 'selected' : '' }}>Rechazo</option>
                        <option value="Abono" {{ request('accion') == 'Abono' ? 'selected' : '' }}>Abono / Pago</option>
                        <option value="Login" {{ request('accion') == 'Login' ? 'selected' : '' }}>Login / Acceso</option>
                        <option value="Logout" {{ request('accion') == 'Logout' ? 'selected' : '' }}>Logout / Salida</option>
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Desde</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                </div>
                
                <div class="col-12 col-md-2">
                    <label class="form-label fw-bold text-dark" style="font-size: 12.5px;">Hasta</label>
                    <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                </div>

                <div class="col-12 col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-primary rounded-3 w-100 fw-semibold" title="Aplicar filtros">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request()->hasAny(['user_id', 'modulo', 'accion', 'fecha_inicio', 'fecha_fin']))
                    <a href="{{ route('auditorias.index') }}" class="btn btn-outline-secondary rounded-3" title="Limpiar filtros">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Auditorías -->
    <div class="card border rounded-3 overflow-hidden shadow-xs bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle operational-table">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3" style="width: 170px;">Fecha y Hora</th>
                            <th class="py-3" style="width: 200px;">Usuario</th>
                            <th class="py-3" style="width: 130px;">Módulo</th>
                            <th class="py-3" style="width: 130px;">Acción</th>
                            <th class="py-3">Detalle del Movimiento</th>
                            <th class="text-end pe-4 py-3" style="width: 140px;">Dirección IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auditorias as $audit)
                            @php
                                $accionStyles = [
                                    'Creación'     => ['bg' => '#dcfce7', 'color' => '#166534', 'border' => '#bbf7d0', 'icon' => 'fa-plus'],
                                    'Edición'      => ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'icon' => 'fa-edit'],
                                    'Eliminación'  => ['bg' => '#fee2e2', 'color' => '#991b1b', 'border' => '#fecaca', 'icon' => 'fa-trash'],
                                    'Aprobación'   => ['bg' => '#dbeafe', 'color' => '#1e40af', 'border' => '#bfdbfe', 'icon' => 'fa-check'],
                                    'Confirmación' => ['bg' => '#e0f2fe', 'color' => '#0369a1', 'border' => '#bae6fd', 'icon' => 'fa-check-double'],
                                    'Rechazo'      => ['bg' => '#ffe4e6', 'color' => '#be123c', 'border' => '#fecdd3', 'icon' => 'fa-ban'],
                                    'Abono'        => ['bg' => '#ccfbf1', 'color' => '#0f766e', 'border' => '#99f6e4', 'icon' => 'fa-money-bill-wave'],
                                    'Login'        => ['bg' => '#ede9fe', 'color' => '#6d28d9', 'border' => '#ddd6fe', 'icon' => 'fa-sign-in-alt'],
                                    'Logout'       => ['bg' => '#f1f5f9', 'color' => '#475569', 'border' => '#cbd5e1', 'icon' => 'fa-sign-out-alt'],
                                ];
                                $actStyle = $accionStyles[$audit->accion] ?? ['bg' => '#f1f5f9', 'color' => '#475569', 'border' => '#e2e8f0', 'icon' => 'fa-circle'];
                            @endphp
                            <tr class="operational-row">
                                <td class="ps-4 py-3 font-mono text-muted" style="font-size: 12px;">
                                    <i class="far fa-clock me-1 text-muted"></i>{{ $audit->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-xs flex-shrink-0" style="width: 28px; height: 28px; font-size: 11px; font-weight: bold; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                                            {{ strtoupper(substr($audit->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="fw-semibold text-dark" style="font-size: 13.5px;">{{ $audit->user->name ?? 'Usuario Eliminado' }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill bg-light text-secondary border px-2.5 py-1" style="font-size: 11.5px; border-color: #e2e8f0 !important;">
                                        {{ $audit->modulo }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-2.5 py-1 fw-semibold" style="background: {{ $actStyle['bg'] }}; color: {{ $actStyle['color'] }}; border: 1px solid {{ $actStyle['border'] }}; font-size: 11px;">
                                        <i class="fas {{ $actStyle['icon'] }} me-1" style="font-size: 9px;"></i>{{ $audit->accion }}
                                    </span>
                                </td>
                                <td class="py-3" style="max-width: 420px; font-size: 13px; color: #334155;">
                                    {{ $audit->detalles }}
                                </td>
                                <td class="text-end pe-4 py-3 font-mono text-muted" style="font-size: 11.5px;">
                                    {{ $audit->ip_address ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: #f1f5f9; color: #94a3b8;">
                                            <i class="fas fa-clipboard-list fs-4"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No hay registros de auditoría</h6>
                                        <p class="text-muted mb-0" style="font-size: 13.5px;">No se encontraron movimientos registrados para los filtros seleccionados.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($auditorias->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-center bg-white" style="border-color: #e2e8f0 !important;">
                    {{ $auditorias->links('pagination::bootstrap-4') }}
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
