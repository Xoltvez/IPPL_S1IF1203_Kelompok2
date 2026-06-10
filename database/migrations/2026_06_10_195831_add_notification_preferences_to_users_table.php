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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notif_persetujuan')->default(true)->after('no_telp');
            $table->boolean('notif_pengembalian')->default(true)->after('notif_persetujuan');
            $table->boolean('notif_jatuh_tempo')->default(true)->after('notif_pengembalian');
            $table->boolean('notif_rekomendasi')->default(true)->after('notif_jatuh_tempo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notif_persetujuan', 'notif_pengembalian', 'notif_jatuh_tempo', 'notif_rekomendasi']);
        });
    }
};
