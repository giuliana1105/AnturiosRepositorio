@extends('layouts.app')

@section('content')

    <div class="container">
        <h2 class="text-center">Listado de Productos</h2>

        <!-- Alertas de éxito o error -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Formulario de búsqueda -->
        <form action="{{ route('producto.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por código de producto"
                        value="{{ request()->search }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100" style="background-color: #88022D">Buscar</button>
                </div>
            </div>
        </form>

        <!-- Botón para crear un nuevo producto -->
        <div class="mb-3 text-right">
            <a href="{{ route('producto.create') }}" class="btn btn-primary" style="background-color: #88022D">Añadir
                Producto</a>
        </div>

        <!-- Tabla de productos -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Tipo Empaque</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $producto)
                    <tr>
                        <td>{{ $producto->codigo }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->descripcion }}</td>
                        <td>{{ $producto->cantidad }}</td>
                        <td>{{ $producto->tipoempaque ?? 'Sin asignar' }}</td>
                        <td>
                            <a href="{{ route('producto.edit', $producto->codigo) }}"
                                class="btn btn-sm btn-primary">Editar</a>
                            <form action="{{ route('producto.destroy', $producto->codigo) }}" method="POST"
                                class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay productos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="mt-3">
            {{ $productos->links() }}
        </div>
    </div>
@endsection
