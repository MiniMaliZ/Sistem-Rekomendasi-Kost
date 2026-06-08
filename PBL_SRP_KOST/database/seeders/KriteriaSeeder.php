<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $data = [
            [
                'id_user' => 1,
                'nama_kriteria' => 'Harga',
            ],
            [
                'id_user' => 1,
                'nama_kriteria' => 'Rating',
            ],
            [
                'id_user' => 1,
                'nama_kriteria' => 'Luas Kamar',
            ],
            [
                'id_user' => 1,
                'nama_kriteria' => 'Total fasilitas',
            ],
            [
                'id_user' => 1,
                'nama_kriteria' => 'Keamanan (CCTV)',
            ],
            [
                'id_user' => 1,
                'nama_kriteria' => 'Listrik (termasuk/tidak)',
            ]
        ];

        $data = array_map(fn (array $row): array => $row + [
            'created_at' => $now,
            'updated_at' => $now,
        ], $data);

        DB::table('kriteria')->insert($data);
    }
}
