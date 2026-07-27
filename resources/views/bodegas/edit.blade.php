@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Editar Bodega</h3>
            <p class="page-subtitle">Modificación de espacio de almacenamiento</p>
        </div>
        <a href="{{ route('bodegas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
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
                    <i class="fas fa-warehouse" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color: var(--foreground);">{{ $bodega->nombrebodega }}</h5>
                    <div class="font-mono mt-1" style="font-size: 13px; color: var(--muted);">Código: {{ $bodega->idbodega }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('bodegas.update', $bodega->idbodega) }}">
                @csrf
                @method('PATCH')
                
                <div class="row g-4">
                    <!-- Campo Nombre de la Bodega -->
                    <div class="col-12">
                        <label for="nombrebodega" class="form-label">Nombre de la Bodega</label>
                        <input type="text" name="nombrebodega" id="nombrebodega" class="form-control" placeholder="Ingrese el nombre de la bodega" value="{{ old('nombrebodega', $bodega->nombrebodega) }}" required>
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-light);">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('bodegas.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection