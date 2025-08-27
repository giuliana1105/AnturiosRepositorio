
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Agregar abono a la Venta #{{ $venta->id }}</h3>
    <a href="{{ route('venta.index') }}" class="btn btn-secondary mb-3">Volver</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <tr><th>Fecha</th><td>{{ \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') }}</td></tr>
        <tr><th>Cliente</th><td>{{ $venta->cliente }}</td></tr>
        <tr><th>Ciudad</th><td>{{ $venta->ciudad }}</td></tr>
        <tr><th>Bodega</th><td>{{ $venta->bodega->nombrebodega ?? $venta->bodega_id }}</td></tr>
        <tr><th>Total venta</th><td>{{ $venta->total_venta }}</td></tr>
        <tr><th>Forma de pago</th><td>{{ $venta->tipo_pago }}</td></tr>
    </table>

    <h4>Abonos anteriores</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Abono</th>
                <th>Fecha</th>
                <th>Tipo de pago</th>
            </tr>
        </thead>
        <tbody>
            @forelse($abonos as $abono)
                <tr>
                    <td><input type="number" class="form-control" value="{{ $abono->abono }}" readonly></td>
                    <td><input type="text" class="form-control" value="{{ $abono->fecha }}" readonly></td>
                    <td><input type="text" class="form-control" value="{{ $abono->tipo_pago }}" readonly></td>
                </tr>
            @empty
                <tr><td colspan="3">No hay abonos registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div>
        <strong>Saldo actual:</strong> {{ $saldo }}
    </div>

    <h4 class="mt-4">Agregar nuevo abono</h4>
    <form method="POST" action="{{ route('venta.abono.store', $venta->id) }}">
        @csrf
        <div class="row mb-3">
            <div class="col-md-3">
                <label>Abono</label>
                <input type="number" name="abono" class="form-control" min="0.01" step="0.01" required>
            </div>
            <div class="col-md-3">
                <label>Fecha</label>
                <input type="date" name="fecha_abono" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-3">
                <label>Tipo de pago</label>
                <select name="tipo_pago_abono" class="form-control" required>
                    <option value="Cheque">Cheque</option>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Transferencia">Transferencia</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-success">Agregar abono</button>
            </div>
        </div>
    </form>
</div>
@endsection
