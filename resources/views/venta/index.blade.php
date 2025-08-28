@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Ventas registradas</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">Volver</a>
    @if(isset($bodega))
        <a href="{{ route('venta.index.bodega', $bodega->idbodega) }}" class="btn btn-info">Ver ventas</a>
    @endif
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
                    <td>
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