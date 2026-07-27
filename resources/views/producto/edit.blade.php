@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Editar Producto</h3>
            <p class="page-subtitle">Modificación de características del producto</p>
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
        <div class="card-body p-4">
            <!-- Header con info resumida -->
            <div class="d-flex align-items-center mb-4 p-3 rounded" style="background: var(--accent-subtle);">
                <div style="width: 48px; height: 48px; border-radius: var(--radius-sm); background: var(--primary); display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                    <i class="fas fa-cube" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color: var(--foreground);">{{ $producto->nombre }}</h5>
                    <div class="font-mono mt-1" style="font-size: 13px; color: var(--muted);">Código: {{ $producto->codigo }}</div>
                </div>
            </div>

            <form action="{{ route('productos.update', $producto->codigo) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="codigo" class="form-label">Código del Producto</label>
                        <input type="text" name="codigo" id="codigo" class="form-control font-mono @error('codigo') is-invalid @enderror" required value="{{ old('codigo', $producto->codigo) }}" placeholder="Ej: PRD-001">
                        @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre del Producto</label>
                        <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" required value="{{ old('nombre', $producto->nombre) }}" placeholder="Nombre comercial">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="cantidad" class="form-label">Cantidad Actual</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control font-mono @error('cantidad') is-invalid @enderror" required value="{{ old('cantidad', $producto->cantidad) }}" placeholder="0" min="0">
                        @error('cantidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción Detallada</label>
                        <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" required rows="3" placeholder="Características del producto...">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-light);">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection