@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de Página -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--foreground); letter-spacing: -0.5px;">
                <i class="fas fa-user-edit me-2" style="color: var(--primary);"></i>Editar Cliente
            </h3>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Actualizar datos personales o comerciales de {{ $cliente->nombre_completo }}.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Volver a Clientes
            </a>
            <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-outline-info rounded-pill px-3 shadow-sm">
                <i class="fas fa-eye me-1"></i> Ver Ficha
            </a>
        </div>
    </div>

    <!-- Errores de Validación -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert" style="max-width: 960px; margin: 0 auto 1.5rem auto;">
            <div class="d-flex align-items-center mb-1">
                <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                <strong class="fs-6">Por favor revisa los siguientes campos:</strong>
            </div>
            <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert" style="max-width: 960px; margin: 0 auto 1.5rem auto;">
            <i class="fas fa-exclamation-circle me-2 fs-5"></i><strong>Error:</strong> {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Formulario Principal -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="max-width: 960px; margin: 0 auto;">
        <div class="card-body p-4 p-md-5">
            {{-- Header de perfil de cliente --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between p-3 rounded-4 mb-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0;">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-xs" 
                         style="width: 52px; height: 52px; background: rgba(0, 151, 167, 0.15); color: var(--primary); font-weight: 700; font-size: 18px;">
                        {{ strtoupper(substr($cliente->nombres, 0, 1) . substr($cliente->apellidos, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold text-dark">{{ $cliente->nombre_completo }}</h5>
                        <div class="text-muted d-flex align-items-center gap-2" style="font-size: 13px;">
                            <span class="font-monospace"><i class="fas fa-id-card me-1"></i>{{ $cliente->cedula }}</span>
                            <span>•</span>
                            <span>ID Cliente #{{ $cliente->id }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-2 mt-md-0">
                    @if($cliente->nombre_empresa)
                        <span class="badge rounded-pill px-3 py-1.5 fw-semibold" style="background: rgba(14, 165, 233, 0.1); color: #0284c7; border: 1px solid rgba(14, 165, 233, 0.25); font-size: 12px;">
                            <i class="fas fa-building me-1"></i> {{ $cliente->nombre_empresa }}
                        </span>
                    @else
                        <span class="badge rounded-pill px-3 py-1.5 fw-semibold" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-size: 12px;">
                            <i class="fas fa-user me-1"></i> Persona Natural
                        </span>
                    @endif
                </div>
            </div>

            <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="nombres" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Nombres <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3">
                                <i class="fas fa-user text-muted"></i>
                            </span>
                            <input type="text" name="nombres" id="nombres" 
                                   class="form-control border-0 bg-light rounded-end-pill py-2" 
                                   required value="{{ old('nombres', $cliente->nombres) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="apellidos" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Apellidos <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3">
                                <i class="fas fa-user text-muted"></i>
                            </span>
                            <input type="text" name="apellidos" id="apellidos" 
                                   class="form-control border-0 bg-light rounded-end-pill py-2" 
                                   required value="{{ old('apellidos', $cliente->apellidos) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="cedula" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Cédula o RUC <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3">
                                <i class="fas fa-id-card text-muted"></i>
                            </span>
                            <input type="text" name="cedula" id="cedula" 
                                   class="form-control border-0 bg-light rounded-end-pill py-2 font-monospace" 
                                   required maxlength="13" value="{{ old('cedula', $cliente->cedula) }}">
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: 11.5px;">10 dígitos para Cédula o 13 para RUC.</small>
                    </div>

                    <div class="col-md-6">
                        <label for="celular" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Número Celular / Teléfono <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3">
                                <i class="fas fa-phone text-muted"></i>
                            </span>
                            <input type="text" name="celular" id="celular" 
                                   class="form-control border-0 bg-light rounded-end-pill py-2" 
                                   required maxlength="15" value="{{ old('celular', $cliente->celular) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="correo" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Correo Electrónico <span class="text-muted fw-normal">(Opcional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3">
                                <i class="fas fa-envelope text-muted"></i>
                            </span>
                            <input type="email" name="correo" id="correo" 
                                   class="form-control border-0 bg-light rounded-end-pill py-2" 
                                   value="{{ old('correo', $cliente->correo) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="nombre_empresa" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Nombre de la Empresa / Razón Social <span class="text-muted fw-normal">(Opcional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3">
                                <i class="fas fa-building text-muted"></i>
                            </span>
                            <input type="text" name="nombre_empresa" id="nombre_empresa" 
                                   class="form-control border-0 bg-light rounded-end-pill py-2" 
                                   value="{{ old('nombre_empresa', $cliente->nombre_empresa) }}" 
                                   placeholder="Dejar vacío si es persona natural">
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="direccion" class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                            Dirección de Entrega / Domicilio <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3">
                                <i class="fas fa-map-marker-alt text-muted"></i>
                            </span>
                            <input type="text" name="direccion" id="direccion" 
                                   class="form-control border-0 bg-light rounded-end-pill py-2" 
                                   required value="{{ old('direccion', $cliente->direccion) }}">
                        </div>
                    </div>
                </div>

                <hr class="my-4" style="border-color: #f1f5f9;">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                        <i class="fas fa-save me-1"></i> Actualizar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
