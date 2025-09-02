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
                <a class="nav-link text-dark mb-2" href="{{ route('bodegas.index') }}">
                    <i class="fas fa-warehouse me-2"></i> Bodegas
                </a>
                <a class="nav-link text-dark mb-2" href="{{ route('transaccionProducto.index') }}">
                    <i class="fas fa-exchange-alt me-2"></i> Transacción Producto
                </a>
                <a class="nav-link active text-info fw-bold mb-2" href="#">
                    <i class="fas fa-shopping-cart me-2"></i> Ventas
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 py-3 px-4 bg-white">
            <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width: 1400px;">
                <div class="card-header bg-info text-white rounded-top-4 text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-edit me-2"></i> Editar Venta #{{ $venta->id }}
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('venta.update', $venta->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Información Básica de la Venta -->
                        <div class="card mb-4 border-0 bg-primary bg-opacity-10">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i> Información Básica
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-calendar me-2"></i>Fecha
                                        </label>
                                        <input type="date" name="fecha" class="form-control" 
                                               value="{{ $venta->fecha ? \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') : '' }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-user me-2"></i>Cliente
                                        </label>
                                        <input type="text" name="cliente" class="form-control" 
                                               value="{{ old('cliente', $venta->cliente) }}" required 
                                               placeholder="Nombre del cliente">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-map-marker-alt me-2"></i>Ciudad
                                        </label>
                                        <select name="ciudad" class="form-control" required>
                                            <option value="">Seleccione una ciudad</option>
                                            @foreach([
                                                'Ambato','Arajuno','Archidona','Atacames','Atuntaqui','Azogues','Babahoyo','Baeza','Bahía de Caráquez','Balao','Balsas','Balzar','Baños de Agua Santa','Bucay','Calceta','Carlos Julio Arosemena Tola','Catarama','Chone','Coca','Colimes','Coronel Marcelino Maridueña','Cotacachi','Cuenca','Daule','Durán','El Chaco','El Empalme','El Guabo','El Triunfo','Esmeraldas','Gualaquiza','Guaranda','Guayaquil','Huaquillas','Ibarra','Isidro Ayora','Jama','Jujan','La Concordia','La Libertad','Lago Agrio (Nueva Loja)','Latacunga','Limones','Logroño','Loja','Lomas de Sargentillo','Macas','Machala','Manta','Mera','Milagro','Montecristi','Muisne','Naranjal','Nobol','Nuevo Rocafuerte','Otavalo','Paján','Palestina','Palora','Pasaje','Pedernales','Pedro Carbo','Pichincha (ciudad homónima)','Pimampiro','Piñas','Playas (General Villamil)','Portovelo','Portoviejo','Puerto Ayora','Puerto Baquerizo Moreno','Puerto El Carmen de Putumayo','Puerto López','Puerto Villamil','Puyo','Quevedo','Quinindé','Quito (capital)','Riobamba','Rioverde','Rocafuerte','San Lorenzo','San Vicente','Santa Rosa','Santo Domingo (Santo Domingo de los Tsáchilas)','Salinas','Samborondón','Santa Elena','Simón Bolívar','Sucre','Sucúa','Tarapoa','Tena','Tosagua','Tulcán','Urcuquí','Valencia','Ventanas','Vinces','Yaguachi','Yantzaza','Zamora','Zaruma'
                                            ] as $ciudad)
                                                <option value="{{ $ciudad }}" {{ old('ciudad', $venta->ciudad) == $ciudad ? 'selected' : '' }}>{{ $ciudad }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Productos -->
                        <div class="card mb-4 border-0 bg-success bg-opacity-10">
                            <div class="card-header bg-success text-white rounded-top-4">
                                <h5 class="mb-0">
                                    <i class="fas fa-shopping-bag me-2"></i> Productos de la Venta
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="productos-container">
                                    @if($venta->detalles && count($venta->detalles) > 0)
                                        @foreach($venta->detalles as $i => $detalle)
                                        <div class="row align-items-end mb-3 row-producto p-3 bg-white rounded-4 border">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-cube me-2"></i>Producto
                                                </label>
                                                <select name="producto_id[]" class="form-control producto-select" disabled>
                                                    <option value="">Seleccione un producto</option>
                                                    @foreach($productos as $prod)
                                                        <option value="{{ $prod['codigo'] }}" 
                                                                data-stock="{{ $prod['stock'] }}" 
                                                                data-empaque="{{ $prod['tipoempaque'] }}"
                                                                {{ old('producto_id.'.$i, $detalle->producto_id) == $prod['codigo'] ? 'selected' : '' }}>
                                                            {{ $prod['codigo'] }} - {{ $prod['nombre'] }} (Stock: {{ $prod['stock'] }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="producto_id[]" value="{{ $detalle->producto_id }}">
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-calculator me-2"></i>Cantidad
                                                </label>
                                                <input type="number" name="cantidad[]" class="form-control cantidad-input text-center" 
                                                       min="1" value="{{ old('cantidad.'.$i, $detalle->cantidad) }}" readonly>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-box me-2"></i>Tipo Empaque
                                                </label>
                                                <input type="text" name="tipoempaque[]" class="form-control empaque-input text-center" 
                                                       value="{{ old('tipoempaque.'.$i, $detalle->tipoempaque) }}" readonly>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-tag me-2"></i>Precio Unitario
                                                </label>
                                                <input type="number" name="precio_unitario[]" class="form-control precio-unitario-input text-center" 
                                                       step="0.01" min="0.01" value="{{ old('precio_unitario.'.$i, $detalle->precio_unitario) }}" readonly>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-dollar-sign me-2"></i>Precio Total
                                                </label>
                                                <input type="number" name="precio_total[]" class="form-control precio-total-input text-center fw-bold" 
                                                       value="{{ old('precio_total.'.$i, $detalle->precio_total) }}" readonly>
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Total y Forma de Pago -->
                        <div class="card mb-4 border-0 bg-info bg-opacity-10">
                            <div class="card-header bg-info text-white rounded-top-4">
                                <h5 class="mb-0">
                                    <i class="fas fa-calculator me-2"></i> Total y Forma de Pago
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-dollar-sign me-2"></i>Total venta
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white">
                                                <i class="fas fa-dollar-sign"></i>
                                            </span>
                                            <input type="number" name="total_venta" class="form-control fw-bold text-success" id="total-venta" 
                                                   value="{{ old('total_venta', $venta->total_venta) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-credit-card me-2"></i>Tipo de pago
                                        </label>
                                        <select name="tipo_pago" class="form-control" id="tipo-pago-select" required>
                                            <option value="Efectivo" {{ old('tipo_pago', $venta->tipo_pago) == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                                            <option value="Transferencia" {{ old('tipo_pago', $venta->tipo_pago) == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                                            <option value="Crédito" {{ old('tipo_pago', $venta->tipo_pago) == 'Crédito' ? 'selected' : '' }}>Crédito</option>
                                            <option value="Cheque" {{ old('tipo_pago', $venta->tipo_pago) == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $mostrarAbonos = old('tipo_pago', $venta->tipo_pago) == 'Crédito';
                        @endphp

                        <!-- Sección de Abonos (solo si es crédito) -->
                        <div id="abonos-section" style="display: {{ $mostrarAbonos ? 'block' : 'none' }};">
                            <div class="card mb-4 border-0 bg-warning bg-opacity-10">
                                <div class="card-header bg-warning text-white rounded-top-4">
                                    <h5 class="mb-0">
                                        <i class="fas fa-money-bill-wave me-2"></i> Gestión de Abonos
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div id="abonos-container">
                                        @if(isset($abonos) && count($abonos) > 0)
                                            @foreach($abonos as $j => $abono)
                                            <div class="row align-items-end mb-3 row-abono p-3 bg-white rounded-4 border">
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-money-bill me-2"></i>Abono
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-warning text-white">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </span>
                                                        <input type="number" name="abono[]" class="form-control abono-input fw-bold" 
                                                               min="0" step="0.01" value="{{ old('abono.'.$j, $abono->abono) }}" 
                                                               {{ $loop->index > 0 ? '' : 'readonly' }}>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-calendar me-2"></i>Fecha
                                                    </label>
                                                    <input type="date" name="fecha_abono[]" class="form-control fecha-abono-input" 
                                                           value="{{ old('fecha_abono.'.$j, \Carbon\Carbon::parse($abono->fecha)->format('Y-m-d')) }}" 
                                                           {{ $loop->index > 0 ? '' : 'readonly' }}>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-credit-card me-2"></i>Tipo de pago
                                                    </label>
                                                    @if($loop->index == 0)
                                                        <input type="text" name="tipo_pago_abono[]" class="form-control text-center" 
                                                               value="{{ old('tipo_pago_abono.'.$j, $abono->tipo_pago) }}" readonly>
                                                    @else
                                                        <select name="tipo_pago_abono[]" class="form-control">
                                                            <option value="Cheque" {{ old('tipo_pago_abono.'.$j, $abono->tipo_pago) == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                                            <option value="Efectivo" {{ old('tipo_pago_abono.'.$j, $abono->tipo_pago) == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                                                            <option value="Transferencia" {{ old('tipo_pago_abono.'.$j, $abono->tipo_pago) == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                                                        </select>
                                                    @endif
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    @if($loop->index > 0)
                                                        <div class="btn-group w-100" role="group">
                                                            <button type="button" class="btn btn-success btn-sm btn-add-abono rounded-pill">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm btn-remove-abono rounded-pill">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="row align-items-end mb-3 row-abono p-3 bg-white rounded-4 border">
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-money-bill me-2"></i>Abono
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-warning text-white">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </span>
                                                        <input type="number" name="abono[]" class="form-control abono-input" 
                                                               min="0" step="0.01" value="{{ old('abono.0', 0) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-calendar me-2"></i>Fecha
                                                    </label>
                                                    <input type="date" name="fecha_abono[]" class="form-control fecha-abono-input" 
                                                           value="{{ old('fecha_abono.0', now()->format('Y-m-d')) }}">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-credit-card me-2"></i>Tipo de pago
                                                    </label>
                                                    <select name="tipo_pago_abono[]" class="form-control">
                                                        <option value="Cheque" {{ old('tipo_pago_abono.0') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                                        <option value="Efectivo" {{ old('tipo_pago_abono.0') == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                                                        <option value="Transferencia" {{ old('tipo_pago_abono.0') == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="btn-group w-100" role="group">
                                                        <button type="button" class="btn btn-success btn-sm btn-add-abono rounded-pill">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-abono rounded-pill">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Saldo -->
                                    <div class="mt-3 p-3 bg-white rounded-4 border border-warning">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h5 class="mb-0 text-warning">
                                                    <i class="fas fa-balance-scale me-2"></i>Saldo:
                                                </h5>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-danger text-white">
                                                        <i class="fas fa-dollar-sign"></i>
                                                    </span>
                                                    <input type="number" name="saldo" class="form-control fw-bold text-danger" id="saldo-venta" 
                                                           value="{{ old('saldo', $venta->total_venta - (isset($abonos) ? collect($abonos)->sum('abono') : 0)) }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-center gap-3">
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4">
                                <i class="fas fa-save me-2"></i>Guardar cambios
                            </button>
                            <a href="{{ route('venta.index.bodega', $venta->bodega_id) }}" class="btn btn-secondary fw-bold rounded-pill px-4">
                                <i class="fas fa-times me-2"></i>Cancelar
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
.row-producto, .row-abono {
    transition: all 0.3s ease;
}
.row-producto:hover, .row-abono:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
    .col-md-3, .col-md-4, .col-md-6 {
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
    }
    .d-flex.gap-3 {
        flex-direction: column;
        gap: 1rem !important;
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

@section('scripts')
    @include('venta.partials.scripts-productos-abonos')
@endsection