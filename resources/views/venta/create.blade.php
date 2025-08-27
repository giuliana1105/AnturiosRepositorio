@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Registrar Venta en {{ $bodega->nombrebodega }}</h3>
    <form method="POST" action="{{ route('venta.store', $bodega->idbodega) }}">
        @csrf
        <div class="mb-3">
            <label>Nro. venta</label>
            <input type="text" class="form-control" value="{{ $nroVenta }}" readonly>
        </div>
        <div class="mb-3">
            <label>Fecha</label>
            <input type="text" class="form-control" value="{{ now()->format('Y-m-d') }}" readonly>
        </div>
        <div class="mb-3">
            <label>Cliente</label>
            <input type="text" name="cliente" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Ciudad</label>
            <select name="ciudad" class="form-control" required>
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
                <option>Paján</option>
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
        <div class="mb-3">
            <label>Total venta</label>
            <input type="number" name="total_venta" class="form-control" id="total-venta" readonly>
        </div>
        <div class="mb-3">
            <label>Tipo de pago</label>
            <select name="tipo_pago" class="form-control" required>
                <option value="Efectivo">Efectivo</option>
                <option value="Transferencia">Transferencia</option>
                <option value="Crédito">Crédito</option>
                <option value="Cheque">Cheque</option>
            </select>
        </div>
        <div id="abonos-section" style="display:none;">
            <!-- <label class="mt-3">Abonos</label> -->
            <div id="abonos-container">
                <div class="row align-items-end mb-2 row-abono">
                    <div class="col-md-3">
                        <label>Abono</label>
                        <input type="number" name="abono[]" class="form-control abono-input" min="0" step="0.01" value="0">
                    </div>
                    <div class="col-md-3">
                        <label>Fecha</label>
                        <input type="text" name="fecha_abono[]" class="form-control fecha-abono-input" value="{{ now()->format('Y-m-d') }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label>Tipo de pago</label>
                        <select name="tipo_pago_abono[]" class="form-control">
                            <option value="Cheque">Cheque</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Transferencia">Transferencia</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success btn-add-abono">+</button>
                        <button type="button" class="btn btn-danger btn-remove-abono">-</button>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label>Saldo</label>
                <input type="number" name="saldo" class="form-control" id="saldo-venta" readonly>
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
            calcularTotalVenta(); // <-- recalcula el total al agregar
        }
        if (e.target.classList.contains('btn-remove-producto')) {
            const rows = document.querySelectorAll('.row-producto');
            if (rows.length > 1) {
                e.target.closest('.row-producto').remove();
                actualizarSelects();
                calcularTotalVenta(); // <-- recalcula el total al eliminar
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
});
</script>
@endsection