@extends('layouts.app')

@php
    $cargo = auth()->user()->cargoNombre();
@endphp

@section('content')
<div class="container-fluid py-3 px-3 px-md-4">
    <!-- Breadcrumb & Status Wayfinding -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                        <i class="fas fa-home me-1 text-primary"></i> Inicio
                    </a>
                </li>
                <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                    Catálogo de Productos
                </li>
            </ol>
        </nav>
    </div>

    <!-- Encabezado de la página -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 pb-3 mb-4 border-bottom" style="border-color: #e2e8f0 !important;">
        <div>
            <h2 class="fw-bold mb-1 text-dark tracking-tight" style="letter-spacing: -0.025em;">
                Catálogo de Productos
            </h2>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Control analítico de especies de anturios, inventario consolidado, empaques y especificaciones.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('catalogo.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-2 fw-medium" target="_blank">
                <i class="fas fa-external-link-alt me-1 text-primary"></i> Catálogo Público
            </a>
            @can('crear producto')
            <a href="{{ route('productos.create') }}" class="btn btn-sm btn-primary rounded-3 px-3 py-2 fw-semibold shadow-sm">
                <i class="fas fa-plus me-1"></i> Añadir Producto
            </a>
            @endcan
        </div>
    </div>

    <!-- Alertas de Sesión -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tarjetas de Resumen Ejecutivo (Estilo Impeccable) -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Variedades Registradas -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Variedades Registradas</span>
                        <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold" style="font-size: 11px;">
                            Especies
                        </span>
                    </div>
                    <div class="metric-value">
                        {{ $totalProductos ?? $productos->total() }}
                        <span class="metric-unit">variedades</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-boxes text-primary me-1"></i> Línea activa de anturios
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Unidades en Stock -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Unidades en Stock Global</span>
                        <span class="badge rounded-pill bg-success-subtle text-success fw-semibold" style="font-size: 11px;">
                            Existencias
                        </span>
                    </div>
                    <div class="metric-value text-success font-mono">
                        {{ number_format($totalStock ?? 0) }}
                        <span class="metric-unit">tallos</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-check-circle text-success me-1"></i> Stock físico en bodegas
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Stock Crítico -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Stock Crítico (≤ 10)</span>
                        <span class="badge rounded-pill bg-danger-subtle text-danger fw-semibold" style="font-size: 11px;">
                            Alerta
                        </span>
                    </div>
                    <div class="metric-value font-mono {{ ($productosStockBajo ?? 0) > 0 ? 'text-danger' : 'text-dark' }}">
                        {{ $productosStockBajo ?? 0 }}
                        <span class="metric-unit">variedades</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        @if(($productosStockBajo ?? 0) > 0)
                            <span class="text-danger fw-semibold">
                                <i class="fas fa-exclamation-triangle me-1"></i> Requiere reabastecimiento urgente
                            </span>
                        @else
                            <i class="fas fa-check-circle text-success me-1"></i> Niveles de existencias estables
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="card border rounded-3 mb-4 bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-3 p-md-3.5">
            <form action="{{ route('productos.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-barcode" style="font-size: 12px;"></i>
                        </span>
                        <input type="text" name="search_codigo" id="search_codigo" class="form-control border-start-0 ps-1"
                               value="{{ request('search_codigo') }}" placeholder="Código...">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-search" style="font-size: 12px;"></i>
                        </span>
                        <input type="text" name="search_nombre" id="search_nombre" class="form-control border-start-0 ps-1"
                               value="{{ request('search_nombre') }}" placeholder="Nombre o variedad...">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-box" style="font-size: 12px;"></i>
                        </span>
                        <select name="tipoempaque" class="form-select border-start-0 ps-1">
                            <option value="">Todos los empaques</option>
                            @if(isset($empaquesDisponibles))
                                @foreach($empaquesDisponibles as $emp)
                                    <option value="{{ $emp }}" {{ request('tipoempaque') == $emp ? 'selected' : '' }}>
                                        {{ $emp }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-3 px-3 w-100 fw-semibold">
                        <i class="fas fa-search me-1"></i> Filtrar
                    </button>
                    @if(request()->hasAny(['search_codigo', 'search_nombre', 'tipoempaque']))
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-3 px-3" title="Limpiar filtros">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Principal de Productos -->
    <div class="card border rounded-3 overflow-hidden shadow-xs bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 operational-table">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3" style="width: 320px;">Producto / Variedad</th>
                            <th class="py-3">Descripción</th>
                            <th class="py-3" style="width: 170px;">Tipo de Empaque</th>
                            <th class="py-3 text-center" style="width: 180px;">Nivel de Existencias</th>
                            @if(auth()->user()->can('editar producto') || auth()->user()->can('eliminar producto'))
                            <th class="text-end pe-4 py-3" style="width: 130px;">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                            @php
                                $stock = (int) $producto->cantidad;
                                if ($stock <= 0) {
                                    $statusClass = 'status-danger';
                                    $statusLabel = 'Agotado (0)';
                                } elseif ($stock <= 10) {
                                    $statusClass = 'status-warning';
                                    $statusLabel = 'Stock Crítico (' . $stock . ')';
                                } else {
                                    $statusClass = 'status-success';
                                    $statusLabel = number_format($stock) . ' unidades';
                                }
                            @endphp
                            <tr class="operational-row">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 flex-shrink-0">
                                            @if($producto->imagen)
                                                <img src="{{ $producto->imagen }}" alt="Foto de {{ $producto->nombre }}" class="rounded-3 object-fit-cover border" style="width: 44px; height: 44px; border-color: #e2e8f0 !important;">
                                            @else
                                                <div class="rounded-3 d-flex align-items-center justify-content-center border" style="width: 44px; height: 44px; background: rgba(0, 151, 167, 0.08); color: var(--primary); border-color: #e2e8f0 !important;">
                                                    <i class="fas fa-leaf fs-5"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 14px;">{{ $producto->nombre }}</div>
                                            <div>
                                                <span class="font-mono text-muted fw-bold" style="font-size: 12px;">
                                                    {{ $producto->codigo }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3" style="max-width: 280px;">
                                    <div class="text-muted text-truncate" style="font-size: 13px;" title="{{ $producto->descripcion }}">
                                        {{ $producto->descripcion ?? '—' }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-2.5 py-1" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; font-size: 12px;">
                                        <i class="fas fa-box me-1 text-muted"></i>{{ $producto->tipoempaque ?? 'Unidad' }}
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="status-badge {{ $statusClass }} font-mono">
                                        <span class="status-indicator-dot"></span> {{ $statusLabel }}
                                    </span>
                                </td>
                                @if(auth()->user()->can('editar producto') || auth()->user()->can('eliminar producto'))
                                <td class="text-end pe-4 py-3">
                                    <div class="d-inline-flex gap-1">
                                        @can('editar producto')
                                        <a href="{{ route('productos.edit', $producto->codigo) }}" 
                                           class="btn-action-circle text-primary" 
                                           title="Editar producto">
                                            <i class="fas fa-edit" style="font-size: 12px;"></i>
                                        </a>
                                        @endcan

                                        @can('eliminar producto')
                                        <form action="{{ route('productos.destroy', $producto->codigo) }}" method="POST" class="d-inline form-delete-prod">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-action-circle danger btn-delete-prod" 
                                                    title="Eliminar producto">
                                                <i class="fas fa-trash" style="font-size: 12px;"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: #f1f5f9; color: #94a3b8;">
                                            <i class="fas fa-box-open fs-4"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No se encontraron productos</h6>
                                        <p class="text-muted mb-3" style="font-size: 13.5px;">Intenta cambiar los filtros de búsqueda o registra una nueva especie de anturio.</p>
                                        @if(request()->hasAny(['search_codigo', 'search_nombre', 'tipoempaque']))
                                            <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3">
                                                <i class="fas fa-undo me-1"></i> Restablecer filtros
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($productos, 'links') && $productos->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-center bg-white" style="border-color: #e2e8f0 !important;">
                    {{ $productos->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnsDelete = document.querySelectorAll('.btn-delete-prod');
    btnsDelete.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            let form = this.closest('form');
            Swal.fire({
                title: '¿Eliminar este producto?',
                text: "Si el producto tiene movimientos de bodega o ventas asociadas, se recomienda verificar antes de proceder.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'rounded-pill px-3',
                    cancelButton: 'rounded-pill px-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size: 12px;"></i>';
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
