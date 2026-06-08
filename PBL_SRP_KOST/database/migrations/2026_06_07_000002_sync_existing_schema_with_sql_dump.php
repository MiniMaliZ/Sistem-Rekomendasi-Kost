<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLES_WITH_TIMESTAMPS = [
        'user',
        'kriteria',
        'kost_kriteria',
        'user_preferensi',
        'hasil_rekomendasi',
        'favorit',
        'feedback',
        'riwayat',
    ];

    public function up(): void
    {
        foreach (self::TABLES_WITH_TIMESTAMPS as $table) {
            $this->addMissingTimestamps($table);
        }

        if (Schema::hasTable('riwayat') && DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `riwayat` MODIFY `aksi` varchar(255) NOT NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('riwayat') && DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `riwayat` MODIFY `aksi` enum('lihat','cari','rekomendasikan') NOT NULL");
        }

        foreach (array_reverse(self::TABLES_WITH_TIMESTAMPS) as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $columns = [];
            if (Schema::hasColumn($table, 'updated_at')) {
                $columns[] = 'updated_at';
            }

            if ($table !== 'user' && Schema::hasColumn($table, 'created_at')) {
                $columns[] = 'created_at';
            }

            if ($columns !== []) {
                Schema::table($table, function (Blueprint $tableBlueprint) use ($columns): void {
                    $tableBlueprint->dropColumn($columns);
                });
            }
        }
    }

    private function addMissingTimestamps(string $table): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        $hasCreatedAt = Schema::hasColumn($table, 'created_at');
        $hasUpdatedAt = Schema::hasColumn($table, 'updated_at');

        if ($hasCreatedAt && $hasUpdatedAt) {
            return;
        }

        Schema::table($table, function (Blueprint $tableBlueprint) use ($hasCreatedAt, $hasUpdatedAt): void {
            if (! $hasCreatedAt) {
                $tableBlueprint->timestamp('created_at')->nullable();
            }

            if (! $hasUpdatedAt) {
                $tableBlueprint->timestamp('updated_at')->nullable();
            }
        });
    }
};
