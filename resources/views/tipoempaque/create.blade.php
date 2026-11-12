@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de Página -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--foreground); letter-spacing: -0.5px;">
                <i class="fas fa-boxes me-2" style="color: var(--primary);"></i>Crear Tipo de Empaque
            </h3>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Registro de nueva categoría de embalaje para los productos del catálogo.
            </p>
        </div>
        <a href="{{ route('tipoempaque.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert" style="max-width: 800px; margin: 0 auto 1.5rem auto;">
            <div class="d-flex align-items-center mb-1">
                <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                <strong class="fs-6">Revise los campos obligatorios:</strong>
            </div>
            <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                     style="width: 48px; height: 48px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                    <i class="fas fa-box fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0">Datos del Empaque</h5>
                    <small class="text-muted">Define el código de referencia y el nombre descriptivo</small>
                </div>
            </div>

            <form method="POST" action="{{ route('tipoempaque.store') }}">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="codigotipoempaque" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Código del Empaque <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3">
                                <i class="fas fa-barcode text-muted"></i>
                            </span>
                            <input type="text" name="codigotipoempaque" id="codigotipoempaque" 
                                   class="form-control border-0 bg-light rounded-end-pill py-2 font-monospace" 
                                   placeholder="Ej: EMP-01" required value="{{ old('codigotipoempaque') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="nombretipoempaque" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Nombre del Empaque <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3">
                                <i class="fas fa-box-open text-muted"></i>
                            </span>
                            <input type="text" name="nombretipoempaque" id="nombretipoempaque" 
                                   class="form-control border-0 bg-light rounded-end-pill py-2" 
                                   placeholder="Ej: Caja de cartón pequeña" required value="{{ old('nombretipoempaque') }}">
                        </div>
                    </div>
                </div>

                <hr class="my-4" style="border-color: #f1f5f9;">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('tipoempaque.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                        <i class="fas fa-save me-1"></i> Guardar Tipo de Empaque
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
