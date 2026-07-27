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
                                    <span class="badge {{ $transaccion->estado == 'PENDIENTE' ? 'bg-warning' : 'bg-success' }}">
                                        <i class="fas {{ $transaccion->estado == 'PENDIENTE' ? 'fa-clock' : 'fa-check-circle' }} me-1"></i>
                                        {{ $transaccion->estado }}
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
                                        <form action="{{ route('transaccionProducto.finalizar', $transaccion->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
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
@endsection