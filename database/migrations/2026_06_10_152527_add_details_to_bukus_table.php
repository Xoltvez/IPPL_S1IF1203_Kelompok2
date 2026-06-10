<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            $table->text('deskripsi')->nullable();
            $table->text('sinopsis')->nullable();
            $table->string('lebar')->nullable();
            $table->string('panjang')->nullable();
            $table->string('berat')->nullable();
            $table->string('bahasa')->default('Indonesia');
            $table->integer('halaman')->nullable();
            $table->string('jenis')->default('Buku Fisik');
            $table->date('tanggal_terbit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            $table->dropColumn([
                'deskripsi',
                'sinopsis',
                'lebar',
                'panjang',
                'berat',
                'bahasa',
                'halaman',
                'jenis',
                'tanggal_terbit'
            ]);
        });
    }
};
