<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado — Importadora Anturios</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('LogoEmpresa.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, system-ui, sans-serif;
            background: #faf9f7;
            color: #1c1917;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .error-container {
            text-align: center;
            max-width: 520px;
            width: 100%;
        }

        .error-icon-wrapper {
            width: 120px;
            height: 120px;
            margin: 0 auto 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(220,38,38,0.08), rgba(220,38,38,0.15));
            display: flex;
            align-items: center;
            justify-content: center;
            animation: shake 0.6s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-5px); }
            80% { transform: translateX(5px); }
        }

        .error-icon-wrapper i {
            font-size: 48px;
            color: #dc2626;
        }

        .error-code {
            font-size: 64px;
            font-weight: 700;
            color: #dc2626;
            letter-spacing: -2px;
            line-height: 1;
            margin-bottom: 12px;
        }

        .error-title {
            font-size: 22px;
            font-weight: 600;
            color: #1c1917;
            margin-bottom: 12px;
        }

        .error-message {
            font-size: 15px;
            color: #57534e;
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .error-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-error {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: none;
        }

        .btn-primary-error {
            background: #E91E8C;
            color: #fff;
        }

        .btn-primary-error:hover {
            background: #C4177A;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(233,30,140,0.3);
        }

        .btn-secondary-error {
            background: #fff;
            color: #57534e;
            border: 1px solid #e7e5e2;
        }

        .btn-secondary-error:hover {
            background: #faf9f7;
            border-color: #a8a29e;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        .error-footer {
            margin-top: 48px;
            font-size: 12px;
            color: #a8a29e;
        }

        .error-footer i {
            color: #E91E8C;
            margin-right: 4px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon-wrapper">
            <i class="fas fa-lock"></i>
        </div>

        <div class="error-code">403</div>
        <h1 class="error-title">Acceso Denegado</h1>
        <p class="error-message">
            No tienes permiso para acceder a esta sección.
            <br>
            Si crees que esto es un error, contacta al administrador del sistema para que revise tus permisos.
        </p>

        <div class="error-actions">
            <a href="javascript:history.back()" class="btn-error btn-primary-error">
                <i class="fas fa-arrow-left"></i>
                Volver Atrás
            </a>
            <a href="{{ url('/home') }}" class="btn-error btn-secondary-error">
                <i class="fas fa-home"></i>
                Ir al Inicio
            </a>
        </div>

        <div class="error-footer">
            <i class="fas fa-seedling"></i>
            Importadora Anturios — Sistema de Gestión
        </div>
    </div>
</body>
</html>
