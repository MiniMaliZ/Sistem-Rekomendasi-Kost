<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KostApp - Detail Kost</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" />
    <link rel="stylesheet" href="{{ asset('css/user/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/detailkost.css') }}">
</head>

<body>

    <!-- HEADER -->
    <section id="section-header">
        <div class="header-left">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="logo-icon">
            <div class="greeting">
                <h1 class="greeting-title">Detail Kost</h1>
                <p class="greeting-subtitle">Informasi lengkap mengenai kost yang kamu pilih.</p>
            </div>
        </div>

        <div class="header-right">
            <form action="{{ route('user.search') }}" method="GET" class="search-bar" role="search">
                <input type="text" name="q" placeholder="Cari nama kost atau lokasi..." class="search-input"
                    value="{{ request('q') }}" autocomplete="off">
                <button type="submit" class="search-button" aria-label="Cari">
                    <img src="{{ asset('images/search.svg') }}" alt="Search Icon" class="search-icon">
                </button>
            </form>

            @auth
                <a href="{{ route('profile') }}" class="user-avatar" aria-label="Lihat profil saya">
                    <img src="{{ auth()->user()->avatar ? asset('images/' . auth()->user()->avatar) : asset('images/profile_user.png') }}"
                        alt="Foto Profil {{ auth()->user()->nama_depan ?? 'Pengguna' }}">
                </a>
            @else
                <a href="{{ route('login') }}" class="signin-btn">
                    <span>Masuk</span>
                </a>
            @endauth
        </div>
    </section>

    <div class="page-body">

        @include('user.partials.sidebar')

        <main class="main-content">
            <section class="detail-main">
                <div class="detail-panel">
                    <div class="detail-heading">
                        <div>
                            <span class="badge badge-{{ $kost->tipe_kos }}">{{ strtoupper($kost->tipe_kos) }}</span>
                            <h2>{{ $kost->nama_kost }}</h2>
                            <p class="detail-location">{{ $kost->lokasi ?? 'Malang, Jawa Timur' }}</p>
                        </div>
                        <div class="price-box">
                            <span class="price-value">{{ $kost->harga }}</span>
                            <span class="price-period">/bulan</span>
                            <span class="price-note">Termasuk listrik, air & Wi-Fi</span>
                        </div>
                    </div>

                    <div class="image-gallery">
                        <div class="gallery-item">
                            <img src="{{ $kost->fotoKost?->foto_bangunan_url ?? asset('images/no-image.jpg') }}"
                                alt="Foto Bangunan {{ $kost->nama_kost }}">
                            <span>Foto Bangunan</span>
                        </div>
                        <div class="gallery-item">
                            <img src="{{ $kost->fotoKost?->foto_kamar_url ?? asset('images/no-image.jpg') }}"
                                alt="Foto Kamar {{ $kost->nama_kost }}">
                            <span>Foto Kamar</span>
                        </div>
                        <div class="gallery-item">
                            <img src="{{ $kost->fotoKost?->foto_kamar_mandi_url ?? asset('images/no-image.jpg') }}"
                                alt="Foto Kamar Mandi {{ $kost->nama_kost }}">
                            <span>Foto Kamar Mandi</span>
                        </div>
                    </div>

                    <div class="detail-sections">
                        <section class="section-card">
                            <h3>Spesifikasi Tipe Kamar</h3>
                            @php
                                $specs = array_filter(array_map('trim', explode(',', $kost->spesifikasi_tipe_kamar ?? '')));
                            @endphp
                            @if(count($specs) > 0)
                                <ul class="detail-list">
                                    @foreach ($specs as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>-</p>
                            @endif
                        </section>

                        <section class="section-card">
                            <h3>Fasilitas Kamar</h3>
                            @php
                                $roomFacilities = array_filter(array_map('trim', explode(',', $kost->fasilitas_kamar ?? '')));
                            @endphp
                            @if(count($roomFacilities) > 0)
                                <ul class="detail-list">
                                    @foreach ($roomFacilities as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>-</p>
                            @endif
                        </section>

                        <section class="section-card">
                            <h3>Fasilitas Kamar Mandi</h3>
                            @php
                                $bathFacilities = array_filter(array_map('trim', explode(',', $kost->fasilitas_kamar_mandi ?? '')));
                            @endphp
                            @if(count($bathFacilities) > 0)
                                <ul class="detail-list">
                                    @foreach ($bathFacilities as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>-</p>
                            @endif
                        </section>

                        <section class="section-card">
                            <h3>Fasilitas Umum</h3>
                            @php
                                $sharedFacilities = array_filter(array_map('trim', explode(',', $kost->fasilitas_umum ?? '')));
                            @endphp
                            @if(count($sharedFacilities) > 0)
                                <ul class="detail-list">
                                    @foreach ($sharedFacilities as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>-</p>
                            @endif
                        </section>

                        <section class="section-card">
                            <h3>Fasilitas Parkir</h3>
                            @php
                                $parkFacilities = array_filter(array_map('trim', explode(',', $kost->fasilitas_parkir ?? '')));
                            @endphp
                            @if(count($parkFacilities) > 0)
                                <ul class="detail-list">
                                    @foreach ($parkFacilities as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>-</p>
                            @endif
                        </section>

                        <section class="section-card">
                            <h3>Peraturan Kost</h3>
                            <ul class="detail-list">
                                <li>Tidak diperkenankan merokok di dalam kamar.</li>
                                <li>Dilarang membawa tamu inap tanpa izin penghuni kost.</li>
                                <li>Pembayaran sewa dilakukan setiap bulan di muka.</li>
                                <li>Jaga kebersihan area bersama dan tidak membuat keributan.</li>
                                <li>Listrik dan air dihitung sesuai penggunaan jika berlaku.</li>
                            </ul>
                        </section>
                    </div>

                    <section class="section-card description-card">
                        <h3>Deskripsi Kost</h3>
                        <p class="description-text">
                            Kost {{ $kost->nama_kost }} menawarkan hunian nyaman dengan fasilitas lengkap dan lokasi strategis. Cocok untuk penghuni yang mencari kost dengan suasana tenang, akses mudah ke fasilitas umum, dan lingkungan yang aman.
                        </p>
                    </section>
                </div>

                <aside class="detail-sidebar">
                    <section class="section-card summary-card">
                        <h3>Ringkasan Kost</h3>
                        <div class="summary-row">
                            <span>Jenis</span>
                            <strong>{{ ucfirst($kost->tipe_kos) }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Harga</span>
                            <strong>{{ $kost->harga_format }} / bulan</strong>
                        </div>
                        <div class="summary-row">
                            <span>Lokasi</span>
                            <strong>{{ $kost->lokasi }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Terdekat</span>
                            <strong>{{ $kost->tempat_terdekat ?: '-' }}</strong>
                        </div>
                    </section>

                    <section class="action-card">
                        <div class="action-card-top">
                            <div>
                                <h3>Tertarik dengan kost ini?</h3>
                                <p class="location-desc">Hubungi pemilik untuk informasi lebih lanjut.</p>
                            </div>
                            <button class="favorite-toggle" type="button" aria-label="Simpan ke favorit">❤</button>
                        </div>
                        
                        <button class="action-button secondary-button" type="button">Simpan kost favorit</button>
                    </section>

                    <section class="map-card">
                        <h3>Lokasi Kost</h3>
                        @php
                            $mapQuery = urlencode($kost->tempat_terdekat ?: $kost->lokasi ?: 'Malang');
                        @endphp
                        <iframe class="map-frame" loading="lazy"
                            src="https://www.google.com/maps?q={{ $mapQuery }}&output=embed"></iframe>
                        <a class="map-link" href="https://www.google.com/maps/search/?api=1&query={{ $mapQuery }}" target="_blank" rel="noreferrer">
                            Lihat di Google Maps
                        </a>
                    </section>

                    <section class="section-card nearby-card">
                        <h3>Lokasi Terdekat</h3>
                        @php
                            $nearby = array_filter(array_map('trim', explode(',', $kost->tempat_terdekat ?? '')));
                        @endphp
                        @if(count($nearby) > 0)
                            <ul class="nearby-list">
                                @foreach ($nearby as $item)
                                    <li>
                                        <span>{{ $item }}</span>
                                        <strong>~2,5 km</strong>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>-</p>
                        @endif
                    </section>
                </aside>
            </section>
        </main>
    </div>

</body>

</html>
