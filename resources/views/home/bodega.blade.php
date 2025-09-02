@extends('layouts.app')

@section('content')
@php
    $cargo = auth()->user()->cargoNombre();
@endphp

<div class="container-fluid p-0 m-0">
    <div class="row g-0 min-vh-100">
        <!-- Sidebar Navigation -->
        <div class="col-md-2 bg-light py-3 px-3">
            <div class="text-center mb-4">
                <div class="bg-info rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-user text-white fa-lg"></i>
                </div>
                <div class="mt-2">
                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                    <div class="text-muted small">{{ auth()->user()->email }}</div>
                    <div class="text-secondary small">{{ auth()->user()->cargoNombre() }}</div>
                </div>
            </div>

            <div class="mb-3">
                <small class="text-uppercase text-muted fw-bold">NAVEGACIÓN PRINCIPAL</small>
            </div>

            <nav class="nav flex-column">
                <a class="nav-link text-dark mb-2" href="{{ route('home') }}">
                    <i class="fas fa-th-large me-2"></i> Dashboard
                </a>
                <!-- <a class="nav-link text-dark mb-2" href="{{ route('productos.index') }}">
                    <i class="fas fa-cube me-2"></i> Productos
                </a>
                @if($cargo !== 'Jefe de bodega')
                <a class="nav-link text-dark mb-2" href="{{ route('empleados.index') }}">
                    <i class="fas fa-users me-2"></i> Empleados
                </a>
                @endif
                <a class="nav-link text-dark mb-2" href="#">
                    <i class="fas fa-box me-2"></i> Gestión de Productos
                    <i class="fas fa-plus-circle ms-auto"></i>
                </a>
                <a class="nav-link text-dark mb-2" href="#">
                    <i class="fas fa-clipboard-list me-2"></i> Gestión de Existencias
                    <i class="fas fa-plus-circle ms-auto"></i>
                </a>
                <a class="nav-link text-dark mb-2" href="#">
                    <i class="fas fa-users-cog me-2"></i> Gestión de usuarios
                    <i class="fas fa-plus-circle ms-auto"></i>
                </a>
                @if($cargo !== 'Jefe de bodega')
                <a class="nav-link active text-info fw-bold mb-2" href="{{ route('bodegas.index') }}">
                    <i class="fas fa-warehouse me-2"></i> Bodegas
                </a>
                @endif
                <a class="nav-link text-dark mb-2" href="#">
                    <i class="fas fa-chart-bar me-2"></i> Reportes
                </a>
                <a class="nav-link text-dark mb-2" href="#">
                    <i class="fas fa-cog me-2"></i> Configuración
                    <i class="fas fa-plus-circle ms-auto"></i>
                </a> -->
                <a class="nav-link text-dark mb-2" href="{{ route('home') }}">
                    <i class="fas fa-home me-2"></i> Home
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link text-dark btn btn-link mb-2" style="text-align:left;">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar sesión
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 py-3 px-4 bg-white">
            <h3 class="mb-4 text-dark">Productos en {{ $bodega->nombrebodega }}</h3>

            <!-- Botones para mostrar cada sección -->
            <div class="mb-4">
                <button class="btn btn-outline-primary me-2" id="btn-enviados">
                    <i class="fas fa-paper-plane me-1"></i> Productos Enviados
                </button>
                <button class="btn btn-outline-warning me-2" id="btn-devueltos">
                    <i class="fas fa-undo me-1"></i> Productos Devueltos
                </button>
                <button class="btn btn-outline-success" id="btn-stock">
                    <i class="fas fa-boxes me-1"></i> Productos en Bodega (Stock Actual)
                </button>

                
            </div>

            <!-- Sección: Productos Enviados -->
            <div id="section-enviados" style="display:none;" class="mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Productos Enviados</h5>
                    </div>
                    <div class="card-body">
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
                        <div class="table-responsive">
                            <table class="table table-hover" id="tabla-enviados">
                                <thead class="table-light">
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
                                            <td><span class="badge bg-secondary">{{ $item->codigo }}</span></td>
                                            <td>{{ $item->nombre }}</td>
                                            <td><span class="badge bg-primary">{{ $item->cantidad }}</span></td>
                                            <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                No hay productos enviados
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección: Productos Devueltos -->
            <div id="section-devueltos" style="display:none;" class="mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-undo me-2"></i>Productos Devueltos</h5>
                    </div>
                    <div class="card-body">
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
                        <div class="table-responsive">
                            <table class="table table-hover" id="tabla-devueltos">
                                <thead class="table-light">
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
                                            <td><span class="badge bg-secondary">{{ $item->codigo }}</span></td>
                                            <td>{{ $item->nombre }}</td>
                                            <td><span class="badge bg-warning">{{ $item->cantidad }}</span></td>
                                            <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                No hay productos devueltos
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección: Productos en Bodega (Stock Actual) -->
            <div id="section-stock" style="display:none;" class="mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-boxes me-2"></i>Productos en Bodega (Stock Actual)
                        </h5>
                        <a href="{{ route('bodega.stock.pdf', $bodega->idbodega) }}" target="_blank" class="btn btn-light btn-sm fw-bold">
                            <i class="fas fa-file-pdf me-1 text-danger"></i> PDF
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3" id="filtros-stock" style="display:none;">
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="filtro-codigo-stock" placeholder="Filtrar por código">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="filtro-nombre-stock" placeholder="Filtrar por nombre">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="tabla-stock">
                                <thead class="table-light">
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
                                            <td><span class="badge bg-secondary">{{ $item['codigo'] }}</span></td>
                                            <td>{{ $item['nombre'] }}</td>
                                            <td>{{ $item['descripcion'] }}</td>
                                            <td><span class="badge bg-success">{{ $item['cantidad'] }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                No hay productos en stock
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="mt-4 d-flex flex-wrap gap-2">
                <!-- <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="fas fa-home me-1"></i> Volver al inicio
                </a> -->
                @if(!in_array($cargo, ['Jefe de bodega']))
                    <a href="{{ route('tipoNota.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Crear Nueva Nota
                    </a>
                     <a href="{{ route('tipoNota.index') }}" class="btn btn-info">
                        <i class="fas fa-eye me-1"></i> Ver Notas
                    </a>
                @endif
                @if(in_array($cargo, ['Administrador', 'Gerente', 'Vendedor camión']))
                    <a href="{{ route('venta.create', $bodega->idbodega) }}" class="btn btn-warning">
                        <i class="fas fa-cash-register me-1"></i> Registrar venta
                    </a>
                    <a href="{{ route('venta.index.bodega', $bodega->idbodega) }}" class="btn btn-info">
                        <i class="fas fa-eye me-1"></i> Ver ventas
                    </a>
                @endif
            </div>
        </div>
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

<style>
/* Reset de márgenes y padding globales */
body {
    margin: 0;
    padding: 0;
}

.container-fluid {
    padding: 0 !important;
    margin: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}

.card {
    transition: all 0.3s ease;
    margin-bottom: 0;
}

.card:hover {
    transform: translateY(-1px);
}

.nav-link {
    padding: 0.5rem 0;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.nav-link:hover {
    background-color: rgba(0, 123, 255, 0.1);
    padding-left: 0.5rem;
}

.nav-link.active {
    background-color: rgba(23, 162, 184, 0.1);
    border-left: 3px solid #17a2b8;
    padding-left: 0.5rem;
}

.min-vh-100 {
    min-height: 100vh;
}

.h3 {
    font-size: 2.5rem;
}

/* Eliminación de gutters y espacios */
.row.g-0 {
    margin: 0;
}

.col-md-2, .col-md-10 {
    padding-left: 0;
    padding-right: 0;
}

/* Asegurar que el sidebar llegue hasta el borde */
.col-md-2.bg-light {
    margin: 0;
    border-radius: 0;
}

/* Botones mejorados */
.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Tablas mejoradas */
.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
}

.badge {
    font-size: 0.85em;
}

/* Responsive */
@media (max-width: 768px) {
    .col-md-2 {
        display: none;
    }
    
    .col-md-10 {
        flex: 0 0 100%;
        max-width: 100%;
        padding: 15px !important;
    }
    
    .h3 {
        font-size: 2rem;
    }
    
    .container-fluid {
        padding: 0 !important;
    }

    .d-flex.gap-2 {
        flex-direction: column;
    }

    .d-flex.gap-2 .btn {
        margin-bottom: 0.5rem;
    }
}

/* Asegurar altura completa */
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}

#app {
    min-height: 100vh;
}
</style>
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