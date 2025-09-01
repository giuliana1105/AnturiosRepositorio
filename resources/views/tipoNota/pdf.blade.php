<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nota PDF - {{ $nota->codigo }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 95%;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            padding: 32px 24px;
        }
        .title {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            color: #0097a7;
            margin-bottom: 18px;
            letter-spacing: 1px;
        }
        .info-row {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            margin-bottom: 18px;
        }
        .info-box {
            flex: 1 1 220px;
            background: #e0f7fa;
            border-radius: 8px;
            padding: 12px 18px;
            margin-bottom: 8px;
            font-size: 1rem;
            color: #00796b;
        }
        .label {
            font-weight: bold;
            color: #333;
        }
        .estado {
            font-weight: bold;
            color: #fff;
            padding: 5px 18px;
            border-radius: 20px;
            font-size: 1rem;
            display: inline-block;
        }
        .pendiente { background-color: #ff9800; }
        .finalizada { background-color: #43a047; }
        .sin-confirmar { background-color: #607d8b; }
        h3 {
            color: #0097a7;
            font-size: 1.2rem;
            margin-top: 24px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 8px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        th, td {
            padding: 10px 8px;
            text-align: left;
            font-size: 0.98rem;
        }
        th {
            background: #0097a7;
            color: #fff;
            font-weight: 600;
            border-bottom: 2px solid #00796b;
        }
        tr:nth-child(even) td {
            background: #e0f7fa;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .footer {
            margin-top: 32px;
            text-align: center;
            color: #888;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="title">Nota: {{ $nota->codigo }}</div>
    <div class="info-row">
        <div class="info-box">
            <span class="label">Tipo:</span> {{ $nota->tiponota }}
        </div>
        <div class="info-box">
            <span class="label">Solicitante:</span>
            {{ $nota->responsableEmpleado->nombreemp ?? 'N/A' }}
            {{ $nota->responsableEmpleado->apellidoemp ?? '' }}
        </div>
        <div class="info-box">
            <span class="label">Bodega:</span> {{ $nota->bodega->nombrebodega ?? 'N/A' }}
        </div>
        <div class="info-box">
            <span class="label">Fecha:</span> {{ $nota->fechanota }}
        </div>
        <div class="info-box">
            <span class="label">Estado:</span>
            <span class="estado
                {{ ($nota->transaccion->estado ?? '') == 'PENDIENTE' ? 'pendiente' : 
                   (($nota->transaccion->estado ?? '') == 'FINALIZADA' ? 'finalizada' : 'sin-confirmar') }}">
                {{ $nota->transaccion->estado ?? 'Sin Confirmar' }}
            </span>
        </div>
    </div>
    <h3>Productos</h3>
    <table>
        <thead>
        <tr>
            <th>Código</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Tipo de Empaque</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($nota->detalles as $detalle)
            <tr>
                <td>{{ $detalle->producto->codigo ?? 'N/A' }}</td>
                <td>{{ $detalle->producto->nombre ?? 'N/A' }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>{{ $detalle->producto->tipoempaque ?? 'Sin Empaque' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="footer">
        Generado por {{ config('app.name') }} el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>
</div>
</body>
</html>
