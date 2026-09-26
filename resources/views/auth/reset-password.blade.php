<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña — Importadora Anturios</title>
    <link rel="icon" href="{{ asset('LogoEmpresa.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #9d2449;
            --primary-dark: #7a1c38;
            --primary-light: #b94b6d;
            --bg-base: #faf9f7;
            --bg-surface: #ffffff;
            --border: #e7e5e2;
            --text-main: #1c1917;
            --text-muted: #6B7280;
            --radius-md: 12px;
            --shadow-card: 0 10px 30px rgba(0,0,0,0.08);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-base);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .reset-card {
            width: 100%;
            max-width: 440px;
            background: var(--bg-surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

        .reset-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #ffffff;
            padding: 30px 24px;
            text-align: center;
        }

        .reset-header i {
            font-size: 36px;
            margin-bottom: 12px;
        }

        .reset-header h4 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .reset-header p {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.8);
            margin: 6px 0 0 0;
        }

        .reset-body {
            padding: 28px 24px;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            border: 1px solid var(--border);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(157, 36, 73, 0.15);
        }

        .btn-primary-custom {
            background: var(--primary);
            border: none;
            color: #ffffff;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            width: 100%;
            transition: all 0.2s;
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            color: #ffffff;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
            font-size: 13px;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>

<div class="reset-card">
    <div class="reset-header">
        <i class="fas fa-key"></i>
        <h4>Nueva Contraseña</h4>
        <p>Ingresa tu nueva contraseña para actualizar tu acceso al sistema.</p>
    </div>

    <div class="reset-body">
        @if ($errors->any())
            <div class="alert alert-danger" style="font-size: 13px;" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold" style="font-size: 13px;">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                    <input type="email" id="email" name="email" class="form-control border-start-0" 
                           value="{{ old('email', $request->email) }}" required autofocus readonly style="background-color: #f8f9fa;">
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold" style="font-size: 13px;">Nueva Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" id="password" name="password" class="form-control border-start-0" 
                           placeholder="••••••••" required autocomplete="new-password">
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-semibold" style="font-size: 13px;">Confirmar Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-shield-alt text-muted"></i></span>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control border-start-0" 
                           placeholder="••••••••" required autocomplete="new-password">
                </div>
            </div>

            <button type="submit" class="btn btn-primary-custom mb-3">
                <i class="fas fa-save me-2"></i>Restablecer Contraseña
            </button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Volver al inicio de sesión
                </a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
