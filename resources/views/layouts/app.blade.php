<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplicación de Gestión de Inventario')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('LogoEmpresa.ico') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --sidebar-bg: #404040; /* Charcoal */
            --sidebar-hover: rgba(255, 255, 255, 0.1);
            --sidebar-active: #F235B0; /* Vibrant Magenta */
            --sidebar-text: #F2F2F2; /* Light Grey */
            --sidebar-text-hover: #ffffff;
            --font-family: 'Inter', sans-serif;
            
            --brand-primary: #F235B0;
            --brand-primary-dark: #BF5098;
            --brand-dark: #404040;
            --brand-light: #F2F2F2;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--brand-light); /* Fondo general muy claro */
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden; /* Para manejar el scroll internamente */
        }

        /* Layout Container */
        #wrapper {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        /* Premium Sidebar */
        #sidebar-wrapper {
            width: 280px;
            background: linear-gradient(180deg, #404040 0%, #2a2a2a 100%);
            color: var(--sidebar-text);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .sidebar-header {
            padding: 1.5rem;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-brand {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            letter-spacing: -0.5px;
        }

        .user-profile {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 1.2rem;
            box-shadow: 0 4px 10px rgba(242, 53, 176, 0.3);
        }

        .nav-section-title {
            padding: 1.5rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #a0a0a0;
            font-weight: 600;
        }

        /* Menus and Links */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem;
            /* Ocultar barra de scroll pero permitir scroll */
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .sidebar-nav::-webkit-scrollbar {
            display: none;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .sidebar-link:hover, .sidebar-link[aria-expanded="true"] {
            background-color: var(--sidebar-hover);
            color: var(--sidebar-text-hover);
            transform: translateX(4px); /* Micro animacion */
        }

        .sidebar-link.active {
            background-color: rgba(242, 53, 176, 0.15);
            color: var(--sidebar-active);
            border-left: 3px solid var(--sidebar-active);
        }

        .sidebar-link i.icon-main {
            width: 24px;
            font-size: 1.1rem;
            margin-right: 12px;
            text-align: center;
            transition: transform 0.2s;
        }

        .sidebar-link:hover i.icon-main {
            transform: scale(1.1); /* Micro animacion icono */
        }

        /* Collapsible menus */
        .sidebar-link .arrow {
            margin-left: auto;
            transition: transform 0.3s ease;
            font-size: 0.8rem;
        }

        .sidebar-link[aria-expanded="true"] .arrow {
            transform: rotate(90deg);
        }

        .collapse-inner {
            padding-left: 2.5rem;
            padding-right: 1rem;
            margin-bottom: 0.5rem;
        }

        .collapse-item {
            display: block;
            padding: 0.5rem 1rem;
            color: #bfbfbf;
            text-decoration: none;
            font-size: 0.9rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .collapse-item:hover {
            color: var(--sidebar-text-hover);
            background-color: rgba(255,255,255,0.05);
        }

        /* Main Content Area */
        #page-content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Header for mobile */
        .top-navbar {
            display: none;
            background: #fff;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            align-items: center;
        }

        .content-scrollable {
            flex: 1;
            overflow-y: auto;
            padding: 2rem;
            position: relative;
        }

        /* Fondo sutil tipo marca de agua */
        .content-scrollable::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            height: 400px;
            background: url("{{ asset('images/logo-empresa.png') }}") no-repeat center center;
            background-size: contain;
            opacity: 0.03;
            pointer-events: none;
            z-index: 0;
        }

        /* Mobile View adjustments */
        @media (max-width: 768px) {
            #sidebar-wrapper {
                margin-left: -280px;
                position: fixed;
                height: 100%;
            }
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
            .top-navbar {
                display: flex;
            }
            .content-scrollable {
                padding: 1rem;
            }
        }

        /* Botón de cierre de sesión premium */
        .logout-container {
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        .btn-logout {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: rgba(242, 53, 176, 0.1);
            color: #F235B0;
            border: 1px solid rgba(242, 53, 176, 0.2);
            padding: 0.6rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 600;
        }
        .btn-logout:hover {
            background: #F235B0;
            color: #fff;
            box-shadow: 0 4px 12px rgba(242, 53, 176, 0.3);
        }

        /* -------------------------------------------
           GLOBAL PREMIUM UI (Tablas, Cards, Botones)
           ------------------------------------------- */
        /* Cards */
        .card {
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1.5rem;
            border-radius: 1rem;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .card-header {
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.25rem 1.5rem;
            background: var(--brand-dark) !important;
            color: white !important;
            font-weight: 600;
        }

        /* Botones */
        .btn {
            font-weight: 500;
            transition: all 0.2s ease;
            letter-spacing: 0.3px;
            border-radius: 50rem; /* pill */
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-info {
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-primary-dark));
            border: none;
            color: white;
        }
        .btn-info:hover {
            background: linear-gradient(135deg, #F235C0, var(--brand-primary));
            color: white;
        }
        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
        }
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border: none;
            color: white;
        }
        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
        }

        /* Formularios */
        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            padding: 0.6rem 1rem;
            transition: all 0.2s ease;
            background-color: #ffffff;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px rgba(242, 53, 176, 0.15);
            background-color: #ffffff;
        }
        .form-label {
            font-weight: 600;
            color: var(--brand-dark);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        /* Custom Brand Utilities added for forms without breaking menu */
        .text-brand {
            color: var(--brand-primary) !important;
        }
        .bg-brand {
            background-color: var(--brand-primary) !important;
            color: white !important;
        }
        .bg-brand-light {
            background-color: rgba(242, 53, 176, 0.1) !important;
        }

        /* Tablas */
        .table-responsive {
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .table {
            margin-bottom: 0;
            color: #334155;
        }
        .table thead th {
            background-color: var(--brand-light);
            color: var(--brand-dark);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--brand-light);
        }
        .table tbody tr:hover {
            background-color: #fafafa;
        }

        /* Alertas */
        .alert {
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        /* Paginación */
        .pagination .page-link {
            border-radius: 50%;
            margin: 0 3px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            color: var(--brand-dark);
            transition: all 0.2s;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--brand-primary);
            color: white;
            box-shadow: 0 4px 10px rgba(242, 53, 176, 0.3);
        }
        .pagination .page-link:hover:not(.active) {
            background-color: var(--brand-light);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

@if (Auth::check())
    <div id="wrapper">
        
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            
            <div class="sidebar-header">
                <a href="{{ route('home') }}" class="sidebar-brand">
                    <i class="fas fa-seedling me-2 text-brand"></i> Anturios
                </a>
            </div>

            <div class="user-profile">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="overflow: hidden;">
                    <div class="text-white fw-semibold text-truncate" style="font-size: 0.95rem;">{{ auth()->user()->name }}</div>
                    <div style="font-size: 0.75rem; color: #94a3b8;" class="text-truncate">{{ auth()->user()->cargoNombre() }}</div>
                </div>
            </div>

            <div class="sidebar-nav">
                
                <div class="nav-section-title">Principal</div>
                
                @if(auth()->user()->can('ver dashboard general') || auth()->user()->can('ver dashboard vendedor'))
                <a href="{{ route('home') }}" class="sidebar-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home icon-main"></i>
                    <span>Dashboard</span>
                </a>
                @endif

                @if(auth()->user()->can('ver productos') || auth()->user()->can('ver inventario global'))
                <div class="nav-section-title">Almacén</div>
                
                <a href="#inventarioSubmenu" data-bs-toggle="collapse" class="sidebar-link" aria-expanded="false">
                    <i class="fas fa-boxes icon-main"></i>
                    <span>Inventario</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <div class="collapse" id="inventarioSubmenu">
                    <div class="collapse-inner">
                        @can('ver productos')
                        <a href="{{ route('productos.index') }}" class="collapse-item">
                            <i class="fas fa-cube me-2"></i>Catálogo de Productos
                        </a>
                        @endcan
                        <!-- Puedes agregar mis bodegas aqui luego -->
                    </div>
                </div>
                @endif

                @if(auth()->user()->can('gestionar mis solicitudes') || auth()->user()->can('aprobar solicitudes'))
                <div class="nav-section-title">Operaciones</div>
                
                <a href="#movimientosSubmenu" data-bs-toggle="collapse" class="sidebar-link" aria-expanded="false">
                    <i class="fas fa-truck-loading icon-main"></i>
                    <span>Movimientos</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <div class="collapse" id="movimientosSubmenu">
                    <div class="collapse-inner">
                        @can('gestionar mis solicitudes')
                        <a href="{{ route('tipoNota.index') }}" class="collapse-item">
                            <i class="fas fa-file-invoice me-2"></i>Mis Solicitudes
                        </a>
                        @endcan
                        @can('aprobar solicitudes')
                        <a href="{{ route('transaccionProducto.index') }}" class="collapse-item">
                            <i class="fas fa-clipboard-check me-2"></i>Aprobaciones
                        </a>
                        @endcan
                    </div>
                </div>
                @endif

                @if(auth()->user()->can('registrar ventas') || auth()->user()->can('gestionar cuentas cobrar') || auth()->user()->hasRole('Administrador') || auth()->user()->cargoNombre() === 'Administrador')
                <div class="nav-section-title">Ventas</div>
                
                <a href="#ventasSubmenu" data-bs-toggle="collapse" class="sidebar-link" aria-expanded="false">
                    <i class="fas fa-cash-register icon-main"></i>
                    <span>Ventas y Recaudación</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <div class="collapse" id="ventasSubmenu">
                    <div class="collapse-inner">
                        <a href="{{ route('venta.index') }}" class="collapse-item">
                            <i class="fas fa-shopping-cart me-2"></i>Historial de Ventas
                        </a>
                    </div>
                </div>
                @endif

                @if(auth()->user()->can('gestionar usuarios') || auth()->user()->can('gestionar bodegas') || auth()->user()->hasRole('Administrador') || auth()->user()->cargoNombre() === 'Administrador')
                <div class="nav-section-title">Configuración</div>
                
                <a href="#adminSubmenu" data-bs-toggle="collapse" class="sidebar-link" aria-expanded="false">
                    <i class="fas fa-cogs icon-main"></i>
                    <span>Administración</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <div class="collapse" id="adminSubmenu">
                    <div class="collapse-inner">
                        @can('gestionar usuarios')
                        <a href="{{ route('empleados.index') }}" class="collapse-item">
                            <i class="fas fa-users me-2"></i>Empleados
                        </a>
                        @endcan
                        @can('gestionar bodegas')
                        <a href="{{ route('bodegas.index') }}" class="collapse-item">
                            <i class="fas fa-store me-2"></i>Bodegas
                        </a>
                        @endcan
                    </div>
                </div>
                @endif

            </div>

            <div class="logout-container">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            
            <!-- Navbar Mobile Toggle -->
            <div class="top-navbar">
                <button class="btn btn-light" id="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="ms-3 fw-bold text-dark">
                    Importadora Anturios
                </div>
            </div>

            <!-- Main Dynamic Content -->
            <div class="content-scrollable">
                <div style="position: relative; z-index: 1;">
                    
                    <!-- Dynamic Breadcrumbs -->
                    @if(!request()->routeIs('home') && !request()->routeIs('login'))
                    <div class="mb-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--brand-dark);"><i class="fas fa-home"></i> Inicio</a></li>
                                @php $segments = ''; @endphp
                                @foreach(request()->segments() as $segment)
                                    @php $segments .= '/'.$segment; @endphp
                                    <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" aria-current="page">
                                        @if(!$loop->last)
                                            <a href="{{ url($segments) }}" class="text-decoration-none text-capitalize" style="color: var(--brand-primary);">{{ str_replace('-', ' ', $segment) }}</a>
                                        @else
                                            <span class="text-capitalize text-muted fw-bold">{{ str_replace('-', ' ', $segment) }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </div>

        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

@else
    <!-- Si no está logueado, muestra el contenido directo (ej. pantalla login) -->
    @yield('content')
@endif

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Menu Toggle Script -->
<script>
    document.getElementById("menu-toggle")?.addEventListener("click", function(e) {
        e.preventDefault();
        document.getElementById("wrapper").classList.toggle("toggled");
    });

    // Global Form Loading State
    document.addEventListener('submit', function(e) {
        const form = e.target;
        // Solo aplicar a formularios que no tengan la clase 'no-spinner'
        if(!form.classList.contains('no-spinner')) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                // Prevenir doble submit y mostrar spinner
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Procesando...';
                
                // Si el formulario falla en enviar por validaciones html5, restaurar botón
                setTimeout(() => {
                    if(!form.checkValidity()) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                }, 50);
            }
        }
    });
</script>

@yield('scripts')
</body>
</html>