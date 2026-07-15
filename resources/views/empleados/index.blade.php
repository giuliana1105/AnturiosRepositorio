@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-3">
    <div class="row">
        <!-- Main Content -->
        <div class="col-12 py-3">
            <div class="card shadow-sm border-0 rounded-4 w-100">
                <div class="card-header rounded-top-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">Lista de Empleados</h3>
                        <a href="{{ route('home.master') }}" class="btn btn-light btn-sm rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Alertas de éxito o error -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Formulario de búsqueda -->
                    <!-- <form action="{{ route('empleados.index') }}" method="GET" class="row g-2 mb-3 align-items-end">
                        <div class="col-md-8">
                            <label for="search" class="form-label fw-bold">Buscar Empleado</label>
                            <input type="text" name="search" id="search" class="form-control"
                                   value="{{ request('search') }}" placeholder="Buscar por nombre o Nro. de Identificación">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-info fw-bold rounded-pill px-4">
                                <i class="fas fa-search me-2"></i> Buscar
                            </button>
                            <a href="{{ route('empleados.index') }}" class="btn btn-secondary rounded-pill ms-2 px-4">
                                <i class="fas fa-times me-2"></i> Limpiar
                            </a>
                        </div>
                    </form> -->

                    @php
                        $cargo = auth()->user()->cargoNombre();
                    @endphp

                    @if(!in_array($cargo, ['Jefe de bodega']))
                        <a href="{{ route('empleados.create') }}" class="btn btn-info text-white fw-bold rounded-pill mb-3">
                            <i class="fas fa-plus me-2"></i> Añadir Empleado
                        </a>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-nowrap">Tipo ID</th>
                                    <th class="text-nowrap">Nro. ID</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Email</th>
                                    <th>Celular</th>
                                    <th>Cargo</th>
                                    <th>Bodega</th>
                                    <th class="text-center text-nowrap" style="min-width: 115px; width: 115px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($empleados as $empleado)
                                    <tr>
                                        <td>{{ $empleado->tipo_identificacion === 'Cedula' ? 'Cédula' : $empleado->tipo_identificacion }}</td>
                                        <td class="text-nowrap fw-500">{{ $empleado->nro_identificacion }}</td>
                                        <td>{{ $empleado->nombreemp }}</td>
                                        <td>{{ $empleado->apellidoemp }}</td>
                                        <td style="max-width: 200px;">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span id="email-{{ $empleado->nro_identificacion }}" class="me-2 text-break" style="word-wrap: break-word;">{{ $empleado->email }}</span>
                                                <button class="btn btn-sm btn-outline-info rounded-pill flex-shrink-0"
                                                        onclick="copyToClipboard('{{ $empleado->nro_identificacion }}')" 
                                                        title="Copiar email">
                                                    <i id="icon-{{ $empleado->nro_identificacion }}" class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-nowrap">{{ $empleado->nro_telefono ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1">{{ $empleado->cargoNombre() }}</span>
                                        </td>
                                        <td>{{ $empleado->bodega->nombrebodega ?? 'N/A' }}</td>
                                        <td class="text-center text-nowrap" style="min-width: 115px; width: 115px;">
                                            @if(!in_array($cargo, ['Jefe de bodega']))
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <a href="{{ route('empleados.edit', $empleado->nro_identificacion) }}" 
                                                       class="btn btn-warning btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                                       title="Editar empleado"
                                                       style="width: 32px; height: 32px;">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('empleados.destroy', $empleado->nro_identificacion) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                                                                onclick="return confirm('¿Está seguro de eliminar este empleado?')"
                                                                title="Eliminar empleado"
                                                                style="width: 32px; height: 32px;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('empleados.reset_password', $empleado->nro_identificacion) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                                                                onclick="return confirm('¿Está seguro de restablecer la contraseña de este empleado?')" 
                                                                title="Restablecer contraseña"
                                                                style="width: 32px; height: 32px;">
                                                            <i class="fas fa-key"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-users fa-2x mb-2"></i>
                                                <p class="mb-0">No hay empleados registrados</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="mt-3 d-flex justify-content-center">
                        {{ $empleados->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
body {
    margin: 0;
    padding: 0;
}
.container-fluid {
    padding: 0 !important;
    margin: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}
.card {
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 0;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}
.nav-link {
    padding: 0.5rem 0;
    border-radius: 4px;
    transition: all 0.2s ease;
}
.nav-link:hover {
    background-color: rgba(0, 123, 255, 0.1);
    padding-left: 0.5rem;
}
.nav-link.active {
    background-color: rgba(23, 162, 184, 0.1);
    border-left: 3px solid #17a2b8;
    padding-left: 0.5rem;
}
.min-vh-100 {
    min-height: 100vh;
}
.card-body {
    position: relative;
    overflow: hidden;
}
.card-body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    pointer-events: none;
}
.h3 {
    font-size: 2.5rem;
}
.row.g-0 {
    margin: 0;
}
.row.g-3 {
    margin: 0;
}

.btn-info {
    background-color: #0097a7;
    border-color: #0097a7;
}
.btn-info:hover, .btn-info:focus {
    background-color: #00796b;
    border-color: #00796b;
}
.btn-warning {
    background-color: #ff9800;
    border-color: #ff9800;
    color: #fff;
}
.btn-warning:hover, .btn-warning:focus {
    background-color: #f57c00;
    border-color: #f57c00;
    color: #fff;
}
.btn-danger {
    background-color: #e53935;
    border-color: #e53935;
    color: #fff;
}
.btn-danger:hover, .btn-danger:focus {
    background-color: #b71c1c;
    border-color: #b71c1c;
    color: #fff;
}
.btn-secondary {
    background-color: #607d8b;
    border-color: #607d8b;
    color: #fff;
}
.btn-secondary:hover, .btn-secondary:focus {
    background-color: #455a64;
    border-color: #455a64;
    color: #fff;
}
.btn-outline-info {
    color: #0097a7;
    border-color: #0097a7;
}
.btn-outline-info:hover, .btn-outline-info:focus {
    background-color: #0097a7;
    border-color: #0097a7;
    color: #fff;
}
.rounded-pill {
    border-radius: 50rem !important;
}
.alert {
    border: none;
    border-radius: 0.5rem;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
}
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}
@media (max-width: 768px) {
    .col-md-2 {
        display: none;
    }
    .col-md-10 {
        flex: 0 0 100%;
        max-width: 100%;
        padding: 15px !important;
    }
    .h3 {
        font-size: 2rem;
    }
    .container-fluid {
        padding: 0 !important;
    }
    .btn-group .btn {
        margin-bottom: 0.25rem;
    }
    .table-responsive {
        font-size: 0.875rem;
    }
}
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}
#app {
    min-height: 100vh;
}
</style>

<script>
function copyToClipboard(empleadoId) {
    var emailText = document.getElementById('email-' + empleadoId).innerText;
    navigator.clipboard.writeText(emailText).then(function() {
        var icon = document.getElementById('icon-' + empleadoId);
        icon.classList.remove('fa-copy');
        icon.classList.add('fa-check'); // Cambia el ícono a un 'check' cuando se copie
        setTimeout(function() {
            icon.classList.remove('fa-check');
            icon.classList.add('fa-copy'); // Vuelve a poner el ícono de copiar
        }, 2000); // Vuelve al ícono original después de 2 segundos
    }).catch(function(err) {
        console.error('Error al copiar: ', err);
    });
}
</script>
@endsection