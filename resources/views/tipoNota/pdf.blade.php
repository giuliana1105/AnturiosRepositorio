<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota PDF - {{ $nota->codigo }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 90%; margin: 0 auto; }
        .title { text-align: center; font-size: 22px; margin-bottom: 20px; }
        p { margin: 5px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .estado { font-weight: bold; color: #fff; padding: 5px 10px; border-radius: 5px; }
        .pendiente { background-color: orange; }
        .finalizada { background-color: green; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="title">Nota: {{ $nota->codigo }}</h2>

    <p><strong>Tipo:</strong> {{ $nota->tiponota }}</p>
    <p><strong>Solicitante:</strong>
        {{ $nota->responsableEmpleado->nombreemp ?? 'N/A' }}
        {{ $nota->responsableEmpleado->apellidoemp ?? '' }}
    </p>
    <p><strong>Bodega:</strong> {{ $nota->bodega->nombrebodega ?? 'N/A' }}</p>
    <p><strong>Fecha:</strong> {{ $nota->fechanota }}</p>

    <!-- Estado con colores -->
    <p><strong>Estado:</strong>
        <span class="estado {{ $nota->transaccion->estado == 'PENDIENTE' ? 'pendiente' : 'finalizada' }}">
            {{ $nota->transaccion->estado ?? 'Sin Confirmar' }}
        </span>
    </p>

    <h3>Productos</h3>
    <table>
        <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Tipo de Empaque</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($nota->detalles as $detalle)
            <tr>
                <td>{{ $detalle->producto->nombre ?? 'N/A' }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>{{ $detalle->producto->tipoempaque ?? 'Sin Empaque' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
