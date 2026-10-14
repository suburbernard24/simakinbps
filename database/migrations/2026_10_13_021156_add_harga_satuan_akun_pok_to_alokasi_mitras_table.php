<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom harga_satuan dan akun_pok ke alokasi_mitras.
     * - harga_satuan : honor per satuan (harga satuan kegiatan)
     * - akun_pok     : kode/uraian beban anggaran / akun POK
     * Honor total = target_kuantitas × harga_satuan
     */
    public function up(): void
    {
        Schema::table('alokasi_mitras', function (Blueprint $table) {
            $table->unsignedBigInteger('harga_satuan')
                  ->default(0)
                  ->after('satuan')
                  ->comment('Honor per satuan kegiatan (Rp)');

            $table->string('akun_pok', 255)
                  ->nullable()
                  ->after('keterangan')
                  ->comment('Beban Anggaran / Akun POK');
        });
    }

    public function down(): void
    {
        Schema::table('alokasi_mitras', function (Blueprint $table) {
            $table->dropColumn(['harga_satuan', 'akun_pok']);
        });
    }
};
