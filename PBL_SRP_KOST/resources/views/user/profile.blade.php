@php
    $firstName = strtok($user->nama, ' ') ?: $user->nama;
    $roleLabel = $user->role === 'admin' ? 'Admin' : 'Pencari Kost';
    $avatarUrl = $user->avatar_url;
    if (session('avatar_refresh') && $user->foto_url && ! str_starts_with($avatarUrl, 'http')) {
        $avatarUrl .= (str_contains($avatarUrl, '?') ? '&' : '?') . 't=' . session('avatar_refresh');
    }
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KostApp - Profil Saya</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" />
    <link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
</head>

<body>

    <section id="section-header">
        <div class="header-left">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="logo-icon">
            <div class="greeting">
                <h1 class="greeting-title">Halo, {{ $firstName }}!</h1>
                <p class="greeting-subtitle">Yuk cari kost yang sesuai dengan dirimu sekarang.</p>
            </div>
        </div>

        <div class="header-right">
            <div class="search-bar">
                <input type="text" placeholder="Cari nama kost, lokasi..." class="search-input">
                <button type="button" class="search-button" aria-label="Cari">
                    <img src="{{ asset('images/search.svg') }}" alt="" class="search-icon">
                </button>
            </div>
            <button type="button" class="notif-button" aria-label="Notifikasi">
                <img src="{{ asset('images/notif.svg') }}" alt="" class="notif-icon">
            </button>
            <a href="{{ route('profile') }}" class="user-avatar" aria-label="Profil saya">
                <img src="{{ $avatarUrl }}" alt="{{ $user->nama }}">
            </a>
        </div>
    </section>

    <div class="page-body">

        <section id="section-sidebar">
            <nav class="nav-pill">
                <ul class="nav-list">
                    <li>
                        <a href="{{ route('user.home') }}" class="nav-item nav-item--active" aria-label="Dashboard">
                            <x-tabler-layout-dashboard-filled class="nav-blade-icon nav-blade-icon--active" />
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.kost') }}" class="nav-item" aria-label="Daftar Kost">
                            <x-iconsax-lin-buliding class="nav-blade-icon" />
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.favorit') }}" class="nav-item" aria-label="Favorit">
                            <x-solar-heart-linear class="nav-blade-icon" />
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.riwayat') }}" class="nav-item" aria-label="Riwayat">
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

        <main class="main-content">
            <div class="profile-page">

                <header class="profile-page-header">
                    <h2 class="profile-page-title">Profil Saya</h2>
                    <p class="profile-page-subtitle">Kelola informasi profil dan pengaturan akunmu.</p>
                </header>

                @if (session('success'))
                    <div class="alert-banner alert-banner--success" role="status">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert-banner alert-banner--error" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Kartu ringkasan profil --}}
                <article class="profile-summary-card">
                    <button type="button" class="btn-edit-profile" id="open-edit-modal">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Edit Profil
                    </button>

                    <div class="profile-summary-inner">
                        <div class="profile-avatar-col">
                            <div class="profile-deco" aria-hidden="true">
                                <span class="profile-deco-blob profile-deco-blob--1"></span>
                                <span class="profile-deco-blob profile-deco-blob--2"></span>
                                <span class="profile-deco-blob profile-deco-blob--3"></span>
                            </div>
                            <div class="profile-avatar-frame">
                                <img src="{{ $avatarUrl }}" alt="{{ $user->nama }}" class="profile-avatar-img" id="profile-avatar-preview">
                                <form action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data" id="profile-photo-form">
                                    @csrf
                                    <input type="file" name="profile_photo" id="profile-photo-input" class="profile-photo-input"
                                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    <label for="profile-photo-input" class="profile-camera-btn" aria-label="Ubah foto profil">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                            <circle cx="12" cy="13" r="4"/>
                                        </svg>
                                    </label>
                                </form>
                            </div>
                        </div>

                        <div class="profile-summary-content">
                            <h3 class="profile-display-name">{{ $user->nama }}</h3>
                            <span class="profile-role-pill">{{ $roleLabel }}</span>
                            <p class="profile-tagline">Mencari kost nyaman dan strategis untuk mendukung aktivitas sehari-hari.</p>

                            <ul class="profile-meta-list">
                                <li class="profile-meta-item">
                                    <span class="profile-meta-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                            <polyline points="22,6 12,13 2,6"/>
                                        </svg>
                                    </span>
                                    {{ $user->email }}
                                </li>
                                <li class="profile-meta-item">
                                    <span class="profile-meta-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </span>
                                    {{ $roleLabel }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </article>

                {{-- Kartu informasi akun --}}
                <article class="account-info-card">
                    <h3 class="account-info-heading">
                        <span class="account-info-heading-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="5" width="20" height="14" rx="2"/>
                                <line x1="2" y1="10" x2="22" y2="10"/>
                            </svg>
                        </span>
                        Informasi Akun
                    </h3>

                    <div class="account-info-body">
                        <ul class="account-info-rows">
                            <li class="account-info-row">
                                <span class="account-info-row-left">
                                    <span class="account-row-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </span>
                                    <span class="account-row-label">Nama Lengkap</span>
                                </span>
                                <span class="account-row-value">{{ $user->nama }}</span>
                            </li>
                            <li class="account-info-row">
                                <span class="account-info-row-left">
                                    <span class="account-row-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="2" y="7" width="20" height="14" rx="2"/>
                                            <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                                        </svg>
                                    </span>
                                    <span class="account-row-label">Role</span>
                                </span>
                                <span class="account-row-value account-row-value--role">
                                    <svg class="account-row-value-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    {{ $roleLabel }}
                                </span>
                            </li>
                            <li class="account-info-row">
                                <span class="account-info-row-left">
                                    <span class="account-row-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                            <polyline points="22,6 12,13 2,6"/>
                                        </svg>
                                    </span>
                                    <span class="account-row-label">Email</span>
                                </span>
                                <span class="account-row-value">{{ $user->email }}</span>
                            </li>
                            <li class="account-info-row">
                                <span class="account-info-row-left">
                                    <span class="account-row-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                                            <circle cx="8.5" cy="8.5" r="1.5"/>
                                            <polyline points="21 15 16 10 5 21"/>
                                        </svg>
                                    </span>
                                    <span class="account-row-label">Foto Profil</span>
                                </span>
                                <span class="account-row-value">
                                    @if ($user->foto_url)
                                        <img src="{{ $avatarUrl }}" alt="Foto profil" class="account-foto-preview">
                                    @else
                                        <span class="text-muted">Belum diunggah</span>
                                    @endif
                                </span>
                            </li>
                        </ul>

                        <div class="account-info-illustration" aria-hidden="true">
                            <img src="{{ asset('images/illus.svg') }}" alt="">
                        </div>
                    </div>
                </article>

            </div>
        </main>
    </div>

    {{-- Modal edit --}}
    <div class="profile-modal" id="edit-profile-modal" aria-hidden="true">
        <div class="profile-modal-backdrop" id="close-edit-modal-backdrop"></div>
        <div class="profile-modal-dialog" role="dialog" aria-labelledby="edit-modal-title" aria-modal="true">
            <div class="profile-modal-header">
                <h3 id="edit-modal-title">Edit Profil</h3>
                <button type="button" class="profile-modal-close" id="close-edit-modal" aria-label="Tutup">&times;</button>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="profile-modal-form">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="edit-nama">Nama Lengkap</label>
                    <input type="text" id="edit-nama" name="nama" value="{{ old('nama', $user->nama) }}" required maxlength="100">
                </div>

                <div class="form-group">
                    <label for="edit-email">Email</label>
                    <input type="email" id="edit-email" name="email" value="{{ old('email', $user->email) }}" required maxlength="150">
                </div>

                <p class="form-hint">Ubah foto lewat ikon kamera pada foto profil. Role ditetapkan oleh sistem.</p>

                <div class="profile-modal-actions">
                    <button type="button" class="btn-cancel" id="cancel-edit-modal">Batal</button>
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            var input = document.getElementById('profile-photo-input');
            var form = document.getElementById('profile-photo-form');
            var preview = document.getElementById('profile-avatar-preview');

            if (input && form) {
                input.addEventListener('change', function () {
                    if (!input.files || !input.files[0]) return;
                    if (preview) preview.src = URL.createObjectURL(input.files[0]);
                    form.submit();
                });
            }

            var modal = document.getElementById('edit-profile-modal');
            var openBtn = document.getElementById('open-edit-modal');
            var closeBtn = document.getElementById('close-edit-modal');
            var cancelBtn = document.getElementById('cancel-edit-modal');
            var backdrop = document.getElementById('close-edit-modal-backdrop');

            function openModal() {
                if (!modal) return;
                modal.classList.add('profile-modal--open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                if (!modal) return;
                modal.classList.remove('profile-modal--open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            if (openBtn) openBtn.addEventListener('click', openModal);
            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
            if (backdrop) backdrop.addEventListener('click', closeModal);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeModal();
            });

            @if ($errors->any())
                openModal();
            @endif
        })();
    </script>
</body>

</html>