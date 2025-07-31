@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-center my-4">Editar Empleado</h2>

        <!-- Mostrar los errores de validación -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Mostrar el error específico que venga desde el controlador -->
        @if (session('error'))
            <div class="alert alert-danger">
                <ul>
                    <li>{{ session('error') }}</li>
                </ul>
            </div>
        @endif

        <form action="{{ route('empleado.update', $empleado->nro_identificacion) }}" method="POST" class="row g-3">
            @csrf
            @method('PATCH')

            <div class="col-md-6">
                <label for="nombreemp" class="form-label">Nombre</label>
                <input type="text" name="nombreemp" id="nombreemp" class="form-control"
                    value="{{ old('nombreemp', $empleado->nombreemp) }}" required>
            </div>

            <div class="col-md-6">
                <label for="apellidoemp" class="form-label">Apellido</label>
                <input type="text" name="apellidoemp" id="apellidoemp" class="form-control"
                    value="{{ old('apellidoemp', $empleado->apellidoemp) }}" required>
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control"
                    value="{{ old('email', $empleado->email) }}" required>
            </div>

            <div class="col-md-6">
                <label for="tipo_identificacion" class="form-label">Tipo de Identificación</label>
                <select name="tipo_identificacion" id="tipo_identificacion" class="form-select" required>
                    <option value="Cedula"
                        {{ old('tipo_identificacion', $empleado->tipo_identificacion) == 'Cedula' ? 'selected' : '' }}>
                        Cédula</option>
                    <option value="RUC"
                        {{ old('tipo_identificacion', $empleado->tipo_identificacion) == 'RUC' ? 'selected' : '' }}>RUC
                    </option>
                    <option value="Pasaporte"
                        {{ old('tipo_identificacion', $empleado->tipo_identificacion) == 'Pasaporte' ? 'selected' : '' }}>
                        Pasaporte</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="nro_identificacion" class="form-label">Nro. Identificación</label>
                <input type="text" name="nro_identificacion" id="nro_identificacion" class="form-control"
                    value="{{ old('nro_identificacion', $empleado->nro_identificacion) }}" required>
            </div>            

            <div class="col-md-6">
                <label for="idbodega" class="form-label">Bodega</label>
                <select name="idbodega" id="idbodega" class="form-select" required>
                    <option value="">Seleccione una opción</option>
                    @foreach ($bodegas as $bodega)
                        <option value="{{ $bodega->idbodega }}"
                            {{ old('idbodega', $empleado->idbodega) == $bodega->idbodega ? 'selected' : '' }}>
                            {{ $bodega->nombrebodega }}
                        </option>
                    @endforeach
                </select>
            </div>            

            <div class="col-md-6">
                <label for="codigocargo" class="form-label">Cargo</label>
                <select name="codigocargo" id="codigocargo" class="form-select" required>
                    <option value="">Seleccione una opción</option>
                    @foreach ($cargos as $cargo)
                        <option value="{{ $cargo->codigocargo }}"
                            {{ old('codigocargo', $empleado->codigocargo) == $cargo->codigocargo ? 'selected' : '' }}>
                            {{ $cargo->nombrecargo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="nro_telefono" class="form-label">Teléfono</label>
                <input type="text" name="nro_telefono" id="nro_telefono" class="form-control"
                    value="{{ old('nro_telefono', $empleado->nro_telefono) }}" required>
            </div>

            <div class="col-md-6">
                <label for="direccionemp" class="form-label">Dirección</label>
                <input type="text" name="direccionemp" id="direccionemp" class="form-control"
                    value="{{ old('direccionemp', $empleado->direccionemp) }}" required>
            </div>

            <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">Actualizar</button>
                <a href="{{ route('empleado.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
