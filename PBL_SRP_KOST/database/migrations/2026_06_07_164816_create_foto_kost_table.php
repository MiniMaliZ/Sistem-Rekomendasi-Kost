<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('foto_kost', function (Blueprint $table) {
            $table->bigIncrements('id_foto');
            $table->unsignedBigInteger('id_kost');
            $table->text('foto_bangunan');
            $table->text('foto_kamar_mandi')->nullable();
            $table->text('foto_kamar')->nullable();

            $table->foreign('id_kost', 'fk_foto_kost')
                ->references('id_kost')
                ->on('kost')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE foto_kost 
            DEFAULT CHARSET=utf8mb4 
            COLLATE=utf8mb4_0900_ai_ci 
            ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foto_kost', function (Blueprint $table) {
            $table->dropForeign('fk_foto_kost');
        });

        Schema::dropIfExists('foto_kost');
    }
};
