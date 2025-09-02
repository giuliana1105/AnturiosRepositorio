<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplicación de Gestión de Inventario')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('LogoEmpresa.ico') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url("{{ asset('images/logo-empresa.png') }}") no-repeat center center;
            background-size: 600px 600px; /* <-- Cambia aquí el tamaño */
            opacity: 0.08; /* Ajusta la transparencia si lo deseas */
            pointer-events: none;
            z-index: 0;
        }
        body {
            position: relative;
            z-index: 1;
            padding-top: 0; /* Eliminado el padding-top que causaba el espacio */
            margin: 0;
            padding: 0;
            background: url("{{ asset('images/logo-empresa.png') }}") no-repeat center center;
            background-size: 350px 350px;
            background-attachment: fixed;
            /* Si quieres que el fondo sea más tenue, usa opacity en un pseudo-elemento */
        }

        /* Cambia el color de la letra del botón Cerrar Sesión */
        .logout-btn {
            color: #ff6347 !important; /* Cambia este valor por el color que desees */
            text-decoration: none;
        }

        .logout-btn:hover {
            color: #ff4500 !important; /* Cambia el color al pasar el mouse */
        }

        /* Navbar sin posición fija para que el contenido quede pegado */
        .navbar {
            position: relative;
            margin: 0;
            border-radius: 0;
            padding: 0;
        }

        /* Contenedor principal sin márgenes */
        .main-container {
            padding: 0;
            margin: 0;
        }

        /* Asegurar que no haya contenedores que agreguen márgenes */
        .container {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }

        /* Ajustes para dispositivos móviles */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem; /* Tamaño más pequeño para el logo */
            }

            .navbar-nav .nav-link {
                padding: 0.5rem 1rem; /* Espaciado más pequeño para los enlaces */
            }

            .dropdown-menu {
                background-color: #343a40; /* Fondo oscuro para el menú desplegable */
            }

            .dropdown-item {
                color: #fff !important; /* Texto blanco para los elementos del menú desplegable */
            }

            .dropdown-item:hover {
                background-color: #495057; /* Color de fondo al pasar el mouse */
            }

            .logout-btn {
                padding: 0.5rem 1rem; /* Espaciado para el botón de cerrar sesión */
            }

            .main-container {
                margin-top: 0; /* Sin margen en móvil */
            }
        }

        /* Estilo para que los botones de la navbar se alineen en una columna en pantallas pequeñas */
        @media (max-width: 576px) {
            .navbar-nav {
                text-align: center;
            }

            .navbar-nav .nav-item {
                margin-bottom: 10px;
            }

            .navbar-nav .nav-link {
                padding-left: 0;
                padding-right: 0;
            }

            .main-container {
                margin-top: 50px; /* Aún menor margen en pantallas muy pequeñas */
            }
        }

        /* Asegurar altura completa sin espacios */
        html {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<!-- Navbar -->
@if (Auth::check())
    <nav class="navbar navbar-expand-lg" style="background-color: #0097a7;">
        <div class="container-fluid">
            <span class="navbar-brand text-white fw-bold" style="font-size: 1.7rem;">
                Gestión de inventario - Importadora Anturios
                <i class="fas fa-shopping-cart ms-2"></i>
            </span>
            <div class="collapse navbar-collapse" id="navbarNav">
                <nav class="nav flex-column">
                    <!-- <a class="nav-link text-dark mb-2" href="{{ route('home') }}">
                        <i class="fas fa-home me-2"></i> Home
                    </a> -->
                    <!-- ...otros enlaces... -->
                    <!-- <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link text-dark btn btn-link mb-2" style="text-align:left;">
                            <i class="fas fa-sign-out-alt me-2"></i> Cerrar sesión
                        </button>
                    </form> -->
                </nav>
            </div>
        </div>
    </nav>
@endif

<!-- Content -->
<div class="main-container">
    @yield('content')
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>