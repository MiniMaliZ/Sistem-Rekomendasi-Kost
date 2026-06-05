{{-- resources/views/user/partials/sidebar.blade.php --}}
<section id="section-sidebar">
    <nav class="nav-pill">
        <ul class="nav-list">
            <li>
                <a href="{{ route('user.dashboard') }}"
                    class="nav-item {{ request()->routeIs('user.dashboard') ? 'nav-item--active' : '' }}"
                    aria-label="Dashboard">
                    <x-tabler-layout-dashboard-filled
                        class="nav-blade-icon {{ request()->routeIs('user.dashboard') ? 'nav-blade-icon--active' : '' }}" />
                </a>
            </li>
            <li>
                <a href="{{ route('user.kost') }}"
                    class="nav-item {{ request()->routeIs('user.kost*') ? 'nav-item--active' : '' }}"
                    aria-label="Daftar Kost">
                    <x-iconsax-lin-buliding
                        class="nav-blade-icon {{ request()->routeIs('user.kost*') ? 'nav-blade-icon--active' : '' }}" />
                </a>
            </li>
            <li>
                @auth
                    <a href="{{ route('user.favorit') }}"
                        class="nav-item {{ request()->routeIs('user.favorit') ? 'nav-item--active' : '' }}"
                        aria-label="Favorit">
                        <x-solar-heart-linear
                            class="nav-blade-icon {{ request()->routeIs('user.favorit') ? 'nav-blade-icon--active' : '' }}" />
                    </a>
                @else
                    {{-- Guest: arahkan ke halaman login saat klik favorit --}}
                    <a href="{{ route('login') }}" class="nav-item" aria-label="Favorit — Login untuk mengakses">
                        <x-solar-heart-linear class="nav-blade-icon" />
                    </a>
                @endauth
            </li>
            <li>
                @auth
                    <a href="{{ route('user.riwayat') }}"
                        class="nav-item {{ request()->routeIs('user.riwayat') ? 'nav-item--active' : '' }}"
                        aria-label="Riwayat">
                        <x-clarity-history-line
                            class="nav-blade-icon {{ request()->routeIs('user.riwayat') ? 'nav-blade-icon--active' : '' }}" />
                    </a>
                @else
                    {{-- Guest: arahkan ke halaman login saat klik riwayat --}}
                    <a href="{{ route('login') }}" class="nav-item" aria-label="Riwayat — Login untuk mengakses">
                        <x-clarity-history-line class="nav-blade-icon" />
                    </a>
                @endauth
            </li>
        </ul>
    </nav>

    <a href="{{ route('landing') }}" class="logout-button" aria-label="Kembali ke Beranda">
        <x-iconsax-lin-logout class="nav-blade-icon" />
    </a>
</section>
