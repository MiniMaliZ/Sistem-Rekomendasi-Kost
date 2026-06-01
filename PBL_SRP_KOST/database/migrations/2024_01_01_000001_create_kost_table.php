<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kost', function (Blueprint $table) {
            $table->id('id_kost');
            $table->string('nama_kost', 255);
            $table->decimal('harga', 12, 2);
            $table->string('tipe_kos', 255);
            $table->timestamps();
            $table->string('sepesifikasi_tipe_kamar', 255)->nullable();
            $table->string('fasilitas_kamar', 255)->nullable();
            $table->string('fasilitas_kamar_mandi', 255)->nullable();
            $table->string('fasilitas_umum', 255)->nullable();
            $table->string('fasilitas_parkir', 255)->nullable();
            $table->string('tempat_terdekat', 255)->nullable();
            $table->text('peraturan_kos')->nullable();
            $table->string('link_original', 500)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kost');
    }
};
