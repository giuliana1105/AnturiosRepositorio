@extends('layouts.app')

@section('content')

    <div class="container">
        <h2 class="text-center">Crear Rol</h2>

        <!-- Formulario de creación de rol -->
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" name="name" class="form-control" placeholder="Nombre del rol" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <select name="permissions[]" class="form-control" multiple required>
                        <option value="">Seleccionar permisos</option>
                        @foreach($permissions as $permission)
                            <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary" style="background-color: #88022D">Crear Rol</button>
            </div>
        </form>
    </div>

@endsection
