@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

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
            
        </div>

        <!-- Main Content -->
        <div class="col-md-10 py-3 px-4 bg-white">
            <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width: 800px;">
                <div class="card-header bg-info text-white rounded-top-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">
                            <i class="fas fa-key me-2"></i> Cambiar Contraseña
                        </h3>
                        
                    </div>
                </div>
                <div class="card-body">
                    <!-- Alertas de errores -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Error:</strong> Por favor corrige los siguientes errores:
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Mensaje de éxito -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Éxito:</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Información de seguridad -->
                    <div class="text-center mb-4">
                        <i class="fas fa-shield-alt fa-3x text-info mb-3"></i>
                        <h4 class="text-muted">Actualiza tu contraseña</h4>
                        <p class="text-muted">Por tu seguridad, asegúrate de usar una contraseña fuerte</p>
                    </div>

                    <form action="{{ route('password.change') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label for="password" class="form-label fw-bold">
                                <i class="fas fa-lock me-2 text-info"></i> Nueva Contraseña
                            </label>
                            <input type="password" name="password" id="password" 
                                   class="form-control rounded-pill" required 
                                   placeholder="Ingresa tu nueva contraseña">
                            <div class="form-text mt-2">
                                <i class="fas fa-info-circle me-1"></i> 
                                La contraseña debe tener al menos 8 caracteres
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label for="password_confirmation" class="form-label fw-bold">
                                <i class="fas fa-lock me-2 text-info"></i> Confirmar Contraseña
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="form-control rounded-pill" required 
                                   placeholder="Confirma tu nueva contraseña">
                        </div>
                        
                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-info text-white fw-bold rounded-pill px-5 py-3">
                                <i class="fas fa-key me-2"></i> Cambiar Contraseña
                            </button>
                           
                        </div>
                    </form>

                    <!-- Consejos de seguridad -->
                    <div class="mt-4 p-3 bg-light rounded-4">
                        <h6 class="fw-bold text-info mb-2">
                            <i class="fas fa-lightbulb me-2"></i> Consejos para una contraseña segura:
                        </h6>
                        <ul class="small text-muted mb-0">
                            <li>Usa al menos 8 caracteres</li>
                            <li>Incluye mayúsculas, minúsculas y números</li>
                            <li>Agrega símbolos especiales (!@#$%)</li>
                            <li>No uses información personal</li>
                        </ul>
                    </div>
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
.alert-success {
    background-color: #d4edda;
    color: #155724;
}
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
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