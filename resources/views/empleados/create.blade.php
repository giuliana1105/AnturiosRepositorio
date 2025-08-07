@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="text-center mb-4">Crear Empleado</h3>

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
    <ul class="nav nav-tabs mb-3" id="empleadoTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab">Ingreso Manual</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="excel-tab" data-bs-toggle="tab" data-bs-target="#excel" type="button" role="tab">Importar desde Excel</button>
        </li>
    </ul>

    <div class="tab-content" id="empleadoTabContent">
        <!-- Ingreso Manual -->
        <div class="tab-pane fade show active" id="manual" role="tabpanel">
            <form id="manualForm" action="{{ route('empleados.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label for="nro_identificacion" class="form-label">Cédula</label>
                    <input type="text" name="nro_identificacion" id="nro_identificacion" class="form-control" required value="{{ old('nro_identificacion') }}">
                </div>
                <div class="col-md-6">
                    <label for="nombreemp" class="form-label">Nombre</label>
                    <input type="text" name="nombreemp" id="nombreemp" class="form-control" required value="{{ old('nombreemp') }}">
                </div>
                <div class="col-md-6">
                    <label for="apellidoemp" class="form-label">Apellido</label>
                    <input type="text" name="apellidoemp" id="apellidoemp" class="form-control" required value="{{ old('apellidoemp') }}">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
                </div>
                <div class="col-md-6">
                    <label for="nro_telefono" class="form-label">Celular</label>
                    <input type="text" name="nro_telefono" id="nro_telefono" class="form-control" required value="{{ old('nro_telefono') }}">
                </div>
                <div class="col-md-6">
                    <label for="direccionemp" class="form-label">Dirección</label>
                    <input type="text" name="direccionemp" id="direccionemp" class="form-control" required value="{{ old('direccionemp') }}">
                </div>
                <div class="col-md-6">
                    <label for="idbodega" class="form-label">Bodega</label>
                    <select name="idbodega" id="idbodega" class="form-control" required>
                        <option value="">Seleccione una bodega</option>
                        @foreach ($bodegas as $bodega)
                            <option value="{{ $bodega->idbodega }}" {{ old('idbodega') == $bodega->idbodega ? 'selected' : '' }}>
                                {{ $bodega->nombrebodega }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="tipo_identificacion" class="form-label">Tipo Identificación</label>
                    <select name="tipo_identificacion" id="tipo_identificacion" class="form-control" required>
                        <option value="">Seleccione tipo</option>
                        <option value="Cédula" {{ old('tipo_identificacion') == 'Cédula' ? 'selected' : '' }}>Cédula</option>
                        <option value="RUC" {{ old('tipo_identificacion') == 'RUC' ? 'selected' : '' }}>RUC</option>
                        <option value="Pasaporte" {{ old('tipo_identificacion') == 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="codigocargo" class="form-label">Cargo</label>
                    <select name="codigocargo" id="codigocargo" class="form-control" required>
                        <option value="">Seleccione un cargo</option>
                        @foreach ($cargos as $cargo)
                            <option value="{{ $cargo->codigocargo }}" {{ old('codigocargo') == $cargo->codigocargo ? 'selected' : '' }}>
                                {{ $cargo->nombrecargo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary w-50">Guardar</button>
                </div>
            </form>
        </div>
        <!-- Importar Excel -->
        <div class="tab-pane fade" id="excel" role="tabpanel">
            <form id="excelForm" action="{{ route('empleados.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="excel_file" class="form-label">Importar empleados por Excel</label>
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
