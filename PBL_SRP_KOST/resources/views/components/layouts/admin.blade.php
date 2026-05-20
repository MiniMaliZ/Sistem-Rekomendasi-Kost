<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' — ' : '' }}Admin Panel | SRP Kost</title>
    <meta name="description" content="Panel Admin Sistem Rekomendasi Pencarian Kost">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    @livewireStyles
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-body-bg">

    {{-- Overlay for mobile sidebar --}}
    <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

    {{-- ============ FLOATING SIDEBAR ============ --}}
    <aside id="admin-sidebar" class="admin-sidebar">

        {{-- Logo Block --}}
        <div class="sidebar-logo">
            <img src="{{ asset('images/logobrown.png') }}" alt="Logo" style="width: 50px; height: auto; object-fit: contain;">
        </div>

        {{-- Nav Links Container --}}
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Dashboard">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            </a>
            <a href="{{ route('admin.kost') }}" class="nav-item {{ request()->routeIs('admin.kost') ? 'active' : '' }}" title="Data Kost">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </a>
        </nav>

        {{-- Logout Block --}}
        <div class="sidebar-logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout" class="logout-btn">
                    <x-solar-logout-3-linear class="w-7 h-7" />
                </button>
            </form>
        </div>
    </aside>

    {{-- ============ MAIN CONTENT ============ --}}
    <div class="admin-main">
        <div class="admin-container">
            {{-- Top Bar --}}
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button class="mobile-toggle-btn" onclick="toggleSidebar()">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="greeting-title">{{ $title ?? 'Admin Dashboard' }}</h1>
                    </div>
                </div>

                <div class="topbar-right">
                    {{-- Profile --}}
                    <div class="profile-wrapper">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Admin" class="profile-img">
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="admin-body">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts

    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-open');
        }
    </script>
</body>
</html>
