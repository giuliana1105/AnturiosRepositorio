@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="text-center">Crear Nueva Nota</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tipoNota.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="tiponota" class="form-label">Tipo de Nota</label>
                <select name="tiponota" id="tiponota-select" class="form-control" required>
                    <option value="ENVIO">Envío</option>
                    <option value="DEVOLUCION">Devolución</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="nro_identificacion" class="form-label">Solicitante</label>
                <select name="nro_identificacion" class="form-control" required>
                    @foreach ($empleados as $empleado)
                        <option value="{{ $empleado->nro_identificacion }}">
                            {{ $empleado->nombreemp }} {{ $empleado->apellidoemp }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="productos-container">
                <div class="producto-row row mb-3">
                    <div class="col-md-4">
                        <label for="codigoproducto[]" class="form-label">Producto</label>
                        <select name="codigoproducto[]" class="form-control producto-select" required>
                            <option value="">Seleccione un producto</option>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->codigo }}" data-stock="{{ $producto->cantidad }}" data-tipoempaque="{{ $producto->tipoempaque }}">
                                    {{ $producto->codigo }} - {{ $producto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="cantidad[]" class="form-label">Cantidad</label>
                        <input type="number" name="cantidad[]" class="form-control cantidad-input" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <label for="tipoempaque[]" class="form-label">Tipo de Empaque</label>
                        <input type="text" name="tipoempaque[]" class="form-control tipoempaque-input" readonly>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-success add-producto me-2">+</button>
                        <button type="button" class="btn btn-danger remove-producto">x</button>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="idbodega" class="form-label">Bodega</label>
                <select name="idbodega" class="form-control" required>
                    @foreach ($bodegas as $bodega)
                        <option value="{{ $bodega->idbodega }}">{{ $bodega->nombrebodega }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Nota</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const productosContainer = document.getElementById('productos-container');
            const tipoNotaSelect = document.getElementById('tiponota-select');

            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('add-producto')) {
                    e.preventDefault();
                    agregarProducto();
                }

                if (e.target.classList.contains('remove-producto')) {
                    e.preventDefault();
                    if (document.querySelectorAll('.producto-row').length > 1) {
                        e.target.closest('.producto-row').remove();
                    }
                }
            });

            document.addEventListener('change', function (e) {
                if (e.target.classList.contains('producto-select')) {
                    actualizarDatosProducto(e.target);
                }
            });

            tipoNotaSelect.addEventListener('change', function () {
                actualizarValidacionCantidad();
            });

            function agregarProducto() {
                const firstRow = document.querySelector('.producto-row');
                const newRow = firstRow.cloneNode(true);

                newRow.querySelectorAll('select, input').forEach(input => {
                    if (input.tagName === 'SELECT') {
                        input.selectedIndex = 0;
                    } else {
                        input.value = '';
                    }
                });

                productosContainer.appendChild(newRow);
                actualizarValidacionCantidad();
            }

            function actualizarDatosProducto(selectElement) {
                let selectedOption = selectElement.options[selectElement.selectedIndex];
                let stock = selectedOption.getAttribute('data-stock') || 0;
                let tipoEmpaque = selectedOption.getAttribute('data-tipoempaque');

                let cantidadInput = selectElement.closest('.producto-row').querySelector('.cantidad-input');
                let tipoEmpaqueInput = selectElement.closest('.producto-row').querySelector('.tipoempaque-input');

                cantidadInput.setAttribute('max', stock);
                tipoEmpaqueInput.value = tipoEmpaque;

                actualizarValidacionCantidad();
            }

            function actualizarValidacionCantidad() {
                let tipoNota = tipoNotaSelect.value;
                document.querySelectorAll('.cantidad-input').forEach(input => {
                    let productoSelect = input.closest('.producto-row').querySelector('.producto-select');
                    let maxStock = parseInt(productoSelect.selectedOptions[0].getAttribute('data-stock')) || 0;

                    if (tipoNota === 'ENVIO') {
                        input.setAttribute('max', maxStock);
                    } else {
                        input.removeAttribute('max');
                    }
                });
            }

            document.addEventListener('input', function (e) {
                if (e.target.classList.contains('cantidad-input')) {
                    let tipoNota = tipoNotaSelect.value;
                    let maxStock = parseInt(e.target.getAttribute('max')) || 0;

                    if (tipoNota === 'ENVIO' && parseInt(e.target.value) > maxStock) {
                        alert('La cantidad ingresada supera el stock disponible.');
                        e.target.value = maxStock;
                    }
                }
            });
        });
    </script>
@endsection
