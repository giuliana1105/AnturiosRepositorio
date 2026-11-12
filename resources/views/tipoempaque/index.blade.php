@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de Página -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--foreground); letter-spacing: -0.5px;">
                <i class="fas fa-boxes me-2" style="color: var(--primary);"></i>Tipos de Empaque
            </h3>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Configuración y catálogo de embalajes para clasificación de flores y productos.
            </p>
        </div>
        <a href="{{ route('tipoempaque.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-plus me-1"></i> Nuevo Empaque
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-check-circle me-2 fs-5"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2 fs-5"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Formulario Rápido de Creación -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 text-dark">
                <i class="fas fa-plus-circle me-2" style="color: var(--primary);"></i>Registro Rápido de Tipo de Empaque
            </h6>
            <form action="{{ route('tipoempaque.store') }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label for="codigotipoempaque" class="form-label fw-bold text-dark" style="font-size: 13px;">Código</label>
                        <input type="text" name="codigotipoempaque" id="codigotipoempaque" 
                               class="form-control border-0 bg-light rounded-pill py-2 font-monospace" 
                               placeholder="Ej: CJ-GR" required>
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="nombretipoempaque" class="form-label fw-bold text-dark" style="font-size: 13px;">Nombre del Empaque</label>
                        <input type="text" name="nombretipoempaque" id="nombretipoempaque" 
                               class="form-control border-0 bg-light rounded-pill py-2" 
                               placeholder="Ej: Caja grande / Bonche" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" class="btn btn-primary rounded-pill w-100 py-2 shadow-sm">
                            <i class="fas fa-save me-1"></i> Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Tipos de Empaque -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #edf2f7;">
                        <tr>
                            <th class="ps-4 py-3" style="font-size: 12.5px; text-transform: uppercase; color: #64748b;">Código</th>
                            <th class="py-3" style="font-size: 12.5px; text-transform: uppercase; color: #64748b;">Nombre del Empaque</th>
                            <th class="py-3 text-end pe-4" style="font-size: 12.5px; text-transform: uppercase; color: #64748b; width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tipoEmpaques as $tipoEmpaque)
                        <tr>
                            <td class="ps-4 py-3">
                                <span class="badge rounded-pill px-3 py-1.5 font-monospace" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; font-size: 12px;">
                                    {{ $tipoEmpaque->codigotipoempaque }}
                                </span>
                            </td>
                            <td class="py-3 fw-semibold text-dark">
                                <i class="fas fa-box text-muted me-2"></i>{{ $tipoEmpaque->nombretipoempaque }}
                            </td>
                            <td class="py-3 text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('tipoempaque.edit', $tipoEmpaque->codigotipoempaque) }}" 
                                       class="btn btn-sm btn-outline-warning rounded-circle d-flex align-items-center justify-content-center shadow-xs" 
                                       style="width: 32px; height: 32px;" title="Editar">
                                        <i class="fas fa-edit" style="font-size: 12px;"></i>
                                    </a>
                                    <form action="{{ route('tipoempaque.destroy', $tipoEmpaque->codigotipoempaque) }}" method="POST" class="d-inline form-eliminar-empaque">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center shadow-xs btn-delete-empaque" 
                                                style="width: 32px; height: 32px;" title="Eliminar" data-nombre="{{ $tipoEmpaque->nombretipoempaque }}">
                                            <i class="fas fa-trash" style="font-size: 12px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open d-block mb-2" style="font-size: 32px; opacity: 0.3;"></i>
                                No hay tipos de empaque registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($tipoEmpaques, 'hasPages') && $tipoEmpaques->hasPages())
                <div class="p-3 border-top d-flex justify-content-center">
                    {{ $tipoEmpaques->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete-empaque');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');
            const nombre = this.getAttribute('data-nombre');

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Eliminar tipo de empaque?',
                    text: `¿Estás seguro de eliminar "${nombre}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: { popup: 'rounded-4 shadow-lg' }
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            } else {
                if (confirm(`¿Estás seguro de eliminar "${nombre}"?`)) form.submit();
            }
        });
    });
});
</script>
@endsection
