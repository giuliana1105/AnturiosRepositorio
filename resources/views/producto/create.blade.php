@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Crear Producto</h3>
            <p class="page-subtitle">Registro de nuevos productos en el catálogo</p>
        </div>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><strong>Error:</strong> {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4" style="max-width: 900px; margin: 0 auto; border-top: 3px solid var(--primary);">
        <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 px-4">
            <ul class="nav nav-tabs border-bottom-0 gap-2" id="productoTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-medium" style="border-radius: var(--radius-sm) var(--radius-sm) 0 0;" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab">
                        <i class="fas fa-edit me-1"></i> Ingreso Manual
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-medium text-muted" style="border-radius: var(--radius-sm) var(--radius-sm) 0 0;" id="excel-tab" data-bs-toggle="tab" data-bs-target="#excel" type="button" role="tab">
                        <i class="fas fa-file-excel me-1 text-success"></i> Importar Excel
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-4 border-top">
            <div class="tab-content" id="productoTabContent">
                
                <!-- Ingreso Manual -->
                <div class="tab-pane fade show active" id="manual" role="tabpanel">
                    <form id="manualForm" action="{{ route('productos.store') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="codigo" class="form-label">Código del Producto</label>
                                <input type="text" name="codigo" id="codigo" class="form-control font-mono @error('codigo') is-invalid @enderror" required value="{{ old('codigo') }}" placeholder="Ej: PRD-001">
                                @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre del Producto</label>
                                <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" required value="{{ old('nombre') }}" placeholder="Nombre comercial">
                                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="cantidad" class="form-label">Cantidad Inicial</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control font-mono @error('cantidad') is-invalid @enderror" required value="{{ old('cantidad') }}" placeholder="0" min="0">
                                @error('cantidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="descripcion" class="form-label">Descripción Detallada</label>
                                <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" required rows="3" placeholder="Características del producto...">{{ old('descripcion') }}</textarea>
                                @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="my-4" style="border-color: var(--border-light);">
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Producto
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Importar Excel -->
                <div class="tab-pane fade" id="excel" role="tabpanel">
                    <div class="text-center py-5">
                        <div class="mb-4 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border-radius: 50%; background: rgba(25, 135, 84, 0.1);">
                            <i class="fas fa-file-excel text-success" style="font-size: 36px;"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="color: var(--foreground);">Importación Masiva</h5>
                        <p style="color: var(--muted); max-width: 400px; margin: 0 auto 30px;">Sube un archivo Excel (.xlsx o .xls) con el formato establecido para registrar múltiples productos a la vez.</p>
                        
                        <form id="excelForm" action="{{ route('productos.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="d-flex flex-column align-items-center">
                                <div style="max-width: 400px; width: 100%; margin-bottom: 24px;">
                                    <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx,.xls" required>
                                </div>
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="fas fa-cloud-upload-alt me-2"></i> Procesar Archivo Excel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
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

        // Tab style handling
        document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (event) {
                document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(t => {
                    t.classList.remove('text-primary');
                    t.classList.add('text-muted');
                });
                event.target.classList.remove('text-muted');
                event.target.classList.add('text-primary');
            });
        });
    });
</script>
@endsection