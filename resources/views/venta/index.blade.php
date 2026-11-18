@extends('layouts.app')

@php
    $cargo = auth()->user()->cargoNombre();
    $totalVentasCount = $totalVentasCount ?? ($ventas instanceof \Illuminate\Pagination\LengthAwarePaginator ? $ventas->total() : $ventas->count());
    $totalFacturado = $totalFacturado ?? $ventas->sum('total_venta');
    $totalSaldoPendiente = $totalSaldoPendiente ?? $ventas->where('tipo_pago', 'Crédito')->sum('saldo');
    $creditosPendientesCount = $creditosPendientesCount ?? $ventas->where('tipo_pago', 'Crédito')->where('saldo', '>', 0)->count();
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
                @if(isset($bodega))
                    <li class="breadcrumb-item">
                        <a href="{{ route('bodegas.index') }}" class="text-decoration-none text-muted">Bodegas</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('bodega.show', $bodega->idbodega) }}" class="text-decoration-none text-muted">{{ $bodega->nombrebodega }}</a>
                    </li>
                @endif
                <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                    Ventas
                </li>
            </ol>
        </nav>
    </div>

    <!-- Encabezado de la página -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 pb-3 mb-4 border-bottom" style="border-color: #e2e8f0 !important;">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                <h2 class="fw-bold mb-0 text-dark tracking-tight" style="letter-spacing: -0.025em;">
                    Gestión de Ventas
                </h2>
                @if(isset($bodega))
                    <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1" style="font-size: 12px;">
                        <i class="fas fa-warehouse me-1"></i> {{ $bodega->nombrebodega }}
                    </span>
                @endif
            </div>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Historial de facturación, emisión de recibos, control de pagos y gestión de saldos a crédito.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            @if(isset($bodega))
                <a href="{{ route('bodega.show', $bodega->idbodega) }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Bodega
                </a>
                @can('registrar ventas')
                <a href="{{ route('venta.create', $bodega->idbodega) }}" class="btn btn-sm btn-primary rounded-3 px-3 py-2 fw-semibold shadow-sm">
                    <i class="fas fa-plus me-1"></i> Registrar Nueva Venta
                </a>
                @endcan
            @endif
            <button id="btn-reporte" class="btn btn-sm btn-outline-danger rounded-3 px-3 py-2 fw-medium" data-bodega="{{ isset($bodega) ? $bodega->idbodega : '' }}">
                <i class="fas fa-file-pdf me-1"></i> Exportar Reporte PDF
            </button>
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
        <!-- KPI 1: Ventas Totales -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Ventas Totales</span>
                        <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold" style="font-size: 11px;">
                            Operaciones
                        </span>
                    </div>
                    <div class="metric-value">
                        {{ number_format($totalVentasCount) }}
                        <span class="metric-unit">ventas</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-receipt text-primary me-1"></i> Total transacciones emitidas
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Total Facturado -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Total Facturado</span>
                        <span class="badge rounded-pill bg-success-subtle text-success fw-semibold" style="font-size: 11px;">
                            Ingresos
                        </span>
                    </div>
                    <div class="metric-value text-success font-mono">
                        ${{ number_format($totalFacturado, 2) }}
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-dollar-sign text-success me-1"></i> Facturación bruta consolidada
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Saldo por Cobrar -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Saldo por Cobrar</span>
                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis fw-semibold" style="font-size: 11px;">
                            Cartera
                        </span>
                    </div>
                    <div class="metric-value text-warning-emphasis font-mono">
                        ${{ number_format($totalSaldoPendiente, 2) }}
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-clock text-warning me-1"></i> Créditos activos por liquidar
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 4: Créditos Pendientes -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Créditos con Saldo</span>
                        <span class="badge rounded-pill bg-danger-subtle text-danger fw-semibold" style="font-size: 11px;">
                            Pendientes
                        </span>
                    </div>
                    <div class="metric-value text-dark font-mono">
                        {{ $creditosPendientesCount }}
                        <span class="metric-unit">cuentas</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-hand-holding-usd text-danger me-1"></i> Facturas con cuota abierta
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Módulo de Filtros de Búsqueda Rápida -->
    <div class="card border rounded-3 mb-4 bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex align-items-center justify-content-between" style="border-color: #e2e8f0 !important;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; background: rgba(0, 151, 167, 0.1); color: var(--primary);">
                    <i class="fas fa-filter" style="font-size: 12px;"></i>
                </div>
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">Filtros Dinámicos de Búsqueda</h6>
            </div>
            <span class="text-muted d-none d-sm-inline" style="font-size: 12px;">Filtrado en tiempo real</span>
        </div>
        <div class="card-body p-3 p-md-4">
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-lg-3">
                    <label for="filtro-cliente" class="form-label fw-bold text-dark" style="font-size: 12.5px;">Cliente</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-user" style="font-size: 12px;"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-1" id="filtro-cliente" placeholder="Nombre o empresa...">
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <label for="filtro-ciudad" class="form-label fw-bold text-dark" style="font-size: 12.5px;">Ciudad de Entrega</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-map-marker-alt" style="font-size: 12px;"></i>
                        </span>
                        <select class="form-select border-start-0 ps-1" id="filtro-ciudad">
                            <option value="">Todas las ciudades</option>
                            @php
                                $ciudades = $ventas->pluck('ciudad')->unique()->filter()->sort();
                            @endphp
                            @foreach($ciudades as $ciudad)
                                <option value="{{ $ciudad }}">{{ $ciudad }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <label for="filtro-pago" class="form-label fw-bold text-dark" style="font-size: 12.5px;">Forma / Estado de Pago</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-credit-card" style="font-size: 12px;"></i>
                        </span>
                        <select class="form-select border-start-0 ps-1" id="filtro-pago">
                            <option value="">Todos los métodos</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Transferencia">Transferencia</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Crédito">Crédito (Cualquiera)</option>
                            <option value="Crédito liquidado">Crédito Liquidado (Pagado)</option>
                            <option value="Crédito pendiente">Crédito Pendiente de Pago</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <label for="filtro-dia" class="form-label fw-bold text-dark" style="font-size: 12.5px;">Fecha Exacta</label>
                    <div class="input-group">
                        <input type="date" class="form-control rounded-start-3" id="filtro-dia">
                        <button type="button" id="clear-dia" class="btn btn-outline-secondary" title="Limpiar"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1 pt-3 border-top" style="border-color: #f1f5f9 !important;">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size: 12.5px;">Filtrar por Rango de Fechas</label>
                    <div class="d-flex gap-2">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted" style="font-size: 12px;">Desde</span>
                            <input type="date" class="form-control" id="filtro-fecha-inicio">
                            <button type="button" id="clear-inicio" class="btn btn-outline-secondary" title="Limpiar"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted" style="font-size: 12px;">Hasta</span>
                            <input type="date" class="form-control" id="filtro-fecha-fin">
                            <button type="button" id="clear-fin" class="btn btn-outline-secondary" title="Limpiar"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Principal de Ventas -->
    <div class="card border rounded-3 overflow-hidden shadow-xs bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 operational-table" id="tabla-ventas">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3" style="width: 100px;">Nro.</th>
                            <th class="py-3" style="width: 130px;">Fecha</th>
                            <th class="py-3">Cliente</th>
                            <th class="py-3">Destino / Ciudad</th>
                            <th class="py-3">Bodega</th>
                            <th class="py-3 text-end" style="width: 130px;">Total Venta</th>
                            <th class="py-3 text-end" style="width: 130px;">Saldo Pdte.</th>
                            <th class="py-3 text-center" style="width: 160px;">Estado de Pago</th>
                            <th class="text-end pe-4 py-3" style="width: 130px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventas as $venta)
                            @php
                                $fechaFormat = \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d');
                                $esCredito = ($venta->tipo_pago === 'Crédito');
                                $saldoVal = (float) ($venta->saldo ?? 0);
                            @endphp
                            <tr class="operational-row">
                                <td class="ps-4 py-3">
                                    <span class="font-mono text-dark fw-bold" style="font-size: 13px;">
                                        #{{ $venta->nro_venta }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="text-muted font-mono" style="font-size: 12.5px;">
                                        <i class="far fa-calendar-alt me-1 text-muted"></i>{{ $fechaFormat }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    @if($venta->cliente_id)
                                        <a href="{{ route('clientes.show', $venta->cliente_id) }}" class="text-decoration-none fw-bold text-dark hover-primary" title="Ver perfil del cliente">
                                            {{ $venta->cliente }}
                                        </a>
                                        @if($venta->clienteRelacion && $venta->clienteRelacion->nombre_empresa)
                                            <div class="text-muted d-flex align-items-center gap-1" style="font-size: 11.5px;">
                                                <i class="fas fa-building text-muted" style="font-size: 10px;"></i>
                                                <span>{{ $venta->clienteRelacion->nombre_empresa }}</span>
                                            </div>
                                        @endif
                                    @else
                                        <span class="fw-bold text-dark">{{ $venta->cliente }}</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if($venta->ciudad)
                                        <span class="badge rounded-pill bg-light text-secondary border px-2.5 py-1" style="font-size: 11.5px; border-color: #e2e8f0 !important;">
                                            <i class="fas fa-map-marker-alt me-1 text-primary"></i>{{ $venta->ciudad }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="text-dark" style="font-size: 13px;">
                                        <i class="fas fa-warehouse text-muted me-1"></i>{{ $venta->bodega->nombrebodega ?? $venta->bodega_id }}
                                    </span>
                                </td>
                                <td class="py-3 text-end">
                                    <span class="font-mono fw-bold text-dark fs-6">
                                        ${{ number_format($venta->total_venta, 2) }}
                                    </span>
                                </td>
                                <td class="py-3 text-end">
                                    @if($esCredito && $saldoVal > 0)
                                        <span class="font-mono fw-bold text-danger fs-6">
                                            ${{ number_format($saldoVal, 2) }}
                                        </span>
                                    @else
                                        <span class="font-mono text-muted" style="font-size: 13px;">$0.00</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center" @if($esCredito) data-saldo="{{ $saldoVal }}" @endif>
                                    @if($esCredito)
                                        @if($saldoVal > 0)
                                            <span class="status-badge status-warning">
                                                <span class="status-indicator-dot"></span> Crédito Pdte.
                                            </span>
                                        @else
                                            <span class="status-badge status-success">
                                                <span class="status-indicator-dot"></span> Crédito Pagado
                                            </span>
                                        @endif
                                    @elseif($venta->tipo_pago === 'Efectivo')
                                        <span class="status-badge status-success">
                                            <span class="status-indicator-dot"></span> Efectivo
                                        </span>
                                    @elseif($venta->tipo_pago === 'Transferencia')
                                        <span class="status-badge status-info">
                                            <span class="status-indicator-dot"></span> Transferencia
                                        </span>
                                    @elseif($venta->tipo_pago === 'Cheque')
                                        <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; font-size: 11.5px;">
                                            <i class="fas fa-money-check me-1"></i> Cheque
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-light text-muted border px-2.5 py-1" style="font-size: 11.5px;">
                                            {{ $venta->tipo_pago ?? 'N/A' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4 py-3">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('venta.show', $venta->id) }}" class="btn-action-circle" title="Ver Detalle de Factura">
                                            <i class="fas fa-eye" style="font-size: 12px;"></i>
                                        </a>

                                        @if($esCredito && $saldoVal > 0)
                                            <a href="{{ route('venta.abono', $venta->id) }}" class="btn-action-circle text-success" title="Registrar Abono a este Crédito">
                                                <i class="fas fa-hand-holding-usd" style="font-size: 12px;"></i>
                                            </a>
                                        @endif

                                        @if(in_array($cargo, ['Administrador', 'Gerente']))
                                            <a href="{{ route('venta.edit', $venta->id) }}" class="btn-action-circle text-primary" title="Editar Venta">
                                                <i class="fas fa-edit" style="font-size: 12px;"></i>
                                            </a>
                                            <form action="{{ route('venta.destroy', $venta->id) }}" method="POST" class="d-inline form-delete-venta">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn-action-circle danger btn-delete-venta" title="Eliminar Venta">
                                                    <i class="fas fa-trash" style="font-size: 12px;"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: #f1f5f9; color: #94a3b8;">
                                            <i class="fas fa-shopping-cart fs-4"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No hay ventas registradas</h6>
                                        <p class="text-muted mb-0" style="font-size: 13.5px;">Las ventas registradas en las bodegas aparecerán listadas aquí.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if(method_exists($ventas, 'hasPages') && $ventas->hasPages())
                <div class="p-3 border-top d-flex justify-content-center" style="border-color: #e2e8f0 !important;">
                    {{ $ventas->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.hover-primary:hover {
    color: var(--primary) !important;
}
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clienteInput = document.getElementById('filtro-cliente');
    const ciudadSelect = document.getElementById('filtro-ciudad');
    const pagoSelect = document.getElementById('filtro-pago');
    const diaInput = document.getElementById('filtro-dia');
    const fechaInicioInput = document.getElementById('filtro-fecha-inicio');
    const fechaFinInput = document.getElementById('filtro-fecha-fin');
    const tabla = document.getElementById('tabla-ventas');

    function filtrar() {
        if (!tabla) return;
        const cliente = clienteInput.value.trim().toLowerCase();
        const ciudad = ciudadSelect.value;
        const pago = pagoSelect.value;
        const dia = diaInput.value;
        const fechaInicio = fechaInicioInput.value;
        const fechaFin = fechaFinInput.value;

        Array.from(tabla.querySelectorAll('tbody tr')).forEach(row => {
            const tdCliente = row.children[2]?.textContent.toLowerCase();
            const tdCiudad = row.children[3]?.textContent;
            const tdPago = row.children[7]?.textContent.trim();
            const tdSaldo = row.children[7]?.getAttribute('data-saldo');
            const tdFecha = row.children[1]?.textContent.trim();

            let mostrar = true;
            if (cliente && (!tdCliente || !tdCliente.includes(cliente))) mostrar = false;
            if (ciudad && (!tdCiudad || !tdCiudad.includes(ciudad))) mostrar = false;

            if (pago) {
                if (pago === 'Crédito liquidado') {
                    if (!(tdPago.includes('Crédito') && tdSaldo == 0)) mostrar = false;
                } else if (pago === 'Crédito pendiente') {
                    if (!(tdPago.includes('Crédito') && parseFloat(tdSaldo) > 0)) mostrar = false;
                } else if (!tdPago.includes(pago)) {
                    mostrar = false;
                }
            }

            if (dia && !tdFecha.includes(dia)) mostrar = false;
            if ((fechaInicio || fechaFin) && tdFecha) {
                const rowDate = tdFecha.replace(/[^0-9-]/g, '').trim();
                if (fechaInicio && rowDate < fechaInicio) mostrar = false;
                if (fechaFin && rowDate > fechaFin) mostrar = false;
            }

            row.style.display = mostrar ? '' : 'none';
        });
    }

    if (clienteInput) clienteInput.addEventListener('input', filtrar);
    if (ciudadSelect) ciudadSelect.addEventListener('change', filtrar);
    if (pagoSelect) pagoSelect.addEventListener('change', filtrar);
    if (diaInput) diaInput.addEventListener('change', filtrar);
    if (fechaInicioInput) fechaInicioInput.addEventListener('change', filtrar);
    if (fechaFinInput) fechaFinInput.addEventListener('change', filtrar);

    // Confirmación con SweetAlert para eliminar venta
    const btnsDelete = document.querySelectorAll('.btn-delete-venta');
    btnsDelete.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            let form = this.closest('form');
            Swal.fire({
                title: '¿Eliminar este registro de venta?',
                text: "Esta acción anulará la venta y afectará el balance financiero.",
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

document.getElementById('btn-reporte').addEventListener('click', function(e) {
    e.preventDefault();
    const bodega_id = this.getAttribute('data-bodega');
    const cliente = document.getElementById('filtro-cliente')?.value || '';
    const ciudad = document.getElementById('filtro-ciudad')?.value || '';
    const tipo_pago = document.getElementById('filtro-pago')?.value || '';
    const dia = document.getElementById('filtro-dia')?.value || '';
    const fecha_inicio = document.getElementById('filtro-fecha-inicio')?.value || '';
    const fecha_fin = document.getElementById('filtro-fecha-fin')?.value || '';

    let url = "{{ route('ventas.exportar') }}?";
    let params = [];
    if(bodega_id) params.push('bodega_id=' + encodeURIComponent(bodega_id));
    if(cliente) params.push('cliente=' + encodeURIComponent(cliente));
    if(ciudad) params.push('ciudad=' + encodeURIComponent(ciudad));
    if(tipo_pago) params.push('tipo_pago=' + encodeURIComponent(tipo_pago));
    if(dia) params.push('dia=' + encodeURIComponent(dia));
    if(fecha_inicio) params.push('fecha_inicio=' + encodeURIComponent(fecha_inicio));
    if(fecha_fin) params.push('fecha_fin=' + encodeURIComponent(fecha_fin));
    url += params.join('&');

    window.open(url, '_blank');
});

document.getElementById('clear-dia').addEventListener('click', function() {
    document.getElementById('filtro-dia').value = '';
    document.getElementById('filtro-dia').dispatchEvent(new Event('change'));
});
document.getElementById('clear-inicio').addEventListener('click', function() {
    document.getElementById('filtro-fecha-inicio').value = '';
    document.getElementById('filtro-fecha-inicio').dispatchEvent(new Event('change'));
});
document.getElementById('clear-fin').addEventListener('click', function() {
    document.getElementById('filtro-fecha-fin').value = '';
    document.getElementById('filtro-fecha-fin').dispatchEvent(new Event('change'));
});
</script>
@endsection
