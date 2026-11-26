<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pst_antrians', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('kode_layanan', 10); // K, D, R, M
            $table->string('nama_layanan');
            $table->string('loket', 50); // Loket 1, Loket 2, Loket 3, Loket 4
            $table->unsignedInteger('urutan');
            $table->string('nomor_antrian', 20); // K-001
            $table->string('nama_pemohon');
            $table->string('no_wa', 30);
            $table->string('instansi')->nullable();
            $table->text('keperluan');
            $table->enum('status', ['menunggu', 'dipanggil', 'selesai'])->default('menunggu');
            $table->timestamp('called_at')->nullable();
            $table->timestamps();

            $table->index(['tanggal', 'status']);
            $table->index(['tanggal', 'kode_layanan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pst_antrians');
    }
};
