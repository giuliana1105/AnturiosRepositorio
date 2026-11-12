@extends('layouts.app')

@php
    $cargo = auth()->user()->cargoNombre();
    $esCredito = ($venta->tipo_pago === 'Crédito');
    $totalAbonado = $esCredito ? $abonos->sum('abono') : 0;
    $saldoPendiente = $esCredito ? ($venta->total_venta - $totalAbonado) : 0;
    $totalItems = $venta->detalles->sum('cantidad');
@endphp

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de la página -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h3 class="fw-bold mb-0">Detalle de Venta #{{ $venta->nro_venta ?? $venta->id }}</h3>
                @if($esCredito)
                    @if($saldoPendiente > 0)
                        <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 12px;">
                            <i class="fas fa-clock me-1"></i>Crédito Pendiente
                        </span>
                    @else
                        <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: 12px;">
                            <i class="fas fa-check-circle me-1"></i>Crédito Liquidado
                        </span>
                    @endif
                @elseif($venta->tipo_pago === 'Efectivo')
                    <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: 12px;">
                        <i class="fas fa-money-bill-wave me-1"></i>Pagado (Efectivo)
                    </span>
                @elseif($venta->tipo_pago === 'Transferencia')
                    <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; font-size: 12px;">
                        <i class="fas fa-university me-1"></i>Pagado (Transferencia)
                    </span>
                @elseif($venta->tipo_pago === 'Cheque')
                    <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; font-size: 12px;">
                        <i class="fas fa-money-check me-1"></i>Pagado (Cheque)
                    </span>
                @endif
            </div>
            <p class="page-subtitle mb-0 text-muted">
                Emitida el {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }} &bull; Bodega: <strong>{{ $venta->bodega->nombrebodega ?? $venta->bodega_id }}</strong>
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('venta.index.bodega', $venta->bodega_id) }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Volver a Ventas
            </a>
            @if($esCredito && $saldoPendiente > 0)
                <a href="{{ route('venta.abono', $venta->id) }}" class="btn btn-success rounded-pill px-3 shadow-sm">
                    <i class="fas fa-hand-holding-usd me-1"></i> Registrar Abono
                </a>
            @endif
            @if(in_array($cargo, ['Administrador', 'Gerente']))
                <a href="{{ route('venta.edit', $venta->id) }}" class="btn btn-outline-primary rounded-pill px-3 shadow-sm">
                    <i class="fas fa-edit me-1"></i> Editar Venta
                </a>
            @endif
        </div>
    </div>

    <!-- Tarjetas de Resumen KPI -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                        <i class="fas fa-receipt fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Monto Total</small>
                        <h4 class="fw-bold mb-0 text-dark">${{ number_format($venta->total_venta, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: {{ $esCredito && $saldoPendiente > 0 ? 'rgba(239, 68, 68, 0.12)' : 'rgba(16, 185, 129, 0.12)' }}; color: {{ $esCredito && $saldoPendiente > 0 ? '#dc2626' : '#059669' }};">
                        <i class="fas {{ $esCredito && $saldoPendiente > 0 ? 'fa-clock' : 'fa-check' }} fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Saldo Pendiente</small>
                        <h4 class="fw-bold mb-0 {{ $esCredito && $saldoPendiente > 0 ? 'text-danger' : 'text-success' }}">
                            ${{ number_format($saldoPendiente, 2) }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                        <i class="fas fa-credit-card fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Forma de Pago</small>
                        <h5 class="fw-bold mb-0 text-dark">{{ $venta->tipo_pago }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                        <i class="fas fa-boxes fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Unidades Vendidas</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalItems }} <small class="fs-6 fw-normal text-muted">items</small></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal (Layout 2 Columnas) -->
    <div class="row g-4">
        <!-- Columna Izquierda: Ficha del Cliente y Destino -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                        <i class="fas fa-user" style="font-size: 13px;"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-dark">Información del Cliente</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-xs" style="width: 48px; height: 48px; background: rgba(0, 151, 167, 0.15); color: var(--primary); font-weight: 700; font-size: 18px;">
                            {{ strtoupper(substr($venta->cliente, 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0 fs-6">{{ $venta->cliente }}</h6>
                            @if($venta->cliente_id && $venta->clienteRelacion && $venta->clienteRelacion->nombre_empresa)
                                <div class="text-muted" style="font-size: 12.5px;">
                                    <i class="fas fa-building me-1"></i>{{ $venta->clienteRelacion->nombre_empresa }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @if($venta->cliente_id && $venta->clienteRelacion)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted" style="font-size: 13px;"><i class="fas fa-id-card me-2 text-muted"></i>Identificación:</span>
                                <span class="font-mono fw-bold text-dark" style="font-size: 13px;">{{ $venta->clienteRelacion->cedula }}</span>
                            </div>

                            @if($venta->clienteRelacion->celular)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted" style="font-size: 13px;"><i class="fas fa-phone me-2 text-muted"></i>Teléfono:</span>
                                <a href="tel:{{ $venta->clienteRelacion->celular }}" class="text-decoration-none font-mono fw-bold text-dark" style="font-size: 13px;">
                                    {{ $venta->clienteRelacion->celular }}
                                </a>
                            </div>
                            @endif

                            @if($venta->clienteRelacion->email)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted" style="font-size: 13px;"><i class="far fa-envelope me-2 text-muted"></i>Correo:</span>
                                <span class="text-dark" style="font-size: 12.5px;">{{ $venta->clienteRelacion->email }}</span>
                            </div>
                            @endif
                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted" style="font-size: 13px;"><i class="fas fa-map-marker-alt me-2 text-muted"></i>Ciudad de Entrega:</span>
                            <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1" style="font-size: 12px;">
                                {{ $venta->ciudad ?? 'No especificada' }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted" style="font-size: 13px;"><i class="fas fa-warehouse me-2 text-muted"></i>Bodega de Despacho:</span>
                            <span class="text-dark fw-medium" style="font-size: 13px;">
                                {{ $venta->bodega->nombrebodega ?? $venta->bodega_id }}
                            </span>
                        </div>
                    </div>

                    @if($venta->cliente_id)
                        <div class="mt-4 pt-3 border-top">
                            <a href="{{ route('clientes.show', $venta->cliente_id) }}" class="btn btn-outline-primary rounded-pill w-100 py-1.5 shadow-xs" style="font-size: 13px;">
                                <i class="fas fa-user-circle me-1"></i> Ver Historial Completo del Cliente
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Productos y Abonos -->
        <div class="col-12 col-lg-8">
            <!-- Tarjeta de Productos Vendidos -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                            <i class="fas fa-shopping-bag" style="font-size: 13px;"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark">Productos Vendidos</h6>
                    </div>
                    <span class="badge rounded-pill bg-light text-muted border px-2.5 py-1" style="font-size: 11.5px;">
                        {{ $venta->detalles->count() }} {{ $venta->detalles->count() === 1 ? 'línea' : 'líneas' }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                <tr>
                                    <th class="ps-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Código</th>
                                    <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Producto</th>
                                    <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Empaque</th>
                                    <th class="py-3 text-uppercase text-muted fw-bold text-center" style="font-size: 11.5px; letter-spacing: 0.5px;">Cantidad</th>
                                    <th class="py-3 text-uppercase text-muted fw-bold text-end" style="font-size: 11.5px; letter-spacing: 0.5px;">P. Unitario</th>
                                    <th class="text-end pe-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($venta->detalles as $detalle)
                                    <tr style="transition: background-color 0.15s ease;">
                                        <td class="ps-4 py-3">
                                            @if($detalle->es_extra)
                                                <span class="text-muted font-mono" style="font-size: 12px;">—</span>
                                            @else
                                                <span class="badge rounded-pill bg-light text-dark border px-2.5 py-0.5 font-mono" style="font-size: 11.5px;">
                                                    {{ $detalle->producto->codigo ?? $detalle->producto_id }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            <div class="fw-bold text-dark" style="font-size: 13.5px;">
                                                @if($detalle->es_extra)
                                                    {{ $detalle->nombre_extra }}
                                                    <span class="badge rounded-pill ms-1" style="background: rgba(0, 151, 167, 0.12); color: var(--primary); font-size: 10px; border: 1px solid rgba(0, 151, 167, 0.25);">
                                                        Item Extra
                                                    </span>
                                                @else
                                                    {{ $detalle->producto->nombre ?? $detalle->producto_id }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge rounded-pill bg-light text-muted border px-2 py-0.5" style="font-size: 11.5px;">
                                                {{ $detalle->tipoempaque ?? 'Unidad' }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-center">
                                            <span class="font-mono fw-bold text-dark" style="font-size: 13.5px;">{{ $detalle->cantidad }}</span>
                                        </td>
                                        <td class="py-3 text-end">
                                            <span class="font-mono text-muted" style="font-size: 13px;">${{ number_format($detalle->precio_unitario, 2) }}</span>
                                        </td>
                                        <td class="text-end pe-4 py-3">
                                            <span class="font-mono fw-bold text-dark fs-6">${{ number_format($detalle->precio_total, 2) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background: #f8fafc; border-top: 2px solid #e2e8f0;">
                                <tr>
                                    <th colspan="4" class="ps-4 py-3 text-end text-muted fw-bold" style="font-size: 13px;">Total de la Factura:</th>
                                    <th colspan="2" class="pe-4 py-3 text-end font-mono fw-bold text-success fs-5">${{ number_format($venta->total_venta, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sección de Abonos (Solo si es Crédito) -->
            @if($esCredito)
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(245, 158, 11, 0.12); color: #d97706;">
                                <i class="fas fa-hand-holding-usd" style="font-size: 13px;"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark">Control de Abonos y Estado del Crédito</h6>
                        </div>
                        @if($saldoPendiente > 0)
                            <a href="{{ route('venta.abono', $venta->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-xs">
                                <i class="fas fa-plus me-1"></i> Nuevo Abono
                            </a>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <!-- Barra de Progreso del Pago -->
                        @php
                            $porcentajePagado = $venta->total_venta > 0 ? min(100, round(($totalAbonado / $venta->total_venta) * 100)) : 100;
                        @endphp
                        <div class="mb-4 p-3 rounded-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark" style="font-size: 13px;">Progreso de Amortización</span>
                                <span class="badge rounded-pill {{ $saldoPendiente <= 0 ? 'bg-success' : 'bg-warning text-dark' }} px-2.5 py-1 font-mono fw-bold" style="font-size: 12px;">
                                    {{ $porcentajePagado }}% Pagado
                                </span>
                            </div>
                            <div class="progress rounded-pill" style="height: 10px;">
                                <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: {{ $porcentajePagado }}%;" aria-valuenow="{{ $porcentajePagado }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 font-mono" style="font-size: 12px;">
                                <span class="text-muted">Abonado: <strong class="text-success">${{ number_format($totalAbonado, 2) }}</strong></span>
                                <span class="text-muted">Saldo restante: <strong class="text-danger">${{ number_format($saldoPendiente, 2) }}</strong></span>
                            </div>
                        </div>

                        <!-- Tabla de Abonos Realizados -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                    <tr>
                                        <th class="ps-3 py-2 text-uppercase text-muted fw-bold" style="font-size: 11px;">Monto Abonado</th>
                                        <th class="py-2 text-uppercase text-muted fw-bold" style="font-size: 11px;">Fecha</th>
                                        <th class="py-2 text-uppercase text-muted fw-bold" style="font-size: 11px;">Método de Pago</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @forelse($abonos as $abono)
                                        <tr>
                                            <td class="ps-3 py-2.5">
                                                <span class="font-mono fw-bold text-success fs-6">${{ number_format($abono->abono, 2) }}</span>
                                            </td>
                                            <td class="py-2.5 font-mono text-muted" style="font-size: 12.5px;">
                                                <i class="far fa-calendar-alt me-1 text-muted"></i>{{ $abono->fecha }}
                                            </td>
                                            <td class="py-2.5">
                                                <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1" style="font-size: 11px;">
                                                    <i class="fas fa-wallet me-1 text-primary"></i>{{ $abono->tipo_pago }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted" style="font-size: 13px;">
                                                <i class="fas fa-info-circle me-1"></i> Aún no se han registrado abonos para este crédito.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
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
@endsection
