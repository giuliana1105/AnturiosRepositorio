@extends('layouts.app')

@section('content')
<div class="container-fluid py-3 px-3 px-md-4">
    <!-- Breadcrumb & Wayfinding -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                        <i class="fas fa-home me-1 text-primary"></i> Inicio
                    </a>
                </li>
                <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                    Puntos de Almacenamiento (Bodegas)
                </li>
            </ol>
        </nav>
    </div>

    <!-- Encabezado de la página -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 pb-3 mb-4 border-bottom" style="border-color: #e2e8f0 !important;">
        <div>
            <h2 class="fw-bold mb-1 text-dark tracking-tight" style="letter-spacing: -0.025em;">
                Puntos de Almacenamiento
            </h2>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Administración de bodegas centrales y sucursales, existencias físicas locales y puntos de emisión de ventas.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                <i class="fas fa-arrow-left me-1"></i> Volver al Panel
            </a>
            <button class="btn btn-sm btn-primary rounded-3 px-3 py-2 fw-semibold shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNuevaBodega" aria-expanded="false" aria-controls="collapseNuevaBodega">
                <i class="fas fa-plus me-1.5"></i> Registrar Nueva Bodega
            </button>
        </div>
    </div>

    <!-- Alertas de Sesión -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-center mb-1">
                <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                <strong class="fs-6">Errores al guardar la bodega:</strong>
            </div>
            <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tarjetas de Resumen Ejecutivo (Estilo Impeccable) -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Bodegas Habilitadas</span>
                        <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold" style="font-size: 11px;">
                            Sedes Físicas
                        </span>
                    </div>
                    <div class="metric-value">
                        {{ $totalBodegas ?? $bodegas->total() }}
                        <span class="metric-unit">almacenes</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-warehouse text-primary me-1"></i> Centros de recepción y despacho
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Personal en Bodega</span>
                        <span class="badge rounded-pill bg-success-subtle text-success fw-semibold" style="font-size: 11px;">
                            Personal
                        </span>
                    </div>
                    <div class="metric-value font-mono">
                        {{ $totalEmpleados ?? '—' }}
                        <span class="metric-unit">colaboradores</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-users text-success me-1"></i> Asignados operativamente
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de Creación Rápida (Colapsable) -->
    <div class="collapse {{ $errors->any() ? 'show' : '' }} mb-4" id="collapseNuevaBodega">
        <div class="card border rounded-3 bg-white" style="border-color: #e2e8f0 !important; border-top: 3px solid var(--primary) !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(0, 151, 167, 0.1); color: var(--primary);">
                        <i class="fas fa-plus" style="font-size: 12px;"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-dark">Registrar Nueva Bodega</h6>
                </div>
                <form method="POST" action="{{ route('bodegas.store') }}" role="form">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-8">
                            <label for="nombrebodega" class="form-label fw-bold text-dark" style="font-size: 13px;">Nombre de la Bodega <span class="text-danger">*</span></label>
                            <input type="text" name="nombrebodega" id="nombrebodega"
                                class="form-control rounded-3" placeholder="Ej: Central, Bodega Norte, Sucursal Ambato..."
                                value="{{ old('nombrebodega') }}" required maxlength="50">
                        </div>
                        <div class="col-12 col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-3 px-4 w-100 fw-semibold">
                                <i class="fas fa-save me-1"></i> Guardar Bodega
                            </button>
                            <button type="button" class="btn btn-outline-secondary rounded-3 px-3" data-bs-toggle="collapse" data-bs-target="#collapseNuevaBodega">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Barra de Búsqueda -->
    <div class="card border rounded-3 mb-4 bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-3">
            <form action="{{ route('bodegas.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-9">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-search" style="font-size: 13px;"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-1" 
                               placeholder="Buscar bodega por nombre..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-3 px-3 w-100 fw-semibold">
                        <i class="fas fa-search me-1"></i> Buscar
                    </button>
                    @if(request()->filled('search'))
                    <a href="{{ route('bodegas.index') }}" class="btn btn-outline-secondary rounded-3 px-3" title="Limpiar filtro">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Bodegas en Tarjetas Operativas -->
    <div class="row g-3">
        @forelse ($bodegas as $bodega)
            <div class="col-12 col-lg-6">
                <div class="card border rounded-3 h-100 hover-card bg-white" style="border-color: #e2e8f0 !important;">
                    <div class="card-body p-3.5 p-md-4 d-flex flex-column justify-content-between">
                        <div>
                            <!-- Header de la Bodega -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                                         style="width: 44px; height: 44px; background: rgba(0, 151, 167, 0.1); color: var(--primary);">
                                        <i class="fas fa-warehouse fs-5"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1 text-dark tracking-tight">{{ $bodega->nombrebodega }}</h5>
                                        <div class="d-flex gap-1.5 align-items-center flex-wrap">
                                            <span class="font-mono text-muted fw-bold" style="font-size: 11.5px;">
                                                #{{ $bodega->idbodega }}
                                            </span>
                                            <span class="text-muted">·</span>
                                            <span class="badge rounded-pill bg-light text-secondary border font-mono px-2 py-0.5" style="font-size: 11px; border-color: #e2e8f0 !important;">
                                                <i class="fas fa-users me-1 text-muted"></i>{{ $bodega->empleados_count ?? 0 }} {{ ($bodega->empleados_count ?? 0) === 1 ? 'empleado' : 'empleados' }}
                                            </span>
                                            <span class="badge rounded-pill bg-light text-secondary border font-mono px-2 py-0.5" style="font-size: 11px; border-color: #e2e8f0 !important;">
                                                <i class="fas fa-receipt me-1 text-muted"></i>{{ $bodega->ventas_count ?? 0 }} {{ ($bodega->ventas_count ?? 0) === 1 ? 'venta' : 'ventas' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menú de edición rápida -->
                                <div class="d-flex gap-1">
                                    <a href="{{ route('bodegas.edit', $bodega->idbodega) }}" class="btn-action-circle text-primary" title="Editar nombre">
                                        <i class="fas fa-edit" style="font-size: 12px;"></i>
                                    </a>
                                    <form action="{{ route('bodegas.destroy', $bodega->idbodega) }}" method="POST" class="d-inline form-delete-bodega">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-action-circle danger btn-delete-bodega" title="Eliminar bodega">
                                            <i class="fas fa-trash" style="font-size: 12px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones Principales del Almacén -->
                        <div class="pt-3 border-top mt-2" style="border-color: #f1f5f9 !important;">
                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="{{ route('bodega.show', $bodega->idbodega) }}" class="btn btn-outline-primary rounded-3 w-100 py-1.5 fw-medium" style="font-size: 12.5px;">
                                        <i class="fas fa-boxes me-1"></i> Stock Físico
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('venta.index.bodega', $bodega->idbodega) }}" class="btn btn-outline-secondary rounded-3 w-100 py-1.5 fw-medium" style="font-size: 12.5px;">
                                        <i class="fas fa-receipt me-1"></i> Ver Ventas
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border rounded-3 text-center py-5 bg-white" style="border-color: #e2e8f0 !important;">
                    <div class="card-body">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: #f1f5f9; color: #94a3b8;">
                            <i class="fas fa-warehouse fs-4"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">No se encontraron bodegas</h6>
                        <p class="text-muted mb-3" style="font-size: 13.5px;">Comienza registrando la primera bodega para gestionar existencias y ventas.</p>
                        <button class="btn btn-sm btn-primary rounded-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNuevaBodega">
                            <i class="fas fa-plus me-1"></i> Crear Bodega Ahora
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if(method_exists($bodegas, 'links') && $bodegas->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $bodegas->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>

<style>
.hover-card {
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06) !important;
}
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnsDelete = document.querySelectorAll('.btn-delete-bodega');
    btnsDelete.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            let form = this.closest('form');
            Swal.fire({
                title: '¿Eliminar esta bodega?',
                text: "Si la bodega contiene registros de inventario o ventas asociadas, la base de datos podría restringir su eliminación.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'rounded-pill px-3',
                    cancelButton: 'rounded-pill px-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size: 12px;"></i>';
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
