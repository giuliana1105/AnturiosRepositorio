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
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 py-3 px-4 bg-white">
            <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width: 1400px;">
                <div class="card-header bg-info text-white rounded-top-4 text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i> Registrar Venta en {{ $bodega->nombrebodega }}
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Botón Volver -->
                    <div class="mb-4">
                        <a href="{{ route('bodegas.show', $bodega->idbodega) }}" class="btn btn-secondary fw-bold rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </a>
                    </div>

                    <form method="POST" action="{{ route('venta.store', $bodega->idbodega) }}" id="form-venta">
                        @csrf
                        
                        <!-- Información básica de la venta -->
                        <div class="card mb-4 border-0 bg-primary bg-opacity-10">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i> Información de la Venta
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-hashtag me-2"></i>Nro. venta
                                        </label>
                                        <input type="text" class="form-control rounded-pill" value="{{ $nroVenta }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-calendar me-2"></i>Fecha
                                        </label>
                                        <input type="text" class="form-control rounded-pill" value="{{ now()->format('Y-m-d') }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-user me-2"></i>Cliente
                                        </label>
                                        <input type="text" name="cliente" class="form-control rounded-pill" required placeholder="Nombre del cliente">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-map-marker-alt me-2"></i>Ciudad
                                        </label>
                                        <select name="ciudad" class="form-control rounded-pill" required>
                                            <option value="">Seleccione una ciudad</option>
                                            <option>Ambato</option>
                                            <option>Arajuno</option>
                                            <option>Archidona</option>
                                            <option>Atacames</option>
                                            <option>Atuntaqui</option>
                                            <option>Azogues</option>
                                            <option>Babahoyo</option>
                                            <option>Baeza</option>
                                            <option>Bahía de Caráquez</option>
                                            <option>Balao</option>
                                            <option>Balsas</option>
                                            <option>Balzar</option>
                                            <option>Baños de Agua Santa</option>
                                            <option>Bucay</option>
                                            <option>Calceta</option>
                                            <option>Carlos Julio Arosemena Tola</option>
                                            <option>Catarama</option>
                                            <option>Chone</option>
                                            <option>Coca</option>
                                            <option>Colimes</option>
                                            <option>Coronel Marcelino Maridueña</option>
                                            <option>Cotacachi</option>
                                            <option>Cuenca</option>
                                            <option>Daule</option>
                                            <option>Durán</option>
                                            <option>El Chaco</option>
                                            <option>El Empalme</option>
                                            <option>El Guabo</option>
                                            <option>El Triunfo</option>
                                            <option>Esmeraldas</option>
                                            <option>Gualaquiza</option>
                                            <option>Guaranda</option>
                                            <option>Guayaquil</option>
                                            <option>Huaquillas</option>
                                            <option>Ibarra</option>
                                            <option>Isidro Ayora</option>
                                            <option>Jama</option>
                                            <option>Jujan</option>
                                            <option>La Concordia</option>
                                            <option>La Libertad</option>
                                            <option>Lago Agrio (Nueva Loja)</option>
                                            <option>Latacunga</option>
                                            <option>Limones</option>
                                            <option>Logroño</option>
                                            <option>Loja</option>
                                            <option>Lomas de Sargentillo</option>
                                            <option>Macas</option>
                                            <option>Machala</option>
                                            <option>Manta</option>
                                            <option>Mera</option>
                                            <option>Milagro</option>
                                            <option>Montecristi</option>
                                            <option>Muisne</option>
                                            <option>Naranjal</option>
                                            <option>Nobol</option>
                                            <option>Nuevo Rocafuerte</option>
                                            <option>Otavalo</option>
                                            <option>Pajá</option>
                                            <option>Palestina</option>
                                            <option>Palora</option>
                                            <option>Pasaje</option>
                                            <option>Pedernales</option>
                                            <option>Pedro Carbo</option>
                                            <option>Pichincha (ciudad homónima)</option>
                                            <option>Pimampiro</option>
                                            <option>Piñas</option>
                                            <option>Playas (General Villamil)</option>
                                            <option>Portovelo</option>
                                            <option>Portoviejo</option>
                                            <option>Puerto Ayora</option>
                                            <option>Puerto Baquerizo Moreno</option>
                                            <option>Puerto El Carmen de Putumayo</option>
                                            <option>Puerto López</option>
                                            <option>Puerto Villamil</option>
                                            <option>Puyo</option>
                                            <option>Quevedo</option>
                                            <option>Quinindé</option>
                                            <option>Quito (capital)</option>
                                            <option>Riobamba</option>
                                            <option>Rioverde</option>
                                            <option>Rocafuerte</option>
                                            <option>San Lorenzo</option>
                                            <option>San Vicente</option>
                                            <option>Santa Rosa</option>
                                            <option>Santo Domingo (Santo Domingo de los Tsáchilas)</option>
                                            <option>Salinas</option>
                                            <option>Samborondón</option>
                                            <option>Santa Elena</option>
                                            <option>Simón Bolívar</option>
                                            <option>Sucre</option>
                                            <option>Sucúa</option>
                                            <option>Tarapoa</option>
                                            <option>Tena</option>
                                            <option>Tosagua</option>
                                            <option>Tulcán</option>
                                            <option>Urcuquí</option>
                                            <option>Valencia</option>
                                            <option>Ventanas</option>
                                            <option>Vinces</option>
                                            <option>Yaguachi</option>
                                            <option>Yantzaza</option>
                                            <option>Zamora</option>
                                            <option>Zaruma</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Productos -->
                        <div class="card mb-4 border-0 bg-success bg-opacity-10">
                            <div class="card-header bg-success text-white rounded-top-4">
                                <h5 class="mb-0">
                                    <i class="fas fa-cube me-2"></i> Productos
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="productos-container">
                                    <div class="row align-items-end mb-3 row-producto border rounded-4 p-3 bg-white shadow-sm">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-cube me-2"></i>Producto
                                            </label>
                                            <select name="producto_id[]" class="form-control rounded-pill producto-select" required>
                                                <option value="">Seleccione un producto</option>
                                                @foreach($productos as $prod)
                                                    <option value="{{ $prod['codigo'] }}" data-stock="{{ $prod['stock'] }}" data-empaque="{{ $prod['tipoempaque'] }}">
                                                        {{ $prod['codigo'] }} - {{ $prod['nombre'] }} (Stock: {{ $prod['stock'] }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-sort-numeric-up me-2"></i>Cantidad
                                            </label>
                                            <input type="number" name="cantidad[]" class="form-control rounded-pill cantidad-input" min="1" required>
                                            <small class="max-cantidad form-text text-muted"></small>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-box me-2"></i>Tipo Empaque
                                            </label>
                                            <input type="text" name="tipoempaque[]" class="form-control rounded-pill empaque-input" value="Unidad" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-dollar-sign me-2"></i>Precio Unitario
                                            </label>
                                            <input type="number" name="precio_unitario[]" class="form-control rounded-pill precio-unitario-input" step="0.01" min="0.01" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-calculator me-2"></i>Precio Total
                                            </label>
                                            <input type="number" name="precio_total[]" class="form-control rounded-pill precio-total-input" readonly>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label fw-bold text-transparent">Acciones</label>
                                            <div class="d-flex flex-column gap-1">
                                                <button type="button" class="btn btn-success btn-sm rounded-pill btn-add-producto">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm rounded-pill btn-remove-producto">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de pago -->
                        <div class="card mb-4 border-0 bg-warning bg-opacity-10">
                            <div class="card-header bg-warning text-white rounded-top-4">
                                <h5 class="mb-0">
                                    <i class="fas fa-credit-card me-2"></i> Información de Pago
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-calculator me-2"></i>Total venta
                                        </label>
                                        <input type="number" name="total_venta" class="form-control rounded-pill" id="total-venta" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-credit-card me-2"></i>Tipo de pago
                                        </label>
                                        <select name="tipo_pago" class="form-control rounded-pill" required>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Crédito">Crédito</option>
                                            <option value="Cheque">Cheque</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Sección de abonos (oculta por defecto) -->
                                <div id="abonos-section" style="display:none;">
                                    <div class="card border-0 bg-info bg-opacity-10">
                                        <div class="card-header bg-info text-white rounded-top-4">
                                            <h6 class="mb-0">
                                                <i class="fas fa-money-bill me-2"></i> Abonos
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="abonos-container">
                                                <div class="row align-items-end mb-2 row-abono border rounded-4 p-3 bg-white shadow-sm">
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold">
                                                            <i class="fas fa-money-bill me-2"></i>Abono
                                                        </label>
                                                        <input type="number" name="abono[]" class="form-control rounded-pill abono-input" min="0" step="0.01" value="0">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold">
                                                            <i class="fas fa-calendar me-2"></i>Fecha
                                                        </label>
                                                        <input type="text" name="fecha_abono[]" class="form-control rounded-pill fecha-abono-input" value="{{ now()->format('Y-m-d') }}" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold">
                                                            <i class="fas fa-credit-card me-2"></i>Tipo de pago
                                                        </label>
                                                        <select name="tipo_pago_abono[]" class="form-control rounded-pill">
                                                            <option value="Cheque">Cheque</option>
                                                            <option value="Efectivo">Efectivo</option>
                                                            <option value="Transferencia">Transferencia</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label fw-bold text-transparent">Acciones</label>
                                                        <div class="d-flex gap-1">
                                                            <button type="button" class="btn btn-success btn-sm rounded-pill btn-add-abono">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm rounded-pill btn-remove-abono">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-balance-scale me-2"></i>Saldo
                                                </label>
                                                <input type="number" name="saldo" class="form-control rounded-pill" id="saldo-venta" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="text-center">
                            <button type="button" class="btn btn-success fw-bold rounded-pill px-4 me-3" id="btn-pre-confirmar">
                                <i class="fas fa-save me-2"></i> Registrar Venta
                            </button>
                            <a href="{{ route('bodegas.show', $bodega->idbodega) }}" class="btn btn-secondary fw-bold rounded-pill px-4">
                                <i class="fas fa-times me-2"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmVentaModal" tabindex="-1" aria-labelledby="confirmVentaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="confirmVentaLabel">
                    <i class="fas fa-check-circle me-2"></i> Confirmar Venta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div id="detalle-venta-preview">
                    <!-- Aquí se llenará el detalle con JS -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success rounded-pill" id="btn-confirmar-venta">
                    <i class="fas fa-check me-2"></i> Confirmar y Guardar
                </button>
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
.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}
.text-transparent {
    color: transparent !important;
}
.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
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
    .col-md-3, .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    .d-flex.gap-1 {
        flex-direction: column;
    }
    .d-flex.flex-column.gap-1 {
        flex-direction: column;
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function actualizarSelects() {
        // Evita productos repetidos
        const selects = document.querySelectorAll('.producto-select');
        const seleccionados = Array.from(selects).map(s => s.value).filter(v => v);
        selects.forEach(select => {
            Array.from(select.options).forEach(opt => {
                opt.disabled = seleccionados.includes(opt.value) && select.value !== opt.value && opt.value !== '';
            });
        });
    }

    function actualizarCampos(row) {
        const select = row.querySelector('.producto-select');
        const cantidadInput = row.querySelector('.cantidad-input');
        const maxCantidad = row.querySelector('.max-cantidad');
        const empaqueInput = row.querySelector('.empaque-input');
        const precioUnitarioInput = row.querySelector('.precio-unitario-input');
        const precioTotalInput = row.querySelector('.precio-total-input');

        select.addEventListener('change', function() {
            const selected = select.options[select.selectedIndex];
            const stock = selected.getAttribute('data-stock');
            const empaque = selected.getAttribute('data-empaque');
            cantidadInput.max = stock;
            maxCantidad.textContent = stock ? `Máx: ${stock}` : '';
            empaqueInput.value = empaque || 'Unidad';
            cantidadInput.value = '';
            precioTotalInput.value = '';
            actualizarSelects();
        });

        cantidadInput.addEventListener('input', calcularTotal);
        precioUnitarioInput.addEventListener('input', calcularTotal);

        function calcularTotal() {
            const cantidad = parseFloat(cantidadInput.value) || 0;
            const precioUnitario = parseFloat(precioUnitarioInput.value) || 0;
            if (cantidad > 0 && precioUnitario > 0) {
                precioTotalInput.value = (cantidad * precioUnitario).toFixed(2);
            } else {
                precioTotalInput.value = '';
            }
        }
    }

    // Inicializa la primera fila
    document.querySelectorAll('.row-producto').forEach(row => actualizarCampos(row));

    document.getElementById('productos-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-add-producto')) {
            const row = e.target.closest('.row-producto');
            const newRow = row.cloneNode(true);

            // Limpia los valores
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelector('.producto-select').selectedIndex = 0;
            newRow.querySelector('.max-cantidad').textContent = '';
            actualizarCampos(newRow);

            row.parentNode.appendChild(newRow);
            actualizarSelects();
            calcularTotalVenta();
        }
        if (e.target.classList.contains('btn-remove-producto')) {
            const rows = document.querySelectorAll('.row-producto');
            if (rows.length > 1) {
                e.target.closest('.row-producto').remove();
                actualizarSelects();
                calcularTotalVenta();
            }
        }
    });

    // Al cambiar cualquier select, actualiza los disables
    document.getElementById('productos-container').addEventListener('change', function(e) {
        if (e.target.classList.contains('producto-select')) {
            actualizarSelects();
        }
    });

    function calcularTotalVenta() {
        let total = 0;
        document.querySelectorAll('.precio-total-input').forEach(function(input) {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('total-venta').value = total.toFixed(2);
    }
    document.getElementById('productos-container').addEventListener('input', calcularTotalVenta);
    document.getElementById('productos-container').addEventListener('change', calcularTotalVenta);

    // Sección de abonos
    document.getElementById('abonos-section').style.display = 'none'; // Oculta por defecto

    document.querySelectorAll('.row-abono').forEach(row => {
        row.querySelector('.abono-input').addEventListener('input', function() {
            const abono = parseFloat(this.value) || 0;
            const filaAbono = this.closest('.row-abono');
            const tipoPagoAbono = filaAbono.querySelector('select[name="tipo_pago_abono[]"]');
            
            // Lógica para mostrar/ocultar tipo de pago según el abono
            if (abono > 0) {
                tipoPagoAbono.closest('.col-md-3').style.display = 'block';
            } else {
                tipoPagoAbono.closest('.col-md-3').style.display = 'none';
            }

            calcularSaldo();
        });
    });

    // Mostrar/ocultar sección de abonos
    const tipoPagoSelect = document.querySelector('select[name="tipo_pago"]');
    const abonosSection = document.getElementById('abonos-section');
    const totalVentaInput = document.getElementById('total-venta');
    const saldoVentaInput = document.getElementById('saldo-venta');

    tipoPagoSelect.addEventListener('change', function() {
        if (tipoPagoSelect.value === 'Crédito') {
            abonosSection.style.display = '';
            calcularSaldo();
        } else {
            abonosSection.style.display = 'none';
            saldoVentaInput.value = '';
        }
    });

    // Función para calcular saldo
    function calcularSaldo() {
        let totalVenta = parseFloat(totalVentaInput.value) || 0;
        let totalAbonos = 0;
        document.querySelectorAll('.abono-input').forEach(function(input) {
            totalAbonos += parseFloat(input.value) || 0;
        });
        saldoVentaInput.value = (totalVenta - totalAbonos).toFixed(2);
    }

    // Inicializa la primera fila de abono
    document.querySelectorAll('.row-abono').forEach(row => {
        row.querySelector('.abono-input').addEventListener('input', calcularSaldo);
    });

    // Agregar y eliminar filas de abono
    document.getElementById('abonos-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-add-abono')) {
            const row = e.target.closest('.row-abono');
            const newRow = row.cloneNode(true);

            // Limpia los valores
            newRow.querySelector('.abono-input').value = '0';
            newRow.querySelector('.fecha-abono-input').value = '{{ now()->format('Y-m-d') }}'; 
            newRow.querySelector('select[name="tipo_pago_abono[]"]').selectedIndex = 0;

            newRow.querySelector('.abono-input').addEventListener('input', calcularSaldo);

            row.parentNode.appendChild(newRow);
            calcularSaldo();
        }
        if (e.target.classList.contains('btn-remove-abono')) {
            const rows = document.querySelectorAll('.row-abono');
            if (rows.length > 1) {
                e.target.closest('.row-abono').remove();
                calcularSaldo();
            }
        }
    });

    // Recalcula saldo al cambiar abonos
    document.getElementById('abonos-container').addEventListener('input', calcularSaldo);

    // Recalcula saldo al cambiar productos
    document.getElementById('productos-container').addEventListener('input', function() {
        if (tipoPagoSelect.value === 'Crédito') {
            calcularSaldo();
        }
    });
    document.getElementById('productos-container').addEventListener('change', function() {
        if (tipoPagoSelect.value === 'Crédito') {
            calcularSaldo();
        }
    });

    // Botón para mostrar el modal
    document.getElementById('btn-pre-confirmar').addEventListener('click', function(e) {
        e.preventDefault();
        mostrarDetalleVenta();
        var modal = new bootstrap.Modal(document.getElementById('confirmVentaModal'));
        modal.show();
    });

    // Botón para confirmar y guardar
    document.getElementById('btn-confirmar-venta').addEventListener('click', function() {
        document.getElementById('form-venta').submit();
    });

    function mostrarDetalleVenta() {
        const cliente = document.querySelector('input[name="cliente"]').value;
        const ciudad = document.querySelector('select[name="ciudad"]').value;
        const tipoPago = document.querySelector('select[name="tipo_pago"]').value;
        const productos = [];
        document.querySelectorAll('.row-producto').forEach(row => {
            productos.push({
                cantidad: row.querySelector('input[name="cantidad[]"]').value,
                producto: row.querySelector('select[name="producto_id[]"] option:checked').text,
                empaque: row.querySelector('input[name="tipoempaque[]"]').value,
                precio: row.querySelector('input[name="precio_unitario[]"]').value,
                total: row.querySelector('input[name="precio_total[]"]').value
            });
        });
        const totalVenta = document.getElementById('total-venta').value;

        let html = `
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="card border-0 bg-primary bg-opacity-10">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-user me-2"></i>Cliente</h6>
                        <p class="card-text fw-bold">${cliente}</p>
                        <h6 class="card-title"><i class="fas fa-map-marker-alt me-2"></i>Ciudad</h6>
                        <p class="card-text fw-bold">${ciudad}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 bg-success bg-opacity-10">
                    <div class="card-body text-end">
                        <h6 class="card-title"><i class="fas fa-credit-card me-2"></i>Forma de pago</h6>
                        <p class="card-text fw-bold">${tipoPago}</p>
                        <h6 class="card-title"><i class="fas fa-calendar me-2"></i>Fecha</h6>
                        <p class="card-text fw-bold">{{ now()->format('Y-m-d') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th><i class="fas fa-sort-numeric-up me-2"></i>Cantidad</th>
                        <th><i class="fas fa-cube me-2"></i>Producto</th>
                        <th><i class="fas fa-box me-2"></i>Empaque</th>
                        <th><i class="fas fa-dollar-sign me-2"></i>Precio</th>
                        <th><i class="fas fa-calculator me-2"></i>Total</th>
                    </tr>
                </thead>
                <tbody>
        `;
        productos.forEach(p => {
            html += `<tr>
                <td class="text-center">${p.cantidad}</td>
                <td>${p.producto}</td>
                <td class="text-center">${p.empaque}</td>
                <td class="text-end">${parseFloat(p.precio).toFixed(2)}</td>
                <td class="text-end fw-bold">${parseFloat(p.total).toFixed(2)}</td>
            </tr>`;
        });
        html += `
                </tbody>
            </table>
        </div>
        <div class="card border-0 bg-warning bg-opacity-10 mt-3">
            <div class="card-body text-end">
                <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Total venta: <span class="fw-bold">${parseFloat(totalVenta).toFixed(2)}</span></h5>
            </div>
        </div>
        `;

        // Mostrar abonos si el tipo de pago es Crédito
        if (tipoPago === 'Crédito') {
            const abonos = [];
            document.querySelectorAll('.row-abono').forEach(row => {
                abonos.push({
                    abono: row.querySelector('input[name="abono[]"]').value,
                    fecha: row.querySelector('input[name="fecha_abono[]"]').value,
                    tipo: row.querySelector('select[name="tipo_pago_abono[]"]').value
                });
            });
            const saldo = document.getElementById('saldo-venta').value;

            html += `
            <div class="card border-0 bg-info bg-opacity-10 mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-money-bill me-2"></i>Abonos</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fas fa-money-bill me-2"></i>Abono</th>
                                    <th><i class="fas fa-calendar me-2"></i>Fecha</th>
                                    <th><i class="fas fa-credit-card me-2"></i>Tipo de pago</th>
                                </tr>
                            </thead>
                            <tbody>
            `;
            abonos.forEach(a => {
                html += `<tr>
                    <td class="text-end">${parseFloat(a.abono).toFixed(2)}</td>
                    <td class="text-center">${a.fecha}</td>
                    <td class="text-center">${a.tipo}</td>
                </tr>`;
            });
            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card border-0 bg-danger bg-opacity-10 mt-3">
                <div class="card-body text-end">
                    <h5 class="mb-0"><i class="fas fa-balance-scale me-2"></i>Saldo: <span class="fw-bold">${parseFloat(saldo).toFixed(2)}</span></h5>
                </div>
            </div>
            `;
        }

        document.getElementById('detalle-venta-preview').innerHTML = html;
    }
});
</script>
@endsection