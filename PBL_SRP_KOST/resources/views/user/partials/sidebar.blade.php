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
                {{--
                    Guest maupun auth sama-sama diarahkan ke route('user.favorit').
                    Controller FavoritController@index sudah menangani state guest.
                --}}
                <a href="{{ route('user.favorit') }}"
                    class="nav-item {{ request()->routeIs('user.favorit') ? 'nav-item--active' : '' }}"
                    aria-label="Favorit">
                    <x-solar-heart-linear
                        class="nav-blade-icon {{ request()->routeIs('user.favorit') ? 'nav-blade-icon--active' : '' }}" />
                </a>
            </li>
            <li>
                {{--
                    History tetap bisa diakses guest — controller yang menangani warning banner
                    tanpa redirect ke login, sama polanya dengan favorit.
                --}}
                <a href="{{ route('user.history') }}"
                    class="nav-item {{ request()->routeIs('user.history') ? 'nav-item--active' : '' }}"
                    aria-label="History">
                    <x-clarity-history-line
                        class="nav-blade-icon {{ request()->routeIs('user.history') ? 'nav-blade-icon--active' : '' }}" />
                </a>
            </li>
        </ul>
    </nav>
    <a href="{{ route('landing') }}" class="logout-button" aria-label="Kembali ke Beranda">
        <x-iconsax-lin-logout class="nav-blade-icon" />
    </a>
</section>
