@extends('layouts.app')

@section('content')
@php
    $cargo = auth()->user()->cargoNombre();
@endphp

<div class="container">
    <h3 class="mb-4">Productos en {{ $bodega->nombrebodega }}</h3>

    <!-- Botones para mostrar cada sección -->
    <div class="mb-3">
        <button class="btn btn-outline-primary me-2" id="btn-enviados">Productos Enviados</button>
        <button class="btn btn-outline-warning me-2" id="btn-devueltos">Productos Devueltos</button>
        <button class="btn btn-outline-success" id="btn-stock">Productos en Bodega (Stock Actual)</button>
    </div>

    <!-- Sección: Productos Enviados -->
    <div id="section-enviados" style="display:none;">
        <h4>Productos Enviados</h4>
        <div class="row mb-3" id="filtros-enviados" style="display:none;">
            <div class="col-md-3">
                <input type="text" class="form-control" id="filtro-codigo-enviados" placeholder="Filtrar por código">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="filtro-nombre-enviados" placeholder="Filtrar por nombre">
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" id="filtro-fecha-inicio-enviados" placeholder="Fecha inicio">
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" id="filtro-fecha-fin-enviados" placeholder="Fecha fin">
            </div>
        </div>
        <table class="table table-bordered" id="tabla-enviados">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $item)
                    <tr>
                        <td>{{ $item->codigo }}</td>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->cantidad }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay productos enviados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Sección: Productos Devueltos -->
    <div id="section-devueltos" style="display:none;">
        <h4>Productos Devueltos</h4>
        <div class="row mb-3" id="filtros-devueltos" style="display:none;">
            <div class="col-md-3">
                <input type="text" class="form-control" id="filtro-codigo-devueltos" placeholder="Filtrar por código">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="filtro-nombre-devueltos" placeholder="Filtrar por nombre">
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" id="filtro-fecha-inicio-devueltos" placeholder="Fecha inicio">
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" id="filtro-fecha-fin-devueltos" placeholder="Fecha fin">
            </div>
        </div>
        <table class="table table-bordered" id="tabla-devueltos">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($devueltos as $item)
                    <tr>
                        <td>{{ $item->codigo }}</td>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->cantidad }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay productos devueltos</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Sección: Productos en Bodega (Stock Actual) -->
    <div id="section-stock" style="display:none;">
        <h4>Productos en Bodega (Stock Actual)</h4>
        <div class="row mb-3" id="filtros-stock" style="display:none;">
            <div class="col-md-6">
                <input type="text" class="form-control" id="filtro-codigo-stock" placeholder="Filtrar por código">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" id="filtro-nombre-stock" placeholder="Filtrar por nombre">
            </div>
        </div>
        <table class="table table-bordered" id="tabla-stock">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cantidad Actual</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productosEnBodega as $item)
                    <tr>
                        <td>{{ $item['codigo'] }}</td>
                        <td>{{ $item['nombre'] }}</td>
                        <td>{{ $item['descripcion'] }}</td>
                        <td>{{ $item['cantidad'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay productos en stock</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ route('home') }}" class="btn btn-secondary">Volver al inicio</a>
        @if(!in_array($cargo, ['Jefe de bodega']))
            <a href="{{ route('tipoNota.create') }}" class="btn btn-primary">Crear Nueva Nota</a>
        @endif
        @if(in_array($cargo, ['Administrador', 'Gerente', 'Vendedor camión']))
            <a href="{{ route('venta.create', $bodega->idbodega) }}" class="btn btn-warning">Registrar venta</a>
            <a href="{{ route('venta.index.bodega', $bodega->idbodega) }}" class="btn btn-info">Ver ventas</a>
        @endif
    </div>
</div>

<!-- Modal para mostrar los productos -->
<div class="modal fade" id="productosModal" tabindex="-1" aria-labelledby="productosModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productosModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="productosModalBody">
        <!-- Aquí se mostrará la tabla -->
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Oculta todas las secciones y filtros al cargar la página
    document.getElementById('section-enviados').style.display = 'none';
    document.getElementById('section-devueltos').style.display = 'none';
    document.getElementById('section-stock').style.display = 'none';
    document.getElementById('filtros-enviados').style.display = 'none';
    document.getElementById('filtros-devueltos').style.display = 'none';
    document.getElementById('filtros-stock').style.display = 'none';

    function toggleSection(sectionId, filtrosId) {
        const section = document.getElementById(sectionId);
        const filtros = document.getElementById(filtrosId);
        const isVisible = section.style.display === '' || section.style.display === 'block';
        // Oculta todas las secciones y filtros
        document.getElementById('section-enviados').style.display = 'none';
        document.getElementById('section-devueltos').style.display = 'none';
        document.getElementById('section-stock').style.display = 'none';
        document.getElementById('filtros-enviados').style.display = 'none';
        document.getElementById('filtros-devueltos').style.display = 'none';
        document.getElementById('filtros-stock').style.display = 'none';
        // Si estaba oculto, lo muestra; si estaba visible, lo oculta
        if (!isVisible) {
            section.style.display = '';
            filtros.style.display = '';
        }
    }

    document.getElementById('btn-enviados').addEventListener('click', function() {
        toggleSection('section-enviados', 'filtros-enviados');
    });
    document.getElementById('btn-devueltos').addEventListener('click', function() {
        toggleSection('section-devueltos', 'filtros-devueltos');
    });
    document.getElementById('btn-stock').addEventListener('click', function() {
        toggleSection('section-stock', 'filtros-stock');
    });

    // Filtro para cada tabla
    function addTableFilter(tablaId, codigoId, nombreId, fechaInicioId, fechaFinId, fechaColIndex = 3, useFecha = true) {
        const tabla = document.getElementById(tablaId);
        if (codigoId) document.getElementById(codigoId).addEventListener('input', filtrar);
        if (nombreId) document.getElementById(nombreId).addEventListener('input', filtrar);
        if (useFecha && fechaInicioId) document.getElementById(fechaInicioId).addEventListener('change', filtrar);
        if (useFecha && fechaFinId) document.getElementById(fechaFinId).addEventListener('change', filtrar);

        function filtrar() {
            const codigo = codigoId ? document.getElementById(codigoId).value.trim().toLowerCase() : '';
            const nombre = nombreId ? document.getElementById(nombreId).value.trim().toLowerCase() : '';
            const fechaInicio = useFecha && fechaInicioId ? document.getElementById(fechaInicioId).value : '';
            const fechaFin = useFecha && fechaFinId ? document.getElementById(fechaFinId).value : '';

            Array.from(tabla.querySelectorAll('tbody tr')).forEach(row => {
                let mostrar = true;
                const tds = row.querySelectorAll('td');
                const tdCodigo = tds[0]?.textContent.toLowerCase();
                const tdNombre = tds[1]?.textContent.toLowerCase();
                const tdFecha = useFecha ? tds[fechaColIndex]?.textContent : null;

                if (codigo && (!tdCodigo || !tdCodigo.includes(codigo))) mostrar = false;
                if (nombre && (!tdNombre || !tdNombre.includes(nombre))) mostrar = false;
                if (useFecha && fechaInicio && tdFecha) {
                    const fechaRow = tdFecha.split('/').reverse().join('-');
                    if (fechaRow < fechaInicio) mostrar = false;
                }
                if (useFecha && fechaFin && tdFecha) {
                    const fechaRow = tdFecha.split('/').reverse().join('-');
                    if (fechaRow > fechaFin) mostrar = false;
                }
                row.style.display = mostrar ? '' : 'none';
            });
        }
    }

    // Aplica los filtros a cada tabla
    addTableFilter('tabla-enviados', 'filtro-codigo-enviados', 'filtro-nombre-enviados', 'filtro-fecha-inicio-enviados', 'filtro-fecha-fin-enviados', 3, true);
    addTableFilter('tabla-devueltos', 'filtro-codigo-devueltos', 'filtro-nombre-devueltos', 'filtro-fecha-inicio-devueltos', 'filtro-fecha-fin-devueltos', 3, true);
    addTableFilter('tabla-stock', 'filtro-codigo-stock', 'filtro-nombre-stock', null, null, null, false);
});
</script>
@endsection