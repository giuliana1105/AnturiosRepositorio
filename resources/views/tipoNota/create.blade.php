@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="text-center">Crear Nueva Nota</h3>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

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
                <select id="tiponota-select" name="tiponota" class="form-control" required>
                    <option value="">Seleccione tipo</option>
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

            <div class="mb-3">
                <label for="idbodega" class="form-label">Bodega</label>
                <select name="idbodega" class="form-control" required>
                    @foreach ($bodegas as $bodega)
                        <option value="{{ $bodega->idbodega }}">{{ $bodega->nombrebodega }}</option>
                    @endforeach
                </select>
            </div>

            <div id="productos-container">
                <div class="row row-producto mb-3">
                    <div class="col-md-4">
                        <label for="codigoproducto[]" class="form-label">Producto</label>
                        <select name="codigoproducto[]" class="form-control producto-select" required>
                            <option value="">Seleccione un producto</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="empaque[]" class="form-label">Cantidad</label>
                        <input type="number" name="cantidad[]" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="empaque[]" class="form-label">Tipo de Empaque</label>
                        <input type="text" name="empaque[]" class="form-control empaque-input" readonly>
                    </div>
                    <div class="col-md-3 d-flex">
                        <button type="button" class="btn btn-success btn-add-producto me-2">+</button>
                        <button type="button" class="btn btn-danger btn-remove-producto">x</button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Nota</button>
        </form>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoNotaSelect = document.getElementById('tiponota-select');
    const bodegaSelect = document.querySelector('select[name="idbodega"]');

    function cargarProductos(url, selectToUpdate = null) {
        fetch(url)
            .then(res => res.json())
            .then(productos => {
                if (selectToUpdate) {
                    // Solo llena el select nuevo
                    selectToUpdate.innerHTML = '<option value="">Seleccione un producto</option>';
                    productos.forEach(prod => {
                        selectToUpdate.innerHTML += `<option value="${prod.codigo}" data-stock="${prod.cantidad ?? ''}" data-empaque="${prod.tipoempaque ?? ''}">${prod.codigo} - ${prod.nombre}</option>`;
                    });
                } else {
                    // Llena todos los selects
                    document.querySelectorAll('.producto-select').forEach(select => {
                        select.innerHTML = '<option value="">Seleccione un producto</option>';
                        productos.forEach(prod => {
                            select.innerHTML += `<option value="${prod.codigo}" data-stock="${prod.cantidad ?? ''}" data-empaque="${prod.tipoempaque ?? ''}">${prod.codigo} - ${prod.nombre}</option>`;
                        });
                    });
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
        }
    }

    tipoNotaSelect.addEventListener('change', actualizarOpcionesProductos);
    bodegaSelect.addEventListener('change', actualizarOpcionesProductos);

    // Ejecutar al cargar la página si ya hay valores seleccionados
    actualizarOpcionesProductos();

    // Agregar y quitar productos dinámicamente
    document.getElementById('productos-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-add-producto')) {
            const row = e.target.closest('.row-producto');
            const newRow = row.cloneNode(true);

            // Limpia los valores de los inputs/clones
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelector('.producto-select').innerHTML = '<option value="">Seleccione un producto</option>';

            row.parentNode.appendChild(newRow);

            // Llena solo el nuevo select
            if (tipoNotaSelect.value === 'DEVOLUCION' && bodegaSelect.value) {
                cargarProductos(`/bodegas/${bodegaSelect.value}/productos`, newRow.querySelector('.producto-select'));
            } else if (tipoNotaSelect.value === 'ENVIO') {
                cargarProductos(`/bodegas/master/productos`, newRow.querySelector('.producto-select'));
            }
        }
        if (e.target.classList.contains('btn-remove-producto')) {
            const rows = document.querySelectorAll('.row-producto');
            if (rows.length > 1) {
                e.target.closest('.row-producto').remove();
            }
        }
    });

    // Cuando se selecciona un producto, llena el empaque
    document.getElementById('productos-container').addEventListener('change', function(e) {
        if (e.target.classList.contains('producto-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const stock = selectedOption.getAttribute('data-stock');
            const empaque = selectedOption.getAttribute('data-empaque');
            const row = e.target.closest('.row-producto');
            const empaqueInput = row.querySelector('.empaque-input');
            const cantidadInput = row.querySelector('input[name="cantidad[]"]');
            empaqueInput.value = empaque ?? '';
            if (cantidadInput) {
                cantidadInput.max = stock ?? '';
                cantidadInput.value = '';
                cantidadInput.placeholder = stock ? `Máx: ${stock}` : '';
            }
        }
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        let valid = true;
        document.querySelectorAll('.row-producto').forEach(row => {
            const cantidadInput = row.querySelector('input[name="cantidad[]"]');
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
            alert('La cantidad ingresada supera el stock disponible.');
        }
    });
});
</script>
@endsection
