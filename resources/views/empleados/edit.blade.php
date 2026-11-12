@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Editar Empleado</h3>
            <p class="page-subtitle mb-0 text-muted">Actualización de datos laborales y personales del empleado</p>
        </div>
        <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver a Empleados
        </a>
    </div>

    @if (isset($errors) && $errors->any())
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
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><strong>Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4" style="max-width: 900px; margin: 0 auto; border-top: 3px solid var(--primary) !important;">
        <div class="card-body p-4 p-md-5">
            <!-- Header con info resumida -->
            <div class="d-flex align-items-center mb-4 p-3 rounded-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0;">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-xs" style="width: 52px; height: 52px; background: rgba(0, 151, 167, 0.15); color: var(--primary);">
                    <span class="fw-bold fs-4">{{ strtoupper(substr($empleado->nombreemp, 0, 1)) }}</span>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">{{ $empleado->nombreemp }} {{ $empleado->apellidoemp }}</h5>
                    <div class="mt-1 d-flex align-items-center gap-2" style="font-size: 13px; color: var(--muted);">
                        <span class="badge rounded-pill bg-light text-secondary border px-2 py-0.5" style="font-size: 11px;">
                            {{ $empleado->tipo_identificacion === 'Cedula' ? 'Cédula' : $empleado->tipo_identificacion }}
                        </span>
                        <span class="font-mono fw-bold text-dark">{{ $empleado->nro_identificacion }}</span>
                        @if($empleado->bodega)
                            <span class="ms-2 text-muted">
                                <i class="fas fa-warehouse me-1 text-primary"></i>{{ $empleado->bodega->nombrebodega }}
                            </span>
                        @endif
                    </div>
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
                    <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
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