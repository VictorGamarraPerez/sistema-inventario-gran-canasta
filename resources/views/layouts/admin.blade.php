<!DOCTYPE html>
<html lang="es" data-theme="{{ Auth::user()->theme ?? 'claro' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - La Gran Canasta</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            padding: 20px 0;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 10px;
        }

        .sidebar-header {
            padding: 0 20px 30px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .sidebar-logo {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 30px;
            color: #667eea;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .sidebar-title {
            color: white;
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu-item {
            margin-bottom: 5px;
        }

        .sidebar-menu-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 15px;
            border-left: 3px solid transparent;
        }

        .sidebar-menu-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: white;
        }

        .sidebar-menu-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left-color: white;
            font-weight: 600;
        }

        .sidebar-menu-link i {
            width: 25px;
            margin-right: 12px;
            font-size: 18px;
        }

        .sidebar-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 15px 20px;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Top Navbar */
        .top-navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            margin: 0;
        }

        .user-role {
            font-size: 12px;
            color: #666;
            margin: 0;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        .dropdown-toggle::after {
            display: none;
        }

        /* Content Area */
        .content-area {
            padding: 30px;
        }

        /* Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            border-left: 4px solid #667eea;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .stat-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-card-title {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }

        .stat-card-value {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        /* Toggle Sidebar Button */
        .toggle-sidebar {
            display: none;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .toggle-sidebar {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .navbar-title {
                font-size: 18px;
            }

            .user-info {
                display: none;
            }

            .content-area {
                padding: 15px;
            }
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Tema Oscuro */
        html[data-theme="oscuro"] body {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }

        html[data-theme="oscuro"] .content-area {
            background-color: #1a1a1a;
        }

        html[data-theme="oscuro"] .card {
            background-color: #2d2d2d;
            color: #e0e0e0;
            border-color: #404040;
        }

        html[data-theme="oscuro"] .card-header {
            background-color: #2d2d2d !important;
            border-bottom-color: #404040;
            color: #e0e0e0;
        }

        html[data-theme="oscuro"] .table {
            color: #e0e0e0;
            background-color: #2d2d2d;
        }

        html[data-theme="oscuro"] .table thead th {
            background-color: #383838;
            color: #e0e0e0;
            border-color: #404040;
        }

        html[data-theme="oscuro"] .table tbody td {
            border-color: #404040;
            background-color: #2d2d2d;
        }

        html[data-theme="oscuro"] .table-striped tbody tr:nth-of-type(odd) {
            background-color: #333333;
        }

        html[data-theme="oscuro"] .table-hover tbody tr:hover {
            background-color: #383838;
        }

        html[data-theme="oscuro"] .form-control,
        html[data-theme="oscuro"] .form-select {
            background-color: #383838;
            border-color: #505050;
            color: #e0e0e0;
        }

        html[data-theme="oscuro"] .form-control:focus,
        html[data-theme="oscuro"] .form-select:focus {
            background-color: #383838;
            border-color: #667eea;
            color: #e0e0e0;
        }

        html[data-theme="oscuro"] .form-control::placeholder {
            color: #888;
        }

        html[data-theme="oscuro"] .input-group-text {
            background-color: #383838;
            border-color: #505050;
            color: #e0e0e0;
        }

        html[data-theme="oscuro"] .top-navbar {
            background-color: #2d2d2d;
            border-bottom-color: #404040;
        }

        html[data-theme="oscuro"] .navbar-title {
            color: #e0e0e0;
        }

        html[data-theme="oscuro"] .user-name {
            color: #e0e0e0;
        }

        html[data-theme="oscuro"] .user-role {
            color: #999;
        }

        html[data-theme="oscuro"] .dropdown-menu {
            background-color: #2d2d2d;
            border-color: #404040;
        }

        html[data-theme="oscuro"] .dropdown-item {
            color: #e0e0e0;
        }

        html[data-theme="oscuro"] .dropdown-item:hover {
            background-color: #383838;
            color: #e0e0e0;
        }

        html[data-theme="oscuro"] .modal-content {
            background-color: #2d2d2d;
            color: #e0e0e0;
            border-color: #404040;
        }

        html[data-theme="oscuro"] .modal-header,
        html[data-theme="oscuro"] .modal-footer {
            border-color: #404040;
        }

        html[data-theme="oscuro"] .btn-close {
            filter: invert(1);
        }

        html[data-theme="oscuro"] .alert {
            border-color: #404040;
        }

        html[data-theme="oscuro"] .alert-success {
            background-color: #1e4620;
            color: #a3d9a5;
            border-color: #2d5a2f;
        }

        html[data-theme="oscuro"] .alert-danger {
            background-color: #4a1e1e;
            color: #f8a5a5;
            border-color: #6b2d2d;
        }

        html[data-theme="oscuro"] .alert-warning {
            background-color: #4a3d1e;
            color: #f8d7a5;
            border-color: #6b5a2d;
        }

        html[data-theme="oscuro"] .alert-info {
            background-color: #1e3a4a;
            color: #a5d5f8;
            border-color: #2d556b;
        }

        html[data-theme="oscuro"] .badge {
            color: #fff;
        }

        html[data-theme="oscuro"] .text-muted {
            color: #999 !important;
        }

        html[data-theme="oscuro"] hr {
            border-color: #404040;
            opacity: 1;
        }

        html[data-theme="oscuro"] .border {
            border-color: #404040 !important;
        }

        html[data-theme="oscuro"] .card-body h6 {
            color: #e0e0e0;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-shopping-basket"></i>
            </div>
            <h1 class="sidebar-title">LA GRAN<br>CANASTA</h1>
        </div>

        <ul class="sidebar-menu">
            {{-- Dashboard - Todos los roles --}}
            <li class="sidebar-menu-item">
                <a href="{{ route('dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- Productos - Todos los roles --}}
            <li class="sidebar-menu-item">
                <a href="{{ route('products.index') }}" class="sidebar-menu-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Productos</span>
                </a>
            </li>

            {{-- Entradas - Todos los roles --}}
            <li class="sidebar-menu-item">
                <a href="{{ route('entries.index') }}" class="sidebar-menu-link {{ request()->routeIs('entries.*') ? 'active' : '' }}">
                    <i class="fas fa-arrow-down"></i>
                    <span>Entradas</span>
                </a>
            </li>

            {{-- Salidas - Todos los roles --}}
            <li class="sidebar-menu-item">
                <a href="{{ route('exits.index') }}" class="sidebar-menu-link {{ request()->routeIs('exits.*') ? 'active' : '' }}">
                    <i class="fas fa-arrow-up"></i>
                    <span>Salidas</span>
                </a>
            </li>

            {{-- Reportes - Solo administrador, supervisor y consulta --}}
            @if(in_array(Auth::user()->role, ['administrador', 'supervisor', 'consulta']))
            <li class="sidebar-menu-item">
                <a href="{{ route('reports.index') }}" class="sidebar-menu-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reportes</span>
                </a>
            </li>
            @endif

            {{-- Usuarios - Solo administrador --}}
            @if(Auth::user()->role === 'administrador')
            <li class="sidebar-menu-item">
                <a href="{{ route('users.index') }}" class="sidebar-menu-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
            </li>
            @endif
            
            <div class="sidebar-divider"></div>
            
            {{-- Configuración - Todos los roles --}}
            <li class="sidebar-menu-item">
                <a href="{{ route('settings') }}" class="sidebar-menu-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </a>
            </li>

            {{-- Cerrar sesión - Todos --}}
            <li class="sidebar-menu-item">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <a href="#" class="sidebar-menu-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar sesión</span>
                    </a>
                </form>
            </li>
        </ul>
    </aside>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="toggle-sidebar" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="navbar-title">@yield('page-title', 'Dashboard')</h2>
            </div>

            <div class="navbar-user">
                <div class="user-info">
                    <p class="user-name">{{ Auth::user()->name }}</p>
                    <p class="user-role">
                        @switch(Auth::user()->role)
                            @case('administrador')
                                Administrador
                                @break
                            @case('supervisor')
                                Supervisor
                                @break
                            @case('almacen')
                                Almacén
                                @break
                            @case('consulta')
                                Consulta
                                @break
                            @default
                                {{ ucfirst(Auth::user()->role) }}
                        @endswitch
                    </p>
                </div>
                <div class="dropdown">
                    <button class="user-avatar dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i> Perfil</a></li>
                        <li><a class="dropdown-item" href="{{ route('settings') }}"><i class="fas fa-cog me-2"></i> Configuración</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Content Area -->
        <div class="content-area">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle Sidebar for Mobile
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        }

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>
