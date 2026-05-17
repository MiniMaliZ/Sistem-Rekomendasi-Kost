<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' — ' : '' }}Admin Panel | SRP Kost</title>
    <meta name="description" content="Panel Admin Sistem Rekomendasi Pencarian Kost">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* Ensure SVGs don't inherit large sizes */
        svg {
            display: inline-block;
            vertical-align: middle;
            overflow: hidden;
        }
        /* Livewire loading bar */
        [wire\:loading] { display: none !important; }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#DBD3C6; font-family:'Poppins',sans-serif; color:#5D4D34;">

    {{-- Mobile Overlay --}}
    <div id="sidebar-overlay" onclick="toggleSidebar()"
         style="display:none; position:fixed; inset:0; background-color:rgba(0,0,0,0.5); z-index:40;"></div>

    {{-- ============ SIDEBAR ============ --}}
    <aside id="admin-sidebar" class="admin-sidebar">

        {{-- Logo --}}
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid rgba(255,255,255,0.1);">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <div style="width:36px; height:36px; border-radius:8px; background-color:#AD9C8A; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 22V12h6v10"/>
                    </svg>
                </div>
                <div>
                    <p style="color:white; font-weight:700; font-size:0.875rem; line-height:1.2; margin:0;">SRP Kost</p>
                    <p style="color:#AD9C8A; font-size:0.7rem; line-height:1.2; margin:0;">Admin Panel</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav style="flex:1; padding:1rem 0;">

            <p style="color:#AD9C8A; font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; padding:0 1rem; margin:0 0 0.5rem;">Menu Utama</p>

            <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <p style="color:#AD9C8A; font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; padding:0 1rem; margin:1.25rem 0 0.5rem;">Manajemen Data</p>

            <a href="{{ route('admin.kost') }}" class="sidebar-nav-item {{ request()->routeIs('admin.kost') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819"/>
                </svg>
                <span>Data Kost</span>
            </a>

            <a href="{{ route('admin.kriteria') }}" class="sidebar-nav-item {{ request()->routeIs('admin.kriteria') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                </svg>
                <span>Kriteria SPK</span>
            </a>

            <a href="{{ route('admin.users') }}" class="sidebar-nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
                <span>Pengguna</span>
            </a>

            <p style="color:#AD9C8A; font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; padding:0 1rem; margin:1.25rem 0 0.5rem;">Laporan & Analisis</p>

            <a href="{{ route('admin.rekomendasi') }}" class="sidebar-nav-item {{ request()->routeIs('admin.rekomendasi') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Hasil Rekomendasi</span>
            </a>

            <a href="{{ route('admin.feedback') }}" class="sidebar-nav-item {{ request()->routeIs('admin.feedback') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                </svg>
                <span>Feedback & Ulasan</span>
            </a>

            <a href="{{ route('admin.riwayat') }}" class="sidebar-nav-item {{ request()->routeIs('admin.riwayat') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Riwayat Aktivitas</span>
            </a>
        </nav>

        {{-- Bottom User --}}
        <div style="padding:1rem 1rem; border-top:1px solid rgba(255,255,255,0.1);">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <div style="width:36px; height:36px; border-radius:50%; background-color:#AD9C8A; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:0.875rem; flex-shrink:0;">A</div>
                <div style="flex:1; min-width:0;">
                    <p style="color:white; font-size:0.8rem; font-weight:600; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Administrator</p>
                    <p style="color:#AD9C8A; font-size:0.7rem; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">admin@srpkost.id</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ============ MAIN CONTENT ============ --}}
    <div class="admin-main">

        {{-- Top Bar --}}
        <header class="admin-topbar">
            {{-- Mobile menu btn --}}
            <button onclick="toggleSidebar()" id="sidebar-toggle-btn"
                    style="display:none; padding:0.5rem; border-radius:0.5rem; border:none; background:transparent; cursor:pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#5D4D34" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Breadcrumb --}}
            <div style="flex:1;">
                @isset($breadcrumb)
                    <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.875rem; color:#5D4D34; flex-wrap:wrap;">
                        <a href="{{ route('admin.dashboard') }}" style="font-weight:600; text-decoration:none; color:#5D4D34; opacity:0.7;">Admin</a>
                        @foreach($breadcrumb as $label => $url)
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#5D4D34" stroke-width="2" style="opacity:0.4;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                            @if($loop->last)
                                <span style="font-weight:700;">{{ $label }}</span>
                            @else
                                <a href="{{ $url }}" style="text-decoration:none; color:#5D4D34;">{{ $label }}</a>
                            @endif
                        @endforeach
                    </div>
                @endisset
            </div>

            {{-- Date --}}
            <div style="text-align:right;">
                <p style="font-size:0.75rem; font-weight:500; color:#7C929E; margin:0;">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
        </header>

        {{-- Page Content --}}
        <main style="padding:1.5rem;" class="animate-fade-in">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts

    <script>
        // Show mobile toggle on small screens
        function updateToggleBtn() {
            const btn = document.getElementById('sidebar-toggle-btn');
            if (btn) btn.style.display = window.innerWidth < 768 ? 'flex' : 'none';
        }
        updateToggleBtn();
        window.addEventListener('resize', updateToggleBtn);

        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const isOpen = sidebar.classList.contains('open');
            if (isOpen) {
                sidebar.classList.remove('open');
                overlay.style.display = 'none';
            } else {
                sidebar.classList.add('open');
                overlay.style.display = 'block';
            }
        }
    </script>
</body>
</html>
