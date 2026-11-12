@extends('layouts.app')

@section('title', 'Selección de Bodega')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de Página -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--foreground); letter-spacing: -0.5px;">
                <i class="fas fa-warehouse me-2" style="color: var(--primary);"></i>Puntos de Almacenamiento
            </h3>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Seleccione la bodega o punto de venta para consultar su stock disponible, recepciones y despachos.
            </p>
        </div>
        @can('ver dashboard general')
        <a href="{{ route('home.master') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-crown me-2"></i>Panel General Maestro
        </a>
        @endcan
    </div>

    @can('ver dashboard general')
    <div class="row g-3">
        @foreach($bodegas as $bodega)
            @php
                $icon = 'fa-warehouse';
                $color = '#0ea5e9';
                $bg = 'rgba(14, 165, 233, 0.12)';
                $nombreLower = Str::lower($bodega->nombrebodega);

                if (Str::contains($nombreLower, 'camión') || Str::contains($nombreLower, 'camion')) {
                    $icon = 'fa-truck-moving';
                    $color = '#f59e0b';
                    $bg = 'rgba(245, 158, 11, 0.12)';
                } elseif (Str::contains($nombreLower, 'central') || Str::contains($nombreLower, 'matriz')) {
                    $icon = 'fa-industry';
                    $color = '#0097a7';
                    $bg = 'rgba(0, 151, 167, 0.12)';
                } elseif (Str::contains($nombreLower, 'tienda') || Str::contains($nombreLower, 'local')) {
                    $icon = 'fa-store';
                    $color = '#8b5cf6';
                    $bg = 'rgba(139, 92, 246, 0.12)';
                }
            @endphp
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <a href="{{ route('home.bodega', $bodega->idbodega) }}" class="card h-100 border-0 shadow-sm rounded-4 text-decoration-none text-dark transition-all hover-lift overflow-hidden">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                                 style="width: 52px; height: 52px; background: {{ $bg }}; color: {{ $color }};">
                                <i class="fas {{ $icon }} fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $bodega->nombrebodega }}</h6>
                                <small class="text-muted" style="font-size: 12px;">ID: #{{ $bodega->idbodega }}</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <span class="text-primary fw-semibold" style="font-size: 13px;">
                                Consultar Inventario <i class="fas fa-arrow-right ms-1"></i>
                            </span>
                            <span class="badge rounded-pill bg-light text-muted border px-2 py-1" style="font-size: 11px;">
                                Activa
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach

        {{-- Tarjeta Bodega Master --}}
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('home.master') }}" class="card h-100 border-0 shadow-sm rounded-4 text-decoration-none text-dark transition-all hover-lift overflow-hidden" style="border: 2px dashed rgba(0, 151, 167, 0.3) !important;">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 52px; height: 52px; background: rgba(0, 151, 167, 0.15); color: var(--primary);">
                            <i class="fas fa-network-wired fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Bodega Master</h6>
                            <small class="text-muted" style="font-size: 12px;">Inventario consolidado</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <span class="text-primary fw-semibold" style="font-size: 13px;">
                            Ver Consolidado <i class="fas fa-arrow-right ms-1"></i>
                        </span>
                        <span class="badge rounded-pill text-white px-2 py-1" style="background: var(--primary); font-size: 11px;">
                            Global
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @else
    <div class="card border-0 shadow-sm rounded-4 text-center py-5 my-4">
        <div class="card-body">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light text-warning mb-3" style="width: 72px; height: 72px; font-size: 32px;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">Sin Bodega Asignada</h5>
            <p class="text-muted mx-auto mb-3" style="max-width: 480px; font-size: 14px;">
                Tu cuenta de usuario no cuenta con una bodega asignada en este momento. Por favor contacta al administrador del sistema para configurar tu punto de trabajo.
            </p>
        </div>
    </div>
    @endcan
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
