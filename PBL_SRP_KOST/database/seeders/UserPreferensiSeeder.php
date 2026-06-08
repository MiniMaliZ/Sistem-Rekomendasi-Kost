<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserPreferensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $data = [
            [
                'id_kriteria' => 1, // Harga
                'bobot' => 0.30,
            ],
            [
                'id_kriteria' => 2, // Rating
                'bobot' => 0.30,
            ],
            [
                'id_kriteria' => 3, // Luas Kamar
                'bobot' => 0.10,
            ],
            [
                'id_kriteria' => 4, // Total fasilitas
                'bobot' => 0.15,
            ],
            [
                'id_kriteria' => 5, // Keamanan (CCTV)
                'bobot' => 0.10,
            ],
            [
                'id_kriteria' => 6, // Listrik (termasuk/tidak)
                'bobot' => 0.05,
            ]
        ];

        $data = array_map(fn (array $row): array => $row + [
            'created_at' => $now,
            'updated_at' => $now,
        ], $data);

        DB::table('user_preferensi')->insert($data);
    }
}
