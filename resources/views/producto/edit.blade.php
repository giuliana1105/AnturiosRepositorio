@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="text-center mb-4">Editar Producto</h3>

        <!-- Mostrar mensaje del trigger si se activa -->
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('producto.update', $producto->codigo) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Campo Código -->
                <div class="col-12 col-md-6 mb-3">
                    <label for="codigo" class="form-label">Código</label>
                    <input type="text" name="codigo" class="form-control"
                           value="{{ $producto->codigo }}" required>
                </div>

                <!-- Campo Nombre -->
                <div class="col-12 col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ $producto->nombre }}" required>
                </div>
            </div>

            <div class="row">
                <!-- Campo Descripción -->
                <div class="col-12 mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" required>{{ $producto->descripcion }}</textarea>
                </div>
            </div>

            <div class="row">
                <!-- Campo Cantidad -->
                <div class="col-12 col-md-6 mb-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" class="form-control"
                           value="{{ $producto->cantidad }}" required min="1">
                </div>

                <!-- Campo Tipo de Empaque -->
                <div class="col-12 col-md-6 mb-3">
                    <label for="tipoempaque" class="form-label">Tipo de Empaque</label>
                    <select name="tipoempaque" class="form-control">
                        <option value="">Seleccione un tipo de empaque</option>
                        @foreach ($tipoempaques as $tipo)
                            <option value="{{ $tipo }}" {{ $producto->tipoempaque == $tipo ? 'selected' : '' }}>
                                {{ $tipo }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Botón Actualizar -->
            <div class="row">
                <div class="col-12 mb-3">
                    <button type="submit" class="btn btn-primary w-100">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
@endsection
