@extends('layouts.app')

@php
    $totalUnidadesStock = collect($productosEnBodega)->sum('cantidad');
    $totalVariedadesStock = collect($productosEnBodega)->count();
    $totalEnviados = $productos->sum('cantidad');
    $totalDevueltos = $devueltos->sum('cantidad');
@endphp

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de la página -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h3 class="fw-bold mb-0">Inventario: {{ $bodega->nombrebodega }}</h3>
                <span class="badge rounded-pill bg-light text-muted border px-2.5 py-1 font-mono" style="font-size: 11.5px;">
                    ID: {{ $bodega->idbodega }}
                </span>
            </div>
            <p class="page-subtitle mb-0 text-muted">Control de existencias físicas disponibles, recepciones y devoluciones</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('bodegas.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Volver a Bodegas
            </a>
            <a href="{{ route('bodega.stock.pdf', $bodega->idbodega) }}" target="_blank" class="btn btn-outline-danger rounded-pill px-3 shadow-sm">
                <i class="fas fa-file-pdf me-1"></i> Reporte PDF de Stock
            </a>
            @can('registrar ventas')
                <a href="{{ route('venta.create', $bodega->idbodega) }}" class="btn btn-primary rounded-pill px-3 shadow-sm">
                    <i class="fas fa-plus me-1"></i> Registrar Venta
                </a>
            @endcan
            @can('ver ventas')
                <a href="{{ route('venta.index.bodega', $bodega->idbodega) }}" class="btn btn-outline-primary rounded-pill px-3 shadow-sm">
                    <i class="fas fa-receipt me-1"></i> Ver Ventas
                </a>
            @endcan
        </div>
    </div>

    <!-- Tarjetas de Resumen KPI -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                        <i class="fas fa-boxes fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Unidades Disponibles</small>
                        <h4 class="fw-bold mb-0 text-success">{{ number_format($totalUnidadesStock) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                        <i class="fas fa-tags fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Variedades en Stock</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalVariedadesStock }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(59, 130, 246, 0.12); color: #2563eb;">
                        <i class="fas fa-paper-plane fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total Recepciones</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalEnviados) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.12); color: #d97706;">
                        <i class="fas fa-undo fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total Devoluciones</small>
                        <h4 class="fw-bold mb-0 text-warning">{{ number_format($totalDevueltos) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pestañas de Navegación del Inventario -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3 d-flex flex-wrap gap-2">
            <button class="btn btn-sm rounded-pill px-3 btn-primary shadow-sm" id="btn-stock">
                <i class="fas fa-boxes me-1"></i> Stock Actual en Bodega ({{ $totalVariedadesStock }})
            </button>
            <button class="btn btn-sm rounded-pill px-3 btn-outline-secondary" id="btn-enviados">
                <i class="fas fa-paper-plane me-1"></i> Historial de Recepciones (Enviados)
            </button>
            <button class="btn btn-sm rounded-pill px-3 btn-outline-secondary" id="btn-devueltos">
                <i class="fas fa-undo me-1"></i> Historial de Devoluciones
            </button>
        </div>
    </div>

    <!-- SECCIÓN 1: STOCK ACTUAL EN BODEGA (VISIBLE POR DEFECTO) -->
    <div id="section-stock" class="mb-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                        <i class="fas fa-cubes" style="font-size: 13px;"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-dark">Existencias Físicas Actuales</h6>
                </div>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control form-control-sm rounded-pill" id="filtro-nombre-stock" placeholder="Filtrar por variedad o código..." style="max-width: 260px;">
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tabla-stock">
                        <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Código</th>
                                <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Variedad / Producto</th>
                                <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Empaque</th>
                                <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Descripción</th>
                                <th class="text-end pe-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Cantidad Disponible</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($productosEnBodega as $item)
                                <tr style="transition: background-color 0.15s ease;">
                                    <td class="ps-4 py-3">
                                        <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1 font-mono fw-bold" style="font-size: 12px;">
                                            {{ $item['codigo'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 fw-bold text-dark" style="font-size: 13.5px;">
                                        {{ $item['nombre'] }}
                                    </td>
                                    <td class="py-3">
                                        <span class="badge rounded-pill bg-light text-secondary border px-2.5 py-1" style="font-size: 11.5px;">
                                            {{ $item['tipoempaque'] ?? ($item['empaque'] ?? 'Unidad') }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-muted" style="font-size: 13px; max-width: 300px;">
                                        {{ $item['descripcion'] ?: '—' }}
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        <span class="badge rounded-pill px-3 py-1 font-mono fw-bold fs-6" style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;">
                                            {{ $item['cantidad'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="py-4">
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: #f1f5f9; color: #94a3b8;">
                                                <i class="fas fa-boxes fs-3"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1">No hay productos en stock</h6>
                                            <p class="text-muted mb-0" style="font-size: 13.5px;">Esta bodega no cuenta actualmente con existencias físicas registradas.</p>
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

    <!-- SECCIÓN 2: PRODUCTOS ENVIADOS / RECEPCIONES -->
    <div id="section-enviados" style="display:none;" class="mb-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(59, 130, 246, 0.12); color: #2563eb;">
                        <i class="fas fa-paper-plane" style="font-size: 13px;"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-dark">Historial de Productos Enviados a esta Bodega</h6>
                </div>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control form-control-sm rounded-pill" id="filtro-nombre-enviados" placeholder="Filtrar por nombre o código...">
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tabla-enviados">
                        <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Código</th>
                                <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Producto</th>
                                <th class="py-3 text-uppercase text-muted fw-bold text-center" style="font-size: 11.5px; letter-spacing: 0.5px;">Cantidad Enviada</th>
                                <th class="text-end pe-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Fecha de Envío</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($productos as $item)
                                <tr style="transition: background-color 0.15s ease;">
                                    <td class="ps-4 py-3">
                                        <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1 font-mono" style="font-size: 12px;">
                                            {{ $item->codigo }}
                                        </span>
                                    </td>
                                    <td class="py-3 fw-bold text-dark" style="font-size: 13.5px;">{{ $item->nombre }}</td>
                                    <td class="py-3 text-center">
                                        <span class="badge rounded-pill px-3 py-1 font-mono fw-bold" style="background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe;">
                                            {{ $item->cantidad }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4 py-3 font-mono text-muted" style="font-size: 12.5px;">
                                        <i class="far fa-calendar-alt me-1 text-muted"></i>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        No hay registros de envíos hacia esta bodega.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 3: PRODUCTOS DEVUELTOS -->
    <div id="section-devueltos" style="display:none;" class="mb-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(245, 158, 11, 0.12); color: #d97706;">
                        <i class="fas fa-undo" style="font-size: 13px;"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-dark">Historial de Productos Devueltos desde esta Bodega</h6>
                </div>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control form-control-sm rounded-pill" id="filtro-nombre-devueltos" placeholder="Filtrar por nombre o código...">
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tabla-devueltos">
                        <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Código</th>
                                <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Producto</th>
                                <th class="py-3 text-uppercase text-muted fw-bold text-center" style="font-size: 11.5px; letter-spacing: 0.5px;">Cantidad Devuelta</th>
                                <th class="text-end pe-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Fecha de Devolución</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($devueltos as $item)
                                <tr style="transition: background-color 0.15s ease;">
                                    <td class="ps-4 py-3">
                                        <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1 font-mono" style="font-size: 12px;">
                                            {{ $item->codigo }}
                                        </span>
                                    </td>
                                    <td class="py-3 fw-bold text-dark" style="font-size: 13.5px;">{{ $item->nombre }}</td>
                                    <td class="py-3 text-center">
                                        <span class="badge rounded-pill px-3 py-1 font-mono fw-bold" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                            {{ $item->cantidad }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4 py-3 font-mono text-muted" style="font-size: 12.5px;">
                                        <i class="far fa-calendar-alt me-1 text-muted"></i>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        No hay devoluciones registradas desde esta bodega.
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

    function setActiveTab(activeBtn, activeSec) {
        // Reset all buttons
        [btnStock, btnEnviados, btnDevueltos].forEach(b => {
            b.className = 'btn btn-sm rounded-pill px-3 btn-outline-secondary';
        });
        // Set active button
        activeBtn.className = 'btn btn-sm rounded-pill px-3 btn-primary shadow-sm';

        // Hide all sections
        [secStock, secEnviados, secDevueltos].forEach(s => s.style.display = 'none');
        // Show active section
        activeSec.style.display = 'block';
    }

    btnStock.addEventListener('click', () => setActiveTab(btnStock, secStock));
    btnEnviados.addEventListener('click', () => setActiveTab(btnEnviados, secEnviados));
    btnDevueltos.addEventListener('click', () => setActiveTab(btnDevueltos, secDevueltos));

    // Filtros rápidos de tabla
    function attachFilter(inputId, tableId) {
        const input = document.getElementById(inputId);
        const table = document.getElementById(tableId);
        if (!input || !table) return;

        input.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            Array.from(table.querySelectorAll('tbody tr')).forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    attachFilter('filtro-nombre-stock', 'tabla-stock');
    attachFilter('filtro-nombre-enviados', 'tabla-enviados');
    attachFilter('filtro-nombre-devueltos', 'tabla-devueltos');
});
</script>
@endsection
