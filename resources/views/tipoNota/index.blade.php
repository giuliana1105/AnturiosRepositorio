@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="text-center mb-4">Lista de Notas</h3>

        @php
            $cargo = auth()->user()->cargoNombre();
        @endphp

        @if(!in_array($cargo, ['Jefe de bodega']))
            <a href="{{ route('tipoNota.create') }}" class="btn btn-success mb-3">Crear Nota</a>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Tabla Responsiva -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>CÓDIGO</th>
                        <th>TIPO</th>
                        <th>SOLICITANTE</th>
                        <th>PRODUCTOS</th>
                        <th>CANTIDAD</th>
                        <th>TIPO EMPAQUE</th>
                        <th>BODEGA</th>
                        <th>FECHA</th>
                        <th>ESTADO</th>
                        <th>ACCIONES</th>
                        <th>PDF</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tipoNotas as $nota)
                        <tr>
                            <td>{{ $nota->codigo }}</td>
                            <td>{{ $nota->tiponota }}</td>
                            <td>{{ optional($nota->responsableEmpleado)->nombreemp ?? 'N/A' }} {{ optional($nota->responsableEmpleado)->apellidoemp ?? '' }}</td>
                            {{-- PRODUCTOS, CANTIDAD Y TIPO EMPAQUE --}}
                            <td colspan="3" style="vertical-align:top; padding:0;">
                                @if($nota->detalles && $nota->detalles->count() > 0)
                                    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin:0;">
                                        <tbody>
                                            @foreach ($nota->detalles as $index => $detalle)
                                                <tr style="{{ $index > 0 ? 'border-top: 1px solid #dee2e6;' : '' }}">
                                                    <td style="width: 33.33%; padding: 8px; vertical-align: top; word-wrap: break-word; overflow-wrap: break-word; border:none;">
                                                        {{ $detalle->producto->nombre ?? $detalle->codigoproducto }}
                                                    </td>
                                                    <td style="width: 33.33%; padding: 8px; vertical-align: top; text-align: center; border:none;">
                                                        {{ $detalle->cantidad ?? 0 }}
                                                    </td>
                                                    <td style="width: 33.34%; padding: 8px; vertical-align: top; text-align: center; word-wrap: break-word; border:none;">
                                                        {{ $detalle->producto->tipoempaque ?? 'Sin empaque' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin:0;">
                                        <tbody>
                                            <tr>
                                                <td style="width: 33.33%; padding: 8px; text-align: center; border:none;" class="text-muted">Sin productos</td>
                                                <td style="width: 33.33%; padding: 8px; text-align: center; border:none;" class="text-muted">-</td>
                                                <td style="width: 33.34%; padding: 8px; text-align: center; border:none;" class="text-muted">-</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </td>
                            <td>{{ optional($nota->bodega)->nombrebodega ?? 'N/A' }}</td>
                            <td>{{ $nota->fechanota ? \Carbon\Carbon::parse($nota->fechanota)->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td>
                                @if(optional($nota->transaccion)->estado)
                                    <span class="badge bg-info">{{ $nota->transaccion->estado }}</span>
                                @else
                                    <span class="badge bg-secondary">Sin Confirmar</span>
                                @endif
                            </td>
                            <td>
                                @if(!$nota->transaccion)
                                    <form action="{{ route('tipoNota.confirmar', $nota->codigo) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm mb-2">Confirmar</button>
                                    </form>
                                    <a href="{{ route('tipoNota.edit', $nota->codigo) }}" class="btn btn-warning btn-sm mb-2">Editar</a>
                                    @can('eliminar TipoNota')
                                        <form action="{{ route('tipoNota.destroy', $nota->codigo) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta nota?')">Eliminar</button>
                                        </form>
                                    @endcan
                                @else
                                    <span class="text-muted small">Nota confirmada</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('tipoNota.pdf', $nota->codigo) }}" class="btn btn-danger btn-sm">
                                    Descargar PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">No se encontraron notas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-3 d-flex justify-content-center">
            {{ $tipoNotas->links() }}
        </div>
    </div>
@endsection