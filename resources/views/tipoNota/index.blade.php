@extends('layouts.app')

@php
    $cargo = auth()->user()->cargoNombre();
@endphp

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de la página -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Notas de Pedido y Movimiento</h3>
            <p class="page-subtitle mb-0 text-muted">Solicitudes formales de envío y devolución entre bodegas e inventario general</p>
        </div>
        @if(!in_array($cargo, ['Jefe de bodega']))
        <a href="{{ route('tipoNota.create') }}" class="btn btn-primary rounded-pill px-3 shadow-sm">
            <i class="fas fa-plus me-1"></i> Crear Nueva Nota
        </a>
        @endif
    </div>

    <!-- Alertas de Sesión -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tarjetas de Resumen KPI -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                        <i class="fas fa-clipboard-list fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total de Notas</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalNotas ?? $tipoNotas->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(59, 130, 246, 0.12); color: #2563eb;">
                        <i class="fas fa-paper-plane fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Notas de Envío</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalEnvios ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.12); color: #d97706;">
                        <i class="fas fa-undo-alt fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Notas de Devolución</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalDevoluciones ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('tipoNota.index') }}" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-warehouse"></i>
                        </span>
                        <select name="bodega" id="filtro_bodega" class="form-select border-start-0 ps-1">
                            <option value="">Todas las bodegas destino</option>
                            @foreach($bodegas as $bodega)
                                <option value="{{ $bodega->idbodega }}" {{ request('bodega') == $bodega->idbodega ? 'selected' : '' }}>
                                    {{ $bodega->nombrebodega }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-exchange-alt"></i>
                        </span>
                        <select name="tipo" id="filtro_tipo" class="form-select border-start-0 ps-1">
                            <option value="">Todos los tipos de nota</option>
                            <option value="ENVIO" {{ request('tipo') == 'ENVIO' ? 'selected' : '' }}>Notas de Envío</option>
                            <option value="DEVOLUCION" {{ request('tipo') == 'DEVOLUCION' ? 'selected' : '' }}>Notas de Devolución</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-3 w-100 shadow-sm">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                    @if(request()->hasAny(['bodega', 'tipo']))
                    <a href="{{ route('tipoNota.index') }}" class="btn btn-outline-secondary rounded-pill px-3" title="Limpiar filtros">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Principal de Notas -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Nota</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Tipo</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Bodega Destino</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Solicitante</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Productos Solicitados</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Estado</th>
                            <th class="text-end pe-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($tipoNotas as $nota)
                            @php
                                $esEnvio = ($nota->tiponota === 'ENVIO');
                                $tipoStyle = $esEnvio 
                                    ? ['bg' => '#dbeafe', 'color' => '#1e40af', 'border' => '#bfdbfe', 'icon' => 'fa-paper-plane', 'text' => 'ENVÍO']
                                    : ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'icon' => 'fa-undo', 'text' => 'DEVOLUCIÓN'];
                                
                                $fechaNota = $nota->fechanota ? \Carbon\Carbon::parse($nota->fechanota)->format('d/m/Y H:i') : 'N/A';
                                $estadoTransaccion = optional($nota->transaccion)->estado;
                            @endphp
                            <tr style="transition: background-color 0.15s ease;">
                                <td class="ps-4 py-3">
                                    <div>
                                        <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1 font-mono fw-bold" style="font-size: 12.5px;">
                                            #{{ $nota->codigo }}
                                        </span>
                                        <div class="text-muted font-mono mt-1" style="font-size: 11.5px;">
                                            <i class="far fa-clock me-1 text-muted"></i>{{ $fechaNota }}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: {{ $tipoStyle['bg'] }}; color: {{ $tipoStyle['color'] }}; border: 1px solid {{ $tipoStyle['border'] }}; font-size: 11.5px;">
                                        <i class="fas {{ $tipoStyle['icon'] }} me-1" style="font-size: 10px;"></i>{{ $tipoStyle['text'] }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(0, 151, 167, 0.08); color: #006064; border: 1px solid rgba(0, 151, 167, 0.2); font-size: 12px;">
                                        <i class="fas fa-warehouse me-1 text-primary"></i>{{ optional($nota->bodega)->nombrebodega ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <div class="text-dark fw-medium" style="font-size: 13.5px;">
                                        {{ optional($nota->responsableEmpleado)->nombreemp ?? 'N/A' }} {{ optional($nota->responsableEmpleado)->apellidoemp ?? '' }}
                                    </div>
                                </td>
                                <td class="py-3" style="max-width: 320px;">
                                    @if($nota->detalles && $nota->detalles->count() > 0)
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($nota->detalles as $detalle)
                                                <span class="badge bg-white text-dark border fw-medium px-2 py-1 shadow-xs" style="font-size: 11.5px; border-color: #e2e8f0 !important;">
                                                    <span class="fw-bold text-primary me-1">{{ $detalle->cantidad ?? 0 }}x</span>
                                                    {{ $detalle->producto->nombre ?? $detalle->codigoproducto }}
                                                    <small class="text-muted ms-1">({{ $detalle->producto->tipoempaque ?? 'Unidad' }})</small>
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic" style="font-size: 12px;">Sin productos asignados</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if($estadoTransaccion)
                                        @php
                                            $estadoStyles = [
                                                'PENDIENTE' => ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'icon' => 'fa-clock'],
                                                'FINALIZADA' => ['bg' => '#dcfce7', 'color' => '#166534', 'border' => '#bbf7d0', 'icon' => 'fa-check-circle'],
                                                'FINALIZADA_PARCIAL' => ['bg' => '#e0f2fe', 'color' => '#0369a1', 'border' => '#bae6fd', 'icon' => 'fa-info-circle'],
                                                'RECHAZADA' => ['bg' => '#fee2e2', 'color' => '#991b1b', 'border' => '#fecaca', 'icon' => 'fa-times-circle'],
                                            ];
                                            $eStyle = $estadoStyles[$estadoTransaccion] ?? ['bg' => '#f1f5f9', 'color' => '#334155', 'border' => '#e2e8f0', 'icon' => 'fa-check'];
                                        @endphp
                                        <span class="badge rounded-pill px-2.5 py-1 fw-semibold" style="background: {{ $eStyle['bg'] }}; color: {{ $eStyle['color'] }}; border: 1px solid {{ $eStyle['border'] }}; font-size: 11.5px;">
                                            <i class="fas {{ $eStyle['icon'] }} me-1" style="font-size: 10px;"></i>{{ str_replace('_', ' ', $estadoTransaccion) }}
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-2.5 py-1 fw-semibold" style="background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; font-size: 11.5px;">
                                            <i class="fas fa-hourglass-start me-1"></i>Sin Confirmar
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4 py-3">
                                    <div class="d-inline-flex gap-1">
                                        @if(!$nota->transaccion)
                                            <form action="{{ route('tipoNota.confirmar', $nota->codigo) }}" method="POST" class="d-inline form-confirm-nota">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success rounded-circle" title="Confirmar y Generar Transacción" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-check" style="font-size: 12px;"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('tipoNota.edit', $nota->codigo) }}"
                                               class="btn btn-sm btn-outline-primary rounded-circle" title="Editar Nota" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-edit" style="font-size: 12px;"></i>
                                            </a>
                                            @can('eliminar nota')
                                                <form action="{{ route('tipoNota.destroy', $nota->codigo) }}" method="POST" class="d-inline form-delete-nota">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle btn-delete-nota" title="Eliminar Nota" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-trash" style="font-size: 12px;"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif
                                        <a href="{{ route('tipoNota.pdf', $nota->codigo) }}"
                                           class="btn btn-sm btn-outline-danger rounded-circle" title="Descargar Comprobante PDF" target="_blank" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-file-pdf" style="font-size: 12px;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: #f1f5f9; color: #94a3b8;">
                                            <i class="fas fa-clipboard-list fs-3"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No se encontraron notas de pedido</h6>
                                        <p class="text-muted mb-3" style="font-size: 13.5px;">Intenta cambiar los filtros seleccionados o registra una nueva solicitud.</p>
                                        @if(request()->hasAny(['bodega', 'tipo']))
                                            <a href="{{ route('tipoNota.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                <i class="fas fa-undo me-1"></i> Restablecer filtros
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($tipoNotas, 'links') && $tipoNotas->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-center bg-white">
                    {{ $tipoNotas->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.shadow-xs {
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnsDelete = document.querySelectorAll('.btn-delete-nota');
    btnsDelete.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            let form = this.closest('form');
            Swal.fire({
                title: '¿Eliminar esta nota?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'rounded-pill px-3',
                    cancelButton: 'rounded-pill px-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size: 12px;"></i>';
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
