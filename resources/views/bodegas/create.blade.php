@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de la página -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Registrar Nueva Bodega</h3>
            <p class="page-subtitle mb-0 text-muted">Alta de nuevo punto físico de almacenamiento y distribución</p>
        </div>
        <a href="{{ route('bodegas.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver a Bodegas
        </a>
    </div>

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-center mb-1">
                <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                <strong class="fs-6">Se encontraron errores en el formulario:</strong>
            </div>
            <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4" style="max-width: 800px; margin: 0 auto; border-top: 3px solid var(--primary) !important;">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                    <i class="fas fa-warehouse fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Información del Almacén</h5>
                    <small class="text-muted">Introduce el nombre descriptivo de la nueva bodega o sucursal.</small>
                </div>
            </div>

            <form method="POST" action="{{ route('bodegas.store') }}">
                @csrf
                <div class="row g-4">
                    <div class="col-12">
                        <label for="nombrebodega" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Nombre de la Bodega <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nombrebodega" id="nombrebodega" class="form-control form-control-lg rounded-3" 
                               placeholder="Ej: Central, Sucursal Norte, Depósito 2..." required value="{{ old('nombrebodega') }}" maxlength="50">
                    </div>
                </div>

                <hr class="my-4" style="border-color: #e2e8f0;">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('bodegas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save me-1"></i> Guardar Bodega
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
