@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Ventas registradas</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">Volver</a>
    <!-- @if(isset($bodega))
        <a href="{{ route('venta.index.bodega', $bodega->idbodega) }}" class="btn btn-info">Ver ventas</a>
    @endif -->

    <div class="row mb-3" id="filtros-ventas">
        <div class="col-md-4">
            <input type="text" class="form-control" id="filtro-cliente" placeholder="Buscar por cliente">
        </div>
        <div class="col-md-4">
            <select class="form-control" id="filtro-ciudad">
                <option value="">Todas las ciudades</option>
                @php
                    $ciudades = $ventas->pluck('ciudad')->unique()->filter()->sort();
                @endphp
                @foreach($ciudades as $ciudad)
                    <option value="{{ $ciudad }}">{{ $ciudad }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <select class="form-control" id="filtro-pago">
                <option value="">Todas las formas de pago</option>
                <option value="Efectivo">Efectivo</option>
                <option value="Transferencia">Transferencia</option>
                <option value="Cheque">Cheque</option>
                <option value="Crédito">Crédito</option>
                <option value="Crédito liquidado">Crédito liquidado</option>
                <option value="Crédito pendiente">Crédito pendiente</option>
            </select>
        </div>
    </div>

    <div class="row mb-3" id="filtros-fechas">
        <div class="col-md-4 mt-2">
            <input type="date" class="form-control" id="filtro-dia" placeholder="Buscar por día">
        </div>
        <div class="col-md-4 mt-2">
            <input type="date" class="form-control" id="filtro-fecha-inicio" placeholder="Fecha inicio">
        </div>
        <div class="col-md-4 mt-2">
            <input type="date" class="form-control" id="filtro-fecha-fin" placeholder="Fecha fin">
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nro. venta</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Ciudad</th>
                <th>Bodega</th>
                <th>Total venta</th>
                <th>Forma de pago</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
                <tr>
                    <td>{{ $venta->nro_venta }}</td>
                    <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') }}</td>
                    <td>{{ $venta->cliente }}</td>
                    <td>{{ $venta->ciudad ?? 'N/A' }}</td>
                    <td>{{ $venta->bodega->nombrebodega ?? $venta->bodega_id }}</td>
                    <td>{{ $venta->total_venta }}</td>
                    <td
                        @if($venta->tipo_pago === 'Crédito')
                            data-saldo="{{ isset($venta->saldo) ? $venta->saldo : 0 }}"
                        @endif
                    >
                        @if($venta->tipo_pago === 'Crédito')
                            @if(isset($venta->saldo) && $venta->saldo > 0)
                                <span style="background-color:#ffdddd; color:#b30000; padding:4px 8px; border-radius:4px;">Crédito</span>
                            @else
                                <span style="background-color:#ddffdd; color:#008000; padding:4px 8px; border-radius:4px;">Crédito</span>
                            @endif
                        @else
                            {{ $venta->tipo_pago ?? 'N/A' }}
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('venta.show', $venta->id) }}" class="btn btn-info btn-sm">Detalle venta</a>
                        @if($venta->tipo_pago === 'Crédito')
                            <a href="{{ route('venta.abono', $venta->id) }}" class="btn btn-warning btn-sm">Agregar abono</a>
                        @endif
                        <a href="{{ route('venta.edit', $venta->id) }}" class="btn btn-primary btn-sm">Editar</a>
                        <form action="{{ route('venta.destroy', $venta->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta venta?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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
            const tdPago = row.children[6]?.textContent.trim();
            const tdSaldo = row.children[6]?.getAttribute('data-saldo');
            const tdFecha = row.children[1]?.textContent; // Formato: YYYY-MM-DD

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

            // Filtro por día exacto
            if (dia && tdFecha !== dia) mostrar = false;

            // Filtro por rango de fechas
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
</script>
@endsection