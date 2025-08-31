<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Ventas</title>
    <style>
        @page { size: A4 landscape; }
        body { font-family: sans-serif; color: #222; }
        .venta-box { border: 1px solid #ccc; padding: 16px; margin-bottom: 24px; }
        .venta-header { display: flex; justify-content: space-between; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #ccc; padding: 4px; }
        th { background: #f5f5f5; }
        .venta-total { text-align: right; font-size: 1.1em; }
    </style>
</head>
<body>
@if(request('dia'))
    <h3 style="text-align:center; margin-bottom:20px;">
        Reporte diario - {{ \Carbon\Carbon::parse(request('dia'))->format('d/m/Y') }}
    </h3>
    <table style="width:100%; border-collapse:collapse; margin-bottom:24px;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="border:1px solid #ccc; padding:4px;">Nro. Fac</th>
                <th style="border:1px solid #ccc; padding:4px;">Cliente</th>
                <th style="border:1px solid #ccc; padding:4px;">Total venta</th>
                <th style="border:1px solid #ccc; padding:4px;">Forma de pago</th>
                <th style="border:1px solid #ccc; padding:4px;">Abono</th>
                <th style="border:1px solid #ccc; padding:4px;">Forma de pago/abonos</th>
            </tr>
        </thead>
        <tbody>
        @php
            // Para los totales finales
            $totalEfectivo = 0;
            $totalTransferencia = 0;
            $totalCheque = 0;
        @endphp
        @foreach($ventas as $venta)
            @php
                // Abonos del día
                $abonosDia = [];
                if ($venta->tipo_pago === 'Crédito' && isset($venta->abonos)) {
                    foreach ($venta->abonos as $abono) {
                        if (\Carbon\Carbon::parse($abono->fecha)->format('Y-m-d') === request('dia')) {
                            $abonosDia[] = $abono;
                        }
                    }
                }
                $abonosCount = count($abonosDia);
            @endphp

            {{-- Fila principal de la venta --}}
            <tr>
                <td style="border:1px solid #ccc; padding:4px;">{{ $venta->nro_venta }}</td>
                <td style="border:1px solid #ccc; padding:4px;">{{ $venta->cliente }}</td>
                <td style="border:1px solid #ccc; padding:4px;">${{ number_format($venta->total_venta, 2) }}</td>
                <td style="border:1px solid #ccc; padding:4px;">{{ $venta->tipo_pago }}</td>
                <td style="border:1px solid #ccc; padding:4px;">
                    @if($venta->tipo_pago === 'Crédito' && $abonosCount > 0)
                        ${{ number_format($abonosDia[0]->abono, 2) }}
                    @else
                        -
                    @endif
                </td>
                <td style="border:1px solid #ccc; padding:4px;">
                    @if($venta->tipo_pago === 'Crédito' && $abonosCount > 0)
                        {{ $abonosDia[0]->tipo_pago }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            {{-- Filas adicionales para abonos extra (si hay más de uno) --}}
            @if($venta->tipo_pago === 'Crédito' && $abonosCount > 1)
                @for($i = 1; $i < $abonosCount; $i++)
                    <tr>
                        <td style="border:1px solid #ccc; padding:4px;">{{ $venta->nro_venta }}</td>
                        <td style="border:1px solid #ccc; padding:4px;">{{ $venta->cliente }}</td>
                        <td style="border:1px solid #ccc; padding:4px;">-</td>
                        <td style="border:1px solid #ccc; padding:4px;">-</td>
                        <td style="border:1px solid #ccc; padding:4px;">${{ number_format($abonosDia[$i]->abono, 2) }}</td>
                        <td style="border:1px solid #ccc; padding:4px;">{{ $abonosDia[$i]->tipo_pago }}</td>
                    </tr>
                @endfor
            @endif

            {{-- Sumar totales para ventas directas --}}
            @php
                if($venta->tipo_pago === 'Efectivo') {
                    $totalEfectivo += $venta->total_venta;
                }
                if($venta->tipo_pago === 'Transferencia') {
                    $totalTransferencia += $venta->total_venta;
                }
                if($venta->tipo_pago === 'Cheque') {
                    $totalCheque += $venta->total_venta;
                }
                // Sumar abonos del día
                if($venta->tipo_pago === 'Crédito' && $abonosCount > 0) {
                    foreach($abonosDia as $abono) {
                        if($abono->tipo_pago === 'Efectivo') $totalEfectivo += $abono->abono;
                        if($abono->tipo_pago === 'Transferencia') $totalTransferencia += $abono->abono;
                        if($abono->tipo_pago === 'Cheque') $totalCheque += $abono->abono;
                    }
                }
            @endphp
        @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <span style="font-weight:bold; text-decoration: underline;">Total entregar:</span><br><br>
        EFECTIVO: ${{ number_format($totalEfectivo, 2) }}<br>
        TRANSFERENCIA: ${{ number_format($totalTransferencia, 2) }}<br>
        CHEQUE: ${{ number_format($totalCheque, 2) }}
    </div>

@elseif(request('fecha_inicio') && request('fecha_fin'))
    @php
        $inicio = \Carbon\Carbon::parse(request('fecha_inicio'));
        $fin = \Carbon\Carbon::parse(request('fecha_fin'));
        $totalEfectivo = 0;
        $totalTransferencia = 0;
        $totalCheque = 0;
    @endphp

    @for($fecha = $inicio->copy(); $fecha->lte($fin); $fecha->addDay())
        @php
            $ventasDia = $ventas->filter(function($venta) use ($fecha) {
                return \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') === $fecha->format('Y-m-d');
            });
            $efectivoDia = 0;
            $transferenciaDia = 0;
            $chequeDia = 0;
        @endphp

        @if($ventasDia->count())
            <h3 style="text-align:center; margin-bottom:20px;">
                Reporte diario - {{ $fecha->format('d/m/Y') }}
            </h3>
            <table style="width:100%; border-collapse:collapse; margin-bottom:24px;">
                <thead>
                    <tr style="background:#f5f5f5;">
                        <th style="border:1px solid #ccc; padding:4px;">Nro. Fac</th>
                        <th style="border:1px solid #ccc; padding:4px;">Cliente</th>
                        <th style="border:1px solid #ccc; padding:4px;">Total venta</th>
                        <th style="border:1px solid #ccc; padding:4px;">Forma de pago</th>
                        <th style="border:1px solid #ccc; padding:4px;">Abono</th>
                        <th style="border:1px solid #ccc; padding:4px;">Forma de pago/abonos</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($ventasDia as $venta)
                    @php
                        $abonosDia = [];
                        if ($venta->tipo_pago === 'Crédito' && isset($venta->abonos)) {
                            foreach ($venta->abonos as $abono) {
                                if (\Carbon\Carbon::parse($abono->fecha)->format('Y-m-d') === $fecha->format('Y-m-d')) {
                                    $abonosDia[] = $abono;
                                }
                            }
                        }
                        $abonosCount = count($abonosDia);
                    @endphp

                    <tr>
                        <td style="border:1px solid #ccc; padding:4px;">{{ $venta->nro_venta }}</td>
                        <td style="border:1px solid #ccc; padding:4px;">{{ $venta->cliente }}</td>
                        <td style="border:1px solid #ccc; padding:4px;">${{ number_format($venta->total_venta, 2) }}</td>
                        <td style="border:1px solid #ccc; padding:4px;">{{ $venta->tipo_pago }}</td>
                        <td style="border:1px solid #ccc; padding:4px;">
                            @if($venta->tipo_pago === 'Crédito' && $abonosCount > 0)
                                ${{ number_format($abonosDia[0]->abono, 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="border:1px solid #ccc; padding:4px;">
                            @if($venta->tipo_pago === 'Crédito' && $abonosCount > 0)
                                {{ $abonosDia[0]->tipo_pago }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @if($venta->tipo_pago === 'Crédito' && $abonosCount > 1)
                        @for($i = 1; $i < $abonosCount; $i++)
                            <tr>
                                <td style="border:1px solid #ccc; padding:4px;">{{ $venta->nro_venta }}</td>
                                <td style="border:1px solid #ccc; padding:4px;">{{ $venta->cliente }}</td>
                                <td style="border:1px solid #ccc; padding:4px;">-</td>
                                <td style="border:1px solid #ccc; padding:4px;">-</td>
                                <td style="border:1px solid #ccc; padding:4px;">${{ number_format($abonosDia[$i]->abono, 2) }}</td>
                                <td style="border:1px solid #ccc; padding:4px;">{{ $abonosDia[$i]->tipo_pago }}</td>
                            </tr>
                        @endfor
                    @endif

                    @php
                        if($venta->tipo_pago === 'Efectivo') {
                            $efectivoDia += $venta->total_venta;
                        }
                        if($venta->tipo_pago === 'Transferencia') {
                            $transferenciaDia += $venta->total_venta;
                        }
                        if($venta->tipo_pago === 'Cheque') {
                            $chequeDia += $venta->total_venta;
                        }
                        if($venta->tipo_pago === 'Crédito' && $abonosCount > 0) {
                            foreach($abonosDia as $abono) {
                                if($abono->tipo_pago === 'Efectivo') $efectivoDia += $abono->abono;
                                if($abono->tipo_pago === 'Transferencia') $transferenciaDia += $abono->abono;
                                if($abono->tipo_pago === 'Cheque') $chequeDia += $abono->abono;
                            }
                        }
                    @endphp
                @endforeach
                </tbody>
            </table>
            <div style="margin-bottom: 30px;">
                <span style="font-weight:bold; text-decoration: underline;">Total entregar del día:</span><br><br>
                EFECTIVO: ${{ number_format($efectivoDia, 2) }}<br>
                TRANSFERENCIA: ${{ number_format($transferenciaDia, 2) }}<br>
                CHEQUE: ${{ number_format($chequeDia, 2) }}
            </div>
            @php
                $totalEfectivo += $efectivoDia;
                $totalTransferencia += $transferenciaDia;
                $totalCheque += $chequeDia;
            @endphp
        @endif
    @endfor

    <div style="margin-top: 30px; border-top:1px solid #ccc; padding-top:10px;">
        <span style="font-weight:bold; text-decoration: underline;">
            Reporte {{ \Carbon\Carbon::parse(request('fecha_inicio'))->format('d-m-Y') }} / {{ \Carbon\Carbon::parse(request('fecha_fin'))->format('d-m-Y') }}
        </span><br><br>
        EFECTIVO: ${{ number_format($totalEfectivo, 2) }}<br>
        TRANSFERENCIA: ${{ number_format($totalTransferencia, 2) }}<br>
        CHEQUE: ${{ number_format($totalCheque, 2) }}
    </div>

@else
    <h3 style="text-align:center; margin-bottom:20px;">
        @if(request('tipo_pago'))
            Reporte - {{ ucwords(str_replace('_', ' ', request('tipo_pago'))) }}
        @elseif(request('ciudad'))
            Reporte - {{ request('ciudad') }}
        @else
            Reporte de Ventas
        @endif
    </h3>

    @foreach($ventas as $venta)
        <div class="venta-box">
            <div class="venta-header">
                <div>
                    <strong>Cliente:</strong> {{ $venta->cliente }}<br>
                    <strong>Ciudad:</strong> {{ $venta->ciudad }}
                </div>
                <div>
                    <strong>Forma de pago:</strong> {{ $venta->tipo_pago }}<br>
                    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') }}
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th>Producto</th>
                        <th>Empaque</th>
                        <th>Precio</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($venta->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>{{ $detalle->producto->codigo }} - {{ $detalle->producto->nombre }}</td>
                        <td>{{ $detalle->tipoempaque ?? $detalle->empaque ?? '-' }}</td>
                        <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td>${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="venta-total">
                <strong>Total venta:</strong> ${{ number_format($venta->total_venta, 2) }}
            </div>
            @if($venta->tipo_pago === 'Crédito')
                @php
                    $saldo = $venta->total_venta;
                    if(isset($venta->abonos)) {
                        foreach($venta->abonos as $abono) {
                            $saldo -= $abono->abono;
                        }
                    }
                @endphp
                <div class="venta-total">
                    <strong>Saldo actual:</strong> ${{ number_format($saldo, 2) }}
                </div>
            @endif
            @if($venta->tipo_pago === 'Crédito' && isset($venta->abonos) && count($venta->abonos) > 0)
                <div style="margin-top: 10px;">
                    <strong>Abonos</strong>
                    <table>
                        <thead>
                            <tr>
                                <th>Abono</th>
                                <th>Fecha</th>
                                <th>Tipo de pago</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($venta->abonos as $abono)
                            <tr>
                                <td>${{ number_format($abono->abono, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($abono->fecha)->format('Y-m-d') }}</td>
                                <td>{{ $abono->tipo_pago }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endforeach
@endif
</body>
</html>