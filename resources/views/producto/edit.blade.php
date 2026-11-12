@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Editar Producto</h3>
            <p class="page-subtitle mb-0 text-muted">Modificación de características y parámetros del producto</p>
        </div>
        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver a Productos
        </a>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><strong>Error:</strong> {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4" style="max-width: 900px; margin: 0 auto; border-top: 3px solid var(--primary) !important;">
        <div class="card-body p-4 p-md-5">
            <!-- Header con info resumida -->
            <div class="d-flex align-items-center mb-4 p-3 rounded-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0;">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-xs" style="width: 52px; height: 52px; background: rgba(0, 151, 167, 0.15); color: var(--primary);">
                    <i class="fas fa-cube fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">{{ $producto->nombre }}</h5>
                    <div class="font-mono mt-1 text-muted" style="font-size: 13px;">Código Único: {{ $producto->codigo }}</div>
                </div>
            </div>

            <form action="{{ route('productos.update', $producto->codigo) }}" method="POST" enctype="multipart/form-data">
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

                    <div class="col-md-6">
                        <label for="imagen" class="form-label">Foto del Producto (Opcional)</label>
                        <input type="file" name="imagen" id="imagen" class="form-control @error('imagen') is-invalid @enderror" accept="image/*">
                        <div class="form-text">Si no deseas cambiar la foto, deja este campo en blanco.</div>
                        @if($producto->imagen)
                            <div class="mt-2">
                                <span class="d-block text-muted" style="font-size: 0.85rem;">Foto actual:</span>
                                <img src="{{ $producto->imagen }}" alt="Foto actual" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-light); margin-top: 5px;">
                            </div>
                        @endif
                        @error('imagen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción Detallada</label>
                        <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" required rows="3" placeholder="Características del producto...">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-light);">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection