@extends('layouts.app')

@section('title', 'Auditorías del Sistema')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Auditorías del Sistema</h3>
            <p class="page-subtitle">Registro histórico de todas las acciones importantes realizadas por los usuarios</p>
        </div>
    </div>

    <!-- Tarjeta de Filtros -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0"><i class="fas fa-filter text-muted me-2"></i>Filtros de Búsqueda</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('auditorias.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label" style="font-size: 13px;">Usuario</label>
                    <select name="user_id" class="form-select form-select-sm">
                        <option value="">Todos los usuarios</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label" style="font-size: 13px;">Módulo</label>
                    <select name="modulo" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="Autenticación" {{ request('modulo') == 'Autenticación' ? 'selected' : '' }}>Autenticación</option>
                        <option value="Ventas" {{ request('modulo') == 'Ventas' ? 'selected' : '' }}>Ventas</option>
                        <option value="Notas" {{ request('modulo') == 'Notas' ? 'selected' : '' }}>Notas</option>
                        <option value="Productos" {{ request('modulo') == 'Productos' ? 'selected' : '' }}>Productos</option>
                        <option value="Usuarios" {{ request('modulo') == 'Usuarios' ? 'selected' : '' }}>Usuarios</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label" style="font-size: 13px;">Acción</label>
                    <select name="accion" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        <option value="Creación" {{ request('accion') == 'Creación' ? 'selected' : '' }}>Creación</option>
                        <option value="Edición" {{ request('accion') == 'Edición' ? 'selected' : '' }}>Edición</option>
                        <option value="Eliminación" {{ request('accion') == 'Eliminación' ? 'selected' : '' }}>Eliminación</option>
                        <option value="Aprobación" {{ request('accion') == 'Aprobación' ? 'selected' : '' }}>Aprobación</option>
                        <option value="Login" {{ request('accion') == 'Login' ? 'selected' : '' }}>Login / Acceso</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label" style="font-size: 13px;">Desde</label>
                    <input type="date" name="fecha_inicio" class="form-control form-control-sm" value="{{ request('fecha_inicio') }}">
                </div>
                
                <div class="col-md-2">
                    <label class="form-label" style="font-size: 13px;">Hasta</label>
                    <input type="date" name="fecha_fin" class="form-control form-control-sm" value="{{ request('fecha_fin') }}">
                </div>

                <div class="col-md-1 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100" title="Aplicar filtros">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('auditorias.index') }}" class="btn btn-secondary btn-sm w-100" title="Limpiar filtros">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Auditorías -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha y Hora</th>
                            <th>Usuario</th>
                            <th>Módulo</th>
                            <th>Acción</th>
                            <th>Detalle del Movimiento</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($auditorias as $audit)
                            <tr>
                                <td style="font-size: 12px; color: var(--muted);">
                                    {{ $audit->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 10px; font-weight: bold;">
                                            {{ strtoupper(substr($audit->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="fw-medium">{{ $audit->user->name ?? 'Usuario Eliminado' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $audit->modulo }}</span>
                                </td>
                                <td>
                                    @php
                                        $color = 'secondary';
                                        switch($audit->accion) {
                                            case 'Creación': $color = 'success'; break;
                                            case 'Edición': $color = 'warning'; break;
                                            case 'Eliminación': $color = 'danger'; break;
                                            case 'Aprobación': $color = 'info'; break;
                                            case 'Login': $color = 'primary'; break;
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ $audit->accion }}</span>
                                </td>
                                <td style="color: var(--secondary); max-width: 400px; white-space: normal;">
                                    {{ $audit->detalles }}
                                </td>
                                <td style="font-size: 11px; color: var(--muted);">
                                    {{ $audit->ip_address ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-clipboard-list text-muted mb-3" style="font-size: 40px; opacity: 0.5;"></i>
                                        <h5 class="text-secondary">No hay registros de auditoría</h5>
                                        <p class="text-muted">Aún no se han registrado movimientos o no hay resultados para estos filtros.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($auditorias->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    {{ $auditorias->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
