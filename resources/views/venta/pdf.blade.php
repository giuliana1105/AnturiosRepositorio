<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Ventas</title>
    <style>
        @page { size: A4 landscape; margin: 15mm; }
        body {
            font-family: Arial, sans-serif;
            background: #fff;
            margin: 0;
            padding: 0;
            color: #3C3C3C;
            font-size: 12px;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px solid #dc94ca;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .logo-cell {
            width: 40%;
            vertical-align: middle;
        }
        .logo {
            width: 200px;
            height: auto;
        }
        .title-cell {
            width: 60%;
            text-align: right;
            vertical-align: middle;
        }
        .doc-title {
            font-size: 26px;
            font-weight: bold;
            color: #dc94ca;
            margin: 0;
            text-transform: uppercase;
        }
        .doc-subtitle {
            font-size: 16px;
            color: #666;
            margin-top: 5px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }
        table.data-table th {
            background: #fdf0f5;
            color: #880e4f;
            font-weight: bold;
            border-bottom: 2px solid #dc94ca;
        }
        table.data-table tr:nth-child(even) td {
            background: #fafafa;
        }
        .venta-box { 
            border: 1px solid #e2e8f0; 
            margin-bottom: 30px; 
            border-radius: 8px;
            overflow: hidden;
            page-break-inside: avoid;
        }
        .venta-header { 
            background: #fdf0f5;
            padding: 12px 16px;
            border-bottom: 2px solid #dc94ca;
        }
        .venta-header-table {
            width: 100%;
            border: none;
        }
        .venta-header-table td {
            border: none;
            padding: 0;
        }
        .venta-total { 
            text-align: right; 
            font-size: 1.1em; 
            padding: 10px 16px;
            background: #fafafa;
            border-top: 1px solid #e2e8f0;
            color: #880e4f;
        }
        .totals-box {
            background: #fdf0f5;
            border: 2px solid #dc94ca;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            width: 350px;
            float: right;
            page-break-inside: avoid;
        }
        h3.section-title {
            color: #dc94ca;
            font-size: 16px;
            margin-top: 0;
            margin-bottom: 15px;
            border-bottom: 1px solid #fdf0f5;
            padding-bottom: 5px;
        }
        .footer {
            clear: both;
            margin-top: 40px;
            text-align: center;
            color: #999;
            font-size: 11px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('images/logo-empresa.png') }}" class="logo" alt="Logo Anturios">
            </td>
            <td class="title-cell">
                <h1 class="doc-title">Reporte de Ventas</h1>
                <div class="doc-subtitle">
                    @if(request('dia'))
                        Diario - {{ \Carbon\Carbon::parse(request('dia'))->format('d/m/Y') }}
                    @elseif(request('fecha_inicio') && request('fecha_fin'))
                        Periodo: {{ \Carbon\Carbon::parse(request('fecha_inicio'))->format('d/m/Y') }} al {{ \Carbon\Carbon::parse(request('fecha_fin'))->format('d/m/Y') }}
                    @elseif(request('tipo_pago'))
                        Filtro: {{ ucwords(str_replace('_', ' ', request('tipo_pago'))) }}
                    @elseif(request('ciudad'))
                        Filtro: {{ request('ciudad') }}
                    @else
                        Documento Oficial
                    @endif
                </div>
            </td>
        </tr>
    </table>

