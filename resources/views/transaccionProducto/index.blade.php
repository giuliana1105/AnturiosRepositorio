@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de la página -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Aprobación de Transacciones</h3>
            <p class="page-subtitle mb-0 text-muted">Auditoría, verificación de stock físico y liquidación de notas de pedido</p>
        </div>
        <a href="{{ route('tipoNota.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="fas fa-clipboard-list me-1"></i> Ver Notas de Pedido
        </a>
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

    <!-- Tarjetas de Resumen KPI -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                        <i class="fas fa-exchange-alt fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total Movimientos</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ ($pendientes ?? 0) + ($finalizadas ?? 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.12); color: #d97706;">
                        <i class="fas fa-clock fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Pendientes de Aprobación</small>
                        <h4 class="fw-bold mb-0 text-warning">{{ $pendientes ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                        <i class="fas fa-check-circle fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Finalizadas con Éxito</small>
                        <h4 class="fw-bold mb-0 text-success">{{ $finalizadas ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pestañas de Filtro por Estado -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3 d-flex flex-wrap gap-2 align-items-center">
            <span class="text-muted fw-bold me-2" style="font-size: 13px;">Filtrar por estado:</span>
            <a href="{{ route('transaccionProducto.index', ['estado' => '']) }}" 
               class="btn btn-sm rounded-pill px-3 {{ request('estado') == '' ? 'btn-primary shadow-sm' : 'btn-outline-secondary' }}">
                <i class="fas fa-list me-1"></i> Todas las Transacciones
            </a>
            <a href="{{ route('transaccionProducto.index', ['estado' => 'PENDIENTE']) }}" 
               class="btn btn-sm rounded-pill px-3 {{ request('estado') == 'PENDIENTE' ? 'btn-warning text-dark fw-bold shadow-sm' : 'btn-outline-secondary' }}">
                <i class="fas fa-clock me-1"></i> Pendientes ({{ $pendientes ?? 0 }})
            </a>
            <a href="{{ route('transaccionProducto.index', ['estado' => 'FINALIZADA']) }}" 
               class="btn btn-sm rounded-pill px-3 {{ request('estado') == 'FINALIZADA' ? 'btn-success text-white fw-bold shadow-sm' : 'btn-outline-secondary' }}">
                <i class="fas fa-check-circle me-1"></i> Finalizadas ({{ $finalizadas ?? 0 }})
            </a>
        </div>
    </div>

    <!-- Tabla Principal de Transacciones -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Código Nota</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Tipo de Nota</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Bodega Solicitante</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Estado</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Productos Involucrados</th>
                            <th class="text-end pe-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($transacciones as $transaccion)
                            @php
                                $esEnvio = ($transaccion->tipoNota->tiponota === 'ENVIO');
                                $tipoBadge = $esEnvio 
                                    ? ['bg' => '#dbeafe', 'color' => '#1e40af', 'border' => '#bfdbfe', 'icon' => 'fa-paper-plane']
                                    : ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'icon' => 'fa-undo'];
                                
                                $estadoStyles = [
                                    'PENDIENTE' => ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'icon' => 'fa-clock'],
                                    'FINALIZADA' => ['bg' => '#dcfce7', 'color' => '#166534', 'border' => '#bbf7d0', 'icon' => 'fa-check-circle'],
                                    'FINALIZADA_PARCIAL' => ['bg' => '#e0f2fe', 'color' => '#0369a1', 'border' => '#bae6fd', 'icon' => 'fa-info-circle'],
                                    'RECHAZADA' => ['bg' => '#fee2e2', 'color' => '#991b1b', 'border' => '#fecaca', 'icon' => 'fa-times-circle'],
                                ];
                                $eStyle = $estadoStyles[$transaccion->estado] ?? ['bg' => '#f1f5f9', 'color' => '#334155', 'border' => '#e2e8f0', 'icon' => 'fa-check'];
                            @endphp
                            <tr style="transition: background-color 0.15s ease;">
                                <td class="ps-4 py-3">
                                    <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1 font-mono fw-bold" style="font-size: 12px;">
                                        #{{ $transaccion->tipoNota->codigo }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: {{ $tipoBadge['bg'] }}; color: {{ $tipoBadge['color'] }}; border: 1px solid {{ $tipoBadge['border'] }}; font-size: 11.5px;">
                                        <i class="fas {{ $tipoBadge['icon'] }} me-1" style="font-size: 10px;"></i>{{ $transaccion->tipoNota->tiponota }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(0, 151, 167, 0.08); color: #006064; border: 1px solid rgba(0, 151, 167, 0.2); font-size: 12px;">
                                        <i class="fas fa-warehouse me-1 text-primary"></i>{{ $transaccion->tipoNota->bodega->nombrebodega ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-2.5 py-1 fw-semibold" style="background: {{ $eStyle['bg'] }}; color: {{ $eStyle['color'] }}; border: 1px solid {{ $eStyle['border'] }}; font-size: 11.5px;">
                                        <i class="fas {{ $eStyle['icon'] }} me-1" style="font-size: 10px;"></i>{{ str_replace('_', ' ', $transaccion->estado) }}
                                    </span>
                                </td>
                                <td class="py-3" style="max-width: 340px;">
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($transaccion->tipoNota->detalles as $detalle)
                                            <span class="badge bg-white text-dark border fw-medium px-2 py-1 shadow-xs" style="font-size: 11.5px; border-color: #e2e8f0 !important;">
                                                <strong class="text-primary me-1">{{ $detalle->cantidad }}x</strong>
                                                {{ $detalle->producto->nombre ?? 'N/A' }}
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
                                            <button type="button" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm fw-semibold" onclick="verificarYFinalizar({{ $transaccion->id }})">
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
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: #f1f5f9; color: #94a3b8;">
                                            <i class="fas fa-clipboard-check fs-3"></i>
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
                <div class="px-4 py-3 border-top d-flex justify-content-center bg-white">
                    {{ $transacciones->appends(['estado' => request('estado'), 'search' => request('search')])->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.shadow-xs {
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
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
