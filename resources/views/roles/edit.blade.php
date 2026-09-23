@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Editar Rol</h3>
            <p class="page-subtitle">Modificación de perfil y sus permisos</p>
        </div>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
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
            {{-- Header con info del rol --}}
            <div class="d-flex align-items-center mb-4 p-3 rounded" style="background: var(--accent-subtle);">
                <div style="width: 48px; height: 48px; border-radius: var(--radius-sm); background: var(--primary); display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                    <i class="fas fa-user-shield" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color: var(--foreground);">{{ $role->name }}</h5>
                    <div class="mt-1" style="font-size: 13px; color: var(--muted);">
                        ID: {{ $role->id }}
                        @if($esProtegido)
                            <span class="badge bg-warning text-dark ms-2" style="font-size: 11px;">
                                <i class="fas fa-lock me-1"></i>Rol del Sistema
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">
                        <i class="fas fa-user-shield me-1" style="color: var(--primary);"></i>Nombre del Rol
                    </label>
                    @if($esProtegido)
                        <input type="text" class="form-control" value="{{ $role->name }}" disabled
                               style="background: var(--accent-subtle); cursor: not-allowed;">
                        <div class="form-text"><i class="fas fa-info-circle me-1"></i>El nombre de los roles del sistema no se puede modificar.</div>
                    @else
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $role->name) }}" required>
                    @endif
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label fw-semibold mb-0">
                            <i class="fas fa-key me-1" style="color: var(--primary);"></i>Permisos del Rol
                        </label>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">
                                <i class="fas fa-check-double me-1"></i>Seleccionar Todo
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                <i class="fas fa-times me-1"></i>Deseleccionar Todo
                            </button>
                        </div>
                    </div>

                    @foreach($permissionsGrouped as $grupo => $permisos)
                        @if($permisos->count() > 0)
                        <div class="permission-group mb-3">
                            <div class="permission-group-header d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fas fa-folder-open me-2"></i>{{ $grupo }}
                                    <span class="badge bg-light text-dark ms-1">{{ $permisos->count() }}</span>
                                </span>
                                <button type="button" class="btn btn-sm btn-link toggle-group p-0" style="font-size: 12px;">
                                    Marcar todos
                                </button>
                            </div>
                            <div class="permission-group-body">
                                @foreach($permisos as $permission)
                                    <label class="permission-checkbox">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                               class="form-check-input perm-check"
                                               {{ in_array($permission->id, $rolePermissionIds) ? 'checked' : '' }}>
                                        <span>{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                <hr class="my-4" style="border-color: var(--border-light);">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
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
    document.getElementById('selectAll').addEventListener('click', function() {
        document.querySelectorAll('.perm-check').forEach(cb => cb.checked = true);
    });

    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('.perm-check').forEach(cb => cb.checked = false);
    });

    document.querySelectorAll('.toggle-group').forEach(btn => {
        btn.addEventListener('click', function() {
            const group = this.closest('.permission-group');
            const checkboxes = group.querySelectorAll('.perm-check');
            const allChecked = [...checkboxes].every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            this.textContent = allChecked ? 'Marcar todos' : 'Desmarcar todos';
        });
    });
});
</script>
@endsection
