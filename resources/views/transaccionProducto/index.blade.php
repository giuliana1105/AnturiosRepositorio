@extends('layouts.app')

@section('content')
<div class="container-fluid py-3 px-3 px-md-4">
    <!-- Breadcrumb & Wayfinding -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                        <i class="fas fa-home me-1 text-primary"></i> Inicio
                    </a>
                </li>
                <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                    Aprobación de Transacciones
                </li>
            </ol>
        </nav>
    </div>

    <!-- Encabezado de la página -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 pb-3 mb-4 border-bottom" style="border-color: #e2e8f0 !important;">
        <div>
            <h2 class="fw-bold mb-1 text-dark tracking-tight" style="letter-spacing: -0.025em;">
                Aprobación de Transacciones
            </h2>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Auditoría física de pedidos, liquidación de notas de despacho y verificación de transferencias entre bodegas.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                <i class="fas fa-arrow-left me-1"></i> Volver al Panel
            </a>
            <a href="{{ route('tipoNota.index') }}" class="btn btn-sm btn-outline-primary rounded-3 px-3 py-2 fw-medium">
                <i class="fas fa-clipboard-list me-1"></i> Notas de Pedido
            </a>
        </div>
    </div>

    <!-- Alertas de Sesión -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tarjetas de Resumen Ejecutivo (Estilo Impeccable) -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Total Movimientos -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Total Transacciones</span>
                        <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold" style="font-size: 11px;">
                            Historial
                        </span>
                    </div>
                    <div class="metric-value">
                        {{ ($pendientes ?? 0) + ($finalizadas ?? 0) }}
                        <span class="metric-unit">movimientos</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-exchange-alt text-primary me-1"></i> Despachos y retornos
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Pendientes -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Pendientes de Aprobación</span>
                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis fw-semibold" style="font-size: 11px;">
                            Por Liquidar
                        </span>
                    </div>
                    <div class="metric-value font-mono {{ ($pendientes ?? 0) > 0 ? 'text-warning-emphasis' : 'text-dark' }}">
                        {{ $pendientes ?? 0 }}
                        <span class="metric-unit">pendientes</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        @if(($pendientes ?? 0) > 0)
                            <span class="text-warning-emphasis fw-semibold">
                                <i class="fas fa-clock me-1"></i> Requieren verificación física
                            </span>
                        @else
                            <i class="fas fa-check-circle text-success me-1"></i> Sin transacciones por procesar
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Finalizadas -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Finalizadas con Éxito</span>
                        <span class="badge rounded-pill bg-success-subtle text-success fw-semibold" style="font-size: 11px;">
                            Liquidadas
                        </span>
                    </div>
                    <div class="metric-value text-success font-mono">
                        {{ $finalizadas ?? 0 }}
                        <span class="metric-unit">completadas</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-check-double text-success me-1"></i> Con stock debitado correctamente
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Pestañas Segmentada (Filtro por Estado) -->
    <div class="card border rounded-3 mb-4 bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="segmented-rail">
                <a href="{{ route('transaccionProducto.index', ['estado' => '']) }}" 
                   class="segmented-tab {{ request('estado') == '' ? 'active' : '' }}">
                    <i class="fas fa-list me-1"></i> Todas
                    <span class="counter-badge">{{ ($pendientes ?? 0) + ($finalizadas ?? 0) }}</span>
                </a>
                <a href="{{ route('transaccionProducto.index', ['estado' => 'PENDIENTE']) }}" 
                   class="segmented-tab {{ request('estado') == 'PENDIENTE' ? 'active' : '' }}">
                    <i class="fas fa-clock me-1 text-warning"></i> Pendientes
                    <span class="counter-badge" style="{{ ($pendientes ?? 0) > 0 ? 'background: #fef3c7; color: #b45309;' : '' }}">{{ $pendientes ?? 0 }}</span>
                </a>
                <a href="{{ route('transaccionProducto.index', ['estado' => 'FINALIZADA']) }}" 
                   class="segmented-tab {{ request('estado') == 'FINALIZADA' ? 'active' : '' }}">
                    <i class="fas fa-check-circle me-1 text-success"></i> Finalizadas
                    <span class="counter-badge">{{ $finalizadas ?? 0 }}</span>
                </a>
            </div>
            <div class="text-muted d-none d-sm-block" style="font-size: 12.5px;">
                <i class="fas fa-filter text-muted me-1"></i> Filtro de estado operativo
            </div>
        </div>
    </div>

    <!-- Tabla Principal de Transacciones -->
    <div class="card border rounded-3 overflow-hidden shadow-xs bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 operational-table">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3" style="width: 130px;">Código Nota</th>
                            <th class="py-3" style="width: 140px;">Tipo de Nota</th>
                            <th class="py-3" style="width: 190px;">Bodega Solicitante</th>
                            <th class="py-3" style="width: 150px;">Estado</th>
                            <th class="py-3">Productos Involucrados</th>
                            <th class="text-end pe-4 py-3" style="width: 180px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transacciones as $transaccion)
                            @php
                                $esEnvio = ($transaccion->tipoNota->tiponota === 'ENVIO');
                                $tipoBadge = $esEnvio 
                                    ? ['bg' => '#dbeafe', 'color' => '#1e40af', 'border' => '#bfdbfe', 'icon' => 'fa-paper-plane']
                                    : ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'icon' => 'fa-undo'];
                                
                                $estadoStyles = [
                                    'PENDIENTE' => ['class' => 'status-warning', 'label' => 'Pendiente'],
                                    'FINALIZADA' => ['class' => 'status-success', 'label' => 'Finalizada'],
                                    'FINALIZADA_PARCIAL' => ['class' => 'status-info', 'label' => 'Parcial'],
                                    'RECHAZADA' => ['class' => 'status-danger', 'label' => 'Rechazada'],
                                ];
                                $eStyle = $estadoStyles[$transaccion->estado] ?? ['class' => 'status-info', 'label' => str_replace('_', ' ', $transaccion->estado)];
                            @endphp
                            <tr class="operational-row">
                                <td class="ps-4 py-3">
                                    <span class="font-mono text-dark fw-bold" style="font-size: 13px;">
                                        #{{ $transaccion->tipoNota->codigo }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-2.5 py-1 fw-semibold" style="background: {{ $tipoBadge['bg'] }}; color: {{ $tipoBadge['color'] }}; border: 1px solid {{ $tipoBadge['border'] }}; font-size: 11.5px;">
                                        <i class="fas {{ $tipoBadge['icon'] }} me-1" style="font-size: 10px;"></i>{{ $transaccion->tipoNota->tiponota }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(0, 151, 167, 0.08); color: #006064; border: 1px solid rgba(0, 151, 167, 0.2); font-size: 12px;">
                                        <i class="fas fa-warehouse me-1 text-primary"></i>{{ $transaccion->tipoNota->bodega->nombrebodega ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="status-badge {{ $eStyle['class'] }}">
                                        <span class="status-indicator-dot"></span> {{ $eStyle['label'] }}
                                    </span>
                                </td>
                                <td class="py-3" style="max-width: 340px;">
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($transaccion->tipoNota->detalles as $detalle)
                                            <span class="badge bg-white text-dark border fw-medium px-2 py-1 shadow-xs font-mono" style="font-size: 11.5px; border-color: #e2e8f0 !important;">
                                                <strong class="text-primary me-1">{{ $detalle->cantidad }}x</strong>
                                                <span class="fw-normal">{{ $detalle->producto->nombre ?? 'N/A' }}</span>
                                                <small class="text-muted ms-1">({{ $detalle->producto->tipoempaque ?? 'Unidad' }})</small>
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="text-end pe-4 py-3">
                                    @if($transaccion->estado == 'PENDIENTE')
                                        <form id="form-finalizar-{{ $transaccion->id }}" action="{{ route('transaccionProducto.finalizar', $transaccion->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="force_reject" id="force-reject-{{ $transaccion->id }}" value="0">
                                            <button type="button" class="btn btn-sm btn-primary rounded-3 px-3 py-1.5 fw-semibold shadow-xs" onclick="verificarYFinalizar({{ $transaccion->id }})">
                                                <i class="fas fa-check me-1"></i> Verificar y Finalizar
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge rounded-pill bg-light text-muted border px-2.5 py-1" style="font-size: 11px;">
                                            <i class="fas fa-check-double text-success me-1"></i>Completada
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: #f1f5f9; color: #94a3b8;">
                                            <i class="fas fa-clipboard-check fs-4"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No hay transacciones registradas</h6>
                                        <p class="text-muted mb-0" style="font-size: 13.5px;">Las transacciones generadas al confirmar notas de pedido se listarán aquí.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($transacciones, 'links') && $transacciones->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-center bg-white" style="border-color: #e2e8f0 !important;">
                    {{ $transacciones->appends(['estado' => request('estado'), 'search' => request('search')])->onEachSide(1)->links('pagination::bootstrap-4') }}
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

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function verificarYFinalizar(id) {
    Swal.fire({
        title: 'Verificando stock...',
        text: 'Consultando disponibilidad en almacén central',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/transaccionProducto/verificarStock/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'ok') {
                Swal.fire({
                    title: '¿Confirmar transacción?',
                    text: 'Todos los productos cuentan con stock suficiente en bodega. Se procederá a descontar y registrar la salida.',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-check me-1"></i> Sí, Finalizar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'rounded-pill px-3',
                        cancelButton: 'rounded-pill px-3'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-finalizar-' + id).submit();
                    }
                });
            } else if (data.status === 'insufficient') {
                let htmlMsg = '<div style="text-align: left; font-size: 13.5px;">' +
                              '<p class="text-danger fw-bold mb-2"><i class="fas fa-exclamation-triangle me-1"></i> Faltan los siguientes productos:</p>' +
                              '<ul style="padding-left: 20px;" class="mb-3">';
                
                data.faltantes.forEach(item => {
                    htmlMsg += `<li><b>${item.nombre}</b><br><span class="text-muted">Solicitado: ${item.solicitado} | Disponible: <strong class="text-danger">${item.disponible}</strong></span></li>`;
                });
                htmlMsg += '</ul><hr>';
                
                if (data.hay_stock_parcial) {
                    htmlMsg += '<p class="mb-0 fw-bold text-dark">¿Deseas realizar un DESPACHO PARCIAL enviando únicamente las unidades disponibles?</p></div>';
                    
                    Swal.fire({
                        title: 'Stock Parcial Insuficiente',
                        html: htmlMsg,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#0097a7',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, Despachar Parcial',
                        cancelButtonText: 'Cancelar',
                        customClass: {
                            confirmButton: 'rounded-pill px-3',
                            cancelButton: 'rounded-pill px-3'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('form-finalizar-' + id).submit();
                        }
                    });
                } else {
                    htmlMsg += '<p class="mb-0 fw-bold text-danger">No hay existencias disponibles para NINGUNO de los productos solicitados.</p></div>';
                    
                    Swal.fire({
                        title: 'Sin Stock en Bodega',
                        html: htmlMsg,
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Rechazar Solicitud',
                        cancelButtonText: 'Cancelar',
                        customClass: {
                            confirmButton: 'rounded-pill px-3',
                            cancelButton: 'rounded-pill px-3'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('force-reject-' + id).value = '1';
                            document.getElementById('form-finalizar-' + id).submit();
                        }
                    });
                }
            } else {
                Swal.fire('Error', 'Ocurrió un error al verificar el stock.', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error de Conexión', 'No se pudo contactar con el servidor.', 'error');
        });
}
</script>
@endsection
