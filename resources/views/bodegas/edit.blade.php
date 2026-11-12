@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Editar Bodega</h3>
            <p class="page-subtitle mb-0 text-muted">Modificación de espacio de almacenamiento</p>
        </div>
        <a href="{{ route('bodegas.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver a Bodegas
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-center mb-1">
                <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                <strong class="fs-6">Errores de validación:</strong>
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
            <!-- Header con info resumida -->
            <div class="d-flex align-items-center mb-4 p-3 rounded-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0;">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-xs" style="width: 52px; height: 52px; background: rgba(0, 151, 167, 0.15); color: var(--primary);">
                    <i class="fas fa-warehouse fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">{{ $bodega->nombrebodega }}</h5>
                    <div class="font-mono mt-1 text-muted" style="font-size: 13px;">Código de Registro: #{{ $bodega->idbodega }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('bodegas.update', $bodega->idbodega) }}">
                @csrf
                @method('PATCH')
                
                <div class="row g-4">
                    <!-- Campo Nombre de la Bodega -->
                    <div class="col-12">
                        <label for="nombrebodega" class="form-label fw-bold text-dark" style="font-size: 13.5px;">Nombre de la Bodega <span class="text-danger">*</span></label>
                        <input type="text" name="nombrebodega" id="nombrebodega" class="form-control rounded-3" placeholder="Ingrese el nombre de la bodega" value="{{ old('nombrebodega', $bodega->nombrebodega) }}" required maxlength="50">
                    </div>
                </div>

                <hr class="my-4" style="border-color: #e2e8f0;">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('bodegas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection