@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de la página -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h3 class="fw-bold mb-0">Editar Rol: {{ $role->name }}</h3>
                @if($esProtegido)
                    <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 12px;">
                        <i class="fas fa-lock me-1"></i> Rol del Sistema
                    </span>
                @endif
            </div>
            <p class="page-subtitle mb-0 text-muted">Configuración de perfiles y actualización de la matriz de autorización</p>
        </div>
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver a Roles
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-center mb-1">
                <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                <strong class="fs-6">Se encontraron errores en el formulario:</strong>
            </div>
            <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4" style="max-width: 1000px; margin: 0 auto; border-top: 3px solid var(--primary) !important;">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Campo Nombre del Rol -->
                <div class="mb-4 pb-3 border-bottom">
                    <label for="name" class="form-label fw-bold text-dark" style="font-size: 14px;">
                        <i class="fas fa-user-shield text-primary me-1"></i> Nombre del Rol <span class="text-danger">*</span>
                    </label>
                    @if($esProtegido)
                        <input type="text" class="form-control form-control-lg rounded-3" value="{{ $role->name }}" disabled
                               style="background: #f8fafc; color: #64748b; cursor: not-allowed;">
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-info-circle me-1 text-warning"></i>El nombre de este rol fundamental del sistema está protegido contra cambios para mantener la integridad de los accesos.
                        </small>
                    @else
                        <input type="text" name="name" id="name" class="form-control form-control-lg rounded-3" 
                               value="{{ old('name', $role->name) }}" required>
                        <small class="text-muted d-block mt-1">Nombre asignado al perfil de usuario.</small>
                    @endif
                </div>

                <!-- Matriz de Permisos Organizada por Módulos -->
                <div class="mb-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">
                                <i class="fas fa-key text-warning me-2"></i>Matriz de Permisos del Rol
                            </h5>
                            <p class="text-muted mb-0" style="font-size: 13px;">Marca o desmarca los módulos y capacidades permitidas para los usuarios de este perfil.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-xs" id="selectAll">
                                <i class="fas fa-check-double me-1"></i> Marcar Todo
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-xs" id="deselectAll">
                                <i class="fas fa-times me-1"></i> Desmarcar Todo
                            </button>
                        </div>
                    </div>

                    <div class="row g-3">
                        @foreach($permissionsGrouped as $grupo => $permisos)
                            @if($permisos->count() > 0)
                                @php
                                    $moduloMeta = [
                                        'Dashboard y KPIs'     => ['icon' => 'fa-chart-pie', 'color' => '#0284c7', 'bg' => '#e0f2fe'],
                                        'Catálogo y Productos' => ['icon' => 'fa-box-open',  'color' => '#059669', 'bg' => '#d1fae5'],
                                        'Clientes'             => ['icon' => 'fa-user-tie',  'color' => '#0097a7', 'bg' => '#e0f7fa'],
                                        'Empleados y Personal' => ['icon' => 'fa-users',     'color' => '#dc2626', 'bg' => '#fee2e2'],
                                        'Bodegas y Almacén'    => ['icon' => 'fa-warehouse', 'color' => '#d97706', 'bg' => '#fef3c7'],
                                        'Notas y Solicitudes'  => ['icon' => 'fa-clipboard-list', 'color' => '#7c3aed', 'bg' => '#ede9fe'],
                                        'Movimientos'          => ['icon' => 'fa-exchange-alt', 'color' => '#0891b2', 'bg' => '#cffafe'],
                                        'Ventas y Cobros'      => ['icon' => 'fa-cash-register', 'color' => '#b45309', 'bg' => '#fef3c7'],
                                        'Usuarios'             => ['icon' => 'fa-user-shield', 'color' => '#2563eb', 'bg' => '#dbeafe'],
                                        'Roles y Seguridad'    => ['icon' => 'fa-lock',      'color' => '#475569', 'bg' => '#f1f5f9'],
                                    ];
                                    $meta = $moduloMeta[$grupo] ?? ['icon' => 'fa-folder', 'color' => '#64748b', 'bg' => '#f8fafc'];
                                @endphp
                                <div class="col-md-6">
                                    <div class="card h-100 border rounded-3 shadow-xs permission-group">
                                        <div class="card-header py-2.5 px-3 bg-white border-bottom d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; background: {{ $meta['bg'] }}; color: {{ $meta['color'] }}; font-size: 12px;">
                                                    <i class="fas {{ $meta['icon'] }}"></i>
                                                </div>
                                                <span class="fw-bold text-dark" style="font-size: 13px;">{{ $grupo }}</span>
                                                <span class="badge rounded-pill ms-2" style="background: {{ $meta['bg'] }}; color: {{ $meta['color'] }}; font-size: 11px;">
                                                    {{ $permisos->count() }}
                                                </span>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-link toggle-group p-0 text-decoration-none fw-semibold" style="font-size: 11.5px; color: var(--primary);">
                                                Alternar módulo
                                            </button>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="d-flex flex-column gap-1.5">
                                                @foreach($permisos as $permission)
                                                    <label class="permission-checkbox-custom p-2 rounded-2 d-flex align-items-center">
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                               class="form-check-input mt-0 me-2 perm-check"
                                                               {{ in_array($permission->id, $rolePermissionIds) ? 'checked' : '' }}>
                                                        <span class="text-dark" style="font-size: 13px;">{{ $permission->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <hr class="my-4" style="border-color: #e2e8f0;">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.permission-checkbox-custom {
    cursor: pointer;
    transition: background-color 0.15s ease;
    border: 1px solid transparent;
}
.permission-checkbox-custom:hover {
    background-color: #f8fafc;
    border-color: #e2e8f0;
}
.shadow-xs {
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar todo
    document.getElementById('selectAll').addEventListener('click', function() {
        document.querySelectorAll('.perm-check').forEach(cb => cb.checked = true);
    });

    // Deseleccionar todo
    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('.perm-check').forEach(cb => cb.checked = false);
    });

    // Toggle por grupo
    document.querySelectorAll('.toggle-group').forEach(btn => {
        btn.addEventListener('click', function() {
            const group = this.closest('.permission-group');
            const checkboxes = group.querySelectorAll('.perm-check');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
        });
    });
});
</script>
@endsection
