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
        body {
            padding-top: 70px;
        }

        /* Cambia el color de la letra del botón Cerrar Sesión */
        .logout-btn {
            color: #ff6347 !important; /* Cambia este valor por el color que desees */
            text-decoration: none;
        }

        .logout-btn:hover {
            color: #ff4500 !important; /* Cambia el color al pasar el mouse */
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
        }
    </style>
</head>
<body>
<!-- Navbar -->
@if (Auth::check())
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Gestión de Inventario</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    @can('ver Producto')
                        <!-- Productos -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('producto.index') }}">Productos</a>
                        </li>
                    @endcan

                    @can('ver empleado')
                        <!-- Empleados -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('empleado.index') }}">Empleados</a>
                        </li>
                    @endcan

                    @can('ver bodega')
                        <!-- Bodegas -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bodega.index') }}">Bodegas</a>
                        </li>
                    @endcan

                    @can('ver TransaccionProducto')
                        <!-- Transacción Producto -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('transaccionProducto.index') }}" id="navbarDropdownTransaccion" role="button"  >
                                Transacción Producto
                            </a>                           
                        </li>
                    @endcan

                    
                    <!-- Tipo Nota -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tipoNota.index') }}">Tipo Nota</a>
                    </li>

                    <!-- Cerrar Sesión -->
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link logout-btn">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endif

<!-- Content -->
<div class="container">
    @yield('content')
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
