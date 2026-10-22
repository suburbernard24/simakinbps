<?php

namespace App\Http\Controllers;

use App\Models\AlokasiPekerjaan;
use App\Models\HariKerjaAktif;
use App\Models\Team;
use App\Models\User;
use App\Services\KinerjaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Jika Admin -> Redirect ke Dashboard Admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $currentYear = (int) $request->input('tahun', Carbon::now()->year);
        $bulanInput = $request->input('bulan', Carbon::now()->month);
        $currentMonth = $bulanInput === 'all' ? 'all' : (int) $bulanInput;

        $daftarTahun = $this->getDaftarTahun();
        $daftarBulan = ['all' => 'Seluruh Bulan'] + $this->getDaftarBulan();

        // Global Chart & Calendar Data
        $calendarTasksQuery = AlokasiPekerjaan::with(['pegawai.tim', 'tim'])
            ->where(function ($q) use ($currentYear, $currentMonth) {
                if ($currentMonth === 'all') {
                    $q->where(function ($sq) use ($currentYear) {
                        $sq->where('tahun', $currentYear);
                    })->orWhere(function ($sq) use ($currentYear) {
                        $sq->whereNull('tahun')
                           ->whereYear('tanggal_mulai', $currentYear);
                    });
                } else {
                    $q->where(function ($sq) use ($currentYear, $currentMonth) {
                        $sq->where('tahun', $currentYear)->where('bulan', $currentMonth);
                    })->orWhere(function ($sq) use ($currentYear, $currentMonth) {
                        $sq->whereNull('tahun')
                           ->whereYear('tanggal_mulai', $currentYear)
                           ->whereMonth('tanggal_mulai', $currentMonth);
                    });
                }
            })->get();

        $calendarEvents = [];
        foreach ($calendarTasksQuery as $task) {
            if ($task->tanggal_mulai) {
                $endDate = $task->tanggal_selesai ? (clone $task->tanggal_selesai)->addDay() : (clone $task->tanggal_mulai)->addDay();
                $asalTim = $task->asal_tim ?: ($task->tim ? $task->tim->nama_tim : ($task->pegawai && $task->pegawai->tim ? $task->pegawai->tim->nama_tim : '-'));
                $calendarEvents[] = [
                    'id' => $task->id,
                    'title' => $task->nama_kegiatan,
                    'start' => $task->tanggal_mulai->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                    'pegawai' => $task->pegawai ? $task->pegawai->name : 'Unknown',
                    'asal_tim' => $asalTim,
                    'pekerjaan' => $task->nama_kegiatan,
                ];
            }
        }

        $globalRekapPegawai = KinerjaService::rekapKinerjaSemuaPegawai($currentYear, $currentMonth);
        $globalChartLabels = [];
        $globalChartPekerjaan = [];
        $globalChartKinerja = [];
        $globalChartCkps = [];
        foreach ($globalRekapPegawai as $rp) {
            $globalChartLabels[] = $rp['pegawai']->name;
            $globalChartPekerjaan[] = $rp['total_pekerjaan'];
            $globalChartKinerja[] = $rp['skor_kinerja'];
            $globalChartCkps[] = $rp['nilai_ckp'];
        }

        // 2. DASHBOARD KEPALA BPS
        if ($user->isKepala()) {
            $totalPegawai = User::where('is_active', true)->whereNotIn('role', ['admin', 'kepala_bps'])->count();
            $totalTim = Team::where('is_active', true)->count();

            $allTasks = AlokasiPekerjaan::where(function ($q) use ($currentYear, $currentMonth) {
                $q->where(function ($sq) use ($currentYear, $currentMonth) {
                    $sq->where('tahun', $currentYear)->where('bulan', $currentMonth);
                })->orWhere(function ($sq) use ($currentYear, $currentMonth) {
                    $sq->whereNull('tahun')
                       ->whereYear('tanggal_mulai', $currentYear)
                       ->whereMonth('tanggal_mulai', $currentMonth);
                });
            })->get();

            $totalPekerjaan = $allTasks->count();
            $pekerjaanSelesai = $allTasks->filter(fn($t) => $t->status_waktu === 'selesai')->count();
            $pekerjaanBerjalan = $allTasks->filter(fn($t) => $t->status_waktu === 'berjalan')->count();

            // Rekap kinerja seluruh pegawai
            $rekapPegawai = KinerjaService::rekapKinerjaSemuaPegawai($currentYear, $currentMonth);
            $rekapTim = KinerjaService::rekapKinerjaTim($currentYear, $currentMonth);

            $avgSkorKinerja = count($rekapPegawai) > 0 ? round(collect($rekapPegawai)->avg('skor_kinerja'), 2) : 0;
            $avgCkp = count($rekapPegawai) > 0 ? round(collect($rekapPegawai)->avg('nilai_ckp'), 2) : 0;

            // Chart data: Pegawai
            $chartPegawaiLabels = [];
            $chartPegawaiScores = [];
            $chartPegawaiCkps   = [];
            foreach (array_slice($rekapPegawai, 0, 10) as $rp) {
                $chartPegawaiLabels[] = $rp['pegawai']->name;
                $chartPegawaiScores[] = $rp['skor_kinerja'];
                $chartPegawaiCkps[]   = $rp['nilai_ckp'];
            }

            // Chart data: Tim
            $chartTimLabels = [];
            $chartTimScores = [];
            $chartTimCkps   = [];
            foreach ($rekapTim as $rt) {
                $chartTimLabels[] = $rt['tim']->singkatan ?? $rt['tim']->nama_tim;
                $chartTimScores[] = $rt['skor_kinerja'];
                $chartTimCkps[]   = $rt['avg_ckp'];
            }

            return view('dashboard.kepala', compact(
                'currentYear',
                'currentMonth',
                'totalPegawai',
                'totalTim',
                'totalPekerjaan',
                'pekerjaanSelesai',
                'pekerjaanBerjalan',
                'avgSkorKinerja',
                'avgCkp',
                'rekapPegawai',
                'rekapTim',
                'chartPegawaiLabels',
                'chartPegawaiScores',
                'chartPegawaiCkps',
                'chartTimLabels',
                'chartTimScores',
                'chartTimCkps',
                'daftarTahun',
                'daftarBulan',
                'calendarEvents',
                'globalChartLabels',
                'globalChartPekerjaan',
                'globalChartKinerja',
                'globalChartCkps'
            ));
        }

        // 3. DASHBOARD KETUA TIM
        if ($user->isKetuaTim()) {
            $tim = $user->tim;
            $timId = $tim ? $tim->id : null;

            // Kinerja pribadi ketua tim (user itu sendiri)
            $kinerja = KinerjaService::hitungKinerjaPegawai($user->id, $currentYear, $currentMonth);
            $personalTasks = $kinerja['items'];

            // Tugas tim
            $teamTasks = AlokasiPekerjaan::where(function ($q) use ($currentYear, $currentMonth) {
                $q->where(function ($sq) use ($currentYear, $currentMonth) {
                    $sq->where('tahun', $currentYear)->where('bulan', $currentMonth);
                })->orWhere(function ($sq) use ($currentYear, $currentMonth) {
                    $sq->whereNull('tahun')
                       ->whereYear('tanggal_mulai', $currentYear)
                       ->whereMonth('tanggal_mulai', $currentMonth);
                });
            })->where(function ($q) use ($timId, $user) {
                if ($timId) {
                    $q->where('tim_id', $timId)->orWhere('created_by', $user->id);
                } else {
                    $q->where('created_by', $user->id);
                }
            })->get();

            $totalUtama = $teamTasks->where('jenis_pekerjaan', 'utama')->count();
            $totalTambahan = $teamTasks->where('jenis_pekerjaan', 'tambahan')->count();
            $pekerjaanBerjalan = $teamTasks->filter(fn($t) => $t->status_waktu === 'berjalan')->count();
            $pekerjaanSelesai = $teamTasks->filter(fn($t) => $t->status_waktu === 'selesai')->count();
            $totalTarget = (float) $teamTasks->sum('target_kuantitas');
            $totalRealisasi = (float) $teamTasks->sum('realisasi_kuantitas');

            // Kinerja anggota dalam tim
            $rekapTimPegawai = KinerjaService::rekapKinerjaSemuaPegawai($currentYear, $currentMonth, $timId);

            $timSkorKinerja = count($rekapTimPegawai) > 0 ? round(collect($rekapTimPegawai)->avg('skor_kinerja'), 2) : 0;
            $timCkp = count($rekapTimPegawai) > 0 ? round(collect($rekapTimPegawai)->avg('nilai_ckp'), 2) : 0;

            // Chart data
            $chartLabels = [];
            $chartScores = [];
            $chartCkps   = [];
            foreach ($rekapTimPegawai as $p) {
                $chartLabels[] = $p['pegawai']->name;
                $chartScores[] = $p['skor_kinerja'];
                $chartCkps[]   = $p['nilai_ckp'];
            }

            return view('dashboard.ketua', compact(
                'currentYear',
                'currentMonth',
                'tim',
                'kinerja',
                'personalTasks',
                'totalUtama',
                'totalTambahan',
                'pekerjaanBerjalan',
                'pekerjaanSelesai',
                'totalTarget',
                'totalRealisasi',
                'timSkorKinerja',
                'timCkp',
                'rekapTimPegawai',
                'chartLabels',
                'chartScores',
                'chartCkps',
                'daftarTahun',
                'daftarBulan',
                'calendarEvents',
                'globalChartLabels',
                'globalChartPekerjaan',
                'globalChartKinerja',
                'globalChartCkps'
            ));
        }

        // 4. DASHBOARD PEGAWAI / ANGGOTA TIM
        $kinerja = KinerjaService::hitungKinerjaPegawai($user->id, $currentYear, $currentMonth);
        $personalTasks = $kinerja['items'];

        // Rekap anggota tim (sama seperti ketua)
        $tim = $user->tim;
        $timId = $tim ? $tim->id : null;
        $rekapTimPegawai = KinerjaService::rekapKinerjaSemuaPegawai($currentYear, $currentMonth, $timId);

        return view('dashboard.pegawai', compact(
            'currentYear',
            'currentMonth',
            'kinerja',
            'personalTasks',
            'rekapTimPegawai',
            'daftarTahun',
            'daftarBulan',
            'calendarEvents',
            'globalChartLabels',
            'globalChartPekerjaan',
            'globalChartKinerja',
            'globalChartCkps'
        ));
    }
}
