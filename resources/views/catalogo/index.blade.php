<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo Público — Importadora Anturios</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo-empresa.png') }}" type="image/png">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #9d2449;
            --primary-dark: #7a1c38;
            --primary-light: #b94b6d;
            --primary-subtle: rgba(157, 36, 73, 0.08);
            --dark: #2c2925;
            --dark-surface: #3f3b35;
            --text-primary: #2d2a26;
            --text-secondary: #6B7280;
            --text-light: #9CA3AF;
            --white: #FFFFFF;
            --off-white: #f5f3f0;
            --border-color: #E5E7EB;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 16px 40px rgba(0, 0, 0, 0.12);
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --radius-xl: 28px;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--off-white);
            color: var(--text-primary);
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 16px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: var(--transition);
            background: rgba(10, 10, 30, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .nav-logo img {
            height: 38px;
            width: auto;
            filter: drop-shadow(0 2px 6px rgba(233, 30, 140, 0.3));
        }

        .nav-logo-text {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--white);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--primary-light);
        }

        .nav-cta {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white) !important;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600 !important;
            box-shadow: 0 4px 15px rgba(233, 30, 140, 0.3);
            transition: var(--transition);
        }

        .nav-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 30, 140, 0.5);
        }

        .nav-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--white);
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* ===== HERO CATALOGO ===== */
        .hero-catalog {
            background: linear-gradient(135deg, var(--dark) 0%, #16163F 50%, #1F1135 100%);
            padding: 140px 40px 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-catalog::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: var(--primary);
            opacity: 0.12;
            filter: blur(80px);
        }

        .hero-catalog h1 {
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--white);
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .text-gradient {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-catalog p {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.7);
            max-width: 650px;
            margin: 0 auto 36px;
            line-height: 1.6;
            font-weight: 300;
        }

        /* ===== FILTERS & SEARCH ===== */
        .filter-section {
            max-width: 1200px;
            margin: -30px auto 40px;
            padding: 0 20px;
            position: relative;
            z-index: 10;
        }

        .filter-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }

        .search-form {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .search-input-wrapper {
            flex: 1;
            position: relative;
        }

        .search-input-wrapper i {
            position: absolute;
            top: 50%;
            left: 18px;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1rem;
        }

        .search-input-wrapper input {
            width: 100%;
            padding: 14px 18px 14px 48px;
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            outline: none;
            transition: var(--transition);
            background: var(--off-white);
        }

        .search-input-wrapper input:focus {
            border-color: var(--primary);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(233, 30, 140, 0.1);
        }

        .btn-search {
            padding: 0 28px;
            background: var(--dark);
            color: var(--white);
            border: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-search:hover {
            background: var(--primary);
        }

        .btn-reset {
            padding: 0 16px;
            background: #F3F4F6;
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .btn-reset:hover {
            background: #E5E7EB;
            color: var(--text-primary);
        }

        .categories-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .pill {
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            color: var(--text-secondary);
            background: #F3F4F6;
            transition: var(--transition);
            border: 1px solid transparent;
        }

        .pill:hover {
            background: #E5E7EB;
            color: var(--text-primary);
        }

        .pill.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            box-shadow: 0 4px 12px rgba(233, 30, 140, 0.25);
        }

        /* ===== CATALOG GRID ===== */
        .catalog-container {
            max-width: 1200px;
            margin: 0 auto 80px;
            padding: 0 20px;
            flex: 1;
        }

        .catalog-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .catalog-stats strong {
            color: var(--text-primary);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 28px;
        }

        .product-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(233, 30, 140, 0.2);
        }

        .product-img-box {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .product-img-box i {
            font-size: 4rem;
            transition: var(--transition);
        }

        .product-card:hover .product-img-box i {
            transform: scale(1.15) rotate(5deg);
        }

        .badge-category {
            position: absolute;
            top: 14px;
            right: 14px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-primary);
            box-shadow: var(--shadow-sm);
        }

        .product-body {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-code {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-desc {
            font-size: 0.88rem;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-footer {
            border-top: 1px solid #F3F4F6;
            padding-top: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .stock-info {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            font-weight: 500;
        }

        .stock-info.available {
            color: #059669;
        }

        .stock-info.consult {
            color: #6B7280;
        }

        .price-tag {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--dark);
        }

        .btn-whatsapp {
            width: 100%;
            padding: 12px;
            background: #25D366;
            color: var(--white);
            border: none;
            border-radius: var(--radius-md);
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);
        }

        .btn-whatsapp:hover {
            background: #1EBE5D;
            box-shadow: 0 6px 18px rgba(37, 211, 102, 0.4);
            transform: translateY(-2px);
        }

        /* ===== NO RESULTS ===== */
        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: var(--white);
            border-radius: var(--radius-lg);
            border: 1px dashed var(--border-color);
        }

        .no-results i {
            font-size: 3rem;
            color: var(--text-light);
            margin-bottom: 16px;
        }

        .no-results h3 {
            font-size: 1.3rem;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .no-results p {
            color: var(--text-secondary);
            margin-bottom: 24px;
        }

        /* ===== PAGINATION ===== */
        .pagination-wrapper {
            margin-top: 40px;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            gap: 6px;
            list-style: none;
        }

        .pagination li a,
        .pagination li span {
            padding: 10px 16px;
            background: var(--white);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .pagination li.active span {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--white);
        }

        .pagination li a:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--dark);
            padding: 0;
            margin-top: auto;
        }

        .footer-main {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
            gap: 48px;
            max-width: 1100px;
            margin: 0 auto;
            padding: 64px 40px 48px;
        }

        .footer-brand {
            display: flex;
            flex-direction: column;
        }

        .footer-brand-top {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .footer-logo {
            height: 36px;
            width: auto;
        }

        .footer-brand-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .footer-brand p {
            color: rgba(255,255,255,0.45);
            font-size: 0.88rem;
            font-weight: 300;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .footer-social {
            display: flex;
            gap: 10px;
        }

        .footer-social a {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            transition: var(--transition);
            font-size: 0.9rem;
        }

        .footer-social a:hover {
            background: var(--primary);
            color: var(--white);
            transform: translateY(-3px);
        }

        .footer-col h4 {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 12px;
        }

        .footer-col h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 28px;
            height: 2px;
            background: var(--primary);
            border-radius: 2px;
        }

        .footer-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-col ul li {
            margin-bottom: 12px;
        }

        .footer-col ul li a {
            color: rgba(255,255,255,0.45);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 400;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-col ul li a:hover {
            color: var(--primary-light);
            transform: translateX(4px);
        }

        .footer-col ul li a i {
            font-size: 0.7rem;
            color: var(--primary);
            width: 16px;
            text-align: center;
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 16px;
        }

        .footer-contact-item i {
            color: var(--primary);
            font-size: 0.85rem;
            margin-top: 3px;
            width: 16px;
            text-align: center;
            flex-shrink: 0;
        }

        .footer-contact-item span {
            color: rgba(255,255,255,0.45);
            font-size: 0.88rem;
            font-weight: 400;
            line-height: 1.5;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .footer-bottom p {
            color: rgba(255,255,255,0.3);
            font-size: 0.82rem;
            font-weight: 300;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .navbar {
                padding: 14px 20px;
            }

            .nav-links {
                position: fixed;
                top: 0;
                right: -100%;
                width: 280px;
                height: 100vh;
                background: var(--white);
                flex-direction: column;
                justify-content: center;
                gap: 24px;
                padding: 40px;
                transition: right 0.4s ease;
                box-shadow: -10px 0 30px rgba(0,0,0,0.1);
            }

            .nav-links.open {
                right: 0;
            }

            .nav-links a {
                color: var(--text-primary) !important;
                font-size: 1.1rem;
            }

            .nav-toggle {
                display: block;
                z-index: 1001;
            }

            .hero-catalog {
                padding: 120px 20px 50px;
            }

            .hero-catalog h1 {
                font-size: 2.2rem;
            }

            .search-form {
                flex-direction: column;
            }

            .btn-search {
                padding: 14px;
                justify-content: center;
            }

            .footer-main {
                grid-template-columns: 1fr;
                gap: 32px;
                padding: 40px 24px 32px;
            }

            .footer-brand, .footer-col {
                text-align: center;
                align-items: center;
            }

            .footer-col h4::after {
                left: 50%;
                transform: translateX(-50%);
            }

            .footer-col ul li a, .footer-contact-item, .footer-social {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar" id="navbar">
        <a href="{{ url('/') }}" class="nav-logo">
            <img src="{{ asset('images/logo-empresa.png') }}" alt="Anturios">
            <span class="nav-logo-text">Anturios</span>
        </a>
        <ul class="nav-links" id="navLinks">
            <li><a href="{{ url('/') }}#hero">Inicio</a></li>
            <li><a href="{{ url('/') }}#servicios">Nosotros</a></li>
            <li><a href="{{ route('catalogo.index') }}" class="active">Catálogo</a></li>
            <li><a href="{{ url('/') }}#contacto">Contacto</a></li>
            <li><a href="{{ route('login') }}" class="nav-cta">Iniciar Sesión</a></li>
        </ul>
        <button class="nav-toggle" onclick="toggleMobileMenu()" aria-label="Menú">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <!-- ===== HERO CATALOGO ===== -->
    <header class="hero-catalog">
        <h1>Catálogo de <span class="text-gradient">Productos</span></h1>
        <p>
            Explora nuestra selección de artículos de floristería y empaques para mayoristas. 
            Calidad garantizada e importación directa con envíos a todo el país.
        </p>
    </header>

    <!-- ===== FILTERS & SEARCH ===== -->
    <section class="filter-section">
        <div class="filter-card">
            <form action="{{ route('catalogo.index') }}" method="GET" class="search-form">
                @if($categoriaSeleccionada !== 'Todos')
                    <input type="hidden" name="categoria" value="{{ $categoriaSeleccionada }}">
                @endif
                <div class="search-input-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Buscar por código, nombre o descripción..." value="{{ $search }}">
                </div>
                <button type="submit" class="btn-search">
                    <i class="fas fa-filter"></i> Buscar
                </button>
                @if(!empty($search) || $categoriaSeleccionada !== 'Todos')
                    <a href="{{ route('catalogo.index') }}" class="btn-reset" title="Limpiar filtros">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>

            <!-- Categorías -->
            <div class="categories-pills">
                @foreach($categorias as $cat)
                    <a href="{{ route('catalogo.index', ['categoria' => $cat, 'search' => $search]) }}" 
                       class="pill {{ $categoriaSeleccionada === $cat ? 'active' : '' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== CATALOG GRID ===== -->
    <main class="catalog-container">
        <div class="catalog-stats">
            <span>Mostrando <strong>{{ $productos->count() }}</strong> de <strong>{{ $productos->total() }}</strong> productos</span>
            @if($categoriaSeleccionada !== 'Todos')
                <span>Categoría: <strong>{{ $categoriaSeleccionada }}</strong></span>
            @endif
        </div>

        @if($productos->count() > 0)
            <div class="products-grid">
                @foreach($productos as $producto)
                    <div class="product-card">
                        <div class="product-img-box" style="background: {{ $producto->meta_bg }}; color: {{ $producto->meta_color }};">
                            <i class="fas {{ $producto->meta_icono }}"></i>
                            <span class="badge-category">{{ $producto->meta_categoria }}</span>
                        </div>
                        <div class="product-body">
                            <span class="product-code"># {{ $producto->codigo }}</span>
                            <h3 class="product-title">{{ $producto->nombre }}</h3>
                            <p class="product-desc">{{ $producto->descripcion }}</p>
                            
                            <div class="product-footer">
                                <div class="stock-info {{ $producto->cantidad > 0 ? 'available' : 'consult' }}">
                                    @if($producto->cantidad > 0)
                                        <i class="fas fa-check-circle"></i>
                                        <span>Disponible ({{ $producto->cantidad }} {{ $producto->tipoempaque ?? 'Unidades' }})</span>
                                    @else
                                        <i class="fas fa-clock"></i>
                                        <span>Consultar disponibilidad</span>
                                    @endif
                                </div>
                                <div class="price-tag">
                                    <span>Precio Mayorista — Cotizar</span>
                                </div>
                                @php
                                    $mensajeWA = urlencode("Hola Importadora Anturios! 👋 Vengo del catálogo web y deseo consultar el precio y disponibilidad al por mayor de:\n\n📦 *Producto:* {$producto->nombre}\n🔢 *Código:* {$producto->codigo}\n\nQuedo atento a su respuesta, gracias! 🌸");
                                @endphp
                                <a href="https://wa.me/593997874363?text={{ $mensajeWA }}" target="_blank" rel="noopener noreferrer" class="btn-whatsapp">
                                    <i class="fab fa-whatsapp"></i> Consultar por WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            @if($productos->hasPages())
                <div class="pagination-wrapper">
                    {{ $productos->links('pagination::simple-default') ?? '' }}
                </div>
            @endif
        @else
            <div class="no-results">
                <i class="fas fa-box-open"></i>
                <h3>No se encontraron productos</h3>
                <p>No hay artículos que coincidan con los criterios de búsqueda o categoría seleccionados.</p>
                <a href="{{ route('catalogo.index') }}" class="btn-search" style="display: inline-flex; width: auto; padding: 12px 28px;">
                    Ver todos los productos
                </a>
            </div>
        @endif
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="footer-main">
            <div class="footer-brand">
                <div class="footer-brand-top">
                    <img src="{{ asset('images/logo-empresa.png') }}" alt="Anturios" class="footer-logo">
                    <span class="footer-brand-name">Anturios</span>
                </div>
                <p>Importadores directos de artículos de floristería. Envíos a nivel nacional desde Ibarra, Ecuador.</p>
                <div class="footer-social">
                    <a href="https://www.facebook.com/anturios.importadora" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/anturiosimportadora/" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/@anturiosimportadora" target="_blank" rel="noopener noreferrer" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    <a href="https://wa.me/593997874363" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Enlaces Rápidos</h4>
                <ul>
                    <li><a href="{{ url('/') }}#hero"><i class="fas fa-chevron-right"></i> Inicio</a></li>
                    <li><a href="{{ url('/') }}#servicios"><i class="fas fa-chevron-right"></i> Nosotros</a></li>
                    <li><a href="{{ route('catalogo.index') }}"><i class="fas fa-chevron-right"></i> Catálogo</a></li>
                    <li><a href="{{ url('/') }}#contacto"><i class="fas fa-chevron-right"></i> Contacto</a></li>
                    <li><a href="{{ route('login') }}"><i class="fas fa-chevron-right"></i> Iniciar Sesión</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Categorías</h4>
                <ul>
                    <li><a href="{{ route('catalogo.index', ['categoria' => 'Cintas y Lazos']) }}"><i class="fas fa-chevron-right"></i> Cintas y Lazos</a></li>
                    <li><a href="{{ route('catalogo.index', ['categoria' => 'Papeles y Envolturas']) }}"><i class="fas fa-chevron-right"></i> Papeles</a></li>
                    <li><a href="{{ route('catalogo.index', ['categoria' => 'Cajas y Empaques']) }}"><i class="fas fa-chevron-right"></i> Cajas</a></li>
                    <li><a href="{{ route('catalogo.index', ['categoria' => 'Flores y Follaje']) }}"><i class="fas fa-chevron-right"></i> Flores</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contacto</h4>
                <div class="footer-contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Av. Pérez Guerrero 5-27<br>y Sucre, Ibarra, Imbabura</span>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>0997 874 363</span>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>anturiosimportadora<br>@gmail.com</span>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Importadora Anturios. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            document.getElementById('navLinks').classList.toggle('open');
        }
    </script>
</body>
</html>
