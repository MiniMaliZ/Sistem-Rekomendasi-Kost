<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KostApp - Daftar Kost</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" />
    <link rel="stylesheet" href="{{ asset('css/user/listkost.css') }}">
</head>

<body>

    <!-- ============================================================
         HEADER
         ============================================================ -->
    <section id="section-header">

        <div class="header-left">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="logo-icon">
            <div class="greeting">
                <h1 class="greeting-title">Cari Kost</h1>
                <p class="greeting-subtitle">Temukan kost sesuai lokasi, budget dan fasilitas yang kamu butuhkan.</p>
            </div>
        </div>

        <div class="header-right">
            <form action="{{ route('user.kost') }}" method="GET" class="search-bar" role="search">
                <input type="text" name="search" placeholder="Cari nama kost, lokasi..." class="search-input"
                    value="{{ request('search') }}" autocomplete="off">
                <button type="submit" class="search-button" aria-label="Cari">
                    <img src="{{ asset('images/search.svg') }}" alt="Search Icon" class="search-icon">
                </button>
            </form>

            <a href="{{ route('profile') }}" class="user-avatar" aria-label="Profil saya">
                <img src="{{ asset('images/profile_user.png') }}" alt="User Profile">
            </a>
        </div>

    </section>

    <!-- ============================================================
         PAGE BODY
         ============================================================ -->
    <div class="page-body">

        <!-- ============================================================
             SIDEBAR
             ============================================================ -->
        @include('user.partials.sidebar')

        <!-- ============================================================
             MAIN CONTENT
             ============================================================ -->
        <main class="main-content">

            <!-- ────────────────────────────────────────────────────────
                 SEARCH & FILTER SECTION
                 ──────────────────────────────────────────────────────── -->
            <section id="section-search-filter">
                <div class="search-filter-container">

                    <!-- Filter Card (Modal/Collapsible) -->
                    <div class="filter-card">
                        <div class="filter-content">
                            <form id="filter-form" action="{{ route('user.kost') }}" method="GET">

                                <!-- ── Filters Row ─────────────────────────────────────────────── -->
                                <div class="filters-row">

                                    <!-- Tipe Filter -->
                                    <div class="filter-item">
                                        <div class="filter-toggle" data-filter="tipe">
                                            <div class="filter-text">
                                                <span class="filter-label">Tipe</span>
                                                <span class="filter-value">
                                                    @if (request('tipe_kos'))
                                                        @if (is_array(request('tipe_kos')))
                                                            {{ count(request('tipe_kos')) }} dipilih
                                                        @else
                                                            {{ ucfirst(request('tipe_kos')) }}
                                                        @endif
                                                    @else
                                                        Semua Tipe
                                                    @endif
                                                </span>
                                            </div>
                                            <img src="{{ asset('images/arrow-down.svg') }}" alt="Dropdown Arrow"
                                                class="icon-dropdown" />
                                        </div>

                                        <!-- Dropdown Menu -->
                                        <div class="filter-dropdown" id="tipe-dropdown" style="display: none;">
                                            @foreach ($filterOptions['tipe_kos_options'] as $value => $label)
                                                <label class="filter-option">
                                                    <input type="checkbox" name="tipe_kos[]"
                                                        value="{{ $value }}"
                                                        @if (is_array(request('tipe_kos')) && in_array($value, request('tipe_kos'))) checked @endif>
                                                    <span>{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Harga Filter -->
                                    <div class="filter-item">
                                        <div class="filter-toggle" data-filter="harga">
                                            <div class="filter-text">
                                                <span class="filter-label">Harga</span>
                                                <span class="filter-value">
                                                    @if (request('harga_min') || request('harga_max'))
                                                        Rp. {{ number_format(request('harga_min', 0), 0, ',', '.') }} -
                                                        Rp.
                                                        {{ number_format(request('harga_max', 999999999), 0, ',', '.') }}
                                                    @else
                                                        Semua Harga
                                                    @endif
                                                </span>
                                            </div>
                                            <img src="{{ asset('images/arrow-down.svg') }}" alt="Dropdown Arrow"
                                                class="icon-dropdown" />
                                        </div>

                                        <!-- Dropdown Menu -->
                                        <div class="filter-dropdown" id="harga-dropdown" style="display: none;">
                                            @foreach ($filterOptions['harga_ranges'] as $label => $range)
                                                <label class="filter-option">
                                                    <input type="radio" name="harga_range"
                                                        value="{{ $range['min'] }}-{{ $range['max'] }}"
                                                        @if (request('harga_range') === $range['min'] . '-' . $range['max']) checked @endif>
                                                    <span>{{ $label }}</span>
                                                </label>
                                            @endforeach
                                            <label class="filter-option">
                                                <input type="radio" name="harga_range" value=""
                                                    @if (!request('harga_range')) checked @endif>
                                                <span>Semua Harga</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Fasilitas Filter -->
                                    <div class="filter-item">
                                        <div class="filter-toggle" data-filter="fasilitas">
                                            <div class="filter-text">
                                                <span class="filter-label">Fasilitas</span>
                                                <span class="filter-value">
                                                    @if (request('fasilitas'))
                                                        {{ count((array) request('fasilitas')) }} dipilih
                                                    @else
                                                        Semua Fasilitas
                                                    @endif
                                                </span>
                                            </div>
                                            <img src="{{ asset('images/arrow-down.svg') }}" alt="Dropdown Arrow"
                                                class="icon-dropdown" />
                                        </div>

                                        <!-- Dropdown Menu -->
                                        <div class="filter-dropdown" id="fasilitas-dropdown" style="display: none;">
                                            @foreach (['WiFi', 'AC', 'Kamar Mandi Dalam', 'Parkir', 'Dapur'] as $facility)
                                                <label class="filter-option">
                                                    <input type="checkbox" name="fasilitas[]"
                                                        value="{{ $facility }}"
                                                        @if (is_array(request('fasilitas')) && in_array($facility, request('fasilitas'))) checked @endif>
                                                    <span>{{ $facility }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                </div><!-- end .filters-row -->

                                <!-- ── Action Buttons ─────────────────────────────────────────── -->
                                <div class="buttons-group">
                                    <a href="{{ route('user.kost') }}" class="btn-reset">
                                        <img src="{{ asset('images/reset.svg') }}" alt="Reset Icon"
                                            class="icon-reset" />
                                        <span>Reset</span>
                                    </a>
                                    <button type="submit" class="btn-search">
                                        <span>Cari Kost</span>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </section>

            <!-- ────────────────────────────────────────────────────────
                 RESULTS HEADER (count & sort)
                 ──────────────────────────────────────────────────────── -->
            <section id="results-header">
                <div class="container">
                    <h2 class="results-count">{{ $kosts->total() }} Kost Ditemukan</h2>

                    <div class="sort-container">
                        <span class="sort-label">Urutkan:</span>
                        <form id="sort-form" action="{{ route('user.kost') }}" method="GET"
                            style="display: inline;">
                            <!-- Preserve other query params -->
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="tipe_kos"
                                value="{{ implode(',', (array) request('tipe_kos', [])) }}">
                            <input type="hidden" name="harga_min" value="{{ request('harga_min') }}">
                            <input type="hidden" name="harga_max" value="{{ request('harga_max') }}">

                            <select name="sort" class="sort-dropdown"
                                onchange="document.getElementById('sort-form').submit()">
                                <option value="newest" @if ($currentSort === 'newest') selected @endif>Terbaru
                                </option>
                                <option value="price_asc" @if ($currentSort === 'price_asc') selected @endif>Harga
                                    Terendah</option>
                                <option value="price_desc" @if ($currentSort === 'price_desc') selected @endif>Harga
                                    Tertinggi</option>
                                <option value="name" @if ($currentSort === 'name') selected @endif>Nama A-Z
                                </option>
                            </select>
                        </form>
                    </div>
                </div>
            </section>

            <!-- ────────────────────────────────────────────────────────
                 LISTINGS GRID (kost cards)
                 ──────────────────────────────────────────────────────── -->
            <section id="section-listings">
                <div class="container">
                    @if ($kosts->count() > 0)
                        <div class="listings-grid">
                            @foreach ($kosts as $kost)
                                <div class="card" data-kost-id="{{ $kost->id_kost }}">
                                    <div class="card-image-wrapper">
                                        <img src="{{ $kost->link_original }}" alt="{{ $kost->nama_kost }}"
                                            class="card-image" />
                                        <div class="badge badge-{{ $kost->tipe_kos }}">
                                            {{ strtoupper($kost->tipe_kos) }}
                                        </div>
                                        <button class="btn-favorite fav-btn" data-kost-id="{{ $kost->id_kost }}"
                                            data-is-favorit="{{ $kost->is_favorite ? 'true' : 'false' }}"
                                            aria-label="Favorite">
                                            <img src="{{ $kost->is_favorite ? asset('images/heart_filled.svg') : asset('images/heart_outline.svg') }}"
                                                alt="" />
                                        </button>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-header">
                                            <h3 class="card-title">{{ $kost->nama_kost }}</h3>
                                            <div class="card-rating">
                                                <img src="{{ asset('images/star.svg') }}" alt="Star" />
                                                <span>4.9</span>
                                            </div>
                                        </div>
                                        <div class="card-location">
                                            <img src="{{ asset('images/loc.svg') }}" alt="Location" />
                                            <span>{{ $kost->tempat_terdekat ?? 'Malang, Jawa Timur' }}</span>
                                        </div>
                                        <div class="card-price">
                                            <strong>{{ $kost->harga_formatted }}</strong>
                                            <span class="price-period">/bulan</span>
                                        </div>
                                        <div class="card-amenities">
                                            @php
                                                $amenities = [];
                                                if (!empty($kost->fasilitas_kamar)) {
                                                    $amenities = array_slice(
                                                        explode(',', $kost->fasilitas_kamar),
                                                        0,
                                                        3,
                                                    );
                                                }
                                                if (count($amenities) < 3 && !empty($kost->fasilitas_umum)) {
                                                    $amenities = array_merge(
                                                        $amenities,
                                                        array_slice(
                                                            explode(',', $kost->fasilitas_umum),
                                                            0,
                                                            3 - count($amenities),
                                                        ),
                                                    );
                                                }
                                            @endphp
                                            @foreach (array_slice($amenities, 0, 3) as $amenity)
                                                <span class="amenity-tag">{{ trim($amenity) }}</span>
                                            @endforeach
                                            @if (count($amenities) > 3)
                                                <span class="amenity-tag">{{ count($amenities) - 3 }}+</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <p>Tidak ada kost yang ditemukan. Coba ubah filter pencarian Anda.</p>
                        </div>
                    @endif
                </div>
            </section>

            <!-- ────────────────────────────────────────────────────────
                 PAGINATION
                 ──────────────────────────────────────────────────────── -->
            @if ($kosts->hasPages())
                <section id="section-pagination">
                    <nav class="pagination-container" aria-label="Pagination Navigation">
                        {{-- Previous Button --}}
                        @if ($kosts->onFirstPage())
                            <button class="nav-button prev" disabled aria-label="Previous page">
                                <img src="{{ asset('images/arrow-left.svg') }}" alt="Previous" />
                            </button>
                        @else
                            <a href="{{ $kosts->previousPageUrl() }}" class="nav-button prev"
                                aria-label="Previous page">
                                <img src="{{ asset('images/arrow-left.svg') }}" alt="Previous" />
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        <ul class="page-numbers">
                            @foreach ($kosts->getUrlRange(1, $kosts->lastPage()) as $page => $url)
                                @if ($page == $kosts->currentPage())
                                    <li><button class="page-number active"
                                            aria-current="page">{{ $page }}</button></li>
                                @else
                                    <li><a href="{{ $url }}" class="page-number">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>

                        {{-- Next Button --}}
                        @if ($kosts->hasMorePages())
                            <a href="{{ $kosts->nextPageUrl() }}" class="nav-button next" aria-label="Next page">
                                <img src="{{ asset('images/arrow-left.svg') }}" alt="Next" />
                            </a>
                        @else
                            <button class="nav-button next" disabled aria-label="Next page">
                                <img src="{{ asset('images/arrow-left.svg') }}" alt="Next" />
                            </button>
                        @endif
                    </nav>
                </section>
            @endif

        </main>

    </div><!-- end .page-body -->

    <!-- ============================================================
         CSRF Token & JavaScript
         ============================================================ -->
    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        // ── [A] Filter Dropdown Toggle ─────────────────────────────────
        document.querySelectorAll('.filter-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const filterName = this.dataset.filter;
                const dropdown = document.getElementById(filterName + '-dropdown');

                // Close other dropdowns
                document.querySelectorAll('.filter-dropdown').forEach(dd => {
                    if (dd !== dropdown) dd.style.display = 'none';
                });

                // Toggle current dropdown
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.filter-item') && !e.target.closest('.filter-dropdown')) {
                document.querySelectorAll('.filter-dropdown').forEach(dd => {
                    dd.style.display = 'none';
                });
            }
        });

        // ── [B] Toggle Favorit (AJAX) ──────────────────────────────────
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.fav-btn');
            if (!btn) return;

            e.preventDefault();
            e.stopPropagation();

            const kostId = parseInt(btn.dataset.kostId);
            const imgEl = btn.querySelector('img');
            const isFav = btn.dataset.isFavorit === 'true';
            const nextState = !isFav;

            // Optimistic UI
            btn.dataset.isFavorit = String(nextState);
            imgEl.src = nextState ?
                '{{ asset('images/heart_filled.svg') }}' :
                '{{ asset('images/heart_outline.svg') }}';

            // Send to server
            fetch('{{ route('favorit.toggle') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        kost_id: kostId
                    }),
                })
                .then(r => r.json())
                .then(data => {
                    // Success
                })
                .catch(err => {
                    // Revert on error
                    btn.dataset.isFavorit = String(isFav);
                    imgEl.src = isFav ?
                        '{{ asset('images/heart_filled.svg') }}' :
                        '{{ asset('images/heart_outline.svg') }}';
                    console.error('Favorit toggle error:', err);
                });
        });
    </script>

</body>

</html>
