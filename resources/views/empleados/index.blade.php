@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Empleados</h3>
            <p class="page-subtitle">Gestión del personal de la empresa</p>
        </div>
        @php $cargo = auth()->user()->cargoNombre(); @endphp
        @if(!in_array($cargo, ['Jefe de bodega']))
        <a href="{{ route('empleados.create') }}" class="btn btn-info">
            <i class="fas fa-plus"></i> Añadir Empleado
        </a>
        @endif
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
                            @if(!in_array($cargo, ['Jefe de bodega']))
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
                                <td style="max-width: 200px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <span id="email-{{ $empleado->nro_identificacion }}" class="text-break" style="color: var(--secondary);">{{ $empleado->email }}</span>
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
                                @if(!in_array($cargo, ['Jefe de bodega']))
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('empleados.edit', $empleado->nro_identificacion) }}" 
                                           class="btn btn-warning btn-sm btn-icon" title="Editar">
                                            <i class="fas fa-edit" style="font-size: 12px;"></i>
                                        </a>
                                        <form action="{{ route('empleados.destroy', $empleado->nro_identificacion) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm btn-icon" 
                                                    onclick="return confirm('¿Está seguro de eliminar este empleado?')"
                                                    title="Eliminar">
                                                <i class="fas fa-trash" style="font-size: 12px;"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('empleados.reset_password', $empleado->nro_identificacion) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary btn-sm btn-icon" 
                                                    onclick="return confirm('¿Restablecer contraseña de este empleado?')" 
                                                    title="Restablecer contraseña">
                                                <i class="fas fa-key" style="font-size: 12px;"></i>
                                            </button>
                                        </form>
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
</script>
@endsection