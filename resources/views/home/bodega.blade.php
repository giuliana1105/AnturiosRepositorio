@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Productos en {{ $bodega->nombrebodega }}</h3>

    <h4>Productos Enviados</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productos as $item)
                <tr>
                    <td>{{ $item->codigo }}</td>
                    <td>{{ $item->nombre }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay productos enviados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h4 class="mt-5">Productos Devueltos</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($devueltos as $item)
                <tr>
                    <td>{{ $item->codigo }}</td>
                    <td>{{ $item->nombre }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay productos devueltos</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h4 class="mt-5">Productos en Bodega (Stock Actual)</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cantidad Actual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productosEnBodega as $item)
                <tr>
                    <td>{{ $item['codigo'] }}</td>
                    <td>{{ $item['nombre'] }}</td>
                    <td>{{ $item['descripcion'] }}</td>
                    <td>{{ $item['cantidad'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay productos en stock</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        <a href="{{ route('home') }}" class="btn btn-secondary">Volver al inicio</a>
        <a href="{{ route('tipoNota.create') }}" class="btn btn-primary">Crear Nueva Nota</a>
    </div>
</div>
@endsection