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
            @foreach($productos as $item)
                <tr>
                    <td>{{ $item->codigo }}</td>
                    <td>{{ $item->nombre }}</td>
                    <td>{{ $item->pivot->cantidad }}</td>
                    <td>{{ $item->pivot->fecha }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 class="mt-5">Productos Devueltos</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Cantidad</th>
                <th>Nombre</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($devueltos as $item)
                <tr>
                    <td>{{ $item->pivot->cantidad }}</td>
                    <td>{{ $item->nombre }}</td>
                    <td>{{ $item->pivot->fecha }}</td>
                </tr>
            @endforeach
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
            @foreach($productosEnBodega as $item)
                <tr>
                    <td>{{ $item['codigo'] }}</td>
                    <td>{{ $item['nombre'] }}</td>
                    <td>{{ $item['descripcion'] }}</td>
                    <td>{{ $item['cantidad'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection