<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HasilRekomendasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_user' => 2,
                'id_kost' => 1,
                'skor' => 0.92,
                'rangking' => 1,
                'created_at' => now(),
            ],
            [
                'id_user' => 2,
                'id_kost' => 2,
                'skor' => 0.89,
                'rangking' => 2,
                'created_at' => now(),
            ],
            [
                'id_user' => 2,
                'id_kost' => 3,
                'skor' => 0.85,
                'rangking' => 3,
                'created_at' => now(),
            ],
            [
                'id_user' => 2,
                'id_kost' => 4,
                'skor' => 0.80,
                'rangking' => 4,
                'created_at' => now(),
            ],
            [
                'id_user' => 2,
                'id_kost' => 5,
                'skor' => 0.75,
                'rangking' => 5,
                'created_at' => now(),
            ]
        ];

        DB::table('hasil_rekomendasi')->insert($data);
    }
}