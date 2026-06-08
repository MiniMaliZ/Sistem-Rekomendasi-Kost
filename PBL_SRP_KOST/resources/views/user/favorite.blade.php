<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KostApp - Favorit</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" />
    <link rel="stylesheet" href="{{ asset('css/user/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/favorite.css') }}">
</head>

<body>

    <!-- ============================================================
         HEADER
         ============================================================ -->
    <section id="section-header">

        <!-- Left Area: Logo and Greeting Text -->
        <div class="header-left">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="logo-icon">
            <div class="greeting">
                <h1 class="greeting-title">Favorit</h1>
                <p class="greeting-subtitle">Kost yang kamu simpan akan muncul di sini.</p>
            </div>
        </div>

        <!-- Right Area: Search + Avatar / Login -->
        <div class="header-right">

            {{-- Search bar hanya ditampilkan untuk user yang sudah login --}}
            @auth
                <div class="search-bar">
                    <input type="text" id="fav-search-input" placeholder="Cari nama kost, lokasi..."
                        class="search-input">
                    <button class="search-button" id="fav-search-btn">
                        <img src="{{ asset('images/search.svg') }}" alt="Search Icon" class="search-icon">
                    </button>
                </div>

                <a href="{{ route('profile') }}" class="user-avatar" aria-label="Profil saya">
                    <img src="{{ asset('images/profile_user.png') }}" alt="User Profile">
                </a>
            @else
                <a href="{{ route('login') }}" class="signin-btn">
                    <span>Masuk</span>
                </a>
            @endauth
        </div>
    </section>

    <!-- ============================================================
         PAGE BODY: Sidebar + Main
         ============================================================ -->
    <div class="page-body">

        @include('user.partials.sidebar')

        <main class="main-content">

            {{-- ══════════════════════════════════════════════════════
                 FILTER BAR — hanya tampil untuk user yang sudah login
                 Guest tidak memerlukan filter karena tidak ada data
            ══════════════════════════════════════════════════════ --}}
            @auth
                <section id="section-filter-bar">
                    <div class="filter-container">

                        {{-- Kiri: Filter Dropdown --}}
                        <div class="filter-left">

                            {{-- Filter: Jenis Kos --}}
                            <div class="filter-dropdown-wrapper" id="filter-jenis-wrapper">
                                <button class="filter-dropdown-btn" id="filter-jenis-btn" aria-label="Filter jenis kos">
                                    <span class="filter-dropdown-label" id="filter-jenis-label">Jenis Kos</span>
                                    <div class="filter-dropdown-icon-wrapper">
                                        <img src="{{ asset('images/arrow-down.svg') }}" alt="Arrow"
                                            class="filter-dropdown-icon" id="filter-jenis-arrow" />
                                    </div>
                                </button>
                                <div class="filter-dropdown-menu hidden" id="filter-jenis-menu">
                                    <button class="filter-option filter-option--active" data-filter="jenis"
                                        data-value="">Semua Jenis</button>
                                    <button class="filter-option" data-filter="jenis" data-value="Kos Putri">Kos
                                        Putri</button>
                                    <button class="filter-option" data-filter="jenis" data-value="Kos Putra">Kos
                                        Putra</button>
                                    <button class="filter-option" data-filter="jenis" data-value="Kos Campur">Kos
                                        Campur</button>
                                </div>
                            </div>

                            {{-- Filter: Kecamatan --}}
                            <div class="filter-dropdown-wrapper" id="filter-kecamatan-wrapper">
                                <button class="filter-dropdown-btn" id="filter-kecamatan-btn" aria-label="Filter kecamatan">
                                    <span class="filter-dropdown-label" id="filter-kecamatan-label">Kecamatan</span>
                                    <div class="filter-dropdown-icon-wrapper">
                                        <img src="{{ asset('images/arrow-down.svg') }}" alt="Arrow"
                                            class="filter-dropdown-icon" id="filter-kecamatan-arrow" />
                                    </div>
                                </button>
                                <div class="filter-dropdown-menu hidden" id="filter-kecamatan-menu">
                                    <button class="filter-option filter-option--active" data-filter="kecamatan"
                                        data-value="">Semua Kecamatan</button>
                                    @php
                                        // Kecamatan Kota Malang
                                        $kecKota = ['Lowokwaru', 'Blimbing', 'Klojen', 'Sukun', 'Kedungkandang'];
                                        // Kecamatan Kabupaten Malang
                                        $kecKabupaten = ['Karang Ploso', 'Dau'];
                                    @endphp
                                    <div class="filter-option-group-label">Kota Malang</div>
                                    @foreach ($kecKota as $kec)
                                        <button class="filter-option" data-filter="kecamatan"
                                            data-value="{{ $kec }}">{{ $kec }}</button>
                                    @endforeach
                                    <div class="filter-option-group-label">Kabupaten Malang</div>
                                    @foreach ($kecKabupaten as $kec)
                                        <button class="filter-option" data-filter="kecamatan"
                                            data-value="{{ $kec }}">{{ $kec }}</button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Active Filter Chips --}}
                            <div class="active-filter-chips" id="active-filter-chips"></div>

                            {{-- Reset All --}}
                            <button class="btn-reset-all hidden" id="btn-reset-all" aria-label="Reset semua filter">
                                <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 10a6 6 0 1 0 1.06-3.4" stroke="currentColor" stroke-width="1.6"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M4 6v4h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                Reset
                            </button>
                        </div>

                        {{-- Kanan: Sort Dropdown --}}
                        <div class="filter-actions" id="sort-actions-wrapper">
                            <span class="sort-label">Urutkan:</span>
                            <button class="sort-dropdown" id="sort-toggle" aria-label="Sort options">
                                <span class="dropdown-text" id="sort-label-text">Terbaru</span>
                                <div class="dropdown-icon-wrapper">
                                    <img src="{{ asset('images/arrow-down.svg') }}" alt="Dropdown Arrow"
                                        class="dropdown-icon" />
                                </div>
                            </button>
                            <div class="sort-menu hidden" id="sort-menu">
                                <button class="sort-option sort-option--active" data-sort="terbaru">Terbaru</button>
                                <button class="sort-option" data-sort="termurah">Harga Terendah</button>
                                <button class="sort-option" data-sort="termahal">Harga Tertinggi</button>
                                <button class="sort-option" data-sort="nama">Nama A–Z</button>
                            </div>
                        </div>

                    </div>
                </section>
            @endauth

            {{-- ══════════════════════════════════════════════════════
                 STATE: GUEST — halaman kosong dengan CTA login
                 Satu CTA utama (tombol solid) + satu secondary action (tombol outline)
            ══════════════════════════════════════════════════════ --}}
            @if ($isGuest)
                <section id="section-empty-guest" class="section-empty-state section-empty-state--guest">
                    <div class="empty-state-wrapper">
                        <div class="empty-state-illustration">
                            <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="empty-heart-svg">
                                <circle cx="60" cy="60" r="58" fill="#fff4f4" stroke="#f5d0d0"
                                    stroke-width="2" />
                                <path
                                    d="M60 82C60 82 36 67 36 50.5C36 43.6 41.6 38 48.5 38C52.6 38 56.3 40 58.9 43.1L60 44.4L61.1 43.1C63.7 40 67.4 38 71.5 38C78.4 38 84 43.6 84 50.5C84 67 60 82 60 82Z"
                                    stroke="#e57373" stroke-width="2.5" stroke-linejoin="round" fill="#ffcdd2" />
                                <path d="M44 56h8M44 61h6" stroke="#e57373" stroke-width="2" stroke-linecap="round"
                                    opacity="0.5" />
                            </svg>
                        </div>
                        <h3 class="empty-state-title">Masuk untuk Melihat Favorit</h3>
                        <p class="empty-state-desc">
                            Simpan kost yang kamu suka agar mudah ditemukan lagi.<br>
                            Fitur ini tersedia setelah kamu masuk ke akun.
                        </p>
                        {{-- CTA Utama: Masuk --}}
                        <a href="{{ route('login') }}" class="empty-cta-btn">
                            Masuk Sekarang
                        </a>
                        {{-- Secondary Action: Jelajahi Kost — tombol outline, bukan link teks --}}
                        <a href="{{ route('user.kost') }}" class="empty-browse-btn">
                            Jelajahi Kost Dulu
                        </a>
                    </div>
                </section>

                {{-- ══════════════════════════════════════════════════════
                 STATE: AUTH — Kosong (belum ada favorit)
            ══════════════════════════════════════════════════════ --}}
            @elseif ($totalFavorit === 0)
                <section id="section-empty-auth" class="section-empty-state">
                    <div class="empty-state-wrapper">
                        <div class="empty-state-illustration">
                            <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="empty-heart-svg">
                                <circle cx="60" cy="60" r="58" fill="#fefcf8" stroke="#e5e5e5"
                                    stroke-width="2" />
                                <path
                                    d="M60 82C60 82 36 67 36 50.5C36 43.6 41.6 38 48.5 38C52.6 38 56.3 40 58.9 43.1L60 44.4L61.1 43.1C63.7 40 67.4 38 71.5 38C78.4 38 84 43.6 84 50.5C84 67 60 82 60 82Z"
                                    stroke="#c0b0a8" stroke-width="2.5" stroke-linejoin="round" fill="none" />
                            </svg>
                        </div>
                        <h3 class="empty-state-title">Belum Ada Kost Favorit</h3>
                        <p class="empty-state-desc">
                            Simpan kost yang kamu suka untuk akses lebih cepat nanti.<br>
                            Klik ikon hati pada kost yang kamu minati.
                        </p>
                        <a href="{{ route('user.kost') }}" class="empty-cta-btn">
                            Jelajahi Kost
                        </a>
                    </div>
                </section>

                {{-- ══════════════════════════════════════════════════════
                 STATE: AUTH — Ada data favorit
            ══════════════════════════════════════════════════════ --}}
            @else
                <!-- Listings Grid -->
                <section id="section-listings">
                    <div class="container">
                        <div class="listings-grid" id="listings-grid">
                            @foreach ($favoritKosts as $kost)
                                <div class="card" data-id="{{ $kost['id'] }}"
                                    data-nama="{{ strtolower($kost['nama']) }}" data-harga="{{ $kost['harga'] }}"
                                    data-tipe="{{ $kost['tipe_kos'] }}" data-kecamatan="{{ $kost['kecamatan'] }}"
                                    data-added="{{ $loop->index }}">

                                    <div class="card-image-wrapper">
                                        {{-- foto_bangunan sudah berisi path lengkap, gunakan asset() langsung --}}
                                        <img src="{{ asset($kost['foto']) }}" alt="{{ $kost['nama'] }}"
                                            class="card-image"
                                            onerror="this.src='{{ asset('images/kost1.png') }}'" />

                                        <div class="badge {{ $kost['tipe_badge'] }}">
                                            {{ $kost['tipe_label'] }}
                                        </div>

                                        {{-- Tombol hapus dari favorit --}}
                                        <button class="btn-favorite fav-btn" data-kost-id="{{ $kost['id'] }}"
                                            data-is-favorit="true" aria-label="Hapus dari favorit">
                                            <img src="{{ asset('images/fav-bold.svg') }}" alt="Favorit"
                                                id="fav-img-{{ $kost['id'] }}" />
                                        </button>
                                    </div>

                                    <div class="card-content">
                                        <div class="card-header">
                                            <h3 class="card-title">{{ $kost['nama'] }}</h3>
                                            <div class="card-rating">
                                                <img src="{{ asset('images/star.svg') }}" alt="Star" />
                                                <span>{{ $kost['rating'] }}</span>
                                            </div>
                                        </div>
                                        <div class="card-location">
                                            <img src="{{ asset('images/loc.svg') }}" alt="Location" />
                                            <span>{{ $kost['lokasi'] }}</span>
                                        </div>
                                        <div class="card-price">
                                            <strong>{{ $kost['harga_format'] }}</strong>
                                            <span class="price-period">/bulan</span>
                                        </div>
                                        <div class="card-amenities">
                                            @foreach ($kost['fasilitas_tags'] as $tag)
                                                <span class="amenity-tag">{{ $tag }}</span>
                                            @endforeach
                                            @if ($kost['extra_count'] > 0)
                                                <span class="amenity-tag">{{ $kost['extra_count'] }}+</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Empty state saat hasil filter kosong -->
                        <div id="no-results" class="no-results-msg hidden">
                            <p>Tidak ada kost yang sesuai pencarian atau filter.</p>
                        </div>
                    </div>
                </section>

                <!-- Pagination -->
                <section id="section-pagination">
                    <nav class="pagination-container" aria-label="Navigasi Halaman">
                        <button class="nav-button prev" id="pag-prev" aria-label="Halaman sebelumnya">
                            <img src="{{ asset('images/arrow-left.svg') }}" alt="Previous" />
                        </button>
                        <ul class="page-numbers" id="page-numbers"></ul>
                        <button class="nav-button next" id="pag-next" aria-label="Halaman berikutnya">
                            <img src="{{ asset('images/arrow-left.svg') }}" alt="Next" />
                        </button>
                    </nav>
                </section>
            @endif

        </main>
    </div>

    <!-- ============================================================
         DATA BRIDGE: favorit ids untuk JS
         ============================================================ -->
    @auth
        <script>
            let FAVORIT_IDS = @json($favoritIds);
            const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
            const TOGGLE_URL = '{{ route('favorit.toggle') }}';
            const HEART_FILLED = '{{ asset('images/fav-bold.svg') }}';
            const HEART_OUTLINE = '{{ asset('images/heart_outline.svg') }}';
        </script>
    @endauth

    <!-- ============================================================
         JAVASCRIPT
         ============================================================ -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ── STATE TERPUSAT ────────────────────────────────────────────────
            let currentSort = 'terbaru';
            let currentSearchQuery = '';
            let currentJenis = '';
            let currentKecamatan = '';

            // Label tampil di chip
            const jenisLabels = {
                'Kos Putri': 'Kos Putri',
                'Kos Putra': 'Kos Putra',
                'Kos Campur': 'Kos Campur',
            };

            // ── [B] SORT DROPDOWN ─────────────────────────────────────────────
            const sortToggle = document.getElementById('sort-toggle');
            const sortMenu = document.getElementById('sort-menu');
            const sortLabel = document.getElementById('sort-label-text');

            if (sortToggle && sortMenu) {
                sortToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeAllDropdowns('sort');
                    sortMenu.classList.toggle('hidden');
                });

                sortMenu.querySelectorAll('.sort-option').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        currentSort = this.dataset.sort;
                        sortLabel.textContent = this.textContent;
                        sortMenu.querySelectorAll('.sort-option').forEach(b => b.classList.remove(
                            'sort-option--active'));
                        this.classList.add('sort-option--active');
                        sortMenu.classList.add('hidden');
                        applyAll();
                    });
                });
            }

            // ── [C] FILTER DROPDOWN — Jenis Kos ──────────────────────────────
            const jenisBtn = document.getElementById('filter-jenis-btn');
            const jenisMenu = document.getElementById('filter-jenis-menu');
            const jenisLabel = document.getElementById('filter-jenis-label');
            const jenisArrow = document.getElementById('filter-jenis-arrow');

            if (jenisBtn && jenisMenu) {
                jenisBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeAllDropdowns('jenis');
                    const isOpen = !jenisMenu.classList.contains('hidden');
                    jenisMenu.classList.toggle('hidden');
                    jenisBtn.classList.toggle('filter-dropdown-btn--open', !isOpen);
                });

                jenisMenu.querySelectorAll('.filter-option').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        currentJenis = this.dataset.value;
                        jenisMenu.querySelectorAll('.filter-option').forEach(b => b.classList
                            .remove('filter-option--active'));
                        this.classList.add('filter-option--active');

                        // Update label tombol
                        jenisLabel.textContent = currentJenis ? jenisLabels[currentJenis] ||
                            currentJenis : 'Jenis Kos';
                        jenisBtn.classList.toggle('filter-dropdown-btn--filled', currentJenis !==
                            '');

                        jenisMenu.classList.add('hidden');
                        jenisBtn.classList.remove('filter-dropdown-btn--open');
                        renderActiveChips();
                        applyAll();
                    });
                });
            }

            // ── [D] FILTER DROPDOWN — Kecamatan ──────────────────────────────
            const kecBtn = document.getElementById('filter-kecamatan-btn');
            const kecMenu = document.getElementById('filter-kecamatan-menu');
            const kecLabel = document.getElementById('filter-kecamatan-label');
            const kecArrow = document.getElementById('filter-kecamatan-arrow');

            if (kecBtn && kecMenu) {
                kecBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeAllDropdowns('kecamatan');
                    const isOpen = !kecMenu.classList.contains('hidden');
                    kecMenu.classList.toggle('hidden');
                    kecBtn.classList.toggle('filter-dropdown-btn--open', !isOpen);
                });

                kecMenu.querySelectorAll('.filter-option').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        currentKecamatan = this.dataset.value;
                        kecMenu.querySelectorAll('.filter-option').forEach(b => b.classList.remove(
                            'filter-option--active'));
                        this.classList.add('filter-option--active');

                        // Update label tombol
                        kecLabel.textContent = currentKecamatan || 'Kecamatan';
                        kecBtn.classList.toggle('filter-dropdown-btn--filled', currentKecamatan !==
                            '');

                        kecMenu.classList.add('hidden');
                        kecBtn.classList.remove('filter-dropdown-btn--open');
                        renderActiveChips();
                        applyAll();
                    });
                });
            }

            // ── [E] ACTIVE CHIPS ──────────────────────────────────────────────
            const chipsContainer = document.getElementById('active-filter-chips');
            const resetAllBtn = document.getElementById('btn-reset-all');

            function renderActiveChips() {
                if (!chipsContainer) return;
                chipsContainer.innerHTML = '';

                const filters = [{
                        key: 'jenis',
                        value: currentJenis,
                        label: jenisLabels[currentJenis] || currentJenis
                    },
                    {
                        key: 'kecamatan',
                        value: currentKecamatan,
                        label: currentKecamatan
                    },
                ];

                let count = 0;
                filters.forEach(function(f) {
                    if (!f.value) return;
                    count++;

                    const chip = document.createElement('div');
                    chip.className = 'active-chip';
                    chip.innerHTML =
                        '<span class="active-chip-label">' + f.label + '</span>' +
                        '<button class="active-chip-remove" aria-label="Hapus filter ' + f.label + '">' +
                        '<svg viewBox="0 0 16 16" fill="none"><path d="M12 4L4 12M4 4l8 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>' +
                        '</button>';

                    chip.querySelector('.active-chip-remove').addEventListener('click', function() {
                        if (f.key === 'jenis') {
                            currentJenis = '';
                            if (jenisLabel) jenisLabel.textContent = 'Jenis Kos';
                            if (jenisBtn) jenisBtn.classList.remove('filter-dropdown-btn--filled');
                            if (jenisMenu) jenisMenu.querySelectorAll('.filter-option').forEach(
                                function(b) {
                                    b.classList.toggle('filter-option--active', b.dataset
                                        .value === '');
                                });
                        }
                        if (f.key === 'kecamatan') {
                            currentKecamatan = '';
                            if (kecLabel) kecLabel.textContent = 'Kecamatan';
                            if (kecBtn) kecBtn.classList.remove('filter-dropdown-btn--filled');
                            if (kecMenu) kecMenu.querySelectorAll('.filter-option').forEach(
                                function(b) {
                                    b.classList.toggle('filter-option--active', b.dataset
                                        .value === '');
                                });
                        }
                        renderActiveChips();
                        applyAll();
                    });

                    chipsContainer.appendChild(chip);
                });

                // Tampilkan/sembunyikan tombol Reset All
                if (resetAllBtn) resetAllBtn.classList.toggle('hidden', count < 2);
            }

            // Reset semua filter
            if (resetAllBtn) {
                resetAllBtn.addEventListener('click', function() {
                    currentJenis = '';
                    currentKecamatan = '';

                    if (jenisLabel) jenisLabel.textContent = 'Jenis Kos';
                    if (kecLabel) kecLabel.textContent = 'Kecamatan';
                    if (jenisBtn) jenisBtn.classList.remove('filter-dropdown-btn--filled');
                    if (kecBtn) kecBtn.classList.remove('filter-dropdown-btn--filled');

                    if (jenisMenu) jenisMenu.querySelectorAll('.filter-option').forEach(function(b) {
                        b.classList.toggle('filter-option--active', b.dataset.value === '');
                    });
                    if (kecMenu) kecMenu.querySelectorAll('.filter-option').forEach(function(b) {
                        b.classList.toggle('filter-option--active', b.dataset.value === '');
                    });

                    renderActiveChips();
                    applyAll();
                });
            }

            // ── [F] TUTUP SEMUA DROPDOWN SAAT KLIK LUAR ──────────────────────
            function closeAllDropdowns(except) {
                if (except !== 'sort' && sortMenu) sortMenu.classList.add('hidden');
                if (except !== 'jenis' && jenisMenu) {
                    jenisMenu.classList.add('hidden');
                    if (jenisBtn) jenisBtn.classList.remove('filter-dropdown-btn--open');
                }
                if (except !== 'kecamatan' && kecMenu) {
                    kecMenu.classList.add('hidden');
                    if (kecBtn) kecBtn.classList.remove('filter-dropdown-btn--open');
                }
            }

            document.addEventListener('click', function() {
                closeAllDropdowns('');
            });

            // ── [G] SEARCH ────────────────────────────────────────────────────
            const searchInput = document.getElementById('fav-search-input');
            const searchBtn = document.getElementById('fav-search-btn');

            function doSearch() {
                currentSearchQuery = searchInput ? searchInput.value.trim().toLowerCase() : '';
                applyAll();
            }

            if (searchInput) searchInput.addEventListener('input', doSearch);
            if (searchBtn) searchBtn.addEventListener('click', doSearch);

            // ── [H] APPLY SORT + FILTER ───────────────────────────────────────
            const grid = document.getElementById('listings-grid');

            function applyAll() {
                if (!grid) return;

                const cards = Array.from(grid.querySelectorAll('.card'));

                // Filter
                let visible = cards.filter(function(card) {
                    const matchSearch = !currentSearchQuery || card.dataset.nama.includes(
                        currentSearchQuery);
                    const matchJenis = !currentJenis || card.dataset.tipe === currentJenis;
                    const matchKec = !currentKecamatan || card.dataset.kecamatan === currentKecamatan;
                    return matchSearch && matchJenis && matchKec;
                });

                // Sort
                visible.sort(function(a, b) {
                    if (currentSort === 'termurah') return parseFloat(a.dataset.harga) - parseFloat(b
                        .dataset.harga);
                    if (currentSort === 'termahal') return parseFloat(b.dataset.harga) - parseFloat(a
                        .dataset.harga);
                    if (currentSort === 'nama') return a.dataset.nama.localeCompare(b.dataset.nama);
                    return parseInt(a.dataset.added) - parseInt(b.dataset.added); // terbaru
                });

                // Reorder DOM
                visible.forEach(function(c) {
                    grid.appendChild(c);
                });

                // Show/hide
                cards.forEach(function(c) {
                    c.style.display = visible.includes(c) ? '' : 'none';
                });

                // No results
                const noResults = document.getElementById('no-results');
                if (noResults) noResults.classList.toggle('hidden', visible.length > 0);

                // Refresh pagination
                renderPagination(visible);
            }

            // ── [I] PAGINATION ────────────────────────────────────────────────
            const PER_PAGE = 6;
            let currentPage = 1;
            let visibleCards = [];

            function renderPagination(cards) {
                visibleCards = cards;
                currentPage = 1;
                showPage(1);
            }

            function showPage(page) {
                currentPage = page;
                const start = (page - 1) * PER_PAGE;
                const end = start + PER_PAGE;
                visibleCards.forEach(function(c, i) {
                    c.style.display = (i >= start && i < end) ? '' : 'none';
                });
                buildPageButtons();
            }

            function buildPageButtons() {
                const pageNumsEl = document.getElementById('page-numbers');
                if (!pageNumsEl) return;

                const total = Math.ceil(visibleCards.length / PER_PAGE);
                pageNumsEl.innerHTML = '';

                for (let i = 1; i <= total; i++) {
                    const li = document.createElement('li');
                    const btn = document.createElement('button');
                    btn.className = 'page-number' + (i === currentPage ? ' active' : '');
                    btn.textContent = i;
                    btn.setAttribute('aria-current', i === currentPage ? 'page' : '');
                    btn.addEventListener('click', function() {
                        showPage(i);
                    });
                    li.appendChild(btn);
                    pageNumsEl.appendChild(li);
                }

                const prevBtn = document.getElementById('pag-prev');
                const nextBtn = document.getElementById('pag-next');
                if (prevBtn) prevBtn.disabled = currentPage <= 1;
                if (nextBtn) nextBtn.disabled = currentPage >= total;
            }

            const pagPrev = document.getElementById('pag-prev');
            const pagNext = document.getElementById('pag-next');
            if (pagPrev) pagPrev.addEventListener('click', function() {
                if (currentPage > 1) showPage(currentPage - 1);
            });
            if (pagNext) pagNext.addEventListener('click', function() {
                const total = Math.ceil(visibleCards.length / PER_PAGE);
                if (currentPage < total) showPage(currentPage + 1);
            });

            if (grid) {
                const allCards = Array.from(grid.querySelectorAll('.card'));
                renderPagination(allCards);
            }

            // ── [J] TOGGLE FAVORIT (AJAX) ─────────────────────────────────────
            if (typeof FAVORIT_IDS === 'undefined') return;

            document.body.addEventListener('click', function(e) {
                const btn = e.target.closest('.fav-btn');
                if (!btn) return;
                e.preventDefault();
                e.stopPropagation();

                const kostId = parseInt(btn.dataset.kostId);
                const imgEl = btn.querySelector('img');
                const isFav = btn.dataset.isFavorit === 'true';
                const nextState = !isFav;
                const card = btn.closest('.card');

                // Optimistic UI
                btn.dataset.isFavorit = String(nextState);
                imgEl.src = nextState ? HEART_FILLED : HEART_OUTLINE;

                if (!nextState && card) {
                    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(function() {
                        card.remove();

                        // Hapus card duplikat berdasarkan data-id (jika ada)
                        document.querySelectorAll('.card[data-id="' + kostId + '"]').forEach(
                            function(p) {
                                p.remove();
                            });

                        // Refresh pagination
                        const remaining = Array.from(grid ? grid.querySelectorAll('.card') : []);
                        renderPagination(remaining);
                        const noResults = document.getElementById('no-results');
                        if (noResults && remaining.length === 0) noResults.classList.remove(
                            'hidden');
                    }, 300);
                }

                fetch(TOGGLE_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            kost_id: kostId
                        }),
                    })
                    .then(function(r) {
                        return r.json();
                    })
                    .then(function(data) {
                        if (!data.success) {
                            btn.dataset.isFavorit = String(isFav);
                            imgEl.src = isFav ? HEART_FILLED : HEART_OUTLINE;
                        } else {
                            FAVORIT_IDS = data.favorit_ids ?? FAVORIT_IDS;
                        }
                    })
                    .catch(function() {
                        btn.dataset.isFavorit = String(isFav);
                        imgEl.src = isFav ? HEART_FILLED : HEART_OUTLINE;
                    });
            });

        });
    </script>

</body>

</html>
