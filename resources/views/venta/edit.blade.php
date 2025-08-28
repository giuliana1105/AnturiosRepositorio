@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Editar Venta #{{ $venta->id }}</h3>
    <form method="POST" action="{{ route('venta.update', $venta->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Fecha</label>
            <input type="date" name="fecha" class="form-control" value="{{ $venta->fecha ? \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') : '' }}">
        </div>
        <div class="mb-3">
            <label>Cliente</label>
            <input type="text" name="cliente" class="form-control" value="{{ old('cliente', $venta->cliente) }}" required>
        </div>
        <div class="mb-3">
            <label>Ciudad</label>
            <select name="ciudad" class="form-control" required>
                <option value="">Seleccione una ciudad</option>
                @foreach([
                    'Ambato','Arajuno','Archidona','Atacames','Atuntaqui','Azogues','Babahoyo','Baeza','Bahía de Caráquez','Balao','Balsas','Balzar','Baños de Agua Santa','Bucay','Calceta','Carlos Julio Arosemena Tola','Catarama','Chone','Coca','Colimes','Coronel Marcelino Maridueña','Cotacachi','Cuenca','Daule','Durán','El Chaco','El Empalme','El Guabo','El Triunfo','Esmeraldas','Gualaquiza','Guaranda','Guayaquil','Huaquillas','Ibarra','Isidro Ayora','Jama','Jujan','La Concordia','La Libertad','Lago Agrio (Nueva Loja)','Latacunga','Limones','Logroño','Loja','Lomas de Sargentillo','Macas','Machala','Manta','Mera','Milagro','Montecristi','Muisne','Naranjal','Nobol','Nuevo Rocafuerte','Otavalo','Paján','Palestina','Palora','Pasaje','Pedernales','Pedro Carbo','Pichincha (ciudad homónima)','Pimampiro','Piñas','Playas (General Villamil)','Portovelo','Portoviejo','Puerto Ayora','Puerto Baquerizo Moreno','Puerto El Carmen de Putumayo','Puerto López','Puerto Villamil','Puyo','Quevedo','Quinindé','Quito (capital)','Riobamba','Rioverde','Rocafuerte','San Lorenzo','San Vicente','Santa Rosa','Santo Domingo (Santo Domingo de los Tsáchilas)','Salinas','Samborondón','Santa Elena','Simón Bolívar','Sucre','Sucúa','Tarapoa','Tena','Tosagua','Tulcán','Urcuquí','Valencia','Ventanas','Vinces','Yaguachi','Yantzaza','Zamora','Zaruma'
                ] as $ciudad)
                    <option value="{{ $ciudad }}" {{ old('ciudad', $venta->ciudad) == $ciudad ? 'selected' : '' }}>{{ $ciudad }}</option>
                @endforeach
            </select>
        </div>
        <div id="productos-container">
            @if($venta->detalles && count($venta->detalles) > 0)
                @foreach($venta->detalles as $i => $detalle)
                <div class="row align-items-end mb-3 row-producto">
                    <div class="col-md-4">
                        <label>Producto</label>
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
                    </div>
                    <div class="col-md-2">
                        <label>Cantidad</label>
                        <input type="number" name="cantidad[]" class="form-control cantidad-input" 
                               min="1" value="{{ old('cantidad.'.$i, $detalle->cantidad) }}" readonly>
                    </div>
                    <div class="col-md-2">
                        <label>Tipo Empaque</label>
                        <input type="text" name="tipoempaque[]" class="form-control empaque-input" 
                               value="{{ old('tipoempaque.'.$i, $detalle->tipoempaque) }}" readonly>
                    </div>
                    <div class="col-md-2">
                        <label>Precio Unitario</label>
                        <input type="number" name="precio_unitario[]" class="form-control precio-unitario-input" 
                               step="0.01" min="0.01" value="{{ old('precio_unitario.'.$i, $detalle->precio_unitario) }}" readonly>
                    </div>
                    <div class="col-md-1">
                        <label>Precio Total</label>
                        <input type="number" name="precio_total[]" class="form-control precio-total-input" 
                               value="{{ old('precio_total.'.$i, $detalle->precio_total) }}" readonly>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
        <div class="mb-3">
            <label>Total venta</label>
            <input type="number" name="total_venta" class="form-control" id="total-venta" 
                   value="{{ old('total_venta', $venta->total_venta) }}" readonly>
        </div>
        <div class="mb-3">
            <label>Tipo de pago</label>
            <select name="tipo_pago" class="form-control" id="tipo-pago-select" required>
                <option value="Efectivo" {{ old('tipo_pago', $venta->tipo_pago) == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                <option value="Transferencia" {{ old('tipo_pago', $venta->tipo_pago) == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                <option value="Crédito" {{ old('tipo_pago', $venta->tipo_pago) == 'Crédito' ? 'selected' : '' }}>Crédito</option>
                <option value="Cheque" {{ old('tipo_pago', $venta->tipo_pago) == 'Cheque' ? 'selected' : '' }}>Cheque</option>
            </select>
        </div>
        @php
            $mostrarAbonos = old('tipo_pago', $venta->tipo_pago) == 'Crédito';
        @endphp
        <div id="abonos-section" style="display: {{ $mostrarAbonos ? 'block' : 'none' }};">
            <div id="abonos-container">
                @if(isset($abonos) && count($abonos) > 0)
                    @foreach($abonos as $j => $abono)
                    <div class="row align-items-end mb-2 row-abono">
                        <div class="col-md-3">
                            <label>Abono</label>
                            <input type="number" name="abono[]" class="form-control abono-input" 
                                   min="0" step="0.01" value="{{ old('abono.'.$j, $abono->abono) }}" 
                                   {{ $loop->index > 0 ? '' : 'readonly' }}>
                        </div>
                        <div class="col-md-3">
                            <label>Fecha</label>
                            <input type="date" name="fecha_abono[]" class="form-control fecha-abono-input" 
                                   value="{{ old('fecha_abono.'.$j, \Carbon\Carbon::parse($abono->fecha)->format('Y-m-d')) }}" 
                                   {{ $loop->index > 0 ? '' : 'readonly' }}>
                        </div>
                        <div class="col-md-3">
                            <label>Tipo de pago</label>
                            @if($loop->index == 0)
                                <input type="text" name="tipo_pago_abono[]" class="form-control" 
                                       value="{{ old('tipo_pago_abono.'.$j, $abono->tipo_pago) }}" readonly>
                            @else
                                <select name="tipo_pago_abono[]" class="form-control">
                                    <option value="Cheque" {{ old('tipo_pago_abono.'.$j, $abono->tipo_pago) == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="Efectivo" {{ old('tipo_pago_abono.'.$j, $abono->tipo_pago) == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                                    <option value="Transferencia" {{ old('tipo_pago_abono.'.$j, $abono->tipo_pago) == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                                </select>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if($loop->index > 0)
                            <button type="button" class="btn btn-success btn-sm btn-add-abono">+</button>
                            <button type="button" class="btn btn-danger btn-sm btn-remove-abono">-</button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="row align-items-end mb-2 row-abono">
                        <div class="col-md-3">
                            <label>Abono</label>
                            <input type="number" name="abono[]" class="form-control abono-input" min="0" step="0.01" value="{{ old('abono.0', 0) }}">
                        </div>
                        <div class="col-md-3">
                            <label>Fecha</label>
                            <input type="date" name="fecha_abono[]" class="form-control fecha-abono-input" value="{{ old('fecha_abono.0', now()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label>Tipo de pago</label>
                            <select name="tipo_pago_abono[]" class="form-control">
                                <option value="Cheque" {{ old('tipo_pago_abono.0') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="Efectivo" {{ old('tipo_pago_abono.0') == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                                <option value="Transferencia" {{ old('tipo_pago_abono.0') == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success btn-sm btn-add-abono">+</button>
                            <button type="button" class="btn btn-danger btn-sm btn-remove-abono">-</button>
                        </div>
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <label>Saldo</label>
                <input type="number" name="saldo" class="form-control" id="saldo-venta" 
                       value="{{ old('saldo', $venta->total_venta - (isset($abonos) ? collect($abonos)->sum('abono') : 0)) }}" readonly>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Guardar cambios</button>
        <a href="{{ route('venta.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection

@section('scripts')
    @include('venta.partials.scripts-productos-abonos')
@endsection