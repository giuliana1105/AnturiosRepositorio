@extends('layouts.app')

@php
    $cargo = auth()->user()->cargoNombre();
@endphp

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Dashboard</h3>
            <p class="page-subtitle">Panel principal de gestión — {{ $cargo }}</p>
        </div>
    </div>

    <div class="row g-3">
        @can('ver empleados')
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('empleados.index') }}" class="stat-card">
                <div class="stat-icon" style="background: rgba(255,152,0,0.1); color: #f59e0b;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-label">Empleados</div>
                <div class="stat-sublabel">Gestión de personal</div>
            </a>
        </div>
        @endcan

        @can('ver productos')
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('productos.index') }}" class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <div class="stat-label">Productos</div>
                <div class="stat-sublabel">Catálogo de inventario</div>
            </a>
        </div>
        @endcan

        @can('ver notas')
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('tipoNota.index') }}" class="stat-card">
                <div class="stat-icon" style="background: rgba(5,150,105,0.1); color: #059669;">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-label">Notas de Pedido</div>
                <div class="stat-sublabel">Solicitudes activas</div>
            </a>
        </div>
        @endcan

        @can('ver transacciones')
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('transaccionProducto.index') }}" class="stat-card">
                <div class="stat-icon" style="background: rgba(220,38,38,0.08); color: #dc2626;">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="stat-label">Transacción Producto</div>
                <div class="stat-sublabel">Movimientos de inventario</div>
            </a>
        </div>
        @endcan

        @can('ver bodegas')
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('bodegas.index') }}" class="stat-card">
                <div class="stat-icon" style="background: rgba(120,85,72,0.1); color: #795548;">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="stat-label">Bodegas</div>
                <div class="stat-sublabel">Puntos de almacenamiento</div>
            </a>
        </div>
        @endcan

        @can('ver ventas')
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('venta.index') }}" class="stat-card">
                <div class="stat-icon" style="background: rgba(99,102,241,0.1); color: #6366f1;">
                    <i class="fas fa-cash-register"></i>
                </div>
                <div class="stat-label">Ventas</div>
                <div class="stat-sublabel">Historial de ventas</div>
            </a>
        </div>
        @endcan

        @can('ver usuarios')
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('users.index') }}" class="stat-card">
                <div class="stat-icon" style="background: rgba(14,165,233,0.1); color: #0ea5e9;">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div class="stat-label">Usuarios</div>
                <div class="stat-sublabel">Gestión de cuentas</div>
            </a>
        </div>
        @endcan

        @can('ver roles')
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('roles.index') }}" class="stat-card">
                <div class="stat-icon" style="background: rgba(168,85,247,0.1); color: #a855f7;">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-label">Roles y Permisos</div>
                <div class="stat-sublabel">Control de acceso</div>
            </a>
        </div>
        @endcan

        @can('ver auditorias')
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('auditorias.index') }}" class="stat-card">
                <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                    <i class="fas fa-history"></i>
                </div>
                <div class="stat-label">Auditorías</div>
                <div class="stat-sublabel">Registro de actividades</div>
            </a>
        </div>
        @endcan

        @can('ver kpis')
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('kpis.index') }}" class="stat-card">
                <div class="stat-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-label">KPIs y Analítica</div>
                <div class="stat-sublabel">Indicadores de rendimiento</div>
            </a>
        </div>
        @endcan
    </div>
</div>
@endsection