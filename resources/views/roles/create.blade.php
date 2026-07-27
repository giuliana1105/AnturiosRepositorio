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

    <div class="card mb-4" style="max-width: 800px; margin: 0 auto; border-top: 3px solid var(--primary);">
        <div class="card-body p-4">
            <h6 class="fw-semibold mb-4" style="color: var(--foreground);">
                <i class="fas fa-user-shield me-2" style="color: var(--primary);"></i>Datos del Rol
            </h6>

            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-12">
                        <label for="name" class="form-label">Nombre del Rol</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Ej: Administrador, Vendedor, etc." required value="{{ old('name') }}">
                    </div>
                    
                    <div class="col-12">
                        <label for="permissions" class="form-label">Permisos Asignados</label>
                        <select name="permissions[]" id="permissions" class="form-select" multiple required size="6" style="border-radius: var(--radius-md);">
                            <option value="" disabled>Presione Ctrl o Cmd para selección múltiple</option>
                            @foreach($permissions as $permission)
                                <option value="{{ $permission->id }}" {{ (collect(old('permissions'))->contains($permission->id)) ? 'selected':'' }}>
                                    {{ $permission->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i>Puede asignar múltiples permisos a la vez.</div>
                    </div>
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
@endsection
