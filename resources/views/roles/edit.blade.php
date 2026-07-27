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

    <div class="card mb-4" style="max-width: 800px; margin: 0 auto; border-top: 3px solid var(--primary);">
        <div class="card-body p-4">
            <!-- Header con info resumida -->
            <div class="d-flex align-items-center mb-4 p-3 rounded" style="background: var(--accent-subtle);">
                <div style="width: 48px; height: 48px; border-radius: var(--radius-sm); background: var(--primary); display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                    <i class="fas fa-user-shield" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color: var(--foreground);">{{ $role->name }}</h5>
                    <div class="font-mono mt-1" style="font-size: 13px; color: var(--muted);">ID: {{ $role->id }}</div>
                </div>
            </div>

            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <div class="col-12">
                        <label for="name" class="form-label">Nombre del Rol</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $role->name) }}" required>
                    </div>
                    
                    <div class="col-12">
                        <label for="permissions" class="form-label">Permisos Asignados</label>
                        <select name="permissions[]" id="permissions" class="form-select" multiple required size="8" style="border-radius: var(--radius-md);">
                            @foreach($permissions as $permission)
                                <option value="{{ $permission->id }}" 
                                    {{ in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $permission->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i>Presione Ctrl (o Cmd) para selección múltiple.</div>
                    </div>
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
@endsection
