<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom jenis_mitra ke alokasi_mitras untuk menentukan
     * batas honor yang berlaku: pendataan_lapangan atau pengolahan.
     */
    public function up(): void
    {
        Schema::table('alokasi_mitras', function (Blueprint $table) {
            $table->string('jenis_mitra', 30)
                  ->default('pendataan_lapangan')
                  ->after('posisi')
                  ->comment('Klasifikasi mitra: pendataan_lapangan | pengolahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alokasi_mitras', function (Blueprint $table) {
            $table->dropColumn('jenis_mitra');
        });
    }
};
