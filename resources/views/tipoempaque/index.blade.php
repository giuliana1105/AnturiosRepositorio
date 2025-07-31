@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center">Tipos de Empaques</h2>

    <!-- Alertas de éxito o error -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Formulario para crear un nuevo tipo de empaque -->
    <div class="mb-4">
        <h4>Nuevo Tipo de Empaque</h4>
        <form action="{{ route('tipoempaque.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nombretipoempaque">Nombre del Tipo de Empaque</label>
                        <input type="text" name="nombretipoempaque" id="nombretipoempaque" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="codigotipoempaque">Código del Tipo de Empaque</label>
                        <input type="text" name="codigotipoempaque" id="codigotipoempaque" class="form-control" required>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3" style="background-color: #88022D">Guardar</button>
        </form>
    </div>

    <!-- Tabla de tipos de empaque -->
    <div>
        <h4>Lista de Tipos de Empaque</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Código Empaque</th>
                    <th>Nombre Empaque</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tipoEmpaques as $tipoEmpaque)
                <tr>
                    <td>{{ $tipoEmpaque->codigotipoempaque }}</td>
                    <td>{{ $tipoEmpaque->nombretipoempaque }}</td>
                    <td>
                        <a href="{{ route('tipoempaque.edit', $tipoEmpaque->codigotipoempaque) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form action="{{ route('tipoempaque.destroy', $tipoEmpaque->codigotipoempaque) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este tipo de empaque?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">No hay tipos de empaque registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="mt-3">
            {{ $tipoEmpaques->links() }}
        </div>
    </div>
</div>
@endsection
