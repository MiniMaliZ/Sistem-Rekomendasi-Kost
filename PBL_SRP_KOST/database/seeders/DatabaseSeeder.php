<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Urutan seeder mengikuti dependency antar tabel (FK).
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KostSeeder::class,
            KriteriaSeeder::class,
            KostKriteriaSeeder::class,
            UserPreferensiSeeder::class,
            FavoritSeeder::class,
            FeedbackSeeder::class,
            HasilRekomendasiSeeder::class,
            RiwayatSeeder::class,
        ]);
    }
}