<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use App\Models\NomorSurat;
use App\Models\Surat;
use App\Models\SuratDetail;
use App\Models\SuratPegawai;
use App\Models\SuratPenerima;
use App\Models\SuratStatusHistory;
use App\Models\TemplateSurat;
use App\Models\UnitKerja;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ESuratSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Unit Kerja / Fungsi BPS
        $unitKerjas = [
            ['kode' => 'TU', 'nama' => 'Subbagian Umum / Tata Usaha', 'keterangan' => 'Urusan kepegawaian, keuangan, dan tata usaha'],
            ['kode' => 'SOSIAL', 'nama' => 'Fungsi Statistik Sosial', 'keterangan' => 'Statistik kependudukan, ketenagakerjaan, dan kesejahteraan rakyat'],
            ['kode' => 'PRODUKSI', 'nama' => 'Fungsi Statistik Produksi', 'keterangan' => 'Statistik pertanian, industri, dan pertambangan'],
            ['kode' => 'DISTRIBUSI', 'nama' => 'Fungsi Statistik Distribusi', 'keterangan' => 'Statistik harga, pariwisata, dan perdagangan'],
            ['kode' => 'NERWILIS', 'nama' => 'Fungsi Neraca Wilayah dan Analisis Statistik', 'keterangan' => 'PDRB dan analisis statistik wilayah'],
            ['kode' => 'IPDS', 'nama' => 'Fungsi Integrasi Pengolahan dan Diseminasi Statistik', 'keterangan' => 'Pengolahan data, TI, dan pelayanan statistik terpadu'],
        ];

        $unitKerjaModels = [];
        foreach ($unitKerjas as $u) {
            $unitKerjaModels[$u['kode']] = UnitKerja::firstOrCreate(
                ['kode' => $u['kode']],
                ['nama' => $u['nama'], 'keterangan' => $u['keterangan'], 'status' => true]
            );
        }

        // Assign unit kerja to existing users
        $tu = $unitKerjaModels['TU'] ?? null;
        $sosial = $unitKerjaModels['SOSIAL'] ?? null;
        $ipds = $unitKerjaModels['IPDS'] ?? null;

        User::where('role', 'admin')->update(['unit_kerja_id' => $tu?->id]);
        User::where('role', 'kepala_bps')->update(['unit_kerja_id' => $tu?->id]);
        User::where('role', 'ketua_tim')->update(['unit_kerja_id' => $sosial?->id]);
        User::where('role', 'pegawai')->update(['unit_kerja_id' => $ipds?->id]);

        // 2. Seed Jenis Surat
        $jenisSurats = [
            [
                'kode' => 'ST',
                'nama' => 'Surat Tugas',
                'format_nomor' => 'B-{nomor_urut}/{kode_satker}/{kode_klasifikasi}/{tahun}',
                'kode_klasifikasi_default' => 'VS.330',
                'deskripsi' => 'Surat penugasan resmi pegawai untuk dinas lapangan, rapat, atau pelatihan',
            ],
            [
                'kode' => 'SK',
                'nama' => 'Surat Keputusan (SK)',
                'format_nomor' => '{nomor_urut}/{kode_satker}/SK/{tahun}',
                'kode_klasifikasi_default' => 'KP.510',
                'deskripsi' => 'Surat penetapan pimpinan tentang tim kerja, penunjukan pejabat, atau program',
            ],
            [
                'kode' => 'B',
                'nama' => 'Surat Keluar',
                'format_nomor' => 'B-{nomor_urut}/{kode_satker}/{kode_klasifikasi}/{tahun}',
                'kode_klasifikasi_default' => 'PR.100',
                'deskripsi' => 'Surat dinas resmi eksternal kepada instansi/lembaga lain',
            ],
            [
                'kode' => 'BA',
                'nama' => 'Berita Acara',
                'format_nomor' => 'BA-{nomor_urut}/{kode_satker}/{kode_klasifikasi}/{tahun}',
                'kode_klasifikasi_default' => 'KU.100',
                'deskripsi' => 'Dokumen resmi pembuktian suatu peristiwa, pemeriksaan, atau serah terima pekerjaan',
            ],
            [
                'kode' => 'UND',
                'nama' => 'Undangan',
                'format_nomor' => 'B-{nomor_urut}/{kode_satker}/UND/{tahun}',
                'kode_klasifikasi_default' => 'PR.100',
                'deskripsi' => 'Surat undangan pertemuan resmi, rapat koordinasi, atau sosialisasi',
            ],
            [
                'kode' => 'ND',
                'nama' => 'Nota Dinas',
                'format_nomor' => 'ND-{nomor_urut}/{kode_satker}/{kode_klasifikasi}/{tahun}',
                'kode_klasifikasi_default' => 'OT.000',
                'deskripsi' => 'Surat komunikasi kedinasan internal antar unit kerja/fungsi di lingkungan BPS',
            ],
        ];

        $jenisModels = [];
        foreach ($jenisSurats as $js) {
            $jenisModels[$js['kode']] = JenisSurat::firstOrCreate(
                ['kode' => $js['kode']],
                $js
            );
        }

        // 3. Seed Template Surat
        $templates = [
            [
                'jenis_surat_id' => $jenisModels['ST']->id,
                'kode_template' => 'TPL-ST-01',
                'nama_template' => 'Surat Tugas Lapangan & Survei BPS',
                'kop_surat' => 'BADAN PUSAT STATISTIK KABUPATEN MANGGARAI',
                'isi_template' => "Kepala Badan Pusat Statistik Kabupaten Manggarai dengan ini menugaskan:\n\nKepada Pegawai terlampir untuk melaksanakan:\n{uraian_kegiatan}\n\nTempat Pelaksanaan : {tempat}\nTanggal Pelaksanaan: {tanggal_kegiatan}\n\nDemikian surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.",
                'penutup' => "Ruteng, {tanggal_surat}\nKepala Badan Pusat Statistik Kabupaten Manggarai",
            ],
            [
                'jenis_surat_id' => $jenisModels['SK']->id,
                'kode_template' => 'TPL-SK-01',
                'nama_template' => 'Keputusan Kepala BPS Penetapan Tim Kerja',
                'kop_surat' => 'BADAN PUSAT STATISTIK KABUPATEN MANGGARAI',
                'isi_template' => "KEPUTUSAN KEPALA BADAN PUSAT STATISTIK KABUPATEN MANGGARAI\nNOMOR: {nomor_surat}\nTENTANG\n{perihal}\n\nMenimbang:\n{dasar_penetapan}\n\nMEMUTUSKAN:\nMenetapkan nama-nama pegawai yang tercantum dalam lampiran keputusan ini sebagai tim kerja pelaksana.",
                'penutup' => "Ditetapkan di Ruteng pada tanggal {tanggal_surat}\nKepala Badan Pusat Statistik Kabupaten Manggarai",
            ],
            [
                'jenis_surat_id' => $jenisModels['B']->id,
                'kode_template' => 'TPL-B-01',
                'nama_template' => 'Surat Keluar Dinas Resmi',
                'kop_surat' => 'BADAN PUSAT STATISTIK KABUPATEN MANGGARAI',
                'isi_template' => "Nomor : {nomor_surat}\nSifat : Biasa\nLampiran : Terlampir\nHal : {perihal}\n\nYth. {tujuan}\nDi Tempat\n\nSehubungan dengan pelaksanaan program statistik terpadu di Kabupaten Manggarai, bersama ini kami sampaikan hal-hal sebagai berikut:\n\n{uraian_kegiatan}\n\nDemikian surat ini kami sampaikan, atas perhatian dan kerja samanya kami ucapkan terima kasih.",
                'penutup' => "Kepala Badan Pusat Statistik Kabupaten Manggarai",
            ],
            [
                'jenis_surat_id' => $jenisModels['BA']->id,
                'kode_template' => 'TPL-BA-01',
                'nama_template' => 'Berita Acara Pemeriksaan & Serah Terima',
                'kop_surat' => 'BADAN PUSAT STATISTIK KABUPATEN MANGGARAI',
                'isi_template' => "Pada hari ini, tanggal {tanggal_kegiatan}, bertempat di {tempat}, kami yang bertanda tangan di bawah ini:\n1. Pihak Pertama\n2. Pihak Kedua\n\nTelah melaksanakan pemeriksaan dan serah terima dokumen/pekerjaan dengan uraian sebagai berikut:\n{uraian_kegiatan}\n\nDemikian Berita Acara ini dibuat dalam rangkap yang cukup untuk dipergunakan sebagaimana mestinya.",
                'penutup' => "Pihak Pertama & Pihak Kedua",
            ],
        ];

        $tplModels = [];
        foreach ($templates as $tpl) {
            $tplModels[$tpl['kode_template']] = TemplateSurat::firstOrCreate(
                ['kode_template' => $tpl['kode_template']],
                $tpl
            );
        }

        // 4. Seed Sample Surat for testing & workflow demonstration
        $pemohon = User::where('role', 'pegawai')->first() ?? User::first();
        $ketua = User::where('role', 'ketua_tim')->first() ?? $pemohon;
        $kepala = User::where('role', 'kepala_bps')->first() ?? $pemohon;

        if ($pemohon && $jenisModels['ST']) {
            // Surat 1: Selesai dengan Nomor Surat Otomatis
            $surat1 = Surat::firstOrCreate(
                ['nomor_surat' => 'B-001/53131/VS.330/2026'],
                [
                    'jenis_surat_id' => $jenisModels['ST']->id,
                    'template_surat_id' => $tplModels['TPL-ST-01']->id,
                    'user_id' => $pemohon->id,
                    'unit_kerja_id' => $sosial->id,
                    'tanggal_pengajuan' => Carbon::now()->subDays(5),
                    'tanggal_surat' => Carbon::now()->subDays(3),
                    'perihal' => 'Surat Tugas Pencacahan Survei Sosial Ekonomi Nasional (Susenas) 2026',
                    'fungsi' => 'Statistik Sosial',
                    'status' => 'selesai',
                    'kode_klasifikasi' => 'VS.330',
                    'kode_satker' => '53131',
                ]
            );

            SuratDetail::firstOrCreate(
                ['surat_id' => $surat1->id],
                [
                    'tanggal_kegiatan' => Carbon::now()->subDays(2),
                    'tanggal_selesai' => Carbon::now()->addDays(5),
                    'tempat_kegiatan' => 'Kecamatan Langke Rembong, Kab. Manggarai',
                    'tujuan_kegiatan' => 'Pendataan rumah tangga sampel Susenas 2026',
                    'uraian_kegiatan' => 'Melaksanakan pencacahan lapangan terhadap 150 rumah tangga sampel di wilayah Kecamatan Langke Rembong sesuai SOP BPS.',
                    'keterangan' => 'Biaya dibebankan pada DIPA BPS Kabupaten Manggarai Tahun 2026.',
                ]
            );

            // Assign multiple pegawais
            $allUsers = User::take(3)->get();
            $rolesList = ['Ketua Tim / Koordinator', 'Petugas Pengawas', 'Petugas Pencacah'];
            foreach ($allUsers as $idx => $u) {
                SuratPegawai::firstOrCreate(
                    ['surat_id' => $surat1->id, 'pegawai_id' => $u->id],
                    ['peran' => $rolesList[$idx] ?? 'Anggota', 'urutan' => $idx + 1]
                );
            }

            NomorSurat::firstOrCreate(
                ['surat_id' => $surat1->id],
                [
                    'jenis_surat_id' => $jenisModels['ST']->id,
                    'tahun' => 2026,
                    'nomor_urut' => 1,
                    'kode_satker' => '53131',
                    'kode_klasifikasi' => 'VS.330',
                    'nomor_lengkap' => 'B-001/53131/VS.330/2026',
                ]
            );

            SuratStatusHistory::firstOrCreate(
                ['surat_id' => $surat1->id, 'status' => 'draft'],
                ['user_id' => $pemohon->id, 'catatan' => 'Draft surat tugas dibuat']
            );
            SuratStatusHistory::firstOrCreate(
                ['surat_id' => $surat1->id, 'status' => 'diajukan'],
                ['user_id' => $pemohon->id, 'catatan' => 'Surat diajukan dan diproses (Verifikasi via Srikandi)']
            );
            SuratStatusHistory::firstOrCreate(
                ['surat_id' => $surat1->id, 'status' => 'terverifikasi'],
                ['user_id' => $ketua->id, 'catatan' => 'Data dan penugasan pegawai sesuai']
            );
            SuratStatusHistory::firstOrCreate(
                ['surat_id' => $surat1->id, 'status' => 'selesai'],
                ['user_id' => $kepala->id, 'catatan' => 'Disetujui Kepala BPS dan nomor diterbitkan']
            );

            // Surat 2: Menunggu Persetujuan (Kepala BPS)
            $surat2 = Surat::firstOrCreate(
                ['perihal' => 'Surat Keputusan Pembentukan Tim Koordinasi Sensus Pertanian Wilayah Manggarai'],
                [
                    'jenis_surat_id' => $jenisModels['SK']->id,
                    'template_surat_id' => $tplModels['TPL-SK-01']->id,
                    'user_id' => $ketua->id,
                    'unit_kerja_id' => $unitKerjaModels['PRODUKSI']->id,
                    'tanggal_pengajuan' => Carbon::now()->subDays(1),
                    'tanggal_surat' => Carbon::now(),
                    'fungsi' => 'Statistik Produksi',
                    'status' => 'persetujuan',
                    'kode_klasifikasi' => 'KP.510',
                    'kode_satker' => '53131',
                ]
            );

            SuratDetail::firstOrCreate(
                ['surat_id' => $surat2->id],
                [
                    'pejabat_penetap' => 'Kepala Badan Pusat Statistik Kabupaten Manggarai',
                    'dasar_penetapan' => "Menimbang: a. Bahwa demi kelancaran koordinasi Sensus Pertanian; b. Perlu ditetapkan tim kerja teknis.\nMengingat: UU No. 16 Tahun 1997 tentang Statistik.",
                    'uraian_kegiatan' => 'Menetapkan pembentukan tim koordinasi sensus pertanian tingkat satker BPS Kabupaten Manggarai tahun 2026.',
                ]
            );

            SuratPegawai::firstOrCreate(
                ['surat_id' => $surat2->id, 'pegawai_id' => $ketua->id],
                ['peran' => 'Ketua Tim', 'urutan' => 1]
            );
            SuratPegawai::firstOrCreate(
                ['surat_id' => $surat2->id, 'pegawai_id' => $pemohon->id],
                ['peran' => 'Sekretaris & Teknis', 'urutan' => 2]
            );

            SuratStatusHistory::firstOrCreate(
                ['surat_id' => $surat2->id, 'status' => 'verifikasi'],
                ['user_id' => $ketua->id, 'catatan' => 'Telah diverifikasi, diteruskan ke Pimpinan']
            );

            // Surat 3: Menunggu Verifikasi
            $surat3 = Surat::firstOrCreate(
                ['perihal' => 'Permohonan Data Koordinasi Lintas Sektoral dengan Bapelitbangda Manggarai'],
                [
                    'jenis_surat_id' => $jenisModels['B']->id,
                    'template_surat_id' => $tplModels['TPL-B-01']->id,
                    'user_id' => $pemohon->id,
                    'unit_kerja_id' => $unitKerjaModels['IPDS']->id,
                    'tanggal_pengajuan' => Carbon::now(),
                    'tanggal_surat' => Carbon::now(),
                    'fungsi' => 'Integrasi Pengolahan dan Diseminasi Statistik',
                    'status' => 'verifikasi',
                    'kode_klasifikasi' => 'PR.100',
                    'kode_satker' => '53131',
                ]
            );

            SuratDetail::firstOrCreate(
                ['surat_id' => $surat3->id],
                [
                    'uraian_kegiatan' => 'Permintaan data indikator makro ekonomi daerah untuk kebutuhan penyusunan Publikasi Daerah Dalam Angka 2026.',
                ]
            );

            SuratPenerima::firstOrCreate(
                ['surat_id' => $surat3->id, 'nama_penerima' => 'Kepala Bapelitbangda Kabupaten Manggarai'],
                [
                    'instansi' => 'Bapelitbangda Kabupaten Manggarai',
                    'alamat' => 'Jl. Motang Rua No. 1, Ruteng',
                    'email' => 'bapelitbangda@manggaraikab.go.id',
                    'jenis_penerima' => 'Eksternal',
                ]
            );

            SuratStatusHistory::firstOrCreate(
                ['surat_id' => $surat3->id, 'status' => 'diajukan'],
                ['user_id' => $pemohon->id, 'catatan' => 'Diajukan untuk verifikasi teknis']
            );
        }
    }
}
