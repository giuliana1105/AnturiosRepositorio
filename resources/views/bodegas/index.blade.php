@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-center mb-4">Listado de Bodegas</h2>

        <!-- Mensajes de éxito y error -->
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

        <!-- Mostrar errores de validación -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario de creación de bodega -->
        <div class="card mb-4">
            <div class="card-header  text-white" style="background-color: #88022D">
                <h3 class="card-title">Nueva Bodega</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('bodegas.store') }}" role="form">
                    @csrf
                    <div class="row">
                        <!-- Campo Nombre de la Bodega -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="nombrebodega" class="form-label">Nombre de la Bodega</label>
                            <input type="text" name="nombrebodega" id="nombrebodega"
                                class="form-control" placeholder="Nombre de la Bodega"
                                value="{{ old('nombrebodega') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <button type="submit" class="btn btn-success w-100">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Formulario de búsqueda -->
        <form action="{{ route('bodegas.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-12 col-md-4 mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre de bodega"
                        value="{{ request()->search }}">
                </div>
                <div class="col-12 col-md-2 mb-3">
                    <button type="submit" class="btn btn-primary w-100" style="background-color: #88022D">Buscar</button>
                </div>
            </div>
        </form>

        <!-- Tabla de bodegas -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nombre de la Bodega</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bodegas as $bodega)
                        <tr>
                            <td>{{ $bodega->nombrebodega }}</td>
                            <td>
                                <!-- Botón Editar -->
                                <a href="{{ route('bodegas.edit', $bodega->idbodega) }}" class="btn btn-sm btn-primary mb-2 mb-md-0">Editar</a>
                                <!-- Botón Eliminar -->
                                <form action="{{ route('bodegas.destroy', $bodega->idbodega) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Estás seguro de eliminar esta bodega?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No hay bodegas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-3 d-flex justify-content-center">
            {{ $bodegas->links() }}
        </div>
    </div>
@endsection