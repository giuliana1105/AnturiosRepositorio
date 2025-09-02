<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background: url('{{ asset('images/fondo.png') }}') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            display: flex;
            width: 900px;
            max-width: 98vw;
            min-height: 540px;
            box-shadow: 0 0 32px rgba(60,60,120,0.12);
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
        }
        .login-left {
            background: #fcfcfeff;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
        }
        .logo-img {
            width: 260px;
            max-width: 90%;
            display: block;
            margin: 0 auto;
        }
        .login-right {
            background: #d062a2ff;
            flex: 1.2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 48px 32px;
        }
        .login-title {
            font-size: 2.7rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 32px;
            text-align: center;
        }
        .login-form {
            width: 100%;
            max-width: 340px;
        }
        .login-form label {
            font-weight: bold;
            margin-bottom: 6px;
            color: #fff;
        }
        .login-form .form-control {
            border-radius: 8px;
            margin-bottom: 18px;
            background: #ececfb;
            border: none;
            font-size: 1.1rem;
        }
        .login-form .btn-info {
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            padding: 12px 0;
            background: #e3d6e1ff;
            border: none;
            color: #222;
            font-size: 1.1rem;
            margin-top: 10px;
        }
        .login-form .form-check-label {
            font-size: 1rem;
            color: #fff;
        }
        .position-relative .btn-link {
            color: #222;
        }
        @media (max-width: 900px) {
            .login-container {
                flex-direction: column;
                min-height: 100vh;
                box-shadow: none;
                border-radius: 0;
            }
            .login-left {
                padding: 24px 0;
            }
            .login-right {
                padding: 32px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Columna izquierda: logo grande -->
        <div class="login-left">
            <img src="{{ asset('images/logo-empresa.png') }}" alt="Logo Empresa" class="logo-img">
        </div>
        <!-- Columna derecha: formulario -->
        <div class="login-right">
            <div class="login-title">Bienvenidos</div>
            <form action="{{ route('login.post') }}" method="POST" class="login-form">
                @csrf
                <label for="email"><i class="fas fa-envelope me-2 text-info"></i>Correo Electrónico</label>
                <input type="email" name="email" id="email" class="form-control" required placeholder="Ingresa tu correo electrónico" value="{{ old('email') }}">

                <label for="password"><i class="fas fa-lock me-2 text-info"></i>Contraseña</label>
                <div class="position-relative">
                    <input type="password" name="password" id="password" class="form-control" required placeholder="Ingresa tu contraseña">
                    <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-2" onclick="togglePassword()" id="toggleBtn">
                        <i class="fas fa-eye text-muted" id="eyeIcon"></i>
                    </button>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                    <label class="form-check-label ms-1" for="remember_me">
                        <i class="fas fa-bookmark me-1 text-info"></i>Recordar sesión
                    </label>
                </div>

                <button type="submit" class="btn btn-info">Iniciar Sesión</button>
            </form>
        </div>
    </div>
    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.className = 'fas fa-eye-slash text-muted';
        } else {
            passwordInput.type = 'password';
            eyeIcon.className = 'fas fa-eye text-muted';
        }
    }
    </script>
</body>
</html>