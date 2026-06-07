<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RiwayatSeeder extends Seeder
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
                'aksi' => 'lihat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'id_kost' => 2,
                'aksi' => 'rekomendasikan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'id_kost' => 3,
                'aksi' => 'lihat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'id_kost' => 4,
                'aksi' => 'cari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'id_kost' => 5,
                'aksi' => 'rekomendasikan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('riwayat')->insert($data);
    }
}
