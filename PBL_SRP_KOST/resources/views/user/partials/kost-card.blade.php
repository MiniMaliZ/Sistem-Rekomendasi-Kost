{{--
    Partial: user/partials/kost-card.blade.php
    Kartu besar untuk section Rekomendasi Kost.

    $kost adalah array dengan key:
      id, nama, tipe, lokasi, harga_format, rating,
      fasilitas_tags, foto, is_favorit
--}}
<a href="{{ route('user.kost.show', $kost['id']) }}" class="kost-card-link">
<div class="kost-card">
    <div class="kost-card-img-wrap">
        <img src="{{ $kost['foto'] }}" alt="{{ $kost['nama'] }}" class="card-photo" loading="lazy">
        <span class="badge">{{ $kost['tipe'] }}</span>

        <button class="fav-btn" data-kost-id="{{ $kost['id'] }}"
            data-is-favorit="{{ $kost['is_favorit'] ? 'true' : 'false' }}"
            aria-label="{{ $kost['is_favorit'] ? 'Hapus dari favorit' : 'Tambah ke favorit' }}">
            <img src="{{ $kost['is_favorit'] ? asset('images/heart_filled.svg') : asset('images/heart_outline.svg') }}"
                alt="Favorit">
        </button>
    </div>

    <div class="kost-card-body">
        <div class="card-name-row">
            <h4 class="card-name">{{ $kost['nama'] }}</h4>
            <div class="card-rating">
                <img src="{{ asset('images/star.svg') }}" alt="Bintang">
                <span>{{ number_format($kost['rating'], 1) }}</span>
            </div>
        </div>

        <div class="card-location">
            <img src="{{ asset('images/loc.svg') }}" alt="Lokasi">
            <span>{{ $kost['lokasi'] }}</span>
        </div>

        <div class="card-price">
            {{ $kost['harga_format'] }} <span>/bulan</span>
        </div>

        <div class="card-tags">
            @foreach ($kost['fasilitas_tags'] as $tag)
                <span class="tag">{{ $tag }}</span>
            @endforeach
        </div>
    </div>
</div>
</a>