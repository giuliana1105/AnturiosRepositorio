@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de la página -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Editar Venta #{{ $venta->nro_venta ?? $venta->id }}</h3>
            <p class="page-subtitle mb-0 text-muted">Bodega: <strong>{{ $bodega->nombrebodega }}</strong> &bull; Modificación de datos de entrega y condiciones</p>
        </div>
        <a href="{{ route('venta.show', $venta->id) }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver al Detalle
        </a>
    </div>

    <form method="POST" action="{{ route('venta.update', $venta->id) }}">
        @csrf
        @method('PUT')
        
        <!-- Información Básica de la Venta -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                    <i class="fas fa-info-circle" style="font-size: 13px;"></i>
                </div>
                <h6 class="fw-bold mb-0 text-dark">Información de la Venta</h6>
            </div>
            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-calendar me-2"></i>Fecha
                                        </label>
                                        <input type="date" name="fecha" class="form-control" 
                                               value="{{ $venta->fecha ? \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') : '' }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label fw-bold mb-0">
                                                <i class="fas fa-user me-2"></i>Cliente <span class="text-danger">*</span>
                                            </label>
                                            <a href="{{ route('clientes.create') }}" target="_blank" class="badge bg-primary text-decoration-none">
                                                <i class="fas fa-plus"></i> Nuevo
                                            </a>
                                        </div>
                                        <select name="cliente_id" id="cliente_id" class="form-control" required>
                                            <option value="">Seleccione un cliente registrado...</option>
                                            @foreach($clientes as $c)
                                                <option value="{{ $c->id }}" 
                                                        {{ (old('cliente_id', $venta->cliente_id) == $c->id || (empty($venta->cliente_id) && trim(strtolower($venta->cliente)) == trim(strtolower($c->nombre_completo)))) ? 'selected' : '' }}>
                                                    {{ $c->nombre_completo }}{{ $c->nombre_empresa ? ' - ' . $c->nombre_empresa : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-map-marker-alt me-2"></i>Ciudad
                                        </label>
                                        <select name="ciudad" class="form-control" required>
                                            <option value="">Seleccione una ciudad</option>
                                            @foreach([
                                                'Ambato','Arajuno','Archidona','Atacames','Atuntaqui','Azogues','Babahoyo','Baeza','Bahía de Caráquez','Balao','Balsas','Balzar','Baños de Agua Santa','Bucay','Calceta','Carlos Julio Arosemena Tola','Catarama','Chone','Coca','Colimes','Coronel Marcelino Maridueña','Cotacachi','Cuenca','Daule','Durán','El Chaco','El Empalme','El Guabo','El Triunfo','Esmeraldas','Gualaquiza','Guaranda','Guayaquil','Huaquillas','Ibarra','Isidro Ayora','Jama','Jujan','La Concordia','La Libertad','Lago Agrio (Nueva Loja)','Latacunga','Limones','Logroño','Loja','Lomas de Sargentillo','Macas','Machala','Manta','Mera','Milagro','Montecristi','Muisne','Naranjal','Nobol','Nuevo Rocafuerte','Otavalo','Paján','Palestina','Palora','Pasaje','Pedernales','Pedro Carbo','Pichincha (ciudad homónima)','Pimampiro','Piñas','Playas (General Villamil)','Portovelo','Portoviejo','Puerto Ayora','Puerto Baquerizo Moreno','Puerto El Carmen de Putumayo','Puerto López','Puerto Villamil','Puyo','Quevedo','Quinindé','Quito (capital)','Riobamba','Rioverde','Rocafuerte','San Lorenzo','San Vicente','Santa Rosa','Santo Domingo (Santo Domingo de los Tsáchilas)','Salinas','Samborondón','Santa Elena','Simón Bolívar','Sucre','Sucúa','Tarapoa','Tena','Tosagua','Tulcán','Urcuquí','Valencia','Ventanas','Vinces','Yaguachi','Yantzaza','Zamora','Zaruma'
                                            ] as $ciudad)
                                                <option value="{{ $ciudad }}" {{ old('ciudad', $venta->ciudad) == $ciudad ? 'selected' : '' }}>{{ $ciudad }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Productos -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                                    <i class="fas fa-boxes" style="font-size: 13px;"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark">Productos de la Venta</h6>
                            </div>
                            <div class="card-body p-4">
                                <div id="productos-container">
                                    @if($venta->detalles && count($venta->detalles) > 0)
                                        @foreach($venta->detalles as $i => $detalle)
                                        <div class="row align-items-end mb-3 row-producto p-3 bg-white rounded-4 border">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-cube me-2"></i>Producto
                                                </label>
                                                @if($detalle->es_extra)
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" value="{{ $detalle->nombre_extra }}" readonly>
                                                        <span class="input-group-text text-white" style="background-color: #0097a7; font-size: 11px;">Extra</span>
                                                    </div>
                                                    <input type="hidden" name="producto_id[]" value="">
                                                @else
                                                    <select name="producto_id[]" class="form-control producto-select" disabled>
                                                        <option value="">Seleccione un producto</option>
                                                        @foreach($productos as $prod)
                                                            <option value="{{ $prod['codigo'] }}" 
                                                                    data-stock="{{ $prod['stock'] }}" 
                                                                    data-empaque="{{ $prod['tipoempaque'] }}"
                                                                    {{ old('producto_id.'.$i, $detalle->producto_id) == $prod['codigo'] ? 'selected' : '' }}>
                                                                {{ $prod['codigo'] }} - {{ $prod['nombre'] }} (Stock: {{ $prod['stock'] }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="producto_id[]" value="{{ $detalle->producto_id }}">
                                                @endif
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-calculator me-2"></i>Cantidad
                                                </label>
                                                <input type="number" name="cantidad[]" class="form-control cantidad-input text-center" 
                                                       min="1" value="{{ old('cantidad.'.$i, $detalle->cantidad) }}" readonly>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-box me-2"></i>Tipo Empaque
                                                </label>
                                                <input type="text" name="tipoempaque[]" class="form-control empaque-input text-center" 
                                                       value="{{ old('tipoempaque.'.$i, $detalle->tipoempaque) }}" readonly>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-tag me-2"></i>Precio Unitario
                                                </label>
                                                <input type="number" name="precio_unitario[]" class="form-control precio-unitario-input text-center" 
                                                       step="0.01" min="0.01" value="{{ old('precio_unitario.'.$i, $detalle->precio_unitario) }}" readonly>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-dollar-sign me-2"></i>Precio Total
                                                </label>
                                                <input type="number" name="precio_total[]" class="form-control precio-total-input text-center fw-bold" 
                                                       value="{{ old('precio_total.'.$i, $detalle->precio_total) }}" readonly>
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Total y Forma de Pago -->
                        <div class="card mb-4 border-0 bg-brand-light">
                            <div class="card-header rounded-top-4">
                                <h5 class="mb-0">
                                    <i class="fas fa-calculator me-2"></i> Total y Forma de Pago
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-dollar-sign me-2"></i>Total venta
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white">
                                                <i class="fas fa-dollar-sign"></i>
                                            </span>
                                            <input type="number" name="total_venta" class="form-control fw-bold text-success" id="total-venta" 
                                                   value="{{ old('total_venta', $venta->total_venta) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-credit-card me-2"></i>Tipo de pago
                                        </label>
                                        <select name="tipo_pago" class="form-control" id="tipo-pago-select" required>
                                            <option value="Efectivo" {{ old('tipo_pago', $venta->tipo_pago) == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                                            <option value="Transferencia" {{ old('tipo_pago', $venta->tipo_pago) == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                                            <option value="Crédito" {{ old('tipo_pago', $venta->tipo_pago) == 'Crédito' ? 'selected' : '' }}>Crédito</option>
                                            <option value="Cheque" {{ old('tipo_pago', $venta->tipo_pago) == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $mostrarAbonos = old('tipo_pago', $venta->tipo_pago) == 'Crédito';
                        @endphp

                        <!-- Sección de Abonos (solo si es crédito) -->
                        <div id="abonos-section" style="display: {{ $mostrarAbonos ? 'block' : 'none' }};">
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; background: rgba(245, 158, 11, 0.12); color: #d97706;">
                                        <i class="fas fa-hand-holding-usd" style="font-size: 13px;"></i>
                                    </div>
                                    <h6 class="fw-bold mb-0 text-dark">Gestión de Abonos y Amortizaciones</h6>
                                </div>
                                <div class="card-body p-4">
                                    <div id="abonos-container">
                                        @if(isset($abonos) && count($abonos) > 0)
                                            @foreach($abonos as $j => $abono)
                                            <div class="row align-items-end mb-3 row-abono p-3 bg-white rounded-4 border">
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-money-bill me-2"></i>Abono
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-warning text-white">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </span>
                                                        <input type="number" name="abono[]" class="form-control abono-input fw-bold" 
                                                               min="0" step="0.01" value="{{ old('abono.'.$j, $abono->abono) }}" 
                                                               {{ $loop->index > 0 ? '' : 'readonly' }}>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-calendar me-2"></i>Fecha
                                                    </label>
                                                    <input type="date" name="fecha_abono[]" class="form-control fecha-abono-input" 
                                                           value="{{ old('fecha_abono.'.$j, \Carbon\Carbon::parse($abono->fecha)->format('Y-m-d')) }}" 
                                                           {{ $loop->index > 0 ? '' : 'readonly' }}>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-credit-card me-2"></i>Tipo de pago
                                                    </label>
                                                    @if($loop->index == 0)
                                                        <input type="text" name="tipo_pago_abono[]" class="form-control text-center" 
                                                               value="{{ old('tipo_pago_abono.'.$j, $abono->tipo_pago) }}" readonly>
                                                    @else
                                                        <select name="tipo_pago_abono[]" class="form-control">
                                                            <option value="Cheque" {{ old('tipo_pago_abono.'.$j, $abono->tipo_pago) == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                                            <option value="Efectivo" {{ old('tipo_pago_abono.'.$j, $abono->tipo_pago) == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                                                            <option value="Transferencia" {{ old('tipo_pago_abono.'.$j, $abono->tipo_pago) == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                                                        </select>
                                                    @endif
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    @if($loop->index > 0)
                                                        <div class="btn-group w-100" role="group">
                                                            <button type="button" class="btn btn-success btn-sm btn-add-abono rounded-pill">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm btn-remove-abono rounded-pill">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="row align-items-end mb-3 row-abono p-3 bg-white rounded-4 border">
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-money-bill me-2"></i>Abono
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-warning text-white">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </span>
                                                        <input type="number" name="abono[]" class="form-control abono-input" 
                                                               min="0" step="0.01" value="{{ old('abono.0', 0) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-calendar me-2"></i>Fecha
                                                    </label>
                                                    <input type="date" name="fecha_abono[]" class="form-control fecha-abono-input" 
                                                           value="{{ old('fecha_abono.0', now()->format('Y-m-d')) }}">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-bold">
                                                        <i class="fas fa-credit-card me-2"></i>Tipo de pago
                                                    </label>
                                                    <select name="tipo_pago_abono[]" class="form-control">
                                                        <option value="Cheque" {{ old('tipo_pago_abono.0') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                                        <option value="Efectivo" {{ old('tipo_pago_abono.0') == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                                                        <option value="Transferencia" {{ old('tipo_pago_abono.0') == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="btn-group w-100" role="group">
                                                        <button type="button" class="btn btn-success btn-sm btn-add-abono rounded-pill">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-abono rounded-pill">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Saldo -->
                                    <div class="mt-3 p-3 bg-white rounded-4 border border-warning">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h5 class="mb-0 text-warning">
                                                    <i class="fas fa-balance-scale me-2"></i>Saldo:
                                                </h5>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-danger text-white">
                                                        <i class="fas fa-dollar-sign"></i>
                                                    </span>
                                                    <input type="number" name="saldo" class="form-control fw-bold text-danger" id="saldo-venta" 
                                                           value="{{ old('saldo', $venta->total_venta - (isset($abonos) ? collect($abonos)->sum('abono') : 0)) }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-center gap-3">
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4">
                                <i class="fas fa-save me-2"></i>Guardar cambios
                            </button>
                            <a href="{{ route('venta.index.bodega', $venta->bodega_id) }}" class="btn btn-secondary fw-bold rounded-pill px-4">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
    </div>
</div>


@endsection

@section('scripts')
    @include('venta.partials.scripts-productos-abonos')
@endsection