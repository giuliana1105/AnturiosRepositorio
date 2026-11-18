@extends('layouts.app')

@section('content')
<div class="container-fluid py-3 px-3 px-md-4">
    <!-- Breadcrumb & Wayfinding -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                        <i class="fas fa-home me-1 text-primary"></i> Inicio
                    </a>
                </li>
                <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                    Clientes
                </li>
            </ol>
        </nav>
    </div>

    <!-- Encabezado de Página -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 pb-3 mb-4 border-bottom" style="border-color: #e2e8f0 !important;">
        <div>
            <h2 class="fw-bold mb-1 text-dark tracking-tight" style="letter-spacing: -0.025em;">
                Gestión de Clientes
            </h2>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Directorio unificado de clientes particulares y corporativos, historial de compras y gestión de crédito.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 py-2 fw-medium">
                <i class="fas fa-arrow-left me-1"></i> Volver al Panel
            </a>
            <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-primary rounded-3 px-3 py-2 fw-semibold shadow-sm">
                <i class="fas fa-user-plus me-1.5"></i> Registrar Nuevo Cliente
            </a>
        </div>
    </div>

    <!-- Alertas de Sesión -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2 fs-5"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2 fs-5"></i>{!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tarjetas Resumen Ejecutivo (Estilo Impeccable) -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Total Clientes -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Total Clientes</span>
                        <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold" style="font-size: 11px;">
                            Base General
                        </span>
                    </div>
                    <div class="metric-value">
                        {{ number_format($totalClientes ?? $clientes->total()) }}
                        <span class="metric-unit">contactos</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-users text-primary me-1"></i> Clientes registrados en el sistema
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Clientes Corporativos -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Corporativos</span>
                        <span class="badge rounded-pill bg-info-subtle text-info fw-semibold" style="font-size: 11px;">
                            Empresas
                        </span>
                    </div>
                    <div class="metric-value font-mono">
                        {{ number_format($clientesEmpresa ?? 0) }}
                        <span class="metric-unit">empresas</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-building text-info me-1"></i> Con RUC o razón comercial
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Ventas a Clientes -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Ventas Vinculadas</span>
                        <span class="badge rounded-pill bg-purple-subtle text-purple fw-semibold" style="font-size: 11px; background: rgba(147, 51, 234, 0.1); color: #7e22ce;">
                            Transacciones
                        </span>
                    </div>
                    <div class="metric-value font-mono">
                        {{ number_format($totalVentas ?? 0) }}
                        <span class="metric-unit">facturas</span>
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-receipt text-purple me-1"></i> Histórico total de pedidos
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 4: Facturación Total -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 metric-card">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="metric-label">Facturación Total</span>
                        <span class="badge rounded-pill bg-success-subtle text-success fw-semibold" style="font-size: 11px;">
                            Consolidado
                        </span>
                    </div>
                    <div class="metric-value text-success font-mono">
                        ${{ number_format($totalFacturado ?? 0, 2) }}
                    </div>
                    <div class="metric-caption text-muted mt-1">
                        <i class="fas fa-dollar-sign text-success me-1"></i> Volumen monetario vendido
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div class="card border rounded-3 mb-4 bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-3 p-md-3.5">
            <form method="GET" action="{{ route('clientes.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-6 col-lg-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-search" style="font-size: 13px;"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-1" 
                               placeholder="Buscar por nombre, identificación/RUC, empresa, teléfono o correo..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <select name="tipo" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los perfiles de clientes</option>
                        <option value="empresa" {{ request('tipo') == 'empresa' ? 'selected' : '' }}>Solo Corporativos / Empresas</option>
                        <option value="personal" {{ request('tipo') == 'personal' ? 'selected' : '' }}>Solo Personas Naturales</option>
                    </select>
                </div>

                <div class="col-12 col-md-2 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-3 w-100 py-2 fw-semibold">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                    @if(request('search') || request('tipo'))
                        <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary rounded-3" title="Limpiar filtros">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Principal de Clientes -->
    <div class="card border rounded-3 overflow-hidden shadow-xs bg-white" style="border-color: #e2e8f0 !important;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 operational-table">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3" style="width: 280px;">Cliente</th>
                            <th class="py-3" style="width: 140px;">Identificación</th>
                            <th class="py-3" style="width: 220px;">Empresa / Razón Social</th>
                            <th class="py-3">Contacto</th>
                            <th class="py-3">Dirección</th>
                            <th class="py-3 text-center" style="width: 120px;">Compras</th>
                            <th class="py-3 text-end pe-4" style="width: 130px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $cliente)
                            <tr class="operational-row">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                                             style="width: 38px; height: 38px; background: rgba(0, 151, 167, 0.1); color: var(--primary); font-weight: 700; font-size: 13px;">
                                            {{ strtoupper(substr($cliente->nombres, 0, 1) . substr($cliente->apellidos, 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('clientes.show', $cliente->id) }}" class="fw-bold text-dark text-decoration-none hover-primary" style="font-size: 13.5px;">
                                                {{ $cliente->nombre_completo }}
                                            </a>
                                            <div class="text-muted font-mono" style="font-size: 11px;">
                                                Alta: {{ $cliente->created_at ? $cliente->created_at->format('d/m/Y') : '—' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="font-mono text-dark fw-bold" style="font-size: 12.5px;">
                                        {{ $cliente->cedula }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    @if($cliente->nombre_empresa)
                                        <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle px-2.5 py-1" style="font-size: 12px;">
                                            <i class="fas fa-building me-1"></i>{{ $cliente->nombre_empresa }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size: 12.5px;">— Persona Natural</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <div class="d-flex flex-column gap-1">
                                        @if($cliente->celular)
                                            <a href="tel:{{ $cliente->celular }}" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1 font-mono" style="font-size: 12.5px;">
                                                <i class="fas fa-phone-alt text-success" style="font-size: 10px;"></i> {{ $cliente->celular }}
                                            </a>
                                        @endif
                                        @if($cliente->correo)
                                            <a href="mailto:{{ $cliente->correo }}" class="text-decoration-none text-muted d-inline-flex align-items-center gap-1" style="font-size: 12px;">
                                                <i class="fas fa-envelope text-primary" style="font-size: 10px;"></i> {{ $cliente->correo }}
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3" style="max-width: 220px;">
                                    <div class="text-truncate text-muted" style="font-size: 12.5px;" title="{{ $cliente->direccion }}">
                                        <i class="fas fa-map-marker-alt text-muted me-1" style="font-size: 10px;"></i>{{ $cliente->direccion }}
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="font-mono fw-bold px-2.5 py-1 rounded-pill" 
                                          style="font-size: 12px; background: {{ $cliente->ventas_count > 0 ? '#ecfdf5' : '#f8fafc' }}; color: {{ $cliente->ventas_count > 0 ? '#047857' : '#64748b' }}; border: 1px solid {{ $cliente->ventas_count > 0 ? '#a7f3d0' : '#e2e8f0' }};">
                                        {{ $cliente->ventas_count }} {{ Str::plural('venta', $cliente->ventas_count) }}
                                    </span>
                                </td>
                                <td class="py-3 text-end pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('clientes.show', $cliente->id) }}" 
                                           class="btn-action-circle" title="Ver Detalle e Historial">
                                            <i class="fas fa-eye" style="font-size: 12px;"></i>
                                        </a>
                                        <a href="{{ route('clientes.edit', $cliente->id) }}" 
                                           class="btn-action-circle text-primary" title="Editar Cliente">
                                            <i class="fas fa-edit" style="font-size: 12px;"></i>
                                        </a>
                                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="d-inline form-eliminar-cliente">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-action-circle danger btn-delete-cliente" 
                                                    title="Eliminar Cliente" data-nombre="{{ $cliente->nombre_completo }}">
                                                <i class="fas fa-trash" style="font-size: 12px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light text-muted mb-3" style="width: 56px; height: 56px; font-size: 22px;">
                                            <i class="fas fa-user-slash"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No se encontraron clientes</h6>
                                        <p class="text-muted mb-3" style="font-size: 13.5px;">Intenta con otro término de búsqueda o registra un nuevo cliente.</p>
                                        <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-primary rounded-3 px-4">
                                            <i class="fas fa-user-plus me-1"></i> Registrar Cliente
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($clientes->hasPages())
                <div class="p-3 border-top d-flex justify-content-center" style="border-color: #e2e8f0 !important;">
                    {{ $clientes->appends(['search' => request('search'), 'tipo' => request('tipo')])->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.hover-primary:hover {
    color: var(--primary) !important;
}
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
</style>

{{-- SweetAlert2 para confirmación de eliminación --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete-cliente');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');
            const clienteNombre = this.getAttribute('data-nombre');

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Eliminar cliente?',
                    text: `¿Estás seguro de eliminar a "${clienteNombre}"? Esta acción no se puede deshacer.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        popup: 'rounded-4 shadow-lg'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                if (confirm(`¿Estás seguro de eliminar al cliente "${clienteNombre}"?`)) {
                    form.submit();
                }
            }
        });
    });
});
</script>
@endsection
