@extends('layouts.app')

@section('title', 'Inicio de Sesión')

@php
    $hideNavbar = true; // Ocultar el menú de navegación
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow mt-5" style="border-color: #F9DFAD;">
            <div class="card-header text-center" style="background-color: #88022D; color: white;">
                <h3>Inicio de Sesión</h3>
            </div>
            <div class="card-body" style="background-color: #FFF5F7;">
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
            <label for="email" class="form-label" style="color: #88022D;">Correo Electrónico</label>
            <input type="email" name="email" id="email" class="form-control" required placeholder="Ingresa tu correo electrónico" style="border-color: #ffffff;">
        </div>

                    <div class="mb-3">
                        <label for="password" class="form-label" style="color: #88022D;">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" required placeholder="Ingresa tu contraseña" style="border-color: #ffffff;">
                    </div>
                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                        <label class="form-check-label" for="remember_me">Recordar sesión</label>
                                    </div>
                    <button type="submit" class="btn w-100" style="background-color: #88022D; color: white;">Iniciar Sesión</button>
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
