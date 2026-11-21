@extends('layouts.app')
@php
    $cargo = auth()->user()->cargoNombre();
    $usuario = auth()->user();
    $empleado = $usuario->empleado ?? null;
    $bodegaAsignada = $empleado ? $empleado->bodega : null;
@endphp

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de la página -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Crear Nueva Nota de Pedido</h3>
            <p class="page-subtitle mb-0 text-muted">Registro formal de solicitud de envío o devolución de anturios</p>
        </div>
        <a href="{{ route('tipoNota.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver a Notas
        </a>
    </div>

    <!-- Alertas -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><strong>Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
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
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i><strong>Éxito:</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4" style="max-width: 960px; margin: 0 auto; border-top: 3px solid var(--primary) !important;">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                    <i class="fas fa-clipboard-list fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Información de la Solicitud</h5>
                    <small class="text-muted">Define el tipo de movimiento, solicitante y bodega receptora.</small>
                </div>
            </div>

            <form action="{{ route('tipoNota.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="tiponota" class="form-label">Tipo de Nota</label>
                        <select id="tiponota-select" name="tiponota" class="form-select" required>
                            <option value="">Seleccione tipo</option>
                            <option value="ENVIO">Envío</option>
                            <option value="DEVOLUCION">Devolución</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="nro_identificacion" class="form-label">Solicitante</label>
                        @if(in_array($cargo, ['Vendedor', 'Vendedor camión']))
                            <input type="text" class="form-control bg-light" 
                                value="{{ $empleado ? $empleado->nombreemp . ' ' . $empleado->apellidoemp : $usuario->name }}" 
                                readonly>
                            <input type="hidden" name="nro_identificacion" 
                                value="{{ $empleado ? $empleado->nro_identificacion : '' }}">
                        @else
                            <select name="nro_identificacion" class="form-select" required>
                                <option value="">Seleccione solicitante</option>
                                @foreach ($empleados as $empleado_opt)
                                    <option value="{{ $empleado_opt->nro_identificacion }}">
                                        {{ $empleado_opt->nombreemp }} {{ $empleado_opt->apellidoemp }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="col-12">
                        <label for="idbodega" class="form-label">Bodega</label>
                        @if(in_array($cargo, ['Vendedor', 'Vendedor camión']))
                            <input type="text" class="form-control bg-light mb-1"
                                value="{{ $bodegaAsignada ? $bodegaAsignada->nombrebodega : '' }}"
                                readonly>
                            <select name="idbodega" class="form-select d-none" tabindex="-1" aria-hidden="true">
                                <option value="{{ $bodegaAsignada ? $bodegaAsignada->idbodega : '' }}" selected>
                                    {{ $bodegaAsignada ? $bodegaAsignada->nombrebodega : '' }}
                                </option>
                            </select>
                        @else
                            <select name="idbodega" class="form-select" required>
                                <option value="">Seleccione bodega</option>
                                @foreach ($bodegas as $bodega)
                                    <option value="{{ $bodega->idbodega }}">{{ $bodega->nombrebodega }}</option>
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
                        <div class="row row-producto g-3 align-items-end mb-3 pb-3 border-bottom">
                            <div class="col-md-4">
                                <label for="codigoproducto[]" class="form-label font-sm">Producto</label>
                                <select name="codigoproducto[]" class="form-select producto-select" required>
                                    <option value="">Seleccione un producto</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="cantidad[]" class="form-label font-sm">Cantidad</label>
                                <input type="number" name="cantidad[]" class="form-control cantidad-input" min="1" required
                                    oninvalid="this.setCustomValidity(this.validity.rangeOverflow ? ('El valor debe ser menor o igual a ' + this.max) : (this.validity.rangeUnderflow ? 'El valor debe ser mayor o igual a 1' : (this.validity.valueMissing ? 'Ingrese una cantidad' : '')))"
                                    oninput="this.setCustomValidity('')">
                                <div class="stock-feedback font-mono text-muted mt-1" style="font-size: 11.5px;"></div>
                            </div>
                            <div class="col-md-3">
                                <label for="empaque[]" class="form-label font-sm">Tipo de Empaque</label>
                                <input type="text" name="empaque[]" class="form-control empaque-input bg-light" readonly>
                            </div>
                            <div class="col-md-2 d-flex gap-2 pb-1">
                                <button type="button" class="btn btn-outline-success btn-add-producto px-3" title="Agregar producto">
                                    <i class="fas fa-plus pointer-events-none"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-remove-producto px-3" title="Eliminar producto">
                                    <i class="fas fa-times pointer-events-none"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-light);">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('tipoNota.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save me-1"></i> Guardar Nota
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const tipoNotaSelect = document.getElementById('tiponota-select');
    const bodegaSelect = document.querySelector('select[name="idbodega"]');
    let productosDisponibles = [];

    function mostrarAlertaStock(max) {
        if (window.Swal) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                title: `El valor debe ser menor o igual a ${max}`,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    }

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

    // Función para actualizar las opciones de todos los selects
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
                }
            });
    }

    function actualizarOpcionesProductos() {
        if (tipoNotaSelect.value === 'DEVOLUCION' && bodegaSelect.value) {
            cargarProductos(`/bodegas/${bodegaSelect.value}/productos`);
        } else if (tipoNotaSelect.value === 'ENVIO') {
            cargarProductos(`/bodegas/master/productos`);
        } else {
            document.querySelectorAll('.producto-select').forEach(select => {
                select.innerHTML = '<option value="">Seleccione un producto</option>';
            });
            productosDisponibles = [];
        }
    }

    tipoNotaSelect.addEventListener('change', actualizarOpcionesProductos);
    if(bodegaSelect) bodegaSelect.addEventListener('change', actualizarOpcionesProductos);

    // Ejecutar al cargar la página si ya hay valores seleccionados
    actualizarOpcionesProductos();

    // Agregar y quitar productos dinámicamente
    document.getElementById('productos-container').addEventListener('click', function(e) {
        if (e.target.closest('.btn-add-producto')) {
            const row = e.target.closest('.row-producto');
            const newRow = row.cloneNode(true);

            // Limpia los valores de los inputs/clones
            newRow.querySelectorAll('input').forEach(input => {
                input.value = '';
                input.removeAttribute('max');
                input.removeAttribute('placeholder');
                input.classList.remove('is-invalid');
                input.setCustomValidity('');
            });
            const feedback = newRow.querySelector('.stock-feedback');
            if (feedback) feedback.innerHTML = '';
            newRow.querySelector('.producto-select').innerHTML = '<option value="">Seleccione un producto</option>';

            row.parentNode.appendChild(newRow);

            // Llena solo el nuevo select con productos no seleccionados
            if (tipoNotaSelect.value === 'DEVOLUCION' && bodegaSelect.value) {
                cargarProductos(`/bodegas/${bodegaSelect.value}/productos`, newRow.querySelector('.producto-select'));
            } else if (tipoNotaSelect.value === 'ENVIO') {
                cargarProductos(`/bodegas/master/productos`, newRow.querySelector('.producto-select'));
            }
        }
        if (e.target.closest('.btn-remove-producto')) {
            const rows = document.querySelectorAll('.row-producto');
            if (rows.length > 1) {
                e.target.closest('.row-producto').remove();
                // Actualizar opciones después de eliminar una fila
                actualizarOpcionesEnSelects();
            }
        }
    });

    // Cuando se selecciona un producto, actualizar empaque, stock máximo y opciones de otros selects
    document.getElementById('productos-container').addEventListener('change', function(e) {
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
            const cantidadInput = row.querySelector('input[name="cantidad[]"], .cantidad-input');
            const stockFeedback = row.querySelector('.stock-feedback');
            
            empaqueInput.value = empaque ?? '';
            if (cantidadInput) {
                if (stock !== null && stock !== undefined && stock !== '') {
                    cantidadInput.max = stock;
                    cantidadInput.placeholder = `Máx: ${stock}`;
                    if (stockFeedback) {
                        stockFeedback.innerHTML = `<i class="fas fa-boxes me-1 text-primary"></i>Stock disponible: <strong class="text-dark">${stock}</strong>`;
                    }
                } else {
                    cantidadInput.removeAttribute('max');
                    cantidadInput.placeholder = '';
                    if (stockFeedback) {
                        stockFeedback.innerHTML = '';
                    }
                }
                cantidadInput.value = '';
                cantidadInput.setCustomValidity('');
                cantidadInput.classList.remove('is-invalid');
            }

            // Actualizar opciones en todos los selects para ocultar/mostrar productos
            actualizarOpcionesEnSelects();
        }
    });

    // Restricción en tiempo real mientras el usuario escribe la cantidad
    document.getElementById('productos-container').addEventListener('input', function(e) {
        if (e.target.matches('input[name="cantidad[]"], .cantidad-input')) {
            const input = e.target;
            input.setCustomValidity('');
            
            const max = parseInt(input.max, 10);
            if (!isNaN(max) && max > 0 && input.value !== '') {
                const val = parseInt(input.value, 10);
                if (val > max) {
                    input.value = max; // Clampa inmediatamente al valor máximo permitido
                    input.classList.add('is-invalid');
                    setTimeout(() => input.classList.remove('is-invalid'), 1800);
                    mostrarAlertaStock(max);
                } else if (val < 1) {
                    input.value = 1;
                }
            }
        }
    });

    // Validación al enviar el formulario
    document.querySelector('form').addEventListener('submit', function(e) {
        let valid = true;
        const productosSeleccionados = [];
        
        // Verificar productos duplicados
        document.querySelectorAll('.producto-select').forEach(select => {
            if (select.value) {
                if (productosSeleccionados.includes(select.value)) {
                    valid = false;
                }
                productosSeleccionados.push(select.value);
            }
        });

        if (productosSeleccionados.length !== new Set(productosSeleccionados).size) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Productos duplicados',
                text: 'No se pueden seleccionar productos duplicados en la misma solicitud.',
                confirmButtonColor: '#0097a7',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        // Verificar cantidades máximas
        let excesoStock = false;
        let maxPermitido = null;
        document.querySelectorAll('.row-producto').forEach(row => {
            const cantidadInput = row.querySelector('input[name="cantidad[]"], .cantidad-input');
            if (!cantidadInput) return;
            const max = parseInt(cantidadInput.max, 10);
            const val = parseInt(cantidadInput.value, 10);
            if (!isNaN(max) && max > 0 && val > max) {
                valid = false;
                excesoStock = true;
                maxPermitido = max;
                cantidadInput.value = max;
                cantidadInput.classList.add('is-invalid');
            } else {
                cantidadInput.classList.remove('is-invalid');
            }
        });

        if (excesoStock) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Stock insuficiente',
                text: `El valor debe ser menor o igual a ${maxPermitido}`,
                confirmButtonColor: '#0097a7',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
});
</script>
@endsection