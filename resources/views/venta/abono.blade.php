@extends('layouts.app')

@php
    $cargo = auth()->user()->cargoNombre();
    $totalAbonado = $abonos->sum('abono');
    $porcentajePagado = $venta->total_venta > 0 ? min(100, round(($totalAbonado / $venta->total_venta) * 100)) : 100;
@endphp

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de la página -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h3 class="fw-bold mb-0">Registrar Abono &bull; Venta #{{ $venta->nro_venta ?? $venta->id }}</h3>
                <span class="badge rounded-pill px-3 py-1 fw-semibold" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 12px;">
                    <i class="fas fa-clock me-1"></i>Crédito Pendiente
                </span>
            </div>
            <p class="page-subtitle mb-0 text-muted">Cliente: <strong>{{ $venta->cliente }}</strong> &bull; Bodega: <strong>{{ $venta->bodega->nombrebodega ?? $venta->bodega_id }}</strong></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('venta.show', $venta->id) }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Volver al Detalle
            </a>
            <a href="{{ route('venta.index.bodega', $venta->bodega_id) }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                <i class="fas fa-list me-1"></i> Ver Todas las Ventas
            </a>
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

    <!-- Tarjetas de Resumen KPI del Crédito -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                        <i class="fas fa-receipt fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total Factura</small>
                        <h4 class="fw-bold mb-0 text-dark">${{ number_format($venta->total_venta, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                        <i class="fas fa-check-circle fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Monto Abonado</small>
                        <h4 class="fw-bold mb-0 text-success">${{ number_format($totalAbonado, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.12); color: #dc2626;">
                        <i class="fas fa-balance-scale fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Saldo Pendiente Actual</small>
                        <h4 class="fw-bold mb-0 text-danger">${{ number_format($saldo, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Columna Izquierda: Formulario para Agregar Nuevo Abono -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="border-top: 3px solid var(--primary) !important;">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                        <i class="fas fa-plus-circle" style="font-size: 13px;"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-dark">Registrar Nuevo Abono</h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('venta.abono.store', $venta->id) }}" id="form-nuevo-abono">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                                <i class="fas fa-dollar-sign text-success me-1"></i> Monto del Abono <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted fw-bold">$</span>
                                <input type="number" name="abono" class="form-control rounded-end-3 fs-5 font-mono fw-bold" 
                                       min="0.01" max="{{ $saldo }}" step="0.01" required 
                                       placeholder="0.00" value="{{ old('abono') }}" autofocus>
                            </div>
                            <small class="text-muted mt-1 d-block" style="font-size: 12px;">
                                Máximo abonable permitido: <strong>${{ number_format($saldo, 2) }}</strong>
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                                <i class="far fa-calendar-alt text-muted me-1"></i> Fecha del Abono <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="fecha_abono" class="form-control rounded-3" 
                                   value="{{ old('fecha_abono', now()->format('Y-m-d')) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark" style="font-size: 13.5px;">
                                <i class="fas fa-wallet text-muted me-1"></i> Método / Tipo de Pago <span class="text-danger">*</span>
                            </label>
                            <select name="tipo_pago_abono" class="form-select rounded-3" required>
                                <option value="">Seleccione forma de pago...</option>
                                <option value="Efectivo" {{ old('tipo_pago_abono') == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                                <option value="Transferencia" {{ old('tipo_pago_abono') == 'Transferencia' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                <option value="Cheque" {{ old('tipo_pago_abono') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill w-100 py-2.5 shadow-sm fw-bold">
                            <i class="fas fa-check-circle me-1"></i> Procesar Abono
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Historial y Barra de Amortización -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                            <i class="fas fa-history" style="font-size: 13px;"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark">Historial de Pagos y Amortizaciones</h6>
                    </div>
                    <span class="badge rounded-pill bg-light text-muted border px-2.5 py-1" style="font-size: 11.5px;">
                        {{ $abonos->count() }} {{ $abonos->count() === 1 ? 'abono' : 'abonos' }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <!-- Barra de Progreso del Pago -->
                    <div class="mb-4 p-3 rounded-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-dark" style="font-size: 13px;">Nivel de Amortización</span>
                            <span class="badge rounded-pill {{ $saldo <= 0 ? 'bg-success' : 'bg-warning text-dark' }} px-2.5 py-1 font-mono fw-bold" style="font-size: 12px;">
                                {{ $porcentajePagado }}% Pagado
                            </span>
                        </div>
                        <div class="progress rounded-pill" style="height: 10px;">
                            <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: {{ $porcentajePagado }}%;" aria-valuenow="{{ $porcentajePagado }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 font-mono" style="font-size: 12px;">
                            <span class="text-muted">Abonado: <strong class="text-success">${{ number_format($totalAbonado, 2) }}</strong></span>
                            <span class="text-muted">Resta pagar: <strong class="text-danger">${{ number_format($saldo, 2) }}</strong></span>
                        </div>
                    </div>

                    <!-- Tabla de Abonos Realizados -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                <tr>
                                    <th class="ps-3 py-2 text-uppercase text-muted fw-bold" style="font-size: 11px;">Monto</th>
                                    <th class="py-2 text-uppercase text-muted fw-bold" style="font-size: 11px;">Fecha</th>
                                    <th class="py-2 text-uppercase text-muted fw-bold text-end pe-3" style="font-size: 11px;">Método de Pago</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @forelse($abonos as $abono)
                                    <tr style="transition: background-color 0.15s ease;">
                                        <td class="ps-3 py-2.5">
                                            <span class="font-mono fw-bold text-success fs-6">${{ number_format($abono->abono, 2) }}</span>
                                        </td>
                                        <td class="py-2.5 font-mono text-muted" style="font-size: 12.5px;">
                                            <i class="far fa-calendar-alt me-1 text-muted"></i>{{ $abono->fecha }}
                                        </td>
                                        <td class="py-2.5 text-end pe-3">
                                            <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1" style="font-size: 11px;">
                                                <i class="fas fa-wallet me-1 text-primary"></i>{{ $abono->tipo_pago }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted" style="font-size: 13px;">
                                            <i class="fas fa-info-circle me-1"></i> Aún no se han registrado abonos en este crédito.
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
