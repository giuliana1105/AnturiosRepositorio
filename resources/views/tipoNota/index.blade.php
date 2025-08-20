@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="text-center mb-4">Lista de Notas</h3>
        <a href="{{ route('tipoNota.create') }}" class="btn mb-3" style="background-color: #88022D; color: white;">Crear Nota</a>

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

                            {{-- 🔹 Mostrar productos asociados a la nota --}}
                            <td>
                                @if($nota->detalles && $nota->detalles->count() > 0)
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($nota->detalles as $detalle)
                                            <li>
                                                @if($detalle->producto)
                                                    {{ $detalle->producto->nombre }}
                                                @else
                                                    {{ $detalle->codigoproducto }}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">Sin productos</span>
                                @endif
                            </td>

                            {{-- 🔹 Mostrar cantidad de productos --}}
                            <td>
                                @if($nota->detalles && $nota->detalles->count() > 0)
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($nota->detalles as $detalle)
                                            <li>{{ $detalle->cantidad ?? 0 }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- 🔹 Mostrar tipo de empaque --}}
                            <td>
                                @if($nota->detalles && $nota->detalles->count() > 0)
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($nota->detalles as $detalle)
                                            <li>
                                                @if($detalle->producto && $detalle->producto->tipoempaque)
                                                    {{ $detalle->producto->tipoempaque }}
                                                @else
                                                    <span class="text-muted">Sin empaque</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>{{ optional($nota->bodega)->nombrebodega ?? 'N/A' }}</td>
                            <td>{{ $nota->fechanota ? \Carbon\Carbon::parse($nota->fechanota)->format('d/m/Y H:i') : 'N/A' }}</td>

                            {{-- 🔹 Estado de la nota --}}
                            <td>
                                @if(optional($nota->transaccion)->estado)
                                    <span class="badge bg-info">{{ $nota->transaccion->estado }}</span>
                                @else
                                    <span class="badge bg-secondary">Sin Confirmar</span>
                                @endif
                            </td>

                            {{-- 🔹 Acciones --}}
                            <td>
                                @if(!$nota->transaccion)
                                    <form action="{{ route('tipoNota.confirmar', $nota->codigo) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm mb-2">Confirmar</button>
                                    </form>
                                @endif

                                {{-- Botón de Editar --}}
                                <a href="{{ route('tipoNota.edit', $nota->codigo) }}" class="btn btn-warning btn-sm mb-2">Editar</a>

                                {{-- Botón de Eliminar --}}
                                @can('eliminar TipoNota')
                                    <form action="{{ route('tipoNota.destroy', $nota->codigo) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta nota?')">Eliminar</button>
                                    </form>
                                @endcan
                            </td>

                            {{-- 🔹 Botón de PDF --}}
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