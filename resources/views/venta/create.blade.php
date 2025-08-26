@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Registrar Venta en {{ $bodega->nombrebodega }}</h3>
    <form method="POST" action="{{ route('venta.store', $bodega->idbodega) }}">
        @csrf
        <div class="mb-3">
            <label>Fecha</label>
            <input type="text" class="form-control" value="{{ now()->format('Y-m-d H:i') }}" readonly>
        </div>
        <div id="productos-container">
            <div class="row align-items-end mb-3 row-producto">
                <div class="col-md-4">
                    <label>Producto</label>
                    <select name="producto_id[]" class="form-control producto-select" required>
                        <option value="">Seleccione un producto</option>
                        @foreach($productos as $prod)
                            <option value="{{ $prod['codigo'] }}" data-stock="{{ $prod['stock'] }}" data-empaque="{{ $prod['tipoempaque'] }}">
                                {{ $prod['codigo'] }} - {{ $prod['nombre'] }} (Stock: {{ $prod['stock'] }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Cantidad</label>
                    <input type="number" name="cantidad[]" class="form-control cantidad-input" min="1" required>
                    <small class="max-cantidad form-text text-muted"></small>
                </div>
                <div class="col-md-2">
                    <label>Tipo Empaque</label>
                    <input type="text" name="tipoempaque[]" class="form-control empaque-input" value="Unidad" readonly>
                </div>
                <div class="col-md-2">
                    <label>Precio Unitario</label>
                    <input type="number" name="precio_unitario[]" class="form-control precio-unitario-input" step="0.01" min="0.01" required>
                </div>
                <div class="col-md-2">
                    <label>Precio Total</label>
                    <input type="number" name="precio_total[]" class="form-control precio-total-input" readonly>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-add-producto">+</button>
                    <button type="button" class="btn btn-danger btn-remove-producto">-</button>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success mt-3">Registrar Venta</button>
        <a href="{{ route('bodegas.show', $bodega->idbodega) }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection

@section('scripts')
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
        }
        if (e.target.classList.contains('btn-remove-producto')) {
            const rows = document.querySelectorAll('.row-producto');
            if (rows.length > 1) {
                e.target.closest('.row-producto').remove();
                actualizarSelects();
            }
        }
    });

    // Al cambiar cualquier select, actualiza los disables
    document.getElementById('productos-container').addEventListener('change', function(e) {
        if (e.target.classList.contains('producto-select')) {
            actualizarSelects();
        }
    });
});
</script>
@endsection