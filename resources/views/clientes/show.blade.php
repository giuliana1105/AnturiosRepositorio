@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Detalle del Cliente</h3>
            <p class="page-subtitle">{{ $cliente->nombre_completo }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
            <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Tarjeta de Información del Cliente -->
        <div class="col-lg-4">
            <div class="card h-100" style="border-top: 3px solid var(--primary);">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary mb-3" style="width: 70px; height: 70px; font-size: 28px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <h5 class="fw-bold mb-1">{{ $cliente->nombre_completo }}</h5>
                        @if($cliente->nombre_empresa)
                            <span class="badge bg-info text-white rounded-pill px-3 py-1">
                                <i class="fas fa-building me-1"></i>{{ $cliente->nombre_empresa }}
                            </span>
                        @endif
                    </div>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted"><i class="fas fa-id-card me-2"></i>Cédula / RUC:</span>
                            <span class="fw-semibold font-mono">{{ $cliente->cedula }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted"><i class="fas fa-phone me-2"></i>Celular:</span>
                            <span class="fw-semibold">{{ $cliente->celular }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted"><i class="fas fa-envelope me-2"></i>Correo:</span>
                            <span class="fw-semibold">{{ $cliente->correo ?? 'No registrado' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start px-0 py-2">
                            <span class="text-muted"><i class="fas fa-map-marker-alt me-2"></i>Dirección:</span>
                            <span class="fw-semibold text-end" style="max-width: 60%;">{{ $cliente->direccion }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted"><i class="fas fa-calendar-alt me-2"></i>Registrado:</span>
                            <span class="text-muted" style="font-size: 13px;">{{ $cliente->created_at->format('d/m/Y') }}</span>
                        </li>
                    </ul>

                    <div class="row g-2 mt-4 pt-3 border-top text-center">
                        <div class="col-6">
                            <div class="p-2 rounded bg-light">
                                <small class="text-muted d-block">Ventas Realizadas</small>
                                <span class="fs-5 fw-bold text-primary">{{ $cliente->ventas->count() }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 rounded bg-light">
                                <small class="text-muted d-block">Total Facturado</small>
                                <span class="fs-5 fw-bold text-success">${{ number_format($totalComprado, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Ventas del Cliente -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header border-bottom bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-shopping-bag text-primary me-2"></i> Historial de Ventas
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nro.</th>
                                    <th>Fecha</th>
                                    <th>Bodega</th>
                                    <th>Ciudad</th>
                                    <th>Pago</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center" style="width: 80px;">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cliente->ventas as $venta)
                                    <tr>
                                        <td>
                                            <span class="font-mono fw-bold">#{{ $venta->nro_venta }}</span>
                                        </td>
                                        <td style="font-size: 13px; color: var(--muted);">
                                            {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $venta->bodega->nombrebodega ?? $venta->bodega_id }}
                                            </span>
                                        </td>
                                        <td>{{ $venta->ciudad ?? '—' }}</td>
                                        <td>
                                            <span class="badge {{ $venta->tipo_pago === 'Crédito' ? 'bg-warning' : 'bg-secondary' }}">
                                                {{ $venta->tipo_pago }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold font-mono text-success">
                                            ${{ number_format($venta->total_venta, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('venta.show', $venta->id) }}" class="btn btn-sm btn-info btn-icon" title="Ver detalle de venta">
                                                <i class="fas fa-eye" style="font-size: 12px;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fas fa-shopping-cart d-block mb-2" style="font-size: 32px; opacity: 0.3;"></i>
                                            Este cliente aún no tiene ventas registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

