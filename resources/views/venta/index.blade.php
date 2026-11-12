@extends('layouts.app')

@php
    $cargo = auth()->user()->cargoNombre();
    $totalVentasCount = $ventas->count();
    $totalFacturado = $ventas->sum('total_venta');
    $totalSaldoPendiente = $ventas->where('tipo_pago', 'Crédito')->sum('saldo');
    $creditosPendientesCount = $ventas->where('tipo_pago', 'Crédito')->where('saldo', '>', 0)->count();
@endphp

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de la página -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h3 class="fw-bold mb-0">Gestión de Ventas</h3>
                @if(isset($bodega))
                    <span class="badge rounded-pill px-3 py-1" style="background: rgba(0, 151, 167, 0.12); color: var(--primary); font-size: 13px; border: 1px solid rgba(0, 151, 167, 0.25);">
                        <i class="fas fa-warehouse me-1"></i> Bodega: {{ $bodega->nombrebodega }}
                    </span>
                @endif
            </div>
            <p class="page-subtitle mb-0 text-muted">Historial de facturación, control de cobros y ventas a crédito</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if(isset($bodega))
                <a href="{{ route('bodega.show', $bodega->idbodega) }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Bodega
                </a>
                <a href="{{ route('venta.create', $bodega->idbodega) }}" class="btn btn-primary rounded-pill px-3 shadow-sm">
                    <i class="fas fa-plus me-1"></i> Registrar Nueva Venta
                </a>
            @endif
            <button id="btn-reporte" class="btn btn-danger rounded-pill px-3 shadow-sm" data-bodega="{{ isset($bodega) ? $bodega->idbodega : '' }}">
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

    <!-- Tarjetas de Resumen KPI -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                        <i class="fas fa-shopping-bag fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Ventas Totales</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalVentasCount) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                        <i class="fas fa-dollar-sign fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total Facturado</small>
                        <h4 class="fw-bold mb-0 text-dark">${{ number_format($totalFacturado, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.12); color: #d97706;">
                        <i class="fas fa-clock fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Saldo por Cobrar</small>
                        <h4 class="fw-bold mb-0 text-dark">${{ number_format($totalSaldoPendiente, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.12); color: #dc2626;">
                        <i class="fas fa-hand-holding-usd fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Créditos con Saldo</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $creditosPendientesCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de Búsqueda Dinámica -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                <i class="fas fa-filter" style="font-size: 12px;"></i>
            </div>
            <h6 class="fw-bold mb-0 text-dark">Filtros de Búsqueda Rápida</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="filtro-cliente" class="form-label fw-bold text-dark" style="font-size: 13px;">Cliente</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-1" id="filtro-cliente" placeholder="Nombre o empresa...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="filtro-ciudad" class="form-label fw-bold text-dark" style="font-size: 13px;">Ciudad de Entrega</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-map-marker-alt"></i>
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
                <div class="col-md-3">
                    <label for="filtro-pago" class="form-label fw-bold text-dark" style="font-size: 13px;">Forma / Estado de Pago</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-credit-card"></i>
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
                <div class="col-md-3">
                    <label for="filtro-dia" class="form-label fw-bold text-dark" style="font-size: 13px;">Fecha Exacta</label>
                    <div class="input-group">
                        <input type="date" class="form-control rounded-start-3" id="filtro-dia">
                        <button type="button" id="clear-dia" class="btn btn-outline-secondary" title="Limpiar"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1 pt-2 border-top">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size: 13px;">Filtrar por Rango de Fechas</label>
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
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabla-ventas">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Nro.</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Fecha</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Cliente</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Destino / Ciudad</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Bodega</th>
                            <th class="py-3 text-uppercase text-muted fw-bold text-end" style="font-size: 11.5px; letter-spacing: 0.5px;">Total Venta</th>
                            <th class="py-3 text-uppercase text-muted fw-bold text-end" style="font-size: 11.5px; letter-spacing: 0.5px;">Saldo Pendiente</th>
                            <th class="py-3 text-uppercase text-muted fw-bold text-center" style="font-size: 11.5px; letter-spacing: 0.5px;">Estado de Pago</th>
                            <th class="text-end pe-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($ventas as $venta)
                            @php
                                $fechaFormat = \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d');
                                $esCredito = ($venta->tipo_pago === 'Crédito');
                                $saldoVal = (float) ($venta->saldo ?? 0);
                            @endphp
                            <tr style="transition: background-color 0.15s ease;">
                                <td class="ps-4 py-3">
                                    <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1 font-mono fw-bold" style="font-size: 12px;">
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
                                        <span class="badge rounded-pill bg-light text-secondary border px-2.5 py-1" style="font-size: 11.5px;">
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
                                            <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 11.5px;">
                                                <i class="fas fa-clock me-1"></i>Crédito Pdte.
                                            </span>
                                        @else
                                            <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: 11.5px;">
                                                <i class="fas fa-check-circle me-1"></i>Crédito Pagado
                                            </span>
                                        @endif
                                    @elseif($venta->tipo_pago === 'Efectivo')
                                        <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: 11.5px;">
                                            <i class="fas fa-money-bill-wave me-1"></i>Efectivo
                                        </span>
                                    @elseif($venta->tipo_pago === 'Transferencia')
                                        <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; font-size: 11.5px;">
                                            <i class="fas fa-university me-1"></i>Transferencia
                                        </span>
                                    @elseif($venta->tipo_pago === 'Cheque')
                                        <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; font-size: 11.5px;">
                                            <i class="fas fa-money-check me-1"></i>Cheque
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-light text-muted border px-2.5 py-1" style="font-size: 11.5px;">
                                            {{ $venta->tipo_pago ?? 'N/A' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4 py-3">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('venta.show', $venta->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Ver Detalle de Factura" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-eye" style="font-size: 12px;"></i>
                                        </a>

                                        @if($esCredito && $saldoVal > 0)
                                            <a href="{{ route('venta.abono', $venta->id) }}" class="btn btn-sm btn-outline-success rounded-circle" title="Registrar Abono a este Crédito" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-hand-holding-usd" style="font-size: 12px;"></i>
                                            </a>
                                        @endif

                                        @if(in_array($cargo, ['Administrador', 'Gerente']))
                                            <a href="{{ route('venta.edit', $venta->id) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Editar Venta" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-edit" style="font-size: 12px;"></i>
                                            </a>
                                            <form action="{{ route('venta.destroy', $venta->id) }}" method="POST" class="d-inline form-delete-venta">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle btn-delete-venta" title="Eliminar Venta" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
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
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: #f1f5f9; color: #94a3b8;">
                                            <i class="fas fa-shopping-cart fs-3"></i>
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
        </div>
    </div>
</div>

<style>
.hover-primary:hover {
    color: var(--primary) !important;
}
.shadow-xs {
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
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
