@extends('layouts.app')

@php
    $totalUnidadesStock = collect($productosEnBodega)->sum('cantidad');
    $totalVariedadesStock = collect($productosEnBodega)->count();
    $stockBajoCount = collect($productosEnBodega)->where('cantidad', '<=', 10)->count();
    $totalEnviados = $productos->sum('cantidad');
    $totalDevueltos = $devueltos->sum('cantidad');
@endphp

@section('content')
<div class="container-fluid py-3 px-3 px-md-4">
    <!-- Breadcrumb & Status Wayfinding -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                @can('ver bodegas')
                <li class="breadcrumb-item">
                    <a href="{{ route('bodegas.index') }}" class="text-decoration-none text-muted">
                        <i class="fas fa-warehouse me-1 text-primary"></i> Bodegas
                    </a>
                </li>
                @else
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                        <i class="fas fa-home me-1 text-primary"></i> Inicio
                    </a>
                </li>
                @endcan
                <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                    {{ $bodega->nombrebodega }}
                </li>
            </ol>
        </nav>
    </div>

    <!-- Encabezado Principal y Botones de Acción -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 pb-3 mb-4 border-bottom" style="border-color: #e2e8f0 !important;">
        <div>
            <h2 class="fw-bold mb-1 text-dark tracking-tight" style="letter-spacing: -0.025em;">
                {{ $bodega->nombrebodega }}
            </h2>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Control analítico de existencias físicas en tiempo real, histórico de recepción de pedidos y devoluciones.
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            @can('ver bodegas')
                <a href="{{ route('bodegas.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            @else
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            @endcan

            @can('ver bodegas')
                <a href="{{ route('bodega.stock.pdf', $bodega->idbodega) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-3 px-3 py-2 fw-medium">
                    <i class="fas fa-file-pdf me-1"></i> Exportar Stock PDF
                </a>
            @endcan
            @can('ver ventas')
                <a href="{{ route('venta.index.bodega', $bodega->idbodega) }}" class="btn btn-sm btn-outline-primary rounded-3 px-3 py-2 fw-medium">
                    <i class="fas fa-receipt me-1"></i> Ventas
                </a>
            @endcan
            @can('registrar ventas')
                <a href="{{ route('venta.create', $bodega->idbodega) }}" class="btn btn-sm btn-primary rounded-3 px-3 py-2 fw-semibold shadow-sm">
                    <i class="fas fa-plus me-1"></i> Nueva Venta
                </a>
            @endcan
        </div>
    </div>

    <!-- Módulo de Métricas Ejecutivas (Estilo Impeccable) -->
    <div class="row g-3 mb-4">
        <!-- Tarjeta 1: Stock Total Disponible -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Unidades Disponibles</span>
                        <span class="badge rounded-pill bg-success-subtle text-success fw-semibold" style="font-size: 11px;">
                            En Bodega
                        </span>
                    </div>
                    <div class="metric-value text-dark">
                        {{ number_format($totalUnidadesStock) }}
                        <span class="metric-unit">tallos</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-check-circle text-success me-1"></i> Inventario disponible para venta
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta 2: Variedades en Stock -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Variedades Registradas</span>
                        <span class="badge rounded-pill bg-info-subtle text-info fw-semibold" style="font-size: 11px;">
                            Catálogo
                        </span>
                    </div>
                    <div class="metric-value text-dark">
                        {{ $totalVariedadesStock }}
                        <span class="metric-unit">variedades</span>
                    </div>
                    <div class="metric-caption mt-1">
                        @if($stockBajoCount > 0)
                            <span class="text-warning fw-semibold">
                                <i class="fas fa-exclamation-triangle me-1"></i> {{ $stockBajoCount }} con stock bajo (≤ 10)
                            </span>
                        @else
                            <span class="text-muted">
                                <i class="fas fa-circle-notch text-primary me-1"></i> Niveles de stock saludables
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta 3: Total Recepciones -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Total Recepciones</span>
                        <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold" style="font-size: 11px;">
                            Ingresos
                        </span>
                    </div>
                    <div class="metric-value text-dark">
                        {{ number_format($totalEnviados) }}
                        <span class="metric-unit">unidades</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-truck-loading me-1 text-primary"></i> {{ $productos->count() }} envíos recibidos
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta 4: Total Devoluciones -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Total Devoluciones</span>
                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis fw-semibold" style="font-size: 11px;">
                            Retornos
                        </span>
                    </div>
                    <div class="metric-value text-dark">
                        {{ number_format($totalDevueltos) }}
                        <span class="metric-unit">unidades</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-undo-alt me-1 text-warning"></i> {{ $devueltos->count() }} notas de devolución
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor Maestro de Inventario -->
    <div class="card border rounded-3 overflow-hidden shadow-xs" style="border-color: #e2e8f0 !important; background: #fff;">
        <!-- Barra de Pestañas Segmentada (Segmented Control Bar) -->
        <div class="card-header bg-white px-3 px-md-4 py-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="border-color: #e2e8f0 !important;">
            <div class="segmented-rail">
                <button type="button" class="segmented-tab active" id="btn-stock">
                    <i class="fas fa-cubes me-1.5"></i>
                    <span>Stock Actual</span>
                    <span class="counter-badge" id="badge-stock-count">{{ $totalVariedadesStock }}</span>
                </button>
                <button type="button" class="segmented-tab" id="btn-enviados">
                    <i class="fas fa-paper-plane me-1.5"></i>
                    <span>Recepciones</span>
                    <span class="counter-badge">{{ $productos->count() }}</span>
                </button>
                <button type="button" class="segmented-tab" id="btn-devueltos">
                    <i class="fas fa-undo me-1.5"></i>
                    <span>Devoluciones</span>
                    <span class="counter-badge">{{ $devueltos->count() }}</span>
                </button>
            </div>

            <!-- Buscador en tiempo real integrado -->
            <div class="table-search-box">
                <i class="fas fa-search search-icon text-muted"></i>
                <input type="text" id="filtro-global-bodega" class="form-control form-control-sm search-input" 
                       placeholder="Buscar variedad, código o tipo de empaque... (presiona /)" autocomplete="off">
                <span class="search-kbd-hint d-none d-sm-inline">/</span>
            </div>
        </div>

        <!-- Indicador de Resultados Activos -->
        <div class="px-3 px-md-4 py-2 bg-light border-bottom d-flex align-items-center justify-content-between text-muted" style="font-size: 12.5px; border-color: #e2e8f0 !important;">
            <div>
                <span id="results-count-label">Mostrando todas las variedades disponibles</span>
            </div>
            <div class="d-none d-sm-block">
                <span class="text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Tabla Operativa</span>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- SECCIÓN 1: STOCK ACTUAL EN BODEGA -->
            <div id="section-stock">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 inventory-table" id="tabla-stock">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3" style="width: 140px;">Código</th>
                                <th class="py-3">Variedad de Flor / Producto</th>
                                <th class="py-3" style="width: 160px;">Tipo de Empaque</th>
                                <th class="py-3">Descripción</th>
                                <th class="py-3 text-center" style="width: 140px;">Nivel</th>
                                <th class="text-end pe-4 py-3" style="width: 170px;">Disponible</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productosEnBodega as $item)
                                @php
                                    $qty = (int) $item['cantidad'];
                                    $statusClass = $qty > 20 ? 'status-high' : ($qty > 5 ? 'status-medium' : 'status-low');
                                    $statusLabel = $qty > 20 ? 'Óptimo' : ($qty > 5 ? 'Normal' : 'Stock Crítico');
                                @endphp
                                <tr class="inventory-row">
                                    <td class="ps-4 py-3">
                                        <span class="font-mono text-dark fw-bold" style="font-size: 13px;">
                                            {{ $item['codigo'] }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="fw-semibold text-dark text-truncate" style="font-size: 14px; max-width: 280px;" title="{{ $item['nombre'] }}">
                                            {{ $item['nombre'] }}
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="empaque-pill">
                                            <i class="fas fa-box me-1 opacity-75"></i> {{ $item['tipoempaque'] ?? ($item['empaque'] ?? 'Unidad') }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-muted" style="font-size: 13px;">
                                        <span class="text-truncate d-inline-block" style="max-width: 260px;" title="{{ $item['descripcion'] }}">
                                            {{ $item['descripcion'] ?: '—' }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="status-badge {{ $statusClass }}">
                                            <span class="status-indicator-dot"></span> {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        <span class="inventory-quantity">
                                            {{ number_format($qty) }}
                                        </span>
                                        <span class="text-muted ms-1" style="font-size: 11.5px;">unid.</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="py-4">
                                            <div class="empty-state-icon mb-3">
                                                <i class="fas fa-box-open"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1">Sin existencias en este almacén</h6>
                                            <p class="text-muted mb-0" style="font-size: 13.5px;">
                                                No se han registrado productos en stock para {{ $bodega->nombrebodega }}.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECCIÓN 2: PRODUCTOS ENVIADOS / RECEPCIONES -->
            <div id="section-enviados" style="display:none;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 inventory-table" id="tabla-enviados">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3" style="width: 140px;">Código</th>
                                <th class="py-3">Producto</th>
                                <th class="py-3 text-center" style="width: 180px;">Cantidad Recibida</th>
                                <th class="text-end pe-4 py-3" style="width: 180px;">Fecha de Envío</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productos as $item)
                                <tr class="inventory-row">
                                    <td class="ps-4 py-3">
                                        <span class="font-mono text-dark fw-bold" style="font-size: 13px;">
                                            {{ $item->codigo }}
                                        </span>
                                    </td>
                                    <td class="py-3 fw-semibold text-dark" style="font-size: 14px;">
                                        {{ $item->nombre }}
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge rounded-pill bg-primary-subtle text-primary fw-bold font-mono px-3 py-1.5" style="font-size: 13px;">
                                            +{{ number_format($item->cantidad) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4 py-3 font-mono text-muted" style="font-size: 13px;">
                                        <i class="far fa-calendar-alt me-1 text-muted"></i>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <div class="py-3">
                                            <i class="fas fa-paper-plane fs-3 mb-2 opacity-25"></i>
                                            <p class="mb-0">No hay registros de envíos o transferencias hacia esta bodega.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECCIÓN 3: PRODUCTOS DEVUELTOS -->
            <div id="section-devueltos" style="display:none;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 inventory-table" id="tabla-devueltos">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3" style="width: 140px;">Código</th>
                                <th class="py-3">Producto</th>
                                <th class="py-3 text-center" style="width: 180px;">Cantidad Devuelta</th>
                                <th class="text-end pe-4 py-3" style="width: 180px;">Fecha de Devolución</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($devueltos as $item)
                                <tr class="inventory-row">
                                    <td class="ps-4 py-3">
                                        <span class="font-mono text-dark fw-bold" style="font-size: 13px;">
                                            {{ $item->codigo }}
                                        </span>
                                    </td>
                                    <td class="py-3 fw-semibold text-dark" style="font-size: 14px;">
                                        {{ $item->nombre }}
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis fw-bold font-mono px-3 py-1.5" style="font-size: 13px;">
                                            -{{ number_format($item->cantidad) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4 py-3 font-mono text-muted" style="font-size: 13px;">
                                        <i class="far fa-calendar-alt me-1 text-muted"></i>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <div class="py-3">
                                            <i class="fas fa-undo fs-3 mb-2 opacity-25"></i>
                                            <p class="mb-0">No existen registros de devoluciones tramitadas desde esta bodega.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos Impeccable (Diseño Operativo de Alto Rendimiento) -->
<style>
/* Tipografía y números tabulares */
.font-mono {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    font-feature-settings: "tnum" 1;
}
.tracking-tight {
    letter-spacing: -0.02em;
}

/* Status Indicator Pill */
.status-indicator-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 3px 10px;
    border-radius: 9999px;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    color: #334155;
    font-size: 12px;
    font-weight: 500;
}
.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #10b981;
}
.pulse-green {
    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    animation: statusPulse 2s infinite;
}
@keyframes statusPulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

/* Metric Cards: Precision, White background, Hairline Border */
.metric-card {
    background: #ffffff;
    border-color: #e2e8f0 !important;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
}
.metric-label {
    font-size: 11.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748b;
}
.metric-value {
    font-size: 26px;
    font-weight: 700;
    line-height: 1.1;
    font-family: inherit;
    font-feature-settings: "tnum" 1;
}
.metric-unit {
    font-size: 13px;
    font-weight: 500;
    color: #94a3b8;
    margin-left: 2px;
}
.metric-caption {
    font-size: 12px;
}

/* Segmented Control Bar */
.segmented-rail {
    display: inline-flex;
    background: #f1f5f9;
    padding: 3px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    gap: 2px;
}
.segmented-tab {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
    background: transparent;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.15s ease;
}
.segmented-tab:hover {
    color: #0f172a;
}
.segmented-tab.active {
    background: #ffffff;
    color: #0f172a;
    font-weight: 600;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}
.counter-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 1px 7px;
    font-size: 11px;
    font-weight: 600;
    border-radius: 9999px;
    background: rgba(0, 0, 0, 0.06);
    color: inherit;
}
.segmented-tab.active .counter-badge {
    background: rgba(0, 151, 167, 0.12);
    color: var(--primary);
}

/* Integrated Table Search Box */
.table-search-box {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
    max-width: 320px;
}
.table-search-box .search-icon {
    position: absolute;
    left: 12px;
    font-size: 12px;
    pointer-events: none;
}
.table-search-box .search-input {
    padding-left: 32px;
    padding-right: 32px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    font-size: 13px;
    background: #f8fafc;
    transition: border-color 0.15s ease, background 0.15s ease, box-shadow 0.15s ease;
}
.table-search-box .search-input:focus {
    background: #ffffff;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(0, 151, 167, 0.12);
}
.search-kbd-hint {
    position: absolute;
    right: 10px;
    font-size: 11px;
    color: #94a3b8;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 1px 5px;
    pointer-events: none;
    line-height: 1.2;
}

/* Inventory Table Precision */
.inventory-table thead th {
    font-size: 11.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748b;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.inventory-row {
    transition: background-color 0.12s ease;
}
.inventory-row:hover {
    background-color: #f8fafc;
}
.inventory-quantity {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-weight: 700;
    font-size: 15px;
    color: #0f172a;
    font-feature-settings: "tnum" 1;
}

/* Empaque pill */
.empaque-pill {
    display: inline-flex;
    align-items: center;
    padding: 3px 8px;
    border-radius: 6px;
    background: #f1f5f9;
    color: #475569;
    font-size: 12px;
    border: 1px solid #e2e8f0;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 2px 9px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
}
.status-badge.status-high {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
}
.status-badge.status-medium {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}
.status-badge.status-low {
    background: #fffbeb;
    color: #b45309;
    border: 1px solid #fde68a;
}
.status-indicator-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: currentColor;
}

/* Empty State */
.empty-state-icon {
    width: 56px;
    height: 56px;
    margin: 0 auto;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const secStock = document.getElementById('section-stock');
    const secEnviados = document.getElementById('section-enviados');
    const secDevueltos = document.getElementById('section-devueltos');

    const btnStock = document.getElementById('btn-stock');
    const btnEnviados = document.getElementById('btn-enviados');
    const btnDevueltos = document.getElementById('btn-devueltos');

    const searchInput = document.getElementById('filtro-global-bodega');
    const resultsCountLabel = document.getElementById('results-count-label');

    let currentSection = 'stock';

    function setActiveTab(button, sectionId) {
        [btnStock, btnEnviados, btnDevueltos].forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        [secStock, secEnviados, secDevueltos].forEach(sec => sec.style.display = 'none');

        if (sectionId === 'stock') {
            secStock.style.display = 'block';
            currentSection = 'stock';
        } else if (sectionId === 'enviados') {
            secEnviados.style.display = 'block';
            currentSection = 'enviados';
        } else {
            secDevueltos.style.display = 'block';
            currentSection = 'devueltos';
        }

        applyFilter();
    }

    btnStock.addEventListener('click', () => setActiveTab(btnStock, 'stock'));
    btnEnviados.addEventListener('click', () => setActiveTab(btnEnviados, 'enviados'));
    btnDevueltos.addEventListener('click', () => setActiveTab(btnDevueltos, 'devueltos'));

    // Filtro global reactivo
    function applyFilter() {
        const query = searchInput.value.trim().toLowerCase();
        let targetTable;

        if (currentSection === 'stock') {
            targetTable = document.getElementById('tabla-stock');
        } else if (currentSection === 'enviados') {
            targetTable = document.getElementById('tabla-enviados');
        } else {
            targetTable = document.getElementById('tabla-devueltos');
        }

        if (!targetTable) return;

        const rows = targetTable.querySelectorAll('tbody tr.inventory-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matches = text.includes(query);
            row.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });

        if (query) {
            resultsCountLabel.textContent = `Mostrando ${visibleCount} de ${rows.length} registros para "${query}"`;
        } else {
            if (currentSection === 'stock') {
                resultsCountLabel.textContent = `Mostrando las ${rows.length} variedades disponibles en stock`;
            } else if (currentSection === 'enviados') {
                resultsCountLabel.textContent = `Mostrando ${rows.length} registros de recepciones`;
            } else {
                resultsCountLabel.textContent = `Mostrando ${rows.length} registros de devoluciones`;
            }
        }
    }

    searchInput.addEventListener('input', applyFilter);

    // Atajo de teclado: presionar '/' enfoca el buscador inmediatamente
    document.addEventListener('keydown', function(e) {
        if (e.key === '/' && document.activeElement !== searchInput && !['input', 'textarea'].includes(document.activeElement.tagName.toLowerCase())) {
            e.preventDefault();
            searchInput.focus();
        }
    });
});
</script>
@endsection
