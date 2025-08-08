@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4"> Productos enviados a  {{ $bodega->nombrebodega }}</h3>
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
</div>
@endsection