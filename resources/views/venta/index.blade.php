@extends('layouts.app')
 @php
                        $cargo = auth()->user()->cargoNombre();
                    @endphp

@section('content')
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
                <a class="nav-link text-dark mb-2" href="{{ route('tipoNota.index') }}">
                    <i class="fas fa-file-alt me-2"></i> Notas de Pedido
                </a>
                  @if(!in_array($cargo, ['Vendedor camión', 'Vendedor']))
                <a class="nav-link text-dark mb-2" href="{{ route('productos.index') }}">
                    <i class="fas fa-cube me-2"></i> Productos
                </a>
                
                <a class="nav-link text-dark mb-2" href="{{ route('empleados.index') }}">
                    <i class="fas fa-users me-2"></i> Empleados
                </a>
                <a class="nav-link text-dark mb-2" href="{{ route('bodegas.index') }}">
                    <i class="fas fa-warehouse me-2"></i> Bodegas
                </a>
                <a class="nav-link text-dark mb-2" href="{{ route('transaccionProducto.index') }}">
                    <i class="fas fa-exchange-alt me-2"></i> Transacción Producto
                </a>
                @endif
                <a class="nav-link active text-info fw-bold mb-2" href="#">
                    <i class="fas fa-shopping-cart me-2"></i> Ventas
                </a>
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
            <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width: 1400px;">
                <div class="card-header bg-info text-white rounded-top-4 text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i> Ventas Registradas
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Mensajes de éxito -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong><i class="fas fa-check me-2"></i>¡Éxito!</strong>
                                    {{ session('success') }}
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Botón Volver -->
                    <div class="mb-4">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary fw-bold rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </a>
                    </div>

                    <!-- Filtros de Búsqueda -->
                    <div class="card mb-4 border-0 bg-primary bg-opacity-10">
                        <div class="card-header bg-primary text-white rounded-top-4">
                            <h5 class="mb-0">
                                <i class="fas fa-filter me-2"></i> Filtros de Búsqueda
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Primera fila de filtros -->
                            <div class="row mb-3" id="filtros-ventas">
                                <div class="col-md-4 mb-3">
                                    <label for="filtro-cliente" class="form-label fw-bold">
                                        <i class="fas fa-user me-2"></i>Cliente
                                    </label>
                                    <input type="text" class="form-control rounded-pill" id="filtro-cliente" 
                                           placeholder="Buscar por cliente">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="filtro-ciudad" class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt me-2"></i>Ciudad
                                    </label>
                                    <select class="form-control rounded-pill" id="filtro-ciudad">
                                        <option value="">Todas las ciudades</option>
                                        @php
                                            $ciudades = $ventas->pluck('ciudad')->unique()->filter()->sort();
                                        @endphp
                                        @foreach($ciudades as $ciudad)
                                            <option value="{{ $ciudad }}">{{ $ciudad }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="filtro-pago" class="form-label fw-bold">
                                        <i class="fas fa-credit-card me-2"></i>Forma de Pago
                                    </label>
                                    <select class="form-control rounded-pill" id="filtro-pago">
                                        <option value="">Todas las formas de pago</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Crédito">Crédito</option>
                                        <option value="Crédito liquidado">Crédito liquidado</option>
                                        <option value="Crédito pendiente">Crédito pendiente</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Segunda fila de filtros - Fechas -->
                            <div class="row mb-3" id="filtros-fechas">
                                <div class="col-md-4 mb-3">
                                    <label for="filtro-dia" class="form-label fw-bold">
                                        <i class="fas fa-calendar-day me-2"></i>Filtro por Día
                                    </label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="filtro-dia" 
                                               style="border-top-left-radius: 50rem; border-bottom-left-radius: 50rem;">
                                        <button type="button" id="clear-dia" class="btn btn-outline-danger" 
                                                style="border-top-right-radius: 50rem; border-bottom-right-radius: 50rem;"
                                                title="Quitar filtro">
                                            <span style="font-weight:bold;">&#10006;</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-calendar-alt me-2"></i>Filtro por Rango de Fechas
                                    </label>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="input-group">
                                                <input type="date" class="form-control" id="filtro-fecha-inicio" 
                                                       style="border-top-left-radius: 50rem; border-bottom-left-radius: 50rem;"
                                                       placeholder="Fecha inicio">
                                                <button type="button" id="clear-inicio" class="btn btn-outline-danger" 
                                                        style="border-top-right-radius: 50rem; border-bottom-right-radius: 50rem;"
                                                        title="Quitar filtro">
                                                    <span style="font-weight:bold;">&#10006;</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="input-group">
                                                <input type="date" class="form-control" id="filtro-fecha-fin" 
                                                       style="border-top-left-radius: 50rem; border-bottom-left-radius: 50rem;"
                                                       placeholder="Fecha fin">
                                                <button type="button" id="clear-fin" class="btn btn-outline-danger" 
                                                        style="border-top-right-radius: 50rem; border-bottom-right-radius: 50rem;"
                                                        title="Quitar filtro">
                                                    <span style="font-weight:bold;">&#10006;</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Generar PDF -->
                    <div class="mb-4 text-center">
                        <button id="btn-reporte" class="btn btn-success fw-bold rounded-pill px-4"
                                data-bodega="{{ isset($bodega) ? $bodega->idbodega : '' }}">
                            <i class="fas fa-file-pdf me-2"></i> Generar PDF
                        </button>
                    </div>

                   

                    <!-- Tabla de ventas -->
                    <div id="reporte-ventas">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">
                                            <i class="fas fa-hashtag me-2"></i>Nro. venta
                                        </th>
                                        <th class="text-center">
                                            <i class="fas fa-calendar me-2"></i>Fecha
                                        </th>
                                        <th class="text-center">
                                            <i class="fas fa-user me-2"></i>Cliente
                                        </th>
                                        <th class="text-center">
                                            <i class="fas fa-map-marker-alt me-2"></i>Ciudad
                                        </th>
                                        <th class="text-center">
                                            <i class="fas fa-warehouse me-2"></i>Bodega
                                        </th>
                                        <th class="text-center">
                                            <i class="fas fa-dollar-sign me-2"></i>Total venta
                                        </th>
                                        <th class="text-center">
                                            <i class="fas fa-credit-card me-2"></i>Forma de pago
                                        </th>
                                        <th class="text-center">
                                            <i class="fas fa-cogs me-2"></i>Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ventas as $venta)
                                        <tr>
                                            <td class="fw-bold text-center">{{ $venta->nro_venta }}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') }}</td>
                                            <td>{{ $venta->cliente }}</td>
                                            <td class="text-center">{{ $venta->ciudad ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $venta->bodega->nombrebodega ?? $venta->bodega_id }}</td>
                                            <td class="text-center fw-bold">${{ number_format($venta->total_venta, 2) }}</td>
                                            <td class="text-center"
                                                @if($venta->tipo_pago === 'Crédito')
                                                    data-saldo="{{ isset($venta->saldo) ? $venta->saldo : 0 }}"
                                                @endif
                                            >
                                                @if($venta->tipo_pago === 'Crédito')
                                                    @if(isset($venta->saldo) && $venta->saldo > 0)
                                                        <span class="badge bg-danger rounded-pill">
                                                            <i class="fas fa-exclamation-circle me-1"></i>Crédito Pendiente
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success rounded-pill">
                                                            <i class="fas fa-check-circle me-1"></i>Crédito Liquidado
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-primary rounded-pill">
                                                        {{ $venta->tipo_pago ?? 'N/A' }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group-vertical" role="group">
                                                    <a href="{{ route('venta.show', $venta->id) }}" 
                                                       class="btn btn-info btn-sm rounded-pill mb-1">
                                                        <i class="fas fa-eye me-1"></i> Detalle
                                                    </a>
                                                    @if($venta->tipo_pago === 'Crédito' && isset($venta->saldo) && $venta->saldo > 0)
                                                        <a href="{{ route('venta.abono', $venta->id) }}" 
                                                           class="btn btn-warning btn-sm rounded-pill mb-1">
                                                            <i class="fas fa-money-bill me-1"></i> Abono
                                                        </a>
                                                    @endif
                                                    @if(in_array($cargo, ['Administrador', 'Gerente']))
                                                        <a href="{{ route('venta.edit', $venta->id) }}" 
                                                           class="btn btn-primary btn-sm rounded-pill mb-1">
                                                            <i class="fas fa-edit me-1"></i> Editar
                                                        </a>
                                                        <form action="{{ route('venta.destroy', $venta->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm rounded-pill" 
                                                                    onclick="return confirm('¿Está seguro de eliminar esta venta?')">
                                                                <i class="fas fa-trash me-1"></i> Eliminar
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 0;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
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
.card-body {
    position: relative;
    overflow: hidden;
}
.card-body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    pointer-events: none;
}
.h3 {
    font-size: 2.5rem;
}
.row.g-0 {
    margin: 0;
}
.col-md-2, .col-md-10 {
    padding-left: 0;
    padding-right: 0;
}
.col-md-2.bg-light {
    margin: 0;
    border-radius: 0;
}
.btn-info {
    background-color: #0097a7;
    border-color: #0097a7;
}
.btn-info:hover, .btn-info:focus {
    background-color: #00796b;
    border-color: #00796b;
}
.btn-warning {
    background-color: #ff9800;
    border-color: #ff9800;
    color: #fff;
}
.btn-warning:hover, .btn-warning:focus {
    background-color: #f57c00;
    border-color: #f57c00;
    color: #fff;
}
.btn-danger {
    background-color: #e53935;
    border-color: #e53935;
    color: #fff;
}
.btn-danger:hover, .btn-danger:focus {
    background-color: #b71c1c;
    border-color: #b71c1c;
    color: #fff;
}
.btn-success {
    background-color: #4caf50;
    border-color: #4caf50;
}
.btn-success:hover, .btn-success:focus {
    background-color: #388e3c;
    border-color: #388e3c;
}
.btn-secondary {
    background-color: #607d8b;
    border-color: #607d8b;
    color: #fff;
}
.btn-secondary:hover, .btn-secondary:focus {
    background-color: #455a64;
    border-color: #455a64;
    color: #fff;
}
.btn-primary {
    background-color: #2196f3;
    border-color: #2196f3;
}
.btn-primary:hover, .btn-primary:focus {
    background-color: #1976d2;
    border-color: #1976d2;
}
.btn-outline-danger {
    color: #e53935;
    border-color: #e53935;
}
.btn-outline-danger:hover, .btn-outline-danger:focus {
    background-color: #e53935;
    border-color: #e53935;
    color: #fff;
}
.rounded-pill {
    border-radius: 50rem !important;
}
.rounded-4 {
    border-radius: 1rem !important;
}
.rounded-top-4 {
    border-top-left-radius: 1rem !important;
    border-top-right-radius: 1rem !important;
}
.alert {
    border: none;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
    border-left: 4px solid #4caf50;
}
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border-left: 4px solid #e53935;
}
.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}
.badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
}
.btn-group-vertical .btn {
    margin-bottom: 0.25rem;
}
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
    .btn-group .btn {
        margin-bottom: 0.25rem;
    }
    .table-responsive {
        font-size: 0.875rem;
    }
    .col-md-8, .col-md-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    .btn-group-vertical {
        flex-direction: column;
        width: 100%;
    }
    .btn-group-vertical .btn {
        border-radius: 50rem !important;
        margin-bottom: 0.25rem;
        margin-right: 0 !important;
    }
}
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clienteInput = document.getElementById('filtro-cliente');
    const ciudadSelect = document.getElementById('filtro-ciudad');
    const pagoSelect = document.getElementById('filtro-pago');
    const diaInput = document.getElementById('filtro-dia');
    const fechaInicioInput = document.getElementById('filtro-fecha-inicio');
    const fechaFinInput = document.getElementById('filtro-fecha-fin');
    const tabla = document.querySelector('.table');

    function filtrar() {
        const cliente = clienteInput.value.trim().toLowerCase();
        const ciudad = ciudadSelect.value;
        const pago = pagoSelect.value;
        const dia = diaInput.value;
        const fechaInicio = fechaInicioInput.value;
        const fechaFin = fechaFinInput.value;

        Array.from(tabla.querySelectorAll('tbody tr')).forEach(row => {
            const tdCliente = row.children[2]?.textContent.toLowerCase();
            const tdCiudad = row.children[3]?.textContent;
            const tdPago = row.children[6]?.textContent.trim();
            const tdSaldo = row.children[6]?.getAttribute('data-saldo');
            const tdFecha = row.children[1]?.textContent; // Formato: YYYY-MM-DD

            let mostrar = true;
            if (cliente && (!tdCliente || !tdCliente.includes(cliente))) mostrar = false;
            if (ciudad && tdCiudad !== ciudad) mostrar = false;

            if (pago) {
                if (pago === 'Crédito liquidado') {
                    if (!(tdPago.includes('Crédito') && tdSaldo == 0)) mostrar = false;
                } else if (pago === 'Crédito pendiente') {
                    if (!(tdPago.includes('Crédito') && tdSaldo > 0)) mostrar = false;
                } else if (!tdPago.includes(pago)) {
                    mostrar = false;
                }
            }

            // Filtro por día exacto
            if (dia && tdFecha !== dia) mostrar = false;

            // Filtro por rango de fechas
            if ((fechaInicio || fechaFin) && tdFecha) {
                if (fechaInicio && tdFecha < fechaInicio) mostrar = false;
                if (fechaFin && tdFecha > fechaFin) mostrar = false;
            }

            row.style.display = mostrar ? '' : 'none';
        });
    }

    clienteInput.addEventListener('input', filtrar);
    ciudadSelect.addEventListener('change', filtrar);
    pagoSelect.addEventListener('change', filtrar);
    diaInput.addEventListener('change', filtrar);
    fechaInicioInput.addEventListener('change', filtrar);
    fechaFinInput.addEventListener('change', filtrar);
});

