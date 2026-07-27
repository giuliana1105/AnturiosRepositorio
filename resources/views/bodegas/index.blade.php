@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Bodegas</h3>
            <p class="page-subtitle">Gestión de puntos de almacenamiento</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-triangle me-2"></i>Errores de validación</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Create Form -->
    <div class="card" style="border-left: 3px solid var(--accent);">
        <div class="card-body">
            <h6 class="fw-semibold mb-3" style="color: var(--foreground);">
                <i class="fas fa-plus me-2" style="color: var(--accent);"></i>Nueva Bodega
            </h6>
            <form method="POST" action="{{ route('bodegas.store') }}" role="form">
                @csrf
                <div class="row align-items-end">
                    <div class="col-12 col-md-8 mb-3 mb-md-0">
                        <label for="nombrebodega" class="form-label">Nombre de la Bodega</label>
                        <input type="text" name="nombrebodega" id="nombrebodega"
                            class="form-control" placeholder="Ingrese el nombre de la bodega"
                            value="{{ old('nombrebodega') }}" required>
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-save"></i> Guardar Bodega
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nombre de la Bodega</th>
                            <th style="width: 160px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bodegas as $bodega)
                            <tr>
                                <td class="fw-medium">
                                    <i class="fas fa-warehouse me-2" style="color: var(--muted); font-size: 12px;"></i>
                                    {{ $bodega->nombrebodega }}
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('bodegas.edit', $bodega->idbodega) }}" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('bodegas.destroy', $bodega->idbodega) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('¿Está seguro de eliminar esta bodega?')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-4" style="color: var(--muted);">
                                    <i class="fas fa-warehouse d-block mb-2" style="font-size: 24px; opacity: 0.4;"></i>
                                    No hay bodegas registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3 d-flex justify-content-center">
                {{ $bodegas->appends(['search' => request('search')])->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection