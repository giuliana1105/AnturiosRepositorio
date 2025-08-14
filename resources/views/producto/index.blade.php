@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Lista de Productos</h3>
    <form action="{{ route('productos.index') }}" method="GET" class="mb-3">
        <!-- filtros o búsqueda aquí -->
    </form>
    <a href="{{ route('productos.create') }}" class="btn btn-primary mb-3">Añadir Producto</a>
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
                        <a href="{{ route('productos.edit', $producto->codigo) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form action="{{ route('productos.destroy', $producto->codigo) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
