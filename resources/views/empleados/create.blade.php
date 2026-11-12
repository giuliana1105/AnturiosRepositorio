@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Crear Empleado</h3>
            <p class="page-subtitle mb-0 text-muted">Registro de nuevo personal en el sistema y alta de usuario</p>
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
            <i class="fas fa-exclamation-circle me-2"></i><strong>Error:</strong> {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4" style="max-width: 900px; margin: 0 auto; border-top: 3px solid var(--primary) !important;">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                    <i class="fas fa-user-plus fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Ficha del Nuevo Empleado</h5>
                    <small class="text-muted">Al guardar, se creará automáticamente un usuario de acceso al sistema con la cédula como contraseña.</small>
                </div>
            </div>

            <form action="{{ route('empleados.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="nombreemp" class="form-label">Nombre</label>
                        <input type="text" name="nombreemp" id="nombreemp" class="form-control" required value="{{ old('nombreemp') }}" placeholder="Ingrese el nombre">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="apellidoemp" class="form-label">Apellido</label>
                        <input type="text" name="apellidoemp" id="apellidoemp" class="form-control" required value="{{ old('apellidoemp') }}" placeholder="Ingrese el apellido">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="tipo_identificacion" class="form-label">Tipo de Identificación</label>
                        <select name="tipo_identificacion" id="tipo_identificacion" class="form-select" required>
                            <option value="">Seleccione tipo</option>
                            <option value="Cedula" {{ old('tipo_identificacion') == 'Cedula' ? 'selected' : '' }}>Cédula</option>
                            <option value="RUC" {{ old('tipo_identificacion') == 'RUC' ? 'selected' : '' }}>RUC</option>
                            <option value="Pasaporte" {{ old('tipo_identificacion') == 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="nro_identificacion" class="form-label">Número de Identificación</label>
                        <input type="text" name="nro_identificacion" id="nro_identificacion" class="form-control" required value="{{ old('nro_identificacion') }}" placeholder="Ingrese número">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                            <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}" placeholder="ejemplo@correo.com">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="nro_telefono" class="form-label">Celular</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                            <input type="text" name="nro_telefono" id="nro_telefono" class="form-control" required value="{{ old('nro_telefono') }}" placeholder="Número de celular">
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <label for="direccionemp" class="form-label">Dirección</label>
                        <input type="text" name="direccionemp" id="direccionemp" class="form-control" required value="{{ old('direccionemp') }}" placeholder="Dirección completa">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="codigocargo" class="form-label">Cargo</label>
                        <select name="codigocargo" class="form-select" required>
                            <option value="">Seleccione un cargo</option>
                            @foreach($cargos as $codigo => $nombre)
                                <option value="{{ $codigo }}" {{ old('codigocargo') == $codigo ? 'selected' : '' }}>
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
                                <option value="{{ $bodega->idbodega }}" {{ old('idbodega') == $bodega->idbodega ? 'selected' : '' }}>
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
                        <i class="fas fa-save me-1"></i> Guardar Empleado
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