@extends('layouts.app')

@section('content')
<div class="container-fluid py-3" id="kpi-dashboard-print">

    {{-- Encabezado con Logo de la Empresa (SOLO visible al imprimir) --}}
    <div id="print-header" style="display: none;">
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="width: 120px; vertical-align: middle; padding-right: 15px; border: none;">
                    <img src="{{ asset('images/logo-empresa.png') }}" style="width: 110px; height: auto;" alt="Logo Empresa">
                </td>
                <td style="vertical-align: middle; border: none;">
                    <div style="font-size: 22px; font-weight: bold; color: #333;">Importadora Anturios</div>
                    <div style="font-size: 16px; color: #666; margin-top: 2px;">Informe de KPIs y Desempeño Empresarial</div>
                    <div style="font-size: 12px; color: #999; margin-top: 4px;">
                        Generado: {{ now()->format('d/m/Y H:i') }} |
                        Período: <span id="print-periodo-label"></span>
                    </div>
                </td>
                <td style="text-align: right; vertical-align: middle; border: none; width: 140px;">
                    <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 1px;">Sistema de Gestión</div>
                    <div style="font-size: 11px; color: #999;">Reporte Ejecutivo</div>
                </td>
            </tr>
        </table>
        <hr style="border: none; border-top: 2px solid #e11d48; margin: 0 0 15px 0;">
    </div>

    {{-- Encabezado del Dashboard y Filtros --}}
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold px-3 py-1" style="font-size: 12px; background: rgba(225, 29, 72, 0.1); color: var(--accent) !important;">
                    <i class="fas fa-chart-line me-1"></i> Inteligencia Empresarial
                </span>
                <span class="text-muted" style="font-size: 13px;">| Actualizado en tiempo real</span>
            </div>
            <h2 class="fw-bold mb-1 mt-1" style="color: var(--foreground); letter-spacing: -0.5px;">Dashboard de KPIs y Desempeño</h2>
            <p class="text-muted mb-0" style="font-size: 14px;">Monitor ejecutivo de rendimiento comercial, rotación, cartera y cobertura geográfica.</p>
        </div>

        {{-- Barra de Filtros interactiva --}}
        <form method="GET" action="{{ route('kpis.index') }}" class="d-flex flex-wrap align-items-center gap-2 bg-white p-2 rounded-3 shadow-sm border" style="position: relative; z-index: 10;">
            <div>
                <select name="periodo" class="form-select form-select-sm border-0 bg-light font-weight-medium" onchange="this.form.submit()" style="font-size: 13px; font-weight: 500;">
                    <option value="ultimos_7_dias" {{ $periodo == 'ultimos_7_dias' ? 'selected' : '' }}>Últimos 7 Días</option>
                    <option value="ultimos_30_dias" {{ $periodo == 'ultimos_30_dias' ? 'selected' : '' }}>Últimos 30 Días</option>
                    <option value="este_mes" {{ $periodo == 'este_mes' ? 'selected' : '' }}>Este Mes</option>
                    <option value="ultimo_trimestre" {{ $periodo == 'ultimo_trimestre' ? 'selected' : '' }}>Último Trimestre</option>
                    <option value="este_ano" {{ $periodo == 'este_ano' ? 'selected' : '' }}>Este Año</option>
                    <option value="todo" {{ $periodo == 'todo' ? 'selected' : '' }}>Histórico Completo</option>
                </select>
            </div>

            <div class="vr mx-1 d-none d-sm-block" style="height: 24px;"></div>

            <div>
                <select name="bodega_id" class="form-select form-select-sm border-0 bg-light" onchange="this.form.submit()" style="font-size: 13px; font-weight: 500; min-width: 200px; padding-right: 2rem;">
                    <option value="">Todas las Bodegas</option>
                    @foreach($bodegas as $b)
                        <option value="{{ $b->idbodega }}" {{ $bodegaId == $b->idbodega ? 'selected' : '' }}>
                            {{ $b->nombrebodega }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1 ms-auto" onclick="prepararImpresion()" title="Imprimir Informe KPI">
                <i class="fas fa-print"></i>
                <span class="d-none d-sm-inline">Exportar / Imprimir</span>
            </button>
        </form>
    </div>

    {{-- Tarjetas Resumen Ejecutivo Top --}}
    <div class="row g-3 mb-4">
        {{-- Card 1: Top Producto --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-left: 4px solid #e11d48 !important; background: var(--card-bg, #fff);">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fw-semibold" style="font-size: 12px; text-uppercase; letter-spacing: 0.5px;">Producto Más Vendido</span>
                        <div class="p-2 rounded-circle" style="background: rgba(225, 29, 72, 0.1); color: #e11d48;">
                            <i class="fas fa-crown" style="font-size: 16px;"></i>
                        </div>
                    </div>
                    @if($topProductosFormatted->isNotEmpty())
                        @php $top1 = $topProductosFormatted->first(); @endphp
                        <div class="fw-bold text-truncate" style="font-size: 17px; color: var(--foreground);" title="{{ $top1->nombre }}">
                            {{ $top1->nombre }}
                        </div>
                        <div class="d-flex align-items-baseline gap-2 mt-1">
                            <span class="fs-4 fw-bold" style="color: #e11d48;">{{ number_format($top1->total_unidades) }}</span>
                            <span class="text-muted" style="font-size: 13px;">unidades (${{ number_format($top1->total_ingresos, 2) }})</span>
                        </div>
                    @else
                        <div class="text-muted" style="font-size: 14px;">Sin ventas registradas</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Card 2: Devoluciones --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-left: 4px solid #f59e0b !important; background: var(--card-bg, #fff);">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fw-semibold" style="font-size: 12px; text-uppercase; letter-spacing: 0.5px;">Tasa de Devolución</span>
                        <div class="p-2 rounded-circle" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                            <i class="fas fa-undo-alt" style="font-size: 16px;"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="fs-4 fw-bold" style="color: var(--foreground);">{{ $tasaDevolucionGlobal }}%</span>
                        @if($tasaDevolucionGlobal <= 3)
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 11px;">Excelente (&lt;3%)</span>
                        @elseif($tasaDevolucionGlobal <= 7)
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill" style="font-size: 11px;">Aceptable</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill" style="font-size: 11px;">Atención Requerida</span>
                        @endif
                    </div>
                    <div class="text-muted mt-1" style="font-size: 12px;">Promedio de retorno sobre movimiento de stock</div>
                </div>
            </div>
        </div>

        {{-- Card 3: Recuperación de Cartera --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-left: 4px solid #10b981 !important; background: var(--card-bg, #fff);">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fw-semibold" style="font-size: 12px; text-uppercase; letter-spacing: 0.5px;">Recuperación de Cartera</span>
                        <div class="p-2 rounded-circle" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                            <i class="fas fa-hand-holding-usd" style="font-size: 16px;"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="fs-4 fw-bold" style="color: #10b981;">{{ $indiceRecuperacion }}%</span>
                        <span class="text-muted" style="font-size: 12px;">(${{ number_format($totalRecaudado, 2) }})</span>
                    </div>
                    <div class="text-muted mt-1" style="font-size: 12px;">
                        Saldo pendiente por cobrar: <strong>${{ number_format($saldoPendienteCartera, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Provincia Top --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-left: 4px solid #6366f1 !important; background: var(--card-bg, #fff);">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fw-semibold" style="font-size: 12px; text-uppercase; letter-spacing: 0.5px;">Provincia Líder</span>
                        <div class="p-2 rounded-circle" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                            <i class="fas fa-map-marked-alt" style="font-size: 16px;"></i>
                        </div>
                    </div>
                    @if($ventasPorProvincia->isNotEmpty())
                        @php $provTop = $ventasPorProvincia->first(); @endphp
                        <div class="fw-bold" style="font-size: 17px; color: var(--foreground);">
                            {{ $provTop['provincia'] }}
                        </div>
                        <div class="d-flex align-items-baseline gap-2 mt-1">
                            <span class="fs-4 fw-bold" style="color: #6366f1;">${{ number_format($provTop['monto_total'], 2) }}</span>
                            <span class="badge bg-indigo-subtle text-indigo" style="font-size: 11px; background: rgba(99, 102, 241, 0.1); color: #6366f1;">
                                {{ $provTop['porcentaje'] }}% del total
                            </span>
                        </div>
                    @else
                        <div class="text-muted" style="font-size: 14px;">Sin datos por provincia</div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- SECCIÓN DE GRÁFICAS KPI (2 FILAS x 2 COLUMNAS) --}}
    <div class="row g-4 mb-4">

        {{-- ========================================================= --}}
        {{-- KPI 1: PRODUCTO MÁS VENDIDO --}}
        {{-- ========================================================= --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-0" style="font-size: 16px; color: var(--foreground);">
                            <i class="fas fa-award text-danger me-2"></i> KPI 1: Productos Más Vendidos
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 12px;">Ranking de volumen de ventas por unidad e ingresos generados</p>
                    </div>
                    <span class="badge bg-light text-dark border">Top Products</span>
                </div>
                <div class="card-body">
                    <div style="height: 260px; position: relative;">
                        <canvas id="chartTopProductos"></canvas>
                    </div>

                    {{-- Tabla de Ranking de Productos --}}
                    <div class="table-responsive mt-3">
                        <table class="table table-sm table-hover align-middle mb-0" style="font-size: 13px;">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th class="text-end">Unidades</th>
                                    <th class="text-end">Ingresos</th>
                                    <th style="width: 25%;">Participación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProductosFormatted as $index => $prod)
                                    <tr>
                                        <td>
                                            @if($index == 0)
                                                <span class="badge bg-warning text-dark"><i class="fas fa-trophy"></i> 1</span>
                                            @elseif($index == 1)
                                                <span class="badge bg-secondary">2</span>
                                            @elseif($index == 2)
                                                <span class="badge" style="background: #b45309; color: #fff;">3</span>
                                            @else
                                                <span class="text-muted fw-semibold">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="fw-medium text-truncate" style="max-width: 150px;" title="{{ $prod->nombre }}">
                                            {{ $prod->nombre }}
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format($prod->total_unidades) }}</td>
                                        <td class="text-end text-success fw-semibold">${{ number_format($prod->total_ingresos, 2) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-grow-1" style="height: 6px;">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $prod->porcentaje }}%"></div>
                                                </div>
                                                <span class="text-muted" style="font-size: 11px;">{{ $prod->porcentaje }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">No hay datos de productos disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- KPI 2: PORCENTAJE DE DEVOLUCIONES SEMANALES --}}
        {{-- ========================================================= --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-0" style="font-size: 16px; color: var(--foreground);">
                            <i class="fas fa-chart-area text-warning me-2"></i> KPI 2: % Devoluciones Semanales
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 12px;">Evolución y tasa de retornos de mercadería sobre movimientos de bodega</p>
                    </div>
                    <span class="badge bg-light text-dark border">Tendencia Semanal</span>
                </div>
                <div class="card-body">
                    <div style="height: 260px; position: relative;">
                        <canvas id="chartDevoluciones"></canvas>
                    </div>

                    {{-- Indicadores de referencia --}}
                    <div class="row g-2 text-center mt-3 pt-2 border-top">
                        <div class="col-4">
                            <div class="p-2 rounded bg-light">
                                <span class="text-muted d-block" style="font-size: 11px;">Límite Objetivo</span>
                                <strong class="text-success" style="font-size: 14px;">&le; 5.0%</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-light">
                                <span class="text-muted d-block" style="font-size: 11px;">Tasa Actual</span>
                                <strong class="{{ $tasaDevolucionGlobal <= 5 ? 'text-success' : 'text-danger' }}" style="font-size: 14px;">
                                    {{ $tasaDevolucionGlobal }}%
                                </strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-light">
                                <span class="text-muted d-block" style="font-size: 11px;">Estado Control</span>
                                @if($tasaDevolucionGlobal <= 3)
                                    <span class="badge bg-success" style="font-size: 11px;">Óptimo</span>
                                @elseif($tasaDevolucionGlobal <= 6)
                                    <span class="badge bg-warning text-dark" style="font-size: 11px;">Normal</span>
                                @else
                                    <span class="badge bg-danger" style="font-size: 11px;">Alerta</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4 mb-4">

        {{-- ========================================================= --}}
        {{-- KPI 3: ÍNDICE DE RECUPERACIÓN DE CARTERA --}}
        {{-- ========================================================= --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-0" style="font-size: 16px; color: var(--foreground);">
                            <i class="fas fa-hand-holding-usd text-success me-2"></i> KPI 3: Recuperación de Cartera
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 12px;">Relación entre crédito otorgado y cobros recaudados por abonos</p>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">Financiero</span>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12 col-sm-5 text-center mb-3 mb-sm-0">
                            <div style="height: 200px; position: relative;" class="d-flex align-items-center justify-content-center">
                                <canvas id="chartGaugeCartera"></canvas>
                            </div>
                            <div class="mt-2">
                                <span class="fs-4 fw-bold text-success">{{ $indiceRecuperacion }}%</span>
                                <div class="text-muted" style="font-size: 12px;">Índice de Eficiencia</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-7">
                            <div class="p-3 bg-light rounded-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted" style="font-size: 13px;"><i class="fas fa-credit-card text-primary me-1"></i> Crédito Otorgado:</span>
                                    <span class="fw-bold" style="font-size: 14px;">${{ number_format($totalCreditoOtorgado, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted" style="font-size: 13px;"><i class="fas fa-check-circle text-success me-1"></i> Abonos Recaudados:</span>
                                    <span class="fw-bold text-success" style="font-size: 14px;">${{ number_format($totalRecaudado, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <span class="text-muted" style="font-size: 13px;"><i class="fas fa-exclamation-circle text-danger me-1"></i> Saldo Pendiente:</span>
                                    <span class="fw-bold text-danger" style="font-size: 15px;">${{ number_format($saldoPendienteCartera, 2) }}</span>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="d-flex justify-content-between text-muted mb-1" style="font-size: 12px;">
                                    <span>Progreso de Cobranza</span>
                                    <span>{{ $indiceRecuperacion }}%</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ min(100, $indiceRecuperacion) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Histórico mensual de cobranzas --}}
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-semibold mb-2" style="font-size: 13px; color: var(--foreground);">Evolución Mensual: Crédito vs Recaudación</h6>
                        <div style="height: 160px; position: relative;">
                            <canvas id="chartCarteraMensual"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- KPI 4: RENDIMIENTO DE VENTAS POR PROVINCIA --}}
        {{-- ========================================================= --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-0" style="font-size: 16px; color: var(--foreground);">
                            <i class="fas fa-map-marked-alt text-indigo me-2" style="color: #6366f1;"></i> KPI 4: Ventas por Provincia
                        </h5>
                        <p class="text-muted mb-0" style="font-size: 12px;">Distribución geográfica de facturación y concentración de mercado</p>
                    </div>
                    <span class="badge bg-indigo-subtle text-indigo border" style="background: rgba(99,102,241,0.1); color: #6366f1;">Geográfico</span>
                </div>
                <div class="card-body">
                    <div style="height: 250px; position: relative;">
                        <canvas id="chartProvincias"></canvas>
                    </div>

                    {{-- Tabla de Provincias --}}
                    <div class="table-responsive mt-3">
                        <table class="table table-sm table-hover align-middle mb-0" style="font-size: 13px;">
                            <thead class="bg-light">
                                <tr>
                                    <th>Provincia</th>
                                    <th class="text-center">Transacciones</th>
                                    <th class="text-end">Ticket Prom.</th>
                                    <th class="text-end">Total Ventas</th>
                                    <th class="text-end">% Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ventasPorProvincia as $prov)
                                    <tr>
                                        <td class="fw-semibold" style="color: var(--foreground);">
                                            <i class="fas fa-map-marker-alt text-danger me-1" style="font-size: 11px;"></i>
                                            {{ $prov['provincia'] }}
                                        </td>
                                        <td class="text-center">{{ $prov['num_transacciones'] }}</td>
                                        <td class="text-end">${{ number_format($prov['ticket_promedio'], 2) }}</td>
                                        <td class="text-end fw-bold text-primary">${{ number_format($prov['monto_total'], 2) }}</td>
                                        <td class="text-end">
                                            <span class="badge bg-indigo-subtle text-indigo" style="background: rgba(99,102,241,0.1); color: #6366f1; font-size: 11px;">
                                                {{ $prov['porcentaje'] }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">No hay registros de ventas por provincia</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Estilos personalizados e impresión --}}
<style>
/* Imágenes de gráficas generadas para impresión (ocultas por defecto en pantalla) */
.chart-print-img {
    display: none;
}

@media print {
    @page {
        size: A4 portrait;
        margin: 10mm 12mm;
    }

    /* Ocultar navegación y controles */
    #sidebar-wrapper, .sidebar-header, .user-profile, .sidebar-nav,
    .logout-container, form, button, .breadcrumb-section, .top-navbar,
    .badge.bg-light, .content-scrollable::before {
        display: none !important;
    }

    /* Mostrar encabezado de empresa */
    #print-header {
        display: block !important;
    }

    /* Romper restricciones de viewport del layout */
    html, body {
        height: auto !important;
        overflow: visible !important;
        background: #fff !important;
        margin: 0 !important;
        padding: 0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    #wrapper {
        display: block !important;
        height: auto !important;
        width: 100% !important;
        overflow: visible !important;
        padding: 0 !important;
    }

    #page-content-wrapper {
        display: block !important;
        overflow: visible !important;
        width: 100% !important;
    }

    .content-scrollable {
        overflow: visible !important;
        height: auto !important;
        padding: 0 !important;
    }

    .container-fluid {
        overflow: visible !important;
        padding: 0 !important;
    }

    /* Tarjetas: compactas, sin sombras */
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
        margin-bottom: 8px !important;
    }

    .card-header {
        padding: 8px 12px !important;
    }

    .card-body {
        padding: 10px 12px !important;
    }

    /* Reducir los gaps de las filas */
    .row.g-3, .row.g-4 {
        --bs-gutter-y: 0.5rem !important;
        --bs-gutter-x: 0.5rem !important;
    }

    .mb-4 {
        margin-bottom: 0.5rem !important;
    }

    /* Ocultar canvas, mostrar imágenes estáticas */
    canvas {
        display: none !important;
    }
    .chart-print-img {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        max-height: 220px;
        object-fit: contain;
        margin: 0 auto;
    }

    /* Tablas compactas */
    .table {
        font-size: 11px !important;
    }
    .table th, .table td {
        padding: 3px 6px !important;
    }

    /* Tarjetas resumen más compactas */
    .col-12.col-sm-6.col-xl-3 .card-body {
        padding: 8px 10px !important;
    }

    .fs-4 {
        font-size: 1.1rem !important;
    }
}
</style>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Opciones generales de tipografía y colores en Chart.js
    Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
    Chart.defaults.color = '#64748b';

    // -------------------------------------------------------------
    // GRÁFICO KPI 1: PRODUCTOS MÁS VENDIDOS (Barra Horizontal)
    // -------------------------------------------------------------
    const ctxTopProd = document.getElementById('chartTopProductos').getContext('2d');
    const topProdData = @json($topProductosFormatted);

    const labelsTopProd = topProdData.map(item => item.nombre.length > 20 ? item.nombre.substr(0, 18) + '...' : item.nombre);
    const unidadesTopProd = topProdData.map(item => item.total_unidades);
    const ingresosTopProd = topProdData.map(item => item.total_ingresos);

    new Chart(ctxTopProd, {
        type: 'bar',
        data: {
            labels: labelsTopProd,
            datasets: [{
                label: 'Unidades Vendidas',
                data: unidadesTopProd,
                backgroundColor: 'rgba(225, 29, 72, 0.85)',
                borderColor: '#e11d48',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        afterBody: function(context) {
                            const index = context[0].dataIndex;
                            const ing = ingresosTopProd[index];
                            return 'Ingresos Totales: $' + Number(ing).toLocaleString('en-US', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(0, 0, 0, 0.04)' },
                    ticks: { precision: 0 }
                },
                y: {
                    grid: { display: false }
                }
            }
        }
    });

    // -------------------------------------------------------------
    // GRÁFICO KPI 2: PORCENTAJE DE DEVOLUCIONES SEMANALES (Línea)
    // -------------------------------------------------------------
    const ctxDev = document.getElementById('chartDevoluciones').getContext('2d');
    const devolucionesData = @json($semanasDevoluciones);

    const labelsDev = devolucionesData.map(item => item.semana);
    const pctDev = devolucionesData.map(item => item.porcentaje);
    const cantDev = devolucionesData.map(item => item.devoluciones);
    const cantEnv = devolucionesData.map(item => item.envios);

    // Gradiente suave para la línea de devoluciones
    const gradDev = ctxDev.createLinearGradient(0, 0, 0, 260);
    gradDev.addColorStop(0, 'rgba(245, 158, 11, 0.35)');
    gradDev.addColorStop(1, 'rgba(245, 158, 11, 0.0)');

    new Chart(ctxDev, {
        type: 'line',
        data: {
            labels: labelsDev,
            datasets: [
                {
                    label: '% Tasa de Devolución',
                    data: pctDev,
                    borderColor: '#f59e0b',
                    backgroundColor: gradDev,
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointBackgroundColor: '#f59e0b',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Límite Máximo Objetivo (5%)',
                    data: Array(labelsDev.length).fill(5.0),
                    borderColor: '#ef4444',
                    borderWidth: 1.5,
                    borderDash: [5, 5],
                    pointRadius: 0,
                    fill: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { boxWidth: 12 } },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                const idx = context.dataIndex;
                                return '% Devolución: ' + context.raw + '% (' + cantDev[idx] + ' devueltos / ' + cantEnv[idx] + ' movidos)';
                            }
                            return context.dataset.label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.04)' },
                    ticks: {
                        callback: function(value) { return value + '%'; }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // -------------------------------------------------------------
    // GRÁFICO KPI 3A: GAUGE RECUPERACIÓN DE CARTERA (Doughnut)
    // -------------------------------------------------------------
    const ctxGauge = document.getElementById('chartGaugeCartera').getContext('2d');
    const indRecup = {{ $indiceRecuperacion }};
    const indPend = Math.max(0, 100 - indRecup);

    new Chart(ctxGauge, {
        type: 'doughnut',
        data: {
            labels: ['Recaudado (%)', 'Pendiente (%)'],
            datasets: [{
                data: [indRecup, indPend],
                backgroundColor: ['#10b981', '#e2e8f0'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw + '%';
                        }
                    }
                }
            }
        }
    });

    // -------------------------------------------------------------
    // GRÁFICO KPI 3B: HISTÓRICO CARTERA MENSUAL (Barras Agrupadas)
    // -------------------------------------------------------------
    const ctxCartMes = document.getElementById('chartCarteraMensual').getContext('2d');
    const carteraMensualData = @json($mesesCartera);

    const labelsCartMes = carteraMensualData.map(item => item.mes);
    const dataCreditoMes = carteraMensualData.map(item => item.credito);
    const dataRecaudadoMes = carteraMensualData.map(item => item.recaudado);

    new Chart(ctxCartMes, {
        type: 'bar',
        data: {
            labels: labelsCartMes,
            datasets: [
                {
                    label: 'Crédito Otorgado',
                    data: dataCreditoMes,
                    backgroundColor: 'rgba(99, 102, 241, 0.75)',
                    borderRadius: 4
                },
                {
                    label: 'Cobrado Recaudado',
                    data: dataRecaudadoMes,
                    backgroundColor: 'rgba(16, 185, 129, 0.85)',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { boxWidth: 10, font: { size: 10 } } },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': $' + Number(context.raw).toLocaleString('en-US', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.04)' },
                    ticks: {
                        font: { size: 10 },
                        callback: function(val) { return '$' + val; }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                }
            }
        }
    });

    // -------------------------------------------------------------
    // GRÁFICO KPI 4: VENTAS POR PROVINCIA (Doughnut / Pie)
    // -------------------------------------------------------------
    const ctxProv = document.getElementById('chartProvincias').getContext('2d');
    const provData = @json($ventasPorProvincia);

    const labelsProv = provData.map(item => item.provincia);
    const montosProv = provData.map(item => item.monto_total);

    const colorPalette = [
        '#6366f1', '#10b981', '#f59e0b', '#ec4899', '#3b82f6', 
        '#8b5cf6', '#14b8a6', '#f97316', '#06b6d4', '#64748b'
    ];

    new Chart(ctxProv, {
        type: 'doughnut',
        data: {
            labels: labelsProv,
            datasets: [{
                data: montosProv,
                backgroundColor: colorPalette.slice(0, labelsProv.length),
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        font: { size: 12 },
                        padding: 12
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const val = context.raw;
                            const total = montosProv.reduce((a, b) => a + b, 0);
                            const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                            return context.label + ': $' + Number(val).toLocaleString('en-US', {minimumFractionDigits: 2}) + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });

});

// ---------------------------------------------------------------
// FUNCIÓN DE IMPRESIÓN: Convertir canvas a imágenes estáticas
// para que todas las gráficas se muestren en el PDF impreso
// ---------------------------------------------------------------
function prepararImpresion() {
    // Establecer el texto del período en el encabezado de impresión
    const periodoSelect = document.querySelector('select[name="periodo"]');
    const labelSpan = document.getElementById('print-periodo-label');
    if (periodoSelect && labelSpan) {
        labelSpan.textContent = periodoSelect.options[periodoSelect.selectedIndex].text;
    }

    // Buscar todos los canvas de Chart.js
    const canvases = document.querySelectorAll('canvas');

    canvases.forEach(function(canvas) {
        // Eliminar imágenes previas si ya existían
        const existingImgs = canvas.parentNode.querySelectorAll('.chart-print-img');
        existingImgs.forEach(function(img) { img.remove(); });

        // Crear imagen estática a partir del canvas
        const img = document.createElement('img');
        img.src = canvas.toDataURL('image/png', 1.0);
        img.className = 'chart-print-img';
        img.alt = 'Gráfica KPI';

        // Insertar la imagen justo después del canvas
        canvas.parentNode.insertBefore(img, canvas.nextSibling);
    });

    // Pausa para renderizar imágenes y luego imprimir
    setTimeout(function() {
        window.print();
    }, 400);
}
</script>
@endsection
