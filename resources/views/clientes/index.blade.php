@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de Página -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--foreground); letter-spacing: -0.5px;">
                <i class="fas fa-user-friends me-2" style="color: var(--primary);"></i>Gestión de Clientes
            </h3>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Directorio general de clientes, perfil crediticio y registro histórico de ventas.
            </p>
        </div>
        <div>
            <a href="{{ route('clientes.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-user-plus me-2"></i>Registrar Cliente
            </a>
        </div>
    </div>

    <!-- Alertas de Sesión -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-check-circle me-2 fs-5"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2 fs-5"></i>{!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tarjetas Resumen Ejecutivo (KPIs) -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="border-left: 4px solid var(--primary) !important; background: #fff;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fw-semibold" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Total Clientes</span>
                        <div class="p-2 rounded-circle" style="background: rgba(0, 151, 167, 0.1); color: var(--primary);">
                            <i class="fas fa-users" style="font-size: 16px;"></i>
                        </div>
                    </div>
                    <div class="fs-4 fw-bold text-dark">{{ number_format($totalClientes ?? $clientes->total()) }}</div>
                    <small class="text-muted" style="font-size: 12px;">Clientes activos en base</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="border-left: 4px solid #0ea5e9 !important; background: #fff;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fw-semibold" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Clientes Corporativos</span>
                        <div class="p-2 rounded-circle" style="background: rgba(14, 165, 233, 0.1); color: #0ea5e9;">
                            <i class="fas fa-building" style="font-size: 16px;"></i>
                        </div>
                    </div>
                    <div class="fs-4 fw-bold text-dark">{{ number_format($clientesEmpresa ?? 0) }}</div>
                    <small class="text-muted" style="font-size: 12px;">Con razón social / empresa</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="border-left: 4px solid #8b5cf6 !important; background: #fff;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fw-semibold" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Ventas a Clientes</span>
                        <div class="p-2 rounded-circle" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                            <i class="fas fa-shopping-bag" style="font-size: 16px;"></i>
                        </div>
                    </div>
                    <div class="fs-4 fw-bold text-dark">{{ number_format($totalVentas ?? 0) }}</div>
                    <small class="text-muted" style="font-size: 12px;">Transacciones vinculadas</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="border-left: 4px solid #10b981 !important; background: #fff;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fw-semibold" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Facturación Total</span>
                        <div class="p-2 rounded-circle" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                            <i class="fas fa-dollar-sign" style="font-size: 16px;"></i>
                        </div>
                    </div>
                    <div class="fs-4 fw-bold text-success">${{ number_format($totalFacturado ?? 0, 2) }}</div>
                    <small class="text-muted" style="font-size: 12px;">Volumen histórico vendido</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('clientes.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-6 col-lg-7">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 ps-3">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-0 bg-light rounded-end-pill py-2" 
                               placeholder="Buscar por nombre, cédula/RUC, empresa, teléfono o correo..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <select name="tipo" class="form-select border-0 bg-light rounded-pill py-2" onchange="this.form.submit()">
                        <option value="">Todos los tipos de clientes</option>
                        <option value="empresa" {{ request('tipo') == 'empresa' ? 'selected' : '' }}>Solo Corporativos / Empresas</option>
                        <option value="personal" {{ request('tipo') == 'personal' ? 'selected' : '' }}>Solo Personas Naturales</option>
                    </select>
                </div>

                <div class="col-12 col-md-2 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill w-100 py-2">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                    @if(request('search') || request('tipo'))
                        <a href="{{ route('clientes.index') }}" class="btn btn-light rounded-pill border" title="Limpiar filtros">
                            <i class="fas fa-times text-muted"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Principal de Clientes -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #edf2f7;">
                        <tr>
                            <th class="ps-4 py-3" style="font-size: 12.5px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">Cliente</th>
                            <th class="py-3" style="font-size: 12.5px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">Identificación</th>
                            <th class="py-3" style="font-size: 12.5px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">Empresa / Razón</th>
                            <th class="py-3" style="font-size: 12.5px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">Contacto</th>
                            <th class="py-3" style="font-size: 12.5px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">Dirección</th>
                            <th class="py-3 text-center" style="font-size: 12.5px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;">Compras</th>
                            <th class="py-3 text-end pe-4" style="font-size: 12.5px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($clientes as $cliente)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                                             style="width: 40px; height: 40px; background: rgba(0, 151, 167, 0.12); color: var(--primary); font-weight: 700; font-size: 14px;">
                                            {{ strtoupper(substr($cliente->nombres, 0, 1) . substr($cliente->apellidos, 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('clientes.show', $cliente->id) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $cliente->nombre_completo }}
                                            </a>
                                            <div class="text-muted" style="font-size: 11.5px;">Registrado el {{ $cliente->created_at ? $cliente->created_at->format('d/m/Y') : '—' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-2.5 py-1.5 font-monospace fw-normal" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; font-size: 12px;">
                                        <i class="fas fa-id-card text-muted me-1"></i>{{ $cliente->cedula }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    @if($cliente->nombre_empresa)
                                        <span class="badge rounded-pill px-3 py-1.5 fw-semibold" style="background: rgba(14, 165, 233, 0.1); color: #0284c7; border: 1px solid rgba(14, 165, 233, 0.25); font-size: 12px;">
                                            <i class="fas fa-building me-1"></i>{{ $cliente->nombre_empresa }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size: 13px;">— Persona Natural</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <div class="d-flex flex-column gap-1">
                                        @if($cliente->celular)
                                            <a href="tel:{{ $cliente->celular }}" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1" style="font-size: 13px;">
                                                <i class="fas fa-phone-alt text-success" style="font-size: 11px;"></i> {{ $cliente->celular }}
                                            </a>
                                        @endif
                                        @if($cliente->correo)
                                            <a href="mailto:{{ $cliente->correo }}" class="text-decoration-none text-muted d-inline-flex align-items-center gap-1" style="font-size: 12px;">
                                                <i class="fas fa-envelope text-primary" style="font-size: 11px;"></i> {{ $cliente->correo }}
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3" style="max-width: 200px;">
                                    <div class="text-truncate text-muted" style="font-size: 13px;" title="{{ $cliente->direccion }}">
                                        <i class="fas fa-map-marker-alt text-danger me-1" style="font-size: 11px;"></i>{{ $cliente->direccion }}
                                    </div>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge rounded-pill px-3 py-1 fw-bold" 
                                          style="background: {{ $cliente->ventas_count > 0 ? 'rgba(16, 185, 129, 0.1)' : '#f1f5f9' }}; 
                                                 color: {{ $cliente->ventas_count > 0 ? '#059669' : '#64748b' }}; 
                                                 border: 1px solid {{ $cliente->ventas_count > 0 ? 'rgba(16, 185, 129, 0.25)' : '#e2e8f0' }}; font-size: 12px;">
                                        {{ $cliente->ventas_count }} {{ Str::plural('venta', $cliente->ventas_count) }}
                                    </span>
                                </td>
                                <td class="py-3 text-end pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('clientes.show', $cliente->id) }}" 
                                           class="btn btn-sm btn-outline-info rounded-circle d-flex align-items-center justify-content-center shadow-xs" 
                                           style="width: 32px; height: 32px;" title="Ver Detalle e Historial">
                                            <i class="fas fa-eye" style="font-size: 12px;"></i>
                                        </a>
                                        <a href="{{ route('clientes.edit', $cliente->id) }}" 
                                           class="btn btn-sm btn-outline-warning rounded-circle d-flex align-items-center justify-content-center shadow-xs" 
                                           style="width: 32px; height: 32px;" title="Editar Cliente">
                                            <i class="fas fa-edit" style="font-size: 12px;"></i>
                                        </a>
                                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="d-inline form-eliminar-cliente">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center shadow-xs btn-delete-cliente" 
                                                    style="width: 32px; height: 32px;" title="Eliminar Cliente" data-nombre="{{ $cliente->nombre_completo }}">
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
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light text-muted mb-3" style="width: 64px; height: 64px; font-size: 26px;">
                                            <i class="fas fa-user-slash"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No se encontraron clientes</h6>
                                        <p class="text-muted mb-3" style="font-size: 13.5px;">Intenta con otro término de búsqueda o registra un nuevo cliente.</p>
                                        <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-primary rounded-pill px-4">
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
                <div class="p-3 border-top d-flex justify-content-center">
                    {{ $clientes->appends(['search' => request('search'), 'tipo' => request('tipo')])->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- SweetAlert2 para eliminación --}}
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
