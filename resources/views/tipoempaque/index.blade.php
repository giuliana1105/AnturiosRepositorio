@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Tipos de Empaque</h3>
            <p class="page-subtitle">Configuración de empaques disponibles</p>
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

    <!-- Create Form -->
    <div class="card" style="border-left: 3px solid var(--accent);">
        <div class="card-body">
            <h6 class="fw-semibold mb-3" style="color: var(--foreground);">
                <i class="fas fa-plus me-2" style="color: var(--accent);"></i>Nuevo Tipo de Empaque
            </h6>
            <form action="{{ route('tipoempaque.store') }}" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label for="nombretipoempaque" class="form-label">Nombre del Tipo de Empaque</label>
                        <input type="text" name="nombretipoempaque" id="nombretipoempaque" class="form-control" placeholder="Ej: Caja grande" required>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label for="codigotipoempaque" class="form-label">Código</label>
                        <input type="text" name="codigotipoempaque" id="codigotipoempaque" class="form-control" placeholder="Ej: CJ-GR" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-save"></i> Guardar
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
                            <th>Código</th>
                            <th>Nombre</th>
                            <th style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tipoEmpaques as $tipoEmpaque)
                        <tr>
                            <td><span class="font-mono">{{ $tipoEmpaque->codigotipoempaque }}</span></td>
                            <td class="fw-medium">{{ $tipoEmpaque->nombretipoempaque }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('tipoempaque.edit', $tipoEmpaque->codigotipoempaque) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('tipoempaque.destroy', $tipoEmpaque->codigotipoempaque) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este tipo de empaque?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4" style="color: var(--muted);">
                                <i class="fas fa-box d-block mb-2" style="font-size: 24px; opacity: 0.4;"></i>
                                No hay tipos de empaque registrados
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $tipoEmpaques->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
