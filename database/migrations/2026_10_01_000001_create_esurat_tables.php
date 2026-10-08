<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for e-Surat BPS based on PRD.
     */
    public function up(): void
    {
        // 1. Master Unit Kerja / Fungsi (PRD 17 & 10.4)
        Schema::create('unit_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Add additional columns to users if needed
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'gelar')) {
                $table->string('gelar')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'pangkat')) {
                $table->string('pangkat')->nullable()->after('golongan');
            }
            if (!Schema::hasColumn('users', 'no_whatsapp')) {
                $table->string('no_whatsapp')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'unit_kerja_id')) {
                $table->foreignId('unit_kerja_id')->nullable()->after('tim_id')->constrained('unit_kerjas')->nullOnDelete();
            }
        });

        // 2. Master Jenis Surat (PRD 17 & 10.5)
        Schema::create('jenis_surats', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique(); // e.g. 'ST', 'SK', 'B', 'BA', 'UND'
            $table->string('nama'); // e.g. 'Surat Tugas', 'Surat Keputusan (SK)'
            $table->string('format_nomor')->default('{kode}-{nomor_urut}/{kode_satker}/{kode_klasifikasi}/{tahun}');
            $table->string('kode_klasifikasi_default', 50)->default('VS.330');
            $table->text('deskripsi')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // 3. Master Template Surat (PRD 17 & 10.6)
        Schema::create('template_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surats')->cascadeOnDelete();
            $table->string('nama_template');
            $table->string('kode_template', 50)->unique();
            $table->longText('kop_surat')->nullable();
            $table->longText('isi_template');
            $table->longText('penutup')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // 4. Data Utama Surat (PRD 17 & 10.7)
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surats')->cascadeOnDelete();
            $table->foreignId('template_surat_id')->nullable()->constrained('template_surats')->nullOnDelete();
            $table->foreignId('user_id')->comment('Pemohon')->constrained('users')->cascadeOnDelete();
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerjas')->nullOnDelete();
            $table->string('nomor_surat')->nullable()->unique();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_surat');
            $table->string('perihal');
            $table->string('fungsi')->nullable();
            $table->enum('status', [
                'draft',
                'diajukan',
                'verifikasi',
                'dikembalikan',
                'terverifikasi',
                'persetujuan',
                'ditolak',
                'disetujui',
                'dinomori',
                'selesai',
                'diarsipkan'
            ])->default('draft');
            $table->text('catatan')->nullable();
            $table->string('kode_klasifikasi', 50)->default('VS.330');
            $table->string('kode_satker', 20)->default('53131');
            $table->timestamps();
        });

        // 5. Surat Detail (PRD 17 & 11-14: Surat Tugas, SK, Surat Keluar, Berita Acara)
        Schema::create('surat_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats')->cascadeOnDelete();
            
            // Detail Kegiatan & Surat Tugas
            $table->date('tanggal_kegiatan')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('tempat_kegiatan')->nullable();
            $table->string('tujuan_kegiatan')->nullable();
            $table->text('uraian_kegiatan')->nullable();

            // Detail SK
            $table->string('nomor_sk')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->string('pejabat_penetap')->nullable();
            $table->longText('dasar_penetapan')->nullable(); // Menimbang, Mengingat, Memutuskan

            // Detail Berita Acara & Pihak
            $table->string('pihak_pertama')->nullable();
            $table->string('pihak_kedua')->nullable();
            $table->string('jabatan_pihak_pertama')->nullable();
            $table->string('jabatan_pihak_kedua')->nullable();

            // Detail Tambahan & Keterangan
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 6. Relasi Surat dan Pegawai (PRD 17 & 9: Multiple Pegawai)
        Schema::create('surat_pegawais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->constrained('users')->cascadeOnDelete();
            $table->string('peran')->default('Anggota'); // e.g. Ketua, Anggota, Koordinator, Pengawas, Pencacah
            $table->integer('urutan')->default(1);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // 7. Penerima Surat (PRD 17 & 13)
        Schema::create('surat_penerimas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats')->cascadeOnDelete();
            $table->string('nama_penerima');
            $table->string('instansi')->nullable();
            $table->text('alamat')->nullable();
            $table->string('email')->nullable();
            $table->string('jenis_penerima')->default('Eksternal'); // Internal, Eksternal
            $table->timestamps();
        });

        // 8. Lampiran Surat (PRD 17 & 10.12)
        Schema::create('surat_lampirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats')->cascadeOnDelete();
            $table->string('nama_file');
            $table->string('file_path');
            $table->string('jenis_file')->nullable();
            $table->integer('ukuran')->nullable()->comment('in KB');
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // 9. Master & Log Penomoran Surat (PRD 17 & 16)
        Schema::create('nomor_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->nullable()->constrained('surats')->nullOnDelete();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surats')->cascadeOnDelete();
            $table->integer('tahun');
            $table->integer('nomor_urut');
            $table->string('kode_satker', 20)->default('53131');
            $table->string('kode_klasifikasi', 50)->default('VS.330');
            $table->string('nomor_lengkap');
            $table->timestamps();
        });

        // 10. Proses Verifikasi & Persetujuan (PRD 17 & 10.8-10.9)
        Schema::create('surat_verifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('level', ['verifikator', 'approver']);
            $table->enum('status', ['disetujui', 'dikembalikan', 'ditolak']);
            $table->text('catatan')->nullable();
            $table->dateTime('tanggal_verifikasi');
            $table->timestamps();
        });

        // 11. Riwayat Status Surat (PRD 17 & 10.13)
        Schema::create('surat_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_status_histories');
        Schema::dropIfExists('surat_verifikasis');
        Schema::dropIfExists('nomor_surats');
        Schema::dropIfExists('surat_lampirans');
        Schema::dropIfExists('surat_penerimas');
        Schema::dropIfExists('surat_pegawais');
        Schema::dropIfExists('surat_details');
        Schema::dropIfExists('surats');
        Schema::dropIfExists('template_surats');
        Schema::dropIfExists('jenis_surats');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'unit_kerja_id')) {
                $table->dropForeign(['unit_kerja_id']);
                $table->dropColumn(['unit_kerja_id']);
            }
            if (Schema::hasColumn('users', 'no_whatsapp')) {
                $table->dropColumn(['no_whatsapp']);
            }
            if (Schema::hasColumn('users', 'pangkat')) {
                $table->dropColumn(['pangkat']);
            }
            if (Schema::hasColumn('users', 'gelar')) {
                $table->dropColumn(['gelar']);
            }
        });

        Schema::dropIfExists('unit_kerjas');
    }
};
