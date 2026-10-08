<?php

namespace App\Http\Controllers;

use App\Models\HariKerjaAktif;
use App\Models\Team;
use App\Models\User;
use App\Services\KinerjaService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KinerjaController extends Controller
{
    /**
     * M. GRAFIK PERBANDINGAN SKOR KINERJA ANTAR PEGAWAI
     */
    public function grafik(Request $request)
    {
        $currentYear = (int) $request->input('tahun', Carbon::now()->year);
        $currentMonth = (int) $request->input('bulan', Carbon::now()->month);
        $timId = $request->filled('tim_id') ? (int) $request->tim_id : null;
        $jenisPekerjaan = $request->filled('jenis_pekerjaan') ? $request->jenis_pekerjaan : null;

        // Ambil data perbandingan seluruh pegawai
        $rekapPegawai = KinerjaService::rekapKinerjaSemuaPegawai($currentYear, $currentMonth, $timId, $jenisPekerjaan);

        // Ambil perbandingan antar tim (untuk visualisasi pelengkap)
        $rekapTim = KinerjaService::rekapKinerjaTim($currentYear, $currentMonth);

        $daftarTim = Team::where('is_active', true)->orderBy('nama_tim')->get();
        $hariKerja = HariKerjaAktif::getHariKerja($currentYear, $currentMonth);
        $jamKerjaAktif = HariKerjaAktif::getJamKerjaAktif($currentYear, $currentMonth);

        // Siapkan data JSON untuk Chart.js
        $chartLabels = [];
        $chartScores = [];
        $chartHours  = [];
        $chartCkps   = [];
        $backgroundColors = [];

        foreach ($rekapPegawai as $data) {
            $chartLabels[] = $data['pegawai']->name;
            $chartScores[] = $data['skor_kinerja'];
            $chartHours[]  = $data['total_estimasi_jam'];
            $chartCkps[]   = $data['nilai_ckp'];
            
            // Berikan warna variatif berdasarkan range skor
            if ($data['skor_kinerja'] >= 100) {
                $backgroundColors[] = '#10B981'; // Green (Optimal)
            } elseif ($data['skor_kinerja'] >= 80) {
                $backgroundColors[] = '#1565C0'; // BPS Blue (Baik)
            } elseif ($data['skor_kinerja'] >= 50) {
                $backgroundColors[] = '#F59E0B'; // Amber (Cukup)
            } else {
                $backgroundColors[] = '#EF4444'; // Red (Kurang)
            }
        }

        $daftarTahun = $this->getDaftarTahun();
        $daftarBulan = $this->getDaftarBulan();

        return view('kinerja.grafik', compact(
            'rekapPegawai',
            'rekapTim',
            'daftarTim',
            'currentYear',
            'currentMonth',
            'timId',
            'jenisPekerjaan',
            'hariKerja',
            'jamKerjaAktif',
            'chartLabels',
            'chartScores',
            'chartHours',
            'chartCkps',
            'backgroundColors',
            'daftarTahun',
            'daftarBulan'
        ));
    }
}
