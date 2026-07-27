@extends('layouts.app')

@php
    $cargo = auth()->user()->cargoNombre();
@endphp

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Ventas</h3>
            <p class="page-subtitle">Registro y seguimiento de ventas realizadas</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ isset($bodega) ? route('bodega.show', $bodega->idbodega) : url()->previous() }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button id="btn-reporte" class="btn btn-danger" data-bodega="{{ isset($bodega) ? $bodega->idbodega : '' }}">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header border-bottom">
            <i class="fas fa-filter text-muted me-2"></i>Filtros de Búsqueda
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="filtro-cliente" class="form-label">Cliente</label>
                    <input type="text" class="form-control" id="filtro-cliente" placeholder="Nombre del cliente">
                </div>
                <div class="col-md-3">
                    <label for="filtro-ciudad" class="form-label">Ciudad</label>
                    <select class="form-select" id="filtro-ciudad">
                        <option value="">Todas</option>
                        @php
                            $ciudades = $ventas->pluck('ciudad')->unique()->filter()->sort();
                        @endphp
                        @foreach($ciudades as $ciudad)
                            <option value="{{ $ciudad }}">{{ $ciudad }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtro-pago" class="form-label">Forma de Pago</label>
                    <select class="form-select" id="filtro-pago">
                        <option value="">Todas</option>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Transferencia">Transferencia</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Crédito">Crédito general</option>
                        <option value="Crédito liquidado">Crédito liquidado</option>
                        <option value="Crédito pendiente">Crédito pendiente</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtro-dia" class="form-label">Día exacto</label>
                    <div class="input-group">
                        <input type="date" class="form-control" id="filtro-dia">
                        <button type="button" id="clear-dia" class="btn btn-outline-secondary" title="Limpiar"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label class="form-label">Rango de Fechas</label>
                    <div class="d-flex gap-2">
                        <div class="input-group">
                            <input type="date" class="form-control" id="filtro-fecha-inicio" placeholder="Desde">
                            <button type="button" id="clear-inicio" class="btn btn-outline-secondary" title="Limpiar"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="input-group">
                            <input type="date" class="form-control" id="filtro-fecha-fin" placeholder="Hasta">
                            <button type="button" id="clear-fin" class="btn btn-outline-secondary" title="Limpiar"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nro.</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Ciudad</th>
                            <th>Bodega</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Saldo</th>
                            <th>Pago</th>
                            <th style="width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventas as $venta)
                            <tr>
                                <td><span class="font-mono">{{ $venta->nro_venta }}</span></td>
                                <td style="color: var(--muted); font-size: 12px;">{{ \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') }}</td>
                                <td class="fw-medium">{{ $venta->cliente }}</td>
                                <td style="color: var(--secondary);">{{ $venta->ciudad ?? '—' }}</td>
                                <td style="color: var(--secondary);">{{ $venta->bodega->nombrebodega ?? $venta->bodega_id }}</td>
                                <td class="text-end">
                                    <span class="font-mono fw-bold" style="color: var(--foreground);">${{ number_format($venta->total_venta, 2) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="font-mono fw-bold" style="color: var(--foreground);">${{ number_format($venta->saldo ?? 0, 2) }}</span>
                                </td>
                                <td @if($venta->tipo_pago === 'Crédito') data-saldo="{{ isset($venta->saldo) ? $venta->saldo : 0 }}" @endif>
                                    @if($venta->tipo_pago === 'Crédito')
                                        @if(isset($venta->saldo) && $venta->saldo > 0)
                                            <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Crédito Pdte.</span>
                                        @else
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Crédito Pagado</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">{{ $venta->tipo_pago ?? 'N/A' }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('venta.show', $venta->id) }}" class="btn btn-info btn-sm btn-icon" title="Ver Detalle">
                                            <i class="fas fa-eye" style="font-size: 12px;"></i>
                                        </a>
                                        @if($venta->tipo_pago === 'Crédito' && isset($venta->saldo) && $venta->saldo > 0)
                                            <a href="{{ route('venta.abono', $venta->id) }}" class="btn btn-success btn-sm btn-icon" title="Registrar Abono">
                                                <i class="fas fa-hand-holding-usd" style="font-size: 12px;"></i>
                                            </a>
                                        @endif
                                        @if(in_array($cargo, ['Administrador', 'Gerente']))
                                            <a href="{{ route('venta.edit', $venta->id) }}" class="btn btn-warning btn-sm btn-icon" title="Editar">
                                                <i class="fas fa-edit" style="font-size: 12px;"></i>
                                            </a>
                                            <form action="{{ route('venta.destroy', $venta->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Eliminar" onclick="return confirm('¿Eliminar esta venta?')">
                                                    <i class="fas fa-trash" style="font-size: 12px;"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4" style="color: var(--muted);">
                                    <i class="fas fa-shopping-cart d-block mb-2" style="font-size: 24px; opacity: 0.4;"></i>
                                    No hay ventas registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clienteInput = document.getElementById('filtro-cliente');
    const ciudadSelect = document.getElementById('filtro-ciudad');
    const pagoSelect = document.getElementById('filtro-pago');
    const diaInput = document.getElementById('filtro-dia');
    const fechaInicioInput = document.getElementById('filtro-fecha-inicio');
    const fechaFinInput = document.getElementById('filtro-fecha-fin');
    const tabla = document.querySelector('.table');

    function filtrar() {
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
            const tdFecha = row.children[1]?.textContent;

            let mostrar = true;
            if (cliente && (!tdCliente || !tdCliente.includes(cliente))) mostrar = false;
            if (ciudad && tdCiudad !== ciudad) mostrar = false;

            if (pago) {
                if (pago === 'Crédito liquidado') {
                    if (!(tdPago.includes('Crédito') && tdSaldo == 0)) mostrar = false;
                } else if (pago === 'Crédito pendiente') {
                    if (!(tdPago.includes('Crédito') && tdSaldo > 0)) mostrar = false;
                } else if (!tdPago.includes(pago)) {
                    mostrar = false;
                }
            }

            if (dia && tdFecha !== dia) mostrar = false;
            if ((fechaInicio || fechaFin) && tdFecha) {
                if (fechaInicio && tdFecha < fechaInicio) mostrar = false;
                if (fechaFin && tdFecha > fechaFin) mostrar = false;
            }

            row.style.display = mostrar ? '' : 'none';
        });
    }

    clienteInput.addEventListener('input', filtrar);
    ciudadSelect.addEventListener('change', filtrar);
    pagoSelect.addEventListener('change', filtrar);
    diaInput.addEventListener('change', filtrar);
    fechaInicioInput.addEventListener('change', filtrar);
    fechaFinInput.addEventListener('change', filtrar);
});

document.getElementById('btn-reporte').addEventListener('click', function(e) {
    e.preventDefault();
    const bodega_id = this.getAttribute('data-bodega');
    const cliente = document.getElementById('filtro-cliente').value;
    const ciudad = document.getElementById('filtro-ciudad').value;
    const tipo_pago = document.getElementById('filtro-pago').value;
    const dia = document.getElementById('filtro-dia').value;
    const fecha_inicio = document.getElementById('filtro-fecha-inicio').value;
    const fecha_fin = document.getElementById('filtro-fecha-fin').value;

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