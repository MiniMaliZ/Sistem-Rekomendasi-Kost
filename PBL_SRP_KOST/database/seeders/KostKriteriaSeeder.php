<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KostKriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [

            // =====================================================
            // Kost 1
            // =====================================================
            [
                'id_kost' => 1,
                'id_kriteria' => 1, // Harga
                'nilai' => 78,
            ],
            [
                'id_kost' => 1,
                'id_kriteria' => 2, // Rating
                'nilai' => 90,
            ],
            [
                'id_kost' => 1,
                'id_kriteria' => 3, // Luas Kamar
                'nilai' => 84,
            ],
            [
                'id_kost' => 1,
                'id_kriteria' => 4, // Total fasilitas
                'nilai' => 95,
            ],
            [
                'id_kost' => 1,
                'id_kriteria' => 5, // Keamanan (CCTV)
                'nilai' => 88,
            ],
            [
                'id_kost' => 1,
                'id_kriteria' => 6, // Listrik (termasuk/tidak)
                'nilai' => 92,
            ],

            // =====================================================
            // Kost 2
            // =====================================================
            [
                'id_kost' => 2,
                'id_kriteria' => 1,
                'nilai' => 85,
            ],
            [
                'id_kost' => 2,
                'id_kriteria' => 2,
                'nilai' => 82,
            ],
            [
                'id_kost' => 2,
                'id_kriteria' => 3,
                'nilai' => 90,
            ],
            [
                'id_kost' => 2,
                'id_kriteria' => 4,
                'nilai' => 75,
            ],
            [
                'id_kost' => 2,
                'id_kriteria' => 5,
                'nilai' => 80,
            ],
            [
                'id_kost' => 2,
                'id_kriteria' => 6,
                'nilai' => 70,
            ],

            // =====================================================
            // Kost 3
            // =====================================================
            [
                'id_kost' => 3,
                'id_kriteria' => 1,
                'nilai' => 81,
            ],
            [
                'id_kost' => 3,
                'id_kriteria' => 2,
                'nilai' => 88,
            ],
            [
                'id_kost' => 3,
                'id_kriteria' => 3,
                'nilai' => 77,
            ],
            [
                'id_kost' => 3,
                'id_kriteria' => 4,
                'nilai' => 89,
            ],
            [
                'id_kost' => 3,
                'id_kriteria' => 5,
                'nilai' => 91,
            ],
            [
                'id_kost' => 3,
                'id_kriteria' => 6,
                'nilai' => 76,
            ],

            // =====================================================
            // Kost 4
            // =====================================================
            [
                'id_kost' => 4,
                'id_kriteria' => 1,
                'nilai' => 95,
            ],
            [
                'id_kost' => 4,
                'id_kriteria' => 2,
                'nilai' => 86,
            ],
            [
                'id_kost' => 4,
                'id_kriteria' => 3,
                'nilai' => 83,
            ],
            [
                'id_kost' => 4,
                'id_kriteria' => 4,
                'nilai' => 80,
            ],
            [
                'id_kost' => 4,
                'id_kriteria' => 5,
                'nilai' => 93,
            ],
            [
                'id_kost' => 4,
                'id_kriteria' => 6,
                'nilai' => 85,
            ],

            // =====================================================
            // Kost 5
            // =====================================================
            [
                'id_kost' => 5,
                'id_kriteria' => 1,
                'nilai' => 73,
            ],
            [
                'id_kost' => 5,
                'id_kriteria' => 2,
                'nilai' => 94,
            ],
            [
                'id_kost' => 5,
                'id_kriteria' => 3,
                'nilai' => 88,
            ],
            [
                'id_kost' => 5,
                'id_kriteria' => 4,
                'nilai' => 90,
            ],
            [
                'id_kost' => 5,
                'id_kriteria' => 5,
                'nilai' => 84,
            ],
            [
                'id_kost' => 5,
                'id_kriteria' => 6,
                'nilai' => 79,
            ],

        ];

        DB::table('kost_kriteria')->insert($data);
    }
}