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

class CkpController extends Controller
{

    // =========================================================================
    // 1. CKP SAYA (Pekerjaan Utama, Pekerjaan Tambahan, Realisasi)
    // =========================================================================

    public function index(Request $request)
    {
        $user = Auth::user();
        $currentYear = (int) $request->input('tahun', Carbon::now()->year);
        $currentMonth = (int) $request->input('bulan', Carbon::now()->month);

        // Ambil data dari KinerjaService untuk konsistensi formula
        $kinerja = KinerjaService::hitungKinerjaPegawai($user->id, $currentYear, $currentMonth);

        $daftarTahun = $this->getDaftarTahun();
        $daftarBulan = $this->getDaftarBulan();

        // Untuk kompatibilitas tampilan blade
        $ckp = $kinerja['utama'];
        $ckp_tambahan = $kinerja['tambahan'];
        $totalTarget = $kinerja['total_target'];
        $totalRealisasi = $kinerja['total_realisasi'];
        $avgPersentaseKuantitas = $kinerja['avg_kuantitas'];
        $avgKualitas = $kinerja['avg_kualitas'];
        $avgNilaiCkp = $kinerja['nilai_ckp'];
        $skorKinerja = $kinerja['skor_kinerja'];
        $totalEstimasiJam = $kinerja['total_estimasi_jam'];
        $jamKerjaAktif = $kinerja['jam_kerja_aktif'];
        $hariKerjaAktif = $kinerja['hari_kerja_aktif'];

        $countBelumDinilai = $kinerja['items']->where('status_penilaian', 'belum_dinilai')->count();
        $countSudahDinilai = $kinerja['items']->where('status_penilaian', 'sudah_dinilai')->count();

        return view('ckp.index', compact(
            'ckp',
            'ckp_tambahan',
            'currentYear',
            'currentMonth',
            'totalTarget',
            'totalRealisasi',
            'avgPersentaseKuantitas',
            'avgKualitas',
            'avgNilaiCkp',
            'skorKinerja',
            'totalEstimasiJam',
            'jamKerjaAktif',
            'hariKerjaAktif',
            'countBelumDinilai',
            'countSudahDinilai',
            'daftarTahun',
            'daftarBulan'
        ));
    }

    /**
     * Pegawai mengisi realisasi pekerjaan miliknya sendiri (Utama maupun Tambahan).
     */
    public function ckp_edit(Request $request, $id)
    {
        $user = Auth::user();

        $item = AlokasiPekerjaan::findOrFail($id);

        // Proteksi Hak Akses: Pegawai HANYA boleh mengisi realisasi pekerjaannya sendiri!
        if ($item->pegawai_id !== $user->id) {
            abort(403, 'Akses Ditolak: Anda hanya dapat mengisi realisasi pekerjaan milik Anda sendiri.');
        }

        $request->validate([
            'realisasi_kuantitas' => 'required|numeric|min:0',
            'catatan_realisasi'   => 'nullable|string|max:1000',
            'link_bukti'          => 'nullable|string|max:500',
        ]);

        $realisasi = (float) $request->input('realisasi_kuantitas');
        $target = (float) $item->target_kuantitas;
        $persentase = $target > 0 ? round(($realisasi / $target) * 100, 2) : 0;

        $item->realisasi_kuantitas  = $realisasi;
        $item->persentase_kuantitas = $persentase;
        $item->catatan_realisasi    = $request->catatan_realisasi;
        $item->link_bukti           = $request->link_bukti;
        $item->tanggal_realisasi    = Carbon::now()->toDateString();
        
        // Status otomatis berdasarkan realisasi vs target
        $item->status = ($realisasi >= $target && $target > 0) ? 'selesai' : 'berjalan';

        // Jika sudah dinilai sebelumnya, update kembali nilai CKP
        if ($item->kualitas > 0) {
            $item->nilai_ckp = round(($persentase + $item->kualitas) / 2, 2);
        }

        $item->save();

        return redirect()->route('ckp.index', ['tahun' => $item->tahun, 'bulan' => $item->bulan])
                         ->with('success', "Realisasi pekerjaan '{$item->nama_kegiatan}' berhasil disimpan.");
    }

