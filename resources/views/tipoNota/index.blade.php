@extends('layouts.app')

@php
    $cargo = auth()->user()->cargoNombre();
@endphp

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
                    Notas de Pedido y Movimiento
                </li>
            </ol>
        </nav>
    </div>

    <!-- Encabezado de la página -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 pb-3 mb-4 border-bottom" style="border-color: #e2e8f0 !important;">
        <div>
            <h2 class="fw-bold mb-1 text-dark tracking-tight" style="letter-spacing: -0.025em;">
                Notas de Pedido y Movimiento
            </h2>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Solicitudes formales de despacho, envíos a bodegas periféricas y retornos al almacén central.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                <i class="fas fa-arrow-left me-1"></i> Volver al Panel
            </a>
            @can('crear nota')
            <a href="{{ route('tipoNota.create') }}" class="btn btn-sm btn-primary rounded-3 px-3 py-2 fw-semibold shadow-sm">
                <i class="fas fa-plus me-1.5"></i> Crear Nueva Nota
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
        <!-- KPI 1: Total Notas -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Total de Solicitudes</span>
                        <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold" style="font-size: 11px;">
                            Histórico
                        </span>
                    </div>
                    <div class="metric-value">
                        {{ $totalNotas ?? $tipoNotas->total() }}
                        <span class="metric-unit">notas</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-clipboard-list text-primary me-1"></i> Trámites formalizados
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Notas de Envío -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Notas de Envío</span>
                        <span class="badge rounded-pill bg-info-subtle text-info fw-semibold" style="font-size: 11px;">
                            Despachos
                        </span>
                    </div>
                    <div class="metric-value font-mono">
                        {{ $totalEnvios ?? 0 }}
                        <span class="metric-unit">envíos</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-paper-plane text-info me-1"></i> Hacia bodegas de distribución
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Notas de Devolución -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Notas de Devolución</span>
                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis fw-semibold" style="font-size: 11px;">
                            Retornos
                        </span>
                    </div>
                    <div class="metric-value font-mono">
                        {{ $totalDevoluciones ?? 0 }}
                        <span class="metric-unit">devoluciones</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-undo-alt text-warning me-1"></i> Retornos por merma o ajuste
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="card border rounded-3 mb-4 bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-3 p-md-3.5">
            <form method="GET" action="{{ route('tipoNota.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-warehouse" style="font-size: 12px;"></i>
                        </span>
                        <select name="bodega" id="filtro_bodega" class="form-select border-start-0 ps-1">
                            <option value="">Todas las bodegas destino</option>
                            @foreach($bodegas as $bodega)
                                <option value="{{ $bodega->idbodega }}" {{ request('bodega') == $bodega->idbodega ? 'selected' : '' }}>
                                    {{ $bodega->nombrebodega }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-exchange-alt" style="font-size: 12px;"></i>
                        </span>
                        <select name="tipo" id="filtro_tipo" class="form-select border-start-0 ps-1">
                            <option value="">Todos los tipos de nota</option>
                            <option value="ENVIO" {{ request('tipo') == 'ENVIO' ? 'selected' : '' }}>Notas de Envío</option>
                            <option value="DEVOLUCION" {{ request('tipo') == 'DEVOLUCION' ? 'selected' : '' }}>Notas de Devolución</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-3 px-3 w-100 fw-semibold">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                    @if(request()->hasAny(['bodega', 'tipo']))
                    <a href="{{ route('tipoNota.index') }}" class="btn btn-outline-secondary rounded-3 px-3" title="Limpiar filtros">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Principal de Notas -->
    <div class="card border rounded-3 overflow-hidden shadow-xs bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 operational-table">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3 text-nowrap" style="width: 140px;">Nota / Fecha</th>
                            <th class="py-3 text-nowrap" style="width: 120px;">Tipo</th>
                            <th class="py-3 text-nowrap" style="width: 170px;">Bodega Destino</th>
                            <th class="py-3 text-nowrap" style="width: 170px;">Solicitante</th>
                            <th class="py-3" style="min-width: 250px;">Productos Solicitados</th>
                            <th class="py-3 text-nowrap" style="width: 140px;">Estado</th>
                            <th class="text-end pe-4 py-3 text-nowrap" style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tipoNotas as $nota)
                            @php
                                $esEnvio = ($nota->tiponota === 'ENVIO');
                                $tipoStyle = $esEnvio 
                                    ? ['bg' => '#dbeafe', 'color' => '#1e40af', 'border' => '#bfdbfe', 'icon' => 'fa-paper-plane', 'text' => 'ENVÍO']
                                    : ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'icon' => 'fa-undo', 'text' => 'DEVOLUCIÓN'];
                                
                                $fechaNota = $nota->fechanota ? \Carbon\Carbon::parse($nota->fechanota)->format('d/m/Y H:i') : 'N/A';
                                $estadoTransaccion = optional($nota->transaccion)->estado;
                            @endphp
                            <tr class="operational-row">
                                <td class="ps-4 py-3 text-nowrap">
                                    <div>
                                        <span class="font-mono text-dark fw-bold" style="font-size: 13px;">
                                             #{{ $nota->codigo }}
                                        </span>
                                        <div class="text-muted font-mono mt-0.5" style="font-size: 11.5px;">
                                            <i class="far fa-clock me-1 text-muted"></i>{{ $fechaNota }}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 text-nowrap">
                                    <span class="badge rounded-pill px-2.5 py-1 fw-semibold badge-tipo-pill" style="background: {{ $tipoStyle['bg'] }}; color: {{ $tipoStyle['color'] }}; border: 1px solid {{ $tipoStyle['border'] }}; font-size: 11.5px; white-space: nowrap !important; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas {{ $tipoStyle['icon'] }}" style="font-size: 10px;"></i>
                                        <span>{{ $tipoStyle['text'] }}</span>
                                    </span>
                                </td>
                                <td class="py-3 text-nowrap">
                                    <span class="badge rounded-pill px-2.5 py-1 text-nowrap" style="background: rgba(0, 151, 167, 0.08); color: #006064; border: 1px solid rgba(0, 151, 167, 0.2); font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-warehouse text-primary"></i>
                                        <span>{{ optional($nota->bodega)->nombrebodega ?? 'N/A' }}</span>
                                    </span>
                                </td>
                                <td class="py-3 text-nowrap">
                                    <div class="text-dark fw-medium" style="font-size: 13.5px;">
                                        {{ optional($nota->responsableEmpleado)->nombreemp ?? 'N/A' }} {{ optional($nota->responsableEmpleado)->apellidoemp ?? '' }}
                                    </div>
                                </td>
                                <td class="py-3" style="max-width: 320px;">
                                    @if($nota->detalles && $nota->detalles->count() > 0)
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($nota->detalles as $detalle)
                                                <span class="badge bg-white text-dark border fw-medium px-2 py-1 shadow-xs font-mono text-nowrap" style="font-size: 11.5px; border-color: #e2e8f0 !important;">
                                                    <span class="fw-bold text-primary me-1">{{ $detalle->cantidad ?? 0 }}x</span>
                                                    <span class="fw-normal">{{ $detalle->producto->nombre ?? $detalle->codigoproducto }}</span>
                                                    <small class="text-muted ms-1">({{ $detalle->producto->tipoempaque ?? 'Unidad' }})</small>
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic" style="font-size: 12px;">Sin productos asignados</span>
                                    @endif
                                </td>
                                <td class="py-3 text-nowrap">
                                    @if($estadoTransaccion)
                                        @php
                                            $estadoStyles = [
                                                'PENDIENTE' => ['class' => 'status-warning', 'label' => 'Pendiente'],
                                                'FINALIZADA' => ['class' => 'status-success', 'label' => 'Finalizada'],
                                                'FINALIZADA_PARCIAL' => ['class' => 'status-info', 'label' => 'Parcial'],
                                                'RECHAZADA' => ['class' => 'status-danger', 'label' => 'Rechazada'],
                                            ];
                                            $eStyle = $estadoStyles[$estadoTransaccion] ?? ['class' => 'status-info', 'label' => str_replace('_', ' ', $estadoTransaccion)];
                                        @endphp
                                        <span class="status-badge {{ $eStyle['class'] }} text-nowrap">
                                            <span class="status-indicator-dot"></span> {{ $eStyle['label'] }}
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-2.5 py-1 fw-semibold text-nowrap" style="background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; font-size: 11.5px;">
                                            <i class="fas fa-hourglass-start me-1"></i>Sin Confirmar
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4 py-3 text-nowrap">
                                    <div class="d-inline-flex gap-1">
                                        @if(!$nota->transaccion)
                                            @can('crear nota')
                                            <form action="{{ route('tipoNota.confirmar', $nota->codigo) }}" method="POST" class="d-inline form-confirm-nota">
                                                @csrf
                                                <button type="submit" class="btn-action-circle text-success" title="Confirmar y Generar Transacción">
                                                    <i class="fas fa-check" style="font-size: 12px;"></i>
                                                </button>
                                            </form>
                                            @endcan
                                            @can('editar nota')
                                            <a href="{{ route('tipoNota.edit', $nota->codigo) }}"
                                               class="btn-action-circle text-primary" title="Editar Nota">
                                                <i class="fas fa-edit" style="font-size: 12px;"></i>
                                            </a>
                                            @endcan
                                            @can('eliminar nota')
                                                <form action="{{ route('tipoNota.destroy', $nota->codigo) }}" method="POST" class="d-inline form-delete-nota">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="button" class="btn-action-circle danger btn-delete-nota" title="Eliminar Nota">
                                                        <i class="fas fa-trash" style="font-size: 12px;"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif
                                        <a href="{{ route('tipoNota.pdf', $nota->codigo) }}"
                                           class="btn-action-circle text-danger" title="Descargar Comprobante PDF" target="_blank">
                                            <i class="fas fa-file-pdf" style="font-size: 12px;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: #f1f5f9; color: #94a3b8;">
                                            <i class="fas fa-clipboard-list fs-4"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No se encontraron notas de pedido</h6>
                                        <p class="text-muted mb-3" style="font-size: 13.5px;">Intenta cambiar los filtros seleccionados o registra una nueva solicitud.</p>
                                        @if(request()->hasAny(['bodega', 'tipo']))
                                            <a href="{{ route('tipoNota.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3">
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

            @if(method_exists($tipoNotas, 'links') && $tipoNotas->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-center bg-white" style="border-color: #e2e8f0 !important;">
                    {{ $tipoNotas->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
.operational-table {
    min-width: 960px;
}
.operational-table th,
.operational-table td {
    vertical-align: middle;
}
.badge-tipo-pill {
    white-space: nowrap !important;
    display: inline-flex !important;
    align-items: center;
    gap: 4px;
    letter-spacing: 0.02em;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnsDelete = document.querySelectorAll('.btn-delete-nota');
    btnsDelete.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            let form = this.closest('form');
            Swal.fire({
                title: '¿Eliminar esta nota?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'rounded-pill px-3',
                    cancelButton: 'rounded-pill px-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size: 12px;"></i>';
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
