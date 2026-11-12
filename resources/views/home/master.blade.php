@extends('layouts.app')

@php
    $cargo = auth()->user()->cargoNombre();
@endphp

@section('content')
<div class="container-fluid py-2">
    <!-- Banner de Bienvenida -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" 
         style="background: linear-gradient(135deg, #0097a7 0%, #006064 100%); color: #fff;">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <span class="badge rounded-pill bg-white text-dark px-3 py-1.5 fw-semibold mb-2" style="font-size: 12px;">
                        <i class="fas fa-crown text-warning me-1"></i> Panel Maestro de Control
                    </span>
                    <h2 class="fw-bold mb-1" style="letter-spacing: -0.5px;">Bienvenido, {{ auth()->user()->name }}</h2>
                    <p class="mb-0 opacity-90" style="font-size: 14.5px;">
                        Rol asignado: <strong>{{ $cargo }}</strong> | Acceso total a todos los módulos y operaciones del sistema Anturios.
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('kpis.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm fw-semibold text-primary">
                        <i class="fas fa-chart-line me-1"></i> Ver KPIs
                    </a>
                    <a href="{{ route('venta.index') }}" class="btn btn-warning rounded-pill px-4 shadow-sm fw-bold">
                        <i class="fas fa-cash-register me-1"></i> Ver Ventas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección 1: Comercial y Ventas -->
    <div class="mb-4">
        <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 12px; letter-spacing: 0.8px;">
            <i class="fas fa-shopping-bag me-1 text-primary"></i> Comercial y Ventas
        </h6>
        <div class="row g-3">
            @can('ver ventas')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('venta.index') }}" class="card h-100 border-0 shadow-sm rounded-4 text-decoration-none text-dark transition-all hover-lift">
                    <div class="card-body p-3.5 d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                            <i class="fas fa-cash-register fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-6 mb-0">Ventas</div>
                            <small class="text-muted" style="font-size: 12px;">Historial, facturas y abonos</small>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('clientes.index') }}" class="card h-100 border-0 shadow-sm rounded-4 text-decoration-none text-dark transition-all hover-lift">
                    <div class="card-body p-3.5 d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 48px; height: 48px; background: rgba(0, 151, 167, 0.12); color: #0097a7;">
                            <i class="fas fa-user-friends fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-6 mb-0">Clientes</div>
                            <small class="text-muted" style="font-size: 12px;">Directorio y compras</small>
                        </div>
                    </div>
                </a>
            </div>

            @can('ver kpis')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('kpis.index') }}" class="card h-100 border-0 shadow-sm rounded-4 text-decoration-none text-dark transition-all hover-lift">
                    <div class="card-body p-3.5 d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.12); color: #10b981;">
                            <i class="fas fa-chart-line fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-6 mb-0">KPIs y Analítica</div>
                            <small class="text-muted" style="font-size: 12px;">Métricas ejecutivas y mapas</small>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('catalogo.index') }}" target="_blank" class="card h-100 border-0 shadow-sm rounded-4 text-decoration-none text-dark transition-all hover-lift">
                    <div class="card-body p-3.5 d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 48px; height: 48px; background: rgba(236, 72, 153, 0.12); color: #ec4899;">
                            <i class="fas fa-store fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-6 mb-0">Catálogo Público</div>
                            <small class="text-muted" style="font-size: 12px;">Vitrina online para clientes</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Sección 2: Inventario y Almacenes -->
    <div class="mb-4">
        <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 12px; letter-spacing: 0.8px;">
            <i class="fas fa-boxes me-1 text-primary"></i> Inventario y Almacenes
        </h6>
        <div class="row g-3">
            @can('ver bodegas')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('bodegas.index') }}" class="card h-100 border-0 shadow-sm rounded-4 text-decoration-none text-dark transition-all hover-lift">
                    <div class="card-body p-3.5 d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                            <i class="fas fa-warehouse fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-6 mb-0">Bodegas</div>
                            <small class="text-muted" style="font-size: 12px;">Gestión de puntos de stock</small>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            @can('ver productos')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('productos.index') }}" class="card h-100 border-0 shadow-sm rounded-4 text-decoration-none text-dark transition-all hover-lift">
                    <div class="card-body p-3.5 d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 48px; height: 48px; background: rgba(5, 150, 105, 0.12); color: #059669;">
                            <i class="fas fa-cubes fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-6 mb-0">Productos</div>
                            <small class="text-muted" style="font-size: 12px;">Catálogo y control de stock</small>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            @can('ver transacciones')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('transaccionProducto.index') }}" class="card h-100 border-0 shadow-sm rounded-4 text-decoration-none text-dark transition-all hover-lift">
                    <div class="card-body p-3.5 d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 48px; height: 48px; background: rgba(8, 145, 178, 0.12); color: #0891b2;">
                            <i class="fas fa-exchange-alt fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-6 mb-0">Movimientos</div>
                            <small class="text-muted" style="font-size: 12px;">Transferencias entre bodegas</small>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            @can('ver notas')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('tipoNota.index') }}" class="card h-100 border-0 shadow-sm rounded-4 text-decoration-none text-dark transition-all hover-lift">
                    <div class="card-body p-3.5 d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 48px; height: 48px; background: rgba(124, 58, 237, 0.12); color: #7c3aed;">
                            <i class="fas fa-file-invoice fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-6 mb-0">Notas de Pedido</div>
                            <small class="text-muted" style="font-size: 12px;">Envíos y devoluciones</small>
                        </div>
                    </div>
                </a>
            </div>
            @endcan
        </div>
    </div>

    <!-- Sección 3: Personal, Seguridad y Configuración -->
    <div class="mb-4">
        <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 12px; letter-spacing: 0.8px;">
            <i class="fas fa-cog me-1 text-primary"></i> Personal, Accesos y Auditoría
        </h6>
        <div class="row g-3">
            @can('ver empleados')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('empleados.index') }}" class="card h-100 border-0 shadow-sm rounded-4 text-decoration-none text-dark transition-all hover-lift">
                    <div class="card-body p-3.5 d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 48px; height: 48px; background: rgba(220, 38, 38, 0.12); color: #dc2626;">
                            <i class="fas fa-users fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-6 mb-0">Empleados</div>
                            <small class="text-muted" style="font-size: 12px;">Personal, cargos y bodegas</small>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            @can('ver usuarios')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('users.index') }}" class="card h-100 border-0 shadow-sm rounded-4 text-decoration-none text-dark transition-all hover-lift">
                    <div class="card-body p-3.5 d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 48px; height: 48px; background: rgba(37, 99, 235, 0.12); color: #2563eb;">
                            <i class="fas fa-user-shield fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-6 mb-0">Usuarios</div>
                            <small class="text-muted" style="font-size: 12px;">Cuentas de acceso y credenciales</small>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            @can('ver roles')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('roles.index') }}" class="card h-100 border-0 shadow-sm rounded-4 text-decoration-none text-dark transition-all hover-lift">
                    <div class="card-body p-3.5 d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 48px; height: 48px; background: rgba(168, 85, 247, 0.12); color: #a855f7;">
                            <i class="fas fa-lock fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-6 mb-0">Roles y Permisos</div>
                            <small class="text-muted" style="font-size: 12px;">Matriz de seguridad Spatie</small>
                        </div>
                    </div>
                </a>
            </div>
            @endcan

            @can('ver auditorias')
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('auditorias.index') }}" class="card h-100 border-0 shadow-sm rounded-4 text-decoration-none text-dark transition-all hover-lift">
                    <div class="card-body p-3.5 d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                            <i class="fas fa-history fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-6 mb-0">Auditorías</div>
                            <small class="text-muted" style="font-size: 12px;">Logs de actividad del sistema</small>
                        </div>
                    </div>
                </a>
            </div>
            @endcan
        </div>
    </div>
</div>

<style>
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
}
</style>
@endsection
