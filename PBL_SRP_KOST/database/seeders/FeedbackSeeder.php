<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackSeeder extends Seeder
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
                'rating' => 5,
                'komentar' => 'Kost nyaman dan bersih',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'id_kost' => 2,
                'rating' => 4,
                'komentar' => 'Fasilitas lengkap',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'id_kost' => 3,
                'rating' => 5,
                'komentar' => 'Lokasi strategis dan aman',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'id_kost' => 4,
                'rating' => 3,
                'komentar' => 'Harga cukup terjangkau',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'id_kost' => 5,
                'rating' => 4,
                'komentar' => 'Keamanan bagus dan nyaman',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('feedback')->insert($data);
    }
}
