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
        Schema::create('hari_kerja_aktifs', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->integer('bulan');
            $table->integer('jumlah_hari_kerja')->default(22);
            $table->decimal('jam_kerja_per_hari', 4, 2)->default(7.50);
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['tahun', 'bulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hari_kerja_aktifs');
    }
};
