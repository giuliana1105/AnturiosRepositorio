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
                    @foreach ($tipoNotas as $nota)
                        <tr>
                            <td>{{ $nota->codigo }}</td>
                            <td>{{ $nota->tiponota }}</td>
                            <td>{{ optional($nota->responsableEmpleado)->nombreemp ?? 'N/A' }} {{ optional($nota->responsableEmpleado)->apellidoemp ?? '' }}</td>

                            {{-- 🔹 Mostrar productos asociados a la nota --}}
                            <td>
                                <ul class="list-unstyled">
                                    @foreach ($nota->detalles as $detalle)
                                        @php
                                            // Carga el producto si no está cargado en la relación
                                            $producto = $detalle->producto ?? App\Models\Producto::where('codigo', $detalle->codigoproducto)->first();
                                        @endphp
                                        <li>{{ $producto->nombre ?? 'Producto '.$detalle->codigoproducto }}</li>
                                    @endforeach
                                </ul>
                            </td>

                            {{-- 🔹 Mostrar cantidad de productos --}}
                            <td>
                                <ul class="list-unstyled">
                                    @foreach ($nota->detalles as $detalle)
                                        <li>{{ $detalle->cantidad }}</li>
                                    @endforeach
                                </ul>
                            </td>

                            {{-- 🔹 Mostrar tipo de empaque --}}
                            <td>
                                <ul class="list-unstyled">
                                    @foreach ($nota->detalles as $detalle)
                                        @php
                                            // Carga el producto si no está cargado en la relación
                                            $producto = $detalle->producto ?? App\Models\Producto::where('codigo', $detalle->codigoproducto)->first();
                                        @endphp
                                        <li>{{ $producto->tipoempaque ?? 'Sin empaque' }}</li>
                                    @endforeach
                                </ul>
                            </td>

                            <td>{{ optional($nota->bodega)->nombrebodega ?? 'N/A' }}</td>
                            <td>{{ $nota->fechanota }}</td>

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
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-3 d-flex justify-content-center">
            {{ $tipoNotas->links() }}
        </div>
    </div>
@endsection