@extends('layouts.app')

@php
    $cargo = auth()->user()->cargoNombre();
@endphp

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de la página -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Catálogo de Productos</h3>
            <p class="page-subtitle mb-0 text-muted">Gestión de especies de anturios, inventario general y especificaciones</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('catalogo.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm" target="_blank">
                <i class="fas fa-external-link-alt me-1 text-primary"></i> Ver Catálogo Público
            </a>
            @can('crear producto')
            <a href="{{ route('productos.create') }}" class="btn btn-primary rounded-pill px-3 shadow-sm">
                <i class="fas fa-plus me-1"></i> Añadir Producto
            </a>
            @endcan
        </div>
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
                        <i class="fas fa-boxes fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Variedades Registradas</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalProductos ?? $productos->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                        <i class="fas fa-cubes fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Unidades Totales en Stock</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalStock ?? 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.12); color: #dc2626;">
                        <i class="fas fa-exclamation-triangle fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Stock Bajo o Agotado (≤ 10)</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $productosStockBajo ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('productos.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-barcode"></i>
                        </span>
                        <input type="text" name="search_codigo" id="search_codigo" class="form-control border-start-0 ps-1"
                               value="{{ request('search_codigo') }}" placeholder="Código de producto...">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search_nombre" id="search_nombre" class="form-control border-start-0 ps-1"
                               value="{{ request('search_nombre') }}" placeholder="Nombre o variedad...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-box"></i>
                        </span>
                        <select name="tipoempaque" class="form-select border-start-0 ps-1">
                            <option value="">Todos los empaques</option>
                            @if(isset($empaquesDisponibles))
                                @foreach($empaquesDisponibles as $emp)
                                    <option value="{{ $emp }}" {{ request('tipoempaque') == $emp ? 'selected' : '' }}>
                                        {{ $emp }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-3 w-100 shadow-sm">
                        <i class="fas fa-search me-1"></i> Filtrar
                    </button>
                    @if(request()->hasAny(['search_codigo', 'search_nombre', 'tipoempaque']))
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-pill px-3" title="Limpiar filtros">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Principal de Productos -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Producto</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Descripción</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Empaque</th>
                            <th class="py-3 text-uppercase text-muted fw-bold text-center" style="font-size: 11.5px; letter-spacing: 0.5px;">Existencias Globales</th>
                            @if(auth()->user()->can('editar producto') || auth()->user()->can('eliminar producto'))
                            <th class="text-end pe-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($productos as $producto)
                            @php
                                $stock = (int) $producto->cantidad;
                                if ($stock <= 0) {
                                    $stockBadge = ['bg' => '#fee2e2', 'color' => '#991b1b', 'border' => '#fecaca', 'label' => 'Agotado (0)', 'icon' => 'fa-times-circle'];
                                } elseif ($stock <= 10) {
                                    $stockBadge = ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'label' => 'Stock Bajo (' . $stock . ')', 'icon' => 'fa-exclamation-triangle'];
                                } else {
                                    $stockBadge = ['bg' => '#dcfce7', 'color' => '#166534', 'border' => '#bbf7d0', 'label' => $stock . ' unidades', 'icon' => 'fa-check-circle'];
                                }
                            @endphp
                            <tr style="transition: background-color 0.15s ease;">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 flex-shrink-0">
                                            @if($producto->imagen)
                                                <img src="{{ $producto->imagen }}" alt="Foto de {{ $producto->nombre }}" class="rounded-3 object-fit-cover shadow-xs border" style="width: 46px; height: 46px;">
                                            @else
                                                <div class="rounded-3 d-flex align-items-center justify-content-center shadow-xs border" style="width: 46px; height: 46px; background: rgba(0, 151, 167, 0.1); color: var(--primary);">
                                                    <i class="fas fa-leaf fs-5"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 14px;">{{ $producto->nombre }}</div>
                                            <div>
                                                <span class="badge rounded-pill bg-light text-muted border px-2 py-0.5 font-mono" style="font-size: 11px;">
                                                    {{ $producto->codigo }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3" style="max-width: 280px;">
                                    <div class="text-muted text-truncate" style="font-size: 13px;" title="{{ $producto->descripcion }}">
                                        {{ $producto->descripcion ?? '—' }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-2.5 py-1" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; font-size: 12px;">
                                        <i class="fas fa-box me-1 text-muted"></i>{{ $producto->tipoempaque ?? 'Unidad' }}
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge rounded-pill px-3 py-1 fw-semibold font-mono" style="background: {{ $stockBadge['bg'] }}; color: {{ $stockBadge['color'] }}; border: 1px solid {{ $stockBadge['border'] }}; font-size: 12px;">
                                        <i class="fas {{ $stockBadge['icon'] }} me-1" style="font-size: 10px;"></i>{{ $stockBadge['label'] }}
                                    </span>
                                </td>
                                @if(auth()->user()->can('editar producto') || auth()->user()->can('eliminar producto'))
                                <td class="text-end pe-4 py-3">
                                    <div class="d-inline-flex gap-1">
                                        @can('editar producto')
                                        <a href="{{ route('productos.edit', $producto->codigo) }}" 
                                           class="btn btn-sm btn-outline-primary rounded-circle" 
                                           title="Editar producto" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-edit" style="font-size: 12px;"></i>
                                        </a>
                                        @endcan

                                        @can('eliminar producto')
                                        <form action="{{ route('productos.destroy', $producto->codigo) }}" method="POST" class="d-inline form-delete-prod">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle btn-delete-prod" 
                                                    title="Eliminar producto" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-trash" style="font-size: 12px;"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: #f1f5f9; color: #94a3b8;">
                                            <i class="fas fa-box-open fs-3"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No se encontraron productos</h6>
                                        <p class="text-muted mb-3" style="font-size: 13.5px;">Intenta cambiar los filtros de búsqueda o registra una nueva especie de anturio.</p>
                                        @if(request()->hasAny(['search_codigo', 'search_nombre', 'tipoempaque']))
                                            <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
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

            @if(method_exists($productos, 'links') && $productos->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-center bg-white">
                    {{ $productos->onEachSide(1)->links('pagination::bootstrap-4') }}
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
    const btnsDelete = document.querySelectorAll('.btn-delete-prod');
    btnsDelete.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            let form = this.closest('form');
            Swal.fire({
                title: '¿Eliminar este producto?',
                text: "Si el producto tiene movimientos de bodega o ventas asociadas, se recomienda verificar antes de proceder.",
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
