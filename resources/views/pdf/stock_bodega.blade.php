<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock de Bodega {{ $bodega->nombrebodega }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 13px; 
            margin: 20px;
        }
        
        .header {
            width: 100%;
            margin-bottom: 30px;
        }
        
        .header-content {
            width: 100%;
            border-collapse: collapse;
        }
        
        .logo-cell {
            width: 180px;
            vertical-align: middle;
            padding-right: 30px;
            border: none;
        }
        
        .logo {
            width: 160px;
            height: auto;
        }
        
        .info-cell {
            vertical-align: middle;
            padding-left: 0;
            border: none;
        }
        
        .stock-line {
            margin-bottom: 15px;
        }
        
        .stock-title {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }
        
        .bodega-name {
            font-size: 32px;
            font-weight: normal;
            color: #333;
            margin-left: 10px;
        }
        
        .fecha-line {
            margin-top: 5px;
        }
        
        .fecha-label {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        
        .fecha-value {
            font-size: 24px;
            font-weight: normal;
            color: #333;
            margin-left: 10px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        
        th, td { 
            border: 1px solid #333; 
            padding: 8px; 
            text-align: left; 
        }
        
        th { 
            background: #dc94caff; 
            color: #fff; 
            font-weight: bold;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-content">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('images/logo-empresa.png') }}" class="logo" alt="Logo Empresa">
                </td>
                <td class="info-cell">
                    <div class="stock-line">
                        <span class="stock-title">Stock de Bodega:</span>
                        <span class="bodega-name">{{ $bodega->nombrebodega }}</span>
                    </div>
                    
                </td>
            </tr>
        </table>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Empaque</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productosEnBodega as $item)
                <tr>
                    <td>{{ $item['codigo'] }}</td>
                    <td>{{ $item['nombre'] }}</td>
                    <td>{{ $item['descripcion'] }}</td>
                    <td>{{ $item['cantidad'] }}</td>
                    <td>{{ $item['empaque'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>