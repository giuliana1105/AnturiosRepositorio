@extends('layouts.app')
 @php
    $cargo = auth()->user()->cargoNombre();
    $usuario = auth()->user();
    $empleado = $usuario->empleado ?? null;
    $bodegaAsignada = $empleado ? $empleado->bodega : null;
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
                <a class="nav-link active text-info fw-bold mb-2" href="{{ route('tipoNota.index') }}">
                    <i class="fas fa-file-alt me-2"></i> Notas de Pedido
                </a>
                @if(!in_array($cargo, ['Vendedor camión', 'Vendedor']))
                <a class="nav-link text-dark mb-2" href="{{ route('productos.index') }}">
                    <i class="fas fa-cube me-2"></i> Productos
                </a>
                  
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
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 py-3 px-4 bg-white">
            <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width: 1000px;">
                <div class="card-header bg-info text-white rounded-top-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">
                            <i class="fas fa-edit me-2"></i> Editar Nota
                        </h3>
                        <a href="{{ route('tipoNota.index') }}" class="btn btn-light btn-sm rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Alertas -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Errores de validación:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Éxito:</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Error:</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('tipoNota.update', $tipoNota->codigo) }}" method="POST" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-md-6">
                            <label for="codigo" class="form-label fw-bold">
                                <i class="fas fa-barcode me-2 text-info"></i> Código de Nota
                            </label>
                            <input type="text" name="codigo" id="codigo" class="form-control rounded-pill" 
                                   value="{{ $tipoNota->codigo }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="tiponota" class="form-label fw-bold">
                                <i class="fas fa-tags me-2 text-info"></i> Tipo de Nota
                            </label>
                            <select id="tiponota-select" name="tiponota" class="form-select rounded-pill" required>
                                <option value="ENVIO" {{ $tipoNota->tiponota == 'ENVIO' ? 'selected' : '' }}>Envío</option>
                                <option value="DEVOLUCION" {{ $tipoNota->tiponota == 'DEVOLUCION' ? 'selected' : '' }}>Devolución</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="idbodega" class="form-label fw-bold">
                                <i class="fas fa-warehouse me-2 text-info"></i> Bodega
                            </label>
                            @if(in_array($cargo, ['Vendedor', 'Vendedor camión']))
                                <input type="text" class="form-control rounded-pill mb-1"
                                    value="{{ $bodegaAsignada ? $bodegaAsignada->nombrebodega : '' }}"
                                    readonly>
                                <input type="hidden" name="idbodega" value="{{ $bodegaAsignada ? $bodegaAsignada->idbodega : '' }}">
                            @else
                                <select id="bodega-select" name="idbodega" class="form-select rounded-pill" required>
                                    @foreach ($bodegas as $bodega)
                                        <option value="{{ $bodega->idbodega }}" {{ $tipoNota->idbodega == $bodega->idbodega ? 'selected' : '' }}>
                                            {{ $bodega->nombrebodega }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="nro_identificacion" class="form-label fw-bold">
                                <i class="fas fa-user-tie me-2 text-info"></i> Solicitante
                            </label>
                            @if(in_array($cargo, ['Vendedor', 'Vendedor camión']))
                                <input type="text" class="form-control rounded-pill"
                                    value="{{ $empleado ? $empleado->nombreemp . ' ' . $empleado->apellidoemp : $usuario->name }}"
                                    readonly>
                                <input type="hidden" name="nro_identificacion" value="{{ $empleado ? $empleado->nro_identificacion : '' }}">
                            @else
                                <select name="nro_identificacion" class="form-select rounded-pill" required>
                                    @foreach ($empleados as $empleado)
                                        <option value="{{ $empleado->nro_identificacion }}" {{ $tipoNota->nro_identificacion == $empleado->nro_identificacion ? 'selected' : '' }}>
                                            {{ $empleado->nombreemp }} {{ $empleado->apellidoemp }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <!-- Sección de Productos -->
                        <div class="col-12 mt-4">
                            <div class="card border-2 border-info bg-light bg-opacity-25">
                                <div class="card-header bg-info bg-opacity-10 border-0">
                                    <h5 class="mb-0 text-info fw-bold">
                                        <i class="fas fa-boxes me-2"></i> Productos
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div id="productos-container">
                                        @foreach ($tipoNota->detalles as $detalle)
                                            <div class="row row-producto mb-3 align-items-end p-3 bg-white rounded-3 shadow-sm border">
                                                <input type="hidden" name="detalle_ids[]" value="{{ $detalle->id }}">
                                                <div class="col-md-4">
                                                    <label for="codigoproducto[]" class="form-label fw-bold">
                                                        <i class="fas fa-cube me-1 text-secondary"></i> Producto
                                                    </label>
                                                    <select name="codigoproducto[]" class="form-select rounded-pill producto-select" required>
                                                        <option value="">Seleccione un producto</option>
                                                        @foreach ($productos as $producto)
                                                            <option value="{{ $producto->codigo }}"
                                                                data-stock="{{ $producto->cantidad }}"
                                                                data-empaque="{{ $producto->tipoempaque }}"
                                                                {{ $detalle->codigoproducto == $producto->codigo ? 'selected' : '' }}>
                                                                {{ $producto->codigo }} - {{ $producto->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="cantidad[]" class="form-label fw-bold">
                                                        <i class="fas fa-sort-numeric-up me-1 text-secondary"></i> Cantidad
                                                    </label>
                                                    <input type="number" name="cantidad[]" class="form-control rounded-pill cantidad-input"
                                                        value="{{ $detalle->cantidad }}"
                                                        min="1"
                                                        max="{{ optional($productos->firstWhere('codigo', $detalle->codigoproducto))->cantidad }}"
                                                        required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="empaque[]" class="form-label fw-bold">
                                                        <i class="fas fa-box me-1 text-secondary"></i> Tipo de Empaque
                                                    </label>
                                                    <input type="text" name="empaque[]" class="form-control rounded-pill empaque-input"
                                                        value="{{ optional($productos->firstWhere('codigo', $detalle->codigoproducto))->tipoempaque }}"
                                                        readonly>
                                                </div>
                                                <div class="col-md-2 d-flex gap-2 justify-content-center">
                                                    <button type="button" class="btn btn-outline-danger btn-remove-producto rounded-circle shadow-sm" style="width: 38px; height: 38px;" title="Eliminar producto">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Botón para agregar productos -->
                                    <div class="text-center mt-3">
                                        <button type="button" class="btn btn-outline-success btn-add-producto rounded-pill px-4 py-2">
                                            <i class="fas fa-plus me-2"></i> Agregar producto
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-info text-white fw-bold rounded-pill px-5 py-3">
                                <i class="fas fa-save me-2"></i> Actualizar Nota
                            </button>
                            <a href="{{ route('tipoNota.index') }}" class="btn btn-secondary rounded-pill px-5 py-3 ms-3">
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
.btn-outline-success {
    border-width: 2px;
    transition: all 0.3s ease;
}
.btn-outline-success:hover, .btn-outline-success:focus {
    background-color: #43a047;
    color: #fff;
    border-color: #43a047;
    transform: scale(1.05);
}
.btn-outline-danger {
    border-width: 2px;
    transition: all 0.3s ease;
}
.btn-outline-danger:hover, .btn-outline-danger:focus {
    background-color: #e53935;
    color: #fff;
    border-color: #e53935;
    transform: scale(1.1);
}
.rounded-pill {
    border-radius: 50rem !important;
}
.form-control, .form-select {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}
.form-control:focus, .form-select:focus {
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
.row-producto {
    transition: all 0.3s ease;
}
.row-producto:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
}
#productos-container .card {
    border-radius: 1rem;
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
    .row-producto {
        flex-direction: column;
    }
    .row-producto .col-md-2 {
        display: flex;
        justify-content: center;
        margin-top: 15px;
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
@parent
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoNotaSelect = document.getElementById('tiponota-select');
    const bodegaSelect = document.getElementById('bodega-select');
    const productosContainer = document.getElementById('productos-container');
    let productosDisponibles = [];
    let tipoNotaInicial = tipoNotaSelect.value;
    let bodegaInicial = bodegaSelect.value;

    // Función para obtener productos ya seleccionados
    function getProductosSeleccionados() {
        const seleccionados = [];
        document.querySelectorAll('.producto-select').forEach(select => {
            if (select.value) {
                seleccionados.push(select.value);
            }
        });
        return seleccionados;
    }

    // Función para limpiar todos los campos de productos y dejar solo una fila
    function limpiarCamposProductos() {
        const todasLasFilas = document.querySelectorAll('.row-producto');
        
        // Mantener solo la primera fila
        const primeraFila = todasLasFilas[0];
        
        // Eliminar todas las demás filas
        for (let i = 1; i < todasLasFilas.length; i++) {
            todasLasFilas[i].remove();
        }
        
        // Limpiar la primera fila
        if (primeraFila) {
            const select = primeraFila.querySelector('.producto-select');
            const empaqueInput = primeraFila.querySelector('.empaque-input');
            const cantidadInput = primeraFila.querySelector('.cantidad-input');
            
            if (select) {
                select.innerHTML = '<option value="">Seleccione un producto</option>';
                select.value = '';
            }
            if (empaqueInput) {
                empaqueInput.value = '';
            }
            if (cantidadInput) {
                cantidadInput.value = '';
                cantidadInput.max = '';
                cantidadInput.placeholder = '';
            }
        }
    }

    // Función para actualizar las opciones de todos los selects evitando duplicados
    function actualizarOpcionesEnSelects() {
        const productosSeleccionados = getProductosSeleccionados();
        
        document.querySelectorAll('.producto-select').forEach(select => {
            const valorActual = select.value;
            select.innerHTML = '<option value="">Seleccione un producto</option>';
            
            productosDisponibles.forEach(prod => {
                // Solo mostrar el producto si no está seleccionado en otro select O si es el valor actual de este select
                if (!productosSeleccionados.includes(prod.codigo) || prod.codigo === valorActual) {
                    let optionHTML = `<option value="${prod.codigo}" data-stock="${prod.cantidad ?? ''}" data-empaque="${prod.tipoempaque ?? ''}"`;
                    
                    // Agregar stocks por bodega si existen
                    if (prod.stocks_por_bodega) {
                        prod.stocks_por_bodega.forEach(stockBodega => {
                            optionHTML += ` data-stock-bodega-${stockBodega.idbodega}="${stockBodega.cantidad}"`;
                        });
                    }
                    
                    optionHTML += `>${prod.codigo} - ${prod.nombre}</option>`;
                    select.innerHTML += optionHTML;
                }
            });
            
            // Restaurar el valor seleccionado
            if (valorActual) {
                select.value = valorActual;
            }
        });
    }

    function actualizarEmpaqueEnFilas() {
        const tipoNota = tipoNotaSelect.value;
        const bodegaId = tipoNota === 'DEVOLUCION' ? bodegaSelect.value : null;
        
        document.querySelectorAll('.row-producto').forEach(row => {
            const select = row.querySelector('.producto-select');
            const empaqueInput = row.querySelector('.empaque-input');
            const cantidadInput = row.querySelector('.cantidad-input');
            
            if (select && select.value) {
                const selectedOption = select.options[select.selectedIndex];
                
                let stock;
                if (bodegaId) {
                    stock = selectedOption.getAttribute(`data-stock-bodega-${bodegaId}`) || 
                           selectedOption.getAttribute('data-stock');
                } else {
                    stock = selectedOption.getAttribute('data-stock');
                }
                
                const empaque = selectedOption.getAttribute('data-empaque');
                empaqueInput.value = empaque ?? '';
                
                if (cantidadInput) {
                    cantidadInput.max = stock ?? '';
                    cantidadInput.placeholder = stock ? `Máx: ${stock}` : '';
                }
            } else {
                empaqueInput.value = '';
                if (cantidadInput) {
                    cantidadInput.max = '';
                    cantidadInput.placeholder = '';
                }
            }
        });
    }

    function cargarProductos(url, selectToUpdate = null) {
        fetch(url)
            .then(res => res.json())
            .then(productos => {
                productosDisponibles = productos;
                
                if (selectToUpdate) {
                    // Solo actualiza el select específico (para nuevas filas)
                    const productosSeleccionados = getProductosSeleccionados();
                    selectToUpdate.innerHTML = '<option value="">Seleccione un producto</option>';
                    productos.forEach(prod => {
                        // Solo mostrar productos no seleccionados
                        if (!productosSeleccionados.includes(prod.codigo)) {
                            let optionHTML = `<option value="${prod.codigo}" data-stock="${prod.cantidad ?? ''}" data-empaque="${prod.tipoempaque ?? ''}"`;
                            
                            if (prod.stocks_por_bodega) {
                                prod.stocks_por_bodega.forEach(stockBodega => {
                                    optionHTML += ` data-stock-bodega-${stockBodega.idbodega}="${stockBodega.cantidad}"`;
                                });
                            }
                            
                            optionHTML += `>${prod.codigo} - ${prod.nombre}</option>`;
                            selectToUpdate.innerHTML += optionHTML;
                        }
                    });
                } else {
                    // Actualiza todos los selects
                    actualizarOpcionesEnSelects();
                    actualizarEmpaqueEnFilas();
                }
            });
    }

    function actualizarOpcionesProductos(callback = null) {
        if (tipoNotaSelect.value === 'DEVOLUCION' && bodegaSelect.value) {
            cargarProductos(`/bodegas/${bodegaSelect.value}/productos`, null);
        } else if (tipoNotaSelect.value === 'ENVIO') {
            cargarProductos(`/bodegas/master/productos`, null);
        } else {
            productosDisponibles = [];
            limpiarCamposProductos();
        }
        if (callback) callback();
    }

    // Detectar cambios en tipo de nota
    tipoNotaSelect.addEventListener('change', function() {
        // Si cambió el tipo de nota, limpiar campos
        if (this.value !== tipoNotaInicial) {
            limpiarCamposProductos();
        }
        tipoNotaInicial = this.value;
        actualizarOpcionesProductos();
    });

    // Detectar cambios en bodega
    bodegaSelect.addEventListener('change', function() {
        // Si cambió la bodega, limpiar campos
        if (this.value !== bodegaInicial) {
            limpiarCamposProductos();
        }
        bodegaInicial = this.value;
        actualizarOpcionesProductos();
    });

    // Ejecutar al cargar la página si ya hay valores seleccionados
    actualizarOpcionesProductos();

    document.querySelector('.btn-add-producto').addEventListener('click', function() {
        const rows = productosContainer.querySelectorAll('.row-producto');
        const lastRow = rows[rows.length - 1];
        const newRow = lastRow.cloneNode(true);

        // Limpiar valores del nuevo row
        newRow.querySelectorAll('input').forEach(input => {
            if (input.type !== 'hidden') {
                input.value = '';
            }
        });

        const select = newRow.querySelector('.producto-select');
        select.innerHTML = '<option value="">Seleccione un producto</option>';
        
        // Agregar solo productos no seleccionados
        const productosSeleccionados = getProductosSeleccionados();
        productosDisponibles.forEach(prod => {
            if (!productosSeleccionados.includes(prod.codigo)) {
                let optionHTML = `<option value="${prod.codigo}" data-stock="${prod.cantidad ?? ''}" data-empaque="${prod.tipoempaque ?? ''}"`;
                
                if (prod.stocks_por_bodega) {
                    prod.stocks_por_bodega.forEach(stockBodega => {
                        optionHTML += ` data-stock-bodega-${stockBodega.idbodega}="${stockBodega.cantidad}"`;
                    });
                }
                
                optionHTML += `>${prod.codigo} - ${prod.nombre}</option>`;
                select.innerHTML += optionHTML;
            }
        });

        newRow.querySelector('.empaque-input').value = '';
        const cantidadInput = newRow.querySelector('.cantidad-input');
        cantidadInput.value = '';
        cantidadInput.removeAttribute('max');
        cantidadInput.removeAttribute('placeholder');

        productosContainer.appendChild(newRow);
    });

    productosContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-producto')) {
            const rows = productosContainer.querySelectorAll('.row-producto');
            if (rows.length > 1) {
                e.target.closest('.row-producto').remove();
                // Actualizar opciones después de eliminar una fila
                actualizarOpcionesEnSelects();
                actualizarEmpaqueEnFilas();
            }
        }
    });

    productosContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('producto-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const tipoNota = tipoNotaSelect.value;
            const bodegaId = tipoNota === 'DEVOLUCION' ? bodegaSelect.value : null;
            
            let stock;
            if (bodegaId) {
                stock = selectedOption.getAttribute(`data-stock-bodega-${bodegaId}`) || 
                       selectedOption.getAttribute('data-stock');
            } else {
                stock = selectedOption.getAttribute('data-stock');
            }
            
            const empaque = selectedOption.getAttribute('data-empaque');
            const row = e.target.closest('.row-producto');
            const empaqueInput = row.querySelector('.empaque-input');
            const cantidadInput = row.querySelector('.cantidad-input');
            
            empaqueInput.value = empaque ?? '';
            if (cantidadInput) {
                cantidadInput.max = stock ?? '';
                cantidadInput.value = '';
                cantidadInput.placeholder = stock ? `Máx: ${stock}` : '';
            }

            // Actualizar opciones en todos los selects para evitar duplicados
            actualizarOpcionesEnSelects();
        }
    });

    // Validación del formulario
    document.querySelector('form').addEventListener('submit', function(e) {
        let valid = true;
        const productosSeleccionados = [];
        
        // Verificar productos duplicados
        document.querySelectorAll('.producto-select').forEach(select => {
            if (select.value) {
                if (productosSeleccionados.includes(select.value)) {
                    valid = false;
                    alert('No se pueden seleccionar productos duplicados.');
                    return;
                }
                productosSeleccionados.push(select.value);
            }
        });

        // Verificar cantidades máximas
        document.querySelectorAll('.row-producto').forEach(row => {
            const cantidadInput = row.querySelector('.cantidad-input');
            const max = parseInt(cantidadInput.max, 10);
            const val = parseInt(cantidadInput.value, 10);
            if (max && val > max) {
                valid = false;
                cantidadInput.classList.add('is-invalid');
            } else {
                cantidadInput.classList.remove('is-invalid');
            }
        });

        if (!valid) {
            e.preventDefault();
            if (productosSeleccionados.length !== new Set(productosSeleccionados).size) {
                alert('No se pueden seleccionar productos duplicados.');
            } else {
                alert('La cantidad ingresada supera el stock disponible.');
            }
        }
    });
});
</script>
@endsection