<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KostApp - Header</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" />
    <link rel="stylesheet" href="css/user_listkost.css">
</head>

<body>

    <!-- Header Section: Contains Logo, Greeting, Search Bar, and User Actions -->
    <section id="section-header">

        <!-- Left Area: Logo and Greeting Text -->
        <div class="header-left">
            <!-- Logo Icon -->
            <img src="./images/logo.svg" alt="Logo" class="logo-icon">

            <!-- Greeting Content -->
            <div class="greeting">
                <h1 class="greeting-title">Cari Kost</h1>
                <p class="greeting-subtitle">Temukan kost sesuai lokasi, budget dan fasilitas yang kamu butuhkan.</p>
            </div>
        </div>

        <!-- Right Area: Search, Notifications, and Profile -->
        <div class="header-right">

            <!-- Search Bar Component -->
            <div class="search-bar">
                <input type="text" placeholder="Cari nama kost, lokasi..." class="search-input">
                <button class="search-button">
                    <img src="./images/search.svg" alt="Search Icon" class="search-icon">
                </button>
            </div>

            <!-- Notification Button -->
            <button class="notif-button">
                <img src="./images/notif.svg" alt="Notifications" class="notif-icon">
            </button>

            <!-- User Avatar -->
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
                        <a href="{{ route('user_home') }}" class="nav-item" aria-label="Dashboard">
                            <x-tabler-layout-dashboard-filled class="nav-blade-icon" />
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user_listkost') }}" class="nav-item  nav-item--active" aria-label="Daftar Kost">
                            <x-iconsax-bol-buliding class="nav-blade-icon nav-blade-icon--active" />
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

        <!-- Main Content Area -->
        <main class="main-content">

            <section id="section-search-filter">
                <div class="search-filter-container">
                    <!-- Location Selector -->
                    <div class="location-selector">
                        <div class="location-text-group">
                            <img src="./images/big-loc.svg" alt="Location Icon" class="icon-location" />
                            <span class="location-text">Lowokwaru, Malang</span>
                        </div>
                        <img src="./images/arrow-down.svg" alt="Dropdown Arrow" class="icon-dropdown" />
                    </div>

                    <!-- Filter Card -->
                    <div class="filter-card">
                        <div class="filter-content">
                            <!-- Filters Row -->
                            <div class="filters-row">
                                <!-- Tipe Filter -->
                                <div class="filter-item">
                                    <div class="filter-text">
                                        <span class="filter-label">Tipe</span>
                                        <span class="filter-value">Semua Tipe</span>
                                    </div>
                                    <img src="./images/arrow-down.svg" alt="Dropdown Arrow" class="icon-dropdown" />
                                </div>

                                <!-- Harga Filter -->
                                <div class="filter-item">
                                    <div class="filter-text">
                                        <span class="filter-label">Harga</span>
                                        <span class="filter-value">Maks. Rp.1.500.000</span>
                                    </div>
                                    <img src="./images/arrow-down.svg" alt="Dropdown Arrow" class="icon-dropdown" />
                                </div>

                                <!-- Fasilitas Filter -->
                                <div class="filter-item">
                                    <div class="filter-text">
                                        <span class="filter-label">Fasilitas</span>
                                        <span class="filter-value">Pilih Fasilitas</span>
                                    </div>
                                    <img src="./images/arrow-down.svg" alt="Dropdown Arrow" class="icon-dropdown" />
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="buttons-group">
                                <button class="btn-reset">
                                    <img src="./images/reset.svg" alt="Reset Icon" class="icon-reset" />
                                    <span>Reset</span>
                                </button>
                                <button class="btn-search">
                                    <span>Cari Kost</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="results-header">
                <div class="container">
                    <h2 class="results-count">21 Kost Ditemukan</h2>

                    <div class="sort-container">
                        <span class="sort-label">Urutkan:</span>
                        <button class="sort-dropdown" aria-haspopup="listbox" aria-expanded="false">
                            <span class="sort-value">Terbaru</span>
                            <img src="./images/arrow-down.svg" alt="Dropdown arrow" class="sort-icon" />
                        </button>
                    </div>
                </div>
            </section>

            <section id="section-listings">
                <div class="container">
                    <div class="listings-grid">
                        <!-- Card 1 -->
                        <div class="card">
                            <div class="card-image-wrapper">
                                <img src="./images/kost1.png" alt="Kost Putri Casa De Flora" class="card-image" />
                                <div class="badge badge-putri">PUTRI</div>
                                <button class="btn-favorite" aria-label="Favorite">
                                    <img src="./images/favorite.svg" alt="" />
                                </button>
                            </div>
                            <div class="card-content">
                                <div class="card-header">
                                    <h3 class="card-title">Kost Putri Casa De Flora</h3>
                                    <div class="card-rating">
                                        <img src="./images/star.svg" alt="Star" />
                                        <span>4.9</span>
                                    </div>
                                </div>
                                <div class="card-location">
                                    <img src="./images/loc.svg" alt="Location" />
                                    <span>Malang, Jawa Timur</span>
                                </div>
                                <div class="card-price">
                                    <strong>Rp. 1.350.000</strong>
                                    <span class="price-period">/bulan</span>
                                </div>
                                <div class="card-amenities">
                                    <span class="amenity-tag">WiFi</span>
                                    <span class="amenity-tag">Kamar Mandi Dalam</span>
                                    <span class="amenity-tag">AC</span>
                                    <span class="amenity-tag">2+</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="card">
                            <div class="card-image-wrapper">
                                <img src="./images/kost4.png" alt="Kost Melati Residence" class="card-image" />
                                <div class="badge badge-putra">PUTRA</div>
                                <button class="btn-favorite" aria-label="Favorite">
                                    <img src="./images/favorite.svg" alt="" />
                                </button>
                            </div>
                            <div class="card-content">
                                <div class="card-header">
                                    <h3 class="card-title">Kost Melati Residence</h3>
                                    <div class="card-rating">
                                        <img src="./images/star.svg" alt="Star" />
                                        <span>4.9</span>
                                    </div>
                                </div>
                                <div class="card-location">
                                    <img src="./images/loc.svg" alt="Location" />
                                    <span>Malang, Jawa Timur</span>
                                </div>
                                <div class="card-price">
                                    <strong>Rp. 1.400.000</strong>
                                    <span class="price-period">/bulan</span>
                                </div>
                                <div class="card-amenities">
                                    <span class="amenity-tag">WiFi</span>
                                    <span class="amenity-tag">Kamar Mandi Dalam</span>
                                    <span class="amenity-tag">AC</span>
                                    <span class="amenity-tag">2+</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="card">
                            <div class="card-image-wrapper">
                                <div style="position: relative; width: 100%; height: 100%;">
                                    <img src="./images/kost2.png" alt="Kost Putri Green Hills" class="card-image" />
                                </div>
                                <div class="badge badge-putri">PUTRI</div>
                                <button class="btn-favorite" aria-label="Favorite">
                                    <img src="./images/favorite.svg" alt="" />
                                </button>
                            </div>
                            <div class="card-content">
                                <div class="card-header">
                                    <h3 class="card-title">Kost Putri Green Hills</h3>
                                    <div class="card-rating">
                                        <img src="./images/star.svg" alt="Star" />
                                        <span>4.9</span>
                                    </div>
                                </div>
                                <div class="card-location">
                                    <img src="./images/loc.svg" alt="Location" />
                                    <span>Malang, Jawa Timur</span>
                                </div>
                                <div class="card-price">
                                    <strong>Rp. 1.250.000</strong>
                                    <span class="price-period">/bulan</span>
                                </div>
                                <div class="card-amenities">
                                    <span class="amenity-tag">WiFi</span>
                                    <span class="amenity-tag">Kamar Mandi Dalam</span>
                                    <span class="amenity-tag">AC</span>
                                    <span class="amenity-tag">2+</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4 (duplicate row) -->
                        <div class="card">
                            <div class="card-image-wrapper">
                                <img src="./images/kost1.png" alt="Kost Putri Casa De Flora" class="card-image" />
                                <div class="badge badge-putri">PUTRI</div>
                                <button class="btn-favorite" aria-label="Favorite">
                                    <img src="./images/favorite.svg" alt="" />
                                </button>
                            </div>
                            <div class="card-content">
                                <div class="card-header">
                                    <h3 class="card-title">Kost Putri Casa De Flora</h3>
                                    <div class="card-rating">
                                        <img src="./images/star.svg" alt="Star" />
                                        <span>4.9</span>
                                    </div>
                                </div>
                                <div class="card-location">
                                    <img src="./images/loc.svg" alt="Location" />
                                    <span>Malang, Jawa Timur</span>
                                </div>
                                <div class="card-price">
                                    <strong>Rp. 1.350.000</strong>
                                    <span class="price-period">/bulan</span>
                                </div>
                                <div class="card-amenities">
                                    <span class="amenity-tag">WiFi</span>
                                    <span class="amenity-tag">Kamar Mandi Dalam</span>
                                    <span class="amenity-tag">AC</span>
                                    <span class="amenity-tag">2+</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card 5 -->
                        <div class="card">
                            <div class="card-image-wrapper">
                                <img src="./images/kost4.png" alt="Kost Melati Residence" class="card-image" />
                                <div class="badge badge-putra">PUTRA</div>
                                <button class="btn-favorite" aria-label="Favorite">
                                    <img src="./images/favorite.svg" alt="" />
                                </button>
                            </div>
                            <div class="card-content">
                                <div class="card-header">
                                    <h3 class="card-title">Kost Melati Residence</h3>
                                    <div class="card-rating">
                                        <img src="./images/star.svg" alt="Star" />
                                        <span>4.9</span>
                                    </div>
                                </div>
                                <div class="card-location">
                                    <img src="./images/loc.svg" alt="Location" />
                                    <span>Malang, Jawa Timur</span>
                                </div>
                                <div class="card-price">
                                    <strong>Rp. 1.400.000</strong>
                                    <span class="price-period">/bulan</span>
                                </div>
                                <div class="card-amenities">
                                    <span class="amenity-tag">WiFi</span>
                                    <span class="amenity-tag">Kamar Mandi Dalam</span>
                                    <span class="amenity-tag">AC</span>
                                    <span class="amenity-tag">2+</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card 6 -->
                        <div class="card">
                            <div class="card-image-wrapper">
                                <div style="position: relative; width: 100%; height: 100%;">
                                    <img src="./images/kost2.png" alt="Kost Putri Green Hills" class="card-image" />
                                </div>
                                <div class="badge badge-putri">PUTRI</div>
                                <button class="btn-favorite" aria-label="Favorite">
                                    <img src="./images/favorite.svg" alt="" />
                                </button>
                            </div>
                            <div class="card-content">
                                <div class="card-header">
                                    <h3 class="card-title">Kost Putri Green Hills</h3>
                                    <div class="card-rating">
                                        <img src="./images/star.svg" alt="Star" />
                                        <span>4.9</span>
                                    </div>
                                </div>
                                <div class="card-location">
                                    <img src="./images/loc.svg" alt="Location" />
                                    <span>Malang, Jawa Timur</span>
                                </div>
                                <div class="card-price">
                                    <strong>Rp. 1.250.000</strong>
                                    <span class="price-period">/bulan</span>
                                </div>
                                <div class="card-amenities">
                                    <span class="amenity-tag">WiFi</span>
                                    <span class="amenity-tag">Kamar Mandi Dalam</span>
                                    <span class="amenity-tag">AC</span>
                                    <span class="amenity-tag">2+</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <section id="section-pagination">
                <nav class="pagination-container" aria-label="Pagination Navigation">
                    <button class="nav-button prev" aria-label="Previous page">
                        <img src="./images/arrow-left.svg" alt="Previous" />
                    </button>
                    <ul class="page-numbers">
                        <li><button class="page-number active" aria-current="page">1</button></li>
                        <li><button class="page-number">2</button></li>
                        <li><button class="page-number">3</button></li>
                    </ul>
                    <button class="nav-button next" aria-label="Next page">
                        <img src="./images/arrow-left.svg" alt="Next" />
                    </button>
                </nav>
            </section>

        </main>

    </div><!-- end .page-body -->

</body>

</html>
