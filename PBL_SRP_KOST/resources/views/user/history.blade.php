<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KostApp - History</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" />
    <link rel="stylesheet" href="css/user_history.css">
</head>

<body>

    <!-- Header Section -->
    <section id="section-header">

        <div class="header-left">
            <img src="./images/logo.svg" alt="Logo" class="logo-icon">
            <div class="greeting">
                <h1 class="greeting-title">Riwayat</h1>
                <p class="greeting-subtitle">Kost yang sudah kamu lihat akan muncul di sini.</p>
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
                        <a href="{{ route('user_home') }}" class="nav-item" aria-label="Dashboard">
                            <x-tabler-layout-dashboard-filled class="nav-blade-icon" />
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
                        <a href="{{ route('user_history') }}" class="nav-item nav-item--active" aria-label="Riwayat">
                            <x-clarity-history-line class="nav-blade-icon nav-blade-icon--active" />
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

            <!-- Filter Chips -->
            <section id="section-filters">
                <div class="filters-container">
                    <button class="filter-chip active" type="button">Semua</button>
                    <button class="filter-chip" type="button">Hari ini</button>
                    <button class="filter-chip" type="button">7 Hari Terakhir</button>
                </div>
            </section>

            <!-- Property Cards -->
            <section id="section-property-card">

                <!-- Card 1 -->
                <div class="property-card">
                    <div class="property-card__inner">
                        <!-- Left: Image & Info -->
                        <div class="property-card__main">
                            <!-- Image Group -->
                            <div class="property-card__image-wrapper">
                                <img src="./images/kost5.png"
                                    alt="Kost Taman Anggrek" class="property-card__image" />
                                <div class="property-card__badge">PUTRI</div>
                            </div>

                            <!-- Info Group -->
                            <div class="property-card__info">
                                <div class="property-card__info-top">
                                    <div class="property-card__title-group">
                                        <div class="property-card__title-row">
                                            <h2 class="property-card__title">Kost Taman Anggrek</h2>
                                            <div class="property-card__seen">
                                                <img src="./images/dot.svg" alt=""
                                                    class="property-card__dot" />
                                                <span class="property-card__seen-text">Dilihat 2 jam lalu</span>
                                            </div>
                                        </div>
                                        <div class="property-card__location">
                                            <div class="property-card__location-icon">
                                                <img src="./images/loc.svg" alt="Location" />
                                            </div>
                                            <span class="property-card__location-text">Solo, Jawa Tengah</span>
                                        </div>
                                    </div>
                                    <div class="property-card__price">
                                        Rp. 1.350.000
                                        <span class="property-card__price-period">/bulan</span>
                                    </div>
                                </div>

                                <div class="property-card__amenities">
                                    <span class="property-card__amenity">WiFi</span>
                                    <span class="property-card__amenity">Kamar Mandi Dalam</span>
                                    <span class="property-card__amenity">AC</span>
                                    <span class="property-card__amenity">2+</span>
                                </div>
                            </div>
                        </div>

                        <!-- Middle: Rating -->
                        <div class="property-card__rating">
                            <div class="property-card__rating-icon">
                                <img src="./images/star.svg" alt="Star" />
                            </div>
                            <span class="property-card__rating-text">4.9 (21 Ulasan)</span>
                        </div>

                        <!-- Right: Actions -->
                        <div class="property-card__actions">
                            <button class="property-card__btn" aria-label="Favorite">
                                <img src="./images/favorite.svg" alt="" />
                            </button>
                            <button class="property-card__btn" aria-label="View">
                                <img src="./images/view.svg" alt="" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="property-card">
                    <div class="property-card__inner">
                        <!-- Left: Image & Info -->
                        <div class="property-card__main">
                            <!-- Image Group -->
                            <div class="property-card__image-wrapper">
                                <img src="./images/kost5.png"
                                    alt="Kost Taman Anggrek" class="property-card__image" />
                                <div class="property-card__badge">PUTRI</div>
                            </div>

                            <!-- Info Group -->
                            <div class="property-card__info">
                                <div class="property-card__info-top">
                                    <div class="property-card__title-group">
                                        <div class="property-card__title-row">
                                            <h2 class="property-card__title">Kost Taman Anggrek</h2>
                                            <div class="property-card__seen">
                                                <img src="./images/dot.svg" alt=""
                                                    class="property-card__dot" />
                                                <span class="property-card__seen-text">Dilihat 2 jam lalu</span>
                                            </div>
                                        </div>
                                        <div class="property-card__location">
                                            <div class="property-card__location-icon">
                                                <img src="./images/loc.svg" alt="Location" />
                                            </div>
                                            <span class="property-card__location-text">Solo, Jawa Tengah</span>
                                        </div>
                                    </div>
                                    <div class="property-card__price">
                                        Rp. 1.350.000
                                        <span class="property-card__price-period">/bulan</span>
                                    </div>
                                </div>

                                <div class="property-card__amenities">
                                    <span class="property-card__amenity">WiFi</span>
                                    <span class="property-card__amenity">Kamar Mandi Dalam</span>
                                    <span class="property-card__amenity">AC</span>
                                    <span class="property-card__amenity">2+</span>
                                </div>
                            </div>
                        </div>

                        <!-- Middle: Rating -->
                        <div class="property-card__rating">
                            <div class="property-card__rating-icon">
                                <img src="./images/star.svg" alt="Star" />
                            </div>
                            <span class="property-card__rating-text">4.9 (21 Ulasan)</span>
                        </div>

                        <!-- Right: Actions -->
                        <div class="property-card__actions">
                            <button class="property-card__btn" aria-label="Favorite">
                                <img src="./images/favorite.svg" alt="" />
                            </button>
                            <button class="property-card__btn" aria-label="View">
                                <img src="./images/view.svg" alt="" />
                            </button>
                        </div>
                    </div>
                </div>

            </section>

        </main>

    </div><!-- end .page-body -->

</body>

</html>
