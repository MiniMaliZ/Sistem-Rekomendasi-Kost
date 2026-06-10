<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'History - roomor' }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" />
    <link rel="stylesheet" href="{{ asset('css/user/history.css') }}">
</head>

<body>

    <!-- Header Section -->
    <section id="section-header">

        <div class="header-left">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="logo-icon">
            <div class="greeting">
                <h1 class="greeting-title">History</h1>
                <p class="greeting-subtitle">Kost yang sudah kamu lihat akan muncul di sini.</p>
            </div>
        </div>

        <div class="header-right {{ $isGuest ? 'header-right--guest' : '' }}">
            @if (!$isGuest)
                <div class="search-bar">
                    <input type="text" placeholder="Cari nama kost atau lokasi..." class="search-input"
                        id="search-input">
                    <button class="search-button" id="search-button">
                        <img src="{{ asset('images/search.svg') }}" alt="Search Icon" class="search-icon">
                    </button>
                </div>
            @endif
            @if ($isGuest)
                <a href="{{ route('login') }}" class="signin-btn">Masuk</a>
            @else
                <a href="{{ url('/profile') }}" class="user-avatar" aria-label="Profil saya">
                    <img src="{{ auth()->user()->avatar_url }}" alt="User Profile">
                </a>
            @endif
        </div>

    </section>

    <!-- Page Body: Sidebar + Main Content -->
    <div class="page-body">

        <!-- Sidebar -->
        @include('user.partials.sidebar')

        <!-- Main Content Area -->
        <main class="main-content">

            {{-- ── Filter Chips: hanya tampil saat auth ── --}}
            @if (!$isGuest)
                <section id="section-filters">
                    <div class="filters-container">
                        <a href="{{ route('user.history') }}"
                            class="filter-chip {{ $filter === 'semua' ? 'active' : '' }}">Semua</a>
                        <a href="{{ route('user.history', ['filter' => 'hari_ini']) }}"
                            class="filter-chip {{ $filter === 'hari_ini' ? 'active' : '' }}">Hari ini</a>
                        <a href="{{ route('user.history', ['filter' => '7_hari']) }}"
                            class="filter-chip {{ $filter === '7_hari' ? 'active' : '' }}">7 Hari Terakhir</a>
                    </div>
                </section>
            @endif

            <!-- Property Cards / Empty States -->
            <section id="section-property-card">

                {{-- ── STATE: GUEST ── --}}
                @if ($isGuest)
                    <section id="section-empty-guest" class="section-empty-state section-empty-state--guest">
                        <div class="empty-state-wrapper">
                            <div class="empty-state-illustration">
                                <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="empty-heart-svg">
                                    <circle cx="60" cy="60" r="58" fill="#f5f0eb" stroke="#e0cfc6"
                                        stroke-width="2" />
                                    {{-- Ikon jam / history --}}
                                    <circle cx="60" cy="60" r="26" stroke="#c0a898" stroke-width="2.5"
                                        fill="#fff8f4" />
                                    <path d="M60 46v14l8 8" stroke="#c0a898" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M46 36l-6-6M74 36l6-6" stroke="#d4bdb4" stroke-width="2"
                                        stroke-linecap="round" opacity="0.6" />
                                </svg>
                            </div>
                            <h3 class="empty-state-title">Masuk untuk Melihat Riwayat</h3>
                            <p class="empty-state-desc">
                                Lacak kost yang pernah kamu kunjungi agar mudah ditemukan lagi.<br>
                                Fitur ini tersedia setelah kamu masuk ke akun.
                            </p>
                            <a href="{{ route('login') }}" class="empty-cta-btn">
                                Masuk Sekarang
                            </a>
                            <a href="{{ route('user.kost') }}" class="empty-browse-btn">
                                Jelajahi Kost Dulu
                            </a>
                        </div>
                    </section>

                    {{-- ── STATE: AUTH — Kosong ── --}}
                @elseif($riwayat->isEmpty())
                    <section id="section-empty-auth" class="section-empty-state">
                        <div class="empty-state-wrapper">
                            <div class="empty-state-illustration">
                                <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="empty-heart-svg">
                                    <circle cx="60" cy="60" r="58" fill="#fefcf8" stroke="#e5e5e5"
                                        stroke-width="2" />
                                    <circle cx="60" cy="60" r="26" stroke="#c8c0bc" stroke-width="2.5"
                                        fill="none" />
                                    <path d="M60 46v14l8 8" stroke="#c8c0bc" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h3 class="empty-state-title">Belum Ada Riwayat</h3>
                            <p class="empty-state-desc">
                                Kost yang kamu kunjungi akan otomatis tercatat di sini.<br>
                                Mulai jelajahi dan temukan kost yang cocok untukmu.
                            </p>
                            <a href="{{ route('user.kost') }}" class="empty-cta-btn">
                                Jelajahi Kost
                            </a>
                        </div>
                    </section>

                    {{-- ── STATE: AUTH — Ada data ── --}}
                @else
                    @foreach ($riwayat as $item)
                        @php
                            $kost = $item->kost;
                            $foto = $kost->fotoKost;
                            $fotoBg = $foto ? asset($foto->foto_bangunan) : asset('images/kost_default.png');
                            $badge = ucfirst($kost->tipe_kos ?? '-');
                            $fasilitas = array_filter(array_map('trim', explode(',', $kost->fasilitas_kamar ?? '')));
                            $fasLimit = array_slice($fasilitas, 0, 3);
                            $fasMore = count($fasilitas) > 3 ? count($fasilitas) - 3 . '+' : null;
                            $avgRating = $kost->feedback->avg('rating');
                            $ratingStr = $avgRating ? number_format($avgRating, 1) : '-';
                            $totalUlasan = $kost->feedback->count();
                            $seenAt = $item->created_at ? $item->created_at->locale('id')->diffForHumans() : null;
                        @endphp

                        <div class="property-card" data-id="{{ $kost->id_kost }}"
                            data-nama="{{ strtolower($kost->nama_kost) }}"
                            data-lokasi="{{ strtolower($kost->tempat_terdekat ?? '') }}">
                            <div class="property-card__inner">

                                <!-- Left: Image & Info -->
                                <div class="property-card__main">
                                    <div class="property-card__image-wrapper">
                                        <img src="{{ $fotoBg }}" alt="{{ $kost->nama_kost }}"
                                            class="property-card__image"
                                            onerror="this.src='{{ asset('images/kost_default.png') }}'" />
                                        <div class="property-card__badge">{{ strtoupper($badge) }}</div>
                                    </div>

                                    <div class="property-card__info">
                                        <div class="property-card__info-top">
                                            <div class="property-card__title-group">
                                                <div class="property-card__title-row">
                                                    <h2 class="property-card__title">{{ $kost->nama_kost }}</h2>
                                                    @if ($seenAt)
                                                        <div class="property-card__seen">
                                                            <img src="{{ asset('images/dot.svg') }}" alt=""
                                                                class="property-card__dot" />
                                                            <span class="property-card__seen-text">Dilihat
                                                                {{ $seenAt }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="property-card__location">
                                                    <div class="property-card__location-icon">
                                                        <img src="{{ asset('images/loc.svg') }}" alt="Location" />
                                                    </div>
                                                    <span class="property-card__location-text">
                                                        {{ $kost->tempat_terdekat ?? 'Malang' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="property-card__price">
                                                {{ $kost->harga_formatted }}
                                                <span class="property-card__price-period">/bulan</span>
                                            </div>
                                        </div>

                                        <div class="property-card__amenities">
                                            @foreach ($fasLimit as $fas)
                                                <span class="property-card__amenity">{{ $fas }}</span>
                                            @endforeach
                                            @if ($fasMore)
                                                <span class="property-card__amenity">{{ $fasMore }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Middle: Rating -->
                                <div class="property-card__rating">
                                    <div class="property-card__rating-icon">
                                        <img src="{{ asset('images/star.svg') }}" alt="Star" />
                                    </div>
                                    <span class="property-card__rating-text">
                                        {{ $ratingStr }}
                                        @if ($totalUlasan > 0)
                                            ({{ $totalUlasan }} Ulasan)
                                        @endif
                                    </span>
                                </div>

                                <!-- Right: Actions -->
                                <div class="property-card__actions">
                                    <button class="property-card__btn btn-favorit" data-id="{{ $kost->id_kost }}"
                                        aria-label="Favorite">
                                        <img src="{{ asset('images/favorite.svg') }}" alt="" />
                                    </button>
                                    <a href="{{ route('user.kost.show', $kost->id_kost) }}"
                                        class="property-card__btn" aria-label="View">
                                        <img src="{{ asset('images/view.svg') }}" alt="" />
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @endif

            </section>

        </main>

    </div><!-- end .page-body -->

    @if (!$isGuest)
        <script>
            // ── Search client-side filter ──
            const searchInput = document.getElementById('search-input');
            const searchButton = document.getElementById('search-button');

            function filterCards() {
                const q = (searchInput?.value ?? '').toLowerCase().trim();
                document.querySelectorAll('.property-card').forEach(card => {
                    const nama = card.dataset.nama || '';
                    const lokasi = card.dataset.lokasi || '';
                    card.style.display = (nama.includes(q) || lokasi.includes(q) || q === '') ? '' : 'none';
                });
            }

            searchInput?.addEventListener('input', filterCards);
            searchButton?.addEventListener('click', filterCards);

            // ── Favorit toggle (AJAX) ──
            document.querySelectorAll('.btn-favorit').forEach(btn => {
                btn.addEventListener('click', async function(e) {
                    e.stopPropagation();
                    const kostId = this.dataset.id;
                    try {
                        const res = await fetch('{{ route('favorit.toggle') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                kost_id: kostId
                            }),
                        });
                        const data = await res.json();
                        const img = this.querySelector('img');
                        img.src = data.is_favorit ?
                            '{{ asset('images/heart_filled.svg') }}' :
                            '{{ asset('images/heart_outline.svg') }}';
                    } catch (err) {
                        console.error('Toggle favorit gagal:', err);
                    }
                });
            });
        </script>
    @endif

</body>

</html>
