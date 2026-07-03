<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Importadora Anturios — Sistema de gestión de inventario y ventas. Tu aliado en importación y distribución.">
    <title>Importadora Anturios — Sistema de Gestión</title>
    <link rel="icon" href="{{ asset('LogoEmpresa.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --primary: #E91E8C;
            --primary-dark: #C4177A;
            --primary-light: #F472B6;
            --primary-glow: rgba(233, 30, 140, 0.3);
            --primary-subtle: rgba(233, 30, 140, 0.08);
            --dark: #1A1A2E;
            --dark-soft: #2D2D44;
            --text-primary: #2D2D2D;
            --text-secondary: #6B7280;
            --text-light: #9CA3AF;
            --white: #FFFFFF;
            --off-white: #FAFAFA;
            --glass-bg: rgba(255, 255, 255, 0.12);
            --glass-border: rgba(255, 255, 255, 0.18);
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-md: 0 8px 30px rgba(0,0,0,0.12);
            --shadow-lg: 0 20px 60px rgba(0,0,0,0.15);
            --shadow-glow: 0 0 40px rgba(233, 30, 140, 0.15);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --radius-xl: 28px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== RESET & BASE ===== */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            font-size: 16px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-primary);
            background: var(--off-white);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* ===== INTRO SPLASH SCREEN ===== */
        .intro-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--dark) 0%, #16213E 50%, var(--dark) 100%);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            transition: opacity 0.8s ease, visibility 0.8s ease;
        }

        .intro-overlay.hide {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .intro-logo-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .intro-logo {
            width: 120px;
            height: auto;
            animation: introLogoIn 1s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            opacity: 0;
            filter: drop-shadow(0 0 30px var(--primary-glow));
        }

        .intro-brand-name {
            font-size: 2rem;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 6px;
            text-transform: uppercase;
            opacity: 0;
            animation: introTextIn 0.8s ease forwards 0.5s;
        }

        .intro-brand-sub {
            font-size: 0.9rem;
            font-weight: 300;
            color: var(--primary-light);
            letter-spacing: 10px;
            text-transform: uppercase;
            opacity: 0;
            animation: introTextIn 0.8s ease forwards 0.7s;
        }

        .intro-line {
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            animation: introLineExpand 0.8s ease forwards 0.4s;
        }

        @keyframes introLogoIn {
            0% { opacity: 0; transform: scale(0.3) translateY(20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        @keyframes introTextIn {
            0% { opacity: 0; transform: translateY(15px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes introLineExpand {
            0% { width: 0; opacity: 0; }
            100% { width: 80px; opacity: 1; }
        }

        /* ===== PARTICLES CANVAS ===== */
        #particles-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
            opacity: 0;
            animation: fadeIn 1s ease forwards 1.5s;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 18px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
            transition: var(--transition);
            background: transparent;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 1px 20px rgba(0,0,0,0.06);
            padding: 12px 40px;
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
            transition: var(--transition);
        }

        .nav-logo-text {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--white);
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: var(--transition);
        }

        .navbar.scrolled .nav-logo-text {
            color: var(--text-primary);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: rgba(255,255,255,0.85);
            font-weight: 500;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            transition: var(--transition);
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: var(--transition);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .navbar.scrolled .nav-links a {
            color: var(--text-secondary);
        }

        .navbar.scrolled .nav-links a:hover {
            color: var(--primary);
        }

        .nav-cta {
            padding: 10px 28px;
            background: var(--primary);
            color: var(--white) !important;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.88rem;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            letter-spacing: 0.5px;
            text-decoration: none;
        }

        .nav-cta::after {
            display: none !important;
        }

        .nav-cta:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(233, 30, 140, 0.35);
        }

        /* Mobile menu toggle */
        .nav-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 5px;
        }

        .nav-toggle span {
            width: 24px;
            height: 2px;
            background: var(--white);
            transition: var(--transition);
            border-radius: 2px;
        }

        .navbar.scrolled .nav-toggle span {
            background: var(--text-primary);
        }

        /* ===== HERO SECTION ===== */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background: linear-gradient(160deg, var(--dark) 0%, #16213E 40%, var(--dark-soft) 100%);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('{{ asset("images/hero-bg.png") }}') center/cover no-repeat;
            opacity: 0.15;
            mix-blend-mode: soft-light;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 120px;
            background: linear-gradient(to top, var(--off-white), transparent);
            z-index: 2;
        }

        /* Decorative blobs */
        .hero-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            z-index: 0;
        }

        .hero-blob-1 {
            width: 500px;
            height: 500px;
            background: var(--primary);
            top: -100px;
            right: -150px;
            animation: blobFloat 8s ease-in-out infinite;
        }

        .hero-blob-2 {
            width: 350px;
            height: 350px;
            background: #7C3AED;
            bottom: -50px;
            left: -100px;
            animation: blobFloat 10s ease-in-out infinite reverse;
        }

        @keyframes blobFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -20px) scale(1.05); }
            66% { transform: translate(-20px, 15px) scale(0.95); }
        }

        .hero-content {
            position: relative;
            z-index: 3;
            text-align: center;
            padding: 0 20px;
            max-width: 800px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 50px;
            color: var(--primary-light);
            font-size: 0.82rem;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 28px;
            backdrop-filter: blur(10px);
            opacity: 0;
            animation: fadeInUp 0.8s ease forwards 2.2s;
        }

        .hero-badge i {
            font-size: 0.7rem;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 6vw, 4.2rem);
            font-weight: 800;
            color: var(--white);
            line-height: 1.15;
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeInUp 0.8s ease forwards 2.4s;
        }

        .hero h1 .text-gradient {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: clamp(1rem, 2.5vw, 1.2rem);
            color: rgba(255, 255, 255, 0.65);
            font-weight: 300;
            line-height: 1.7;
            max-width: 560px;
            margin: 0 auto 40px;
            opacity: 0;
            animation: fadeInUp 0.8s ease forwards 2.6s;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
            opacity: 0;
            animation: fadeInUp 0.8s ease forwards 2.8s;
        }

        .btn-primary-hero {
            padding: 16px 40px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            border: none;
            border-radius: 50px;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(233, 30, 140, 0.4);
        }

        .btn-secondary-hero {
            padding: 16px 40px;
            background: transparent;
            color: var(--white);
            border: 1.5px solid rgba(255,255,255,0.25);
            border-radius: 50px;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-secondary-hero:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.5);
            transform: translateY(-3px);
        }

        /* Scroll indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            opacity: 0;
            animation: fadeInUp 0.8s ease forwards 3.2s;
        }

        .scroll-indicator span {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.4);
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .scroll-mouse {
            width: 24px;
            height: 38px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 12px;
            position: relative;
        }

        .scroll-mouse::before {
            content: '';
            position: absolute;
            top: 6px;
            left: 50%;
            transform: translateX(-50%);
            width: 3px;
            height: 8px;
            background: var(--primary-light);
            border-radius: 3px;
            animation: scrollDown 1.5s ease-in-out infinite;
        }

        @keyframes scrollDown {
            0% { opacity: 1; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(14px); }
        }

        /* ===== SECTIONS COMMON ===== */
        .section {
            padding: 100px 40px;
            position: relative;
        }

        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 64px;
        }

        .section-label {
            display: inline-block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--primary);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .section-title {
            font-size: clamp(1.8rem, 4vw, 2.6rem);
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.2;
            margin-bottom: 16px;
        }

        .section-desc {
            font-size: 1.05rem;
            color: var(--text-secondary);
            line-height: 1.7;
            font-weight: 300;
        }

        /* Fade-in on scroll */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ===== SERVICES SECTION ===== */
        .services {
            background: var(--white);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 28px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .service-card {
            background: var(--off-white);
            border-radius: var(--radius-lg);
            padding: 40px 32px;
            transition: var(--transition);
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(233, 30, 140, 0.1);
            background: var(--white);
        }

        .service-card:hover::before {
            transform: scaleX(1);
        }

        .service-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 24px;
            background: var(--primary-subtle);
            color: var(--primary);
            transition: var(--transition);
        }

        .service-card:hover .service-icon {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            transform: scale(1.1);
        }

        .service-card h3 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 12px;
        }

        .service-card p {
            font-size: 0.92rem;
            color: var(--text-secondary);
            line-height: 1.65;
            font-weight: 400;
        }

        /* ===== STATS SECTION ===== */
        .stats {
            background: linear-gradient(160deg, var(--dark) 0%, #16213E 50%, var(--dark-soft) 100%);
            position: relative;
            overflow: hidden;
        }

        .stats::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('{{ asset("images/hero-bg.png") }}') center/cover no-repeat;
            opacity: 0.08;
        }

        .stats .section-label {
            color: var(--primary-light);
        }

        .stats .section-title {
            color: var(--white);
        }

        .stats .section-desc {
            color: rgba(255,255,255,0.55);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 32px;
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .stat-card {
            text-align: center;
            padding: 32px 20px;
            border-radius: var(--radius-lg);
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-6px);
            background: rgba(255,255,255,0.18);
            box-shadow: var(--shadow-glow);
        }

        .stat-number {
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--white);
            margin-bottom: 6px;
            line-height: 1;
        }

        .stat-number .stat-suffix {
            font-size: 1.8rem;
            color: var(--primary-light);
        }

        .stat-label {
            font-size: 0.88rem;
            color: rgba(255,255,255,0.55);
            font-weight: 400;
            letter-spacing: 1px;
        }

        /* ===== CTA SECTION ===== */
        .cta-section {
            background: var(--white);
            text-align: center;
            padding: 80px 40px;
        }

        .cta-card {
            max-width: 680px;
            margin: 0 auto;
            padding: 64px 48px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: var(--radius-xl);
            position: relative;
            overflow: hidden;
        }

        .cta-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1), transparent);
            border-radius: 50%;
        }

        .cta-card h2 {
            font-size: clamp(1.6rem, 3.5vw, 2.2rem);
            font-weight: 800;
            color: var(--white);
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .cta-card p {
            font-size: 1.05rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 32px;
            position: relative;
            z-index: 1;
            font-weight: 300;
        }

        .btn-cta-white {
            padding: 16px 44px;
            background: var(--white);
            color: var(--primary);
            border: none;
            border-radius: 50px;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-cta-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.2);
        }

        /* ===== CURSOR GLOW ===== */
        .cursor-glow {
            position: fixed;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(233, 30, 140, 0.12) 0%, rgba(233, 30, 140, 0.04) 40%, transparent 70%);
            pointer-events: none;
            z-index: 4;
            transform: translate(-50%, -50%);
            transition: opacity 0.3s ease;
            opacity: 0;
            mix-blend-mode: screen;
        }

        .cursor-glow.active {
            opacity: 1;
        }

        /* ===== CONTACT SECTION ===== */
        .contact {
            background: var(--off-white);
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 28px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .contact-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 36px 32px;
            text-align: center;
            transition: var(--transition);
            border: 1px solid #F3F4F6;
            position: relative;
            overflow: hidden;
        }

        .contact-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .contact-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
            border-color: rgba(233, 30, 140, 0.12);
        }

        .contact-card:hover::after {
            transform: scaleX(1);
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, var(--primary-subtle), rgba(233, 30, 140, 0.12));
            color: var(--primary);
            transition: var(--transition);
        }

        .contact-card:hover .contact-icon {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            transform: scale(1.1) rotate(5deg);
        }

        .contact-card h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .contact-card p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            line-height: 1.6;
            font-weight: 400;
        }

        .contact-card a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .contact-card a:hover {
            color: var(--primary-dark);
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--dark);
            padding: 0;
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

        .footer-bottom .heart {
            color: var(--primary);
            font-size: 0.7rem;
            animation: heartbeat 1.5s ease-in-out infinite;
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        /* ===== LOGIN MODAL ===== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(10, 10, 30, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 5000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.4s ease, visibility 0.4s ease;
            padding: 20px;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .login-modal {
            width: 100%;
            max-width: 440px;
            background: var(--white);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transform: translateY(30px) scale(0.95);
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }

        .modal-overlay.active .login-modal {
            transform: translateY(0) scale(1);
        }

        /* Modal header gradient */
        .modal-header {
            background: linear-gradient(135deg, var(--dark), #16213E);
            padding: 40px 40px 36px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200px;
            height: 200px;
            background: var(--primary);
            border-radius: 50%;
            opacity: 0.15;
            filter: blur(40px);
        }

        .modal-header-logo {
            width: 64px;
            height: auto;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.2));
        }

        .modal-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }

        .modal-header p {
            font-size: 0.88rem;
            color: rgba(255,255,255,0.5);
            font-weight: 300;
            position: relative;
            z-index: 1;
        }

        /* Modal body */
        .modal-body {
            padding: 36px 40px 40px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }

        .form-group label i {
            color: var(--primary);
            margin-right: 6px;
            font-size: 0.78rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            padding: 14px 18px;
            background: var(--off-white);
            border: 1.5px solid #E5E7EB;
            border-radius: var(--radius-md);
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            color: var(--text-primary);
            transition: var(--transition);
            outline: none;
        }

        .input-wrapper input::placeholder {
            color: var(--text-light);
            font-weight: 300;
        }

        .input-wrapper input:focus {
            border-color: var(--primary);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(233, 30, 140, 0.1);
        }

        .input-wrapper .toggle-password {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            padding: 4px;
            transition: var(--transition);
            font-size: 0.95rem;
        }

        .input-wrapper .toggle-password:hover {
            color: var(--primary);
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .checkbox-wrapper input[type="checkbox"] {
            display: none;
        }

        .custom-checkbox {
            width: 18px;
            height: 18px;
            border: 2px solid #D1D5DB;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .custom-checkbox i {
            font-size: 0.6rem;
            color: var(--white);
            opacity: 0;
            transition: var(--transition);
        }

        .checkbox-wrapper input[type="checkbox"]:checked + .custom-checkbox {
            background: var(--primary);
            border-color: var(--primary);
        }

        .checkbox-wrapper input[type="checkbox"]:checked + .custom-checkbox i {
            opacity: 1;
        }

        .checkbox-wrapper span {
            font-size: 0.85rem;
            color: var(--text-secondary);
            font-weight: 400;
        }

        .forgot-link {
            font-size: 0.83rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .forgot-link:hover {
            color: var(--primary-dark);
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            border: none;
            border-radius: var(--radius-md);
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-login:hover {
            box-shadow: 0 8px 30px rgba(233, 30, 140, 0.4);
            transform: translateY(-2px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Modal close button */
        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            border: none;
            color: rgba(255,255,255,0.7);
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            z-index: 2;
        }

        .modal-close:hover {
            background: rgba(255,255,255,0.2);
            color: var(--white);
        }

        /* Error alert */
        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert-error i {
            color: #EF4444;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .alert-error ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .alert-error li {
            color: #DC2626;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .navbar {
                padding: 14px 20px;
            }

            .navbar.scrolled {
                padding: 10px 20px;
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
                font-size: 1.05rem;
            }

            .nav-toggle {
                display: flex;
                z-index: 1001;
            }

            .section {
                padding: 60px 20px;
            }

            .hero-actions {
                flex-direction: column;
            }

            .modal-body,
            .modal-header {
                padding-left: 24px;
                padding-right: 24px;
            }

            .cta-card {
                padding: 40px 28px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .stat-number {
                font-size: 2.2rem;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .footer-main {
                grid-template-columns: 1fr;
                gap: 32px;
                padding: 40px 24px 32px;
            }

            .footer-brand {
                text-align: center;
                align-items: center;
            }

            .footer-col h4::after {
                left: 50%;
                transform: translateX(-50%);
            }

            .footer-col {
                text-align: center;
            }

            .footer-col ul li a {
                justify-content: center;
            }

            .footer-contact-item {
                justify-content: center;
            }

            .footer-social {
                justify-content: center;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .cursor-glow {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .intro-brand-name {
                font-size: 1.4rem;
                letter-spacing: 4px;
            }

            .intro-brand-sub {
                font-size: 0.75rem;
                letter-spacing: 6px;
            }
        }
    </style>
</head>
<body>

    <!-- ===== INTRO SPLASH SCREEN ===== -->
    <div class="intro-overlay" id="introOverlay">
        <div class="intro-logo-wrapper">
            <img src="{{ asset('images/logo-empresa.png') }}" alt="Anturios Logo" class="intro-logo" id="introLogo">
            <div class="intro-line"></div>
            <div class="intro-brand-name">Anturios</div>
            <div class="intro-brand-sub">Importadora</div>
        </div>
    </div>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar" id="navbar">
        <a href="#" class="nav-logo">
            <img src="{{ asset('images/logo-empresa.png') }}" alt="Anturios">
            <span class="nav-logo-text">Anturios</span>
        </a>
        <ul class="nav-links" id="navLinks">
            <li><a href="#hero">Inicio</a></li>
            <li><a href="#servicios">Nosotros</a></li>
            <li><a href="#estadisticas">Impacto</a></li>
            <li><a href="#contacto">Contacto</a></li>
            <li><a href="#" class="nav-cta" onclick="openLoginModal(); return false;">Iniciar Sesión</a></li>
        </ul>
        <button class="nav-toggle" id="navToggle" onclick="toggleMobileMenu()" aria-label="Menú">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </nav>

    <!-- ===== HERO SECTION ===== -->
    <section class="hero" id="hero">
        <div class="hero-blob hero-blob-1"></div>
        <div class="hero-blob hero-blob-2"></div>

        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-circle"></i>
                Sistema de Gestión Empresarial
            </div>
            <h1>
                Importadora<br>
                <span class="text-gradient">Anturios</span>
            </h1>
            <p class="hero-subtitle">
                Importadores directos de artículos de floristería. Gestiona tu inventario,
                ventas y operaciones desde una plataforma integral con envíos a nivel nacional.
            </p>
            <div class="hero-actions">
                <button class="btn-primary-hero" onclick="openLoginModal()">
                    Acceder al Sistema
                    <i class="fas fa-arrow-right"></i>
                </button>
                <a href="#servicios" class="btn-secondary-hero">
                    Conocer Más
                    <i class="fas fa-chevron-down"></i>
                </a>
            </div>
        </div>

        <div class="scroll-indicator">
            <div class="scroll-mouse"></div>
            <span>Scroll</span>
        </div>
    </section>

    <!-- ===== SERVICES SECTION ===== -->
    <section class="section services" id="servicios">
        <div class="section-header fade-in">
            <span class="section-label">¿Qué Hacemos?</span>
            <h2 class="section-title">Soluciones integrales para tu negocio</h2>
            <p class="section-desc">
                Brindamos herramientas avanzadas para optimizar cada aspecto de la cadena de suministro y distribución.
            </p>
        </div>

        <div class="services-grid">
            <div class="service-card fade-in">
                <div class="service-icon">
                    <i class="fas fa-ship"></i>
                </div>
                <h3>Importación Directa</h3>
                <p>Gestionamos importaciones de productos con los mejores estándares de calidad y tiempos de entrega óptimos.</p>
            </div>

            <div class="service-card fade-in">
                <div class="service-icon">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <h3>Gestión de Inventario</h3>
                <p>Control total sobre el stock de productos en múltiples bodegas con trazabilidad en tiempo real.</p>
            </div>

            <div class="service-card fade-in">
                <div class="service-icon">
                    <i class="fas fa-truck-fast"></i>
                </div>
                <h3>Distribución Eficiente</h3>
                <p>Red de distribución optimizada para garantizar que los productos lleguen a su destino de forma rápida y segura.</p>
            </div>

            <div class="service-card fade-in">
                <div class="service-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Análisis y Reportes</h3>
                <p>Dashboards y reportes detallados para la toma de decisiones basadas en datos reales de tu operación.</p>
            </div>
        </div>
    </section>

    <!-- ===== STATS SECTION ===== -->
    <section class="section stats" id="estadisticas">
        <div class="section-header fade-in">
            <span class="section-label">Nuestro Impacto</span>
            <h2 class="section-title">Cifras que respaldan nuestra experiencia</h2>
            <p class="section-desc">
                Años de compromiso y dedicación nos han permitido consolidarnos como un referente en el sector de importación.
            </p>
        </div>

        <div class="stats-grid">
            <div class="stat-card fade-in">
                <div class="stat-number">
                    <span class="counter" data-target="10">0</span><span class="stat-suffix">+</span>
                </div>
                <div class="stat-label">Años de Experiencia</div>
            </div>
            <div class="stat-card fade-in">
                <div class="stat-number">
                    <span class="counter" data-target="500">0</span><span class="stat-suffix">+</span>
                </div>
                <div class="stat-label">Productos Gestionados</div>
            </div>
            <div class="stat-card fade-in">
                <div class="stat-number">
                    <span class="counter" data-target="50">0</span><span class="stat-suffix">+</span>
                </div>
                <div class="stat-label">Clientes Satisfechos</div>
            </div>
            <div class="stat-card fade-in">
                <div class="stat-number">
                    <span class="counter" data-target="5">0</span><span class="stat-suffix">+</span>
                </div>
                <div class="stat-label">Bodegas Activas</div>
            </div>
        </div>
    </section>

    <!-- ===== CTA SECTION ===== -->
    <section class="cta-section">
        <div class="cta-card fade-in">
            <h2>¿Listo para gestionar tu negocio?</h2>
            <p>Accede al sistema de gestión integral de Importadora Anturios y lleva el control total de tus operaciones.</p>
            <button class="btn-cta-white" onclick="openLoginModal()">
                Iniciar Sesión
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </section>

    <!-- ===== CONTACT SECTION ===== -->
    <section class="section contact" id="contacto">
        <div class="section-header fade-in">
            <span class="section-label">Contáctanos</span>
            <h2 class="section-title">Estamos para ayudarte</h2>
            <p class="section-desc">
                ¿Tienes preguntas o necesitas asistencia? Comunícate con nuestro equipo a través de cualquiera de estos canales.
            </p>
        </div>

        <div class="contact-grid">
            <div class="contact-card fade-in">
                <div class="contact-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h3>Teléfono</h3>
                <p><a href="tel:+593997874363">0997 874 363</a></p>
                <p style="margin-top: 4px; font-size: 0.82rem; color: var(--text-light);">Lun - Vie, 8:00 - 20:00</p>
            </div>

            <div class="contact-card fade-in">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Correo Electrónico</h3>
                <p><a href="mailto:anturiosimportadora@gmail.com">anturiosimportadora@gmail.com</a></p>
                <p style="margin-top: 4px; font-size: 0.82rem; color: var(--text-light);">Respuesta en menos de 24 horas</p>
            </div>

            <div class="contact-card fade-in">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>Ubicación</h3>
                <p>Av. Pérez Guerrero 5-27 y Sucre</p>
                <p style="margin-top: 4px; font-size: 0.82rem; color: var(--text-light);">Ibarra, Imbabura — Ecuador</p>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="footer-main">
            <!-- Brand Column -->
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

            <!-- Quick Links -->
            <div class="footer-col">
                <h4>Enlaces Rápidos</h4>
                <ul>
                    <li><a href="#hero"><i class="fas fa-chevron-right"></i> Inicio</a></li>
                    <li><a href="#servicios"><i class="fas fa-chevron-right"></i> Nosotros</a></li>
                    <li><a href="#estadisticas"><i class="fas fa-chevron-right"></i> Impacto</a></li>
                    <li><a href="#contacto"><i class="fas fa-chevron-right"></i> Contacto</a></li>
                    <li><a href="#" onclick="openLoginModal(); return false;"><i class="fas fa-chevron-right"></i> Iniciar Sesión</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="footer-col">
                <h4>Servicios</h4>
                <ul>
                    <li><a href="#servicios"><i class="fas fa-chevron-right"></i> Importación</a></li>
                    <li><a href="#servicios"><i class="fas fa-chevron-right"></i> Inventario</a></li>
                    <li><a href="#servicios"><i class="fas fa-chevron-right"></i> Distribución</a></li>
                    <li><a href="#servicios"><i class="fas fa-chevron-right"></i> Reportes</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
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

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Importadora Anturios. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- ===== LOGIN MODAL ===== -->
    <div class="modal-overlay" id="loginModal">
        <div class="login-modal">
            <div class="modal-header">
                <button class="modal-close" onclick="closeLoginModal()" aria-label="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
                <img src="{{ asset('images/logo-empresa.png') }}" alt="Anturios" class="modal-header-logo">
                <h2>Bienvenido de vuelta</h2>
                <p>Inicia sesión para acceder al sistema</p>
            </div>

            <div class="modal-body">
                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                    @csrf

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i>
                            Correo Electrónico
                        </label>
                        <div class="input-wrapper">
                            <input
                                type="email"
                                name="email"
                                id="email"
                                required
                                placeholder="ejemplo@correo.com"
                                value="{{ old('email') }}"
                                autocomplete="email"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i>
                            Contraseña
                        </label>
                        <div class="input-wrapper">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                required
                                placeholder="Ingresa tu contraseña"
                                autocomplete="current-password"
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword()" id="toggleBtn" aria-label="Mostrar contraseña">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" id="remember_me" name="remember">
                            <div class="custom-checkbox">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>Recordar sesión</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="forgot-link">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" class="btn-login" id="loginBtn">
                        <i class="fas fa-sign-in-alt"></i>
                        Iniciar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== JAVASCRIPT ===== -->
    <script>
        // ===== INTRO ANIMATION =====
        document.addEventListener('DOMContentLoaded', function() {
            const intro = document.getElementById('introOverlay');

            // Hide intro after animation
            setTimeout(function() {
                intro.classList.add('hide');
            }, 2000);

            // Remove from DOM after transition
            setTimeout(function() {
                intro.style.display = 'none';
            }, 2800);

            // If there are validation errors, open modal automatically
            @if ($errors->any())
                setTimeout(function() {
                    openLoginModal();
                }, 2200);
            @endif
        });

        // ===== NAVBAR SCROLL EFFECT =====
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 60) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // ===== MOBILE MENU =====
        function toggleMobileMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('open');
        }

        // Close mobile menu on link click
        document.querySelectorAll('.nav-links a').forEach(function(link) {
            link.addEventListener('click', function() {
                document.getElementById('navLinks').classList.remove('open');
            });
        });

        // ===== LOGIN MODAL =====
        function openLoginModal() {
            document.getElementById('loginModal').classList.add('active');
            document.body.style.overflow = 'hidden';
            // Focus email field after modal animation
            setTimeout(function() {
                document.getElementById('email').focus();
            }, 450);
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close modal on overlay click
        document.getElementById('loginModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoginModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLoginModal();
            }
        });

        // ===== TOGGLE PASSWORD =====
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                eyeIcon.className = 'fas fa-eye';
            }
        }

        // ===== SCROLL FADE-IN ANIMATIONS =====
        const fadeElements = document.querySelectorAll('.fade-in');
        const observerOptions = {
            threshold: 0.15,
            rootMargin: '0px 0px -50px 0px'
        };

        const fadeObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    fadeObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);

        fadeElements.forEach(function(el) {
            fadeObserver.observe(el);
        });

        // ===== COUNTER ANIMATION =====
        const counters = document.querySelectorAll('.counter');
        let countersAnimated = false;

        const counterObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting && !countersAnimated) {
                    countersAnimated = true;
                    counters.forEach(function(counter) {
                        const target = parseInt(counter.getAttribute('data-target'));
                        const duration = 2000;
                        const step = target / (duration / 16);
                        let current = 0;

                        function updateCounter() {
                            current += step;
                            if (current < target) {
                                counter.textContent = Math.floor(current);
                                requestAnimationFrame(updateCounter);
                            } else {
                                counter.textContent = target;
                            }
                        }
                        updateCounter();
                    });
                }
            });
        }, { threshold: 0.3 });

        // Observe the stats section
        const statsSection = document.getElementById('estadisticas');
        if (statsSection) {
            counterObserver.observe(statsSection);
        }

        // ===== SMOOTH SCROLL FOR ANCHOR LINKS =====
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                e.preventDefault();
                const target = document.querySelector(targetId);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // ===== PARTICLE BACKGROUND (Subtle) =====
        (function() {
            const canvas = document.createElement('canvas');
            canvas.id = 'particles-canvas';
            document.body.prepend(canvas);
            const ctx = canvas.getContext('2d');
            let particles = [];
            const particleCount = 40;

            function resize() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }

            function createParticle() {
                return {
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    size: Math.random() * 2 + 0.5,
                    speedX: (Math.random() - 0.5) * 0.4,
                    speedY: (Math.random() - 0.5) * 0.4,
                    opacity: Math.random() * 0.3 + 0.1
                };
            }

            function init() {
                resize();
                particles = [];
                for (let i = 0; i < particleCount; i++) {
                    particles.push(createParticle());
                }
            }

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                // Only render particles in the hero area
                const heroH = window.innerHeight;

                particles.forEach(function(p) {
                    p.x += p.speedX;
                    p.y += p.speedY;

                    if (p.x < 0 || p.x > canvas.width) p.speedX *= -1;
                    if (p.y < 0 || p.y > heroH) p.speedY *= -1;

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                    ctx.fillStyle = 'rgba(233, 30, 140, ' + p.opacity + ')';
                    ctx.fill();
                });

                // Draw subtle connecting lines
                particles.forEach(function(a, i) {
                    particles.slice(i + 1).forEach(function(b) {
                        const dx = a.x - b.x;
                        const dy = a.y - b.y;
                        const dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < 150) {
                            ctx.beginPath();
                            ctx.moveTo(a.x, a.y);
                            ctx.lineTo(b.x, b.y);
                            ctx.strokeStyle = 'rgba(233, 30, 140, ' + (0.06 * (1 - dist / 150)) + ')';
                            ctx.lineWidth = 0.5;
                            ctx.stroke();
                        }
                    });
                });

                requestAnimationFrame(animate);
            }

            window.addEventListener('resize', resize);
            init();
            animate();
        })();

        // ===== CURSOR GLOW EFFECT =====
        (function() {
            const glow = document.createElement('div');
            glow.className = 'cursor-glow';
            document.body.appendChild(glow);

            const hero = document.querySelector('.hero');
            let isInHero = false;
            let mouseX = 0, mouseY = 0;
            let glowX = 0, glowY = 0;

            document.addEventListener('mousemove', function(e) {
                mouseX = e.clientX;
                mouseY = e.clientY;

                // Check if cursor is over the hero section
                const heroRect = hero.getBoundingClientRect();
                if (e.clientY >= heroRect.top && e.clientY <= heroRect.bottom &&
                    e.clientX >= heroRect.left && e.clientX <= heroRect.right) {
                    if (!isInHero) {
                        isInHero = true;
                        glow.classList.add('active');
                    }
                } else {
                    if (isInHero) {
                        isInHero = false;
                        glow.classList.remove('active');
                    }
                }
            });

            // Smooth follow with requestAnimationFrame
            function animateGlow() {
                glowX += (mouseX - glowX) * 0.12;
                glowY += (mouseY - glowY) * 0.12;
                glow.style.left = glowX + 'px';
                glow.style.top = glowY + 'px';
                requestAnimationFrame(animateGlow);
            }
            animateGlow();
        })();
    </script>

</body>
</html>