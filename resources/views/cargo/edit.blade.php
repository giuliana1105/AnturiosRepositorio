@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Mostrar los mensajes de éxito -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Mostrar los errores de validación -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Error!</strong> Revise los campos obligatorios.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Mostrar el error específico que venga desde el controlador -->
                @if (session('error'))
                    <div class="alert alert-danger">
                        <ul>
                            <li>{{ session('error') }}</li>
                        </ul>
                    </div>
                @endif

                <div class="card mb-4">
                    <div class="card-header text-white" style="background-color: #88022D">
                        <h3 class="card-title mb-0">Actualizar Cargo</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('cargo.update', $cargo->codigocargo) }}" role="form">
                            @csrf
                            @method('PATCH')
                            <div class="row">
                                <!-- Campo Nombre del Cargo -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="nombrecargo" class="form-label">Nombre del Cargo</label>
                                    <input type="text" name="nombrecargo" id="nombrecargo"
                                        class="form-control"
                                        value="{{ old('nombrecargo', $cargo->nombrecargo) }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <button type="submit" class="btn btn-success w-100">Actualizar</button>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <a href="{{ route('cargo.index') }}" class="btn btn-info w-100">Atrás</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection