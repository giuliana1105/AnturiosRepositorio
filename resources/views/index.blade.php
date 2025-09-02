
@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('home') }}" class="btn btn-secondary mb-3">
        Volver
    </a>
    <h3 class="mb-4">Seleccione una Bodega</h3>
    <div class="row">
        @foreach($bodegas as $bodega)
            <div class="col-md-3 mb-3">
                <a href="{{ route('home.bodega', $bodega->idbodega) }}" class="btn btn-outline-primary w-100">
                    {{ $bodega->nombrebodega }}
                </a>
            </div>
        @endforeach
        <div class="col-md-3 mb-3">
            <a href="{{ route('home.master') }}" class="btn btn-dark w-100">
                Bodega Master
            </a>
        </div>
    </div>
</div>

@php
    $cargo = auth()->user()->cargoNombre();
@endphp

{{-- Depuración temporal --}}
{{-- <div>Cargo: {{ $cargo }}</div> --}}

<table class="table">
    <thead>
        <tr>
            <!-- tus encabezados -->
        </tr>
    </thead>
    <tbody>
        @foreach($ventas as $venta)
            <tr>
                <!-- ...otros campos... -->
                <td>
                    <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-info btn-sm">Ver</a>
                    @if(in_array($cargo, ['Administrador', 'Gerente']))
                        <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection