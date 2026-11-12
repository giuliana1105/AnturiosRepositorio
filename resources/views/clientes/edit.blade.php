@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Editar Cliente</h3>
            <p class="page-subtitle">Modificar datos del cliente {{ $cliente->nombre_completo }}</p>
        </div>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="max-width: 900px; margin: 0 auto 1.5rem auto;">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Por favor verifique los siguientes campos:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="max-width: 900px; margin: 0 auto 1.5rem auto;">
            <i class="fas fa-exclamation-circle me-2"></i><strong>Error:</strong> {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card" style="max-width: 900px; margin: 0 auto; border-top: 3px solid var(--primary);">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4 p-3 rounded-3" style="background: rgba(0, 151, 167, 0.08);">
                <i class="fas fa-user-edit fs-4 text-primary me-3"></i>
                <div>
                    <h6 class="mb-0 fw-bold">{{ $cliente->nombre_completo }}</h6>
                    <small class="text-muted">Cédula/RUC: {{ $cliente->cedula }} | ID: #{{ $cliente->id }}</small>
                </div>
            </div>

            <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="nombres" class="form-label fw-bold">
                            Nombres <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" name="nombres" id="nombres" class="form-control" required value="{{ old('nombres', $cliente->nombres) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="apellidos" class="form-label fw-bold">
                            Apellidos <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" name="apellidos" id="apellidos" class="form-control" required value="{{ old('apellidos', $cliente->apellidos) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="cedula" class="form-label fw-bold">
                            Cédula o RUC <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-id-card text-muted"></i></span>
                            <input type="text" name="cedula" id="cedula" class="form-control" required maxlength="13" value="{{ old('cedula', $cliente->cedula) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="celular" class="form-label fw-bold">
                            Número Celular / Teléfono <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                            <input type="text" name="celular" id="celular" class="form-control" required maxlength="15" value="{{ old('celular', $cliente->celular) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="correo" class="form-label fw-bold">
                            Correo Electrónico <span class="text-muted fw-normal">(Opcional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                            <input type="email" name="correo" id="correo" class="form-control" value="{{ old('correo', $cliente->correo) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="nombre_empresa" class="form-label fw-bold">
                            Nombre de la Empresa <span class="text-muted fw-normal">(Opcional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-building text-muted"></i></span>
                            <input type="text" name="nombre_empresa" id="nombre_empresa" class="form-control" value="{{ old('nombre_empresa', $cliente->nombre_empresa) }}" placeholder="Empresa o negocio">
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="direccion" class="form-label fw-bold">
                            Dirección <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt text-muted"></i></span>
                            <input type="text" name="direccion" id="direccion" class="form-control" required value="{{ old('direccion', $cliente->direccion) }}">
                        </div>
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-light);">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Actualizar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

