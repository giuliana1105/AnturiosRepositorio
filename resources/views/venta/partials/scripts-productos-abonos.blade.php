
<script>
document.addEventListener('DOMContentLoaded', function() {
    function actualizarSelects() {
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

    document.querySelectorAll('.row-producto').forEach(row => actualizarCampos(row));

    document.getElementById('productos-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-add-producto')) {
            const row = e.target.closest('.row-producto');
            const newRow = row.cloneNode(true);

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
        const totalVentaInput = document.getElementById('total-venta');
        if (totalVentaInput) {
            totalVentaInput.value = total.toFixed(2);
        }
    }
    document.getElementById('productos-container').addEventListener('input', calcularTotalVenta);
    document.getElementById('productos-container').addEventListener('change', calcularTotalVenta);

    // Sección de abonos
    const abonosSection = document.getElementById('abonos-section');
    if (abonosSection) abonosSection.style.display = 'none';

    document.querySelectorAll('.row-abono').forEach(row => {
        row.querySelector('.abono-input').addEventListener('input', function() {
            calcularSaldo();
        });
    });

    const tipoPagoSelect = document.querySelector('select[name="tipo_pago"]');
    const saldoVentaInput = document.getElementById('saldo-venta');
    const totalVentaInput = document.getElementById('total-venta');

    if (tipoPagoSelect) {
        tipoPagoSelect.addEventListener('change', function() {
            if (tipoPagoSelect.value === 'Crédito') {
                if (abonosSection) abonosSection.style.display = '';
                calcularSaldo();
            } else {
                if (abonosSection) abonosSection.style.display = 'none';
                if (saldoVentaInput) saldoVentaInput.value = '';
            }
        });
    }

    function calcularSaldo() {
        let totalVenta = parseFloat(totalVentaInput ? totalVentaInput.value : 0) || 0;
        let totalAbonos = 0;
        document.querySelectorAll('.abono-input').forEach(function(input) {
            totalAbonos += parseFloat(input.value) || 0;
        });
        if (saldoVentaInput) {
            saldoVentaInput.value = (totalVenta - totalAbonos).toFixed(2);
        }
    }

    document.querySelectorAll('.row-abono').forEach(row => {
        row.querySelector('.abono-input').addEventListener('input', calcularSaldo);
    });

    const abonosContainer = document.getElementById('abonos-container');
    if (abonosContainer) {
        abonosContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-add-abono')) {
                const row = e.target.closest('.row-abono');
                const newRow = row.cloneNode(true);

                newRow.querySelector('.abono-input').value = '0';
                newRow.querySelector('.fecha-abono-input').value = new Date().toISOString().slice(0,10);
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

        abonosContainer.addEventListener('input', calcularSaldo);
    }

    document.getElementById('productos-container').addEventListener('input', function() {
        if (tipoPagoSelect && tipoPagoSelect.value === 'Crédito') {
            calcularSaldo();
        }
    });
    document.getElementById('productos-container').addEventListener('change', function() {
        if (tipoPagoSelect && tipoPagoSelect.value === 'Crédito') {
            calcularSaldo();
        }
    });
});
</script>