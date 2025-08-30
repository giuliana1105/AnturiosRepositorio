<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Ventas</title>
    <style>
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
        @foreach($ventas as $venta)
            @php
                // Busca el abono de la venta para la fecha filtrada
                $abonoDia = null;
                $formaPagoAbono = null;
                if ($venta->tipo_pago === 'Crédito' && isset($venta->abonos)) {
                    foreach ($venta->abonos as $abono) {
                        if (\Carbon\Carbon::parse($abono->fecha)->format('Y-m-d') === request('dia')) {
                            $abonoDia = $abono->abono;
                            $formaPagoAbono = $abono->tipo_pago;
                            break;
                        }
                    }
                }
            @endphp
            <tr>
                <td style="border:1px solid #ccc; padding:4px;">{{ $venta->nro_venta }}</td>
                <td style="border:1px solid #ccc; padding:4px;">{{ $venta->cliente }}</td>
                <td style="border:1px solid #ccc; padding:4px;">${{ number_format($venta->total_venta, 2) }}</td>
                <td style="border:1px solid #ccc; padding:4px;">{{ $venta->tipo_pago }}</td>
                <td style="border:1px solid #ccc; padding:4px;">
                    @if($abonoDia)
                        ${{ number_format($abonoDia, 2) }}
                    @endif
                </td>
                <td style="border:1px solid #ccc; padding:4px;">
                    @if($formaPagoAbono)
                        {{ $formaPagoAbono }}
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @php
        $totalEfectivo = 0;
        $totalTransferencia = 0;
        $totalCheque = 0;

        foreach($ventas as $venta) {
            // Suma ventas directas
            if($venta->tipo_pago === 'Efectivo') {
                $totalEfectivo += $venta->total_venta;
            }
            if($venta->tipo_pago === 'Transferencia') {
                $totalTransferencia += $venta->total_venta;
            }
            if($venta->tipo_pago === 'Cheque') {
                $totalCheque += $venta->total_venta;
            }
            // Suma abonos del día
            if($venta->tipo_pago === 'Crédito' && isset($venta->abonos)) {
                foreach($venta->abonos as $abono) {
                    if(\Carbon\Carbon::parse($abono->fecha)->format('Y-m-d') === request('dia')) {
                        if($abono->tipo_pago === 'Efectivo') $totalEfectivo += $abono->abono;
                        if($abono->tipo_pago === 'Transferencia') $totalTransferencia += $abono->abono;
                        if($abono->tipo_pago === 'Cheque') $totalCheque += $abono->abono;
                    }
                }
            }
        }
    @endphp

    <div style="margin-top: 30px;">
        <span style="font-weight:bold; text-decoration: underline;">Total entregar:</span><br><br>
        EFECTIVO: ${{ number_format($totalEfectivo, 2) }}<br>
        TRANSFERENCIA: ${{ number_format($totalTransferencia, 2) }}<br>
        CHEQUE: ${{ number_format($totalCheque, 2) }}
    </div>
@else
    @foreach($ventas as $venta)
        <div class="venta-box">
            <h4>Confirmar Venta</h4>
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
                        <td>{{ $detalle->empaque ?? '' }}</td>
                        <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td>${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="venta-total">
                <strong>Total venta:</strong> ${{ number_format($venta->total_venta, 2) }}
            </div>
        </div>
    @endforeach
@endif
</body>
</html>