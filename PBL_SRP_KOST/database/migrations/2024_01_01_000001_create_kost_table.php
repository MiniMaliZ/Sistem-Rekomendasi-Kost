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
            $table->string('nama_kost', 150);
            $table->string('owner', 100);
            $table->string('alamat', 255);
            $table->decimal('harga', 15, 2);
            $table->string('ukuran_kamar', 50)->nullable();
            $table->enum('tipe_kos', ['putra', 'putri', 'campur']);
            $table->text('fasilitas')->nullable();
            $table->string('foto_url', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kost');
    }
};
