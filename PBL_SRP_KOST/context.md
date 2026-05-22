# Project Context: Sistem Rekomendasi Pencarian Kost (SBP)

## Ringkasan Proyek
Sistem Rekomendasi Pencarian Kost adalah platform web untuk membantu mahasiswa dan perantau menemukan kost yang paling sesuai dengan preferensi, anggaran, dan kebutuhan. Sistem ini dibangun dengan pendekatan Knowledge-Based System dan dirancang untuk mendukung perbandingan beberapa metode SPK.

## Tujuan Pengembangan
- Membuat sistem yang dinamis dan mudah dikembangkan.
- Menyediakan evaluasi komparatif antar metode SPK.
- Menghasilkan rekomendasi kost yang paling relevan berdasarkan perhitungan dan pembobotan kriteria.

## Kebutuhan Algoritma
### Algoritma Utama
- AHP (Analytical Hierarchy Process): menentukan bobot prioritas kriteria seperti harga, jarak, fasilitas, dan keamanan melalui perbandingan berpasangan.
- SAW (Simple Additive Weighting): menghitung nilai preferensi akhir dan menghasilkan peringkat alternatif kost.

### Algoritma Pembanding
- Siapkan opsi komparasi seperti WP (Weighted Product), TOPSIS, atau SMART untuk membandingkan hasil rekomendasi.

## Design System & UI/UX
### Tipografi
- Header font: The Season
- Body font: Poppins

### Palet Warna (Tailwind CSS)
| Nama | Hex | Penggunaan |
| --- | --- | --- |
| Off-White / Beige | #DBD3C6 | Background utama halaman, area konten, dan ruang kosong agar nuansanya terang dan hangat |
| Light Sage / Soft Grey | #CDC6BA | Background sidebar, kartu, panel informasi, dan section pendukung |
| Warm Taupe | #AD9C8A | Warna aksen utama untuk tombol aktif, CTA, elemen highlight, serta detail dekoratif |
| Slate Blue | #7C929E | Aksen sekunder untuk ikon, indikator, badge, outline, dan elemen pendukung visual |
| Dark Brown | #5D4D34 | Warna teks utama, judul, dan elemen kontras yang perlu tetap terbaca jelas |

## Tech Stack Utama
### Backend & Framework
Laravel digunakan sebagai core framework karena arsitektur MVC yang rapi, dukungan Service/Action class untuk logika algoritma, serta Eloquent ORM untuk relasi data kost, kriteria, dan bobot.

### Frontend UI
Blade Icons dipakai bersama Livewire untuk membangun antarmuka interaktif tanpa banyak JavaScript. Palet warna di atas diintegrasikan ke Tailwind agar konsisten di seluruh komponen.

### Database
MySQL digunakan untuk menyimpan data kost, kriteria cost/benefit, bobot, dan histori simulasi perhitungan rekomendasi.

## Saran Tech Stack Tambahan (Ekosistem Laravel)
### Backend
- Laravel Sanctum untuk autentikasi SPA, mobile, atau token sederhana.
- Laravel Passport jika kebutuhan OAuth2 dan integrasi API pihak ketiga lebih kompleks.
- Laravel Breeze atau Jetstream untuk fondasi auth yang cepat jika dibutuhkan.
- Laravel Octane untuk performa dan concurrency yang lebih baik.
- Redis + Laravel Horizon untuk antrean job background seperti perhitungan batch atau rekomendasi massal.
- Laravel Telescope untuk observability, debugging request, query, dan job.
- Laravel Scout untuk pencarian data kost yang lebih fleksibel.
- Spatie Laravel Data atau Form Request untuk validasi dan struktur DTO yang lebih rapi.
- Spatie Permission untuk manajemen role dan izin admin, operator, dan user.

### Icons (Mary UI)
- Gunakan icon set bawaan Mary UI agar konsisten dengan Livewire dan Tailwind.
- Jika perlu variasi, tetap tampilkan melalui komponen Mary UI supaya gaya ikon seragam di seluruh halaman.

### Charts (Mary UI)
- Gunakan komponen chart Mary UI untuk visualisasi hasil AHP, SAW, dan metode pembanding.
- Jika ada dashboard admin, tampilkan tren nilai, distribusi bobot, dan perbandingan ranking dalam chart Mary UI.

### Forms
- Gunakan form component Mary UI sebagai pilihan utama untuk form input yang cepat dan reaktif.
- Kombinasikan dengan Livewire validation untuk pengalaman pengguna yang responsif.
- Untuk backoffice yang lebih kompleks, Filament Forms bisa menjadi alternatif jika nanti dibutuhkan builder form yang lebih kaya.