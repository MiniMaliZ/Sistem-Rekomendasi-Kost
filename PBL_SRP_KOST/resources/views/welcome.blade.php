<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>roomor - Sistem Rekomendasi kos</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Landing CSS -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar" id="navbar">
        <div class="navbar-brand">
            <a href="#beranda" class="navbar-logo-link">
                <img src="{{ asset('images/logo01.png') }}" alt="roomor" class="navbar-logo-icon">
            </a>
        </div>

        <div class="navbar-center">
            <ul class="navbar-nav">
                <li><a href="#mengapa" class="active">Mengapa Kami</a></li>
                <li><a href="#cara-kerja">Cara Kerja</a></li>
                <li><a href="#tipe-kos">Jenis Kos</a></li>
                <li><a href="#testimoni">Testimoni</a></li>
            </ul>
        </div>

        @if (Route::has('login'))
            @auth
                <a href="{{ url('/home') }}" class="btn-admin">Dashboard</a>
            @endauth
        @endif
    </nav>

    <!-- ===== HERO SECTION ===== -->
    <section class="hero" id="beranda">
        <!-- Background image hero -->
        <div class="hero-bg">
            <img src="{{ asset('images/gambarlandingpage01.png') }}" alt="Kamar kos Nyaman">
        </div>
        <div class="hero-overlay"></div>

        <div class="hero-container">
            <div class="hero-content">
                <p class="hero-subtitle">
                    Nggak perlu pusing cari kos. roomor punya ribuan pilihan kos di seluruh Indonesia — dari putra, putri, sampai campur. Tinggal pilih, langsung cocok.
                </p>
                <div class="hero-actions">
                    <a href="#cara-kerja" class="btn-hero-primary">
                        Cari kos with roomor
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Hero bottom overlay brand wordmark -->
        <div class="hero-wordmark">
            <img src="{{ asset('images/textroomor.png') }}" alt="roomor" class="hero-wordmark-img">
        </div>
    </section>

    <!-- ===== ABOUT / INTRO ===== -->
    <section class="intro-section" id="tentang-singkat">

        <!-- Top bar: kosong (logo dihapus) -->
        <div class="intro-top-bar">
        </div>

        <!-- Left: editorial tagline -->
        <div class="intro-tagline">
            <span>SETIAP</span>
            <span>RUANG</span>
            <span>MEMILIKI</span>
            <span>CERITA</span>
        </div>

        <!-- Center: main portrait image -->
        <div class="intro-main-img-wrap">
            <img src="{{ asset('images/gambarlandingpage02.png') }}" alt="Ruang kos Estetik" class="intro-main-img">
        </div>

        <!-- Right column: desc + CTA on top, accent image on bottom -->
        <div class="intro-right-col">
            <div class="intro-top-right">
                <p class="intro-desc">
                    Nggak cuma soal tempat tinggal. Di Roomor, kamu bisa nemuin ruang yang
                    cocok sama vibe dan gaya hidupmu. Mau yang cozy, <em>aesthetic</em>,
                    atau yang simpel buat recharge — semuanya ada di sini.
                </p>
                <a href="#cara-kerja" class="btn-intro-cta">Cek kos Sekarang</a>
            </div>

            <div class="intro-accent-wrap">
                <img src="{{ asset('images/gambarlandingpage03.png') }}" alt="Detail kos" class="intro-accent-img">
            </div>
        </div>

    </section>

    <!-- ===== MENGAPA ROOMOR ===== -->
    <section class="section kenapa-section" id="mengapa">
        <div class="container">
            <div class="kenapa-header">
                <p class="section-label">Mengapa kami?</p>
                <h2 class="kenapa-title">
                    Kenapa Pilih<br>
                    <img src="{{ asset('images/roomor.png') }}" alt="roomor" class="heading-wordmark"> ?
                </h2>
            </div>

            <div class="kenapa-accordion">
                <!-- Item 1 -->
                <div class="accordion-item active" onclick="toggleAccordion(this)">
                    <div class="accordion-header">
                        <span class="accordion-num">01</span>
                        <span class="accordion-title">PENCARIAN CERDAS</span>
                        <span class="accordion-icon"><i class="fas fa-minus"></i></span>
                    </div>
                    <div class="accordion-body">
                        <p>Filter berdasarkan kata, harga, jenis kos, fasilitas, dan radius lokasi. Temukan kos yang benar-benar sesuai kebutuhanmu, bukan sekadar yang ada.</p>
                    </div>
                </div>
                <!-- Item 2 -->
                <div class="accordion-item" onclick="toggleAccordion(this)">
                    <div class="accordion-header">
                        <span class="accordion-num">02</span>
                        <span class="accordion-title">HARGA TRANSPARAN</span>
                        <span class="accordion-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="accordion-body">
                        <p>Semua harga ditampilkan secara terbuka. Tidak ada biaya tersembunyi, tidak ada kejutan saat bayar.</p>
                    </div>
                </div>
                <!-- Item 3 -->
                <div class="accordion-item" onclick="toggleAccordion(this)">
                    <div class="accordion-header">
                        <span class="accordion-num">03</span>
                        <span class="accordion-title">KOS TERVERIFIKASI</span>
                        <span class="accordion-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="accordion-body">
                        <p>Setiap kos telah melalui proses verifikasi tim kami. Foto asli, informasi akurat, dan pemilik yang terpercaya.</p>
                    </div>
                </div>
                <!-- Item 4 -->
                <div class="accordion-item" onclick="toggleAccordion(this)">
                    <div class="accordion-header">
                        <span class="accordion-num">04</span>
                        <span class="accordion-title">REVIEW TRANSPARAN</span>
                        <span class="accordion-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="accordion-body">
                        <p>Baca ulasan nyata dari penghuni sebelumnya. Kami tidak menyaring ulasan negatif agar kamu bisa memutuskan dengan informasi yang lengkap.</p>
                    </div>
                </div>
                <!-- Item 5 -->
                <div class="accordion-item" onclick="toggleAccordion(this)">
                    <div class="accordion-header">
                        <span class="accordion-num">05</span>
                        <span class="accordion-title">SIMPAN FAVORIT</span>
                        <span class="accordion-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="accordion-body">
                        <p>Tandai kos favoritmu dan bandingkan nanti. Fitur simpan kami memudahkan kamu mengambil keputusan tanpa terburu-buru.</p>
                    </div>
                </div>
                <!-- Item 6 -->
                <div class="accordion-item" onclick="toggleAccordion(this)">
                    <div class="accordion-header">
                        <span class="accordion-num">06</span>
                        <span class="accordion-title">PILIHAN TERLENGKAP</span>
                        <span class="accordion-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="accordion-body">
                        <p>Ribuan pilihan kos dari berbagai kota di Indonesia. Dari kos murah mahasiswa hingga kos premium eksekutif.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CARA KERJA ===== -->
    <section class="section cara-section" id="cara-kerja">
        <div class="container">
            <div class="cara-header">
                <p class="section-label">Cara kerja</p>
                <h2 class="cara-title">
                    4 Langkah Mudah<br>
                    dengan <img src="{{ asset('images/roomor.png') }}" alt="roomor" class="heading-wordmark">
                </h2>
            </div>

            <div class="cara-grid">
                <div class="cara-card">
                    <div class="cara-num">1</div>
                    <h3 class="cara-title-item">Buat Akun Gratis</h3>
                    <p class="cara-desc">
                        Daftar dengan email atau langsung masuk tanpa daftar akun. Proses registrasi hanya 30 detik dan sepenuhnya gratis tanpa biaya tersembunyi.
                    </p>
                </div>
                <div class="cara-card">
                    <div class="cara-num">2</div>
                    <h3 class="cara-title-item">Cari & Filter kos</h3>
                    <p class="cara-desc">
                        Filter berdasarkan kota, harga, jenis, dan fasilitas untuk menemukan kos yang benar-benar sesuai kebutuhanmu.
                    </p>
                </div>
                <div class="cara-card">
                    <div class="cara-num">4</div>
                    <h3 class="cara-title-item">Hubungi & Pindah Masuk!</h3>
                    <p class="cara-desc">
                        Hubungi pemilik kos langsung melalui platform, atur jadwal survei, dan mulai nikmati kehidupan di kos impianmu. Semudah itu!
                    </p>
                </div>
                <div class="cara-card">
                    <div class="cara-num">3</div>
                    <h3 class="cara-title-item">Cek Detail & Bandingkan</h3>
                    <p class="cara-desc">
                        Lihat foto, baca ulasan penghuni sebelumnya, cek fasilitas lengkap, dan bandingkan beberapa pilihan kos sebelum membuat keputusan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== TIPE kos ===== -->
    <section class="section tipe-section" id="tipe-kos">
        <div class="container">
            <div class="tipe-header">
                <p class="section-label tipe-label-top">JENIS KOS</p>
                <h2 class="tipe-title">
                    Semua Tipe kos,<br>
                    Semua Kebutuhan
                </h2>
            </div>

            <div class="tipe-grid">
                <!-- Big left image -->
                <div class="tipe-big-wrap">
                    <div class="tipe-big">
                        <img src="{{ asset('images/gambarlandingpage04.png') }}" alt="kos Campur" class="tipe-img">
                    </div>
                    <div class="tipe-caption">
                        <em>kos Campur</em>
                        <p>Hunian fleksibel untuk semua kalangan. Fasilitas bersama yang lengkap dengan harga lebih terjangkau dan komunitas yang beragam.</p>
                    </div>
                </div>

                <!-- kos Putri -->
                <div class="tipe-small-wrap">
                    <div class="tipe-small">
                        <img src="{{ asset('images/gambarlandingpage05.png') }}" alt="kos Putri" class="tipe-img">
                    </div>
                    <div class="tipe-caption">
                        <em>kos Putri</em>
                        <p>Hunian eksklusif wanita dengan keamanan ekstra, privasi terjaga, dan suasana yang nyaman.</p>
                    </div>
                </div>

                <!-- kos Putra -->
                <div class="tipe-small-wrap">
                    <div class="tipe-small">
                        <img src="{{ asset('images/gambarlandingpage06.png') }}" alt="kos Putra" class="tipe-img">
                    </div>
                    <div class="tipe-caption">
                        <em>kos Putra</em>
                        <p>Hunian khusus pria dengan lingkungan kondusif dan aman. Cocok untuk mahasiswa.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== TESTIMONI ===== -->
    <section class="section testimoni-section" id="testimoni">
        <div class="container">
            <div class="testimoni-header">
                <p class="section-label">Testimoni</p>
                <h2 class="testimoni-title">
                    APA KATA<br>
                    MEREKA?
                </h2>
            </div>

            <div class="testimoni-grid">
                <!-- Testimoni 1 -->
                <div class="testimoni-card">
                    <div class="quote-mark">❝</div>
                    <p class="testimoni-text">
                        Cari kos di Malang jadi sangat mudah pakai roomor. Dalam 30 menit langsung ketemu kos yang cocok, harga pas, fasilitas lengkap!
                    </p>
                    <div class="stars">★★★★★</div>
                    <div class="testimoni-author">
                        <div class="author-avatar">
                            <img src="{{ asset('images/fototestimoni.png') }}" alt="Kevin Bramasta">
                        </div>
                        <div>
                            <div class="author-name">Kevin Bramasta</div>
                            <div class="author-role">Mahasiswa Politeknik Negeri Malang</div>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 2 -->
                <div class="testimoni-card">
                    <div class="quote-mark">❝</div>
                    <p class="testimoni-text">
                        Filter fasilitasnya detail banget. Langsung bisa pilih yang ada AC, WiFi, dan kamar mandi dalam. Nggak perlu repot tanya satu-satu!
                    </p>
                    <div class="stars">★★★★★</div>
                    <div class="testimoni-author">
                        <div class="author-avatar">
                            <img src="{{ asset('images/fototestimoni.png') }}" alt="Nova Diana">
                        </div>
                        <div>
                            <div class="author-name">Nova Diana</div>
                            <div class="author-role">Mahasiswi Politeknik Negeri Malang</div>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 3 -->
                <div class="testimoni-card">
                    <div class="quote-mark">❝</div>
                    <p class="testimoni-text">
                        Cari kos di Malang jadi sangat mudah pakai roomor. Dalam 30 menit langsung ketemu kos yang cocok, harga pas, fasilitas lengkap!
                    </p>
                    <div class="stars">★★★★★</div>
                    <div class="testimoni-author">
                        <div class="author-avatar">
                            <img src="{{ asset('images/fototestimoni.png') }}" alt="Kevin Bramasta">
                        </div>
                        <div>
                            <div class="author-name">Kevin Bramasta</div>
                            <div class="author-role">Mahasiswa Politeknik Negeri Malang</div>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 4 -->
                <div class="testimoni-card">
                    <div class="quote-mark">❝</div>
                    <p class="testimoni-text">
                        Filter fasilitasnya detail banget. Langsung bisa pilih yang ada AC, WiFi, dan kamar mandi dalam. Nggak perlu repot tanya satu-satu!
                    </p>
                    <div class="stars">★★★★★</div>
                    <div class="testimoni-author">
                        <div class="author-avatar">
                            <img src="{{ asset('images/fototestimoni.png') }}" alt="Nova Diana">
                        </div>
                        <div>
                            <div class="author-name">Nova Diana</div>
                            <div class="author-role">Mahasiswi Politeknik Negeri Malang</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="footer" id="tentang">
        <div class="footer-main">
            <div class="footer-brand">
                <img src="{{ asset('images/logo02.png') }}" alt="roomor Logo" class="footer-logo-img">
                <p class="footer-tagline">
                    Platform rekomendasi kos terpercaya di Indonesia. Menghubungkan pencari kos dengan pemilik kos di seluruh nusantara.
                </p>
            </div>

            <div class="footer-links">
                <div class="footer-col">
                    <h4 class="footer-col-title">Platform</h4>
                    <ul>
                        <li><a href="#">Cari kos</a></li>
                        <li><a href="#">Daftarkan kos</a></li>
                        <li><a href="#">Premium</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-col-title">Perusahaan</h4>
                    <ul>
                        <li><a href="#">Tentang kami</a></li>
                        <li><a href="#">Karir</a></li>
                        <li><a href="#">Pres</a></li>
                        <li><a href="#">Kontak</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-col-title">Bantuan</h4>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Pusat Bantuan</a></li>
                        <li><a href="#">Privasi</a></li>
                        <li><a href="#">Syarat Layanan</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="footer-copy">&copy; 2026 roomor. All Rights Reserved.</p>
            <p class="footer-sub">Platform Rekomendasi kos</p>
        </div>
    </footer>

    <script>
        // Accordion toggle
        function toggleAccordion(el) {
            const allItems = document.querySelectorAll('.accordion-item');
            allItems.forEach(item => {
                if (item !== el) {
                    item.classList.remove('active');
                    item.querySelector('.accordion-icon i').className = 'fas fa-plus';
                }
            });
            el.classList.toggle('active');
            const icon = el.querySelector('.accordion-icon i');
            icon.className = el.classList.contains('active') ? 'fas fa-minus' : 'fas fa-plus';
        }

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 80) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Active nav link follows click
        const navLinks = document.querySelectorAll('.navbar-nav a');
        navLinks.forEach(link => {
            link.addEventListener('click', function () {
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Active nav link follows scroll (scroll spy)
        const sections = [
            { id: 'mengapa', link: document.querySelector('.navbar-nav a[href="#mengapa"]') },
            { id: 'cara-kerja', link: document.querySelector('.navbar-nav a[href="#cara-kerja"]') },
            { id: 'tipe-kos', link: document.querySelector('.navbar-nav a[href="#tipe-kos"]') },
            { id: 'testimoni', link: document.querySelector('.navbar-nav a[href="#testimoni"]') },
        ];

        window.addEventListener('scroll', () => {
            let current = null;
            sections.forEach(({ id }) => {
                const el = document.getElementById(id);
                if (el && window.scrollY >= el.offsetTop - 120) {
                    current = id;
                }
            });
            sections.forEach(({ id, link }) => {
                if (link) link.classList.toggle('active', id === current);
            });
        });
    </script>
</body>
</html>