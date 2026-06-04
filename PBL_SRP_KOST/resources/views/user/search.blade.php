<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hasil Pencarian "{{ $keyword }}" - KostApp</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/user/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/sidebar.css') }}">
</head>

<body>

    <section id="section-header">
        <div class="header-left">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="logo-icon">
            <div class="greeting">
                <h1 class="greeting-title">Hasil Pencarian</h1>
                <p class="greeting-subtitle">Menampilkan kost untuk: "{{ $keyword }}"</p>
            </div>
        </div>
        <div class="header-right">
            <form action="{{ route('user.search') }}" method="GET" class="search-bar">
                <input type="text" name="q" placeholder="Cari nama kost atau lokasi..." class="search-input"
                    value="{{ $keyword }}" autocomplete="off">
                <button type="submit" class="search-button" aria-label="Cari">
                    <img src="{{ asset('images/search.svg') }}" alt="Search" class="search-icon">
                </button>
            </form>
            <div class="user-avatar">
                <img src="{{ asset('images/profile_user.png') }}" alt="{{ $user['nama'] }}">
            </div>
        </div>
    </section>

    <div class="page-body">
        <section id="section-sidebar">
            <nav class="nav-pill">
                <ul class="nav-list">
                    <li><a href="{{ route('user.dashboard') }}" class="nav-item" aria-label="Home">
                            <x-tabler-layout-dashboard-filled class="nav-blade-icon" />
                        </a></li>
                    <li><a href="{{ route('user.kost') }}" class="nav-item nav-item--active" aria-label="Kost">
                            <x-iconsax-lin-buliding class="nav-blade-icon nav-blade-icon--active" />
                        </a></li>
                    <li><a href="{{ route('user.favorit') }}" class="nav-item" aria-label="Favorit">
                            <x-solar-heart-linear class="nav-blade-icon" />
                        </a></li>
                    <li><a href="{{ route('user.riwayat') }}" class="nav-item" aria-label="Riwayat">
                            <x-clarity-history-line class="nav-blade-icon" />
                        </a></li>
                </ul>
            </nav>
            <button class="logout-button" aria-label="Logout" onclick="alert('Login aktif setelah database siap.')">
                <x-iconsax-out-logout class="nav-blade-icon" />
            </button>
        </section>

        <main class="main-content">
            <div class="search-results-wrapper">
                <div class="results-header">
                    <h2>Hasil untuk "{{ $keyword }}"</h2>
                    <p>{{ count($hasil) }} kost ditemukan</p>
                </div>

                @if (count($hasil) > 0)
                    <div class="results-grid">
                        @foreach ($hasil as $kost)
                            @include('user.partials.kost-card', ['kost' => $kost])
                        @endforeach
                    </div>
                @else
                    <div class="no-result">
                        <h3>Kost tidak ditemukan</h3>
                        <p>Coba kata kunci lain, misalnya nama kost atau nama kota.</p>
                        <a href="{{ route('user.dashboard') }}" class="back-link">← Kembali ke Home</a>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            document.body.addEventListener('click', function(e) {
                const btn = e.target.closest('.fav-btn');
                if (!btn) return;
                e.preventDefault();
                const kostId = parseInt(btn.dataset.kostId);
                const imgEl = btn.querySelector('img');
                const isFav = btn.dataset.isFavorit === 'true';
                const next = !isFav;
                btn.dataset.isFavorit = String(next);
                imgEl.src = next ? '{{ asset('images/heart_filled.svg') }}' :
                    '{{ asset('images/heart_outline.svg') }}';
                fetch('{{ route('favorit.toggle') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        kost_id: kostId
                    }),
                }).then(r => r.json()).then(d => {
                    if (!d.success) {
                        btn.dataset.isFavorit = String(isFav);
                        imgEl.src = isFav ? '{{ asset('images/heart_filled.svg') }}' :
                            '{{ asset('images/heart_outline.svg') }}';
                    }
                });
            });
        });
    </script>
</body>

</html>
