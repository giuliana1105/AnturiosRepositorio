@extends('layouts.app')

@section('title', 'Auditorías del Sistema')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de la página -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Pista de Auditorías del Sistema</h3>
            <p class="page-subtitle mb-0 text-muted">Registro histórico inmutable de trazabilidad, eventos de seguridad y operaciones</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge rounded-pill bg-light text-muted border px-3 py-2" style="font-size: 12px;">
                <i class="fas fa-shield-alt text-primary me-1"></i> Modo Solo Lectura
            </span>
        </div>
    </div>

    <!-- Tarjetas de Resumen KPI -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                        <i class="fas fa-history fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total de Eventos</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalAuditorias ?? $auditorias->total()) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                        <i class="fas fa-calendar-day fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Acciones Hoy</small>
                        <h4 class="fw-bold mb-0 text-success">{{ number_format($auditoriasHoy ?? 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                        <i class="fas fa-cubes fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Módulos Monitorizados</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $modulosActivos ?? 5 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta de Filtros -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('auditorias.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold text-dark" style="font-size: 13px;">Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-user"></i>
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
                
                <div class="col-md-2">
                    <label class="form-label fw-bold text-dark" style="font-size: 13px;">Módulo</label>
                    <select name="modulo" class="form-select">
                        <option value="">Todos</option>
                        <option value="Autenticación" {{ request('modulo') == 'Autenticación' ? 'selected' : '' }}>Autenticación</option>
                        <option value="Ventas" {{ request('modulo') == 'Ventas' ? 'selected' : '' }}>Ventas</option>
                        <option value="Notas" {{ request('modulo') == 'Notas' ? 'selected' : '' }}>Notas</option>
                        <option value="Productos" {{ request('modulo') == 'Productos' ? 'selected' : '' }}>Productos</option>
                        <option value="Usuarios" {{ request('modulo') == 'Usuarios' ? 'selected' : '' }}>Usuarios</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold text-dark" style="font-size: 13px;">Acción Realizada</label>
                    <select name="accion" class="form-select">
                        <option value="">Todas</option>
                        <option value="Creación" {{ request('accion') == 'Creación' ? 'selected' : '' }}>Creación</option>
                        <option value="Edición" {{ request('accion') == 'Edición' ? 'selected' : '' }}>Edición</option>
                        <option value="Eliminación" {{ request('accion') == 'Eliminación' ? 'selected' : '' }}>Eliminación</option>
                        <option value="Aprobación" {{ request('accion') == 'Aprobación' ? 'selected' : '' }}>Aprobación</option>
                        <option value="Login" {{ request('accion') == 'Login' ? 'selected' : '' }}>Login / Acceso</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold text-dark" style="font-size: 13px;">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                </div>
                
                <div class="col-md-2">
                    <label class="form-label fw-bold text-dark" style="font-size: 13px;">Fecha Fin</label>
                    <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                </div>

                <div class="col-md-1 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill w-100 shadow-sm" title="Aplicar filtros">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request()->hasAny(['user_id', 'modulo', 'accion', 'fecha_inicio', 'fecha_fin']))
                    <a href="{{ route('auditorias.index') }}" class="btn btn-outline-secondary rounded-pill w-100" title="Limpiar filtros">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Auditorías -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Fecha y Hora</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Usuario</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Módulo</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Acción</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Detalle del Movimiento</th>
                            <th class="text-end pe-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Dirección IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($auditorias as $audit)
                            @php
                                $accionStyles = [
                                    'Creación'    => ['bg' => '#dcfce7', 'color' => '#166534', 'border' => '#bbf7d0', 'icon' => 'fa-plus'],
                                    'Edición'     => ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'icon' => 'fa-edit'],
                                    'Eliminación' => ['bg' => '#fee2e2', 'color' => '#991b1b', 'border' => '#fecaca', 'icon' => 'fa-trash'],
                                    'Aprobación'  => ['bg' => '#dbeafe', 'color' => '#1e40af', 'border' => '#bfdbfe', 'icon' => 'fa-check'],
                                    'Login'       => ['bg' => '#ede9fe', 'color' => '#6d28d9', 'border' => '#ddd6fe', 'icon' => 'fa-sign-in-alt'],
                                ];
                                $actStyle = $accionStyles[$audit->accion] ?? ['bg' => '#f1f5f9', 'color' => '#475569', 'border' => '#e2e8f0', 'icon' => 'fa-circle'];
                            @endphp
                            <tr style="transition: background-color 0.15s ease;">
                                <td class="ps-4 py-3 font-mono text-muted" style="font-size: 12px;">
                                    <i class="far fa-clock me-1 text-muted"></i>{{ $audit->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width: 28px; height: 28px; font-size: 11px; font-weight: bold; background: rgba(0, 151, 167, 0.15); color: var(--primary);">
                                            {{ strtoupper(substr($audit->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="fw-semibold text-dark" style="font-size: 13.5px;">{{ $audit->user->name ?? 'Usuario Eliminado' }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill bg-light text-secondary border px-2.5 py-1" style="font-size: 11.5px;">
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
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: #f1f5f9; color: #94a3b8;">
                                            <i class="fas fa-clipboard-list fs-3"></i>
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
                <div class="px-4 py-3 border-top d-flex justify-content-center bg-white">
                    {{ $auditorias->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
