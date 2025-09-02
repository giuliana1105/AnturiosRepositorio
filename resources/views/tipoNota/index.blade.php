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
                 
                <a class="nav-link text-dark mb-2" href="{{ route('transaccionProducto.index') }}">
                    <i class="fas fa-exchange-alt me-2"></i> Transacción Producto
                </a>
                   @endif


                <a class="nav-link text-dark mb-2" href="{{ route('home') }}">
                    <i class="fas fa-home me-2"></i> Home
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link text-dark btn btn-link mb-2" style="text-align:left;">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar sesión
                    </button>
                </form>
                
                <!-- Agrega más enlaces según tu menú -->
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 py-3 px-4 bg-white">
            <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width: 1200px;">
                <div class="card-header bg-info text-white rounded-top-4 text-center">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">Lista de Notas</h3>
                        <a href="{{ route('home') }}" class="btn btn-light btn-sm rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $cargo = auth()->user()->cargoNombre();
                    @endphp

                    @if(!in_array($cargo, ['Jefe de bodega']))
                        <a href="{{ route('tipoNota.create') }}" class="btn btn-info text-white fw-bold rounded-pill mb-3">
                            <i class="fas fa-plus me-2"></i> Crear Nota
                        </a>
                    @endif

                    <!-- Filtros -->
                    <form method="GET" action="{{ route('tipoNota.index') }}" class="row g-2 mb-4 align-items-end">
                        <div class="col-md-4">
                            <label for="filtro_bodega" class="form-label fw-bold">Filtrar por Bodega</label>
                            <select name="bodega" id="filtro_bodega" class="form-select">
                                <option value="">Todas las bodegas</option>
                                @foreach($bodegas as $bodega)
                                    <option value="{{ $bodega->idbodega }}" {{ request('bodega') == $bodega->idbodega ? 'selected' : '' }}>
                                        {{ $bodega->nombrebodega }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filtro_tipo" class="form-label fw-bold">Filtrar por Tipo</label>
                            <select name="tipo" id="filtro_tipo" class="form-select">
                                <option value="">Todos los tipos</option>
                                <option value="ENVIO" {{ request('tipo') == 'ENVIO' ? 'selected' : '' }}>Envío</option>
                                <option value="DEVOLUCION" {{ request('tipo') == 'DEVOLUCION' ? 'selected' : '' }}>Devolución</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-info fw-bold rounded-pill px-4">
                                <i class="fas fa-filter me-2"></i> Filtrar
                            </button>
                            <a href="{{ route('tipoNota.index') }}" class="btn btn-secondary rounded-pill ms-2 px-4">
                                <i class="fas fa-times me-2"></i> Limpiar
                            </a>
                        </div>
                    </form>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>CÓDIGO</th>
                                    <th>TIPO</th>
                                    <th>SOLICITANTE</th>
                                    <th>PRODUCTOS</th>
                                    <th>CANTIDAD</th>
                                    <th>TIPO EMPAQUE</th>
                                    <th>BODEGA</th>
                                    <th>FECHA</th>
                                    <th>ESTADO</th>
                                    <th>ACCIONES</th>
                                    <th>PDF</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tipoNotas as $nota)
                                    <tr>
                                        <td>{{ $nota->codigo }}</td>
                                        <td>{{ $nota->tiponota }}</td>
                                        <td>{{ optional($nota->responsableEmpleado)->nombreemp ?? 'N/A' }} {{ optional($nota->responsableEmpleado)->apellidoemp ?? '' }}</td>
                                        {{-- PRODUCTOS, CANTIDAD Y TIPO EMPAQUE --}}
                                        <td colspan="3" style="vertical-align:top; padding:0;">
                                            @if($nota->detalles && $nota->detalles->count() > 0)
                                                <table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin:0;">
                                                    <tbody>
                                                        @foreach ($nota->detalles as $index => $detalle)
                                                            <tr style="{{ $index > 0 ? 'border-top: 1px solid #dee2e6;' : '' }}">
                                                                <td style="width: 33.33%; padding: 8px; vertical-align: top; word-wrap: break-word; overflow-wrap: break-word; border:none;">
                                                                    {{ $detalle->producto->nombre ?? $detalle->codigoproducto }}
                                                                </td>
                                                                <td style="width: 33.33%; padding: 8px; vertical-align: top; text-align: center; border:none;">
                                                                    {{ $detalle->cantidad ?? 0 }}
                                                                </td>
                                                                <td style="width: 33.34%; padding: 8px; vertical-align: top; text-align: center; word-wrap: break-word; border:none;">
                                                                    {{ $detalle->producto->tipoempaque ?? 'Sin empaque' }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin:0;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width: 33.33%; padding: 8px; text-align: center; border:none;" class="text-muted">Sin productos</td>
                                                            <td style="width: 33.33%; padding: 8px; text-align: center; border:none;" class="text-muted">-</td>
                                                            <td style="width: 33.34%; padding: 8px; text-align: center; border:none;" class="text-muted">-</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            @endif
                                        </td>
                                        <td>{{ optional($nota->bodega)->nombrebodega ?? 'N/A' }}</td>
                                        <td>{{ $nota->fechanota ? \Carbon\Carbon::parse($nota->fechanota)->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td>
                                            @if(optional($nota->transaccion)->estado)
                                                <span class="badge bg-info">{{ $nota->transaccion->estado }}</span>
                                            @else
                                                <span class="badge bg-secondary">Sin Confirmar</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$nota->transaccion)
                                                <form action="{{ route('tipoNota.confirmar', $nota->codigo) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm mb-2 rounded-pill">
                                                        <i class="fas fa-check me-1"></i> Confirmar
                                                    </button>
                                                </form>
                                                <a href="{{ route('tipoNota.edit', $nota->codigo) }}" class="btn btn-warning btn-sm mb-2 rounded-pill">
                                                    <i class="fas fa-edit me-1"></i> Editar
                                                </a>
                                                @can('eliminar TipoNota')
                                                    <form action="{{ route('tipoNota.destroy', $nota->codigo) }}" method="POST" style="display:inline;">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('¿Estás seguro de eliminar esta nota?')">
                                                            <i class="fas fa-trash me-1"></i> Eliminar
                                                        </button>
                                                    </form>
                                                @endcan
                                            @else
                                                <span class="text-muted small">Nota confirmada</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('tipoNota.pdf', $nota->codigo) }}" class="btn btn-danger btn-sm rounded-pill">
                                                <i class="fas fa-file-pdf me-1"></i> PDF
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">No se encontraron notas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                        {{ $tipoNotas->onEachSide(1)->links('pagination::bootstrap-4') }}
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
.row.g-3 {
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
.btn-outline-success, .btn-outline-danger {
    border-width: 2px;
}
.btn-outline-success:hover, .btn-outline-success:focus {
    background-color: #43a047;
    color: #fff;
    border-color: #43a047;
}
.btn-outline-danger:hover, .btn-outline-danger:focus {
    background-color: #e53935;
    color: #fff;
    border-color: #e53935;
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
.rounded-pill {
    border-radius: 50rem !important;
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