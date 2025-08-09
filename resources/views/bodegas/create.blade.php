@extends('layouts.app')
@section('content')
<div class="row">
    <section class="content">
        <div class="col-md-8 col-md-offset-2">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Error!</strong> Revise los campos obligatorios.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Nueva Bodega</h3>
                </div>
                <div class="panel-body">
                    <div class="table-container">
                        <form method="POST" action="{{ route('bodegas.store') }}" role="form">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="idbodega" id="idbodega" class="form-control input-sm" placeholder="Código de la Bodega">
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="nombrebodega" id="nombrebodega" class="form-control input-sm" placeholder="Nombre de la Bodega">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <input type="submit" value="Guardar" class="btn btn-success btn-block">
                                    <a href="{{ route('bodegas.index') }}" class="btn btn-info btn-block">Atrás</a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
    
<select name="codigoproducto[]" class="form-control producto-select" required>
    <!-- Las opciones se llenan por JS -->
</select>
@endsection

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
        }
    }

    tipoNotaSelect.addEventListener('change', actualizarOpcionesProductos);
    bodegaSelect.addEventListener('change', actualizarOpcionesProductos);

    // Ejecutar al cargar la página si ya hay valores seleccionados
    actualizarOpcionesProductos();
});
</script>
