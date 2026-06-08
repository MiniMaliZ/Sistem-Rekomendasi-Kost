<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorit', function (Blueprint $table) {
            $table->id('id_favorit');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_kost');
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
            $table->foreign('id_kost')->references('id_kost')->on('kost')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorit');
    }
};
