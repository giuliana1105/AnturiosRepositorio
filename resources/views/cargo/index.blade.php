@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-center mb-4">Lista de Cargos</h2>

        <!-- Mensajes de éxito y error -->
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

        <!-- Mostrar errores de validación -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario para crear un nuevo cargo -->
        <div class="card mb-4">
            <div class="card-header  text-white" style="background-color: #88022D">
                <h3 class="card-title mb-0" >Nuevo Cargo</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('cargo.store') }}" role="form">
                    @csrf
                    <div class="row">
                        <!-- Campo Nombre del Cargo (obligatorio) -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="nombrecargo" class="form-label">Nombre del Cargo</label>
                            <input type="text" name="nombrecargo" id="nombrecargo"
                                class="form-control" placeholder="Nombre del cargo"
                                value="{{ old('nombrecargo') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <button type="submit" class="btn btn-success w-100">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de cargos -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nombre del Cargo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cargos as $cargo)
                        <tr>
                            <td>{{ $cargo->nombrecargo }}</td>
                            <td>
                                <!-- Botón Editar -->
                                <a href="{{ route('cargo.edit', $cargo->codigocargo) }}" class="btn btn-sm btn-primary mb-2 mb-md-0">Editar</a>
                                <!-- Botón Eliminar -->
                                <form action="{{ route('cargo.destroy', $cargo->codigocargo) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Está seguro de eliminar este cargo?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No hay cargos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-3 d-flex justify-content-center">
            {{ $cargos->links() }}
        </div>
    </div>
@endsection