    /**
     * Input Pekerjaan Tambahan secara mandiri melalui CKP Saya.
     */
    public function ckp_aksi_tambahan(Request $request)
    {
        $user = Auth::user();

        $namaKegiatan = $request->input('nama_kegiatan') ?? $request->input('uraian_kegiatan');
        if (empty($namaKegiatan)) {
            return back()->withInput()->withErrors(['uraian_kegiatan' => 'Nama / Uraian kegiatan wajib diisi.']);
        }

        $request->validate([
            'satuan'          => 'required|string|max:100',
            'estimasi_waktu'  => 'required|numeric|min:1', // Satuan Jam
            'keterangan'      => 'nullable|string|max:1000',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
        ]);

        $target = (float) ($request->input('target_kuantitas') ?? $request->input('target', 1));
        $realisasi = (float) ($request->input('realisasi_kuantitas') ?? $request->input('realisasi', 0));
        $persentase = $target > 0 ? round(($realisasi / $target) * 100, 2) : 0;

        $tglMulai = $request->filled('tanggal_mulai') 
            ? Carbon::parse($request->tanggal_mulai) 
            : ($request->filled('tahun') && $request->filled('bulan')
                ? Carbon::create((int) $request->tahun, (int) $request->bulan, 1)
                : Carbon::now()->startOfMonth());
        $tglSelesai = $request->filled('tanggal_selesai') 
            ? Carbon::parse($request->tanggal_selesai) 
            : $tglMulai->copy()->endOfMonth();

        $tahun = (int) ($request->input('tahun') ?? $tglMulai->year);
        $bulan = (int) ($request->input('bulan') ?? $tglMulai->month);

        $asalTim = $request->input('asal_tim') ?? ($user->tim ? $user->tim->nama_tim : 'Pribadi');
        $status = $request->input('status') ?? ($realisasi >= $target ? 'selesai' : 'berjalan');

        AlokasiPekerjaan::create([
            'nama_kegiatan'        => $namaKegiatan,
            'satuan'               => $request->satuan,
            'jenis_pekerjaan'      => 'tambahan', // Pekerjaan Tambahan mandiri
            'target_kuantitas'     => $target,
            'realisasi_kuantitas'  => $realisasi,
            'persentase_kuantitas' => $persentase,
            'asal_tim'             => $asalTim,
            'tim_id'               => $user->tim_id,
            'pegawai_id'           => $user->id,
            'created_by'           => $user->id,
            'keterangan'           => $request->keterangan,
            'estimasi_waktu'       => (float) $request->estimasi_waktu,
            'tanggal_mulai'        => $tglMulai,
            'tanggal_selesai'      => $tglSelesai,
            'tahun'                => $tahun,
            'bulan'                => $bulan,
            'status'               => $status,
            'catatan_realisasi'    => 'Pekerjaan tambahan dibuat mandiri oleh pegawai.',
            'tanggal_realisasi'    => Carbon::now()->toDateString(),
            'kualitas'             => 0,
            'nilai_ckp'            => 0,
            'status_penilaian'     => 'belum_dinilai',
        ]);

        return redirect()->route('ckp.index', ['tahun' => $tahun, 'bulan' => $bulan])
                         ->with('success', 'Pekerjaan tambahan berhasil ditambahkan ke CKP Saya.');
    }

    public function ckp_tambah(Request $request)
    {
        $currentYear = (int) $request->input('tahun', Carbon::now()->year);
        $currentMonth = (int) $request->input('bulan', Carbon::now()->month);

        $daftarTahun = $this->getDaftarTahun();
        $daftarBulan = $this->getDaftarBulan();

        return view('ckp.tambah_tambahan', compact('currentYear', 'currentMonth', 'daftarTahun', 'daftarBulan'));
    }

    public function ckp_edit_tambahan($id)
    {
        $user = Auth::user();

        $item = AlokasiPekerjaan::where('jenis_pekerjaan', 'tambahan')
                                ->where('pegawai_id', $user->id)
                                ->findOrFail($id);

        $daftarTahun = $this->getDaftarTahun();
        $daftarBulan = $this->getDaftarBulan();

        return view('ckp.edit_tambahan', compact('item', 'daftarTahun', 'daftarBulan'));
    }

