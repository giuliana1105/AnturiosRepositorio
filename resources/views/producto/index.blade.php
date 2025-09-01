@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Lista de Productos</h3>
    <form action="{{ route('productos.index') }}" method="GET" class="mb-3">
        <!-- filtros o búsqueda aquí -->
    </form>
    @php
        $cargo = auth()->user()->cargoNombre();
    @endphp

    @if(in_array($cargo, ['Administrador', 'Gerente']))
        <a href="{{ route('productos.create') }}" class="btn btn-primary mb-3">Añadir Producto</a>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Tipo de Empaque</th> <!-- Nueva columna -->
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->descripcion }}</td>
                    <td>{{ $producto->cantidad }}</td>
                    <td>{{ $producto->tipoempaque }}</td> <!-- Muestra el tipo de empaque -->
                    <td>
                        <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-info">Ver</a>
                        @if(in_array($cargo, ['Administrador', 'Gerente']))
                            <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
