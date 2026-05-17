<?php

namespace Database\Seeders;

use App\Models\Kost;
use Illuminate\Database\Seeder;

class KostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_kost' => 'Kost Melati 1',
                'owner' => 'Budi Santoso',
                'alamat' => 'Jl. Merdeka No. 10, Bandung',
                'harga' => 750000.00,
                'ukuran_kamar' => '3x3',
                'tipe_kos' => 'putri',
                'fasilitas' => 'Kasur, Lemari, Meja, WiFi, Kamar mandi dalam',
                'foto_url' => 'https://example.com/kost/melati1.jpg',
            ],
            [
                'nama_kost' => 'Kost Anggrek 2',
                'owner' => 'Siti Rahma',
                'alamat' => 'Jl. Dago No. 25, Bandung',
                'harga' => 900000.00,
                'ukuran_kamar' => '3x4',
                'tipe_kos' => 'putri',
                'fasilitas' => 'Kasur, Lemari, AC, WiFi, Kamar mandi dalam',
                'foto_url' => 'https://example.com/kost/anggrek2.jpg',
            ],
            [
                'nama_kost' => 'Kost Mawar 3',
                'owner' => 'Rudi Hartono',
                'alamat' => 'Jl. Setiabudi No. 5, Bandung',
                'harga' => 650000.00,
                'ukuran_kamar' => '3x3',
                'tipe_kos' => 'putra',
                'fasilitas' => 'Kasur, Lemari, WiFi, Parkir motor',
                'foto_url' => 'https://example.com/kost/mawar3.jpg',
            ],
            [
                'nama_kost' => 'Kost Dahlia 4',
                'owner' => 'Lina Wati',
                'alamat' => 'Jl. Pasteur No. 18, Bandung',
                'harga' => 1200000.00,
                'ukuran_kamar' => '4x4',
                'tipe_kos' => 'campur',
                'fasilitas' => 'Kasur, Lemari, AC, WiFi, Dapur bersama',
                'foto_url' => 'https://example.com/kost/dahlia4.jpg',
            ],
            [
                'nama_kost' => 'Kost Kenanga 5',
                'owner' => 'Agus Priyanto',
                'alamat' => 'Jl. Braga No. 40, Bandung',
                'harga' => 800000.00,
                'ukuran_kamar' => '3x3',
                'tipe_kos' => 'putra',
                'fasilitas' => 'Kasur, Lemari, WiFi, Kamar mandi luar',
                'foto_url' => 'https://example.com/kost/kenanga5.jpg',
            ],
            [
                'nama_kost' => 'Kost Flamboyan 6',
                'owner' => 'Dewi Lestari',
                'alamat' => 'Jl. Riau No. 12, Bandung',
                'harga' => 1100000.00,
                'ukuran_kamar' => '3x4',
                'tipe_kos' => 'putri',
                'fasilitas' => 'Kasur, Lemari, AC, WiFi, Laundry',
                'foto_url' => 'https://example.com/kost/flamboyan6.jpg',
            ],
            [
                'nama_kost' => 'Kost Tulip 7',
                'owner' => 'Hendra Gunawan',
                'alamat' => 'Jl. Cihampelas No. 8, Bandung',
                'harga' => 700000.00,
                'ukuran_kamar' => '3x3',
                'tipe_kos' => 'campur',
                'fasilitas' => 'Kasur, Lemari, WiFi, Parkir motor',
                'foto_url' => 'https://example.com/kost/tulip7.jpg',
            ],
            [
                'nama_kost' => 'Kost Teratai 8',
                'owner' => 'Maya Sari',
                'alamat' => 'Jl. Sukajadi No. 21, Bandung',
                'harga' => 950000.00,
                'ukuran_kamar' => '3x4',
                'tipe_kos' => 'putri',
                'fasilitas' => 'Kasur, Lemari, AC, WiFi, Kamar mandi dalam',
                'foto_url' => 'https://example.com/kost/teratai8.jpg',
            ],
            [
                'nama_kost' => 'Kost Seruni 9',
                'owner' => 'Fajar Nugroho',
                'alamat' => 'Jl. Asia Afrika No. 30, Bandung',
                'harga' => 600000.00,
                'ukuran_kamar' => '3x3',
                'tipe_kos' => 'putra',
                'fasilitas' => 'Kasur, Lemari, WiFi',
                'foto_url' => 'https://example.com/kost/seruni9.jpg',
            ],
            [
                'nama_kost' => 'Kost Anyelir 10',
                'owner' => 'Rina Marlina',
                'alamat' => 'Jl. Gatot Subroto No. 15, Bandung',
                'harga' => 1300000.00,
                'ukuran_kamar' => '4x4',
                'tipe_kos' => 'campur',
                'fasilitas' => 'Kasur, Lemari, AC, WiFi, Kamar mandi dalam, Dapur bersama',
                'foto_url' => 'https://example.com/kost/anyelir10.jpg',
            ],
        ];

        Kost::insert($data);
    }
}
