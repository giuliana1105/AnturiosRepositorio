@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Editar Transacción #{{ $transaccion->id }}</h3>
            <p class="page-subtitle mb-0 text-muted">Nota asociada: <strong>#{{ $transaccion->codigo_tipo_nota }}</strong> &bull; Modificación de asignación de productos</p>
        </div>
        <a href="{{ route('transaccionProducto.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver a Transacciones
        </a>
    </div>
    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-center mb-1">
                <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                <strong class="fs-6">Se encontraron errores en el formulario:</strong>
            </div>
            <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4" style="max-width: 900px; margin: 0 auto; border-top: 3px solid var(--primary) !important;">
        <div class="card-body p-4 p-md-5">
            <!-- Header con info resumida -->
            <div class="d-flex align-items-center mb-4 p-3 rounded-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0;">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-xs" style="width: 52px; height: 52px; background: rgba(0, 151, 167, 0.15); color: var(--primary);">
                    <i class="fas fa-exchange-alt fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">Transacción #{{ $transaccion->id }}</h5>
                    <div class="font-mono mt-1 text-muted" style="font-size: 13px;">Nota Relacionada: #{{ $transaccion->codigo_tipo_nota }}</div>
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

                <hr class="my-4" style="border-color: #e2e8f0;">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('transaccionProducto.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
