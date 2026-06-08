<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_preferensi', function (Blueprint $table) {
            $table->id('id_preferensi');
            $table->unsignedBigInteger('id_kriteria');
            $table->decimal('bobot', 5, 2);
            $table->timestamps();

            $table->foreign('id_kriteria')->references('id_kriteria')->on('kriteria')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_preferensi');
    }
};
