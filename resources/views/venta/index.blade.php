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
                @foreach($venta->detalles as $i => $detalle)
                    <tr>
                        @if($i == 0)
                            <td rowspan="{{ $venta->detalles->count() }}">{{ $venta->id }}</td>
                            <td rowspan="{{ $venta->detalles->count() }}">{{ \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') }}</td>
                            <td rowspan="{{ $venta->detalles->count() }}">{{ $venta->cliente }}</td>
                            <td rowspan="{{ $venta->detalles->count() }}">{{ $venta->bodega->nombrebodega ?? $venta->bodega_id }}</td>
                        @endif
                        <td>{{ $detalle->producto->nombre ?? $detalle->producto_id }}</td>
                        <td>{{ $detalle->tipoempaque }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>{{ $detalle->precio_unitario }}</td>
                        <td>{{ $detalle->precio_total }}</td>
                        @if($i == 0)
                            <td rowspan="{{ $venta->detalles->count() }}">{{ $venta->total_venta }}</td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection
