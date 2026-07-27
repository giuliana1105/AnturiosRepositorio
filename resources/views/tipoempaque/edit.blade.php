@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Editar Tipo de Empaque</h3>
            <p class="page-subtitle">Modificación de categoría de embalaje</p>
        </div>
        <a href="{{ route('tipoempaque.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><strong>Errores de validación:</strong>
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
            <!-- Header con info resumida -->
            <div class="d-flex align-items-center mb-4 p-3 rounded" style="background: var(--accent-subtle);">
                <div style="width: 48px; height: 48px; border-radius: var(--radius-sm); background: var(--primary); display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                    <i class="fas fa-box" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color: var(--foreground);">{{ $tipoEmpaque->nombretipoempaque }}</h5>
                    <div class="font-mono mt-1" style="font-size: 13px; color: var(--muted);">Código: {{ $tipoEmpaque->codigotipoempaque }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('tipoempaque.update', $tipoEmpaque->codigotipoempaque) }}">
                @csrf
                @method('PATCH')
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="codigotipoempaque" class="form-label">Código del Empaque</label>
                        <input type="text" name="codigotipoempaque" id="codigotipoempaque" class="form-control font-mono bg-light" value="{{ $tipoEmpaque->codigotipoempaque }}" readonly>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="nombretipoempaque" class="form-label">Nombre del Empaque</label>
                        <input type="text" name="nombretipoempaque" id="nombretipoempaque" class="form-control" value="{{ old('nombretipoempaque', $tipoEmpaque->nombretipoempaque) }}" required>
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-light);">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('tipoempaque.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
