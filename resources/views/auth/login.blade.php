@extends('layouts.app')

@section('title', 'Inicio de Sesión')

@php
    $hideNavbar = true; // Ocultar el menú de navegación
@endphp

@section('content')
<div class="container-fluid p-0 m-0 login-container">
    <div class="row g-0 min-vh-100">
        <!-- Columna izquierda - Información de bienvenida -->
        <div class="col-lg-6 bg-gradient-info d-flex align-items-center justify-content-center">
            <div class="text-center text-white p-5">
                                <div class="mb-4">
                    <img src="{{ asset('images/logo-empresa.png') }}" 
                         alt="Logo Importadora Los Anturios" 
                         class="img-fluid mb-3" 
                         style="max-height: 300px; max-width: 450px; opacity: 0.9;">
                </div>

                <h1 class="display-4 fw-bold mb-3">¡Bienvenido a Importadora Los Anturios!</h1>
                <p class="lead mb-4">Sistema de gestión empresarial</p>
                <div class="row text-center">
                    <!-- <div class="col-4">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <p class="small">Empleados</p>
                    </div>
                    <div class="col-4">
                        <i class="fas fa-cube fa-2x mb-2"></i>
                        <p class="small">Productos</p>
                    </div>
                    <div class="col-4">
                        <i class="fas fa-file-alt fa-2x mb-2"></i>
                        <p class="small">Pedidos</p>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- Columna derecha - Formulario de login -->
        <div class="col-lg-6 bg-white d-flex align-items-center justify-content-center">
            <div class="w-100 p-5" style="max-width: 500px;">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-info text-white rounded-top-4">
                        <div class="text-center py-3">
                            <h3 class="mb-0">
                                <i class="fas fa-sign-in-alt me-2"></i> Inicio de Sesión
                            </h3>
                        </div>
                    </div>
                    <div class="card-body p-5">
                        <!-- Alertas de errores -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Error:</strong> Por favor verifica tus credenciales:
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

                        <form action="{{ route('login.post') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label for="email" class="form-label fw-bold">
                                    <i class="fas fa-envelope me-2 text-info"></i> Correo Electrónico
                                </label>
                                <input type="email" name="email" id="email" 
                                       class="form-control rounded-pill" required 
                                       placeholder="Ingresa tu correo electrónico"
                                       value="{{ old('email') }}">
                            </div>
                            
                            <div class="col-12">
                                <label for="password" class="form-label fw-bold">
                                    <i class="fas fa-lock me-2 text-info"></i> Contraseña
                                </label>
                                <div class="position-relative">
                                    <input type="password" name="password" id="password" 
                                           class="form-control rounded-pill" required 
                                           placeholder="Ingresa tu contraseña">
                                    <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-3" 
                                            onclick="togglePassword()" id="toggleBtn">
                                        <i class="fas fa-eye text-muted" id="eyeIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check d-flex align-items-center">
                                    <input type="checkbox" class="form-check-input rounded" id="remember_me" name="remember">
                                    <label class="form-check-label ms-2" for="remember_me">
                                        <i class="fas fa-bookmark me-1 text-info"></i> Recordar sesión
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn btn-info text-white fw-bold rounded-pill px-5 py-3 w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                                </button>
                            </div>
                        </form>

                        <!-- Información adicional -->
                        <div class="mt-4 text-center">
                            <p class="text-muted small">
                                <i class="fas fa-shield-alt me-1"></i> 
                                Tus datos están protegidos con encriptación SSL
                            </p>
                        </div>
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
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container-fluid {
    padding: 0 !important;
    margin: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}

.login-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #0097a7 0%, #00796b 100%);
    position: relative;
    overflow: hidden;
}

.bg-gradient-info::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="25" cy="25" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="50" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="30" r="1" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    animation: float 20s linear infinite;
}

@keyframes float {
    0% { transform: translateY(0); }
    100% { transform: translateY(-100px); }
}

.card {
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    border: none;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.min-vh-100 {
    min-height: 100vh;
}

.row.g-0 {
    margin: 0;
}

.btn-info {
    background-color: #0097a7;
    border-color: #0097a7;
    transition: all 0.3s ease;
}

.btn-info:hover, .btn-info:focus {
    background-color: #00796b;
    border-color: #00796b;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 151, 167, 0.4);
}

.rounded-pill {
    border-radius: 50rem !important;
}

.form-control {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    padding: 0.75rem 1.25rem;
}

.form-control:focus {
    border-color: #0097a7;
    box-shadow: 0 0 0 0.2rem rgba(0, 151, 167, 0.25);
    transform: translateY(-1px);
}

.form-label {
    color: #495057;
    margin-bottom: 0.75rem;
}

.alert {
    border: none;
    border-radius: 0.75rem;
    backdrop-filter: blur(10px);
}

.alert-success {
    background-color: rgba(212, 237, 218, 0.95);
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-danger {
    background-color: rgba(248, 215, 218, 0.95);
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.form-check-input:checked {
    background-color: #0097a7;
    border-color: #0097a7;
}

.position-relative .btn-link {
    border: none;
    background: none;
    padding: 0;
    text-decoration: none;
}

.position-relative .btn-link:hover {
    background: none;
}

/* Animaciones adicionales */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive design */
@media (max-width: 992px) {
    .bg-gradient-info {
        display: none !important;
    }
    
    .col-lg-6:last-child {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .login-container {
        background: #f8f9fa;
    }
    
    .card {
        margin: 2rem 1rem;
    }
}

@media (max-width: 576px) {
    .card-body {
        padding: 2rem 1.5rem !important;
    }
    
    .display-4 {
        font-size: 2rem;
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

.text-white .opacity-75 {
    opacity: 0.75;
}
</style>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.className = 'fas fa-eye-slash text-muted';
    } else {
        passwordInput.type = 'password';
        eyeIcon.className = 'fas fa-eye text-muted';
    }
}

// Animación de entrada para los elementos
document.addEventListener('DOMContentLoaded', function() {
    const formElements = document.querySelectorAll('.form-control, .btn, .form-check');
    
    formElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'all 0.5s ease';
        
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 100 * index);
    });
});
</script>
@endsection