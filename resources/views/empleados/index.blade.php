@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Empleados</h3>
            <p class="page-subtitle">Gestión del personal de la empresa</p>
        </div>
        @can('crear empleado')
        <a href="{{ route('empleados.create') }}" class="btn btn-info">
            <i class="fas fa-plus"></i> Añadir Empleado
        </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Tipo ID</th>
                            <th>Nro. ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Celular</th>
                            <th>Cargo</th>
                            <th>Bodega</th>
                            @if(auth()->user()->can('editar empleado') || auth()->user()->can('eliminar empleado'))
                            <th style="width: 120px;">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($empleados as $empleado)
                            <tr>
                                <td>{{ $empleado->tipo_identificacion === 'Cedula' ? 'Cédula' : $empleado->tipo_identificacion }}</td>
                                <td><span class="font-mono fw-medium">{{ $empleado->nro_identificacion }}</span></td>
                                <td class="fw-medium">{{ $empleado->nombreemp }}</td>
                                <td>{{ $empleado->apellidoemp }}</td>
                                <td class="text-nowrap">
                                    <div class="d-flex align-items-center gap-2">
                                        <span id="email-{{ $empleado->nro_identificacion }}" style="color: var(--secondary);">{{ $empleado->email }}</span>
                                        <button class="btn btn-sm btn-outline-secondary btn-icon flex-shrink-0"
                                                onclick="copyToClipboard('{{ $empleado->nro_identificacion }}')" 
                                                title="Copiar email" style="width: 28px; height: 28px; min-width: 28px;">
                                            <i id="icon-{{ $empleado->nro_identificacion }}" class="fas fa-copy" style="font-size: 11px;"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="text-nowrap" style="color: var(--muted);">{{ $empleado->nro_telefono ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $empleado->cargoNombre() }}</span>
                                </td>
                                <td style="color: var(--secondary);">{{ $empleado->bodega->nombrebodega ?? '—' }}</td>
                                @if(auth()->user()->can('editar empleado') || auth()->user()->can('eliminar empleado'))
                                <td>
                                    <div class="d-flex gap-1">
                                        @can('editar empleado')
                                        <a href="{{ route('empleados.edit', $empleado->nro_identificacion) }}" 
                                           class="btn btn-warning btn-sm btn-icon" title="Editar">
                                            <i class="fas fa-edit" style="font-size: 12px;"></i>
                                        </a>
                                        @endcan
                                        @can('eliminar empleado')
                                        <form action="{{ route('empleados.destroy', $empleado->nro_identificacion) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm btn-icon btn-delete" title="Eliminar">
                                                <i class="fas fa-trash" style="font-size: 12px;"></i>
                                            </button>
                                        </form>
                                        @endcan
                                        @can('editar empleado')
                                        <form action="{{ route('empleados.reset_password', $empleado->nro_identificacion) }}" method="POST" class="d-inline form-reset">
                                            @csrf
                                            <button type="button" class="btn btn-secondary btn-sm btn-icon btn-reset" title="Restablecer contraseña">
                                                <i class="fas fa-key" style="font-size: 12px;"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4" style="color: var(--muted);">
                                    <i class="fas fa-users d-block mb-2" style="font-size: 24px; opacity: 0.4;"></i>
                                    No hay empleados registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3 d-flex justify-content-center">
                {{ $empleados->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function copyToClipboard(empleadoId) {
    var emailText = document.getElementById('email-' + empleadoId).innerText;
    navigator.clipboard.writeText(emailText).then(function() {
        var icon = document.getElementById('icon-' + empleadoId);
        icon.classList.remove('fa-copy');
        icon.classList.add('fa-check');
        setTimeout(function() {
            icon.classList.remove('fa-check');
            icon.classList.add('fa-copy');
        }, 2000);
    }).catch(function(err) {
        console.error('Error al copiar: ', err);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const btnsReset = document.querySelectorAll('.btn-reset');
    btnsReset.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            let form = this.closest('form');
            Swal.fire({
                title: '¿Restablecer contraseña?',
                text: "La contraseña volverá a su valor por defecto.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc94ca',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, restablecer',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size: 12px;"></i>';
                    form.submit();
                }
            });
        });
    });

    const btnsDelete = document.querySelectorAll('.btn-delete');
    btnsDelete.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            let form = this.closest('form');
            Swal.fire({
                title: '¿Eliminar empleado?',
                text: "Esta acción no se puede deshacer.",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size: 12px;"></i>';
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection