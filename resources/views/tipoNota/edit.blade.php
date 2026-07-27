@extends('layouts.app')
@php
    $cargo = auth()->user()->cargoNombre();
    $usuario = auth()->user();
    $empleado = $usuario->empleado ?? null;
    $bodegaAsignada = $empleado ? $empleado->bodega : null;
@endphp

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Editar Nota</h3>
            <p class="page-subtitle">Modificación de envío o devolución de productos</p>
        </div>
        <a href="{{ route('tipoNota.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <!-- Alertas -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><strong>Errores de validación:</strong>
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
            <i class="fas fa-check-circle me-2"></i><strong>Éxito:</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><strong>Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card" style="border-top: 3px solid var(--primary);">
        <div class="card-body p-4">
            <h6 class="fw-semibold mb-4" style="color: var(--foreground);">
                <i class="fas fa-file-signature me-2" style="color: var(--primary);"></i>Datos de la Nota
            </h6>

            <form action="{{ route('tipoNota.update', $tipoNota->codigo) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="codigo" class="form-label">Código de Nota</label>
                        <input type="text" name="codigo" id="codigo" class="form-control font-mono bg-light" value="{{ $tipoNota->codigo }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="tiponota" class="form-label">Tipo de Nota</label>
                        <select id="tiponota-select" name="tiponota" class="form-select" required>
                            <option value="ENVIO" {{ $tipoNota->tiponota == 'ENVIO' ? 'selected' : '' }}>Envío</option>
                            <option value="DEVOLUCION" {{ $tipoNota->tiponota == 'DEVOLUCION' ? 'selected' : '' }}>Devolución</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="idbodega" class="form-label">Bodega</label>
                        @if(in_array($cargo, ['Vendedor', 'Vendedor camión']))
                            <input type="text" class="form-control bg-light mb-1"
                                value="{{ $bodegaAsignada ? $bodegaAsignada->nombrebodega : '' }}"
                                readonly>
                            <input type="hidden" id="bodega-select" name="idbodega" value="{{ $bodegaAsignada ? $bodegaAsignada->idbodega : '' }}">
                        @else
                            <select id="bodega-select" name="idbodega" class="form-select" required>
                                @foreach ($bodegas as $bodega)
                                    <option value="{{ $bodega->idbodega }}" {{ $tipoNota->idbodega == $bodega->idbodega ? 'selected' : '' }}>
                                        {{ $bodega->nombrebodega }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label for="nro_identificacion" class="form-label">Solicitante</label>
                        @if(in_array($cargo, ['Vendedor', 'Vendedor camión']))
                            <input type="text" class="form-control bg-light"
                                value="{{ $empleado ? $empleado->nombreemp . ' ' . $empleado->apellidoemp : $usuario->name }}"
                                readonly>
                            <input type="hidden" name="nro_identificacion" value="{{ $empleado ? $empleado->nro_identificacion : '' }}">
                        @else
                            <select name="nro_identificacion" class="form-select" required>
                                @foreach ($empleados as $empleado_opt)
                                    <option value="{{ $empleado_opt->nro_identificacion }}" {{ $tipoNota->nro_identificacion == $empleado_opt->nro_identificacion ? 'selected' : '' }}>
                                        {{ $empleado_opt->nombreemp }} {{ $empleado_opt->apellidoemp }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

                <div class="mt-5 mb-3">
                    <h6 class="fw-semibold" style="color: var(--foreground);">
                        <i class="fas fa-boxes me-2" style="color: var(--primary);"></i>Productos a procesar
                    </h6>
                </div>
                
                <div class="p-4 rounded" style="background: var(--surface-hover); border: 1px solid var(--border-light);">
                    <div id="productos-container">
                        @foreach ($tipoNota->detalles as $detalle)
                            <div class="row row-producto g-3 align-items-end mb-3 pb-3 border-bottom">
                                <input type="hidden" name="detalle_ids[]" value="{{ $detalle->id }}">
                                <div class="col-md-4">
                                    <label for="codigoproducto[]" class="form-label font-sm">Producto</label>
                                    <select name="codigoproducto[]" class="form-select producto-select" required>
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
                                    <label for="cantidad[]" class="form-label font-sm">Cantidad</label>
                                    <input type="number" name="cantidad[]" class="form-control cantidad-input"
                                        value="{{ $detalle->cantidad }}"
                                        min="1"
                                        max="{{ optional($productos->firstWhere('codigo', $detalle->codigoproducto))->cantidad }}"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <label for="empaque[]" class="form-label font-sm">Tipo de Empaque</label>
                                    <input type="text" name="empaque[]" class="form-control empaque-input bg-light"
                                        value="{{ optional($productos->firstWhere('codigo', $detalle->codigoproducto))->tipoempaque }}"
                                        readonly>
                                </div>
                                <div class="col-md-2 d-flex gap-2 pb-1">
                                    <button type="button" class="btn btn-outline-danger btn-remove-producto px-3" title="Eliminar producto">
                                        <i class="fas fa-times pointer-events-none"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-outline-success btn-add-producto px-4 py-2">
                            <i class="fas fa-plus me-2"></i> Agregar producto
                        </button>
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-light);">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('tipoNota.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoNotaSelect = document.getElementById('tiponota-select');
    const bodegaSelect = document.getElementById('bodega-select');
    const productosContainer = document.getElementById('productos-container');
    let productosDisponibles = [];
    let tipoNotaInicial = tipoNotaSelect.value;
    let bodegaInicial = bodegaSelect ? bodegaSelect.value : null;

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
            const hiddenId = primeraFila.querySelector('input[name="detalle_ids[]"]');
            
            if (hiddenId) hiddenId.remove(); // Remove old id if it was there to treat as new if needed
            
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
        const bodegaId = tipoNota === 'DEVOLUCION' && bodegaSelect ? bodegaSelect.value : null;
        
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
        let bVal = bodegaSelect ? bodegaSelect.value : null;
        if (tipoNotaSelect.value === 'DEVOLUCION' && bVal) {
            cargarProductos(`/bodegas/${bVal}/productos`, null);
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
    if(bodegaSelect) {
        bodegaSelect.addEventListener('change', function() {
            // Si cambió la bodega, limpiar campos
            if (this.value !== bodegaInicial) {
                limpiarCamposProductos();
            }
            bodegaInicial = this.value;
            actualizarOpcionesProductos();
        });
    }

    // Ejecutar al cargar la página
    actualizarOpcionesProductos();

    document.querySelector('.btn-add-producto').addEventListener('click', function() {
        const rows = productosContainer.querySelectorAll('.row-producto');
        const lastRow = rows[rows.length - 1];
        const newRow = lastRow.cloneNode(true);

        // Limpiar valores del nuevo row
        newRow.querySelectorAll('input').forEach(input => {
            if (input.type !== 'hidden') {
                input.value = '';
                input.classList.remove('is-invalid');
            } else if (input.name === 'detalle_ids[]') {
                input.remove(); // No detail id for new rows
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
        if (e.target.closest('.btn-remove-producto')) {
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
            const bodegaId = tipoNota === 'DEVOLUCION' && bodegaSelect ? bodegaSelect.value : null;
            
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
                // message handled by alert
            } else {
                alert('La cantidad ingresada supera el stock disponible en uno o más productos.');
            }
        }
    });
});
</script>
@endsection