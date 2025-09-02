@extends('layouts.app')

@section('title')
    Home
@endsection

@section('content')
<div class="container-fluid p-0 m-0">
    <div class="row g-0 min-vh-100">
        <!-- Sidebar Navigation -->
        <div class="col-md-2 bg-light py-3 px-3">
            <div class="text-center mb-4">
                <div class="bg-info rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-user text-white fa-lg"></i>
                </div>
                <div class="mt-2">
                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                    <div class="text-muted small">{{ auth()->user()->email }}</div>
                    <div class="text-secondary small">{{ auth()->user()->cargoNombre() }}</div>
                </div>
            </div>

            <div class="mb-3">
                <small class="text-uppercase text-muted fw-bold">NAVEGACIÓN PRINCIPAL</small>
            </div>

            <nav class="nav flex-column">
                <a class="nav-link active text-info fw-bold mb-2" href="#">
                    <i class="fas fa-th-large me-2"></i> Dashboard
                </a>
                <a class="nav-link text-dark mb-2" href="{{ route('home') }}">
                    <i class="fas fa-home me-2"></i> Home
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link text-dark btn btn-link mb-2" style="text-align:left;">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar sesión
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 py-3 px-4 bg-white">
            <h3 class="mb-4 text-dark">Seleccione una Bodega</h3>
            <div class="row g-3">
                @foreach($bodegas as $bodega)
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('home.bodega', $bodega->idbodega) }}" class="text-decoration-none">
                            <div class="card text-white h-100" style="background: linear-gradient(135deg, #0097a7 0%, #00796b 100%); border: none; border-radius: 12px;">
                                <div class="card-body text-center py-4">
                                    @php
                                        // Selecciona el ícono según el nombre de la bodega
                                        $icon = 'fa-store';
                                        if (Str::contains(Str::lower($bodega->nombrebodega), 'camión')) {
                                            $icon = 'fa-truck-moving';
                                        } elseif (Str::contains(Str::lower($bodega->nombrebodega), 'central')) {
                                            $icon = 'fa-warehouse';
                                        } elseif (Str::contains(Str::lower($bodega->nombrebodega), 'tienda')) {
                                            $icon = 'fa-shop';
                                        }
                                    @endphp
                                    <i class="fas {{ $icon }} fa-2x mb-3"></i>
                                    <div class="small">{{ $bodega->nombrebodega }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('home.master') }}" class="text-decoration-none">
                        <div class="card text-white h-100" style="background: linear-gradient(135deg, #795548 0%, #5d4037 100%); border: none; border-radius: 12px;">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-warehouse fa-2x mb-3"></i>
                                <div class="small">Bodega Master</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
body {
    margin: 0;
    padding: 0;
}
.container-fluid {
    padding: 0 !important;
    margin: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}
.card {
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 0;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}
.nav-link {
    padding: 0.5rem 0;
    border-radius: 4px;
    transition: all 0.2s ease;
}
.nav-link:hover {
    background-color: rgba(0, 123, 255, 0.1);
    padding-left: 0.5rem;
}
.nav-link.active {
    background-color: rgba(23, 162, 184, 0.1);
    border-left: 3px solid #17a2b8;
    padding-left: 0.5rem;
}
.min-vh-100 {
    min-height: 100vh;
}
.card-body {
    position: relative;
    overflow: hidden;
}
.card-body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    pointer-events: none;
}
.h3 {
    font-size: 2.5rem;
}
.row.g-0 {
    margin: 0;
}
.row.g-3 {
    margin: 0;
}
.col-md-2, .col-md-10 {
    padding-left: 0;
    padding-right: 0;
}
.col-md-2.bg-light {
    margin: 0;
    border-radius: 0;
}
@media (max-width: 768px) {
    .col-md-2 {
        display: none;
    }
    .col-md-10 {
        flex: 0 0 100%;
        max-width: 100%;
        padding: 15px !important;
    }
    .h3 {
        font-size: 2rem;
    }
    .container-fluid {
        padding: 0 !important;
    }
}
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}
#app {
    min-height: 100vh;
}
</style>
@endsection
