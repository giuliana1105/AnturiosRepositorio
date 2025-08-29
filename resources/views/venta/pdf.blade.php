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
</body>
</html>