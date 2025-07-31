@extends('layouts.app')

@section('content')

    <div class="container">
        <h2 class="text-center">Editar Rol</h2>

        <!-- Si hay errores de validación, mostrar alerta -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario para editar el rol -->
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nombre del rol</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $role->name) }}" placeholder="Nombre del rol">
            </div>

            <div class="mb-3">
                <label for="permissions" class="form-label">Permisos</label>
                <select name="permissions[]" id="permissions" class="form-control" multiple>
                    @foreach($permissions as $permission)
                        <option value="{{ $permission->id }}" 
                            {{ in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'selected' : '' }}>
                            {{ $permission->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="background-color: #88022D">Guardar cambios</button>
        </form>

    </div>

@endsection
