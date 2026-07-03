@extends('layouts.app')


@php
    $cargo = auth()->user()->cargoNombre();
@endphp

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-12 py-3 px-4">
            <h3 class="mb-4 text-dark">Dashboard</h3>
            
            <!-- Dashboard Cards Grid -->
            <div class="row g-3">
                @if($cargo !== 'Jefe de bodega')
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('empleados.index') }}" class="text-decoration-none">
                        <div class="card text-white h-100" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); border: none; border-radius: 12px;">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-users fa-2x mb-3"></i>
                                <div class="small">Empleados</div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('productos.index') }}" class="text-decoration-none">
                        <div class="card text-white h-100" style="background: linear-gradient(135deg, #7b1fa2 0%, #6a1b9a 100%); border: none; border-radius: 12px;">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-cubes fa-2x mb-3"></i>
                                <div class="small">Productos</div>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('tipoNota.index') }}" class="text-decoration-none">
                        <div class="card text-white h-100" style="background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%); border: none; border-radius: 12px;">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-file-alt fa-2x mb-3"></i>
                                <div class="small">Notas de Pedido</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('transaccionProducto.index') }}" class="text-decoration-none">
                        <div class="card text-white h-100" style="background: linear-gradient(135deg, #ff5722 0%, #d84315 100%); border: none; border-radius: 12px;">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-exchange-alt fa-2x mb-3"></i>
                                <div class="small">Transacción Producto</div>
                            </div>
                        </div>
                    </a>
                </div>

                @if($cargo !== 'Jefe de bodega')
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('bodegas.index') }}" class="text-decoration-none">
                        <div class="card text-white h-100" style="background: linear-gradient(135deg, #795548 0%, #5d4037 100%); border: none; border-radius: 12px;">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-warehouse fa-2x mb-3"></i>
                                <div class="small">Bodegas</div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Reset de márgenes y padding globales */
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

/* Eliminación de gutters y espacios */
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

/* Asegurar que el sidebar llegue hasta el borde */
.col-md-2.bg-light {
    margin: 0;
    border-radius: 0;
}

/* Responsive */
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

/* Asegurar altura completa */
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