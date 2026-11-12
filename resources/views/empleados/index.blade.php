@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de la página -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Gestión de Empleados</h3>
            <p class="page-subtitle mb-0 text-muted">Personal administrativo, operativo y comercial de la empresa</p>
        </div>
        @can('crear empleado')
        <a href="{{ route('empleados.create') }}" class="btn btn-primary rounded-pill px-3 shadow-sm">
            <i class="fas fa-user-plus me-1"></i> Añadir Nuevo Empleado
        </a>
        @endcan
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

    <!-- Tarjetas de Resumen KPI -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                        <i class="fas fa-user-tie fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total Empleados</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalEmpleados ?? $empleados->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.12); color: #d97706;">
                        <i class="fas fa-warehouse fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Bodegas en el Sistema</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalBodegas ?? count($bodegas ?? []) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                        <i class="fas fa-briefcase fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Cargos / Roles</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ count($cargos ?? []) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Búsqueda y Filtros -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('empleados.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-1" 
                               placeholder="Buscar por nombre, cédula o email..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-briefcase"></i>
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
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-warehouse"></i>
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
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-3 w-100 shadow-sm">
                        <i class="fas fa-search me-1"></i> Filtrar
                    </button>
                    @if(request()->hasAny(['search', 'cargo', 'bodega']))
                    <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary rounded-pill px-3" title="Limpiar filtros">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Principal de Empleados -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Empleado</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Identificación</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Contacto</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Cargo / Rol</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Bodega Asignada</th>
                            @if(auth()->user()->can('editar empleado') || auth()->user()->can('eliminar empleado'))
                            <th class="text-end pe-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($empleados as $empleado)
                            @php
                                $nombreCompleto = trim($empleado->nombreemp . ' ' . $empleado->apellidoemp);
                                $initial = strtoupper(substr($empleado->nombreemp, 0, 1));
                                $cargoNombre = $empleado->cargoNombre();
                                
                                $cargoStyles = [
                                    'Administrador'   => ['bg' => '#fee2e2', 'color' => '#991b1b', 'border' => '#fecaca', 'icon' => 'fa-crown'],
                                    'Jefe de Bodega'  => ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'icon' => 'fa-warehouse'],
                                    'Vendedor'        => ['bg' => '#dbeafe', 'color' => '#1e40af', 'border' => '#bfdbfe', 'icon' => 'fa-shopping-bag'],
                                ];
                                $cStyle = $cargoStyles[$cargoNombre] ?? ['bg' => '#f1f5f9', 'color' => '#334155', 'border' => '#e2e8f0', 'icon' => 'fa-briefcase'];
                            @endphp
                            <tr style="transition: background-color 0.15s ease;">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar-circle me-3" style="background: {{ $cStyle['bg'] }}; color: {{ $cStyle['color'] }};">
                                            {{ $initial }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 14px;">{{ $nombreCompleto }}</div>
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
                                        <span class="badge rounded-pill bg-light text-secondary border px-2 py-0.5 me-1" style="font-size: 10px;">
                                            {{ strtoupper($empleado->tipo_identificacion === 'Cedula' ? 'Cédula' : $empleado->tipo_identificacion) }}
                                        </span>
                                        <span class="font-mono fw-bold text-dark" style="font-size: 13px;">{{ $empleado->nro_identificacion }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    @if($empleado->nro_telefono)
                                        <div class="text-dark d-flex align-items-center gap-1" style="font-size: 13px;">
                                            <i class="fas fa-phone text-muted" style="font-size: 11px;"></i>
                                            <span class="font-mono">{{ $empleado->nro_telefono }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted" style="font-size: 12px;">—</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: {{ $cStyle['bg'] }}; color: {{ $cStyle['color'] }}; border: 1px solid {{ $cStyle['border'] }}; font-size: 12px;">
                                        <i class="fas {{ $cStyle['icon'] }} me-1" style="font-size: 10px;"></i>{{ $cargoNombre }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    @if($empleado->bodega)
                                        <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(0, 151, 167, 0.08); color: #006064; border: 1px solid rgba(0, 151, 167, 0.2); font-size: 12px;">
                                            <i class="fas fa-warehouse me-1 text-primary"></i>{{ $empleado->bodega->nombrebodega }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size: 12px;">Sin bodega</span>
                                    @endif
                                </td>
                                @if(auth()->user()->can('editar empleado') || auth()->user()->can('eliminar empleado'))
                                <td class="text-end pe-4 py-3">
                                    <div class="d-inline-flex gap-1">
                                        @can('editar empleado')
                                        <a href="{{ route('empleados.edit', $empleado->nro_identificacion) }}" 
                                           class="btn btn-sm btn-outline-primary rounded-circle" 
                                           title="Editar empleado" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-edit" style="font-size: 12px;"></i>
                                        </a>
                                        @endcan
                                        
                                        @can('editar empleado')
                                        <form action="{{ route('empleados.reset_password', $empleado->nro_identificacion) }}" method="POST" class="d-inline form-reset">
                                            @csrf
                                            <button type="button" class="btn btn-sm btn-outline-warning rounded-circle btn-reset" 
                                                    title="Restablecer contraseña a la cédula" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-key" style="font-size: 12px;"></i>
                                            </button>
                                        </form>
                                        @endcan

                                        @can('eliminar empleado')
                                        <form action="{{ route('empleados.destroy', $empleado->nro_identificacion) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle btn-delete" 
                                                    title="Eliminar empleado" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
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
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: #f1f5f9; color: #94a3b8;">
                                            <i class="fas fa-users-slash fs-3"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No se encontraron empleados</h6>
                                        <p class="text-muted mb-3" style="font-size: 13.5px;">Intenta cambiar los términos de búsqueda o registra un nuevo empleado.</p>
                                        @if(request()->hasAny(['search', 'cargo', 'bodega']))
                                            <a href="{{ route('empleados.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
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
                <div class="px-4 py-3 border-top d-flex justify-content-center bg-white">
                    {{ $empleados->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.user-avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 15px;
    flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
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
