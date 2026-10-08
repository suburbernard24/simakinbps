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
        Schema::create('alokasi_pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->string('satuan');
            $table->integer('target_kuantitas');
            $table->string('asal_tim');
            $table->foreignId('pegawai_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('keterangan')->nullable();
            $table->integer('estimasi_waktu')->comment('Estimasi dalam hari');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['terjadwal', 'berjalan', 'selesai', 'terlambat'])->default('terjadwal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alokasi_pekerjaans');
    }
};
