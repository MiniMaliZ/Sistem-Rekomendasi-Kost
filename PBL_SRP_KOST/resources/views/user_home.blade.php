<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KostApp - Home</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" />
    <link rel="stylesheet" href="css/user_home.css">
</head>

<body>

    <!-- Header Section -->
    <section id="section-header">

        <div class="header-left">
            <img src="./images/logo.svg" alt="Logo" class="logo-icon">
            <div class="greeting">
                <h1 class="greeting-title">Halo Kevin!</h1>
                <p class="greeting-subtitle">Yuk cari kost yang sesuai dengan dirimu sekarang.</p>
            </div>
        </div>

        <div class="header-right">
            <div class="search-bar">
                <input type="text" placeholder="Cari nama kost atau lokasi..." class="search-input">
                <button class="search-button">
                    <img src="./images/search.svg" alt="Search Icon" class="search-icon">
                </button>
            </div>
            <button class="notif-button">
                <img src="./images/notif.svg" alt="Notifications" class="notif-icon">
            </button>
            <a href="{{ url('/profile') }}" class="user-avatar" aria-label="Profil saya">
                <img src="{{ asset('images/profile_user.png') }}" alt="User Profile">
            </a>
        </div>

    </section>

    <!-- Page Body: Sidebar + Main Content -->
    <div class="page-body">

        <!-- Sidebar -->
        <section id="section-sidebar">
            <nav class="nav-pill">
                <ul class="nav-list">
                    <li>
                        <a href="{{ route('user_home') }}" class="nav-item nav-item--active" aria-label="Dashboard">
                            <x-tabler-layout-dashboard-filled class="nav-blade-icon nav-blade-icon--active" />
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user_listkost') }}" class="nav-item" aria-label="Daftar Kost">
                            <x-iconsax-lin-buliding class="nav-blade-icon" />
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user_fav') }}" class="nav-item" aria-label="Favorit">
                            <x-solar-heart-linear class="nav-blade-icon" />
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user_history') }}" class="nav-item" aria-label="Riwayat">
                            <x-clarity-history-line class="nav-blade-icon" />
                        </a>
                    </li>
                </ul>
            </nav>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button" aria-label="Keluar">
                    <x-iconsax-out-logout class="nav-blade-icon" />
                </button>
            </form>
        </section>

        <!-- Main Content -->
        <main class="main-content">

            <!-- Two-column layout wrapper -->
            <div class="home-grid">

                <!-- LEFT COLUMN -->
                <div class="home-col-left">

                    <!-- Hero Section -->
                    <section id="section-hero">
                        <div class="hero-wrapper">
                            <img src="./images/hero_bg.png" alt="Hero Background" class="hero-bg-img">
                            <div class="hero-overlay"></div>
                            <div class="hero-content">
                                <h2 class="hero-title">Temukan Kost Terbaik Untukmu.</h2>
                                <p class="hero-subtitle">Ribuan pilihan kost dengan harga transparan dan informasi yang
                                    lengkap.</p>
                                <button class="hero-btn">
                                    Cari Kost
                                    <img src="./images/longarrow_right.svg" alt="Arrow Right">
                                </button>
                            </div>
                        </div>
                    </section>

                    <!-- Rekomendasi Section -->
                    <section id="section-recommendations" class="section-recommendations">
                        <div class="section-container">
                            <div class="section-header">
                                <h3 class="section-title">Rekomendasi Kost</h3>
                                <a href="#" class="section-link">
                                    Lihat Semua
                                    <img src="./images/red_arrow.svg" alt="Arrow Right">
                                </a>
                            </div>
                            <div class="cards-grid">

                                <!-- Card 1 -->
                                <div class="kost-card">
                                    <div class="kost-card-img-wrap">
                                        <img src="./images/kost1.png" alt="Kost Putri Casa De Flora" class="card-photo">
                                        <span class="badge">PUTRI</span>
                                        <button class="fav-btn">
                                            <img src="./images/heart_outline.svg" alt="Favorite">
                                        </button>
                                    </div>
                                    <div class="kost-card-body">
                                        <div class="card-name-row">
                                            <h4 class="card-name">Kost Putri Casa De Flora</h4>
                                            <div class="card-rating">
                                                <img src="./images/star.svg" alt="Star">
                                                <span>4.9</span>
                                            </div>
                                        </div>
                                        <div class="card-location">
                                            <img src="./images/loc.svg" alt="Location">
                                            <span>Malang, Jawa Timur</span>
                                        </div>
                                        <div class="card-price">
                                            Rp. 1.350.000 <span>/bulan</span>
                                        </div>
                                        <div class="card-tags">
                                            <span class="tag">WiFi</span>
                                            <span class="tag">Kamar Mandi Dalam</span>
                                            <span class="tag">AC</span>
                                            <span class="tag">2+</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 2 -->
                                <div class="kost-card">
                                    <div class="kost-card-img-wrap">
                                        <img src="./images/kost2.png" alt="Kost Putri Green Hills"
                                            class="card-photo">
                                        <span class="badge">PUTRI</span>
                                        <button class="fav-btn">
                                            <img src="./images/heart_outline.svg" alt="Favorite">
                                        </button>
                                    </div>
                                    <div class="kost-card-body">
                                        <div class="card-name-row">
                                            <h4 class="card-name">Kost Putri Green Hills</h4>
                                            <div class="card-rating">
                                                <img src="./images/star.svg" alt="Star">
                                                <span>4.9</span>
                                            </div>
                                        </div>
                                        <div class="card-location">
                                            <img src="./images/loc.svg" alt="Location">
                                            <span>Malang, Jawa Timur</span>
                                        </div>
                                        <div class="card-price">
                                            Rp. 1.250.000 <span>/bulan</span>
                                        </div>
                                        <div class="card-tags">
                                            <span class="tag">WiFi</span>
                                            <span class="tag">Kamar Mandi Dalam</span>
                                            <span class="tag">AC</span>
                                            <span class="tag">2+</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>

                    <!-- Kost Terbaru Section -->
                    <section id="section-latest" class="section-latest">
                        <div class="section-container">
                            <div class="section-header">
                                <h3 class="section-title">Kost Terbaru</h3>
                                <a href="#" class="section-link">
                                    Lihat Semua
                                    <img src="./images/red_arrow.svg" alt="Arrow Right">
                                </a>
                            </div>
                            <div class="small-cards-grid">

                                <!-- Small Card 1 -->
                                <div class="kost-card-small">
                                    <img src="./images/kost3.png" alt="Kost Putri Central Park Premium"
                                        class="kost-card-small-img">
                                    <div class="kost-card-small-body">
                                        <h4 class="small-card-name">Kost Putri Central Park Premium</h4>
                                        <div class="small-card-location">
                                            <img src="./images/loc.svg" alt="Location">
                                            <span>Malang, Jawa Timur</span>
                                        </div>
                                        <div class="small-card-price">
                                            Rp. 1.250.000 <span>/bulan</span>
                                        </div>
                                        <div class="small-card-review">
                                            <div class="small-card-stars">
                                                <img src="./images/star.svg" alt="Star">
                                                <span>4.8</span>
                                            </div>
                                            <span class="small-card-review-count">(210 ulasan)</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Small Card 2 -->
                                <div class="kost-card-small">
                                    <img src="./images/kost4.png" alt="Kost Melati Residence"
                                        class="kost-card-small-img">
                                    <div class="kost-card-small-body">
                                        <h4 class="small-card-name">Kost Melati Residence</h4>
                                        <div class="small-card-location">
                                            <img src="./images/loc.svg" alt="Location">
                                            <span>Malang, Jawa Timur</span>
                                        </div>
                                        <div class="small-card-price">
                                            Rp. 1.400.000 <span>/bulan</span>
                                        </div>
                                        <div class="small-card-review">
                                            <div class="small-card-stars">
                                                <img src="./images/star.svg" alt="Star">
                                                <span>4.9</span>
                                            </div>
                                            <span class="small-card-review-count">(127 ulasan)</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Small Card 3 -->
                                <div class="kost-card-small">
                                    <img src="./images/kost5.png" alt="Kost Taman Anggrek"
                                        class="kost-card-small-img">
                                    <div class="kost-card-small-body">
                                        <h4 class="small-card-name">Kost Taman Anggrek</h4>
                                        <div class="small-card-location">
                                            <img src="./images/loc.svg" alt="Location">
                                            <span>Solo, Jawa Tengah</span>
                                        </div>
                                        <div class="small-card-price">
                                            Rp. 1.600.000 <span>/bulan</span>
                                        </div>
                                        <div class="small-card-review">
                                            <div class="small-card-stars">
                                                <img src="./images/star.svg" alt="Star">
                                                <span>4.8</span>
                                            </div>
                                            <span class="small-card-review-count">(12 ulasan)</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>

                </div><!-- end .home-col-left -->

                <!-- RIGHT COLUMN -->
                <div class="home-col-right">

                    <!-- Map Section -->
                    <section id="section-map">
                        <div class="map-wrapper">
                            <div class="map-header">
                                <h2>Lokasi Sekitarmu</h2>
                                <a href="#" class="map-link">
                                    <span>Lihat Maps</span>
                                    <img src="./images/red_arrow.svg" alt="Arrow">
                                </a>
                            </div>
                            <img src="./images/maps.png" alt="Map of surrounding area" class="map-img">
                        </div>
                    </section>

                    <!-- Empty State Section -->
                    <section id="section-empty-state">
                        <div class="empty-state-card">
                            <div class="empty-state-content">
                                <div class="illustration-wrap">
                                    <img src="./images/illus.svg" alt="Card background" class="illus-bg">

                                </div>
                                <div class="empty-state-text">
                                    <h3>Belum ada kost favorit</h3>
                                    <p>Simpan kost yang kamu suka untuk<br>akses lebih cepat nanti.</p>
                                </div>
                            </div>
                            <button class="explore-btn">
                                <span>Jelajahi Kost</span>
                            </button>
                        </div>
                    </section>

                </div><!-- end .home-col-right -->

            </div><!-- end .home-grid -->

        </main>

    </div><!-- end .page-body -->

</body>

</html>
