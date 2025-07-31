@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="text-center">Crear Transacción de Producto</h3>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transaccionProducto.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="codigo_tipo_nota" class="form-label">Código Tipo Nota</label>
            <select name="codigo_tipo_nota" id="codigo_tipo_nota" class="form-control" required>
                <option value="">Seleccione un código de nota</option>
                @foreach ($tipoNotas as $nota)
                    <option value="{{ $nota->codigo }}">{{ $nota->codigo }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="codigo_producto" class="form-label">Código Producto</label>
            <select name="codigo_producto" id="codigo_producto" class="form-control" required>
                <option value="">Seleccione un producto</option>
                @foreach ($productos as $producto)
                    <option value="{{ $producto->codigo }}">{{ $producto->codigo }} - {{ $producto->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tipo_empaque" class="form-label">Tipo de Empaque</label>
            <select name="tipo_empaque" id="tipo_empaque" class="form-control" required>
                <option value="">Seleccione un tipo de empaque</option>
                @foreach ($tipoEmpaques as $tipoEmpaque)
                    <option value="{{ $tipoEmpaque->codigotipoempaque }}">{{ $tipoEmpaque->nombretipoempaque }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="bodega_destino" class="form-label">Bodega Destino</label>
            <select name="bodega_destino" id="bodega_destino" class="form-control" required>
                <option value="">Seleccione una bodega</option>
                @foreach ($bodegas as $bodega)
                    <option value="{{ $bodega->idbodega }}">{{ $bodega->nombrebodega }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="responsable" class="form-label">Responsable</label>
            <select name="responsable" id="responsable" class="form-control" required>
                <option value="">Seleccione un responsable</option>
                @foreach ($empleados as $empleado)
                    <option value="{{ $empleado->idempleado }}">{{ $empleado->nombreemp }} {{ $empleado->apellidoemp }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
            <input type="text" name="fecha_entrega" id="fecha_entrega" class="form-control" value="{{ now() }}" readonly>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('transaccionProducto.index') }}" class="btn btn-secondary">Atrás</a>
            <button type="submit" class="btn btn-success">Guardar</button>
        </div>
    </form>
</div>
@endsection
