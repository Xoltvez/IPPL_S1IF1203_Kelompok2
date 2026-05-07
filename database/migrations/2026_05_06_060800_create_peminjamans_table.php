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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_id')->constrained('users'); 
            $table->foreignId('buku_id')->constrained('bukus');
            $table->date('tanggal_peminjaman'); 
            $table->date('tanggal_jatuh_tempo'); 
            $table->enum('status', ['dipinjam', 'dikembalikan', 'hilang'])->default('dipinjam'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
