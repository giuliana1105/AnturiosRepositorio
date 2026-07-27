@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Crear Bodega</h3>
            <p class="page-subtitle">Registro de nuevo espacio de almacenamiento</p>
        </div>
        <a href="{{ route('bodegas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><strong>Revise los campos obligatorios:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4" style="max-width: 800px; margin: 0 auto; border-top: 3px solid var(--primary);">
        <div class="card-body p-4">
            <h6 class="fw-semibold mb-4" style="color: var(--foreground);">
                <i class="fas fa-warehouse me-2" style="color: var(--primary);"></i>Datos de la Bodega
            </h6>

            <form method="POST" action="{{ route('bodegas.store') }}">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="idbodega" class="form-label">Código de la Bodega</label>
                        <input type="text" name="idbodega" id="idbodega" class="form-control font-mono" placeholder="Ej: BOD-01" required value="{{ old('idbodega') }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="nombrebodega" class="form-label">Nombre de la Bodega</label>
                        <input type="text" name="nombrebodega" id="nombrebodega" class="form-control" placeholder="Nombre descriptivo" required value="{{ old('nombrebodega') }}">
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-light);">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('bodegas.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Bodega
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
