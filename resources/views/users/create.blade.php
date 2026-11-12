@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de Página -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--foreground); letter-spacing: -0.5px;">
                <i class="fas fa-user-plus me-2" style="color: var(--primary);"></i>Crear Nuevo Usuario
            </h3>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Registro de credenciales de acceso y asignación de rol en el sistema.
            </p>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver a Usuarios
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert" style="max-width: 960px; margin: 0 auto 1.5rem auto;">
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
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                     style="width: 48px; height: 48px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                    <i class="fas fa-user-shield fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0">Credenciales y Acceso</h5>
                    <small class="text-muted">Define los accesos iniciales para la cuenta de usuario</small>
                </div>
            </div>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Nombre Completo <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3">
                                <i class="fas fa-user text-muted"></i>
                            </span>
                            <input type="text" name="name" id="name" 
                                   class="form-control border-0 bg-light rounded-end-pill py-2" 
                                   placeholder="Ej: Juan Pérez" required value="{{ old('name') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Correo Electrónico <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3">
                                <i class="fas fa-envelope text-muted"></i>
                            </span>
                            <input type="email" name="email" id="email" 
                                   class="form-control border-0 bg-light rounded-end-pill py-2" 
                                   placeholder="usuario@empresa.com" required value="{{ old('email') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Contraseña Inicial <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input type="password" name="password" id="password" 
                                   class="form-control border-0 bg-light rounded-end-pill py-2" 
                                   placeholder="Mínimo 8 caracteres" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Rol Principal <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3">
                                <i class="fas fa-shield-alt text-muted"></i>
                            </span>
                            <select name="role" id="role" class="form-select border-0 bg-light rounded-end-pill py-2" required>
                                <option value="">Seleccione un rol asignado...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Permisos adicionales opcionales --}}
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                        <div>
                            <label class="form-label fw-bold text-dark mb-0" style="font-size: 13.5px;">
                                <i class="fas fa-key me-1" style="color: var(--primary);"></i>Permisos Directos Adicionales 
                                <span class="fw-normal text-muted">(Opcional)</span>
                            </label>
                            <p class="text-muted mb-0" style="font-size: 12.5px;">
                                Los permisos estándar se heredan automáticamente del rol seleccionado.
                            </p>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mt-2 mt-sm-0" id="togglePerms" data-expanded="false">
                            <i class="fas fa-chevron-down me-1"></i><span>Mostrar permisos</span>
                        </button>
                    </div>

                    <div id="directPermissionsSection" style="display: none;" class="mt-3">
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
                                    <div class="col-12 col-md-6">
                                        <div class="card h-100 border rounded-3 shadow-xs">
                                            <div class="card-header py-2 px-3 bg-white border-bottom d-flex align-items-center">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 28px; height: 28px; background: {{ $meta['bg'] }}; color: {{ $meta['color'] }}; font-size: 12px;">
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
                                                                   {{ (collect(old('direct_permissions'))->contains($permission->id)) ? 'checked' : '' }}>
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

                <hr class="my-4" style="border-color: #f1f5f9;">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                        <i class="fas fa-save me-1"></i> Crear Usuario
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
            if (spanText) {
                spanText.textContent = isExpanded ? 'Mostrar permisos' : 'Ocultar permisos';
            }
            const icon = this.querySelector('i');
            if (icon) {
                icon.className = isExpanded ? 'fas fa-chevron-down me-1' : 'fas fa-chevron-up me-1';
            }
        });
    }
});
</script>
@endsection
