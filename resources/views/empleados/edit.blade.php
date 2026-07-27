@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Editar Empleado</h3>
            <p class="page-subtitle">Modificación de datos del personal</p>
        </div>
        <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
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
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><strong>Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4" style="max-width: 900px; margin: 0 auto; border-top: 3px solid var(--primary);">
        <div class="card-body p-4">
            <!-- Header con info resumida -->
            <div class="d-flex align-items-center mb-4 p-3 rounded" style="background: var(--accent-subtle);">
                <div style="width: 48px; height: 48px; border-radius: var(--radius-sm); background: var(--primary); display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                    <i class="fas fa-user" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color: var(--foreground);">{{ $empleado->nombreemp }} {{ $empleado->apellidoemp }}</h5>
                    <div class="font-mono mt-1" style="font-size: 13px; color: var(--muted);">ID: {{ $empleado->nro_identificacion }}</div>
                </div>
            </div>

            <form action="{{ route('empleados.update', $empleado->nro_identificacion) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="nombreemp" class="form-label">Nombre</label>
                        <input type="text" name="nombreemp" id="nombreemp" class="form-control" required value="{{ old('nombreemp', $empleado->nombreemp) }}" placeholder="Ingrese el nombre">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="apellidoemp" class="form-label">Apellido</label>
                        <input type="text" name="apellidoemp" id="apellidoemp" class="form-control" required value="{{ old('apellidoemp', $empleado->apellidoemp) }}" placeholder="Ingrese el apellido">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="tipo_identificacion" class="form-label">Tipo de Identificación</label>
                        <select name="tipo_identificacion" id="tipo_identificacion" class="form-select" required>
                            <option value="">Seleccione tipo</option>
                            <option value="Cedula" {{ old('tipo_identificacion', $empleado->tipo_identificacion) == 'Cedula' ? 'selected' : '' }}>Cédula</option>
                            <option value="RUC" {{ old('tipo_identificacion', $empleado->tipo_identificacion) == 'RUC' ? 'selected' : '' }}>RUC</option>
                            <option value="Pasaporte" {{ old('tipo_identificacion', $empleado->tipo_identificacion) == 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="nro_identificacion" class="form-label">Número de Identificación</label>
                        <input type="text" name="nro_identificacion" id="nro_identificacion" class="form-control font-mono" required value="{{ old('nro_identificacion', $empleado->nro_identificacion) }}" placeholder="Número de identificación">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                            <input type="email" name="email" id="email" class="form-control" required value="{{ old('email', $empleado->email) }}" placeholder="ejemplo@correo.com">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="nro_telefono" class="form-label">Celular</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                            <input type="text" name="nro_telefono" id="nro_telefono" class="form-control font-mono" required value="{{ old('nro_telefono', $empleado->nro_telefono) }}" placeholder="Número de celular">
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <label for="direccionemp" class="form-label">Dirección</label>
                        <input type="text" name="direccionemp" id="direccionemp" class="form-control" required value="{{ old('direccionemp', $empleado->direccionemp) }}" placeholder="Dirección completa">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="codigocargo" class="form-label">Cargo</label>
                        <select name="codigocargo" class="form-select" required>
                            <option value="">Seleccione un cargo</option>
                            @foreach($cargos as $codigo => $nombre)
                                <option value="{{ $codigo }}" {{ old('codigocargo', $empleado->codigocargo ?? '') == $codigo ? 'selected' : '' }}>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="idbodega" class="form-label">Bodega Asignada</label>
                        <select name="idbodega" id="idbodega" class="form-select" required>
                            <option value="">Seleccione una bodega</option>
                            @foreach ($bodegas as $bodega)
                                <option value="{{ $bodega->idbodega }}" {{ old('idbodega', $empleado->idbodega) == $bodega->idbodega ? 'selected' : '' }}>
                                    {{ $bodega->nombrebodega }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-light);">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tipoSelect = document.getElementById('tipo_identificacion');
        const idInput = document.getElementById('nro_identificacion');
        
        function updateIdValidation() {
            if (!tipoSelect || !idInput) return;
            const val = tipoSelect.value;
            if (val === 'Cedula') {
                idInput.maxLength = 10;
                idInput.placeholder = '10 dígitos numéricos exactos';
            } else if (val === 'RUC') {
                idInput.maxLength = 13;
                idInput.placeholder = '13 dígitos numéricos (ej: ...001)';
            } else {
                idInput.removeAttribute('maxLength');
                idInput.placeholder = 'Ingrese número de identificación';
            }
        }
        
        if (tipoSelect && idInput) {
            tipoSelect.addEventListener('change', updateIdValidation);
            updateIdValidation();
        }
    });
</script>
@endsection