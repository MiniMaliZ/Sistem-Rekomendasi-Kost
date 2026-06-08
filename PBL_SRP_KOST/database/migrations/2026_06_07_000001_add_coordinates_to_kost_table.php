<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('kost', 'latitude')) {
            Schema::table('kost', function (Blueprint $table): void {
                $table->decimal('latitude', 10, 8)->nullable();
            });
        }

        if (! Schema::hasColumn('kost', 'longitude')) {
            Schema::table('kost', function (Blueprint $table): void {
                $table->decimal('longitude', 11, 8)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kost', 'longitude')) {
            Schema::table('kost', function (Blueprint $table): void {
                $table->dropColumn('longitude');
            });
        }

        if (Schema::hasColumn('kost', 'latitude')) {
            Schema::table('kost', function (Blueprint $table): void {
                $table->dropColumn('latitude');
            });
        }
    }
};