@if(request('dia'))
    <h3 class="section-title">
        Desglose de Operaciones - {{ \Carbon\Carbon::parse(request('dia'))->format('d/m/Y') }}
    </h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Nro. Fac</th>
                <th>Cliente</th>
                <th>Total venta</th>
                <th>Forma de pago</th>
                <th>Abono</th>
                <th>Forma de pago/abonos</th>
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
                <td>{{ $venta->nro_venta }}</td>
                <td>{{ $venta->cliente }}</td>
                <td>${{ number_format($venta->total_venta, 2) }}</td>
                <td>{{ $venta->tipo_pago }}</td>
                <td>
                    @if($venta->tipo_pago === 'Crédito' && $abonosCount > 0)
                        ${{ number_format($abonosDia[0]->abono, 2) }}
                    @else
                        -
                    @endif
                </td>
                <td>
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
                        <td>{{ $venta->nro_venta }}</td>
                        <td>{{ $venta->cliente }}</td>
                        <td>-</td>
                        <td>-</td>
                        <td>${{ number_format($abonosDia[$i]->abono, 2) }}</td>
                        <td>{{ $abonosDia[$i]->tipo_pago }}</td>
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

    <div class="totals-box">
        <h3 class="section-title" style="margin-top:0;">Total Entregar:</h3>
        <table style="width: 100%; border: none;">
            <tr><td style="border:none; padding:4px 0;"><strong>Efectivo:</strong></td><td style="border:none; padding:4px 0; text-align:right;">${{ number_format($totalEfectivo, 2) }}</td></tr>
            <tr><td style="border:none; padding:4px 0;"><strong>Transferencia:</strong></td><td style="border:none; padding:4px 0; text-align:right;">${{ number_format($totalTransferencia, 2) }}</td></tr>
            <tr><td style="border:none; padding:4px 0;"><strong>Cheque:</strong></td><td style="border:none; padding:4px 0; text-align:right;">${{ number_format($totalCheque, 2) }}</td></tr>
        </table>
    </div>
    <div style="clear: both;"></div>

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
            <h3 class="section-title">
                Desglose de Operaciones - {{ $fecha->format('d/m/Y') }}
            </h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nro. Fac</th>
                        <th>Cliente</th>
                        <th>Total venta</th>
                        <th>Forma de pago</th>
                        <th>Abono</th>
                        <th>Forma de pago/abonos</th>
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
                        <td>{{ $venta->nro_venta }}</td>
                        <td>{{ $venta->cliente }}</td>
                        <td>${{ number_format($venta->total_venta, 2) }}</td>
                        <td>{{ $venta->tipo_pago }}</td>
                        <td>
                            @if($venta->tipo_pago === 'Crédito' && $abonosCount > 0)
                                ${{ number_format($abonosDia[0]->abono, 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
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
                                <td>{{ $venta->nro_venta }}</td>
                                <td>{{ $venta->cliente }}</td>
                                <td>-</td>
                                <td>-</td>
                                <td>${{ number_format($abonosDia[$i]->abono, 2) }}</td>
                                <td>{{ $abonosDia[$i]->tipo_pago }}</td>
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
            <div class="totals-box">
                <h3 class="section-title" style="margin-top:0;">Total Entregar del Día:</h3>
                <table style="width: 100%; border: none;">
                    <tr><td style="border:none; padding:4px 0;"><strong>Efectivo:</strong></td><td style="border:none; padding:4px 0; text-align:right;">${{ number_format($efectivoDia, 2) }}</td></tr>
                    <tr><td style="border:none; padding:4px 0;"><strong>Transferencia:</strong></td><td style="border:none; padding:4px 0; text-align:right;">${{ number_format($transferenciaDia, 2) }}</td></tr>
                    <tr><td style="border:none; padding:4px 0;"><strong>Cheque:</strong></td><td style="border:none; padding:4px 0; text-align:right;">${{ number_format($chequeDia, 2) }}</td></tr>
                </table>
            </div>
            <div style="clear: both; margin-bottom: 30px;"></div>
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
    <h3 class="section-title">
        Detalle de Registros
    </h3>

    @foreach($ventas as $venta)
        <div class="venta-box">
            <div class="venta-header">
                <table class="venta-header-table">
                    <tr>
                        <td width="60%">
                            <span class="info-label">Cliente:</span> {{ $venta->cliente }}<br>
                            <span class="info-label">Ciudad:</span> {{ $venta->ciudad }}
                        </td>
                        <td width="40%" style="text-align: right;">
                            <span class="info-label">Forma de pago:</span> {{ $venta->tipo_pago }}<br>
                            <span class="info-label">Fecha:</span> {{ \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') }}
                        </td>
                    </tr>
                </table>
            </div>
            <table class="data-table" style="margin-bottom: 0;">
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
                <div class="venta-total" style="margin-top: 10px;">
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
                <div style="padding: 10px 16px;">
                    <h3 class="section-title" style="margin-top:0; font-size: 14px; border:none;">Historial de Abonos</h3>
                    <table class="data-table" style="margin-bottom:0; width: 60%;">
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

    <div class="footer">
        Generado por el Sistema Importadora Anturios el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}<br>
        Documento de uso interno y confidencial.
    </div>

</body>
</html>