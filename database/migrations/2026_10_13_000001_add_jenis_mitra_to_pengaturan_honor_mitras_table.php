<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom jenis_mitra untuk membedakan antara:
     * - pendataan_lapangan : Mitra Pendataan Lapangan (batas Rp 3.653.000)
     * - pengolahan         : Mitra Pengolahan (batas Rp 3.379.000)
     */
    public function up(): void
    {
        Schema::table('pengaturan_honor_mitras', function (Blueprint $table) {
            $table->string('jenis_mitra', 30)
                  ->default('pendataan_lapangan')
                  ->after('keterangan')
                  ->comment('Klasifikasi mitra: pendataan_lapangan | pengolahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan_honor_mitras', function (Blueprint $table) {
            $table->dropColumn('jenis_mitra');
        });
    }
};
