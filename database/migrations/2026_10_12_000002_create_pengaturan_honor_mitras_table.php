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
        Schema::create('pengaturan_honor_mitras', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun')->nullable();
            $table->integer('bulan')->nullable();
            $table->unsignedBigInteger('maksimal_honor')->default(4500000)->comment('Batas honor bulanan dalam rupiah (IDR)');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_honor_mitras');
    }
};
