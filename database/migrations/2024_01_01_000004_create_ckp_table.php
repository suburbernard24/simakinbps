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
        Schema::create('ckp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->year('tahun');
            $table->tinyInteger('bulan')->unsigned()->comment('1-12');
            $table->string('uraian_kegiatan');
            $table->decimal('target_kuantitas', 10, 2)->default(0);
            $table->decimal('realisasi_kuantitas', 10, 2)->default(0);
            $table->decimal('persentase_kuantitas', 8, 2)->default(0)->comment('Realisasi / Target * 100');
            $table->decimal('kualitas', 8, 2)->default(0)->comment('Nilai kualitas skala 0-100');
            $table->decimal('nilai_ckp', 8, 2)->default(0)->comment('Kombinasi kuantitas & kualitas');
            $table->string('satuan')->default('Dokumen');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['tahun', 'bulan']);
            $table->index(['user_id', 'tahun', 'bulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ckp');
    }
};
