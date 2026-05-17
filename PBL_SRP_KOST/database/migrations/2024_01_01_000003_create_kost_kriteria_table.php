<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kost_kriteria', function (Blueprint $table) {
            $table->id('id_kost_kriteria');
            $table->unsignedBigInteger('id_kost');
            $table->unsignedBigInteger('id_kriteria');
            $table->decimal('nilai', 10, 4);

            $table->foreign('id_kost')->references('id_kost')->on('kost')->onDelete('cascade');
            $table->foreign('id_kriteria')->references('id_kriteria')->on('kriteria')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kost_kriteria');
    }
};
