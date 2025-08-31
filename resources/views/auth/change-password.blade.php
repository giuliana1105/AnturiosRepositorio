@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow mt-5">
            <div class="card-header text-center" style="background-color: #88022D; color: white;">
                <h3>Cambiar Contraseña</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('password.change') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn w-100" style="background-color: #88022D; color: white;">Cambiar Contraseña</button>
                </form>
                @if ($errors->any())
                    <div class="mt-3">
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection