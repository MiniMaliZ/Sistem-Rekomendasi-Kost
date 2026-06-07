{{--
    Partial: user/partials/kost-card-small.blade.php
    Kartu kecil untuk section Kost Terbaru.

    $kost adalah array dengan key:
      nama, lokasi, harga_format, rating, ulasan, foto
--}}

<div class="kost-card-small">
    <img src="{{ $kost['foto'] }}"  alt="{{ $kost['nama'] }}" class="kost-card-small-img" loading="lazy">
    <div class="kost-card-small-body">
        <h4 class="small-card-name">{{ $kost['nama'] }}</h4>

        <div class="small-card-location">
            <img src="{{ asset('images/loc.svg') }}" alt="Lokasi">
            <span>{{ $kost['lokasi'] }}</span>
        </div>

        <div class="small-card-price">
            {{ $kost['harga_format'] }} <span>/bulan</span>
        </div>

        <div class="small-card-review">
            <div class="small-card-stars">
                <img src="{{ asset('images/star.svg') }}" alt="Bintang">
                <span>{{ number_format($kost['rating'], 1) }}</span>
            </div>
            <span class="small-card-review-count">
                ({{ number_format($kost['ulasan']) }} ulasan)
            </span>
        </div>
    </div>
</div>
