@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Editar Rol y Permisos</h3>
            <p class="page-subtitle">Gestión de accesos del usuario en el sistema</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><strong>Errores de validación:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4" style="max-width: 900px; margin: 0 auto; border-top: 3px solid var(--primary);">
        <div class="card-body p-4">
            {{-- Header con info resumida --}}
            <div class="d-flex align-items-center mb-4 p-3 rounded" style="background: var(--accent-subtle);">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                    <span class="fw-bold text-white fs-5">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color: var(--foreground);">{{ $user->name }}</h5>
                    <div class="font-mono mt-1" style="font-size: 13px; color: var(--muted);">
                        {{ $user->email }}
                        @if($user->roles->isNotEmpty())
                            <span class="badge rounded-pill ms-2" style="background: var(--primary); color: white; font-size: 11px;">
                                {{ $user->roles->first()->name }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Nota informativa --}}
            <div class="alert d-flex align-items-start gap-2 mb-4" role="alert" style="background: var(--info-bg); border: 1px solid var(--info-border); color: var(--info); border-radius: var(--radius-md); font-size: 13px;">
                <i class="fas fa-info-circle mt-1"></i>
                <span>El nombre, correo y contraseña de este usuario se gestionan desde el módulo de <strong>Empleados</strong>. Aquí solo puedes modificar su rol y permisos.</span>
            </div>

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nombre Completo <i class="fas fa-lock ms-1 text-muted" style="font-size: 11px;" title="Gestionado desde Empleados"></i></label>
                        <input type="text" id="name" class="form-control" value="{{ $user->name }}" readonly disabled style="background: var(--bg-base); color: var(--muted);">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo Electrónico <i class="fas fa-lock ms-1 text-muted" style="font-size: 11px;" title="Gestionado desde Empleados"></i></label>
                        <input type="email" id="email" class="form-control" value="{{ $user->email }}" readonly disabled style="background: var(--bg-base); color: var(--muted);">
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label">Rol del Usuario</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="">Seleccione un rol</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->roles->pluck('id')->contains($role->id) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Permisos directos --}}
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label fw-semibold mb-0">
                            <i class="fas fa-key me-1" style="color: var(--primary);"></i>Permisos Directos Adicionales
                        </label>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="togglePerms" data-expanded="{{ count($directPermissions) > 0 ? 'true' : 'false' }}">
                            <i class="fas fa-chevron-{{ count($directPermissions) > 0 ? 'up' : 'down' }} me-1"></i>{{ count($directPermissions) > 0 ? 'Ocultar' : 'Mostrar' }} permisos
                        </button>
                    </div>
                    <p class="text-muted mb-3" style="font-size: 13px;">
                        <i class="fas fa-info-circle me-1"></i>Los permisos marcados aquí se asignan <strong>directamente al usuario</strong>, adicional a los que hereda de su rol.
                    </p>

                    <div id="directPermissionsSection" style="display: {{ count($directPermissions) > 0 ? 'block' : 'none' }};">
                        @foreach($permissionsGrouped as $grupo => $permisos)
                            @if($permisos->count() > 0)
                            <div class="permission-group mb-3">
                                <div class="permission-group-header">
                                    <i class="fas fa-folder-open me-2"></i>{{ $grupo }}
                                    <span class="badge bg-light text-dark ms-1">{{ $permisos->count() }}</span>
                                </div>
                                <div class="permission-group-body">
                                    @foreach($permisos as $permission)
                                        <label class="permission-checkbox">
                                            <input type="checkbox" name="direct_permissions[]" value="{{ $permission->id }}" 
                                                   class="form-check-input"
                                                   {{ in_array($permission->id, $directPermissions) ? 'checked' : '' }}>
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-light);">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.permission-group {
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    overflow: hidden;
}
.permission-group-header {
    background: var(--accent-subtle);
    padding: 10px 16px;
    font-weight: 600;
    font-size: 14px;
    color: var(--foreground);
    border-bottom: 1px solid var(--border-color);
}
.permission-group-body {
    padding: 12px 16px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 8px;
}
.permission-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 10px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: background 0.15s;
    font-size: 13px;
    color: var(--foreground);
}
.permission-checkbox:hover {
    background: var(--accent-subtle);
}
.permission-checkbox .form-check-input {
    margin: 0;
    cursor: pointer;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('togglePerms');
    const permsSection = document.getElementById('directPermissionsSection');
    
    toggleBtn.addEventListener('click', function() {
        const isExpanded = this.dataset.expanded === 'true';
        permsSection.style.display = isExpanded ? 'none' : 'block';
        this.dataset.expanded = (!isExpanded).toString();
        this.innerHTML = isExpanded 
            ? '<i class="fas fa-chevron-down me-1"></i>Mostrar permisos'
            : '<i class="fas fa-chevron-up me-1"></i>Ocultar permisos';
    });
});
</script>
@endsection

