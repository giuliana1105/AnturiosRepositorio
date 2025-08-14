@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="text-center">Editar Nota</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tipoNota.update', $tipoNota->codigo) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="codigo" class="form-label">Código de Nota</label>
            <input type="text" name="codigo" class="form-control" value="{{ $tipoNota->codigo }}" readonly>
        </div>

        <div class="mb-3">
            <label for="tiponota" class="form-label">Tipo de Nota</label>
            <select id="tiponota-select" name="tiponota" class="form-control" required>
                <option value="ENVIO" {{ $tipoNota->tiponota == 'ENVIO' ? 'selected' : '' }}>Envío</option>
                <option value="DEVOLUCION" {{ $tipoNota->tiponota == 'DEVOLUCION' ? 'selected' : '' }}>Devolución</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="idbodega" class="form-label">Bodega</label>
            <select id="bodega-select" name="idbodega" class="form-control" required>
                @foreach ($bodegas as $bodega)
                    <option value="{{ $bodega->idbodega }}" {{ $tipoNota->idbodega == $bodega->idbodega ? 'selected' : '' }}>
                        {{ $bodega->nombrebodega }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="nro_identificacion" class="form-label">Solicitante</label>
            <select name="nro_identificacion" class="form-control" required>
                @foreach ($empleados as $empleado)
                    <option value="{{ $empleado->nro_identificacion }}" {{ $tipoNota->nro_identificacion == $empleado->nro_identificacion ? 'selected' : '' }}>
                        {{ $empleado->nombreemp }} {{ $empleado->apellidoemp }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="productos-container">
            @foreach ($tipoNota->detalles as $detalle)
                <div class="row row-producto mb-3">
                    <input type="hidden" name="detalle_ids[]" value="{{ $detalle->id }}">
                    <div class="col-md-4">
                        <label for="codigoproducto[]" class="form-label">Producto</label>
                        <select name="codigoproducto[]" class="form-control producto-select" required>
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
                    <div class="col-md-4">
                        <label for="cantidad[]" class="form-label">Cantidad</label>
                        <input type="number" name="cantidad[]" class="form-control cantidad-input"
                            value="{{ $detalle->cantidad }}"
                            min="1"
                            max="{{ optional($productos->firstWhere('codigo', $detalle->codigoproducto))->cantidad }}"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label for="empaque[]" class="form-label">Tipo de Empaque</label>
                        <input type="text" name="empaque[]" class="form-control empaque-input"
                            value="{{ optional($productos->firstWhere('codigo', $detalle->codigoproducto))->tipoempaque }}"
                            readonly>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-remove-producto">x</button>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-success btn-add-producto mb-3">+</button>

        <button type="submit" class="btn btn-primary">Actualizar Nota</button>
        <a href="{{ route('tipoNota.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoNotaSelect = document.getElementById('tiponota-select');
    const bodegaSelect = document.getElementById('bodega-select');
    const productosContainer = document.getElementById('productos-container');
    let productosDisponibles = [];

    function actualizarEmpaqueEnFilas() {
        document.querySelectorAll('.row-producto').forEach(row => {
            const select = row.querySelector('.producto-select');
            const empaqueInput = row.querySelector('.empaque-input');
            const cantidadInput = row.querySelector('.cantidad-input');
            if (select && select.value) {
                const selectedOption = select.options[select.selectedIndex];
                const stock = selectedOption.getAttribute('data-stock');
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

    function cargarProductos(url, callback = null) {
        fetch(url)
            .then(res => res.json())
            .then(productos => {
                productosDisponibles = productos;
                document.querySelectorAll('.producto-select').forEach(select => {
                    const selectedValue = select.value;
                    select.innerHTML = '<option value="">Seleccione un producto</option>';
                    let found = false;
                    productos.forEach(prod => {
                        const selected = selectedValue === prod.codigo ? 'selected' : '';
                        if (selected) found = true;
                        select.innerHTML += `<option value="${prod.codigo}" data-stock="${prod.cantidad ?? ''}" data-empaque="${prod.tipoempaque ?? ''}" ${selected}>${prod.codigo} - ${prod.nombre}</option>`;
                    });
                    if (!found) select.value = '';
                });
                actualizarEmpaqueEnFilas(); // <-- Llama aquí SIEMPRE
                if (callback) callback();
            });
    }

    function actualizarOpcionesProductos(callback = null) {
        if (tipoNotaSelect.value === 'DEVOLUCION' && bodegaSelect.value) {
            cargarProductos(`/bodegas/${bodegaSelect.value}/productos`, callback);
        } else if (tipoNotaSelect.value === 'ENVIO') {
            cargarProductos(`/bodegas/master/productos`, callback);
        } else {
            document.querySelectorAll('.producto-select').forEach(select => {
                select.innerHTML = '<option value="">Seleccione un producto</option>';
            });
            productosDisponibles = [];
            actualizarEmpaqueEnFilas();
        }
    }

    tipoNotaSelect.addEventListener('change', function() {
        actualizarOpcionesProductos();
    });
    bodegaSelect.addEventListener('change', function() {
        actualizarOpcionesProductos();
    });

    // Ejecutar al cargar la página si ya hay valores seleccionados
    actualizarOpcionesProductos();

    document.querySelector('.btn-add-producto').addEventListener('click', function() {
        const rows = productosContainer.querySelectorAll('.row-producto');
        const lastRow = rows[rows.length - 1];
        const newRow = lastRow.cloneNode(true);

        newRow.querySelectorAll('input').forEach(input => input.value = '');
        const select = newRow.querySelector('.producto-select');
        select.innerHTML = '<option value="">Seleccione un producto</option>';
        productosDisponibles.forEach(prod => {
            select.innerHTML += `<option value="${prod.codigo}" data-stock="${prod.cantidad ?? ''}" data-empaque="${prod.tipoempaque ?? ''}">${prod.codigo} - ${prod.nombre}</option>`;
        });
        newRow.querySelector('.empaque-input').value = '';
        newRow.querySelector('.cantidad-input').value = '';
        newRow.querySelector('.cantidad-input').removeAttribute('max');
        newRow.querySelector('.cantidad-input').removeAttribute('placeholder');

        productosContainer.appendChild(newRow);
        actualizarEmpaqueEnFilas();
    });

    productosContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-producto')) {
            const rows = productosContainer.querySelectorAll('.row-producto');
            if (rows.length > 1) {
                e.target.closest('.row-producto').remove();
                actualizarEmpaqueEnFilas();
            }
        }
    });

    productosContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('producto-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const stock = selectedOption.getAttribute('data-stock');
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
        }
    });
});
</script>
@endsection
