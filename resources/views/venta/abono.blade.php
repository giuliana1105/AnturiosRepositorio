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
                  @if(!in_array($cargo, ['Vendedor camión', 'Vendedor']))
                <a class="nav-link text-dark mb-2" href="{{ route('productos.index') }}">
                    <i class="fas fa-cube me-2"></i> Productos
                </a>
                                 

                <a class="nav-link text-dark mb-2" href="{{ route('empleados.index') }}">
                    <i class="fas fa-users me-2"></i> Empleados
                </a>
                <a class="nav-link text-dark mb-2" href="{{ route('bodegas.index') }}">
                    <i class="fas fa-warehouse me-2"></i> Bodegas
                </a>
                <a class="nav-link text-dark mb-2" href="{{ route('transaccionProducto.index') }}">
                    <i class="fas fa-exchange-alt me-2"></i> Transacción Producto
                </a>
                @endif
                <a class="nav-link active text-info fw-bold mb-2" href="#">
                    <i class="fas fa-shopping-cart me-2"></i> Ventas
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
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 py-3 px-4 bg-white">
            <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width: 1400px;">
                <div class="card-header bg-info text-white rounded-top-4 text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i> Agregar abono a la Venta #{{ $venta->id }}
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Mensajes de éxito -->
                    @if(session('success'))
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

                    <!-- Botón Volver -->
                    <div class="mb-4">
                        <a href="{{ route('venta.index.bodega', $venta->bodega_id) }}" class="btn btn-secondary fw-bold rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </a>
                    </div>

                    <!-- Información de la Venta -->
                    <div class="card mb-4 border-0 bg-primary bg-opacity-10">
                        <div class="card-header bg-primary text-white rounded-top-4">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i> Información de la Venta
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle">
                                    <tbody>
                                        <tr>
                                            <th class="fw-bold text-primary" style="width: 200px;">
                                                <i class="fas fa-calendar me-2"></i>Fecha
                                            </th>
                                            <td class="fw-bold">{{ \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="fw-bold text-primary">
                                                <i class="fas fa-user me-2"></i>Cliente
                                            </th>
                                            <td>{{ $venta->cliente }}</td>
                                        </tr>
                                        <tr>
                                            <th class="fw-bold text-primary">
                                                <i class="fas fa-map-marker-alt me-2"></i>Ciudad
                                            </th>
                                            <td>{{ $venta->ciudad }}</td>
                                        </tr>
                                        <tr>
                                            <th class="fw-bold text-primary">
                                                <i class="fas fa-warehouse me-2"></i>Bodega
                                            </th>
                                            <td>{{ $venta->bodega->nombrebodega ?? $venta->bodega_id }}</td>
                                        </tr>
                                        <tr>
                                            <th class="fw-bold text-primary">
                                                <i class="fas fa-dollar-sign me-2"></i>Total venta
                                            </th>
                                            <td class="fw-bold text-success fs-5">${{ number_format($venta->total_venta, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="fw-bold text-primary">
                                                <i class="fas fa-credit-card me-2"></i>Forma de pago
                                            </th>
                                            <td>
                                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                                    {{ $venta->tipo_pago }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Abonos Anteriores -->
                    <div class="card mb-4 border-0 bg-warning bg-opacity-10">
                        <div class="card-header bg-warning text-white rounded-top-4">
                            <h5 class="mb-0">
                                <i class="fas fa-history me-2"></i> Abonos Anteriores
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">
                                                <i class="fas fa-money-bill me-2"></i>Abono
                                            </th>
                                            <th class="text-center">
                                                <i class="fas fa-calendar me-2"></i>Fecha
                                            </th>
                                            <th class="text-center">
                                                <i class="fas fa-credit-card me-2"></i>Tipo de pago
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($abonos as $abono)
                                            <tr>
                                                <td class="text-center">
                                                    <input type="number" class="form-control text-center fw-bold text-success" 
                                                           value="{{ $abono->abono }}" readonly>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control text-center" 
                                                           value="{{ $abono->fecha }}" readonly>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control text-center" 
                                                           value="{{ $abono->tipo_pago }}" readonly>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4">
                                                    <i class="fas fa-exclamation-circle me-2"></i>
                                                    No hay abonos registrados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- Saldo Actual -->
                            <div class="mt-3 p-3 bg-white rounded-4 border border-warning">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h5 class="mb-0 text-warning">
                                            <i class="fas fa-balance-scale me-2"></i>Saldo Actual:
                                        </h5>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <span class="fs-4 fw-bold text-danger">
                                            ${{ number_format($saldo, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Agregar Nuevo Abono -->
                    <div class="card border-0 bg-success bg-opacity-10">
                        <div class="card-header bg-success text-white rounded-top-4">
                            <h5 class="mb-0">
                                <i class="fas fa-plus-circle me-2"></i> Agregar Nuevo Abono
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('venta.abono.store', $venta->id) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-money-bill me-2"></i>Abono
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white">
                                                <i class="fas fa-dollar-sign"></i>
                                            </span>
                                            <input type="number" name="abono" class="form-control" 
                                                   min="0.01" step="0.01" required 
                                                   placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-calendar me-2"></i>Fecha
                                        </label>
                                        <input type="date" name="fecha_abono" class="form-control" 
                                               value="{{ now()->format('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-credit-card me-2"></i>Tipo de pago
                                        </label>
                                        <select name="tipo_pago_abono" class="form-control" required>
                                            <option value="">Seleccionar...</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Transferencia">Transferencia</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-success fw-bold rounded-pill w-100">
                                            <i class="fas fa-plus-circle me-2"></i>Agregar abono
                                        </button>
                                    </div>
                                </div>
                            </form>
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
.btn-primary {
    background-color: #2196f3;
    border-color: #2196f3;
}
.btn-primary:hover, .btn-primary:focus {
    background-color: #1976d2;
    border-color: #1976d2;
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
.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}
.badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
}
.table-borderless th {
    border: none;
    padding: 1rem 0.5rem;
}
.table-borderless td {
    border: none;
    padding: 1rem 0.5rem;
}
.input-group-text {
    border-top-left-radius: 0.375rem !important;
    border-bottom-left-radius: 0.375rem !important;
}
.form-control {
    transition: all 0.2s ease;
}
.form-control:focus {
    border-color: #0097a7;
    box-shadow: 0 0 0 0.2rem rgba(0, 151, 167, 0.25);
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
    .table-responsive {
        font-size: 0.875rem;
    }
    .col-md-3, .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
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