    public function ckp_update(Request $request, $id)
    {
        $user = Auth::user();

        $item = AlokasiPekerjaan::where('jenis_pekerjaan', 'tambahan')
                                ->where('pegawai_id', $user->id)
                                ->findOrFail($id);

        $request->validate([
            'uraian_kegiatan'     => 'required|string|max:255',
            'satuan'              => 'required|string|max:100',
            'target_kuantitas'    => 'required|numeric|min:0.01',
            'realisasi_kuantitas' => 'required|numeric|min:0',
            'estimasi_waktu'      => 'required|numeric|min:1',
            'keterangan'          => 'nullable|string|max:1000',
        ]);

        $target = (float) $request->target_kuantitas;
        $realisasi = (float) $request->realisasi_kuantitas;
        $persentase = $target > 0 ? round(($realisasi / $target) * 100, 2) : 0;

        $item->nama_kegiatan        = $request->uraian_kegiatan;
        $item->satuan               = $request->satuan;
        $item->target_kuantitas     = $target;
        $item->realisasi_kuantitas  = $realisasi;
        $item->persentase_kuantitas = $persentase;
        $item->estimasi_waktu       = (float) $request->estimasi_waktu;
        $item->keterangan           = $request->keterangan;
        $item->status               = $realisasi >= $target ? 'selesai' : 'berjalan';

        if ($item->kualitas > 0) {
            $item->nilai_ckp = round(($persentase + $item->kualitas) / 2, 2);
        }

        $item->save();

        return redirect()->route('ckp.index', ['tahun' => $item->tahun, 'bulan' => $item->bulan])
                         ->with('success', 'Pekerjaan tambahan berhasil diperbarui.');
    }

    public function ckp_hapus_tambahan($id)
    {
        $user = Auth::user();

        $item = AlokasiPekerjaan::where('jenis_pekerjaan', 'tambahan')
                                ->where('pegawai_id', $user->id)
                                ->findOrFail($id);

        $tahun = $item->tahun;
        $bulan = $item->bulan;
        $item->delete();

        return redirect()->route('ckp.index', ['tahun' => $tahun, 'bulan' => $bulan])
                         ->with('success', 'Pekerjaan tambahan berhasil dihapus.');
    }

    // =========================================================================
    // L. PENILAIAN PEKERJAAN (Ketua Tim menilai anggota, Kepala BPS menilai Ketua Tim & Tambahan)
    // =========================================================================

    public function ckp_nilai(Request $request)
    {
        $penilai = Auth::user();

        $currentYear = (int) $request->input('tahun', Carbon::now()->year);
        $currentMonth = (int) $request->input('bulan', Carbon::now()->month);
        $selectedUserId = $request->input('user_id');

        $query = AlokasiPekerjaan::with(['pegawai', 'tim'])
            ->where(function ($q) use ($currentYear, $currentMonth) {
                $q->where(function ($sq) use ($currentYear, $currentMonth) {
                    $sq->where('tahun', $currentYear)->where('bulan', $currentMonth);
                })->orWhere(function ($sq) use ($currentYear, $currentMonth) {
                    $sq->whereNull('tahun')
                       ->whereYear('tanggal_mulai', $currentYear)
                       ->whereMonth('tanggal_mulai', $currentMonth);
                });
            });

        // Filter berdasarkan kewenangan role penilai:
        if ($penilai->isKepala()) {
            // Kepala BPS berwenang menilai:
            // 1. Pekerjaan Utama milik Ketua Tim
            // 2. Pekerjaan Tambahan seluruh pegawai
            $query->where(function ($q) {
                $q->where('jenis_pekerjaan', 'tambahan')
                  ->orWhere(function ($sq) {
                      $sq->where('jenis_pekerjaan', 'utama')
                         ->whereHas('pegawai', function ($uq) {
                             $uq->where('role', 'ketua_tim')->orWhere('is_ketua_tim', true);
                         });
                  });
            });

            // Daftar user yang bisa dipilih Kepala BPS
            $users = User::where('is_active', true)
                ->where('role', '!=', 'admin')
                ->where('id', '!=', $penilai->id)
                ->orderBy('name')
                ->get();

        } elseif ($penilai->isKetuaTim()) {
            // Ketua Tim berwenang menilai:
            // Pekerjaan Utama milik anggota timnya (BUKAN dirinya sendiri)
            $query->where('jenis_pekerjaan', 'utama')
                  ->where('pegawai_id', '!=', $penilai->id);

            $penilaiTimId = $penilai->tim_id ?? Team::where('ketua_id', $penilai->id)->value('id');

            if ($penilaiTimId) {
                $query->where(function ($q) use ($penilaiTimId) {
                    $q->where('tim_id', $penilaiTimId)
                      ->orWhereHas('pegawai', function ($sq) use ($penilaiTimId) {
                          $sq->where('tim_id', $penilaiTimId);
                      });
                });
            }

            // Daftar anggota tim penilai
            $users = $penilaiTimId ? User::where('is_active', true)
                ->where('tim_id', $penilaiTimId)
                ->where('id', '!=', $penilai->id)
                ->orderBy('name')
                ->get() : collect();
        }

        if ($selectedUserId) {
            $query->where('pegawai_id', $selectedUserId);
        }

        $items = $query->orderBy('pegawai_id')->orderBy('jenis_pekerjaan')->get();
        $ckp = $items->where('jenis_pekerjaan', 'utama');
        $ckp_tambahan = $items->where('jenis_pekerjaan', 'tambahan');

        $daftarTahun = $this->getDaftarTahun();
        $daftarBulan = $this->getDaftarBulan();

        return view('ckp.nilai', compact(
            'ckp',
            'ckp_tambahan',
            'users',
            'selectedUserId',
            'currentYear',
            'currentMonth',
            'daftarTahun',
            'daftarBulan'
        ));
    }

