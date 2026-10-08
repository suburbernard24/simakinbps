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
        Schema::table('ckp', function (Blueprint $table) {
            $table->string('kategori', 20)->default('utama')->after('uraian_kegiatan');
            $table->decimal('angka_kredit', 8, 4)->nullable()->after('persentase_kuantitas');
            $table->string('status', 30)->default('Belum Dinilai')->after('satuan');
            $table->string('kode_butir', 50)->nullable()->after('keterangan');

            $table->index(['user_id', 'tahun', 'bulan', 'kategori']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ckp', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'tahun', 'bulan', 'kategori']);
            $table->dropIndex(['status']);
            $table->dropColumn(['kategori', 'angka_kredit', 'status', 'kode_butir']);
        });
    }
};
