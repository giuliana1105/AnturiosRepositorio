@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 m-0">
    <div class="row g-0 min-vh-100">
        <!-- Sidebar Navigation -->
        <div class="col-md-2 bg-light py-3 px-3">
            <div class="text-center mb-4">
                <div class="bg-info rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-user text-white fa-lg"></i>
                </div>
                <div class="mt-2">
                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                    <div class="text-muted small">{{ auth()->user()->email }}</div>
                    <div class="text-secondary small">{{ auth()->user()->cargoNombre() }}</div>
                </div>
            </div>
            <div class="mb-3">
                <small class="text-uppercase text-muted fw-bold">NAVEGACIÓN PRINCIPAL</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link text-dark mb-2" href="{{ route('tipoNota.index') }}">
                    <i class="fas fa-file-alt me-2"></i> Notas de Pedido
                </a>
                <a class="nav-link text-dark mb-2" href="{{ route('productos.index') }}">
                    <i class="fas fa-cube me-2"></i> Productos
                </a>
                <a class="nav-link active text-info fw-bold mb-2" href="{{ route('empleados.index') }}">
                    <i class="fas fa-users me-2"></i> Empleados
                </a>
                <a class="nav-link text-dark mb-2" href="{{ route('transaccionProducto.index') }}">
                    <i class="fas fa-exchange-alt me-2"></i> Transacción Producto
                </a>
                <a class="nav-link text-dark mb-2" href="{{ route('home') }}">
                    <i class="fas fa-home me-2"></i> Home
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link text-dark btn btn-link mb-2" style="text-align:left;">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar sesión
                    </button>
                </form>
                <!-- Agrega más enlaces según tu menú -->
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 py-3 px-4 bg-white">
            <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width: 1000px;">
                <div class="card-header bg-info text-white rounded-top-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i> Editar Empleado
                        </h3>
                        <a href="{{ route('empleados.index') }}" class="btn btn-light btn-sm rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Mostrar los errores de validación -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Errores de validación:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Mostrar el error específico que venga desde el controlador -->
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Error:</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Información del empleado -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="bg-light rounded-4 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">{{ $empleado->nombreemp }} {{ $empleado->apellidoemp }}</h5>
                                        <p class="text-muted mb-0">ID: {{ $empleado->nro_identificacion }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('empleados.update', $empleado->nro_identificacion) }}" method="POST" class="row g-3">
                        @csrf
                        @method('PATCH')

                        <div class="col-md-6">
                            <label for="nombreemp" class="form-label fw-bold">
                                <i class="fas fa-user me-2 text-info"></i> Nombre
                            </label>
                            <input type="text" name="nombreemp" id="nombreemp" 
                                   class="form-control rounded-pill" 
                                   value="{{ old('nombreemp', $empleado->nombreemp) }}" 
                                   required placeholder="Ingrese el nombre">
                        </div>

                        <div class="col-md-6">
                            <label for="apellidoemp" class="form-label fw-bold">
                                <i class="fas fa-user me-2 text-info"></i> Apellido
                            </label>
                            <input type="text" name="apellidoemp" id="apellidoemp" 
                                   class="form-control rounded-pill" 
                                   value="{{ old('apellidoemp', $empleado->apellidoemp) }}" 
                                   required placeholder="Ingrese el apellido">
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold">
                                <i class="fas fa-envelope me-2 text-info"></i> Email
                            </label>
                            <input type="email" name="email" id="email" 
                                   class="form-control rounded-pill" 
                                   value="{{ old('email', $empleado->email) }}" 
                                   required placeholder="ejemplo@correo.com">
                        </div>

                        <div class="col-md-6">
                            <label for="tipo_identificacion" class="form-label fw-bold">
                                <i class="fas fa-id-badge me-2 text-info"></i> Tipo de Identificación
                            </label>
                            <select name="tipo_identificacion" id="tipo_identificacion" class="form-control rounded-pill" required>
                                <option value="">Seleccione tipo</option>
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
                            <label for="nro_identificacion" class="form-label fw-bold">
                                <i class="fas fa-id-card me-2 text-info"></i> Nro. Identificación
                            </label>
                            <input type="text" name="nro_identificacion" id="nro_identificacion" 
                                   class="form-control rounded-pill" 
                                   value="{{ old('nro_identificacion', $empleado->nro_identificacion) }}" 
                                   required placeholder="Número de identificación">
                        </div>            

                        <div class="col-md-6">
                            <label for="idbodega" class="form-label fw-bold">
                                <i class="fas fa-warehouse me-2 text-info"></i> Bodega
                            </label>
                            <select name="idbodega" id="idbodega" class="form-control rounded-pill" required>
                                <option value="">Seleccione una bodega</option>
                                @foreach ($bodegas as $bodega)
                                    <option value="{{ $bodega->idbodega }}"
                                        {{ old('idbodega', $empleado->idbodega) == $bodega->idbodega ? 'selected' : '' }}>
                                        {{ $bodega->nombrebodega }}
                                    </option>
                                @endforeach
                            </select>
                        </div>            

                        <div class="col-md-6">
                            <label for="codigocargo" class="form-label fw-bold">
                                <i class="fas fa-briefcase me-2 text-info"></i> Cargo
                            </label>
                            <select name="codigocargo" class="form-control rounded-pill" required>
                                <option value="">Seleccione un cargo</option>
                                @foreach($cargos as $codigo => $nombre)
                                    <option value="{{ $codigo }}" {{ old('codigocargo', $empleado->codigocargo ?? '') == $codigo ? 'selected' : '' }}>
                                        {{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="nro_telefono" class="form-label fw-bold">
                                <i class="fas fa-phone me-2 text-info"></i> Teléfono
                            </label>
                            <input type="text" name="nro_telefono" id="nro_telefono" 
                                   class="form-control rounded-pill" 
                                   value="{{ old('nro_telefono', $empleado->nro_telefono) }}" 
                                   required placeholder="Número de teléfono">
                        </div>

                        <div class="col-md-6">
                            <label for="direccionemp" class="form-label fw-bold">
                                <i class="fas fa-map-marker-alt me-2 text-info"></i> Dirección
                            </label>
                            <input type="text" name="direccionemp" id="direccionemp" 
                                   class="form-control rounded-pill" 
                                   value="{{ old('direccionemp', $empleado->direccionemp) }}" 
                                   required placeholder="Dirección completa">
                        </div>

                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-success fw-bold rounded-pill px-5 py-3">
                                <i class="fas fa-save me-2"></i> Actualizar Empleado
                            </button>
                            <a href="{{ route('empleados.index') }}" class="btn btn-secondary rounded-pill px-5 py-3 ms-3">
                                <i class="fas fa-times me-2"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
body {
    margin: 0;
    padding: 0;
}
.container-fluid {
    padding: 0 !important;
    margin: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}
.card {
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 0;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}
.nav-link {
    padding: 0.5rem 0;
    border-radius: 4px;
    transition: all 0.2s ease;
}
.nav-link:hover {
    background-color: rgba(0, 123, 255, 0.1);
    padding-left: 0.5rem;
}
.nav-link.active {
    background-color: rgba(23, 162, 184, 0.1);
    border-left: 3px solid #17a2b8;
    padding-left: 0.5rem;
}
.min-vh-100 {
    min-height: 100vh;
}
.card-body {
    position: relative;
    overflow: hidden;
}
.card-body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    pointer-events: none;
}
.h3 {
    font-size: 2.5rem;
}
.row.g-0 {
    margin: 0;
}
.col-md-2, .col-md-10 {
    padding-left: 0;
    padding-right: 0;
}
.col-md-2.bg-light {
    margin: 0;
    border-radius: 0;
}
.btn-info {
    background-color: #0097a7;
    border-color: #0097a7;
}
.btn-info:hover, .btn-info:focus {
    background-color: #00796b;
    border-color: #00796b;
}
.btn-success {
    background-color: #4caf50;
    border-color: #4caf50;
}
.btn-success:hover, .btn-success:focus {
    background-color: #388e3c;
    border-color: #388e3c;
}
.btn-secondary {
    background-color: #607d8b;
    border-color: #607d8b;
    color: #fff;
}
.btn-secondary:hover, .btn-secondary:focus {
    background-color: #455a64;
    border-color: #455a64;
    color: #fff;
}
.btn-light {
    background-color: #f8f9fa;
    border-color: #f8f9fa;
    color: #495057;
}
.btn-light:hover, .btn-light:focus {
    background-color: #e2e6ea;
    border-color: #dae0e5;
    color: #495057;
}
.rounded-pill {
    border-radius: 50rem !important;
}
.form-control {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}
.form-control:focus {
    border-color: #0097a7;
    box-shadow: 0 0 0 0.2rem rgba(0, 151, 167, 0.25);
}
.form-label {
    color: #495057;
    margin-bottom: 0.5rem;
}
.alert {
    border: none;
    border-radius: 0.75rem;
}
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}
.bg-light {
    background-color: #f8f9fa !important;
}
@media (max-width: 768px) {
    .col-md-2 {
        display: none;
    }
    .col-md-10 {
        flex: 0 0 100%;
        max-width: 100%;
        padding: 15px !important;
    }
    .h3 {
        font-size: 1.75rem;
    }
    .container-fluid {
        padding: 0 !important;
    }
    .card-header .d-flex {
        flex-direction: column;
        text-align: center;
    }
    .card-header .btn {
        margin-top: 1rem;
    }
}
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}
#app {
    min-height: 100vh;
}
</style>
@endsection