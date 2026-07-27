@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Editar Transacción</h3>
            <p class="page-subtitle">Modificación de movimientos de productos</p>
        </div>
        <a href="{{ route('transaccionProducto.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><strong>Errores de validación:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4" style="max-width: 900px; margin: 0 auto; border-top: 3px solid var(--primary);">
        <div class="card-body p-4">
            <!-- Header con info resumida -->
            <div class="d-flex align-items-center mb-4 p-3 rounded" style="background: var(--accent-subtle);">
                <div style="width: 48px; height: 48px; border-radius: var(--radius-sm); background: var(--primary); display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                    <i class="fas fa-exchange-alt" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color: var(--foreground);">Transacción #{{ $transaccion->id }}</h5>
                    <div class="font-mono mt-1" style="font-size: 13px; color: var(--muted);">Nota: {{ $transaccion->codigo_tipo_nota }}</div>
                </div>
            </div>

            <form action="{{ route('transaccionProducto.update', $transaccion->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="codigo_tipo_nota" class="form-label">Código de Nota</label>
                        <select name="codigo_tipo_nota" id="codigo_tipo_nota" class="form-select" required>
                            @foreach ($tipoNotas as $nota)
                                <option value="{{ $nota->codigo }}" {{ old('codigo_tipo_nota', $transaccion->codigo_tipo_nota) == $nota->codigo ? 'selected' : '' }}>
                                    {{ $nota->codigo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="codigo_producto" class="form-label">Producto</label>
                        <select name="codigo_producto" id="codigo_producto" class="form-select" required>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->codigo }}" {{ old('codigo_producto', $transaccion->codigo_producto) == $producto->codigo ? 'selected' : '' }}>
                                    {{ $producto->codigo }} - {{ $producto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="tipo_empaque" class="form-label">Tipo de Empaque</label>
                        <select name="tipo_empaque" id="tipo_empaque" class="form-select" required>
                            @foreach ($tipoEmpaques as $tipoEmpaque)
                                <option value="{{ $tipoEmpaque->codigotipoempaque }}" {{ old('tipo_empaque', $transaccion->tipo_empaque) == $tipoEmpaque->codigotipoempaque ? 'selected' : '' }}>
                                    {{ $tipoEmpaque->nombretipoempaque }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" value="{{ old('cantidad', $transaccion->cantidad) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="bodega_destino" class="form-label">Bodega de Destino</label>
                        <select name="bodega_destino" id="bodega_destino" class="form-select" required>
                            @foreach ($bodegas as $bodega)
                                <option value="{{ $bodega->idbodega }}" {{ old('bodega_destino', $transaccion->bodega_destino) == $bodega->idbodega ? 'selected' : '' }}>
                                    {{ $bodega->nombrebodega }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="responsable" class="form-label">Responsable</label>
                        <select name="responsable" id="responsable" class="form-select" required>
                            @foreach ($empleados as $empleado)
                                <option value="{{ $empleado->idempleado }}" {{ old('responsable', $transaccion->responsable) == $empleado->idempleado ? 'selected' : '' }}>
                                    {{ $empleado->nombreemp }} {{ $empleado->apellidoemp }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
                        <input type="text" name="fecha_entrega" id="fecha_entrega" class="form-control bg-light" value="{{ $transaccion->fecha_entrega }}" readonly>
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-light);">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('transaccionProducto.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
