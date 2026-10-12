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
        Schema::create('penilaian_mitras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alokasi_mitra_id')->constrained('alokasi_mitras')->cascadeOnDelete();
            $table->foreignId('mitra_id')->constrained('mitras')->cascadeOnDelete();
            $table->foreignId('penilai_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('bintang')->comment('Skala penilaian bintang 1-5');
            $table->text('komentar')->comment('Minimal 20 karakter');
            $table->timestamps();

            $table->unique(['alokasi_mitra_id', 'penilai_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_mitras');
    }
};
