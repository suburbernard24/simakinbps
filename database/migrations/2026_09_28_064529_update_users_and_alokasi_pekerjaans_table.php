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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tim_id')->nullable()->after('role')->constrained('teams')->nullOnDelete();
            $table->boolean('is_ketua_tim')->default(false)->after('tim_id');
            $table->boolean('is_public_user')->default(false)->after('is_active');
        });

        Schema::table('alokasi_pekerjaans', function (Blueprint $table) {
            $table->foreignId('tim_id')->nullable()->after('asal_tim')->constrained('teams')->nullOnDelete();
            $table->enum('jenis_pekerjaan', ['utama', 'tambahan'])->default('utama')->after('satuan');
            $table->integer('tahun')->nullable()->after('tanggal_selesai');
            $table->integer('bulan')->nullable()->after('tahun');
            $table->decimal('realisasi_kuantitas', 10, 2)->default(0)->after('target_kuantitas');
            $table->decimal('persentase_kuantitas', 5, 2)->default(0)->after('realisasi_kuantitas');
            $table->text('catatan_realisasi')->nullable()->after('status');
            $table->string('link_bukti')->nullable()->after('catatan_realisasi');
            $table->date('tanggal_realisasi')->nullable()->after('link_bukti');
            $table->decimal('kualitas', 5, 2)->default(0)->after('tanggal_realisasi')->comment('Persentase Kualitas b');
            $table->decimal('nilai_ckp', 5, 2)->default(0)->after('kualitas')->comment('CKP = (a + b) / 2');
            $table->foreignId('penilai_id')->nullable()->after('nilai_ckp')->constrained('users')->nullOnDelete();
            $table->text('catatan_penilai')->nullable()->after('penilai_id');
            $table->dateTime('tanggal_penilaian')->nullable()->after('catatan_penilai');
            $table->enum('status_penilaian', ['belum_dinilai', 'sudah_dinilai', 'dinilai'])->default('belum_dinilai')->after('tanggal_penilaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alokasi_pekerjaans', function (Blueprint $table) {
            $table->dropForeign(['tim_id']);
            $table->dropForeign(['penilai_id']);
            $table->dropColumn([
                'tim_id',
                'jenis_pekerjaan',
                'tahun',
                'bulan',
                'realisasi_kuantitas',
                'persentase_kuantitas',
                'catatan_realisasi',
                'link_bukti',
                'tanggal_realisasi',
                'kualitas',
                'nilai_ckp',
                'penilai_id',
                'catatan_penilai',
                'tanggal_penilaian',
                'status_penilaian',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tim_id']);
            $table->dropColumn(['tim_id', 'is_ketua_tim', 'is_public_user']);
        });
    }
};
