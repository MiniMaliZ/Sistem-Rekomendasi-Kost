<?php

namespace Database\Seeders;

use App\Models\Kost;
use Illuminate\Database\Seeder;

class KostSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        Kost::query()->insert([
            [
                'id_kost' => 1,
                'nama_kost' => 'Kost Apik Hasna Polowijen Tipe B Blimbing Malang',
                'harga' => 400500.00,
                'tipe_kos' => 'Kos Putri',
                'created_at' => $now,
                'updated_at' => $now,
                'sepesifikasi_tipe_kamar' => '2.2 x 2.2 meter, Termasuk listrik',
                'fasilitas_kamar' => 'Kasur, Meja, Lemari / Storage, Guling, Bantal, Kipas Angin',
                'fasilitas_kamar_mandi' => 'Kloset Jongkok, K. Mandi Luar, Ember mandi',
                'fasilitas_umum' => 'WiFi, Penjaga Kos, R. Jemur, Dapur, Jemuran, K. Mandi Luar',
                'fasilitas_parkir' => 'Parkir Motor & Sepeda',
                'tempat_terdekat' => 'Warmindo Sam Ndut (2.5 km) | Alfamart (632 m) | Universitas Widya Gama (1.5 km) | RS Umum Permata Bunda (2.2 km)',
                'peraturan_kos' => 'Akses 24 Jam, Dilarang merokok di kamar, Tamu dilarang menginap',
                'link_original' => 'https://mamikos.com/room/kost-kota-malang-kost-putri-murah-kost-apik-hasna-polowijen-tipe-b-blimbing-malang-2',
            ],
            [
                'id_kost' => 2,
                'nama_kost' => 'Kost Singgahsini Amanah Tipe B Lowokwaru Malang',
                'harga' => 1000000.00,
                'tipe_kos' => 'Kos Putri',
                'created_at' => $now,
                'updated_at' => $now,
                'sepesifikasi_tipe_kamar' => '3 x 2.8 meter, Termasuk listrik',
                'fasilitas_kamar' => 'Kasur, Meja, Lemari / Storage, Ventilasi, Jendela, Bantal, Kipas Angin',
                'fasilitas_kamar_mandi' => 'K. Mandi Dalam, Kloset Duduk, Shower, Air panas',
                'fasilitas_umum' => 'WiFi, Kulkas, R. Tamu, Penjaga Kos, R. Jemur, Dapur, Jemuran, CCTV',
                'fasilitas_parkir' => 'Parkir Motor & Sepeda',
                'tempat_terdekat' => 'Warteg Jie (2.0 km) | Alfamart (816 m) | Universitas Brawijaya (2.1 km) | RS Universitas Brawijaya (1.6 km)',
                'peraturan_kos' => 'Akses 24 Jam, Dilarang merokok di kamar, Maks. 1 orang/ kamar',
                'link_original' => 'https://mamikos.com/room/kost-kota-malang-kost-putri-eksklusif-kost-singgahsini-amanah-tipe-b-lowokwaru-malang-2',
            ],
            [
                'id_kost' => 3,
                'nama_kost' => 'Kost Apik Ardhana Tanjungsekar Tipe A Lowokwaru Malang',
                'harga' => 830000.00,
                'tipe_kos' => 'Kos Putri',
                'created_at' => $now,
                'updated_at' => $now,
                'sepesifikasi_tipe_kamar' => '3 x 4.5 meter, Termasuk listrik',
                'fasilitas_kamar' => 'Kasur, Meja, Lemari / Storage, Ventilasi, Jendela, Bantal',
                'fasilitas_kamar_mandi' => 'Kloset Jongkok, K. Mandi Luar, Ember mandi',
                'fasilitas_umum' => 'WiFi, R. Cuci, Kulkas, Mesin Cuci, Penjaga Kos, R. Jemur, Dapur',
                'fasilitas_parkir' => 'Parkir Motor & Sepeda',
                'tempat_terdekat' => 'Warteg Jie (2.4 km) | Alfamart (2.6 km) | Universitas Brawijaya (3.3 km) | Mall Dinoyo City (2.6 km)',
                'peraturan_kos' => 'Akses 24 Jam, Tamu dilarang menginap, Maks. 1 orang/ kamar',
                'link_original' => 'https://mamikos.com/room/kost-kota-malang-kost-putri-murah-kost-apik-ardhana-tanjungsekar-tipe-a-lowokwaru-malang-2',
            ],
            [
                'id_kost' => 4,
                'nama_kost' => 'Kost Adenium Tipe B Lowokwaru Malang',
                'harga' => 450000.00,
                'tipe_kos' => 'Kos Putri',
                'created_at' => $now,
                'updated_at' => $now,
                'sepesifikasi_tipe_kamar' => '3 x 2.5 meter, Termasuk listrik',
                'fasilitas_kamar' => 'Kasur, Meja, Lemari / Storage, Ventilasi, Jendela, Guling, Bantal, Kursi',
                'fasilitas_kamar_mandi' => 'Kloset Duduk, K. Mandi Luar, Ember mandi',
                'fasilitas_umum' => 'WiFi, R. Cuci, Kulkas, R. Tamu, Penjaga Kos, R. Jemur, R. Makan, Mushola, Dapur',
                'fasilitas_parkir' => 'Parkir Motor, Parkir Sepeda',
                'tempat_terdekat' => 'Bian Cafe (1.5 km) | Alfamart (581 m) | Universitas Islam Malang (2.5 km) | RS Umum Universitas Muhammadiyah Malang (959 m)',
                'peraturan_kos' => 'Ada jam malam, Akses 24 Jam, Dilarang merokok di kamar',
                'link_original' => 'https://mamikos.com/room/kost-kota-malang-kost-putri-murah-kost-adenium-tipe-b-lowokwaru-malang-2',
            ],
            [
                'id_kost' => 5,
                'nama_kost' => 'Kost Singgalang Tipe A Sukun Malang',
                'harga' => 1400000.00,
                'tipe_kos' => 'Kos Putri',
                'created_at' => $now,
                'updated_at' => $now,
                'sepesifikasi_tipe_kamar' => '4 x 5 meter, Termasuk listrik',
                'fasilitas_kamar' => 'AC, Kasur, Meja, Lemari / Storage, Cermin, Bantal, Kursi',
                'fasilitas_kamar_mandi' => 'K. Mandi Dalam, Kloset Duduk, Ember mandi, Shower',
                'fasilitas_umum' => 'WiFi, Kulkas, R. Jemur, Dapur, Jemuran, Pengurus Kos, CCTV',
                'fasilitas_parkir' => 'Parkir Motor',
                'tempat_terdekat' => 'Warteg Sari Rasa 24 Jam Cab. Galunggung (415 m) | Universitas Merdeka Malang (410 m) | Malang Town Square (1.6 km)',
                'peraturan_kos' => 'Ada jam malam untuk tamu, Dilarang merokok di kamar, Maks. 2 orang/ kamar',
                'link_original' => 'https://mamikos.com/room/kost-kota-malang-kost-putri-eksklusif-kost-singgalang-tipe-a-sukun-malang-2',
            ],
        ]);
    }
}
