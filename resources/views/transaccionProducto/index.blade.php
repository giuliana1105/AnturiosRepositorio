@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="text-center">Gestión de Transacciones</h3>

        <!-- Filtros y búsqueda -->
        <div class="d-flex flex-column flex-md-row justify-content-between mb-3">
            <div class="btn-group mb-2 mb-md-0">
                <a href="{{ route('transaccionProducto.index', ['estado' => '']) }}" class="btn btn-outline-primary {{ request('estado') == '' ? 'active' : '' }}">
                    Todas
                </a>
                <a href="{{ route('transaccionProducto.index', ['estado' => 'PENDIENTE']) }}" class="btn btn-outline-warning {{ request('estado') == 'PENDIENTE' ? 'active' : '' }}">
                    Pendientes
                </a>
                <a href="{{ route('transaccionProducto.index', ['estado' => 'FINALIZADA']) }}" class="btn btn-outline-success {{ request('estado') == 'FINALIZADA' ? 'active' : '' }}">
                    Finalizadas
                </a>
            </div>

            <form action="{{ route('transaccionProducto.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control w-100 w-md-50" placeholder="Buscar por código de nota" value="{{ request('search') }}">
                <button type="submit" class="btn btn-secondary ms-2">Buscar</button>
            </form>
        </div>

        <!-- Contadores de transacciones -->
        <div class="row text-center">
            <div class="col-md-6">
                <div class="border border-warning p-3 rounded">
                    <h5 class="text-warning">Transacciones Pendientes</h5>
                    <h2>{{ $pendientes ?? 0 }}</h2>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border border-success p-3 rounded">
                    <h5 class="text-success">Transacciones Finalizadas</h5>
                    <h2>{{ $finalizadas ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Tabla de transacciones -->
        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead class="text-center">
                <tr>
                    <th>CÓDIGO NOTA</th>
                    <th>TIPO NOTA</th>
                    <th>ESTADO</th>
                    <th>PRODUCTOS</th>
                    <th>ACCIONES</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($transacciones as $transaccion)
                    <tr>
                        <td>{{ $transaccion->tipoNota->codigo }}</td>
                        <td>{{ $transaccion->tipoNota->tiponota }}</td>
                        <td class="text-center">
                            <span class="badge {{ $transaccion->estado == 'PENDIENTE' ? 'bg-warning' : 'bg-success' }}">
                                {{ $transaccion->estado }}
                            </span>
                        </td>
                        <td>
                            <ul class="list-unstyled">
                                @foreach ($transaccion->tipoNota->detalles as $detalle)
                                    <li>
                                        <strong>Producto:</strong> {{ $detalle->producto->nombre ?? 'N/A' }}<br>
                                        <strong>Cantidad:</strong> {{ $detalle->cantidad }}<br>
                                        <strong>Empaque:</strong> {{ $detalle->producto->tipoempaque ?? 'Sin Empaque' }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-center">
                            @if($transaccion->estado == 'PENDIENTE')
                                <form action="{{ route('transaccionProducto.finalizar', $transaccion->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Finalizar</button>
                                </form>
                            @else
                                <span class="badge bg-secondary">Completado</span>
                            @endif
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center">
            {{ $transacciones->appends(['estado' => request('estado'), 'search' => request('search')])->links() }}
        </div>
    </div>
@endsection
