<?php

namespace App\Services;

use App\Models\AlokasiPekerjaan;
use App\Models\HariKerjaAktif;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;

class KinerjaService
{
    /**
     * Hitung ringkasan CKP dan Skor Kinerja seorang Pegawai untuk periode tertentu.
     */
    public static function hitungKinerjaPegawai(int $userId, int $tahun, $bulan): array
    {
        $pegawai = User::with('tim')->find($userId);

        $items = AlokasiPekerjaan::where('pegawai_id', $userId)
            ->where(function ($q) use ($tahun, $bulan) {
                if ($bulan === 'all' || $bulan === null || $bulan === 0) {
                    $q->where(function ($sq) use ($tahun) {
                        $sq->where('tahun', $tahun);
                    })->orWhere(function ($sq) use ($tahun) {
                        $sq->whereNull('tahun')
                           ->whereYear('tanggal_mulai', $tahun);
                    });
                } else {
                    $q->where(function ($sq) use ($tahun, $bulan) {
                        $sq->where('tahun', $tahun)->where('bulan', $bulan);
                    })->orWhere(function ($sq) use ($tahun, $bulan) {
                        $sq->whereNull('tahun')
                           ->whereYear('tanggal_mulai', $tahun)
                           ->whereMonth('tanggal_mulai', $bulan);
                    });
                }
            })
            ->get();

        $utama = $items->where('jenis_pekerjaan', 'utama');
        $tambahan = $items->where('jenis_pekerjaan', 'tambahan');

        $totalPekerjaan = $items->count();
        $totalTarget = (float) $items->sum('target_kuantitas');
        $totalRealisasi = (float) $items->sum('realisasi_kuantitas');
        $totalEstimasiJam = (float) $items->sum('estimasi_waktu');

        // Hari kerja dan jam kerja aktif bulanan
        $hariKerja = HariKerjaAktif::getHariKerja($tahun, $bulan);
        $jamKerjaAktif = HariKerjaAktif::getJamKerjaAktif($tahun, $bulan);

        // Skor Kinerja = Total Estimasi Waktu Kerja (Jam) / Total Jam Kerja Aktif Bulanan
        $skorKinerja = $jamKerjaAktif > 0 ? round(($totalEstimasiJam / $jamKerjaAktif) * 100, 2) : 0;

        // Perhitungan CKP Bulanan:
        // c = Rata-rata Kuantitas = AVERAGE seluruh nilai Persentase Kuantitas (a)
        // d = Rata-rata Kualitas = AVERAGE seluruh nilai Persentase Kualitas (b)
        // CKP = (c + d) / 2
        $avgKuantitas = 0.0;
        $avgKualitas = 0.0;
        $nilaiCkp = 0.0;

        if ($totalPekerjaan > 0) {
            $avgKuantitas = round($items->avg('persentase_kuantitas') ?? 0, 2);
            $avgKualitas = round($items->avg('kualitas') ?? 0, 2);
            $nilaiCkp = round(($avgKuantitas + $avgKualitas) / 2, 2);
        }

        $berjalan = $items->filter(fn($i) => $i->status_waktu === 'berjalan')->count();
        $selesai  = $items->filter(fn($i) => $i->status_waktu === 'selesai')->count();
        $terjadwal = $items->filter(fn($i) => $i->status_waktu === 'terjadwal')->count();
        $terlambat = 0; // tidak digunakan lagi (digantikan oleh status_waktu)

        return [
            'pegawai'             => $pegawai,
            'tahun'               => $tahun,
            'bulan'               => $bulan,
            'items'               => $items,
            'utama'               => $utama,
            'tambahan'            => $tambahan,
            'total_pekerjaan'     => $totalPekerjaan,
            'jumlah_utama'        => $utama->count(),
            'jumlah_tambahan'     => $tambahan->count(),
            'total_target'        => $totalTarget,
            'total_realisasi'     => $totalRealisasi,
            'total_estimasi_jam'  => $totalEstimasiJam,
            'hari_kerja_aktif'    => $hariKerja,
            'jam_kerja_aktif'     => $jamKerjaAktif,
            'skor_kinerja'        => $skorKinerja,
            'avg_kuantitas'       => $avgKuantitas,
            'avg_kualitas'        => $avgKualitas,
            'nilai_ckp'           => $nilaiCkp,
            'berjalan'            => $berjalan,
            'selesai'             => $selesai,
            'terjadwal'           => $terjadwal,
            'terlambat'           => $terlambat,
        ];
    }

