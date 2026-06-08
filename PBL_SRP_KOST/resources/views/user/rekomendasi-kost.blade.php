<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rekomendasi Kost - roomor</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" />
    <link rel="stylesheet" href="{{ asset('css/user/listkost.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/rekomendasi.css') }}">
</head>

<body>
    <section id="section-header">
        <div class="header-left">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="logo-icon">
            <div class="greeting">
                <h1 class="greeting-title">Rekomendasi Kost</h1>
                <p class="greeting-subtitle">Pilih preferensi dan temukan kost yang paling sesuai.</p>
            </div>
        </div>

        <div class="header-right">
            <a href="{{ route('profile') }}" class="user-avatar" aria-label="Profil saya">
                <img src="{{ asset('images/profile_user.png') }}" alt="User Profile">
            </a>
        </div>
    </section>

    <div class="page-body">
        @include('user.partials.sidebar')

        <main class="main-content recommendation-main">
            <section class="recommendation-shell">
                <form action="{{ route('user.rekomendasi') }}" method="GET" class="recommendation-form">
                    <input type="hidden" name="submitted" value="1">

                    <div class="recommendation-form__header">
                        <div>
                            <span class="recommendation-kicker">Preferensi</span>
                            <h2>Cari kost yang cocok</h2>
                        </div>
                    </div>

                    <div class="recommendation-grid">
                        <label class="recommendation-field">
                            <span>Anggaran maksimal</span>
                            <div class="recommendation-input">
                                <b>Rp</b>
                                <input type="number" name="budget_max" min="0" step="50000" value="{{ $form['budget_max'] }}">
                            </div>
                        </label>

                        <label class="recommendation-field">
                            <span>Jarak maksimal</span>
                            <div class="recommendation-input">
                                <input type="number" name="max_distance" min="0" step="0.1" value="{{ $form['max_distance'] }}">
                                <b>km</b>
                            </div>
                        </label>
                    </div>

                    <div class="recommendation-block">
                        <span class="recommendation-label">Tipe kost</span>
                        <div class="recommendation-segment">
                            @foreach(['' => 'Semua', 'Kos Putra' => 'Putra', 'Kos Putri' => 'Putri', 'Kos Campur' => 'Campur'] as $value => $label)
                                <label>
                                    <input type="radio" name="tipe_kos" value="{{ $value }}" @checked($form['tipe_kos'] === $value)>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="recommendation-block">
                        <span class="recommendation-label">Prioritas</span>
                        <div class="recommendation-segment scenario-segment">
                            @foreach($scenarios as $key => $label)
                                <label>
                                    <input type="radio" name="scenario" value="{{ $key }}" @checked($form['scenario'] === $key)>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="recommendation-block">
                        <span class="recommendation-label">Fasilitas</span>
                        <div class="recommendation-facilities">
                            @foreach($facilityOptions as $key => $option)
                                <label>
                                    <input type="checkbox" name="facilities[]" value="{{ $key }}" @checked(in_array($key, $form['facilities'], true))>
                                    <span>{{ $option['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <label class="recommendation-toggle">
                        <input type="hidden" name="require_all_facilities" value="0">
                        <input type="checkbox" name="require_all_facilities" value="1" @checked($form['require_all_facilities'])>
                        <span>Wajib cocok semua fasilitas pilihan</span>
                    </label>

                    <div class="recommendation-actions">
                        <button type="submit" class="recommendation-submit">Cari Rekomendasi</button>
                    </div>
                </form>
            </section>

            <section class="recommendation-results">
                <div class="recommendation-results__header">
                    <div>
                        <span class="recommendation-kicker">Hasil</span>
                        <h2>{{ $submitted ? count($recommendations) . ' kost direkomendasikan' : 'Siap mencari rekomendasi' }}</h2>
                    </div>
                    @if($submitted && $summary)
                        <span class="recommendation-count">{{ $summary['filtered_alternatives'] }} cocok dari {{ $summary['total_alternatives'] }} data</span>
                    @endif
                </div>

                @if($apiError)
                    <div class="recommendation-alert">{{ $apiError }}</div>
                @elseif(! $submitted)
                    <div class="recommendation-empty">
                        <strong>Isi preferensi terlebih dahulu</strong>
                        <span>Hasil rekomendasi akan muncul setelah pencarian dilakukan.</span>
                    </div>
                @elseif(count($recommendations) === 0)
                    <div class="recommendation-empty">
                        <strong>Belum ada kost yang cocok</strong>
                        <span>Coba longgarkan anggaran, jarak, atau fasilitas pilihan.</span>
                    </div>
                @else
                    <div class="recommendation-card-grid">
                        @foreach($recommendations as $kost)
                            <article class="recommendation-card">
                                <div class="recommendation-card__media">
                                    <img src="{{ $kost['foto'] }}" alt="{{ $kost['nama'] }}" loading="lazy" onerror="this.src='{{ asset('images/kost1.png') }}'">
                                    <span>{{ $kost['tipe'] }}</span>
                                    <button class="recommendation-favorite fav-btn" data-kost-id="{{ $kost['id'] }}"
                                        data-is-favorit="{{ $kost['is_favorit'] ? 'true' : 'false' }}"
                                        aria-label="{{ $kost['is_favorit'] ? 'Hapus dari favorit' : 'Tambah ke favorit' }}">
                                        <img src="{{ $kost['is_favorit'] ? asset('images/heart_filled.svg') : asset('images/heart_outline.svg') }}" alt="">
                                    </button>
                                </div>

                                <div class="recommendation-card__body">
                                    <div class="recommendation-card__title">
                                        <h3>{{ $kost['nama'] }}</h3>
                                        @if($kost['rating'] > 0)
                                            <span>{{ number_format($kost['rating'], 1) }}</span>
                                        @endif
                                    </div>

                                    <div class="recommendation-meta">
                                        <span>{{ $kost['lokasi'] }}</span>
                                        @if($kost['jarak'])
                                            <span>{{ $kost['jarak'] }} dari kampus</span>
                                        @endif
                                        <span>{{ $kost['spesifikasi'] }}</span>
                                    </div>

                                    <div class="recommendation-price">{{ $kost['harga_format'] }} <span>/bulan</span></div>

                                    <div class="recommendation-match">
                                        <strong>{{ $kost['matched_facility_count'] }} fasilitas cocok</strong>
                                        <div>
                                            @forelse(array_slice($kost['matched_facilities'], 0, 4) as $facility)
                                                <span>{{ $facility }}</span>
                                            @empty
                                                <span>Preferensi umum</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    <a href="{{ route('user.kost.show', $kost['id']) }}" class="recommendation-detail">Lihat Detail</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </main>
    </div>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.fav-btn');
            if (!btn) return;

            e.preventDefault();
            e.stopPropagation();

            const kostId = parseInt(btn.dataset.kostId);
            const imgEl = btn.querySelector('img');
            const isFav = btn.dataset.isFavorit === 'true';
            const nextState = !isFav;

            btn.dataset.isFavorit = String(nextState);
            imgEl.src = nextState ?
                '{{ asset('images/heart_filled.svg') }}' :
                '{{ asset('images/heart_outline.svg') }}';

            fetch('{{ route('favorit.toggle') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ kost_id: kostId }),
                })
                .catch(() => {
                    btn.dataset.isFavorit = String(isFav);
                    imgEl.src = isFav ?
                        '{{ asset('images/heart_filled.svg') }}' :
                        '{{ asset('images/heart_outline.svg') }}';
                });
        });
    </script>
</body>

</html>
