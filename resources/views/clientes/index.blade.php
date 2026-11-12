@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Clientes</h3>
            <p class="page-subtitle">Directorio y gestión de clientes registrados para ventas</p>
        </div>
        <a href="{{ route('clientes.create') }}" class="btn btn-info">
            <i class="fas fa-user-plus me-1"></i> Registrar Cliente
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('clientes.index') }}" class="row g-2 align-items-center">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Buscar por nombres, apellidos, cédula, RUC, empresa o celular..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Buscar
                    </button>
                    @if(request('search'))
                        <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary" title="Limpiar filtro">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nombres y Apellidos</th>
                            <th>Cédula / RUC</th>
                            <th>Empresa</th>
                            <th>Celular</th>
                            <th>Correo</th>
                            <th>Dirección</th>
                            <th class="text-center">Compras</th>
                            <th style="width: 130px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $cliente)
                            <tr>
                                <td class="fw-medium">
                                    <i class="fas fa-user-circle text-primary me-2"></i>{{ $cliente->nombre_completo }}
                                </td>
                                <td>
                                    <span class="font-mono badge bg-light text-dark border">{{ $cliente->cedula }}</span>
                                </td>
                                <td>
                                    @if($cliente->nombre_empresa)
                                        <span class="badge bg-info bg-opacity-10 text-info fw-semibold">
                                            <i class="fas fa-building me-1"></i>{{ $cliente->nombre_empresa }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <i class="fas fa-phone-alt text-muted me-1" style="font-size: 11px;"></i>{{ $cliente->celular }}
                                </td>
                                <td style="color: var(--secondary);">
                                    {{ $cliente->correo ?? '—' }}
                                </td>
                                <td style="color: var(--secondary); max-width: 220px;" class="text-truncate" title="{{ $cliente->direccion }}">
                                    {{ $cliente->direccion }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill px-2">
                                        {{ $cliente->ventas_count }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-info btn-sm btn-icon" title="Ver Detalle e Historial">
                                            <i class="fas fa-eye" style="font-size: 12px;"></i>
                                        </a>
                                        <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning btn-sm btn-icon" title="Editar Cliente">
                                            <i class="fas fa-edit" style="font-size: 12px;"></i>
                                        </a>
                                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este cliente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Eliminar Cliente">
                                                <i class="fas fa-trash" style="font-size: 12px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4" style="color: var(--muted);">
                                    <i class="fas fa-user-slash d-block mb-2" style="font-size: 28px; opacity: 0.4;"></i>
                                    No se encontraron clientes registrados.
                                    <div class="mt-2">
                                        <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-1"></i> Registrar el primer cliente
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $clientes->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

