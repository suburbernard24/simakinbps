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
        Schema::create('alokasi_mitras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_id')->constrained('mitras')->cascadeOnDelete();
            $table->foreignId('tim_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('nama_kegiatan')->comment('Nama Survei / Kegiatan Utama');
            $table->string('kegiatan')->comment('Sub-kegiatan atau rincian pekerjaan');
            $table->string('posisi')->comment('Posisi mitra: PCL, PML, Petugas Pengolahan, dll');
            $table->string('satuan')->default('Dokumen');
            $table->decimal('target_kuantitas', 10, 2)->default(1);
            $table->unsignedBigInteger('honor')->default(0)->comment('Honor dalam IDR');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('tahun');
            $table->integer('bulan');
            $table->enum('status', ['terjadwal', 'berjalan', 'selesai', 'terlambat', 'diterima'])->default('terjadwal');
            $table->string('provinsi')->default('Nusa Tenggara Timur');
            $table->string('kabupaten')->default('Kabupaten Manggarai');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alokasi_mitras');
    }
};