    /**
     * Rekap perbandingan kinerja seluruh pegawai untuk periode tertentu.
     */
    public static function rekapKinerjaSemuaPegawai(int $tahun, $bulan, ?int $timId = null, ?string $jenisPekerjaan = null): array
    {
        $pegawaiQuery = User::where('is_active', true)->whereNotIn('role', ['admin', 'kepala_bps']);

        if ($timId) {
            $pegawaiQuery->where('tim_id', $timId);
        }

        $pegawais = $pegawaiQuery->orderBy('name')->get();
        $hasil = [];

        foreach ($pegawais as $pegawai) {
            $data = static::hitungKinerjaPegawai($pegawai->id, $tahun, $bulan);

            // Jika ada filter jenis pekerjaan
            if ($jenisPekerjaan && in_array($jenisPekerjaan, ['utama', 'tambahan'])) {
                $filteredItems = $data['items']->where('jenis_pekerjaan', $jenisPekerjaan);
                $totalJam = (float) $filteredItems->sum('estimasi_waktu');
                $jamAktif = $data['jam_kerja_aktif'];
                $skorKinerja = $jamAktif > 0 ? round(($totalJam / $jamAktif) * 100, 2) : 0;
                $avgKuantitas = $filteredItems->count() > 0 ? round($filteredItems->avg('persentase_kuantitas') ?? 0, 2) : 0;
                $avgKualitas = $filteredItems->count() > 0 ? round($filteredItems->avg('kualitas') ?? 0, 2) : 0;
                $ckp = $filteredItems->count() > 0 ? round(($avgKuantitas + $avgKualitas) / 2, 2) : 0;

                $data['total_pekerjaan']    = $filteredItems->count();
                $data['total_target']       = (float) $filteredItems->sum('target_kuantitas');
                $data['total_realisasi']    = (float) $filteredItems->sum('realisasi_kuantitas');
                $data['total_estimasi_jam'] = $totalJam;
                $data['skor_kinerja']       = $skorKinerja;
                $data['avg_kuantitas']      = $avgKuantitas;
                $data['avg_kualitas']       = $avgKualitas;
                $data['nilai_ckp']          = $ckp;
            }

            $hasil[] = $data;
        }

        // Urutkan berdasarkan Skor Kinerja tertinggi (Ranking)
        usort($hasil, function ($a, $b) {
            return $b['skor_kinerja'] <=> $a['skor_kinerja'];
        });

        return $hasil;
    }

    /**
     * Rekap perbandingan kinerja antar tim.
     */
    public static function rekapKinerjaTim(int $tahun, $bulan): array
    {
        $tims = Team::where('is_active', true)->with('pegawais')->get();
        $hasil = [];

        foreach ($tims as $tim) {
            $anggotaIds = $tim->pegawais->pluck('id')->toArray();
            
            $items = AlokasiPekerjaan::whereIn('pegawai_id', $anggotaIds)
                ->where(function ($q) use ($tahun, $bulan) {
                    if ($bulan === 'all' || $bulan === null || $bulan === 0) {
                        $q->where(function ($sq) use ($tahun) {
                            $sq->where('tahun', $tahun);
                        })->orWhere(function ($sq) use ($tahun) {
                            $sq->whereNull('tahun')
                               ->whereYear('tanggal_mulai', $tahun);
                        });
                    } else {
                        $q->where(function ($sq) use ($tahun, $bulan) {
                            $sq->where('tahun', $tahun)->where('bulan', $bulan);
                        })->orWhere(function ($sq) use ($tahun, $bulan) {
                            $sq->whereNull('tahun')
                               ->whereYear('tanggal_mulai', $tahun)
                               ->whereMonth('tanggal_mulai', $bulan);
                        });
                    }
                })
                ->get();

            $totalJam = (float) $items->sum('estimasi_waktu');
            $jamAktif = HariKerjaAktif::getJamKerjaAktif($tahun, $bulan);
            $totalPegawai = count($anggotaIds);
            
            $totalJamKapasitasTim = $totalPegawai > 0 ? ($jamAktif * $totalPegawai) : $jamAktif;
            $skorKinerjaTim = $totalJamKapasitasTim > 0 ? round(($totalJam / $totalJamKapasitasTim) * 100, 2) : 0;

            $avgCkp = $items->count() > 0 ? round((($items->avg('persentase_kuantitas') ?? 0) + ($items->avg('kualitas') ?? 0)) / 2, 2) : 0;

            $hasil[] = [
                'tim'               => $tim,
                'total_pegawai'     => $totalPegawai,
                'total_pekerjaan'   => $items->count(),
                'total_target'      => (float) $items->sum('target_kuantitas'),
                'total_realisasi'   => (float) $items->sum('realisasi_kuantitas'),
                'total_jam'         => $totalJam,
                'skor_kinerja'      => $skorKinerjaTim,
                'avg_ckp'           => $avgCkp,
                'selesai'           => $items->filter(fn($i) => $i->status_waktu === 'selesai')->count(),
                'berjalan'          => $items->filter(fn($i) => $i->status_waktu === 'berjalan')->count(),
            ];
        }

        return $hasil;
    }
}
