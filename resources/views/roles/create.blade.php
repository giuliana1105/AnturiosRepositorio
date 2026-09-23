@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Crear Rol</h3>
            <p class="page-subtitle">Definición de nuevo perfil y sus permisos</p>
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
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">
                        <i class="fas fa-user-shield me-1" style="color: var(--primary);"></i>Nombre del Rol
                    </label>
                    <input type="text" name="name" id="name" class="form-control" 
                           placeholder="Ej: Supervisor, Contador, Bodeguero" required value="{{ old('name') }}">
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
                                               {{ (collect(old('permissions'))->contains($permission->id)) ? 'checked' : '' }}>
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
                        <i class="fas fa-save"></i> Guardar Rol
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
    // Seleccionar todo
    document.getElementById('selectAll').addEventListener('click', function() {
        document.querySelectorAll('.perm-check').forEach(cb => cb.checked = true);
    });

    // Deseleccionar todo
    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('.perm-check').forEach(cb => cb.checked = false);
    });

    // Marcar/desmarcar por grupo
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
