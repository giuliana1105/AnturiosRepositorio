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
        @if($cargo !== 'Jefe de bodega')
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('empleados.index') }}" class="stat-card">
                <div class="stat-icon" style="background: rgba(255,152,0,0.1); color: #f59e0b;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-label">Empleados</div>
                <div class="stat-sublabel">Gestión de personal</div>
            </a>
        </div>
        @endif

        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('productos.index') }}" class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <div class="stat-label">Productos</div>
                <div class="stat-sublabel">Catálogo de inventario</div>
            </a>
        </div>
        
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('tipoNota.index') }}" class="stat-card">
                <div class="stat-icon" style="background: rgba(5,150,105,0.1); color: #059669;">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-label">Notas de Pedido</div>
                <div class="stat-sublabel">Solicitudes activas</div>
            </a>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('transaccionProducto.index') }}" class="stat-card">
                <div class="stat-icon" style="background: rgba(220,38,38,0.08); color: #dc2626;">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="stat-label">Transacción Producto</div>
                <div class="stat-sublabel">Movimientos de inventario</div>
            </a>
        </div>

        @if($cargo !== 'Jefe de bodega')
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('bodegas.index') }}" class="stat-card">
                <div class="stat-icon" style="background: rgba(120,85,72,0.1); color: #795548;">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="stat-label">Bodegas</div>
                <div class="stat-sublabel">Puntos de almacenamiento</div>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection