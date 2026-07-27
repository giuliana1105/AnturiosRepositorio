<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Importadora Anturios — Sistema de Gestión')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('LogoEmpresa.ico') }}">

    <!-- Google Fonts: Inter + JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ============================================
           DESIGN SYSTEM — Interface Design Tokens
           Personality: Sophistication & Trust
           Foundation: Warm (stone/cream)
           ============================================ */
        :root {
            /* ── Background ── */
            --bg-base:       #faf9f7;
            --bg-surface:    #ffffff;
            --bg-elevated:   #ffffff;
            --bg-sidebar:    #1c1917;
            --bg-sidebar-hover: rgba(255,255,255,0.06);
            --bg-sidebar-active: rgba(233,30,140,0.12);

            /* ── Borders ── */
            --border:        #e7e5e2;
            --border-light:  #f0eeeb;
            --border-sidebar: rgba(255,255,255,0.06);

            /* ── Text ── */
            --foreground:    #1c1917;
            --secondary:     #57534e;
            --muted:         #a8a29e;
            --faint:         #e7e5e4;
            --on-dark:       #f5f5f4;
            --on-dark-muted: rgba(255,255,255,0.5);

            /* ── Brand / Accent ── */
            --accent:        #E91E8C;
            --accent-dark:   #C4177A;
            --accent-light:  #F472B6;
            --accent-subtle: rgba(233,30,140,0.08);
            --accent-ring:   rgba(233,30,140,0.15);

            /* ── Semantic ── */
            --success:       #059669;
            --success-bg:    #ecfdf5;
            --success-border:#a7f3d0;
            --warning:       #d97706;
            --warning-bg:    #fffbeb;
            --warning-border:#fde68a;
            --danger:        #dc2626;
            --danger-bg:     #fef2f2;
            --danger-border: #fecaca;
            --info:          #0284c7;
            --info-bg:       #f0f9ff;
            --info-border:   #bae6fd;

            /* ── Spacing ── */
            --space-1: 4px;
            --space-2: 8px;
            --space-3: 12px;
            --space-4: 16px;
            --space-5: 20px;
            --space-6: 24px;
            --space-8: 32px;
            --space-10: 40px;
            --space-12: 48px;
            --space-16: 64px;

            /* ── Radius ── */
            --radius-xs: 4px;
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;

            /* ── Shadows ── */
            --shadow-xs: 0 1px 2px rgba(0,0,0,0.04);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.06), 0 2px 4px -2px rgba(0,0,0,0.04);

            /* ── Typography ── */
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, system-ui, sans-serif;
            --font-mono: 'JetBrains Mono', 'Fira Code', monospace;

            /* ── Transitions ── */
            --ease: cubic-bezier(0.4, 0, 0.2, 1);
            --duration: 0.2s;

            /* ── Sidebar ── */
            --sidebar-width: 260px;
        }

        /* ============================================
           RESET & BASE
           ============================================ */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-base);
            color: var(--foreground);
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ============================================
           LAYOUT
           ============================================ */
        #wrapper {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        /* ============================================
           SIDEBAR
           ============================================ */
        #sidebar-wrapper {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            background: var(--bg-sidebar);
            color: var(--on-dark);
            display: flex;
            flex-direction: column;
            transition: margin-left var(--duration) var(--ease);
            z-index: 1000;
            border-right: 1px solid rgba(255,255,255,0.04);
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: var(--space-5) var(--space-5);
            border-bottom: 1px solid var(--border-sidebar);
        }

        .sidebar-brand {
            color: var(--on-dark);
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: var(--space-2);
            letter-spacing: -0.3px;
        }

        .sidebar-brand:hover {
            color: var(--on-dark);
            text-decoration: none;
        }

        .sidebar-brand i {
            color: var(--accent);
            font-size: 18px;
        }

        /* User Profile */
        .user-profile {
            padding: var(--space-4) var(--space-5);
            border-bottom: 1px solid var(--border-sidebar);
            display: flex;
            align-items: center;
            gap: var(--space-3);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }

        .user-profile .user-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--on-dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-profile .user-role {
            font-size: 11px;
            color: var(--on-dark-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Navigation */
        .nav-section-title {
            padding: var(--space-5) var(--space-5) var(--space-2);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--on-dark-muted);
            font-weight: 600;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: var(--space-2) var(--space-3);
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .sidebar-nav::-webkit-scrollbar {
            display: none;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: var(--space-2) var(--space-3);
            color: var(--on-dark-muted);
            text-decoration: none;
            border-radius: var(--radius-sm);
            margin-bottom: 2px;
            transition: all var(--duration) var(--ease);
            font-weight: 400;
            font-size: 13px;
            border-left: 2px solid transparent;
        }

        .sidebar-link:hover,
        .sidebar-link[aria-expanded="true"] {
            background-color: var(--bg-sidebar-hover);
            color: var(--on-dark);
        }

        .sidebar-link.active {
            background-color: var(--bg-sidebar-active);
            color: var(--accent-light);
            border-left-color: var(--accent);
            font-weight: 500;
        }

        .sidebar-link i.icon-main {
            width: 20px;
            font-size: 14px;
            margin-right: var(--space-3);
            text-align: center;
            opacity: 0.7;
            transition: opacity var(--duration) var(--ease);
        }

        .sidebar-link:hover i.icon-main,
        .sidebar-link.active i.icon-main {
            opacity: 1;
        }

        .sidebar-link .arrow {
            margin-left: auto;
            font-size: 10px;
            transition: transform 0.3s var(--ease);
            opacity: 0.5;
        }

        .sidebar-link[aria-expanded="true"] .arrow {
            transform: rotate(90deg);
        }

        /* Collapse Sub-menus */
        .collapse-inner {
            padding-left: 44px;
            padding-right: var(--space-3);
            margin-bottom: var(--space-2);
        }

        .collapse-item {
            display: flex;
            align-items: center;
            padding: var(--space-2) var(--space-3);
            color: var(--on-dark-muted);
            text-decoration: none;
            font-size: 13px;
            border-radius: var(--radius-xs);
            transition: all var(--duration) var(--ease);
        }

        .collapse-item:hover {
            color: var(--on-dark);
            background-color: var(--bg-sidebar-hover);
        }

        .collapse-item i {
            font-size: 12px;
            opacity: 0.6;
        }

        /* Logout */
        .logout-container {
            padding: var(--space-3) var(--space-4);
            border-top: 1px solid var(--border-sidebar);
        }

        .btn-logout {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-2);
            background: transparent;
            color: var(--on-dark-muted);
            border: 1px solid rgba(255,255,255,0.08);
            padding: var(--space-2) var(--space-3);
            border-radius: var(--radius-sm);
            transition: all var(--duration) var(--ease);
            font-weight: 500;
            font-size: 13px;
            cursor: pointer;
            font-family: var(--font-sans);
        }

        .btn-logout:hover {
            background: rgba(220,38,38,0.12);
            color: #fca5a5;
            border-color: rgba(220,38,38,0.2);
        }

        /* ============================================
           MAIN CONTENT
           ============================================ */
        #page-content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-width: 0;
        }

        .top-navbar {
            display: none;
            background: var(--bg-surface);
            padding: var(--space-3) var(--space-4);
            border-bottom: 1px solid var(--border);
            align-items: center;
        }

        .content-scrollable {
            flex: 1;
            overflow-y: auto;
            padding: var(--space-8);
            position: relative;
        }

        /* Subtle logo watermark */
        .content-scrollable::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 300px;
            background: url("{{ asset('images/logo-empresa.png') }}") no-repeat center center;
            background-size: contain;
            opacity: 0.02;
            pointer-events: none;
            z-index: 0;
        }

        /* ============================================
           BREADCRUMBS
           ============================================ */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0 0 var(--space-6) 0;
            font-size: 12px;
        }

        .breadcrumb-item a {
            color: var(--muted);
            text-decoration: none;
            transition: color var(--duration) var(--ease);
        }

        .breadcrumb-item a:hover {
            color: var(--accent);
        }

        .breadcrumb-item.active span {
            color: var(--secondary);
            font-weight: 500;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: var(--muted);
        }

        /* ============================================
           PAGE HEADERS
           ============================================ */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: var(--space-6);
            flex-wrap: wrap;
            gap: var(--space-4);
        }

        .page-header h3,
        .page-header h2 {
            font-size: 20px;
            font-weight: 600;
            color: var(--foreground);
            margin: 0;
            line-height: 1.3;
        }

        .page-header .page-subtitle {
            font-size: 13px;
            color: var(--muted);
            margin-top: var(--space-1);
            font-weight: 400;
        }

        /* ============================================
           CARDS
           ============================================ */
        .card {
            border: 1px solid var(--border);
            box-shadow: var(--shadow-xs);
            border-radius: var(--radius-md);
            background: var(--bg-surface);
            margin-bottom: var(--space-6);
            transition: box-shadow var(--duration) var(--ease);
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-sm);
            transform: none;
        }

        .card-header {
            background: var(--bg-surface) !important;
            border-bottom: 1px solid var(--border);
            padding: var(--space-4) var(--space-5);
            color: var(--foreground) !important;
            font-size: 14px;
            font-weight: 600;
        }

        .card-body {
            padding: var(--space-5);
        }

        /* Dashboard stat cards */
        .stat-card {
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            background: var(--bg-surface);
            padding: var(--space-5);
            transition: all var(--duration) var(--ease);
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .stat-card:hover {
            border-color: var(--accent);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
            color: inherit;
            text-decoration: none;
        }

        .stat-card .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-bottom: var(--space-3);
            background: var(--accent-subtle);
            color: var(--accent);
            transition: all var(--duration) var(--ease);
        }

        .stat-card:hover .stat-icon {
            background: var(--accent);
            color: #fff;
        }

        .stat-card .stat-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--foreground);
        }

        .stat-card .stat-sublabel {
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
        }

        /* ============================================
           TABLES
           ============================================ */
        .table-responsive {
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .table {
            margin-bottom: 0;
            color: var(--foreground);
            font-size: 13px;
        }

        .table thead th {
            background-color: var(--bg-base);
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: var(--space-3) var(--space-4);
            border-bottom: 1px solid var(--border);
            border-top: none;
            white-space: nowrap;
        }

        .table tbody td {
            padding: var(--space-3) var(--space-4);
            vertical-align: middle;
            border-bottom: 1px solid var(--border-light);
            color: var(--secondary);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background-color: rgba(0,0,0,0.015);
        }

        .table-bordered,
        .table-bordered th,
        .table-bordered td {
            border-color: var(--border-light);
        }

        /* Monospace for codes and numbers */
        .font-mono {
            font-family: var(--font-mono);
            font-size: 12px;
        }

        /* ============================================
           BUTTONS
           ============================================ */
        .btn {
            font-family: var(--font-sans);
            font-weight: 500;
            font-size: 13px;
            padding: var(--space-2) var(--space-4);
            border-radius: var(--radius-sm);
            transition: all var(--duration) var(--ease);
            letter-spacing: 0;
            display: inline-flex;
            align-items: center;
            gap: var(--space-2);
            line-height: 1.5;
            border: 1px solid transparent;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Primary (accent) */
        .btn-info,
        .btn-primary {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .btn-info:hover,
        .btn-primary:hover {
            background: var(--accent-dark);
            border-color: var(--accent-dark);
            color: #fff;
            box-shadow: 0 4px 12px var(--accent-ring);
        }

        /* Success */
        .btn-success {
            background: var(--success);
            border-color: var(--success);
            color: #fff;
        }

        .btn-success:hover {
            background: #047857;
            border-color: #047857;
            color: #fff;
        }

        /* Warning */
        .btn-warning {
            background: var(--warning);
            border-color: var(--warning);
            color: #fff;
        }

        .btn-warning:hover {
            background: #b45309;
            border-color: #b45309;
            color: #fff;
        }

        /* Danger */
        .btn-danger {
            background: var(--danger);
            border-color: var(--danger);
            color: #fff;
        }

        .btn-danger:hover {
            background: #b91c1c;
            border-color: #b91c1c;
            color: #fff;
        }

        /* Secondary / Outline */
        .btn-secondary {
            background: var(--bg-surface);
            border-color: var(--border);
            color: var(--secondary);
        }

        .btn-secondary:hover {
            background: var(--bg-base);
            border-color: var(--muted);
            color: var(--foreground);
        }

        .btn-outline-secondary {
            background: transparent;
            border-color: var(--border);
            color: var(--secondary);
        }

        .btn-outline-secondary:hover {
            background: var(--bg-base);
            border-color: var(--muted);
            color: var(--foreground);
        }

        .btn-light {
            background: var(--bg-base);
            border-color: var(--border);
            color: var(--secondary);
        }

        .btn-light:hover {
            background: var(--faint);
            color: var(--foreground);
        }

        /* Small buttons */
        .btn-sm {
            padding: var(--space-1) var(--space-2);
            font-size: 12px;
            border-radius: var(--radius-xs);
        }

        /* Icon-only buttons */
        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-sm);
        }

        /* Remove pill from all buttons */
        .rounded-pill {
            border-radius: var(--radius-sm) !important;
        }

        /* ============================================
           FORMS
           ============================================ */
        .form-control,
        .form-select {
            font-family: var(--font-sans);
            font-size: 13px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            padding: var(--space-2) var(--space-3);
            color: var(--foreground);
            background-color: var(--bg-surface);
            transition: border-color var(--duration) var(--ease), box-shadow var(--duration) var(--ease);
            height: 38px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-ring);
            background-color: var(--bg-surface);
            color: var(--foreground);
        }

        .form-control::placeholder {
            color: var(--muted);
        }

        textarea.form-control {
            height: auto;
            min-height: 80px;
        }

        .form-label {
            font-weight: 500;
            color: var(--secondary);
            font-size: 13px;
            margin-bottom: var(--space-1);
        }

        .form-text {
            font-size: 12px;
            color: var(--muted);
        }

        /* Input groups */
        .input-group .form-control {
            border-radius: var(--radius-sm);
        }

        .input-group-text {
            background: var(--bg-base);
            border: 1px solid var(--border);
            color: var(--muted);
            font-size: 13px;
        }

        /* ============================================
           BADGES
           ============================================ */
        .badge {
            font-weight: 500;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: var(--radius-xs);
            letter-spacing: 0.3px;
        }

        .badge.bg-success,
        .badge-success {
            background-color: var(--success-bg) !important;
            color: var(--success) !important;
            border: 1px solid var(--success-border);
        }

        .badge.bg-warning,
        .badge-warning {
            background-color: var(--warning-bg) !important;
            color: var(--warning) !important;
            border: 1px solid var(--warning-border);
        }

        .badge.bg-danger,
        .badge-danger {
            background-color: var(--danger-bg) !important;
            color: var(--danger) !important;
            border: 1px solid var(--danger-border);
        }

        .badge.bg-info,
        .badge-info {
            background-color: var(--info-bg) !important;
            color: var(--info) !important;
            border: 1px solid var(--info-border);
        }

        .badge.bg-primary {
            background-color: var(--accent-subtle) !important;
            color: var(--accent) !important;
            border: 1px solid rgba(233,30,140,0.2);
        }

        .badge.bg-secondary {
            background-color: var(--bg-base) !important;
            color: var(--secondary) !important;
            border: 1px solid var(--border);
        }

        /* ============================================
           ALERTS
           ============================================ */
        .alert {
            border: none;
            border-radius: var(--radius-md);
            padding: var(--space-3) var(--space-4);
            font-size: 13px;
            border-left: 3px solid;
        }

        .alert-success {
            background-color: var(--success-bg);
            color: #065f46;
            border-left-color: var(--success);
        }

        .alert-danger {
            background-color: var(--danger-bg);
            color: #991b1b;
            border-left-color: var(--danger);
        }

        .alert-warning {
            background-color: var(--warning-bg);
            color: #92400e;
            border-left-color: var(--warning);
        }

        .alert-info {
            background-color: var(--info-bg);
            color: #075985;
            border-left-color: var(--info);
        }

        /* ============================================
           PAGINATION
           ============================================ */
        .pagination {
            gap: var(--space-1);
        }

        .pagination .page-link {
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            color: var(--secondary);
            font-size: 13px;
            font-weight: 500;
            padding: var(--space-2) var(--space-3);
            transition: all var(--duration) var(--ease);
            margin: 0;
            min-width: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--accent);
            border-color: var(--accent);
            color: #fff;
            box-shadow: 0 2px 8px var(--accent-ring);
        }

        .pagination .page-link:hover:not(.active) {
            background-color: var(--bg-base);
            border-color: var(--muted);
            color: var(--foreground);
        }

        .pagination .page-item.disabled .page-link {
            background-color: var(--bg-base);
            border-color: var(--border-light);
            color: var(--muted);
        }

        /* ============================================
           MODALS
           ============================================ */
        .modal-content {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .modal-header {
            border-bottom: 1px solid var(--border);
            padding: var(--space-4) var(--space-5);
        }

        .modal-title {
            font-size: 16px;
            font-weight: 600;
        }

        .modal-body {
            padding: var(--space-5);
        }

        .modal-footer {
            border-top: 1px solid var(--border);
            padding: var(--space-3) var(--space-5);
        }

        /* ============================================
           UTILITY CLASSES
           ============================================ */
        .text-brand {
            color: var(--accent) !important;
        }

        .bg-brand {
            background-color: var(--accent) !important;
            color: white !important;
        }

        .bg-brand-light {
            background-color: var(--accent-subtle) !important;
        }

        .text-muted {
            color: var(--muted) !important;
        }

        .text-secondary {
            color: var(--secondary) !important;
        }

        .border-accent {
            border-color: var(--accent) !important;
        }

        /* Empty states */
        .empty-state {
            text-align: center;
            padding: var(--space-12) var(--space-8);
            color: var(--muted);
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: var(--space-4);
            opacity: 0.4;
        }

        .empty-state h4 {
            font-size: 16px;
            font-weight: 500;
            color: var(--secondary);
            margin-bottom: var(--space-2);
        }

        .empty-state p {
            font-size: 13px;
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 768px) {
            #sidebar-wrapper {
                margin-left: calc(var(--sidebar-width) * -1);
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
                padding: var(--space-4);
            }

            .page-header {
                flex-direction: column;
            }
        }

        /* ============================================
           SCROLL BARS (subtle)
           ============================================ */
        .content-scrollable::-webkit-scrollbar {
            width: 6px;
        }

        .content-scrollable::-webkit-scrollbar-track {
            background: transparent;
        }

        .content-scrollable::-webkit-scrollbar-thumb {
            background: var(--faint);
            border-radius: 3px;
        }

        .content-scrollable::-webkit-scrollbar-thumb:hover {
            background: var(--muted);
        }

        /* ============================================
           SPINNER OVERRIDE
           ============================================ */
        .spinner-border {
            color: var(--accent);
        }

        @yield('styles')
    </style>
</head>
<body>

@if (Auth::check())
    <div id="wrapper">
        
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            
            <div class="sidebar-header">
                <a href="{{ route('home') }}" class="sidebar-brand">
                    <i class="fas fa-seedling"></i>
                    <span>Anturios</span>
                </a>
            </div>

            <div class="user-profile">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="overflow: hidden; min-width: 0;">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ auth()->user()->cargoNombre() }}</div>
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
                <div class="ms-3 fw-bold" style="color: var(--foreground);">
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
                                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i class="fas fa-home"></i> Inicio</a></li>
                                @php $segments = ''; @endphp
                                @foreach(request()->segments() as $segment)
                                    @php $segments .= '/'.$segment; @endphp
                                    <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" aria-current="page">
                                        @if(!$loop->last)
                                            <a href="{{ url($segments) }}" class="text-decoration-none text-capitalize">{{ str_replace('-', ' ', $segment) }}</a>
                                        @else
                                            <span class="text-capitalize">{{ str_replace('-', ' ', $segment) }}</span>
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
        if(!form.classList.contains('no-spinner')) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Procesando...';
                
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