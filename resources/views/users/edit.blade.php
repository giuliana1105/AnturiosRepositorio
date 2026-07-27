@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Editar Usuario</h3>
            <p class="page-subtitle">Modificación de accesos y cuenta del sistema</p>
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

    <div class="card mb-4" style="max-width: 700px; margin: 0 auto; border-top: 3px solid var(--primary);">
        <div class="card-body p-4">
            <!-- Header con info resumida -->
            <div class="d-flex align-items-center mb-4 p-3 rounded" style="background: var(--accent-subtle);">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                    <span class="fw-bold text-white fs-5">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color: var(--foreground);">{{ $user->name }}</h5>
                    <div class="font-mono mt-1" style="font-size: 13px; color: var(--muted);">ID: {{ $user->id }}</div>
                </div>
            </div>

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <div class="col-12">
                        <label for="name" class="form-label">Nombre Completo</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    
                    <div class="col-12">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                    
                    <div class="col-12">
                        <label for="password" class="form-label">Nueva Contraseña <span class="text-muted fw-normal">(Opcional)</span></label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Dejar en blanco para mantener la actual">
                    </div>

                    <div class="col-12">
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
@endsection
