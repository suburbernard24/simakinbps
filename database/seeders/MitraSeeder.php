<?php

namespace Database\Seeders;

use App\Models\AlokasiMitra;
use App\Models\Mitra;
use App\Models\PengaturanHonorMitra;
use App\Models\PenilaianMitra;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MitraSeeder extends Seeder
{
    /**
     * Seed data master mitra, batas honor, alokasi penugasan dan evaluasi kinerja mitra.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $ketua = User::where('username', 'ketua')->first();
        $anggota = User::where('username', 'anggota')->first();
        $tim = Team::first();

        // 1. Pengaturan Default Maksimal Honor Mitra (SBM BPS)
        PengaturanHonorMitra::firstOrCreate(
            ['tahun' => null, 'bulan' => null],
            [
                'maksimal_honor' => 4500000,
                'updated_by'     => $admin?->id,
                'keterangan'     => 'Standar Biaya Masukan (SBM) Batas Maksimal Honor Mitra BPS TA 2026',
            ]
        );

        // 2. Data Master Mitra Statistik
        $mitra1 = Mitra::firstOrCreate(
            ['sobat_id' => '5310-M001'],
            [
                'nama'           => 'Yohanes Bosco',
                'nik'            => '5310123456780001',
                'jenis_kelamin'  => 'Laki-laki',
                'telepon'        => '081234567890',
                'email'          => 'yohanes.bosco@mitra.bps.go.id',
                'provinsi'       => 'Nusa Tenggara Timur',
                'kabupaten'      => 'Kabupaten Manggarai',
                'posisi_default' => 'Petugas Pencacah Lapangan (PCL)',
                'alamat'         => 'Kel. Watu, Kec. Langke Rembong, Kab. Manggarai',
                'is_active'      => true,
            ]
        );

        $mitra2 = Mitra::firstOrCreate(
            ['sobat_id' => '5310-M002'],
            [
                'nama'           => 'Maria Goreti',
                'nik'            => '5310123456780002',
                'jenis_kelamin'  => 'Perempuan',
                'telepon'        => '081234567891',
                'email'          => 'maria.goreti@mitra.bps.go.id',
                'provinsi'       => 'Nusa Tenggara Timur',
                'kabupaten'      => 'Kabupaten Manggarai',
                'posisi_default' => 'Petugas Pemeriksa Lapangan (PML)',
                'alamat'         => 'Kel. Pau, Kec. Langke Rembong, Kab. Manggarai',
                'is_active'      => true,
            ]
        );

        $mitra3 = Mitra::firstOrCreate(
            ['sobat_id' => '5310-M003'],
            [
                'nama'           => 'Fransiskus Xaverius',
                'nik'            => '5310123456780003',
                'jenis_kelamin'  => 'Laki-laki',
                'telepon'        => '081234567892',
                'email'          => 'fransiskus@mitra.bps.go.id',
                'provinsi'       => 'Nusa Tenggara Timur',
                'kabupaten'      => 'Kabupaten Manggarai',
                'posisi_default' => 'Petugas Pencacah Lapangan (PCL)',
                'alamat'         => 'Desa Poco Likang, Kec. Ruteng, Kab. Manggarai',
                'is_active'      => true,
            ]
        );

        $mitra4 = Mitra::firstOrCreate(
            ['sobat_id' => '5310-M004'],
            [
                'nama'           => 'Theresia Avila',
                'nik'            => '5310123456780004',
                'jenis_kelamin'  => 'Perempuan',
                'telepon'        => '081234567893',
                'email'          => 'theresia@mitra.bps.go.id',
                'provinsi'       => 'Nusa Tenggara Timur',
                'kabupaten'      => 'Kabupaten Manggarai',
                'posisi_default' => 'Petugas Pengolahan Data',
                'alamat'         => 'Kel. Karot, Kec. Langke Rembong, Kab. Manggarai',
                'is_active'      => true,
            ]
        );

        // 3. Contoh Alokasi Pekerjaan Mitra dan Penilaian
        $now = Carbon::now();
        $tahun = $now->year;
        $bulan = $now->month;

        if ($ketua && $tim) {
            // Alokasi 1 untuk Yohanes Bosco (Selesai & Sudah Dinilai oleh Ketua Tim)
            $alokasi1 = AlokasiMitra::firstOrCreate(
                [
                    'mitra_id'      => $mitra1->id,
                    'nama_kegiatan' => 'Survei Sosial Ekonomi Nasional (Susenas) Maret',
                    'tahun'         => $tahun,
                    'bulan'         => $bulan,
                ],
                [
                    'tim_id'           => $tim->id,
                    'created_by'       => $ketua->id,
                    'kegiatan'         => 'Pencacahan Lapangan Kuesioner Konsumsi dan Kor',
                    'posisi'           => 'Petugas Pencacah Lapangan (PCL)',
                    'satuan'           => 'Dokumen',
                    'target_kuantitas' => 10,
                    'honor'            => 2200000,
                    'tanggal_mulai'    => $now->copy()->startOfMonth()->addDays(2),
                    'tanggal_selesai'  => $now->copy()->startOfMonth()->addDays(20),
                    'status'           => 'selesai',
                    'provinsi'         => $mitra1->provinsi,
                    'kabupaten'        => $mitra1->kabupaten,
                    'keterangan'       => 'Blok sensus di wilayah Langke Rembong.',
                ]
            );

            // Penilaian 1 oleh Ketua Tim
            PenilaianMitra::firstOrCreate(
                [
                    'alokasi_mitra_id' => $alokasi1->id,
                    'penilai_id'       => $ketua->id,
                ],
                [
                    'mitra_id' => $mitra1->id,
                    'bintang'  => 5,
                    'komentar' => 'Pencacahan sangat rapi, responden kooperatif, dan seluruh target selesai tepat waktu.',
                ]
            );

            // Alokasi 2 untuk Maria Goreti (Selesai & Dinilai oleh Anggota Tim)
            $alokasi2 = AlokasiMitra::firstOrCreate(
                [
                    'mitra_id'      => $mitra2->id,
                    'nama_kegiatan' => 'Survei Angkatan Kerja Nasional (Sakernas)',
                    'tahun'         => $tahun,
                    'bulan'         => $bulan,
                ],
                [
                    'tim_id'           => $tim->id,
                    'created_by'       => $ketua->id,
                    'kegiatan'         => 'Pemeriksaan Lapangan dan Validasi Kuesioner',
                    'posisi'           => 'Petugas Pemeriksa Lapangan (PML)',
                    'satuan'           => 'Dokumen',
                    'target_kuantitas' => 15,
                    'honor'            => 1800000,
                    'tanggal_mulai'    => $now->copy()->startOfMonth()->addDays(5),
                    'tanggal_selesai'  => $now->copy()->startOfMonth()->addDays(22),
                    'status'           => 'selesai',
                    'provinsi'         => $mitra2->provinsi,
                    'kabupaten'        => $mitra2->kabupaten,
                    'keterangan'       => 'Pemeriksaan hasil pencacahan 3 PCL.',
                ]
            );

            if ($anggota) {
                // Penilaian 2 oleh Anggota Tim
                PenilaianMitra::firstOrCreate(
                    [
                        'alokasi_mitra_id' => $alokasi2->id,
                        'penilai_id'       => $anggota->id,
                    ],
                    [
                        'mitra_id' => $mitra2->id,
                        'bintang'  => 4,
                        'komentar' => 'Pemeriksaan isian dokumen kuesioner sangat teliti dan koordinasi tim pencacah lapangan berjalan lancar.',
                    ]
                );
            }

            // Alokasi 3 untuk Fransiskus Xaverius (Berjalan, Belum Dinilai)
            AlokasiMitra::firstOrCreate(
                [
                    'mitra_id'      => $mitra3->id,
                    'nama_kegiatan' => 'Survei Komoditas Hortikultura Bulanan',
                    'tahun'         => $tahun,
                    'bulan'         => $bulan,
                ],
                [
                    'tim_id'           => $tim->id,
                    'created_by'       => $ketua->id,
                    'kegiatan'         => 'Pendataan Petani Produsen Sayuran dan Buah',
                    'posisi'           => 'Petugas Pencacah Lapangan (PCL)',
                    'satuan'           => 'Responden',
                    'target_kuantitas' => 8,
                    'honor'            => 1500000,
                    'tanggal_mulai'    => $now->copy()->startOfMonth()->addDays(10),
                    'tanggal_selesai'  => $now->copy()->endOfMonth(),
                    'status'           => 'berjalan',
                    'provinsi'         => $mitra3->provinsi,
                    'kabupaten'        => $mitra3->kabupaten,
                    'keterangan'       => 'Wilayah sentra hortikultura Kecamatan Ruteng.',
                ]
            );
        }
    }
}
