<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Temukan Kost Ideal Anda</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/preferensi_1.css') }}">
</head>

<body>

    <section id="section-hero">
        <div class="hero-container">
            <h1 class="hero-title">Temukan Kost Ideal Anda</h1>
            <p class="hero-subtitle">
                Isi preferensi di bawah ini untuk mendapatkan rekomendasi kost terbaik
                sesuai kebutuhan.
            </p>
        </div>
    </section>

    <section id="section-stepper">
        <div class="stepper-scroll-container">
            <div class="stepper-wrapper">
                <!-- Background Lines -->
                <div class="stepper-lines">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>

                <!-- Stepper Items -->
                <div class="stepper-steps">
                    <!-- Step 1 -->
                    <div class="step step-1">
                        <div class="step-circle active">1</div>
                        <div class="step-label active-label">Identitas</div>
                    </div>

                    <!-- Step 2 -->
                    <div class="step step-2">
                        <div class="step-circle inactive-2">2</div>
                        <div class="step-label inactive-label">Anggaran &amp; Tipe</div>
                    </div>

                    <!-- Step 3 -->
                    <div class="step step-3">
                        <div class="step-circle inactive-3">3</div>
                        <div class="step-label inactive-label">Fasilitas</div>
                    </div>

                    <!-- Step 4 -->
                    <div class="step step-4">
                        <div class="step-circle inactive-4">4</div>
                        <div class="step-label inactive-label">Prioritas</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="section-form-card">
        <div class="card-container">
            <div class="card-content">
                <!-- Header -->
                <header class="header">
                    <div class="header-icon-bg">
                        <x-iconsax-lin-profile class="icon-svg" />
                    </div>
                    <div class="header-text">
                        <h2 class="title">Identitas Pencari</h2>
                        <p class="subtitle">Informasi dasar untuk rekomendasi yang tepat.</p>
                    </div>
                </header>

                <!-- Form Content -->
                <div class="form-container">
                    <!-- Field 1: Nama Lengkap -->
                    <div class="form-field">
                        <label class="label">Nama Lengkap</label>
                        <div class="input-box">
                            <input type="text" placeholder="Masukkan nama Anda" class="input-element" />
                        </div>
                    </div>

                    <!-- Field 2: Status -->
                    <div class="status-field">
                        <label class="label">Status</label>
                        <div class="status-options">
                            <!-- Option 1: Mahasiswa -->
                            <div class="status-option" role="button" tabindex="0" data-status="mahasiswa">
                                <div class="status-option-content">
                                    <span class="status-icon icon-outline">
                                        <x-fluentui-hat-graduation-12-o class="icon-svg" />
                                    </span>
                                    <span class="status-icon icon-filled" style="display:none;">
                                        <x-fluentui-hat-graduation-12 class="icon-svg" />
                                    </span>
                                    <span class="status-text">Mahasiswa</span>
                                </div>
                            </div>

                            <!-- Option 2: Karyawan -->
                            <div class="status-option" role="button" tabindex="0" data-status="karyawan">
                                <div class="status-option-content">
                                    <span class="status-icon icon-outline">
                                        <x-heroicon-o-briefcase class="icon-svg" />
                                    </span>
                                    <span class="status-icon icon-filled" style="display:none;">
                                        <x-heroicon-s-briefcase class="icon-svg" />
                                    </span>
                                    <span class="status-text">Karyawan</span>
                                </div>
                            </div>

                            <!-- Option 3: Umum -->
                            <div class="status-option" role="button" tabindex="0" data-status="umum">
                                <div class="status-option-content">
                                    <span class="status-icon icon-outline">
                                        <x-iconsax-lin-profile-2user class="icon-svg" />
                                    </span>
                                    <span class="status-icon icon-filled" style="display:none;">
                                        <x-iconsax-bol-profile-2user class="icon-svg" />
                                    </span>
                                    <span class="status-text">Umum</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Field 3: Lokasi -->
                    <div class="form-field location-field">
                        <div class="field-header">
                            <label class="label">Lokasi kampus/kerja</label>
                            <p class="subtitle">
                                Masukkan lokasi kampus atau tempat kerja Anda.
                            </p>
                        </div>
                        <div class="input-box-with-icon">
                            <img src="{{ asset('images/loc.svg') }}" alt="Location" class="icon-svg-small" />
                            <input type="text" placeholder="Contoh: Polinema, Jl. Soekarno Hatta No.9, Malang"
                                class="input-element" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="section-action">
        <div class="action-container">
            <button class="btn-primary">
                <span class="btn-text">Lanjut</span>
                <img src="{{ asset('images/longarrow-right-white.svg') }}" alt="Long Arrow Right" class="btn-icon" />
            </button>
        </div>
    </section>

    <script>
        document.querySelectorAll('.status-option').forEach(function(option) {
            option.addEventListener('click', function() {
                // Remove selected from all
                document.querySelectorAll('.status-option').forEach(function(el) {
                    el.classList.remove('selected');
                    el.querySelector('.status-text').classList.remove('selected-text');
                    el.querySelector('.icon-outline').style.display = '';
                    el.querySelector('.icon-filled').style.display = 'none';
                });

                // Add selected to clicked
                this.classList.add('selected');
                this.querySelector('.status-text').classList.add('selected-text');
                this.querySelector('.icon-outline').style.display = 'none';
                this.querySelector('.icon-filled').style.display = '';
            });

            // Keyboard accessibility
            option.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });
    </script>

</body>

</html>
