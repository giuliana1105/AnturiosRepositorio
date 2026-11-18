@extends('layouts.app')

@section('title', 'Puntos de Almacenamiento')

@section('content')
<div class="container-fluid py-3 px-3 px-md-4">
    <!-- Breadcrumb & Wayfinding -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                    <i class="fas fa-warehouse me-1 text-primary"></i> Puntos de Almacenamiento
                </li>
            </ol>
        </nav>
        <div class="text-muted font-mono" style="font-size: 12.5px;">
            {{ count($bodegas) + 1 }} centros de control
        </div>
    </div>

    <!-- Encabezado de Página -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 pb-3 mb-4 border-bottom" style="border-color: #e2e8f0 !important;">
        <div>
            <h2 class="fw-bold mb-1 text-dark tracking-tight" style="letter-spacing: -0.025em;">
                Puntos de Almacenamiento
            </h2>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Seleccione la bodega o centro de acopio para consultar existencias físicas en tiempo real, recepciones y devoluciones.
            </p>
        </div>
        @can('ver dashboard general')
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('home.master') }}" class="btn btn-sm btn-primary rounded-3 px-3 py-2 fw-semibold shadow-sm">
                <i class="fas fa-layer-group me-1.5"></i> Panel General Maestro
            </a>
        </div>
        @endcan
    </div>

    @can('ver dashboard general')
    <div class="row g-3">
        {{-- Tarjeta 1: Bodega Master (Siempre PRIMERA como solicitó el usuario) --}}
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('home.master') }}" class="card h-100 border rounded-3 text-decoration-none text-dark hover-card overflow-hidden bg-white" style="border-color: var(--primary) !important; border-width: 1.5px !important;">
                <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" 
                                 style="width: 44px; height: 44px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                                <i class="fas fa-network-wired fs-5"></i>
                            </div>
                            <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle fw-semibold px-2.5 py-1" style="font-size: 11px;">
                                Principal
                            </span>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark tracking-tight" style="font-size: 15px;">Bodega Master</h6>
                            <small class="text-muted" style="font-size: 12px;">Inventario consolidado general</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-3" style="border-color: #f1f5f9 !important;">
                        <span class="text-primary fw-semibold" style="font-size: 12.5px;">
                            Ver Consolidado <i class="fas fa-arrow-right ms-1"></i>
                        </span>
                        <span class="badge rounded-pill px-2 py-0.5 font-mono" style="background: rgba(0, 151, 167, 0.08); color: var(--primary); font-size: 11px;">
                            Global
                        </span>
                    </div>
                </div>
            </a>
        </div>

        {{-- Demás Bodegas del Sistema --}}
        @foreach($bodegas as $bodega)
            @php
                $icon = 'fa-warehouse';
                $color = '#0284c7';
                $bg = 'rgba(14, 165, 233, 0.1)';
                $nombreLower = Str::lower($bodega->nombrebodega);

                if (Str::contains($nombreLower, 'camión') || Str::contains($nombreLower, 'camion')) {
                    $icon = 'fa-truck-moving';
                    $color = '#d97706';
                    $bg = 'rgba(245, 158, 11, 0.1)';
                } elseif (Str::contains($nombreLower, 'central') || Str::contains($nombreLower, 'matriz')) {
                    $icon = 'fa-industry';
                    $color = '#0097a7';
                    $bg = 'rgba(0, 151, 167, 0.1)';
                } elseif (Str::contains($nombreLower, 'tienda') || Str::contains($nombreLower, 'local')) {
                    $icon = 'fa-store';
                    $color = '#7c3aed';
                    $bg = 'rgba(124, 58, 237, 0.1)';
                }
            @endphp
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <a href="{{ route('home.bodega', $bodega->idbodega) }}" class="card h-100 border rounded-3 text-decoration-none text-dark hover-card overflow-hidden bg-white" style="border-color: #e2e8f0 !important;">
                    <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" 
                                     style="width: 44px; height: 44px; background: {{ $bg }}; color: {{ $color }};">
                                    <i class="fas {{ $icon }} fs-5"></i>
                                </div>
                                <span class="badge rounded-pill bg-light text-secondary border font-mono px-2 py-0.5" style="font-size: 11px; border-color: #e2e8f0 !important;">
                                    ID: #{{ $bodega->idbodega }}
                                </span>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark tracking-tight" style="font-size: 14.5px;">{{ $bodega->nombrebodega }}</h6>
                                <small class="text-muted" style="font-size: 12px;">Punto de almacenamiento</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-3" style="border-color: #f1f5f9 !important;">
                            <span class="text-primary fw-medium" style="font-size: 12.5px;">
                                Consultar Inventario <i class="fas fa-arrow-right ms-1"></i>
                            </span>
                            <span class="badge rounded-pill px-2 py-0.5" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-size: 11px;">
                                Activa
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    @else
    <div class="card border rounded-3 text-center py-5 my-4 bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: #fef3c7; color: #d97706; font-size: 24px;">
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
.hover-card {
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06) !important;
}
</style>
@endsection
