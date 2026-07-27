@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Crear Tipo de Empaque</h3>
            <p class="page-subtitle">Registro de nueva categoría de embalaje</p>
        </div>
        <a href="{{ route('tipoempaque.index') }}" class="btn btn-secondary">
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
                <i class="fas fa-box me-2" style="color: var(--primary);"></i>Datos del Empaque
            </h6>

            <form method="POST" action="{{ route('tipoempaque.store') }}">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="codigotipoempaque" class="form-label">Código del Empaque</label>
                        <input type="text" name="codigotipoempaque" id="codigotipoempaque" class="form-control font-mono" placeholder="Ej: EMP-01" required value="{{ old('codigotipoempaque') }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="nombretipoempaque" class="form-label">Nombre del Empaque</label>
                        <input type="text" name="nombretipoempaque" id="nombretipoempaque" class="form-control" placeholder="Ej: Caja de cartón pequeña" required value="{{ old('nombretipoempaque') }}">
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-light);">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('tipoempaque.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Tipo de Empaque
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection