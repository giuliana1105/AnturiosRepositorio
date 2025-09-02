@extends('layouts.app')
@php
                        $cargo = auth()->user()->cargoNombre();
                    @endphp

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
                <a class="nav-link active text-info fw-bold mb-2" href="{{ route('productos.index') }}">
                    <i class="fas fa-cube me-2"></i> Productos
                </a>
                 @if(!in_array($cargo, ['Vendedor camión', 'Vendedor']))
                <a class="nav-link text-dark mb-2" href="{{ route('transaccionProducto.index') }}">
                    <i class="fas fa-exchange-alt me-2"></i> Transacción Producto
                </a>
                @endif
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
                    <h3 class="mb-0">Lista de Productos</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('productos.index') }}" method="GET" class="row g-2 mb-3 align-items-end">
                        <div class="col-md-4">
                            <label for="search_codigo" class="form-label fw-bold">Buscar por Código</label>
                            <input type="text" name="search_codigo" id="search_codigo" class="form-control"
                                   value="{{ request('search_codigo') }}" placeholder="Código del producto">
                        </div>
                        <div class="col-md-4">
                            <label for="search_nombre" class="form-label fw-bold">Buscar por Nombre</label>
                            <input type="text" name="search_nombre" id="search_nombre" class="form-control"
                                   value="{{ request('search_nombre') }}" placeholder="Nombre del producto">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-info fw-bold rounded-pill px-4">
                                <i class="fas fa-search me-2"></i> Buscar
                            </button>
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary rounded-pill ms-2 px-4">
                                <i class="fas fa-times me-2"></i> Limpiar
                            </a>
                        </div>
                    </form>
                    @php
                        $cargo = auth()->user()->cargoNombre();
                    @endphp

                    @if(in_array($cargo, ['Administrador', 'Gerente', 'Jefe de bodega']))
                        <a href="{{ route('productos.create') }}" class="btn btn-info text-white fw-bold rounded-pill mb-3">
                            <i class="fas fa-plus me-2"></i> Añadir Producto
                        </a>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                    <th>Tipo de Empaque</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productos as $producto)
                                    <tr>
                                        <td>{{ $producto->codigo }}</td>
                                        <td>{{ $producto->nombre }}</td>
                                        <td>{{ $producto->descripcion }}</td>
                                        <td>{{ $producto->cantidad }}</td>
                                        <td>{{ $producto->tipoempaque }}</td>
                                        <td>
                                            <!-- <a href="{{ route('productos.show', $producto->codigo) }}" class="btn btn-info btn-sm">Ver</a> -->
                                            @if(in_array($cargo, ['Administrador', 'Gerente', 'Jefe de bodega']))
                                                <a href="{{ route('productos.edit', $producto->codigo) }}" class="btn btn-warning btn-sm rounded-pill">
                                                    <i class="fas fa-edit me-1"></i> Editar
                                                </a>
                                                <form action="{{ route('productos.destroy', $producto->codigo) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('¿Seguro de eliminar?')">
                                                        <i class="fas fa-trash me-1"></i> Eliminar
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Si tienes paginación, agrégala aquí -->
                    <div class="mt-3 d-flex justify-content-center">
                        {{ $productos->onEachSide(1)->links('pagination::bootstrap-4') }}
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
.rounded-pill {
    border-radius: 50rem !important;
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
