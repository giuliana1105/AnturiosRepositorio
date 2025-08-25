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
        <div class="mb-3">
            <label>Producto</label>
            <select name="producto_id" class="form-control" id="producto-select" required>
                <option value="">Seleccione un producto</option>
                @foreach($productos as $prod)
                    <option value="{{ $prod['codigo'] }}" data-stock="{{ $prod['stock'] }}" data-empaque="{{ $prod['tipoempaque'] }}">
                        {{ $prod['codigo'] }} - {{ $prod['nombre'] }} (Stock: {{ $prod['stock'] }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Cantidad</label>
            <input type="number" name="cantidad" class="form-control" id="cantidad-input" min="1" required>
            <small id="max-cantidad" class="form-text text-muted"></small>
        </div>
        <div class="mb-3">
            <label>Tipo Empaque</label>
            <input type="text" name="tipoempaque" class="form-control" id="empaque-input" value="Unidad" readonly>
        </div>
        <div class="mb-3">
            <label>Precio Unitario</label>
            <input type="number" name="precio_unitario" class="form-control" step="0.01" min="0.01" required>
        </div>
        <div class="mb-3">
            <label>Precio Total</label>
            <input type="number" name="precio_total" class="form-control" id="precio-total" readonly>
        </div>
        <button type="submit" class="btn btn-success">Registrar Venta</button>
        <a href="{{ route('bodegas.show', $bodega->idbodega) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productoSelect = document.getElementById('producto-select');
    const cantidadInput = document.getElementById('cantidad-input');
    const maxCantidad = document.getElementById('max-cantidad');
    const empaqueInput = document.getElementById('empaque-input');
    const precioUnitarioInput = document.querySelector('input[name="precio_unitario"]');
    const precioTotalInput = document.getElementById('precio-total');

    productoSelect.addEventListener('change', function() {
        const selected = productoSelect.options[productoSelect.selectedIndex];
        const stock = selected.getAttribute('data-stock');
        const empaque = selected.getAttribute('data-empaque');
        cantidadInput.max = stock;
        maxCantidad.textContent = stock ? `Máx: ${stock}` : '';
        empaqueInput.value = empaque || 'Unidad';
        cantidadInput.value = '';
        precioTotalInput.value = '';
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
});
</script>
@endsection