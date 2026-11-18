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
                    Gestión de Empleados
                </li>
            </ol>
        </nav>
    </div>

    <!-- Encabezado de la página -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 pb-3 mb-4 border-bottom" style="border-color: #e2e8f0 !important;">
        <div>
            <h2 class="fw-bold mb-1 text-dark tracking-tight" style="letter-spacing: -0.025em;">
                Gestión de Empleados
            </h2>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Administración de personal operativo, jefes de bodega, ejecutivos comerciales y credenciales de acceso.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                <i class="fas fa-arrow-left me-1"></i> Volver al Panel
            </a>
            @can('crear empleado')
            <a href="{{ route('empleados.create') }}" class="btn btn-sm btn-primary rounded-3 px-3 py-2 fw-semibold shadow-sm">
                <i class="fas fa-user-plus me-1.5"></i> Añadir Nuevo Empleado
            </a>
            @endcan
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

    <!-- Tarjetas de Resumen Ejecutivo (Estilo Impeccable) -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Total Empleados -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Total Empleados</span>
                        <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold" style="font-size: 11px;">
                            Nómina
                        </span>
                    </div>
                    <div class="metric-value">
                        {{ $totalEmpleados ?? $empleados->total() }}
                        <span class="metric-unit">colaboradores</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-user-tie text-primary me-1"></i> Personal activo en la empresa
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Bodegas en el Sistema -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Bodegas Asignadas</span>
                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis fw-semibold" style="font-size: 11px;">
                            Sedes
                        </span>
                    </div>
                    <div class="metric-value font-mono">
                        {{ $totalBodegas ?? count($bodegas ?? []) }}
                        <span class="metric-unit">almacenes</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-warehouse text-warning me-1"></i> Puntos con personal asignado
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Cargos / Roles -->
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Cargos Operativos</span>
                        <span class="badge rounded-pill bg-info-subtle text-info fw-semibold" style="font-size: 11px;">
                            Perfiles
                        </span>
                    </div>
                    <div class="metric-value font-mono">
                        {{ count($cargos ?? []) }}
                        <span class="metric-unit">categorías</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-briefcase text-info me-1"></i> Niveles de responsabilidad
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Búsqueda y Filtros -->
    <div class="card border rounded-3 mb-4 bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-3 p-md-3.5">
            <form action="{{ route('empleados.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-search" style="font-size: 12px;"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-1" 
                               placeholder="Buscar por nombre, cédula o email..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-briefcase" style="font-size: 12px;"></i>
                        </span>
                        <select name="cargo" class="form-select border-start-0 ps-1">
                            <option value="">Todos los cargos</option>
                            @if(isset($cargos))
                                @foreach($cargos as $idCargo => $nombreCargo)
                                    <option value="{{ $idCargo }}" {{ request('cargo') == $idCargo ? 'selected' : '' }}>
                                        {{ $nombreCargo }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-warehouse" style="font-size: 12px;"></i>
                        </span>
                        <select name="bodega" class="form-select border-start-0 ps-1">
                            <option value="">Todas las bodegas</option>
                            @if(isset($bodegas))
                                @foreach($bodegas as $b)
                                    <option value="{{ $b->idbodega }}" {{ request('bodega') == $b->idbodega ? 'selected' : '' }}>
                                        {{ $b->nombrebodega }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-3 px-3 w-100 fw-semibold">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                    @if(request()->hasAny(['search', 'cargo', 'bodega']))
                    <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary rounded-3 px-3" title="Limpiar filtros">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Principal de Empleados -->
    <div class="card border rounded-3 overflow-hidden shadow-xs bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 operational-table">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3" style="width: 290px;">Empleado</th>
                            <th class="py-3" style="width: 150px;">Identificación</th>
                            <th class="py-3" style="width: 150px;">Contacto</th>
                            <th class="py-3" style="width: 170px;">Cargo / Rol</th>
                            <th class="py-3">Bodega Asignada</th>
                            @if(auth()->user()->can('editar empleado') || auth()->user()->can('eliminar empleado'))
                            <th class="text-end pe-4 py-3" style="width: 150px;">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($empleados as $empleado)
                            @php
                                $nombreCompleto = trim($empleado->nombreemp . ' ' . $empleado->apellidoemp);
                                $initial = strtoupper(substr($empleado->nombreemp, 0, 1));
                                $cargoNombre = $empleado->cargoNombre();
                                
                                $cargoStyles = [
                                    'Administrador'   => ['bg' => '#fee2e2', 'color' => '#991b1b', 'border' => '#fecaca', 'icon' => 'fa-user-shield'],
                                    'Jefe de Bodega'  => ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'icon' => 'fa-warehouse'],
                                    'Vendedor'        => ['bg' => '#dbeafe', 'color' => '#1e40af', 'border' => '#bfdbfe', 'icon' => 'fa-shopping-bag'],
                                ];
                                $cStyle = $cargoStyles[$cargoNombre] ?? ['bg' => '#f1f5f9', 'color' => '#334155', 'border' => '#e2e8f0', 'icon' => 'fa-briefcase'];
                            @endphp
                            <tr class="operational-row">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                                             style="width: 38px; height: 38px; background: {{ $cStyle['bg'] }}; color: {{ $cStyle['color'] }}; font-weight: 700; font-size: 14px;">
                                            {{ $initial }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 13.5px;">{{ $nombreCompleto }}</div>
                                            <div class="text-muted d-flex align-items-center gap-1" style="font-size: 12px;">
                                                <span id="email-{{ $empleado->nro_identificacion }}">{{ $empleado->email }}</span>
                                                <button type="button" class="btn btn-link p-0 text-muted text-decoration-none ms-1"
                                                        onclick="copyToClipboard('{{ $empleado->nro_identificacion }}')" 
                                                        title="Copiar correo">
                                                    <i id="icon-{{ $empleado->nro_identificacion }}" class="fas fa-copy" style="font-size: 11px;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div>
                                        <span class="badge rounded-pill bg-light text-secondary border px-2 py-0.5 me-1" style="font-size: 10px; border-color: #e2e8f0 !important;">
                                            {{ strtoupper($empleado->tipo_identificacion === 'Cedula' ? 'Cédula' : $empleado->tipo_identificacion) }}
                                        </span>
                                        <span class="font-mono fw-bold text-dark" style="font-size: 12.5px;">{{ $empleado->nro_identificacion }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    @if($empleado->nro_telefono)
                                        <div class="text-dark d-flex align-items-center gap-1 font-mono" style="font-size: 12.5px;">
                                            <i class="fas fa-phone text-muted" style="font-size: 10px;"></i>
                                            <span>{{ $empleado->nro_telefono }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted" style="font-size: 12px;">—</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-2.5 py-1 fw-semibold" style="background: {{ $cStyle['bg'] }}; color: {{ $cStyle['color'] }}; border: 1px solid {{ $cStyle['border'] }}; font-size: 11.5px;">
                                        <i class="fas {{ $cStyle['icon'] }} me-1" style="font-size: 10px;"></i>{{ $cargoNombre }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    @if($empleado->bodega)
                                        <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(0, 151, 167, 0.08); color: #006064; border: 1px solid rgba(0, 151, 167, 0.2); font-size: 12px;">
                                            <i class="fas fa-warehouse me-1 text-primary"></i>{{ $empleado->bodega->nombrebodega }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size: 12px;">Sin bodega asignada</span>
                                    @endif
                                </td>
                                @if(auth()->user()->can('editar empleado') || auth()->user()->can('eliminar empleado'))
                                <td class="text-end pe-4 py-3">
                                    <div class="d-inline-flex gap-1">
                                        @can('editar empleado')
                                        <a href="{{ route('empleados.edit', $empleado->nro_identificacion) }}" 
                                           class="btn-action-circle text-primary" 
                                           title="Editar empleado">
                                            <i class="fas fa-edit" style="font-size: 12px;"></i>
                                        </a>
                                        @endcan
                                        
                                        @can('editar empleado')
                                        <form action="{{ route('empleados.reset_password', $empleado->nro_identificacion) }}" method="POST" class="d-inline form-reset">
                                            @csrf
                                            <button type="button" class="btn-action-circle text-warning btn-reset" 
                                                    title="Restablecer contraseña a la cédula">
                                                <i class="fas fa-key" style="font-size: 12px;"></i>
                                            </button>
                                        </form>
                                        @endcan

                                        @can('eliminar empleado')
                                        <form action="{{ route('empleados.destroy', $empleado->nro_identificacion) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-action-circle danger btn-delete" 
                                                    title="Eliminar empleado">
                                                <i class="fas fa-trash" style="font-size: 12px;"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: #f1f5f9; color: #94a3b8;">
                                            <i class="fas fa-users-slash fs-4"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No se encontraron empleados</h6>
                                        <p class="text-muted mb-3" style="font-size: 13.5px;">Intenta cambiar los términos de búsqueda o registra un nuevo empleado.</p>
                                        @if(request()->hasAny(['search', 'cargo', 'bodega']))
                                            <a href="{{ route('empleados.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3">
                                                <i class="fas fa-undo me-1"></i> Restablecer filtros
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($empleados, 'links') && $empleados->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-center bg-white" style="border-color: #e2e8f0 !important;">
                    {{ $empleados->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function copyToClipboard(empleadoId) {
    var emailElem = document.getElementById('email-' + empleadoId);
    if (!emailElem) return;
    var emailText = emailElem.innerText.trim();
    navigator.clipboard.writeText(emailText).then(function() {
        var icon = document.getElementById('icon-' + empleadoId);
        if (icon) {
            icon.classList.remove('fa-copy');
            icon.classList.add('fa-check', 'text-success');
            setTimeout(function() {
                icon.classList.remove('fa-check', 'text-success');
                icon.classList.add('fa-copy');
            }, 2000);
        }
    }).catch(function(err) {
        console.error('Error al copiar: ', err);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // Confirmación de Restablecimiento de contraseña
    const btnsReset = document.querySelectorAll('.btn-reset');
    btnsReset.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            let form = this.closest('form');
            Swal.fire({
                title: '¿Restablecer contraseña?',
                text: "La contraseña de acceso volverá a ser el número de cédula del empleado y se le solicitará cambio en su próximo inicio de sesión.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0097a7',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-key me-1"></i> Sí, restablecer',
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

    // Confirmación de Eliminación
    const btnsDelete = document.querySelectorAll('.btn-delete');
    btnsDelete.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            let form = this.closest('form');
            Swal.fire({
                title: '¿Eliminar empleado?',
                text: "Esta acción no se puede deshacer. Se desactivará o eliminará la ficha del empleado y su cuenta asociada.",
                icon: 'error',
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
