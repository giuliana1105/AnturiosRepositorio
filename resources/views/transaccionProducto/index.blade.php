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
                <a class="nav-link active text-info fw-bold mb-2" href="{{ route('transaccionProducto.index') }}">
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
            <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width: 1400px;">
                <div class="card-header bg-info text-white rounded-top-4 text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i> Gestión de Transacciones
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Filtros y búsqueda -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-bold mb-2">Filtrar por Estado</label>
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('transaccionProducto.index', ['estado' => '']) }}" 
                                   class="btn {{ request('estado') == '' ? 'btn-info' : 'btn-outline-info' }} rounded-start-pill">
                                    <i class="fas fa-list me-2"></i> Todas
                                </a>
                                <a href="{{ route('transaccionProducto.index', ['estado' => 'PENDIENTE']) }}" 
                                   class="btn {{ request('estado') == 'PENDIENTE' ? 'btn-warning' : 'btn-outline-warning' }}">
                                    <i class="fas fa-clock me-2"></i> Pendientes
                                </a>
                                <a href="{{ route('transaccionProducto.index', ['estado' => 'FINALIZADA']) }}" 
                                   class="btn {{ request('estado') == 'FINALIZADA' ? 'btn-success' : 'btn-outline-success' }} rounded-end-pill">
                                    <i class="fas fa-check-circle me-2"></i> Finalizadas
                                </a>
                            </div>
                        </div>
                        
                            <!-- <form action="{{ route('transaccionProducto.index') }}" method="GET" class="h-100">
                                <label for="search" class="form-label fw-bold">Buscar Transacción</label>
                                <div class="input-group">
                                    <input type="text" name="search" id="search" class="form-control rounded-start-pill" 
                                           placeholder="Código de nota" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-info rounded-end-pill">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form> -->
                        </div>
                    </div>

                    <!-- Contadores de transacciones -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 bg-warning bg-opacity-10 h-100">
                                <div class="card-body text-center">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <div class="bg-warning rounded-circle p-3 me-3">
                                            <i class="fas fa-clock text-white fa-lg"></i>
                                        </div>
                                        <div>
                                            <h5 class="text-warning mb-1 fw-bold">Transacciones Pendientes</h5>
                                            <h2 class="text-warning mb-0">{{ $pendientes ?? 0 }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card border-0 bg-success bg-opacity-10 h-100">
                                <div class="card-body text-center">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <div class="bg-success rounded-circle p-3 me-3">
                                            <i class="fas fa-check-circle text-white fa-lg"></i>
                                        </div>
                                        <div>
                                            <h5 class="text-success mb-1 fw-bold">Transacciones Finalizadas</h5>
                                            <h2 class="text-success mb-0">{{ $finalizadas ?? 0 }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de transacciones -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th><i class="fas fa-barcode me-2"></i>CÓDIGO NOTA</th>
                                    <th><i class="fas fa-tag me-2"></i>TIPO NOTA</th>
                                    <th><i class="fas fa-info-circle me-2"></i>ESTADO</th>
                                    <th><i class="fas fa-boxes me-2"></i>PRODUCTOS</th>
                                    <th><i class="fas fa-cogs me-2"></i>ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transacciones as $transaccion)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $transaccion->tipoNota->codigo }}</td>
                                        <td class="text-center">{{ $transaccion->tipoNota->tiponota }}</td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill {{ $transaccion->estado == 'PENDIENTE' ? 'bg-warning' : 'bg-success' }} px-3 py-2">
                                                <i class="fas {{ $transaccion->estado == 'PENDIENTE' ? 'fa-clock' : 'fa-check-circle' }} me-1"></i>
                                                {{ $transaccion->estado }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="productos-list">
                                                @foreach ($transaccion->tipoNota->detalles as $detalle)
                                                    <div class="producto-item {{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                                                <i class="fas fa-cube text-info"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="fw-bold text-dark">{{ $detalle->producto->nombre ?? 'N/A' }}</div>
                                                                <div class="small text-muted">
                                                                    <span class="me-3">
                                                                        <i class="fas fa-sort-amount-up me-1"></i>
                                                                        Cantidad: <strong>{{ $detalle->cantidad }}</strong>
                                                                    </span>
                                                                    <span>
                                                                        <i class="fas fa-box me-1"></i>
                                                                        {{ $detalle->producto->tipoempaque ?? 'Sin Empaque' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($transaccion->estado == 'PENDIENTE')
                                                <form action="{{ route('transaccionProducto.finalizar', $transaccion->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success rounded-pill px-4" 
                                                          
                                                        <i class="fas fa-check me-2"></i> Finalizar
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-secondary rounded-pill px-3 py-2">
                                                    <i class="fas fa-check-double me-1"></i> Completado
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                                                <p class="mb-0">No hay transacciones registradas</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-3 d-flex justify-content-center">
                        {{ $transacciones->appends(['estado' => request('estado'), 'search' => request('search')])->onEachSide(1)->links('pagination::bootstrap-4') }}
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
.btn-outline-info {
    color: #0097a7;
    border-color: #0097a7;
}
.btn-outline-info:hover, .btn-outline-info:focus {
    background-color: #0097a7;
    border-color: #0097a7;
    color: #fff;
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
.btn-outline-warning {
    color: #ff9800;
    border-color: #ff9800;
}
.btn-outline-warning:hover, .btn-outline-warning:focus {
    background-color: #ff9800;
    border-color: #ff9800;
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
.btn-outline-success {
    color: #4caf50;
    border-color: #4caf50;
}
.btn-outline-success:hover, .btn-outline-success:focus {
    background-color: #4caf50;
    border-color: #4caf50;
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
.rounded-start-pill {
    border-top-left-radius: 50rem !important;
    border-bottom-left-radius: 50rem !important;
}
.rounded-end-pill {
    border-top-right-radius: 50rem !important;
    border-bottom-right-radius: 50rem !important;
}
.productos-list {
    max-height: 200px;
    overflow-y: auto;
}
.producto-item {
    padding: 0.5rem;
    margin: 0.25rem 0;
    border-radius: 0.5rem;
    transition: background-color 0.2s ease;
}
.producto-item:hover {
    background-color: rgba(0, 151, 167, 0.05);
}
.badge {
    font-size: 0.75rem;
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
    .btn-group {
        flex-direction: column;
    }
    .btn-group .btn {
        border-radius: 0.5rem !important;
        margin-bottom: 0.25rem;
    }
    .table-responsive {
        font-size: 0.875rem;
    }
    .productos-list {
        max-height: 150px;
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