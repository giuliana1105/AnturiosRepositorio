@extends('layouts.app')

@section('title')
    Home
@endsection

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Seleccione una Bodega</h3>
            <p class="page-subtitle">Escoja la bodega para ver su inventario y movimientos</p>
        </div>
    </div>

    <div class="row g-3">
        @foreach($bodegas as $bodega)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('home.bodega', $bodega->idbodega) }}" class="stat-card">
                    <div class="stat-icon">
                        @php
                            $icon = 'fa-store';
                            if (Str::contains(Str::lower($bodega->nombrebodega), 'camión')) {
                                $icon = 'fa-truck-moving';
                            } elseif (Str::contains(Str::lower($bodega->nombrebodega), 'central')) {
                                $icon = 'fa-warehouse';
                            } elseif (Str::contains(Str::lower($bodega->nombrebodega), 'tienda')) {
                                $icon = 'fa-shop';
                            }
                        @endphp
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <div class="stat-label">{{ $bodega->nombrebodega }}</div>
                    <div class="stat-sublabel">Ver inventario</div>
                </a>
            </div>
        @endforeach
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="{{ route('home.master') }}" class="stat-card">
                <div class="stat-icon" style="background: rgba(120,85,72,0.1); color: #795548;">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="stat-label">Bodega Master</div>
                <div class="stat-sublabel">Inventario consolidado</div>
            </a>
        </div>
    </div>
</div>
@endsection
