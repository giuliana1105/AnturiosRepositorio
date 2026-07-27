@extends('layouts.app')
@php
    $cargo = auth()->user()->cargoNombre();
@endphp

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Registrar Venta</h3>
            <p class="page-subtitle">Bodega: {{ $bodega->nombrebodega }}</p>
        </div>
        <a href="{{ route('bodegas.show', $bodega->idbodega) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card" style="border-top: 3px solid var(--primary);">
        <div class="card-body p-4">
                    <!-- Alertas -->
                    <div id="alert-container">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Error:</strong> {!! session('error') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Éxito:</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>

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
                                    <div class="row align-items-start mb-3 row-producto border rounded-4 p-3 bg-white shadow-sm">
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
                                    <div class="card border-0 bg-brand-light">
                                        <div class="card-header rounded-top-4">
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
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmVentaModal" tabindex="-1" aria-labelledby="confirmVentaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-brand text-white">
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function mostrarAlertaError(mensaje) {
        const alertContainer = document.getElementById('alert-container');
        if (alertContainer) {
            alertContainer.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Error:</strong> ${mensaje}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            alertContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function limpiarAlertaError() {
        const alertContainer = document.getElementById('alert-container');
        if (alertContainer && alertContainer.querySelector('.alert-danger')) {
            alertContainer.innerHTML = '';
        }
    }

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

        cantidadInput.addEventListener('input', function() {
            const max = parseFloat(cantidadInput.max);
            const val = parseFloat(cantidadInput.value);
            if (!isNaN(max) && max > 0 && val > max) {
                mostrarAlertaError(`No se puede ingresar una cantidad (${val}) mayor al stock disponible (${max}).`);
                cantidadInput.value = max;
            } else {
                limpiarAlertaError();
            }
            calcularTotal();
        });
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
        
        // Validación HTML5 del formulario
        if (!document.getElementById('form-venta').reportValidity()) {
            return;
        }

        // Validación estricta del stock en cada fila
        let stockSuperado = false;
        document.querySelectorAll('.row-producto').forEach(row => {
            const select = row.querySelector('.producto-select');
            const cantidadInput = row.querySelector('.cantidad-input');
            const cantidad = parseFloat(cantidadInput.value) || 0;
            const max = parseFloat(cantidadInput.max);
            
            if (!isNaN(max) && max > 0 && cantidad > max) {
                const nombreProd = select.options[select.selectedIndex]?.text || 'Producto';
                mostrarAlertaError(`La cantidad de "${nombreProd}" (${cantidad}) no puede ser mayor al stock disponible (${max}).`);
                stockSuperado = true;
            }
        });

        if (stockSuperado) {
            return;
        }

        limpiarAlertaError();
        mostrarDetalleVenta();
        // Mover el modal al body para evitar problemas con overflow del layout
        const modalEl = document.getElementById('confirmVentaModal');
        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }
        var modal = new bootstrap.Modal(modalEl, { backdrop: true, keyboard: true });
        modal.show();
    });

    // Botón para confirmar y guardar
    document.getElementById('btn-confirmar-venta').addEventListener('click', function() {
        if (!document.getElementById('form-venta').reportValidity()) {
            return;
        }
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
            <div class="card border-0 bg-brand-light mt-4">
                <div class="card-header">
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