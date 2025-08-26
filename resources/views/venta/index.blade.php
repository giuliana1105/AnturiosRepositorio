@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Ventas registradas</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">Volver</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nro. venta</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Bodega</th>
                <th>Producto</th>
                <th>Tipo Empaque</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Precio Total</th>
                <th>Total venta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
                <tr>
                    <td>{{ $venta->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') }}</td>
                    <td>{{ $venta->cliente }}</td>
                    <td>{{ $venta->bodega->nombrebodega ?? $venta->bodega_id }}</td>
                    <td>
                        @foreach($venta->detalles as $detalle)
                            {{ $detalle->producto->nombre ?? $detalle->producto_id }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach($venta->detalles as $detalle)
                            {{ $detalle->tipoempaque }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach($venta->detalles as $detalle)
                            {{ $detalle->cantidad }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach($venta->detalles as $detalle)
                            {{ $detalle->precio_unitario }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach($venta->detalles as $detalle)
                            {{ $detalle->precio_total }}<br>
                        @endforeach
                    </td>
                    <td>{{ $venta->total_venta }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
