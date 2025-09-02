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
                <a class="nav-link text-dark mb-2" href="{{ route('empleados.index') }}">
                    <i class="fas fa-users me-2"></i> Empleados
                </a>
                <a class="nav-link active text-info fw-bold mb-2" href="#">
                    <i class="fas fa-warehouse me-2"></i> Bodegas
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
            <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width: 1200px;">
                <div class="card-header bg-info text-white rounded-top-4 text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-warehouse me-2"></i> Gestión de Bodegas
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Mensajes de éxito y error -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong><i class="fas fa-check me-2"></i>¡Éxito!</strong>
                                    {{ session('success') }}
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger rounded-circle p-2 me-3">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong><i class="fas fa-times me-2"></i>Error</strong>
                                    {{ session('error') }}
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Mostrar errores de validación -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger rounded-circle p-2 me-3">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong><i class="fas fa-times me-2"></i>Errores de validación</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Formulario de creación de bodega -->
                    <div class="card mb-4 border-0 bg-info bg-opacity-10">
                        <div class="card-header bg-info text-white rounded-top-4">
                            <h5 class="mb-0">
                                <i class="fas fa-plus me-2"></i> Nueva Bodega
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('bodegas.store') }}" role="form">
                                @csrf
                                <div class="row">
                                    <!-- Campo Nombre de la Bodega -->
                                    <div class="col-12 col-md-8 mb-3">
                                        <label for="nombrebodega" class="form-label fw-bold">Nombre de la Bodega</label>
                                        <input type="text" name="nombrebodega" id="nombrebodega"
                                            class="form-control rounded-pill" placeholder="Ingrese el nombre de la bodega"
                                            value="{{ old('nombrebodega') }}" required>
                                    </div>
                                    <div class="col-12 col-md-4 mb-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-success fw-bold rounded-pill w-100">
                                            <i class="fas fa-save me-2"></i> Guardar Bodega
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Formulario de búsqueda -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <!-- <form action="{{ route('bodegas.index') }}" method="GET" class="row g-2 align-items-end">
                                <div class="col-md-8">
                                    <label for="search" class="form-label fw-bold">Buscar Bodega</label>
                                    <input type="text" name="search" id="search" class="form-control rounded-pill"
                                           value="{{ request('search') }}" placeholder="Buscar por nombre de bodega">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-info fw-bold rounded-pill px-4 me-2">
                                        <i class="fas fa-search me-2"></i> Buscar
                                    </button>
                                    <a href="{{ route('bodegas.index') }}" class="btn btn-secondary rounded-pill px-4">
                                        <i class="fas fa-times me-2"></i> Limpiar
                                    </a>
                                </div>
                            </form> -->
                        </div>
                    </div>

                    <!-- Tabla de bodegas -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">
                                        <i class="fas fa-warehouse me-2"></i>Nombre de la Bodega
                                    </th>
                                    <th class="text-center">
                                        <i class="fas fa-cogs me-2"></i>Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bodegas as $bodega)
                                    <tr>
                                        <td class="fw-bold">{{ $bodega->nombrebodega }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <!-- Botón Editar -->
                                                <a href="{{ route('bodegas.edit', $bodega->idbodega) }}" 
                                                   class="btn btn-warning btn-sm rounded-pill me-1">
                                                    <i class="fas fa-edit me-1"></i> Editar
                                                </a>
                                                <!-- Botón Eliminar -->
                                                <form action="{{ route('bodegas.destroy', $bodega->idbodega) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill" 
                                                            onclick="return confirm('¿Está seguro de eliminar esta bodega?')">
                                                        <i class="fas fa-trash me-1"></i> Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-warehouse fa-2x mb-2"></i>
                                                <p class="mb-0">No hay bodegas registradas</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="mt-3 d-flex justify-content-center">
                        {{ $bodegas->appends(['search' => request('search')])->onEachSide(1)->links('pagination::bootstrap-4') }}
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
.row.g-3 {
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
.btn-warning {
    background-color: #ff9800;
    border-color: #ff9800;
    color: #fff;
}
.btn-warning:hover, .btn-warning:focus {
    background-color: #f57c00;
    border-color: #f57c00;
    color: #fff;
}
.btn-danger {
    background-color: #e53935;
    border-color: #e53935;
    color: #fff;
}
.btn-danger:hover, .btn-danger:focus {
    background-color: #b71c1c;
    border-color: #b71c1c;
    color: #fff;
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
.btn-outline-info {
    color: #0097a7;
    border-color: #0097a7;
}
.btn-outline-info:hover, .btn-outline-info:focus {
    background-color: #0097a7;
    border-color: #0097a7;
    color: #fff;
}
.rounded-pill {
    border-radius: 50rem !important;
}
.rounded-4 {
    border-radius: 1rem !important;
}
.rounded-top-4 {
    border-top-left-radius: 1rem !important;
    border-top-right-radius: 1rem !important;
}
.alert {
    border: none;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
    border-left: 4px solid #4caf50;
}
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border-left: 4px solid #e53935;
}
.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
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
        font-size: 2rem;
    }
    .container-fluid {
        padding: 0 !important;
    }
    .btn-group .btn {
        margin-bottom: 0.25rem;
    }
    .table-responsive {
        font-size: 0.875rem;
    }
    .col-md-8, .col-md-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    .btn-group .btn {
        border-radius: 50rem !important;
        margin-bottom: 0.25rem;
        margin-right: 0 !important;
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