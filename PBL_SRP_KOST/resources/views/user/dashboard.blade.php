<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KostApp - Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" />
    <link rel="stylesheet" href="{{ asset('css/user/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/sidebar.css') }}">
</head>

<body>

    <!-- ============================================================
         HEADER
         ============================================================ -->
    <section id="section-header">

        <div class="header-left">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="logo-icon">
            <div class="greeting">
                @auth
                    <h1 class="greeting-title">Halo {{ $user['nama_depan'] }}!</h1>
                @else
                    <h1 class="greeting-title">Halo, Selamat Datang!</h1>
                @endauth
                <p class="greeting-subtitle">Yuk cari kost yang sesuai dengan dirimu sekarang.</p>
            </div>
        </div>

        <div class="header-right">
            {{-- Search bar --}}
            <form action="{{ route('user.search') }}" method="GET" class="search-bar" role="search">
                <input type="text" name="q" placeholder="Cari nama kost atau lokasi..." class="search-input"
                    value="{{ request('q') }}" autocomplete="off">
                <button type="submit" class="search-button" aria-label="Cari">
                    <img src="{{ asset('images/search.svg') }}" alt="Search Icon" class="search-icon">
                </button>
            </form>

            {{-- User Avatar (sudah login) atau tombol Login + Daftar (guest) --}}
            @auth
                <a href="{{ route('profile') }}" class="user-avatar" aria-label="Lihat profil saya">
                    <img src="{{ $user['avatar'] ? asset('images/' . $user['avatar']) : asset('images/profile_user.png') }}"
                        alt="Foto Profil {{ $user['nama'] }}">
                </a>
            @else
                <a href="{{ route('login') }}" class="signin-btn">
                    <span>Masuk</span>
                </a>
            @endauth
        </div>

    </section>

    <!-- Page Body -->
    <div class="page-body">

        <!-- ============================================================
             SIDEBAR
             ============================================================ -->
        @include('user.partials.sidebar')

        <!-- ============================================================
             MAIN CONTENT
             ============================================================ -->
        <main class="main-content">
            <div class="home-grid">

                <!-- ── LEFT COLUMN ──────────────────────────────────── -->
                <div class="home-col-left">

                    <!-- Hero -->
                    <section id="section-hero">
                        <div class="hero-wrapper">
                            <img src="{{ asset('images/hero_bg.png') }}" alt="Hero Background" class="hero-bg-img">
                            <div class="hero-overlay"></div>
                            <div class="hero-content">
                                <h2 class="hero-title">Temukan Kost Terbaik Untukmu.</h2>
                                <p class="hero-subtitle">Ribuan pilihan kost dengan harga transparan dan informasi yang
                                    lengkap.</p>
                                <a href="{{ route('user.kost') }}" class="hero-btn">
                                    Cari Kost
                                    <img src="{{ asset('images/longarrow_right.svg') }}" alt="Arrow Right">
                                </a>
                            </div>
                        </div>
                    </section>

                    <!-- Rekomendasi Kost — 5 kartu dengan slider -->
                    <section id="section-recommendations" class="section-recommendations">
                        <div class="section-container">
                            <div class="section-header">
                                <h3 class="section-title">Rekomendasi Kost</h3>
                                <a href="{{ route('user.kost') }}" class="section-link">
                                    Lihat Semua
                                    <img src="{{ asset('images/red_arrow.svg') }}" alt="Arrow Right">
                                </a>
                            </div>

                            <div class="cards-slider-wrapper">

                                {{-- Tombol Sebelumnya --}}
                                <button class="slider-btn slider-btn--prev hidden" id="slider-prev"
                                    aria-label="Kartu sebelumnya">
                                    <svg viewBox="0 0 24 24">
                                        <polyline points="15 18 9 12 15 6" />
                                    </svg>
                                </button>

                                {{-- Track berisi semua kartu --}}
                                <div class="cards-slider-track" id="cards-slider-track">
                                    @forelse($rekomendasi as $kost)
                                        @include('user.partials.kost-card', ['kost' => $kost])
                                    @empty
                                        <p style="text-align:center; color:#696969; padding:2rem; width:100%;">
                                            Belum ada rekomendasi kost.
                                        </p>
                                    @endforelse
                                </div>

                                {{-- Tombol Berikutnya --}}
                                <button class="slider-btn slider-btn--next" id="slider-next"
                                    aria-label="Kartu berikutnya">
                                    <svg viewBox="0 0 24 24">
                                        <polyline points="9 18 15 12 9 6" />
                                    </svg>
                                </button>

                            </div>
                        </div>
                    </section>

                    <!-- Kost Terbaru -->
                    <section id="section-latest" class="section-latest">
                        <div class="section-container">
                            <div class="section-header">
                                <h3 class="section-title">Kost Terbaru</h3>
                                <a href="{{ route('user.kost') }}" class="section-link">
                                    Lihat Semua
                                    <img src="{{ asset('images/red_arrow.svg') }}" alt="Arrow Right">
                                </a>
                            </div>
                            <div class="small-cards-grid">
                                @forelse($terbaru as $kost)
                                    @include('user.partials.kost-card-small', ['kost' => $kost])
                                @empty
                                    <p style="grid-column:span 3; text-align:center; color:#696969; padding:2rem;">
                                        Belum ada kost terbaru.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </section>

                </div><!-- end .home-col-left -->

                <!-- ── RIGHT COLUMN ─────────────────────────────────── -->
                <div class="home-col-right">

                    <!-- Map -->
                    <section id="section-map">
                        <div class="map-wrapper">
                            <div class="map-header">
                                <h2>Lokasi Sekitarmu</h2>
                                <a href="#" class="map-link">
                                    <span>Lihat Maps</span>
                                    <img src="{{ asset('images/red_arrow.svg') }}" alt="Arrow">
                                </a>
                            </div>
                            <img src="{{ asset('images/maps.png') }}" alt="Peta area sekitar" class="map-img">
                        </div>
                    </section>

                    <!-- Favorit Section -->
                    <section id="section-empty-state">

                        @auth
                            {{-- Empty state: belum ada favorit --}}
                            <div id="favorit-empty" class="empty-state-card"
                                style="{{ !empty($favoritKosts) ? 'display:none;' : '' }}">
                                <div class="empty-state-content">
                                    <div class="illustration-wrap">
                                        <img src="{{ asset('images/illus.svg') }}" alt="Ilustrasi kosong"
                                            class="illus-bg">
                                    </div>
                                    <div class="empty-state-text">
                                        <h3>Belum ada kost favorit</h3>
                                        <p>Simpan kost yang kamu suka untuk<br>akses lebih cepat nanti.</p>
                                    </div>
                                </div>
                                <a href="{{ route('user.kost') }}" class="explore-btn">
                                    <span>Jelajahi Kost</span>
                                </a>
                            </div>

                            {{-- Widget favorit --}}
                            <div id="favorit-list-wrapper" class="favorit-widget"
                                style="{{ empty($favoritKosts) ? 'display:none;' : '' }}">

                                {{-- Header widget --}}
                                <div class="favorit-widget-header">
                                    <h3 class="favorit-widget-title">Kost Disimpan</h3>
                                    <a href="{{ route('user.favorit') }}" class="favorit-widget-link">
                                        <span>Lihat Semua</span>
                                        <img src="{{ asset('images/red_arrow.svg') }}" alt="Arrow Right">
                                    </a>
                                </div>

                                {{-- List kartu favorit --}}
                                <div class="favorit-property-list" id="favorit-list">
                                    @foreach (array_slice(array_values($favoritKosts), 0, 3) as $fav)
                                        <div class="fav-sidebar-item fav-item-enter" data-kost-id="{{ $fav['id'] }}">
                                            <div class="fav-sidebar-img-wrap">
                                                <img src="{{ asset('images/' . $fav['foto']) }}"
                                                    alt="{{ $fav['nama'] }}" class="fav-sidebar-img">
                                            </div>
                                            <div class="fav-sidebar-body">
                                                <div class="fav-sidebar-info">
                                                    <h4 class="fav-sidebar-name">{{ $fav['nama'] }}</h4>
                                                    <div class="fav-sidebar-location">
                                                        <img src="{{ asset('images/loc.svg') }}" alt="Lokasi">
                                                        <span>{{ $fav['lokasi'] }}</span>
                                                    </div>
                                                    <p class="fav-sidebar-price">
                                                        {{ $fav['harga_format'] }} <span>/bulan</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        @else
                            {{-- Guest: tampilkan ajakan login untuk akses favorit --}}
                            <div class="empty-state-card">
                                <div class="empty-state-content">
                                    <div class="illustration-wrap">
                                        <img src="{{ asset('images/illus.svg') }}" alt="Ilustrasi kosong"
                                            class="illus-bg">
                                    </div>
                                    <div class="empty-state-text">
                                        <h3>Simpan kost favoritmu</h3>
                                        <p>Masuk atau daftar untuk menyimpan<br>kost yang kamu suka.</p>
                                    </div>
                                </div>
                                <a href="{{ route('login') }}" class="explore-btn">
                                    <span>Masuk Sekarang</span>
                                </a>
                            </div>
                        @endauth

                    </section>

                </div><!-- end .home-col-right -->

            </div><!-- end .home-grid -->
        </main>

    </div><!-- end .page-body -->

    <!-- ============================================================
    MODAL SIGN IN — Tampil saat guest klik icon hati
    ============================================================ -->
    <div id="modal-signin-overlay" class="modal-signin-overlay" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="modal-signin-title">
        <div class="modal-signin-card">

            {{-- Icon hati besar --}}
            <div class="modal-signin-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12 21C12 21 3 14.5 3 8.5C3 5.42 5.42 3 8.5 3C10.24 3 11.91 3.81 13 5.08C14.09 3.81 15.76 3 17.5 3C20.58 3 23 5.42 23 8.5C23 14.5 12 21 12 21Z"
                        stroke="#ac0000" stroke-width="1.8" stroke-linejoin="round" />
                </svg>
            </div>

            {{-- Teks --}}
            <div class="modal-signin-text">
                <h3 id="modal-signin-title">Simpan Kost Favoritmu</h3>
                <p>Kamu perlu sign in terlebih dahulu untuk menyimpan kost ke daftar favorit.</p>
            </div>

            {{-- Tombol aksi --}}
            <div class="modal-signin-actions">
                <a href="{{ route('login') }}" class="modal-btn modal-btn--primary">
                    Sign In Sekarang
                </a>
                <button type="button" class="modal-btn modal-btn--secondary" id="modal-signin-close">
                    Nanti Saja
                </button>
            </div>

        </div>
    </div>

    <!-- ============================================================
         DATA BRIDGE: semua data kost dari server → JS
         ============================================================ -->
    <script>
        const SEMUA_KOST = {!! $semuaKostJson !!};
        const ASSET_BASE = '{{ asset('') }}';
        const IS_AUTH = {{ auth()->check() ? 'true' : 'false' }};
    </script>

    <!-- ============================================================
         JAVASCRIPT
         ============================================================ -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ── [A] SLIDER REKOMENDASI ─────────────────────────────────

            const track = document.getElementById('cards-slider-track');
            const btnPrev = document.getElementById('slider-prev');
            const btnNext = document.getElementById('slider-next');
            const getStep = () => track ? track.clientWidth + 16 : 0;

            function refreshSliderButtons() {
                if (!track) return;
                btnPrev.classList.toggle('hidden', track.scrollLeft <= 0);
                const atEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 1;
                btnNext.classList.toggle('hidden', atEnd);
            }

            if (track) {
                btnPrev.addEventListener('click', () => {
                    track.scrollBy({
                        left: -getStep(),
                        behavior: 'smooth'
                    });
                });
                btnNext.addEventListener('click', () => {
                    track.scrollBy({
                        left: getStep(),
                        behavior: 'smooth'
                    });
                });
                track.addEventListener('scroll', refreshSliderButtons, {
                    passive: true
                });
                refreshSliderButtons();
            }


            // ── [B] TOGGLE FAVORIT + UPDATE SIDEBAR DINAMIS ───────────

            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            const favoritEmpty = document.getElementById('favorit-empty');
            const favoritWrapper = document.getElementById('favorit-list-wrapper');
            const favoritList = document.getElementById('favorit-list');

            // referensi elemen modal sign in
            const modalOverlay = document.getElementById('modal-signin-overlay');
            const modalCloseBtn = document.getElementById('modal-signin-close');

            // buka modal sign in
            function openSignInModal() {
                modalOverlay.classList.add('modal-signin-overlay--visible');
                modalOverlay.setAttribute('aria-hidden', 'false');
            }

            // tutup modal sign in
            function closeSignInModal() {
                modalOverlay.classList.remove('modal-signin-overlay--visible');
                modalOverlay.setAttribute('aria-hidden', 'true');
            }

            // tutup modal saat klik tombol "Nanti Saja"
            modalCloseBtn.addEventListener('click', closeSignInModal);

            // tutup modal saat klik area luar kartu
            modalOverlay.addEventListener('click', function(e) {
                if (e.target === modalOverlay) closeSignInModal();
            });

            // tutup modal dengan tombol Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeSignInModal();
            });

            let favoritIds = @json($favoritIds ?? []);

            function renderSidebarFavorit() {
                if (!favoritList) return; // guest tidak punya elemen ini
                const favItems = favoritIds
                    .map(id => SEMUA_KOST.find(k => k.id === id))
                    .filter(Boolean)
                    .slice(0, 3);

                if (favItems.length === 0) {
                    if (favoritEmpty) favoritEmpty.style.display = '';
                    if (favoritWrapper) favoritWrapper.style.display = 'none';
                    return;
                }

                if (favoritEmpty) favoritEmpty.style.display = 'none';
                if (favoritWrapper) favoritWrapper.style.display = '';

                favoritList.innerHTML = '';
                favItems.forEach(kost => {
                    const div = document.createElement('div');
                    div.className = 'fav-sidebar-item fav-item-enter';
                    div.dataset.kostId = kost.id;
                    div.innerHTML = `
                        <div class="fav-sidebar-img-wrap">
                            <img
                                src="${ASSET_BASE}images/${kost.foto}"
                                alt="${kost.nama}"
                                class="fav-sidebar-img"
                            >
                        </div>
                        <div class="fav-sidebar-body">
                            <div class="fav-sidebar-info">
                                <h4 class="fav-sidebar-name">${kost.nama}</h4>
                                <div class="fav-sidebar-location">
                                    <img src="${ASSET_BASE}images/loc.svg" alt="Lokasi">
                                    <span>${kost.lokasi}</span>
                                </div>
                                <p class="fav-sidebar-price">
                                    ${kost.harga_format} <span>/bulan</span>
                                </p>
                            </div>
                        </div>
                    `;
                    favoritList.appendChild(div);
                });
            }

            document.body.addEventListener('click', function(e) {
                const btn = e.target.closest('.fav-btn');
                if (!btn) return;

                e.preventDefault();
                e.stopPropagation();

                // Jika guest, tampilkan modal login
                if (!IS_AUTH) {
                    openSignInModal();
                    return;
                }

                const kostId = parseInt(btn.dataset.kostId);
                const imgEl = btn.querySelector('img');
                const isFav = btn.dataset.isFavorit === 'true';
                const nextState = !isFav;

                // Optimistic UI: ubah ikon hati
                btn.dataset.isFavorit = String(nextState);
                imgEl.src = nextState ?
                    '{{ asset('images/heart_filled.svg') }}' :
                    '{{ asset('images/heart_outline.svg') }}';

                // Optimistic UI: perbarui state lokal & sidebar
                if (nextState) {
                    if (!favoritIds.includes(kostId)) favoritIds.push(kostId);
                } else {
                    favoritIds = favoritIds.filter(id => id !== kostId);
                }
                renderSidebarFavorit();

                // Kirim ke server
                fetch('{{ route('favorit.toggle') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            kost_id: kostId
                        }),
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.success) {
                            // Rollback jika server gagal
                            btn.dataset.isFavorit = String(isFav);
                            imgEl.src = isFav ?
                                '{{ asset('images/heart_filled.svg') }}' :
                                '{{ asset('images/heart_outline.svg') }}';
                            favoritIds = data.favorit_ids ?? favoritIds;
                            renderSidebarFavorit();
                        } else {
                            if (data.favorit_ids !== undefined) {
                                favoritIds = data.favorit_ids;
                                renderSidebarFavorit();
                            }
                        }
                    })
                    .catch(() => {
                        // Rollback jika network error
                        btn.dataset.isFavorit = String(isFav);
                        imgEl.src = isFav ?
                            '{{ asset('images/heart_filled.svg') }}' :
                            '{{ asset('images/heart_outline.svg') }}';
                        if (nextState) {
                            favoritIds = favoritIds.filter(id => id !== kostId);
                        } else {
                            if (!favoritIds.includes(kostId)) favoritIds.push(kostId);
                        }
                        renderSidebarFavorit();
                    });
            });

        });
    </script>

</body>

</html>
