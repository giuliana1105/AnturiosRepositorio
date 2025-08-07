@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-center">Lista de Empleados</h2>

        <!-- Alertas de éxito o error -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Formulario de búsqueda -->
        <form action="{{ route('empleados.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control"
                           placeholder="Buscar por nombre o Nro. de Identificación" value="{{ request()->search }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100" style="background-color: #88022D">Buscar</button>
                </div>
            </div>
        </form>

        <!-- Botones para crear nuevos registros -->
        <div class="mb-3 text-right">
            <a href="{{ route('empleados.create') }}" class="btn btn-primary" style="background-color: #88022D">Añadir
                Empleado</a>
            <a href="{{ route('cargo.index') }}" class="btn btn-primary" style="background-color: #88022D">Añadir Cargo</a>
        </div>

        <!-- Tabla de empleados -->
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Nro. Identificación</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Tipo de Identificación</th>
                <th>Bodega</th>
                <th>Cargo</th>
                <th>Email</th>
                <th>Número Celular</th> <!-- Agregando columna de Celular -->
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($empleados as $empleado)
                <tr>
                    <td>{{ $empleado->nro_identificacion }}</td>
                    <td>{{ $empleado->nombreemp }}</td>
                    <td>{{ $empleado->apellidoemp }}</td>
                    <td>{{ $empleado->tipo_identificacion }}</td>
                    <td>{{ $empleado->bodega->nombrebodega ?? 'N/A' }}</td>
                    <td>{{ $empleado->cargo->nombrecargo ?? 'N/A' }}</td>

                    <!-- Columna de Email con ícono para copiar -->
                    <td>
                        <span id="email-{{ $empleado->nro_identificacion }}">{{ $empleado->email }}</span>
                        <button class="btn btn-sm btn-secondary"
                                onclick="copyToClipboard('{{ $empleado->nro_identificacion }}')">
                            <i id="icon-{{ $empleado->nro_identificacion }}" class="fas fa-copy"></i> Copiar
                        </button>
                    </td>

                    <!-- Agregando Celular -->
                    <td>{{ $empleado->nro_telefono ?? 'N/A' }}</td> <!-- Muestra el número celular -->

                    <td>
                        <a href="{{ route('empleados.edit', $empleado->nro_identificacion) }}"
                           class="btn btn-sm btn-primary">Editar</a>
                        <form action="{{ route('empleados.destroy', $empleado->nro_identificacion) }}" method="POST"
                              class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Está seguro de eliminar este empleado?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No hay empleados registrados</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        {{ $empleados->links() }}
    </div>

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

