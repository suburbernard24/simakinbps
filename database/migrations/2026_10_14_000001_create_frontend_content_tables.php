<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Infografis
        Schema::create('frontend_infografis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori')->default('umum'); // transportasi, ekonomi, pertanian, pendidikan, demografi, pariwisata, umum
            $table->string('badge_text')->nullable();
            $table->string('bg_gradient')->default('from-blue-600 to-indigo-700');
            $table->string('icon')->nullable();
            $table->text('data_points')->nullable(); // JSON data points
            $table->string('sumber')->nullable();
            $table->string('tahun')->nullable();
            $table->string('image_path')->nullable();
            $table->string('link_url')->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Tabel Quotes
        Schema::create('frontend_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->text('quote');
            $table->string('foto')->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Tabel Kegiatan / Berita BPS
        Schema::create('frontend_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori')->default('Umum');
            $table->string('tanggal_display')->nullable();
            $table->text('deskripsi');
            $table->string('badge_color')->default('blue'); // purple, teal, orange, emerald, blue
            $table->string('icon')->nullable();
            $table->string('link_url')->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Tabel Indikator Makro Carousel
        Schema::create('frontend_indikators', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('sub_judul')->nullable();
            $table->string('nilai');
            $table->string('periode')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('badge_text')->default('Tabel Dinamis');
            $table->string('link_tabel')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── SEED DATA AWAL DARI HOMEPAGE ─────────────────────────────────────

        // Seed Quotes
        DB::table('frontend_quotes')->insert([
            'nama'       => 'Dr. Ir. Habibie',
            'jabatan'    => 'KEPALA BPS KABUPATEN MANGGARAI',
            'quote'      => 'Data yang akurat dan berkualitas adalah fondasi utama bagi perencanaan, evaluasi pembangunan bangsa yang berkelanjutan serta kesejahteraan masyarakat secara merata.',
            'foto'       => null,
            'urutan'     => 1,
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed Infografis (6 item)
        DB::table('frontend_infografis')->insert([
            [
                'judul'       => 'Transportasi Darat Manggarai',
                'kategori'    => 'transportasi',
                'badge_text'  => 'STATISTIK TRANSPORTASI',
                'bg_gradient' => 'from-amber-500 to-amber-600',
                'icon'        => 'truck',
                'data_points' => json_encode([
                    ['label' => 'Sepeda Motor', 'value' => '78,4%'],
                    ['label' => 'Mobil Penumpang', 'value' => '14,2%'],
                ]),
                'sumber'      => 'Kepolisian & Dishub',
                'tahun'       => '2024',
                'image_path'  => null,
                'link_url'    => 'https://manggaraikab.bps.go.id',
                'urutan'      => 1,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'judul'       => 'Pertumbuhan Ekonomi Daerah',
                'kategori'    => 'ekonomi',
                'badge_text'  => 'INDIKATOR EKONOMI',
                'bg_gradient' => 'from-teal-600 to-cyan-700',
                'icon'        => 'chart-pie',
                'data_points' => json_encode([
                    ['label' => 'Sektor Pertanian', 'value' => '34,12%'],
                    ['label' => 'Perdagangan', 'value' => '16,45%'],
                ]),
                'sumber'      => 'PDRB Harga Berlaku',
                'tahun'       => 'Rilis 2024',
                'image_path'  => null,
                'link_url'    => 'https://manggaraikab.bps.go.id',
                'urutan'      => 2,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'judul'       => 'Sensus Pertanian (ST2023)',
                'kategori'    => 'pertanian',
                'badge_text'  => 'SENSUS PERTANIAN',
                'bg_gradient' => 'from-emerald-600 to-green-700',
                'icon'        => 'tree',
                'data_points' => json_encode([
                    ['label' => 'Komoditas Kopi', 'value' => 'Unggulan'],
                    ['label' => 'Usaha Tani', 'value' => '54.210 RTP'],
                ]),
                'sumber'      => 'ST2023 Tahap II',
                'tahun'       => 'BPS Manggarai',
                'image_path'  => null,
                'link_url'    => 'https://manggaraikab.bps.go.id',
                'urutan'      => 3,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'judul'       => 'Pendidikan & IPM',
                'kategori'    => 'pendidikan',
                'badge_text'  => 'INDIKATOR PENDIDIKAN',
                'bg_gradient' => 'from-indigo-600 to-blue-700',
                'icon'        => 'graduation-cap',
                'data_points' => json_encode([
                    ['label' => 'Skor IPM 2023', 'value' => '67,03 Poin'],
                    ['label' => 'Harapan Lama Sekolah', 'value' => '13,85 Tahun'],
                ]),
                'sumber'      => 'Metode Baru IPM',
                'tahun'       => 'Kategori Sedang',
                'image_path'  => null,
                'link_url'    => 'https://manggaraikab.bps.go.id',
                'urutan'      => 4,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'judul'       => 'Struktur & Komposisi Penduduk',
                'kategori'    => 'demografi',
                'badge_text'  => 'DEMOGRAFI & GENDER',
                'bg_gradient' => 'from-rose-600 to-red-700',
                'icon'        => 'users',
                'data_points' => json_encode([
                    ['label' => 'Usia Produktif', 'value' => '65,4%'],
                    ['label' => 'Rasio Ketergantungan', 'value' => '52,8%'],
                ]),
                'sumber'      => 'Sensus Penduduk SP2020',
                'tahun'       => 'Proyeksi 2023',
                'image_path'  => null,
                'link_url'    => 'https://manggaraikab.bps.go.id',
                'urutan'      => 5,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'judul'       => 'Pariwisata & Okupansi Hotel',
                'kategori'    => 'pariwisata',
                'badge_text'  => 'PARIWISATA MANGGARAI',
                'bg_gradient' => 'from-orange-500 to-amber-700',
                'icon'        => 'hotel',
                'data_points' => json_encode([
                    ['label' => 'TPK Hotel', 'value' => '41,25%'],
                    ['label' => 'Lama Menginap', 'value' => '1,82 Hari'],
                ]),
                'sumber'      => 'Survei Akomodasi',
                'tahun'       => 'Ruteng & Sekitarnya',
                'image_path'  => null,
                'link_url'    => 'https://manggaraikab.bps.go.id',
                'urutan'      => 6,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        // Seed Kegiatan (5 item)
        DB::table('frontend_kegiatans')->insert([
            [
                'judul'           => 'Pelaksanaan Survei Sosial Ekonomi Nasional (Susenas) Maret 2024',
                'kategori'        => 'Survei Sosial Ekonomi',
                'tanggal_display' => '15 Maret 2024',
                'deskripsi'       => 'Petugas pencacah lapangan BPS Kabupaten Manggarai melakukan pendataan langsung ke rumah tangga terpilih guna mengumpulkan data riil mengenai kondisi sosial ekonomi, pendidikan, kesehatan, dan pola konsumsi.',
                'badge_color'     => 'purple',
                'icon'            => 'users',
                'link_url'        => 'https://manggaraikab.bps.go.id',
                'urutan'          => 1,
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'judul'           => 'Pelatihan Calon Petugas Survei Angkatan Kerja Nasional (Sakernas)',
                'kategori'        => 'Penguatan Kapasitas',
                'tanggal_display' => '28 Februari 2024',
                'deskripsi'       => 'Pembekalan konsep dan definisi ketenagakerjaan bagi seluruh pencacah lapangan untuk menghasilkan data Tingkat Pengangguran Terbuka (TPT) dan partisipasi angkatan kerja yang akurat serta tepat...',
                'badge_color'     => 'teal',
                'icon'            => 'book',
                'link_url'        => 'https://manggaraikab.bps.go.id',
                'urutan'          => 2,
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'judul'           => 'Focus Group Discussion (FGD): Penyusunan Publikasi Daerah Dalam Angka',
                'kategori'        => 'Koordinasi Sektoral',
                'tanggal_display' => '14 Februari 2024',
                'deskripsi'       => 'Kolaborasi strategis BPS Kabupaten Manggarai bersama Bappeda dan seluruh Organisasi Perangkat Daerah (OPD) dalam rangka harmonisasi, verifikasi, serta sinkronisasi data statistik sektoral daerah sebelum rilis resmi.',
                'badge_color'     => 'orange',
                'icon'            => 'comments',
                'link_url'        => 'https://manggaraikab.bps.go.id',
                'urutan'          => 3,
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'judul'           => 'Diseminasi Hasil Sensus Pertanian 2023 Tahap II BPS Manggarai',
                'kategori'        => 'Diseminasi Statistik',
                'tanggal_display' => '18 Januari 2024',
                'deskripsi'       => 'Pemaparan hasil sensus pertanian mengenai karakteristik usaha pertanian perorangan, demografi petani milenial, dan potensi subsektor perkebunan kopi kepada stakeholder pembangunan daerah.',
                'badge_color'     => 'emerald',
                'icon'            => 'check-circle',
                'link_url'        => 'https://manggaraikab.bps.go.id',
                'urutan'          => 4,
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'judul'           => 'Implementasi SIMAKIN untuk Peningkatan Akuntabilitas Pegawai',
                'kategori'        => 'Manajemen Kinerja',
                'tanggal_display' => '08 Januari 2024',
                'deskripsi'       => 'Evaluasi capaian Sasaran Kinerja Pegawai (SKP), verifikasi alokasi pekerjaan tim, dan implementasi aplikasi SIMAKIN untuk mewujudkan birokrasi BPS yang adaptif, lincah, dan transparan.',
                'badge_color'     => 'blue',
                'icon'            => 'bolt',
                'link_url'        => '#',
                'urutan'          => 5,
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);

        // Seed Indikator Carousel (5 item)
        DB::table('frontend_indikators')->insert([
            [
                'judul'       => 'Pertumbuhan Ekonomi',
                'sub_judul'   => '(y on y)',
                'nilai'       => '5,01',
                'periode'     => 'Triwulan II 2026',
                'deskripsi'   => 'Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.',
                'badge_text'  => 'Tabel Dinamis',
                'link_tabel'  => '#datapokok',
                'icon'        => 'chart-line',
                'urutan'      => 1,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'judul'       => 'Umur Harapan Hidup Saat Lahir (UHH) SP2020',
                'sub_judul'   => null,
                'nilai'       => '72,16',
                'periode'     => '2025',
                'deskripsi'   => 'Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.',
                'badge_text'  => 'Tabel Dinamis',
                'link_tabel'  => '#datapokok',
                'icon'        => 'heart-pulse',
                'urutan'      => 2,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'judul'       => 'Pertumbuhan Ekonomi (q to q)',
                'sub_judul'   => null,
                'nilai'       => '5,01',
                'periode'     => 'Triwulan II 2026',
                'deskripsi'   => 'Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.',
                'badge_text'  => 'Tabel Dinamis',
                'link_tabel'  => '#datapokok',
                'icon'        => 'chart-line',
                'urutan'      => 3,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'judul'       => 'Umur Harapan Hidup Perempuan',
                'sub_judul'   => null,
                'nilai'       => '73,61',
                'periode'     => 'SP2020LF',
                'deskripsi'   => 'Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.',
                'badge_text'  => 'Tabel Dinamis',
                'link_tabel'  => '#datapokok',
                'icon'        => 'venus',
                'urutan'      => 4,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'judul'       => 'Umur Harapan Hidup Laki-Laki',
                'sub_judul'   => null,
                'nilai'       => '69,63',
                'periode'     => 'SP2020LF',
                'deskripsi'   => 'Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.',
                'badge_text'  => 'Tabel Dinamis',
                'link_tabel'  => '#datapokok',
                'icon'        => 'mars',
                'urutan'      => 5,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('frontend_indikators');
        Schema::dropIfExists('frontend_kegiatans');
        Schema::dropIfExists('frontend_quotes');
        Schema::dropIfExists('frontend_infografis');
    }
};
