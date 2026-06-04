<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KostApp - Favorit</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" />
    <link rel="stylesheet" href="css/user/favorite.css">
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
                <h1 class="greeting-title">Favorit</h1>
                <p class="greeting-subtitle">Kost yang kamu simpan akan muncul di sini.</p>
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
        @include('user.partials.sidebar')

        <!-- Main Content: Filter Bar + Listings + Pagination -->
        <main class="main-content">

            <!-- Filter / Tab Bar -->
            <section id="section-filter-bar">
                <div class="filter-container">
                    <!-- Left side: Tabs -->
                    <div class="filter-tabs">
                        <div class="tab-active">
                            <span class="tab-text-active">Semua Favorit</span>
                            <div class="tab-line"></div>
                        </div>
                        <div class="tab-inactive">
                            <span class="tab-text-inactive">Dikelompokkan</span>
                        </div>
                    </div>

                    <!-- Right side: Sort Actions -->
                    <div class="filter-actions">
                        <span class="sort-label">Urutkan:</span>
                        <button class="sort-dropdown" aria-label="Sort options">
                            <span class="dropdown-text">Terbaru</span>
                            <div class="dropdown-icon-wrapper">
                                <img src="./images/arrow-down.svg" alt="Dropdown Arrow" class="dropdown-icon" />
                            </div>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Listings Grid -->
            <section id="section-listings">
                <div class="container">
                    <div class="listings-grid">

                        <!-- Card 1 -->
                        <div class="card">
                            <div class="card-image-wrapper">
                                <img src="./images/kost1.png" alt="Kost Putri Casa De Flora" class="card-image" />
                                <div class="badge badge-putri">PUTRI</div>
                                <button class="btn-favorite" aria-label="Favorite">
                                    <img src="./images/fav-bold.svg" alt="" />
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
                                    <img src="./images/fav-bold.svg" alt="" />
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
                                    <img src="./images/fav-bold.svg" alt="" />
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

            <!-- Pagination -->
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
    </div>

</body>

</html>
