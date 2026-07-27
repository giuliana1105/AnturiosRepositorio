@extends('layouts.app')

@php
    $cargo = auth()->user()->cargoNombre();
@endphp

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Notas de Pedido</h3>
            <p class="page-subtitle">Solicitudes de envío y devolución</p>
        </div>
        @if(!in_array($cargo, ['Jefe de bodega']))
        <a href="{{ route('tipoNota.create') }}" class="btn btn-info">
            <i class="fas fa-plus"></i> Crear Nota
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <!-- Filters -->
            <form method="GET" action="{{ route('tipoNota.index') }}" class="row g-3 mb-4 align-items-end">
                <div class="col-md-4">
                    <label for="filtro_bodega" class="form-label">Bodega</label>
                    <select name="bodega" id="filtro_bodega" class="form-select">
                        <option value="">Todas las bodegas</option>
                        @foreach($bodegas as $bodega)
                            <option value="{{ $bodega->idbodega }}" {{ request('bodega') == $bodega->idbodega ? 'selected' : '' }}>
                                {{ $bodega->nombrebodega }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filtro_tipo" class="form-label">Tipo</label>
                    <select name="tipo" id="filtro_tipo" class="form-select">
                        <option value="">Todos los tipos</option>
                        <option value="ENVIO" {{ request('tipo') == 'ENVIO' ? 'selected' : '' }}>Envío</option>
                        <option value="DEVOLUCION" {{ request('tipo') == 'DEVOLUCION' ? 'selected' : '' }}>Devolución</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('tipoNota.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table align-middle" style="min-width: 800px;">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Tipo</th>
                            <th>Solicitante</th>
                            <th>Productos</th>
                            <th>Cantidad</th>
                            <th>Tipo Empaque</th>
                            <th>Bodega</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th style="width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tipoNotas as $nota)
                            <tr>
                                <td><span class="font-mono">{{ $nota->codigo }}</span></td>
                                <td>
                                    <span class="badge {{ $nota->tiponota == 'ENVIO' ? 'bg-info' : 'bg-warning' }}">
                                        {{ $nota->tiponota }}
                                    </span>
                                </td>
                                <td>{{ optional($nota->responsableEmpleado)->nombreemp ?? 'N/A' }} {{ optional($nota->responsableEmpleado)->apellidoemp ?? '' }}</td>
                                {{-- PRODUCTOS, CANTIDAD Y TIPO EMPAQUE --}}
                                <td colspan="3" style="vertical-align:top; padding:0;">
                                    @if($nota->detalles && $nota->detalles->count() > 0)
                                        <table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin:0;">
                                            <tbody>
                                                @foreach ($nota->detalles as $index => $detalle)
                                                    <tr style="{{ $index > 0 ? 'border-top: 1px solid var(--border-light);' : '' }}">
                                                        <td style="width: 33.33%; padding: 8px 12px; vertical-align: top; border:none; font-size: 13px;">
                                                            {{ $detalle->producto->nombre ?? $detalle->codigoproducto }}
                                                        </td>
                                                        <td style="width: 33.33%; padding: 8px 12px; vertical-align: top; text-align: center; border:none; font-family: var(--font-mono); font-size: 12px;">
                                                            {{ $detalle->cantidad ?? 0 }}
                                                        </td>
                                                        <td style="width: 33.34%; padding: 8px 12px; vertical-align: top; text-align: center; border:none; font-size: 13px; color: var(--muted);">
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
                                                    <td style="width: 33.33%; padding: 8px 12px; text-align: center; border:none; color: var(--muted); font-size: 13px;">Sin productos</td>
                                                    <td style="width: 33.33%; padding: 8px 12px; text-align: center; border:none; color: var(--muted);">—</td>
                                                    <td style="width: 33.34%; padding: 8px 12px; text-align: center; border:none; color: var(--muted);">—</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                </td>
                                <td style="color: var(--secondary);">{{ optional($nota->bodega)->nombrebodega ?? 'N/A' }}</td>
                                <td class="text-nowrap" style="color: var(--muted); font-size: 12px;">
                                    {{ $nota->fechanota ? \Carbon\Carbon::parse($nota->fechanota)->format('d/m/Y H:i') : 'N/A' }}
                                </td>
                                <td>
                                    @if(optional($nota->transaccion)->estado)
                                        <span class="badge bg-success">{{ $nota->transaccion->estado }}</span>
                                    @else
                                        <span class="badge bg-secondary">Sin Confirmar</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @if(!$nota->transaccion)
                                            <form action="{{ route('tipoNota.confirmar', $nota->codigo) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm btn-icon" title="Confirmar">
                                                    <i class="fas fa-check" style="font-size: 12px;"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('tipoNota.edit', $nota->codigo) }}"
                                               class="btn btn-warning btn-sm btn-icon" title="Editar">
                                                <i class="fas fa-edit" style="font-size: 12px;"></i>
                                            </a>
                                            @can('eliminar TipoNota')
                                                <form action="{{ route('tipoNota.destroy', $nota->codigo) }}" method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Eliminar"
                                                            onclick="return confirm('¿Estás seguro de eliminar esta nota?')">
                                                        <i class="fas fa-trash" style="font-size: 12px;"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 10px;">Confirmada</span>
                                        @endif
                                        <a href="{{ route('tipoNota.pdf', $nota->codigo) }}"
                                           class="btn btn-danger btn-sm btn-icon" title="Descargar PDF">
                                            <i class="fas fa-file-pdf" style="font-size: 12px;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4" style="color: var(--muted);">
                                    <i class="fas fa-file-alt d-block mb-2" style="font-size: 24px; opacity: 0.4;"></i>
                                    No se encontraron notas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $tipoNotas->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection