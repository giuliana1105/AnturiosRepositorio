@extends('layouts.app')

@section('content')

    <div class="container">
        <h2 class="text-center">Crear Usuario</h2>

        <!-- Formulario de creación de usuario -->
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" name="name" class="form-control" placeholder="Nombre" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <select name="role" class="form-control" required>
                        <option value="">Seleccionar rol</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary" style="background-color: #88022D">Crear Usuario</button>
            </div>
        </form>
    </div>

@endsection
