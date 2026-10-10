<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nota PDF - {{ $nota->codigo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff;
            margin: 0;
            padding: 20px 40px;
            color: #3C3C3C;
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
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .info-grid td {
            padding: 8px 0;
            vertical-align: top;
            font-size: 14px;
        }
        .info-label {
            font-weight: bold;
            color: #3C3C3C;
            width: 120px;
        }
        .info-value {
            color: #555;
        }
        .estado-badge {
            font-weight: bold;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            color: #fff;
            text-transform: uppercase;
        }
        .pendiente { background-color: #f59e0b; }
        .finalizada { background-color: #10b981; }
        .sin-confirmar { background-color: #6b7280; }
        
        h3 {
            color: #dc94ca;
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .products-table th, .products-table td {
            padding: 10px;
            text-align: left;
            font-size: 13px;
            border: 1px solid #ddd;
        }
        .products-table th {
            background: #fdf0f5;
            color: #880e4f;
            font-weight: bold;
        }
        .footer {
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
                <h1 class="doc-title">Nota de Pedido</h1>
                <div class="doc-subtitle">Documento Oficial N° {{ $nota->codigo }}</div>
            </td>
        </tr>
    </table>

    <table class="info-grid">
        <tr>
            <td class="info-label">Tipo de Nota:</td>
            <td class="info-value" style="width: 35%;">{{ $nota->tiponota }}</td>
            <td class="info-label">Fecha Emisión:</td>
            <td class="info-value">{{ $nota->fechanota }}</td>
        </tr>
        <tr>
            <td class="info-label">Solicitante:</td>
            <td class="info-value">
                {{ $nota->responsableEmpleado->nombreemp ?? 'N/A' }} 
                {{ $nota->responsableEmpleado->apellidoemp ?? '' }}
            </td>
            <td class="info-label">Estado:</td>
            <td class="info-value">
                <span class="estado-badge 
                    {{ ($nota->transaccion->estado ?? '') == 'PENDIENTE' ? 'pendiente' : 
                       (($nota->transaccion->estado ?? '') == 'FINALIZADA' ? 'finalizada' : 'sin-confirmar') }}">
                    {{ $nota->transaccion->estado ?? 'Sin Confirmar' }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="info-label">Bodega Origen:</td>
            <td class="info-value" colspan="3">{{ $nota->bodega->nombrebodega ?? 'N/A' }}</td>
        </tr>
    </table>

    <h3>Detalle de Productos</h3>
    <table class="products-table">
        <thead>
            <tr>
                <th width="15%">Código</th>
                <th width="50%">Descripción del Producto</th>
                <th width="15%">Cantidad</th>
                <th width="20%">Empaque</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nota->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->codigo ?? 'N/A' }}</td>
                    <td>{{ $detalle->producto->nombre ?? 'N/A' }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{ $detalle->producto->tipoempaque ?? 'Unidad' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado por el Sistema Importadora Anturios el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}<br>
        Documento de uso interno y confidencial.
    </div>

</body>
</html>
