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
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->tinyInteger('bulan')->unsigned()->comment('1-12');
            $table->enum('jenis_pekerjaan', ['kantor', 'lapangan']);
            $table->string('nama_kegiatan');
            $table->string('satuan')->default('Dokumen');
            $table->decimal('bobot_ibk', 8, 4)->default(1.0000)->comment('Bobot nilai IBK per satuan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['tahun', 'bulan', 'jenis_pekerjaan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
