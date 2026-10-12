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
        Schema::create('mitras', function (Blueprint $table) {
            $table->id();
            $table->string('sobat_id')->unique()->comment('ID Mitra Sobat BPS');
            $table->string('nik', 30)->nullable();
            $table->string('nama');
            $table->string('foto')->nullable();
            $table->string('jenis_kelamin', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('telepon', 30)->nullable();
            $table->text('alamat')->nullable();
            $table->string('provinsi')->default('Nusa Tenggara Timur');
            $table->string('kabupaten')->default('Kabupaten Manggarai');
            $table->string('posisi_default')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitras');
    }
};
