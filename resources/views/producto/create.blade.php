@extends('layouts.app')

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
                <a class="nav-link text-dark mb-2" href="{{ route('tipoNota.index') }}">
                    <i class="fas fa-file-alt me-2"></i> Notas de Pedido
                </a>
                <a class="nav-link active text-info fw-bold mb-2" href="{{ route('productos.index') }}">
                    <i class="fas fa-cube me-2"></i> Productos
                </a>
                <a class="nav-link text-dark mb-2" href="{{ route('transaccionProducto.index') }}">
                    <i class="fas fa-exchange-alt me-2"></i> Transacción Producto
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
            <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width: 1000px;">
                <div class="card-header bg-info text-white rounded-top-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i> Crear Producto
                        </h3>
                        <a href="{{ route('productos.index') }}" class="btn btn-light btn-sm rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Alertas -->
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Error:</strong> {!! session('error') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Éxito:</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Nav tabs con diseño moderno -->
                    <ul class="nav nav-pills nav-fill mb-4" id="productoTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill px-4 py-3" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab">
                                <i class="fas fa-edit me-2"></i> Ingreso Manual
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4 py-3" id="excel-tab" data-bs-toggle="tab" data-bs-target="#excel" type="button" role="tab">
                                <i class="fas fa-file-excel me-2"></i> Importar desde Excel
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="productoTabContent">
                        <!-- Ingreso Manual -->
                        <div class="tab-pane fade show active" id="manual" role="tabpanel">
                            <form id="manualForm" action="{{ route('productos.store') }}" method="POST" class="row g-3">
                                @csrf
                                <div class="col-md-6">
                                    <label for="codigo" class="form-label fw-bold">
                                        <i class="fas fa-barcode me-2 text-info"></i> Código
                                    </label>
                                    <input type="text" name="codigo" id="codigo" 
                                           class="form-control rounded-pill @error('codigo') is-invalid @enderror" 
                                           required value="{{ old('codigo') }}" 
                                           placeholder="Ingrese el código del producto">
                                    @error('codigo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label fw-bold">
                                        <i class="fas fa-cube me-2 text-info"></i> Nombre
                                    </label>
                                    <input type="text" name="nombre" id="nombre" 
                                           class="form-control rounded-pill @error('nombre') is-invalid @enderror" 
                                           required value="{{ old('nombre') }}" 
                                           placeholder="Ingrese el nombre del producto">
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="descripcion" class="form-label fw-bold">
                                        <i class="fas fa-align-left me-2 text-info"></i> Descripción
                                    </label>
                                    <textarea name="descripcion" id="descripcion" 
                                              class="form-control @error('descripcion') is-invalid @enderror" 
                                              required rows="4" 
                                              placeholder="Ingrese la descripción del producto">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="cantidad" class="form-label fw-bold">
                                        <i class="fas fa-sort-numeric-up me-2 text-info"></i> Cantidad
                                    </label>
                                    <input type="number" name="cantidad" id="cantidad" 
                                           class="form-control rounded-pill @error('cantidad') is-invalid @enderror" 
                                           required value="{{ old('cantidad') }}" 
                                           placeholder="Ingrese la cantidad" min="0">
                                    @error('cantidad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-info text-white fw-bold rounded-pill px-5 py-3">
                                        <i class="fas fa-save me-2"></i> Guardar Producto
                                    </button>
                                    <a href="{{ route('productos.index') }}" class="btn btn-secondary rounded-pill px-5 py-3 ms-3">
                                        <i class="fas fa-times me-2"></i> Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Importar Excel -->
                        <div class="tab-pane fade" id="excel" role="tabpanel">
                            <div class="text-center mb-4">
                                <i class="fas fa-file-excel fa-3x text-success mb-3"></i>
                                <h4 class="text-muted">Importar productos desde Excel</h4>
                                <p class="text-muted">Sube un archivo Excel (.xlsx o .xls) con los datos de los productos</p>
                            </div>
                            
                            <form id="excelForm" action="{{ route('productos.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="mb-4">
                                            <label for="excel_file" class="form-label fw-bold">
                                                <i class="fas fa-upload me-2 text-info"></i> Seleccionar archivo Excel
                                            </label>
                                            <input type="file" name="excel_file" id="excel_file" 
                                                   class="form-control rounded-pill p-3" accept=".xlsx,.xls" required>
                                            <div class="form-text mt-2">
                                                <i class="fas fa-info-circle me-1"></i> 
                                                Formatos permitidos: .xlsx, .xls
                                            </div>
                                        </div>
                                        
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success fw-bold rounded-pill px-5 py-3">
                                                <i class="fas fa-file-import me-2"></i> Importar desde Excel
                                            </button>
                                            <a href="{{ route('productos.index') }}" class="btn btn-secondary rounded-pill px-5 py-3 ms-3">
                                                <i class="fas fa-times me-2"></i> Cancelar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
.col-md-2, .col-md-10 {
    padding-left: 0;
    padding-right: 0;
}
.col-md-2.bg-light {
    margin: 0;
    border-radius: 0;
}
.btn-info {
    background-color: #0097a7;
    border-color: #0097a7;
}
.btn-info:hover, .btn-info:focus {
    background-color: #00796b;
    border-color: #00796b;
}
.btn-success {
    background-color: #4caf50;
    border-color: #4caf50;
}
.btn-success:hover, .btn-success:focus {
    background-color: #388e3c;
    border-color: #388e3c;
}
.btn-secondary {
    background-color: #607d8b;
    border-color: #607d8b;
    color: #fff;
}
.btn-secondary:hover, .btn-secondary:focus {
    background-color: #455a64;
    border-color: #455a64;
    color: #fff;
}
.btn-light {
    background-color: #f8f9fa;
    border-color: #f8f9fa;
    color: #495057;
}
.btn-light:hover, .btn-light:focus {
    background-color: #e2e6ea;
    border-color: #dae0e5;
    color: #495057;
}
.rounded-pill {
    border-radius: 50rem !important;
}
.nav-pills .nav-link {
    background-color: #f8f9fa;
    color: #495057;
    border: 1px solid #dee2e6;
    margin: 0 0.25rem;
    transition: all 0.3s ease;
}
.nav-pills .nav-link:hover {
    background-color: #e9ecef;
    transform: translateY(-2px);
}
.nav-pills .nav-link.active {
    background-color: #0097a7;
    border-color: #0097a7;
    color: white;
    box-shadow: 0 4px 8px rgba(0, 151, 167, 0.3);
}
.form-control {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}
.form-control:focus {
    border-color: #0097a7;
    box-shadow: 0 0 0 0.2rem rgba(0, 151, 167, 0.25);
}
.form-label {
    color: #495057;
    margin-bottom: 0.5rem;
}
.alert {
    border: none;
    border-radius: 0.75rem;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
}
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}
textarea.form-control {
    border-radius: 1rem !important;
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
        font-size: 1.75rem;
    }
    .container-fluid {
        padding: 0 !important;
    }
    .nav-pills .nav-link {
        margin: 0.25rem 0;
        text-align: center;
    }
    .card-header .d-flex {
        flex-direction: column;
        text-align: center;
    }
    .card-header .btn {
        margin-top: 1rem;
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const manualForm = document.getElementById('manualForm');
        const excelForm = document.getElementById('excelForm');
        const manualTab = document.getElementById('manual-tab');
        const excelTab = document.getElementById('excel-tab');

        manualForm.addEventListener('submit', function(e) {
            if (!manualTab.classList.contains('active')) {
                e.preventDefault();
            }
        });

        excelForm.addEventListener('submit', function(e) {
            if (!excelTab.classList.contains('active')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection