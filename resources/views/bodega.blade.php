@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Productos en {{ $bodega->nombrebodega }}</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $item)
                <tr>
                    <td>{{ $item->producto->codigo ?? '' }}</td>
                    <td>{{ $item->producto->nombre ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection