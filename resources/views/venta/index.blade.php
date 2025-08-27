
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
                    <td>{{ $venta->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') }}</td>
                    <td>{{ $venta->cliente }}</td>
                    <td>{{ $venta->ciudad ?? 'N/A' }}</td>
                    <td>{{ $venta->bodega->nombrebodega ?? $venta->bodega_id }}</td>
                    <td>{{ $venta->total_venta }}</td>
                    <td>{{ $venta->tipo_pago ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('venta.show', $venta->id) }}" class="btn btn-info btn-sm">Detalle venta</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection