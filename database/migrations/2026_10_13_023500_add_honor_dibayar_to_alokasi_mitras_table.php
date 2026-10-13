<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom honor_dibayar, honor_tidak_dibayar, dan melebihi_batas
     * untuk mencatat alokasi honor yang melewati batas maksimum.
     */
    public function up(): void
    {
        Schema::table('alokasi_mitras', function (Blueprint $table) {
            $table->unsignedBigInteger('honor_dibayar')
                  ->nullable()
                  ->after('honor')
                  ->comment('Honor yang dibayarkan ke mitra (Rp)');

            $table->unsignedBigInteger('honor_tidak_dibayar')
                  ->default(0)
                  ->after('honor_dibayar')
                  ->comment('Honor yang melebihi batas dan tidak dibayarkan (Rp)');

            $table->boolean('melebihi_batas')
                  ->default(false)
                  ->after('honor_tidak_dibayar')
                  ->comment('Apakah alokasi ini melebihi batas maksimum honor');
        });

        // Sinkronisasi record yang sudah ada
        DB::table('alokasi_mitras')->whereNull('honor_dibayar')->update([
            'honor_dibayar'       => DB::raw('honor'),
            'honor_tidak_dibayar' => 0,
            'melebihi_batas'      => false,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alokasi_mitras', function (Blueprint $table) {
            $table->dropColumn(['honor_dibayar', 'honor_tidak_dibayar', 'melebihi_batas']);
        });
    }
};
