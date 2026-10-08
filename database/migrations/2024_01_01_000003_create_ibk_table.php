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
        Schema::create('ibk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kegiatan_id')->nullable()->constrained('kegiatan')->nullOnDelete();
            $table->year('tahun');
            $table->tinyInteger('bulan')->unsigned()->comment('1-12');
            $table->enum('jenis_pekerjaan', ['kantor', 'lapangan']);
            $table->string('uraian_kegiatan');
            $table->decimal('volume', 10, 2)->default(0)->comment('Jumlah target/output');
            $table->string('satuan')->default('Dokumen');
            $table->decimal('waktu_penyelesaian', 8, 2)->nullable()->comment('Dalam menit');
            $table->decimal('nilai_ibk', 10, 4)->default(0)->comment('Nilai Indeks Beban Kinerja');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['tahun', 'bulan', 'jenis_pekerjaan']);
            $table->index(['user_id', 'tahun', 'bulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibk');
    }
};