    /**
     * Simpan nilai kualitas pekerjaan oleh Penilai yang berwenang.
     */
    public function ckp_nilai_edit(Request $request, $id)
    {
        $penilai = Auth::user();

        $task = AlokasiPekerjaan::with('pegawai')->findOrFail($id);

        // Verifikasi izin penilaian
        if (!$penilai->canRateTask($task)) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki kewenangan menilai pekerjaan ini.');
        }

        $request->validate([
            'kualitas'        => 'required|numeric|min:0|max:100',
            'catatan_penilai' => 'nullable|string|max:1000',
        ]);

        $kualitas = (float) $request->kualitas;
        $persentaseKuantitas = (float) $task->persentase_kuantitas;

        $task->kualitas           = $kualitas;
        $task->nilai_ckp          = round(($persentaseKuantitas + $kualitas) / 2, 2);
        $task->penilai_id         = $penilai->id;
        $task->catatan_penilai    = $request->catatan_penilai;
        $task->tanggal_penilaian  = Carbon::now();
        $task->status_penilaian   = $request->input('status_penilaian', 'sudah_dinilai');
        $task->save();

        return redirect()->back()->with('success', "Penilaian pekerjaan '{$task->nama_kegiatan}' berhasil disimpan (Nilai Kualitas: {$kualitas}%, CKP: {$task->nilai_ckp}%).");
    }

    // =========================================================================
    // Q. LAPORAN CKP BULANAN & EXPORT EXCEL / PDF
    // =========================================================================

    public function laporan(Request $request)
    {
        $user = Auth::user();

        $currentYear = (int) $request->input('tahun', Carbon::now()->year);
        $currentMonth = (int) $request->input('bulan', Carbon::now()->month);
        $timId = $request->filled('tim_id') ? (int) $request->tim_id : null;
        
        $daftarTim = Team::where('is_active', true)->orderBy('nama_tim')->get();
        $pegawaiQuery = User::where('is_active', true)->where('role', '!=', 'admin');
        if ($timId) {
            $pegawaiQuery->where('tim_id', $timId);
        }
        $daftarPegawai = $pegawaiQuery->orderBy('name')->get();

        // Pilihan pegawai: default ke user login jika Ketua Tim atau Pegawai (laporan dari dirinya sendiri)
        $defaultUserId = $user->isKepala() ? ($daftarPegawai->first()->id ?? null) : $user->id;
        $selectedUserId = $request->filled('pegawai_id') ? (int) $request->pegawai_id : $defaultUserId;

        $kinerja = null;
        $selectedPegawai = null;

        if ($selectedUserId) {
            $selectedPegawai = User::with('tim')->find($selectedUserId);
            if ($selectedPegawai) {
                $kinerja = KinerjaService::hitungKinerjaPegawai($selectedUserId, $currentYear, $currentMonth);
            }
        }

        $daftarTahun = $this->getDaftarTahun();
        $daftarBulan = $this->getDaftarBulan();

        return view('ckp.laporan', compact(
            'daftarTim',
            'daftarPegawai',
            'selectedUserId',
            'selectedPegawai',
            'timId',
            'currentYear',
            'currentMonth',
            'kinerja',
            'daftarTahun',
            'daftarBulan'
        ));
    }

    /**
     * Export Laporan CKP ke Excel (.xls Spreadsheet).
     */
    public function exportExcel(Request $request)
    {
        $tahun = (int) $request->input('tahun', Carbon::now()->year);
        $bulan = (int) $request->input('bulan', Carbon::now()->month);
        $userId = (int) $request->input('pegawai_id', Auth::id());

        $kinerja = KinerjaService::hitungKinerjaPegawai($userId, $tahun, $bulan);
        $pegawai = $kinerja['pegawai'];
        $kepala = User::getKepala() ?? $pegawai;

        $namaBulan = $this->getDaftarBulan()[$bulan] ?? $bulan;

        $filename = "Laporan_CKP_{$pegawai->username}_{$namaBulan}_{$tahun}.xls";

        return response()->view('ckp.export_excel', compact(
            'kinerja',
            'pegawai',
            'kepala',
            'tahun',
            'bulan',
            'namaBulan'
        ))->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
          ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
          ->header('Cache-Control', 'max-age=0');
    }

    /**
     * Export / Print PDF Laporan CKP.
     */
    public function exportPdf(Request $request)
    {
        $tahun = (int) $request->input('tahun', Carbon::now()->year);
        $bulan = (int) $request->input('bulan', Carbon::now()->month);
        $userId = (int) $request->input('pegawai_id', Auth::id());

        $kinerja = KinerjaService::hitungKinerjaPegawai($userId, $tahun, $bulan);
        $pegawai = $kinerja['pegawai'];
        $kepala = User::getKepala() ?? $pegawai;

        $namaBulan = $this->getDaftarBulan()[$bulan] ?? $bulan;

        return view('ckp.export_pdf', compact(
            'kinerja',
            'pegawai',
            'kepala',
            'tahun',
            'bulan',
            'namaBulan'
        ));
    }

    // =========================================================================
    // DOKUMEN CETAK RESMI BPS (CKP-R & CKP-T)
    // =========================================================================

    public function ckp_cetak($tahun, $bulan, Request $request)
    {
        $tahun = (int) $tahun;
        $bulan = (int) $bulan;
        $userId = $request->input('user_id', Auth::id());
        $user = User::findOrFail($userId);

        $kinerja = KinerjaService::hitungKinerjaPegawai($user->id, $tahun, $bulan);
        $ckp = $kinerja['utama'];
        $ckp_tambahan = $kinerja['tambahan'];
        $kepala = User::getKepala() ?? $user;

        $totalTarget = $kinerja['total_target'];
        $totalRealisasi = $kinerja['total_realisasi'];
        $avgPersentaseKuantitas = $kinerja['avg_kuantitas'];
        $avgKualitas = $kinerja['avg_kualitas'];
        $avgNilaiCkp = $kinerja['nilai_ckp'];

        $daftarBulan = $this->getDaftarBulan();

        return view('ckp.cetak', compact(
            'user',
            'kepala',
            'tahun',
            'bulan',
            'ckp',
            'ckp_tambahan',
            'totalTarget',
            'totalRealisasi',
            'avgPersentaseKuantitas',
            'avgKualitas',
            'avgNilaiCkp',
            'daftarBulan'
        ));
    }

    public function ckp_t_cetak($tahun, $bulan, Request $request)
    {
        $tahun = (int) $tahun;
        $bulan = (int) $bulan;
        $userId = $request->input('user_id', Auth::id());
        $user = User::findOrFail($userId);

        $kinerja = KinerjaService::hitungKinerjaPegawai($user->id, $tahun, $bulan);
        $ckp = $kinerja['utama'];
        $ckp_tambahan = $kinerja['tambahan'];
        $kepala = User::getKepala() ?? $user;

        $daftarBulan = $this->getDaftarBulan();

        return view('ckp.t_cetak', compact(
            'user',
            'kepala',
            'tahun',
            'bulan',
            'ckp',
            'ckp_tambahan',
            'daftarBulan'
        ));
    }

    public function ckp_user(Request $request)
    {
        return redirect()->route('alokasi.pegawai', $request->query());
    }

    public function ckp_user_detail($id_user, Request $request)
    {
        $user = User::findOrFail($id_user);
        $currentYear = (int) $request->input('tahun', Carbon::now()->year);
        $currentMonth = (int) $request->input('bulan', Carbon::now()->month);

        $kinerja = KinerjaService::hitungKinerjaPegawai($user->id, $currentYear, $currentMonth);
        $ckp = $kinerja['utama'];
        $ckp_tambahan = $kinerja['tambahan'];
        $totalTarget = $kinerja['total_target'];
        $totalRealisasi = $kinerja['total_realisasi'];
        $avgPersentaseKuantitas = $kinerja['avg_kuantitas'];
        $avgKualitas = $kinerja['avg_kualitas'];
        $avgNilaiCkp = $kinerja['nilai_ckp'];

        $daftarTahun = $this->getDaftarTahun();
        $daftarBulan = $this->getDaftarBulan();

        return view('ckp.user_detail', compact(
            'user',
            'ckp',
            'ckp_tambahan',
            'currentYear',
            'currentMonth',
            'totalTarget',
            'totalRealisasi',
            'avgPersentaseKuantitas',
            'avgKualitas',
            'avgNilaiCkp',
            'daftarTahun',
            'daftarBulan'
        ));
    }

    public function show($id, Request $request)
    {
        return $this->ckp_user_detail($id, $request);
    }
}
