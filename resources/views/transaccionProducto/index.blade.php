@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Transacciones</h3>
            <p class="page-subtitle">Aprobación y seguimiento de movimientos de inventario</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Status Filter Tabs -->
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <a href="{{ route('transaccionProducto.index', ['estado' => '']) }}" 
           class="btn {{ request('estado') == '' ? 'btn-info' : 'btn-secondary' }}">
            <i class="fas fa-list"></i> Todas
        </a>
        <a href="{{ route('transaccionProducto.index', ['estado' => 'PENDIENTE']) }}" 
           class="btn {{ request('estado') == 'PENDIENTE' ? 'btn-warning' : 'btn-secondary' }}">
            <i class="fas fa-clock"></i> Pendientes
        </a>
        <a href="{{ route('transaccionProducto.index', ['estado' => 'FINALIZADA']) }}" 
           class="btn {{ request('estado') == 'FINALIZADA' ? 'btn-success' : 'btn-secondary' }}">
            <i class="fas fa-check-circle"></i> Finalizadas
        </a>
    </div>

    <!-- Counters -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="stat-card" style="border-left: 3px solid var(--warning); cursor: default;">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: var(--warning-bg); color: var(--warning);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div style="font-size: 24px; font-weight: 700; font-family: var(--font-mono); color: var(--warning);">{{ $pendientes ?? 0 }}</div>
                        <div style="font-size: 12px; color: var(--muted); font-weight: 500;">PENDIENTES</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card" style="border-left: 3px solid var(--success); cursor: default;">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: var(--success-bg); color: var(--success);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div style="font-size: 24px; font-weight: 700; font-family: var(--font-mono); color: var(--success);">{{ $finalizadas ?? 0 }}</div>
                        <div style="font-size: 12px; color: var(--muted); font-weight: 500;">FINALIZADAS</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Código Nota</th>
                            <th>Tipo Nota</th>
                            <th>Bodega Solicitante</th>
                            <th>Estado</th>
                            <th>Productos</th>
                            <th style="width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transacciones as $transaccion)
                            <tr>
                                <td><span class="font-mono fw-medium">{{ $transaccion->tipoNota->codigo }}</span></td>
                                <td>
                                    <span class="badge {{ $transaccion->tipoNota->tiponota == 'ENVIO' ? 'bg-info' : 'bg-warning' }}">
                                        {{ $transaccion->tipoNota->tiponota }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-medium text-dark">{{ $transaccion->tipoNota->bodega->nombrebodega ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = 'bg-secondary';
                                        $iconClass = 'fa-check';
                                        if ($transaccion->estado == 'PENDIENTE') {
                                            $badgeClass = 'bg-warning';
                                            $iconClass = 'fa-clock';
                                        } elseif ($transaccion->estado == 'FINALIZADA') {
                                            $badgeClass = 'bg-success';
                                            $iconClass = 'fa-check-circle';
                                        } elseif ($transaccion->estado == 'FINALIZADA_PARCIAL') {
                                            $badgeClass = 'bg-info';
                                            $iconClass = 'fa-exclamation-circle';
                                        } elseif ($transaccion->estado == 'RECHAZADA') {
                                            $badgeClass = 'bg-danger';
                                            $iconClass = 'fa-times-circle';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        <i class="fas {{ $iconClass }} me-1"></i>
                                        {{ str_replace('_', ' ', $transaccion->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="max-height: 160px; overflow-y: auto;">
                                        @foreach ($transaccion->tipoNota->detalles as $detalle)
                                            <div class="d-flex align-items-center gap-2 {{ !$loop->last ? 'pb-2 mb-2' : '' }}" style="{{ !$loop->last ? 'border-bottom: 1px solid var(--border-light);' : '' }}">
                                                <div style="width: 28px; height: 28px; border-radius: var(--radius-xs); background: var(--accent-subtle); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <i class="fas fa-cube" style="font-size: 11px; color: var(--accent);"></i>
                                                </div>
                                                <div style="min-width: 0;">
                                                    <div style="font-size: 13px; font-weight: 500;">{{ $detalle->producto->nombre ?? 'N/A' }}</div>
                                                    <div style="font-size: 11px; color: var(--muted);">
                                                        Cant: <span class="font-mono">{{ $detalle->cantidad }}</span>
                                                        · {{ $detalle->producto->tipoempaque ?? 'Sin Empaque' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($transaccion->estado == 'PENDIENTE')
                                        <form id="form-finalizar-{{ $transaccion->id }}" action="{{ route('transaccionProducto.finalizar', $transaccion->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="force_reject" id="force-reject-{{ $transaccion->id }}" value="0">
                                            <button type="button" class="btn btn-success btn-sm" onclick="verificarYFinalizar({{ $transaccion->id }})">
                                                <i class="fas fa-check"></i> Finalizar
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary" style="font-size: 10px;">
                                            <i class="fas fa-check-double me-1"></i> Completado
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4" style="color: var(--muted);">
                                    <i class="fas fa-clipboard-list d-block mb-2" style="font-size: 24px; opacity: 0.4;"></i>
                                    No hay transacciones registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $transacciones->appends(['estado' => request('estado'), 'search' => request('search')])->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function verificarYFinalizar(id) {
    Swal.fire({
        title: 'Verificando stock...',
        text: 'Por favor espera',
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
                    text: 'Todos los productos tienen stock completo en bodega.',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, Finalizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-finalizar-' + id).submit();
                    }
                });
            } else if (data.status === 'insufficient') {
                let htmlMsg = '<div style="text-align: left; font-size: 14px;">' +
                              '<p class="text-danger fw-bold mb-2"><i class="fas fa-exclamation-triangle"></i> Faltan los siguientes productos:</p>' +
                              '<ul style="padding-left: 20px;">';
                
                data.faltantes.forEach(item => {
                    htmlMsg += `<li><b>${item.nombre}</b><br><span class="text-muted">Solicitado: ${item.solicitado} | Disponible: <strong class="text-danger">${item.disponible}</strong></span></li>`;
                });
                htmlMsg += '</ul><hr>';
                
                if (data.hay_stock_parcial) {
                    htmlMsg += '<p class="mb-0 fw-bold">¿Deseas realizar un DESPACHO PARCIAL enviando solo lo disponible?</p></div>';
                    
                    Swal.fire({
                        title: 'Stock Insuficiente',
                        html: htmlMsg,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#0dcaf0',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, Despachar Parcial',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('form-finalizar-' + id).submit();
                        }
                    });
                } else {
                    htmlMsg += '<p class="mb-0 fw-bold">No hay stock para NINGÚN producto.</p></div>';
                    
                    Swal.fire({
                        title: 'Sin Stock Total',
                        html: htmlMsg,
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, Rechazar Pedido',
                        cancelButtonText: 'Cancelar'
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