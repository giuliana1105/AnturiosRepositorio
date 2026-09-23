@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Crear Usuario</h3>
            <p class="page-subtitle">Registro de un nuevo usuario en el sistema</p>
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
            <h6 class="fw-semibold mb-4" style="color: var(--foreground);">
                <i class="fas fa-user-plus me-2" style="color: var(--primary);"></i>Datos del Usuario
            </h6>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nombre Completo</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Ej: Juan Pérez" required value="{{ old('name') }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="usuario@empresa.com" required value="{{ old('email') }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Mínimo 8 caracteres" required>
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label">Rol del Usuario</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="">Seleccione un rol</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Permisos adicionales --}}
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label fw-semibold mb-0">
                            <i class="fas fa-key me-1" style="color: var(--primary);"></i>Permisos Adicionales <span class="fw-normal text-muted">(Opcional)</span>
                        </label>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="togglePerms" data-expanded="false">
                            <i class="fas fa-chevron-down me-1"></i>Mostrar permisos
                        </button>
                    </div>
                    <p class="text-muted mb-3" style="font-size: 13px;">
                        <i class="fas fa-info-circle me-1"></i>Los permisos del rol se asignan automáticamente. Use esta sección solo para agregar permisos adicionales que no estén incluidos en el rol seleccionado.
                    </p>

                    <div id="directPermissionsSection" style="display: none;">
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
                                                   {{ (collect(old('direct_permissions'))->contains($permission->id)) ? 'checked' : '' }}>
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
                        <i class="fas fa-save"></i> Crear Usuario
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
