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
                <div class="producto-row row mb-3">
                    <div class="col-md-4">
                        <label for="codigoproducto[]" class="form-label">Producto</label>
                        <select name="codigoproducto[]" class="form-control producto-select" required>
                            <option value="">Seleccione un producto</option>
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

            <button type="submit" class="btn btn-primary">Guardar Nota</button>
        </form>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoNotaSelect = document.getElementById('tiponota-select');
    const bodegaSelect = document.querySelector('select[name="idbodega"]');

    function cargarProductos(url) {
        fetch(url)
            .then(res => res.json())
            .then(productos => {
                document.querySelectorAll('.producto-select').forEach(select => {
                    select.innerHTML = '<option value="">Seleccione un producto</option>';
                    productos.forEach(prod => {
                        select.innerHTML += `<option value="${prod.codigo}" data-stock="${prod.cantidad ?? ''}">${prod.codigo} - ${prod.nombre}</option>`;
                    });
                });
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
});
</script>
@endsection
