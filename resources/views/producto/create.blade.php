
@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="text-center mb-4">Crear Producto</h3>

    @if (session('error'))
        <div class="alert alert-danger">
            <strong>Error:</strong> {!! session('error') !!}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            <strong>Éxito:</strong> {{ session('success') }}
        </div>
    @endif

    <!-- Nav tabs -->
    <ul class="nav nav-tabs mb-3" id="productoTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab">Ingreso Manual</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="excel-tab" data-bs-toggle="tab" data-bs-target="#excel" type="button" role="tab">Importar desde Excel</button>
        </li>
    </ul>

    <div class="tab-content" id="productoTabContent">
        <!-- Ingreso Manual -->
        <div class="tab-pane fade show active" id="manual" role="tabpanel">
            <form id="manualForm" action="{{ route('productos.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                               required value="{{ old('codigo') }}">
                        @error('codigo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               required value="{{ old('nombre') }}">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" class="form-control @error('cantidad') is-invalid @enderror"
                               required value="{{ old('cantidad') }}">
                        @error('cantidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="tipoempaque" class="form-label">Tipo de Empaque</label>
                        <select name="tipoempaque" class="form-control @error('tipoempaque') is-invalid @enderror" required>
                            <option value="">Seleccione un tipo de empaque</option>
                            @foreach ($tipoempaques as $tipo)
                                <option value="{{ $tipo }}" {{ old('tipoempaque') == $tipo ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipoempaque')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <button type="submit" class="btn btn-primary w-100">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Importar Excel -->
        <div class="tab-pane fade" id="excel" role="tabpanel">
            <form id="excelForm" action="{{ route('productos.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="excel_file" class="form-label">Importar productos por Excel</label>
                    <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Importar Excel</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const manualForm = document.getElementById('manualForm');
        const excelForm = document.getElementById('excelForm');
        const manualTab = document.getElementById('manual-tab');
        const excelTab = document.getElementById('excel-tab');

        manualForm.addEventListener('submit', function(e) {
            if (!manualTab.classList.contains('active')) {
                e.preventDefault();
            }
        });

        excelForm.addEventListener('submit', function(e) {
            if (!excelTab.classList.contains('active')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection