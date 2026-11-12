@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de la página -->
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Editar Rol y Permisos</h3>
            <p class="page-subtitle mb-0 text-muted">Gestión de accesos y nivel de autorización para este usuario</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver a Usuarios
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

    <div class="card border-0 shadow-sm rounded-4 mb-4" style="max-width: 960px; margin: 0 auto;">
        <div class="card-body p-4 p-md-5">
            {{-- Header de perfil de usuario --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between p-3 rounded-4 mb-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0;">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-xs" style="width: 52px; height: 52px; background: rgba(0, 151, 167, 0.15); color: var(--primary);">
                        <span class="fw-bold fs-4">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold text-dark">{{ $user->name }}</h5>
                        <div class="text-muted d-flex align-items-center gap-2" style="font-size: 13.5px;">
                            <i class="far fa-envelope text-muted"></i>
                            <span>{{ $user->email }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-2 mt-md-0">
                    @if($user->roles->isNotEmpty())
                        <span class="badge rounded-pill px-3 py-1.5 fw-semibold" style="background: rgba(0, 151, 167, 0.12); color: var(--primary); border: 1px solid rgba(0, 151, 167, 0.25); font-size: 12.5px;">
                            <i class="fas fa-shield-alt me-1"></i> Rol Actual: {{ $user->roles->first()->name }}
                        </span>
                    @else
                        <span class="badge bg-secondary rounded-pill px-3 py-1.5" style="font-size: 12px;">Sin Rol Asignado</span>
                    @endif
                </div>
            </div>

            {{-- Alerta informativa UX --}}
            <div class="alert d-flex align-items-start gap-3 p-3 rounded-3 mb-4 border-0" style="background: rgba(59, 130, 246, 0.08); color: #1e40af;">
                <i class="fas fa-info-circle mt-1 fs-5 text-primary"></i>
                <div style="font-size: 13.5px;">
                    El nombre completo y correo electrónico del usuario se administran en la ficha del empleado. Aquí puedes cambiar su <strong>rol del sistema</strong> y otorgarle <strong>permisos directos adicionales</strong> en caso de excepciones operativas.
                </div>
            </div>

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Nombre Completo <i class="fas fa-lock ms-1 text-muted" style="font-size: 11px;" title="Bloqueado - gestionado desde Empleados"></i>
                        </label>
                        <input type="text" id="name" class="form-control rounded-3" value="{{ $user->name }}" readonly disabled style="background: #f8fafc; color: #64748b;">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Correo Electrónico <i class="fas fa-lock ms-1 text-muted" style="font-size: 11px;" title="Bloqueado - gestionado desde Empleados"></i>
                        </label>
                        <input type="email" id="email" class="form-control rounded-3" value="{{ $user->email }}" readonly disabled style="background: #f8fafc; color: #64748b;">
                    </div>

                    <div class="col-12">
                        <label for="role" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            <i class="fas fa-shield-alt text-primary me-1"></i> Rol Asignado <span class="text-danger">*</span>
                        </label>
                        <select name="role" id="role" class="form-select form-select-lg rounded-3" required style="font-size: 14.5px;">
                            <option value="">Seleccione un rol para el usuario</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->roles->pluck('id')->contains($role->id) ? 'selected' : '' }}>
                                    {{ $role->name }} &nbsp;—&nbsp; ({{ $role->permissions->count() }} permisos predeterminados)
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">El usuario heredará todos los accesos configurados en este rol.</small>
                    </div>
                </div>

                {{-- Permisos directos adicionales --}}
                <div class="mt-5 pt-3 border-top">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">
                                <i class="fas fa-key text-warning me-2"></i>Permisos Directos Adicionales
                            </h6>
                            <p class="text-muted mb-0" style="font-size: 13px;">
                                Permisos específicos otorgados a este usuario más allá de los provistos por su rol.
                            </p>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="togglePerms" data-expanded="{{ count($directPermissions) > 0 ? 'true' : 'false' }}">
                            <i class="fas fa-chevron-{{ count($directPermissions) > 0 ? 'up' : 'down' }} me-1"></i>
                            <span>{{ count($directPermissions) > 0 ? 'Ocultar catálogo de permisos' : 'Personalizar permisos directos' }}</span>
                        </button>
                    </div>

                    <div id="directPermissionsSection" style="display: {{ count($directPermissions) > 0 ? 'block' : 'none' }};">
                        <div class="row g-3 mt-2">
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
                                        <div class="card h-100 border rounded-3 shadow-xs">
                                            <div class="card-header py-2 px-3 bg-white border-bottom d-flex align-items-center">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; background: {{ $meta['bg'] }}; color: {{ $meta['color'] }}; font-size: 12px;">
                                                    <i class="fas {{ $meta['icon'] }}"></i>
                                                </div>
                                                <span class="fw-bold text-dark" style="font-size: 13px;">{{ $grupo }}</span>
                                                <span class="badge rounded-pill ms-auto" style="background: {{ $meta['bg'] }}; color: {{ $meta['color'] }}; font-size: 11px;">
                                                    {{ $permisos->count() }}
                                                </span>
                                            </div>
                                            <div class="card-body p-3">
                                                <div class="d-flex flex-column gap-2">
                                                    @foreach($permisos as $permission)
                                                        <label class="permission-checkbox-custom p-2 rounded-2 d-flex align-items-center">
                                                            <input type="checkbox" name="direct_permissions[]" value="{{ $permission->id }}" 
                                                                   class="form-check-input mt-0 me-2"
                                                                   {{ in_array($permission->id, $directPermissions) ? 'checked' : '' }}>
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
                </div>

                <hr class="my-4" style="border-color: #e2e8f0;">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        Cancelar
                    </a>
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
    const toggleBtn = document.getElementById('togglePerms');
    const permsSection = document.getElementById('directPermissionsSection');
    
    if (toggleBtn && permsSection) {
        toggleBtn.addEventListener('click', function() {
            const isExpanded = this.dataset.expanded === 'true';
            permsSection.style.display = isExpanded ? 'none' : 'block';
            this.dataset.expanded = (!isExpanded).toString();
            const spanText = this.querySelector('span');
            const icon = this.querySelector('i');
            if (isExpanded) {
                icon.className = 'fas fa-chevron-down me-1';
                spanText.textContent = 'Personalizar permisos directos';
            } else {
                icon.className = 'fas fa-chevron-up me-1';
                spanText.textContent = 'Ocultar catálogo de permisos';
            }
        });
    }
});
</script>
@endsection
