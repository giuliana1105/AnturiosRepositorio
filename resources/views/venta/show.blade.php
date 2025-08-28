@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Detalle de la Venta #{{ $venta->id }}</h3>
    <a href="{{ route('venta.index') }}" class="btn btn-secondary mb-3">Volver</a>
    <table class="table table-bordered">
        <tr><th>Fecha</th><td>{{ \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') }}</td></tr>
        <tr><th>Cliente</th><td>{{ $venta->cliente }}</td></tr>
        <tr><th>Ciudad</th><td>{{ $venta->ciudad }}</td></tr>
        <tr><th>Bodega</th><td>{{ $venta->bodega->nombrebodega ?? $venta->bodega_id }}</td></tr>
        <tr><th>Total venta</th><td>{{ $venta->total_venta }}</td></tr>
        <tr><th>Forma de pago</th><td>{{ $venta->tipo_pago }}</td></tr>
    </table>

    <h4>Productos vendidos</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th> <!-- Nueva columna -->
                <th>Producto</th>
                <th>Tipo Empaque</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Precio Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->codigo ?? $detalle->producto_id }}</td> <!-- Nuevo dato -->
                    <td>{{ $detalle->producto->nombre ?? $detalle->producto_id }}</td>
                    <td>{{ $detalle->tipoempaque }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{ $detalle->precio_unitario }}</td>
                    <td>{{ $detalle->precio_total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($venta->tipo_pago === 'Crédito')
        <h4>Abonos</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Abono</th>
                    <th>Fecha</th>
                    <th>Tipo de pago</th>
                </tr>
            </thead>
            <tbody>
                @forelse($abonos as $abono)
                    <tr>
                        <td>{{ $abono->abono }}</td>
                        <td>{{ $abono->fecha }}</td>
                        <td>{{ $abono->tipo_pago }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3">No hay abonos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div>
            <strong>Saldo:</strong> {{ $venta->total_venta - $abonos->sum('abono') }}
        </div>
    @endif
</div>
@endsection
