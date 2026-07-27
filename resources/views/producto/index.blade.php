@extends('layouts.app')
@php
    $cargo = auth()->user()->cargoNombre();
@endphp

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Productos</h3>
            <p class="page-subtitle">Catálogo de productos en inventario</p>
        </div>
        @if(in_array($cargo, ['Administrador', 'Gerente', 'Jefe de bodega']))
        <a href="{{ route('productos.create') }}" class="btn btn-info">
            <i class="fas fa-plus"></i> Añadir Producto
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <!-- Search / Filters -->
            <form action="{{ route('productos.index') }}" method="GET" class="row g-3 mb-4 align-items-end">
                <div class="col-md-4">
                    <label for="search_codigo" class="form-label">Código</label>
                    <input type="text" name="search_codigo" id="search_codigo" class="form-control"
                           value="{{ request('search_codigo') }}" placeholder="Buscar por código...">
                </div>
                <div class="col-md-4">
                    <label for="search_nombre" class="form-label">Nombre</label>
                    <input type="text" name="search_nombre" id="search_nombre" class="form-control"
                           value="{{ request('search_nombre') }}" placeholder="Buscar por nombre...">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Tipo de Empaque</th>
                            @if(in_array($cargo, ['Administrador', 'Gerente', 'Jefe de bodega']))
                            <th style="width: 100px;">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                            <tr>
                                <td><span class="font-mono">{{ $producto->codigo }}</span></td>
                                <td class="fw-medium">{{ $producto->nombre }}</td>
                                <td style="color: var(--muted); max-width: 250px;">{{ Str::limit($producto->descripcion, 60) }}</td>
                                <td><span class="font-mono">{{ $producto->cantidad }}</span></td>
                                <td>
                                    <span class="badge bg-secondary">{{ $producto->tipoempaque }}</span>
                                </td>
                                @if(in_array($cargo, ['Administrador', 'Gerente', 'Jefe de bodega']))
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('productos.edit', $producto->codigo) }}" 
                                           class="btn btn-warning btn-sm btn-icon" title="Editar">
                                            <i class="fas fa-edit" style="font-size: 12px;"></i>
                                        </a>
                                        <form action="{{ route('productos.destroy', $producto->codigo) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm btn-icon" title="Eliminar"
                                                    onclick="return confirm('¿Seguro de eliminar?')">
                                                <i class="fas fa-trash" style="font-size: 12px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4" style="color: var(--muted);">
                                    <i class="fas fa-inbox d-block mb-2" style="font-size: 24px; opacity: 0.4;"></i>
                                    No se encontraron productos
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3 d-flex justify-content-center">
                {{ $productos->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
