@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de Página -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('clientes.index') }}" class="text-decoration-none text-muted" style="font-size: 13px;">
                    <i class="fas fa-user-friends me-1"></i> Clientes
                </a>
                <span class="text-muted" style="font-size: 12px;">/</span>
                <span class="text-primary fw-semibold" style="font-size: 13px;">Ficha de Cliente</span>
            </div>
            <h3 class="fw-bold mb-0 mt-1" style="color: var(--foreground); letter-spacing: -0.5px;">
                {{ $cliente->nombre_completo }}
            </h3>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Volver a Clientes
            </a>
            <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-outline-warning rounded-pill px-3 shadow-sm">
                <i class="fas fa-edit me-1"></i> Editar Cliente
            </a>
            @php
                $userBodegaId = auth()->user()->empleado->bodega_id ?? null;
            @endphp
            @if($userBodegaId)
                <a href="{{ route('venta.create', $userBodegaId) }}" class="btn btn-primary rounded-pill px-3 shadow-sm">
                    <i class="fas fa-cart-plus me-1"></i> Nueva Venta
                </a>
            @else
                <a href="{{ route('venta.index') }}" class="btn btn-primary rounded-pill px-3 shadow-sm">
                    <i class="fas fa-cash-register me-1"></i> Ver Ventas
                </a>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Columna Izquierda: Perfil y Datos del Cliente -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-body p-4 text-center" style="background: linear-gradient(180deg, rgba(0,151,167,0.06) 0%, rgba(255,255,255,1) 100%);">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" 
                         style="width: 76px; height: 76px; background: rgba(0, 151, 167, 0.15); color: var(--primary); font-size: 26px; font-weight: 700;">
                        {{ strtoupper(substr($cliente->nombres, 0, 1) . substr($cliente->apellidos, 0, 1)) }}
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ $cliente->nombre_completo }}</h5>
                    <div class="mb-3">
                        @if($cliente->nombre_empresa)
                            <span class="badge rounded-pill px-3 py-1.5 fw-semibold" style="background: rgba(14, 165, 233, 0.1); color: #0284c7; border: 1px solid rgba(14, 165, 233, 0.25); font-size: 12px;">
                                <i class="fas fa-building me-1"></i> {{ $cliente->nombre_empresa }}
                            </span>
                        @else
                            <span class="badge rounded-pill px-3 py-1.5 fw-semibold" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-size: 12px;">
                                <i class="fas fa-user me-1"></i> Persona Natural
                            </span>
                        @endif
                    </div>

                    {{-- Acciones directas de comunicación --}}
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        @if($cliente->celular)
                            <a href="tel:{{ $cliente->celular }}" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-xs" title="Llamar al cliente">
                                <i class="fas fa-phone-alt me-1"></i> Llamar
                            </a>
                            <a href="https://wa.me/593{{ ltrim(preg_replace('/[^0-9]/', '', $cliente->celular), '0') }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-xs" title="Enviar WhatsApp">
                                <i class="fab fa-whatsapp me-1"></i> WhatsApp
                            </a>
                        @endif
                        @if($cliente->correo)
                            <a href="mailto:{{ $cliente->correo }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-xs" title="Enviar Correo">
                                <i class="fas fa-envelope me-1"></i> Correo
                            </a>
                        @endif
                    </div>

                    {{-- Lista de atributos --}}
                    <div class="text-start border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted" style="font-size: 13px;"><i class="fas fa-id-card text-muted me-2"></i>Cédula / RUC</span>
                            <span class="fw-semibold font-monospace text-dark" style="font-size: 13px;">{{ $cliente->cedula }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted" style="font-size: 13px;"><i class="fas fa-phone text-muted me-2"></i>Teléfono</span>
                            <span class="fw-semibold text-dark" style="font-size: 13px;">{{ $cliente->celular }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted" style="font-size: 13px;"><i class="fas fa-envelope text-muted me-2"></i>Correo</span>
                            <span class="fw-semibold text-dark text-truncate" style="font-size: 13px; max-width: 180px;" title="{{ $cliente->correo }}">
                                {{ $cliente->correo ?? 'No registrado' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-start py-2 border-bottom">
                            <span class="text-muted" style="font-size: 13px;"><i class="fas fa-map-marker-alt text-muted me-2"></i>Dirección</span>
                            <span class="fw-semibold text-dark text-end" style="font-size: 13px; max-width: 60%;">
                                {{ $cliente->direccion }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2">
                            <span class="text-muted" style="font-size: 13px;"><i class="fas fa-calendar-alt text-muted me-2"></i>Registro</span>
                            <span class="text-muted" style="font-size: 13px;">{{ $cliente->created_at ? $cliente->created_at->format('d/m/Y') : '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen Financiero del Cliente -->
            <div class="row g-2">
                <div class="col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-white h-100">
                        <small class="text-muted fw-semibold" style="font-size: 11.5px; text-transform: uppercase;">Compras</small>
                        <div class="fs-4 fw-bold text-primary mt-1">{{ $cliente->ventas->count() }}</div>
                        <small class="text-muted" style="font-size: 11px;">pedidos registrados</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-white h-100">
                        <small class="text-muted fw-semibold" style="font-size: 11.5px; text-transform: uppercase;">Facturado</small>
                        <div class="fs-4 fw-bold text-success mt-1">${{ number_format($totalComprado ?? 0, 2) }}</div>
                        <small class="text-muted" style="font-size: 11px;">total acumulado</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Historial de Ventas -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 34px; height: 34px; background: rgba(0, 151, 167, 0.1); color: var(--primary);">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Historial de Ventas</h5>
                    </div>
                    <span class="badge rounded-pill bg-light text-dark border px-3 py-1.5 font-monospace">
                        {{ $cliente->ventas->count() }} {{ Str::plural('registro', $cliente->ventas->count()) }}
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: #f8fafc; border-bottom: 2px solid #edf2f7;">
                                <tr>
                                    <th class="ps-4 py-3" style="font-size: 12px; text-transform: uppercase; color: #64748b;">Nro. Venta</th>
                                    <th class="py-3" style="font-size: 12px; text-transform: uppercase; color: #64748b;">Fecha</th>
                                    <th class="py-3" style="font-size: 12px; text-transform: uppercase; color: #64748b;">Bodega</th>
                                    <th class="py-3" style="font-size: 12px; text-transform: uppercase; color: #64748b;">Ciudad</th>
                                    <th class="py-3" style="font-size: 12px; text-transform: uppercase; color: #64748b;">Pago</th>
                                    <th class="py-3 text-end" style="font-size: 12px; text-transform: uppercase; color: #64748b;">Total</th>
                                    <th class="py-3 text-center pe-4" style="font-size: 12px; text-transform: uppercase; color: #64748b; width: 80px;">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cliente->ventas as $venta)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <a href="{{ route('venta.show', $venta->id) }}" class="fw-bold font-monospace text-primary text-decoration-none">
                                                #{{ $venta->nro_venta }}
                                            </a>
                                        </td>
                                        <td class="py-3" style="font-size: 13px; color: #475569;">
                                            {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}
                                        </td>
                                        <td class="py-3">
                                            <span class="badge rounded-pill px-2.5 py-1" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; font-size: 11.5px;">
                                                <i class="fas fa-warehouse me-1 text-muted"></i>
                                                {{ $venta->bodega->nombrebodega ?? 'Bodega #' . $venta->bodega_id }}
                                            </span>
                                        </td>
                                        <td class="py-3" style="font-size: 13px; color: #475569;">
                                            {{ $venta->ciudad ?? '—' }}
                                        </td>
                                        <td class="py-3">
                                            @if($venta->tipo_pago === 'Crédito')
                                                <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(245, 158, 11, 0.12); color: #b45309; border: 1px solid rgba(245, 158, 11, 0.3); font-size: 11.5px;">
                                                    <i class="fas fa-clock me-1"></i> Crédito
                                                </span>
                                            @elseif($venta->tipo_pago === 'Contado')
                                                <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(16, 185, 129, 0.12); color: #047857; border: 1px solid rgba(16, 185, 129, 0.3); font-size: 11.5px;">
                                                    <i class="fas fa-money-bill-wave me-1"></i> Contado
                                                </span>
                                            @else
                                                <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(99, 102, 241, 0.12); color: #4338ca; border: 1px solid rgba(99, 102, 241, 0.3); font-size: 11.5px;">
                                                    <i class="fas fa-credit-card me-1"></i> {{ $venta->tipo_pago }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-end font-monospace fw-bold text-success" style="font-size: 14px;">
                                            ${{ number_format($venta->total_venta, 2) }}
                                        </td>
                                        <td class="py-3 text-center pe-4">
                                            <a href="{{ route('venta.show', $venta->id) }}" 
                                               class="btn btn-sm btn-outline-info rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-xs" 
                                               style="width: 32px; height: 32px;" title="Ver detalle de la venta">
                                                <i class="fas fa-eye" style="font-size: 12px;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="py-3 text-muted">
                                                <i class="fas fa-shopping-cart d-block mb-2" style="font-size: 32px; opacity: 0.3;"></i>
                                                <h6 class="fw-bold mb-1">Sin ventas registradas</h6>
                                                <p class="mb-3" style="font-size: 13px;">Este cliente aún no registra órdenes en el sistema.</p>
                                                @if(isset($userBodegaId) && $userBodegaId)
                                                    <a href="{{ route('venta.create', $userBodegaId) }}" class="btn btn-sm btn-primary rounded-pill px-4">
                                                        <i class="fas fa-cart-plus me-1"></i> Registrar Primera Venta
                                                    </a>
                                                @else
                                                    <a href="{{ route('venta.index') }}" class="btn btn-sm btn-primary rounded-pill px-4">
                                                        <i class="fas fa-cash-register me-1"></i> Ir a Ventas
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