document.getElementById('btn-reporte').addEventListener('click', function(e) {
    e.preventDefault();

    const bodega_id = this.getAttribute('data-bodega');
    const cliente = document.getElementById('filtro-cliente').value;
    const ciudad = document.getElementById('filtro-ciudad').value;
    const tipo_pago = document.getElementById('filtro-pago').value;
    const dia = document.getElementById('filtro-dia').value;
    const fecha_inicio = document.getElementById('filtro-fecha-inicio').value;
    const fecha_fin = document.getElementById('filtro-fecha-fin').value;

    let url = "{{ route('ventas.exportar') }}?";
    let params = [];
    if(bodega_id) params.push('bodega_id=' + encodeURIComponent(bodega_id));
    if(cliente) params.push('cliente=' + encodeURIComponent(cliente));
    if(ciudad) params.push('ciudad=' + encodeURIComponent(ciudad));
    if(tipo_pago) params.push('tipo_pago=' + encodeURIComponent(tipo_pago));
    if(dia) params.push('dia=' + encodeURIComponent(dia));
    if(fecha_inicio) params.push('fecha_inicio=' + encodeURIComponent(fecha_inicio));
    if(fecha_fin) params.push('fecha_fin=' + encodeURIComponent(fecha_fin));
    url += params.join('&');

    window.open(url, '_blank');
});

document.getElementById('clear-dia').addEventListener('click', function() {
    document.getElementById('filtro-dia').value = '';
    // Dispara el evento para que se actualice el filtro
    document.getElementById('filtro-dia').dispatchEvent(new Event('change'));
});
document.getElementById('clear-inicio').addEventListener('click', function() {
    document.getElementById('filtro-fecha-inicio').value = '';
    document.getElementById('filtro-fecha-inicio').dispatchEvent(new Event('change'));
});
document.getElementById('clear-fin').addEventListener('click', function() {
    document.getElementById('filtro-fecha-fin').value = '';
    document.getElementById('filtro-fecha-fin').dispatchEvent(new Event('change'));
});
</script>
@endsection