<?php

namespace Database\Seeders;

use App\Models\AlokasiPekerjaan;
use App\Models\HariKerjaAktif;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed akun demo dan data dasar untuk SIMAKINBPS
     */
    public function run(): void
    {
        // 1. Buat Tim Kerja BPS
        $timSosial = Team::firstOrCreate(
            ['nama_tim' => 'Tim Statistik Sosial'],
            ['singkatan' => 'Sosial', 'keterangan' => 'Mengelola survei sosial, demografi, dan kesejahteraan rakyat.']
        );

        $timProduksi = Team::firstOrCreate(
            ['nama_tim' => 'Tim Statistik Produksi'],
            ['singkatan' => 'Produksi', 'keterangan' => 'Mengelola survei pertanian, industri, dan konstruksi.']
        );

        $timDistribusi = Team::firstOrCreate(
            ['nama_tim' => 'Tim Statistik Distribusi'],
            ['singkatan' => 'Distribusi', 'keterangan' => 'Mengelola statistik harga, pariwisata, perdagangan, dan transportasi.']
        );

        $timNerwilis = Team::firstOrCreate(
            ['nama_tim' => 'Tim Neraca Wilayah & Analisis Statistik'],
            ['singkatan' => 'Nerwilis', 'keterangan' => 'Mengelola PDRB dan analisis lintas sektor.']
        );

        $timIpds = Team::firstOrCreate(
            ['nama_tim' => 'Tim Integrasi Pengolahan & Diseminasi Statistik'],
            ['singkatan' => 'IPDS', 'keterangan' => 'Mengelola infrastruktur TI, pengolahan, dan publikasi data.']
        );

        $subbagUmum = Team::firstOrCreate(
            ['nama_tim' => 'Subbagian Umum'],
            ['singkatan' => 'Umum', 'keterangan' => 'Mengelola kepegawaian, keuangan, dan perlengkapan.']
        );

        // 2. Buat Pengguna Utama
        // Kepala BPS
        $kepala = User::updateOrCreate(
            ['username' => 'kepala'],
            [
                'name'      => 'Kepala BPS',
                'nip'       => '197001012000011001',
                'jabatan'   => 'Kepala BPS Kabupaten Manggarai',
                'golongan'  => 'IV/b',
                'email'     => 'kepala@bps.go.id',
                'password'  => Hash::make('password'),
                'role'      => 'kepala_bps',
                'is_active' => true,
            ]
        );

        // Ketua Tim
        $ketua = User::updateOrCreate(
            ['username' => 'ketua'],
            [
                'name'         => 'Ketua Tim',
                'nip'          => '198503152010011002',
                'jabatan'      => 'Ketua Tim',
                'golongan'     => 'III/d',
                'email'        => 'ketua@bps.go.id',
                'password'     => Hash::make('password'),
                'role'         => 'ketua_tim',
                'tim_id'       => $timSosial->id,
                'is_ketua_tim' => true,
                'is_active'    => true,
            ]
        );
        $timSosial->update(['ketua_id' => $ketua->id]);

        // Anggota Tim
        $anggota = User::updateOrCreate(
            ['username' => 'anggota'],
            [
                'name'         => 'Anggota Tim',
                'nip'          => '199207202015022003',
                'jabatan'      => 'Anggota Tim',
                'golongan'     => 'III/b',
                'email'        => 'anggota@bps.go.id',
                'password'     => Hash::make('password'),
                'role'         => 'pegawai',
                'tim_id'       => $timSosial->id,
                'is_ketua_tim' => false,
                'is_active'    => true,
            ]
        );

        // Pegawai Budi
        $budi = User::updateOrCreate(
            ['username' => 'budi'],
            [
                'name'         => 'Budi Santoso',
                'nip'          => '199008102014031002',
                'jabatan'      => 'Pranata Komputer Pertama',
                'golongan'     => 'III/a',
                'email'        => 'budi@bps.go.id',
                'password'     => Hash::make('password'),
                'role'         => 'pegawai',
                'tim_id'       => $timSosial->id,
                'is_ketua_tim' => false,
                'is_active'    => true,
            ]
        );

        // Administrator Sistem
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'      => 'Administrator',
                'nip'       => '-',
                'jabatan'   => 'Administrator Sistem',
                'golongan'  => '-',
                'email'     => 'admin@bps.go.id',
                'password'  => Hash::make('AdminBPS@2026'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );

        // User Publik
        User::updateOrCreate(
            ['username' => 'publik'],
            [
                'name'           => 'Pengguna Publik BPS',
                'nip'            => '-',
                'jabatan'        => 'Mitra / Stakeholder Publik',
                'golongan'       => '-',
                'email'          => 'publik@bps.go.id',
                'password'       => Hash::make('password'),
                'role'           => 'pegawai',
                'is_public_user' => true,
                'is_active'      => true,
            ]
        );

        // 3. Seed Hari Kerja Aktif Bulanan (Tahun 2026)
        $hariKerja2026 = [
            1  => 22, // Jan
            2  => 20, // Feb
            3  => 21, // Mar
            4  => 20, // Apr
            5  => 19, // Mei
            6  => 21, // Jun
            7  => 23, // Jul
            8  => 21, // Agu
            9  => 22, // Sep
            10 => 22, // Okt
            11 => 21, // Nov
            12 => 22, // Des
        ];

        foreach ($hariKerja2026 as $bln => $hari) {
            HariKerjaAktif::updateOrCreate(
                ['tahun' => 2026, 'bulan' => $bln],
                [
                    'jumlah_hari_kerja'  => $hari,
                    'jam_kerja_per_hari' => 7.50,
                    'keterangan'         => "Hari kerja aktif reguler BPS (7.5 jam/hari)",
                ]
            );
        }

        // 4. Seed Data Alokasi Pekerjaan (Utama & Tambahan)
        $now = Carbon::now();
        $tahun = $now->year;
        $bulan = $now->month;

        // Pekerjaan Utama 1: Ditugaskan ke Anggota oleh Ketua Tim
        AlokasiPekerjaan::firstOrCreate(
            [
                'nama_kegiatan' => 'Pencacahan Survei Angkatan Kerja Nasional (SAKERNAS)',
                'pegawai_id'    => $anggota->id,
                'tahun'         => $tahun,
                'bulan'         => $bulan,
            ],
            [
                'satuan'               => 'Dokumen',
                'jenis_pekerjaan'      => 'utama',
                'target_kuantitas'     => 10,
                'realisasi_kuantitas'  => 8,
                'persentase_kuantitas' => 80.0,
                'asal_tim'             => 'Tim Statistik Sosial',
                'tim_id'               => $timSosial->id,
                'created_by'           => $ketua->id,
                'keterangan'           => 'Pencacahan blok sensus terpilih di Kecamatan Ruteng.',
                'estimasi_waktu'       => 45, // 45 jam
                'tanggal_mulai'        => $now->copy()->startOfMonth()->addDays(2),
                'tanggal_selesai'      => $now->copy()->startOfMonth()->addDays(15),
                'status'               => 'berjalan',
                'catatan_realisasi'    => 'Telah mencacah 8 dari 10 blok sensus.',
                'tanggal_realisasi'    => $now->copy()->subDays(2),
                'kualitas'             => 90.0,
                'nilai_ckp'            => 85.0,
                'penilai_id'           => $ketua->id,
                'status_penilaian'     => 'sudah_dinilai',
            ]
        );

        // Pekerjaan Utama 2: Ditugaskan ke Ketua Tim sendiri oleh dirinya sendiri
        AlokasiPekerjaan::firstOrCreate(
            [
                'nama_kegiatan' => 'Pengolahan dan Validasi Data Susenas Maret',
                'pegawai_id'    => $ketua->id,
                'tahun'         => $tahun,
                'bulan'         => $bulan,
            ],
            [
                'satuan'               => 'Dokumen',
                'jenis_pekerjaan'      => 'utama',
                'target_kuantitas'     => 25,
                'realisasi_kuantitas'  => 25,
                'persentase_kuantitas' => 100.0,
                'asal_tim'             => 'Tim Statistik Sosial',
                'tim_id'               => $timSosial->id,
                'created_by'           => $ketua->id,
                'keterangan'           => 'Pengolahan data entry dan clean dataset modul konsumsi.',
                'estimasi_waktu'       => 60, // 60 jam
                'tanggal_mulai'        => $now->copy()->startOfMonth()->addDays(5),
                'tanggal_selesai'      => $now->copy()->startOfMonth()->addDays(22),
                'status'               => 'selesai',
                'catatan_realisasi'    => 'Pengolahan rampung 100% tepat waktu.',
                'tanggal_realisasi'    => $now->copy()->subDay(),
                'kualitas'             => 95.0,
                'nilai_ckp'            => 97.5,
                'penilai_id'           => $kepala->id, // Dinilai oleh Kepala BPS karena pegawai adalah Ketua Tim
                'status_penilaian'     => 'sudah_dinilai',
            ]
        );

        // Pekerjaan Tambahan: Diinput mandiri oleh Anggota melalui CKP Saya
        AlokasiPekerjaan::firstOrCreate(
            [
                'nama_kegiatan' => 'Panitia Peringatan Hari Statistik Nasional (HSN)',
                'pegawai_id'    => $anggota->id,
                'tahun'         => $tahun,
                'bulan'         => $bulan,
            ],
            [
                'satuan'               => 'Kegiatan',
                'jenis_pekerjaan'      => 'tambahan',
                'target_kuantitas'     => 1,
                'realisasi_kuantitas'  => 1,
                'persentase_kuantitas' => 100.0,
                'asal_tim'             => 'Tim Statistik Sosial',
                'tim_id'               => $timSosial->id,
                'created_by'           => $anggota->id,
                'keterangan'           => 'Mengkoordinir lomba infografis dan sosialisasi data ke sekolah.',
                'estimasi_waktu'       => 20, // 20 jam
                'tanggal_mulai'        => $now->copy()->startOfMonth()->addDays(10),
                'tanggal_selesai'      => $now->copy()->startOfMonth()->addDays(25),
                'status'               => 'selesai',
                'catatan_realisasi'    => 'Acara terselenggara lancar.',
                'tanggal_realisasi'    => $now->copy()->subDays(1),
                'kualitas'             => 88.0,
                'nilai_ckp'            => 94.0,
                'penilai_id'           => $kepala->id, // Pekerjaan tambahan dinilai Kepala BPS
                'status_penilaian'     => 'sudah_dinilai',
            ]
        );

        // Pekerjaan Utama 3 untuk Budi
        AlokasiPekerjaan::firstOrCreate(
            [
                'nama_kegiatan' => 'Penyusunan Publikasi Kabupaten Manggarai Dalam Angka',
                'pegawai_id'    => $budi->id,
                'tahun'         => $tahun,
                'bulan'         => $bulan,
            ],
            [
                'satuan'               => 'Buku Publikasi',
                'jenis_pekerjaan'      => 'utama',
                'target_kuantitas'     => 1,
                'realisasi_kuantitas'  => 1,
                'persentase_kuantitas' => 100.0,
                'asal_tim'             => 'Tim Statistik Sosial',
                'tim_id'               => $timSosial->id,
                'created_by'           => $ketua->id,
                'keterangan'           => 'Penyusunan bab sosial dan ketenagakerjaan.',
                'estimasi_waktu'       => 50, // 50 jam
                'tanggal_mulai'        => $now->copy()->startOfMonth()->addDays(1),
                'tanggal_selesai'      => $now->copy()->startOfMonth()->addDays(18),
                'status'               => 'selesai',
                'catatan_realisasi'    => 'Telah terbit ber-ISBN.',
                'kualitas'             => 92.0,
                'nilai_ckp'            => 96.0,
                'penilai_id'           => $ketua->id,
                'status_penilaian'     => 'sudah_dinilai',
            ]
        );

        // 7. Seed modul e-Surat (Unit Kerja, Jenis Surat, Template, contoh surat)
        $this->call(ESuratSeeder::class);
    }
}
