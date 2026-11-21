@extends('layouts.app')

@php
    $cargo = auth()->user()->cargoNombre();
@endphp

@section('content')
<div class="container-fluid py-3 px-3 px-md-4">
    <!-- Breadcrumb & Wayfinding -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                    <i class="fas fa-th-large me-1 text-primary"></i> Panel Principal
                </li>
            </ol>
        </nav>
        <div class="text-muted font-mono" style="font-size: 12.5px;">
            <i class="far fa-calendar-alt me-1 text-muted"></i>{{ now()->format('d/m/Y') }}
        </div>
    </div>

    <!-- Encabezado Maestro Ejecutivo (Estilo Impeccable) -->
    <div class="card border rounded-3 p-4 mb-4 bg-white" style="border-color: #e2e8f0 !important;">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold px-2.5 py-1" style="font-size: 11px;">
                        Control Central
                    </span>
                    <span class="text-muted" style="font-size: 12px;">· {{ $cargo }}</span>
                </div>
                <h2 class="fw-bold mb-1 text-dark tracking-tight" style="letter-spacing: -0.025em;">
                    Bienvenido, {{ auth()->user()->name }}
                </h2>
                <p class="text-muted mb-0" style="font-size: 14px;">
                    Acceso autorizado al centro de operaciones, inventarios, comercialización y gestión de Anturios.
                </p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                @can('ver kpis')
                <a href="{{ route('kpis.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                    <i class="fas fa-chart-line me-1.5 text-primary"></i> Analítica & KPIs
                </a>
                @endcan
                @can('ver ventas')
                <a href="{{ route('venta.index') }}" class="btn btn-sm btn-primary rounded-3 px-3 py-2 fw-semibold shadow-sm">
                    <i class="fas fa-receipt me-1.5"></i> Gestión de Ventas
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Sección 1: Comercial y Ventas -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold text-uppercase text-muted mb-0" style="font-size: 11.5px; letter-spacing: 0.05em;">
                <i class="fas fa-shopping-bag me-1 text-primary"></i> Comercial y Ventas
            </h6>
        </div>
        <div class="row g-3">
            @can('ver ventas')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('venta.index') }}" class="card h-100 border rounded-3 text-decoration-none text-dark hover-lift p-3" style="border-color: #e2e8f0 !important; background: #fff;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 44px; height: 44px; background: rgba(0, 151, 167, 0.1); color: var(--primary);">
                            <i class="fas fa-cash-register fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-dark mb-0" style="font-size: 14px;">Ventas</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 12px;">Facturación y abonos</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted opacity-50 ms-2" style="font-size: 11px;"></i>
                    </div>
                </a>
            </div>
            @endcan

            @can('ver ventas')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('clientes.index') }}" class="card h-100 border rounded-3 text-decoration-none text-dark hover-lift p-3" style="border-color: #e2e8f0 !important; background: #fff;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 44px; height: 44px; background: rgba(59, 130, 246, 0.1); color: #2563eb;">
                            <i class="fas fa-user-friends fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-dark mb-0" style="font-size: 14px;">Clientes</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 12px;">Directorio y carteras</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted opacity-50 ms-2" style="font-size: 11px;"></i>
                    </div>
                </a>
            </div>
            @endcan

            @can('ver kpis')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('kpis.index') }}" class="card h-100 border rounded-3 text-decoration-none text-dark hover-lift p-3" style="border-color: #e2e8f0 !important; background: #fff;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 44px; height: 44px; background: rgba(16, 185, 129, 0.1); color: #059669;">
                            <i class="fas fa-chart-line fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-dark mb-0" style="font-size: 14px;">KPIs y Analítica</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 12px;">Métricas ejecutivas</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted opacity-50 ms-2" style="font-size: 11px;"></i>
                    </div>
                </a>
            </div>
            @endcan

            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('catalogo.index') }}" target="_blank" class="card h-100 border rounded-3 text-decoration-none text-dark hover-lift p-3" style="border-color: #e2e8f0 !important; background: #fff;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 44px; height: 44px; background: rgba(236, 72, 153, 0.1); color: #db2777;">
                            <i class="fas fa-store fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-dark mb-0" style="font-size: 14px;">Catálogo Público</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 12px;">Vitrina online exterior</small>
                        </div>
                        <i class="fas fa-external-link-alt text-muted opacity-50 ms-2" style="font-size: 10px;"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Sección 2: Inventario y Almacenes -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold text-uppercase text-muted mb-0" style="font-size: 11.5px; letter-spacing: 0.05em;">
                <i class="fas fa-boxes me-1 text-primary"></i> Inventario y Almacenes
            </h6>
        </div>
        <div class="row g-3">
            @can('ver bodegas')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('bodegas.index') }}" class="card h-100 border rounded-3 text-decoration-none text-dark hover-lift p-3" style="border-color: #e2e8f0 !important; background: #fff;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 44px; height: 44px; background: rgba(245, 158, 11, 0.1); color: #d97706;">
                            <i class="fas fa-warehouse fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-dark mb-0" style="font-size: 14px;">Bodegas</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 12px;">Puntos físicos de stock</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted opacity-50 ms-2" style="font-size: 11px;"></i>
                    </div>
                </a>
            </div>
            @endcan

            @can('ver productos')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('productos.index') }}" class="card h-100 border rounded-3 text-decoration-none text-dark hover-lift p-3" style="border-color: #e2e8f0 !important; background: #fff;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 44px; height: 44px; background: rgba(16, 185, 129, 0.1); color: #059669;">
                            <i class="fas fa-cubes fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-dark mb-0" style="font-size: 14px;">Productos</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 12px;">Catálogo y embalajes</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted opacity-50 ms-2" style="font-size: 11px;"></i>
                    </div>
                </a>
            </div>
            @endcan

            @can('ver transacciones')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('transaccionProducto.index') }}" class="card h-100 border rounded-3 text-decoration-none text-dark hover-lift p-3" style="border-color: #e2e8f0 !important; background: #fff;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 44px; height: 44px; background: rgba(6, 182, 212, 0.1); color: #0891b2;">
                            <i class="fas fa-exchange-alt fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-dark mb-0" style="font-size: 14px;">Movimientos</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 12px;">Transferencias de stock</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted opacity-50 ms-2" style="font-size: 11px;"></i>
                    </div>
                </a>
            </div>
            @endcan

            @can('ver notas')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('tipoNota.index') }}" class="card h-100 border rounded-3 text-decoration-none text-dark hover-lift p-3" style="border-color: #e2e8f0 !important; background: #fff;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 44px; height: 44px; background: rgba(147, 51, 234, 0.1); color: #7e22ce;">
                            <i class="fas fa-file-invoice fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-dark mb-0" style="font-size: 14px;">Notas de Pedido</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 12px;">Envíos y devoluciones</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted opacity-50 ms-2" style="font-size: 11px;"></i>
                    </div>
                </a>
            </div>
            @endcan
        </div>
    </div>

    <!-- Sección 3: Personal, Accesos y Auditoría -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold text-uppercase text-muted mb-0" style="font-size: 11.5px; letter-spacing: 0.05em;">
                <i class="fas fa-shield-alt me-1 text-primary"></i> Personal, Accesos y Auditoría
            </h6>
        </div>
        <div class="row g-3">
            @can('ver empleados')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('empleados.index') }}" class="card h-100 border rounded-3 text-decoration-none text-dark hover-lift p-3" style="border-color: #e2e8f0 !important; background: #fff;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 44px; height: 44px; background: rgba(239, 68, 68, 0.1); color: #dc2626;">
                            <i class="fas fa-users fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-dark mb-0" style="font-size: 14px;">Empleados</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 12px;">Nómina y asignación</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted opacity-50 ms-2" style="font-size: 11px;"></i>
                    </div>
                </a>
            </div>
            @endcan

            @can('ver usuarios')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('users.index') }}" class="card h-100 border rounded-3 text-decoration-none text-dark hover-lift p-3" style="border-color: #e2e8f0 !important; background: #fff;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 44px; height: 44px; background: rgba(59, 130, 246, 0.1); color: #2563eb;">
                            <i class="fas fa-user-shield fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-dark mb-0" style="font-size: 14px;">Usuarios</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 12px;">Cuentas y credenciales</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted opacity-50 ms-2" style="font-size: 11px;"></i>
                    </div>
                </a>
            </div>
            @endcan

            @can('ver roles')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('roles.index') }}" class="card h-100 border rounded-3 text-decoration-none text-dark hover-lift p-3" style="border-color: #e2e8f0 !important; background: #fff;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 44px; height: 44px; background: rgba(168, 85, 247, 0.1); color: #9333ea;">
                            <i class="fas fa-lock fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-dark mb-0" style="font-size: 14px;">Roles y Permisos</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 12px;">Matriz de seguridad</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted opacity-50 ms-2" style="font-size: 11px;"></i>
                    </div>
                </a>
            </div>
            @endcan

            @can('ver auditorias')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('auditorias.index') }}" class="card h-100 border rounded-3 text-decoration-none text-dark hover-lift p-3" style="border-color: #e2e8f0 !important; background: #fff;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 44px; height: 44px; background: rgba(245, 158, 11, 0.1); color: #d97706;">
                            <i class="fas fa-history fs-5"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-bold text-dark mb-0" style="font-size: 14px;">Auditorías</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 12px;">Trazabilidad y bitácora</small>
                        </div>
                        <i class="fas fa-chevron-right text-muted opacity-50 ms-2" style="font-size: 11px;"></i>
                    </div>
                </a>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection