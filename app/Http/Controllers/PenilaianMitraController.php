<?php

namespace App\Http\Controllers;

use App\Models\AlokasiMitra;
use App\Models\Mitra;
use App\Models\PenilaianMitra;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PenilaianMitraController extends Controller
{
    /**
     * Tampilan daftar penilaian kinerja mitra.
     * Dapat dilihat oleh seluruh non-admin (Ketua Tim, Anggota Tim, Kepala BPS).
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            abort(403, 'Akses Ditolak: Administrator tidak mengelola penilaian kinerja mitra.');
        }

        $currentYear = (int) $request->input('tahun', Carbon::now()->year);
        $currentMonth = (int) $request->input('bulan', Carbon::now()->month);

        $query = AlokasiMitra::with(['mitra', 'tim', 'pembuat', 'penilaians.penilai'])
            ->where('tahun', $currentYear)
            ->where('bulan', $currentMonth);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('nama_kegiatan', 'like', "%{$s}%")
                  ->orWhere('kegiatan', 'like', "%{$s}%")
                  ->orWhere('posisi', 'like', "%{$s}%")
                  ->orWhereHas('mitra', function ($mq) use ($s) {
                      $mq->where('nama', 'like', "%{$s}%")
                         ->orWhere('sobat_id', 'like', "%{$s}%");
                  });
            });
        }

        if ($request->filled('status_penilaian')) {
            if ($request->status_penilaian === 'sudah') {
                $query->has('penilaians');
            } elseif ($request->status_penilaian === 'belum') {
                $query->doesntHave('penilaians');
            }
        }

        $alokasiList = $query->latest('tanggal_mulai')->paginate(15)->withQueryString();

        $daftarTahun = $this->getDaftarTahun();
        $daftarBulan = $this->getDaftarBulan();

        return view('penilaian_mitra.index', compact(
            'alokasiList',
            'currentYear',
            'currentMonth',
            'daftarTahun',
            'daftarBulan'
        ));
    }

    /**
     * Simpan atau update penilaian kinerja mitra (1-5 bintang, komentar min 20 karakter).
     * Hanya Ketua Tim dan Anggota Tim yang berhak menilai.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Validasi hak akses penilai: Hanya Ketua Tim dan Anggota Tim (Kepala BPS & Admin ditolak)
        if (!$user->canRateMitra()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang untuk memberikan penilaian kinerja mitra.');
        }

        $validated = $request->validate([
            'alokasi_mitra_id' => 'required|exists:alokasi_mitras,id',
            'bintang'          => 'required|integer|min:1|max:5',
            'komentar'         => 'required|string|min:20',
        ], [
            'alokasi_mitra_id.required' => 'Kegiatan alokasi mitra wajib dipilih.',
            'bintang.required'          => 'Jumlah bintang penilaian wajib dipilih.',
            'bintang.integer'           => 'Nilai bintang harus berupa angka bulat 1 sampai 5.',
            'bintang.min'               => 'Skala bintang terendah adalah 1 bintang.',
            'bintang.max'               => 'Skala bintang tertinggi adalah 5 bintang.',
            'komentar.required'         => 'Komentar evaluasi wajib diisi.',
            'komentar.min'              => 'Komentar penilaian wajib minimal 20 karakter.',
        ]);

        $alokasi = AlokasiMitra::with('mitra')->findOrFail($validated['alokasi_mitra_id']);

        $penilaian = PenilaianMitra::updateOrCreate(
            [
                'alokasi_mitra_id' => $alokasi->id,
                'penilai_id'       => $user->id,
            ],
            [
                'mitra_id'         => $alokasi->mitra_id,
                'bintang'          => (int) $validated['bintang'],
                'komentar'         => trim($validated['komentar']),
            ]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => "Penilaian untuk mitra '{$alokasi->mitra->nama}' berhasil disimpan.",
                'penilaian' => $penilaian,
            ]);
        }

        return redirect()->back()
            ->with('success', "Penilaian kinerja mitra '{$alokasi->mitra->nama}' pada survei '{$alokasi->nama_kegiatan}' berhasil disimpan ({$validated['bintang']} Bintang).");
    }
}
