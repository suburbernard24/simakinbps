<?php

namespace App\Http\Controllers;

use App\Models\PengaturanHonorMitra;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HonorMitraController extends Controller
{
    /**
     * Halaman Pengaturan Batas Maksimal Honor Mitra oleh Administrator.
     * Menampilkan dua panel: Mitra Pendataan Lapangan & Mitra Pengolahan.
     */
    public function index(Request $request)
    {
        $currentYear  = (int) $request->input('tahun', Carbon::now()->year);
        $currentMonth = (int) $request->input('bulan', Carbon::now()->month);

        // ── Pendataan Lapangan ──────────────────────────────────────────────
        $honorPendataan = PengaturanHonorMitra::getMaksimalHonor(
            PengaturanHonorMitra::JENIS_PENDATAAN, $currentYear
        );
        $settingPendataanKhusus = PengaturanHonorMitra::where('jenis_mitra', PengaturanHonorMitra::JENIS_PENDATAAN)
            ->where('tahun', $currentYear)->first();
        $settingPendataanGlobal = PengaturanHonorMitra::where('jenis_mitra', PengaturanHonorMitra::JENIS_PENDATAAN)
            ->whereNull('tahun')->latest('id')->first();

        // ── Pengolahan ──────────────────────────────────────────────────────
        $honorPengolahan = PengaturanHonorMitra::getMaksimalHonor(
            PengaturanHonorMitra::JENIS_PENGOLAHAN, $currentYear
        );
        $settingPengolahanKhusus = PengaturanHonorMitra::where('jenis_mitra', PengaturanHonorMitra::JENIS_PENGOLAHAN)
            ->where('tahun', $currentYear)->first();
        $settingPengolahanGlobal = PengaturanHonorMitra::where('jenis_mitra', PengaturanHonorMitra::JENIS_PENGOLAHAN)
            ->whereNull('tahun')->latest('id')->first();

        // ── Riwayat (semua jenis, terbaru) ────────────────────────────────
        $riwayatPengaturan = PengaturanHonorMitra::with('updater')->latest()->paginate(15);

        return view('admin.honor_mitra.index', compact(
            'currentYear',
            'currentMonth',
            // Pendataan
            'honorPendataan',
            'settingPendataanKhusus',
            'settingPendataanGlobal',
            // Pengolahan
            'honorPengolahan',
            'settingPengolahanKhusus',
            'settingPengolahanGlobal',
            // Riwayat
            'riwayatPengaturan'
        ));
    }

    /**
     * Simpan pengaturan batas maksimal honor mitra.
     * Menerima data untuk satu jenis mitra sekaligus per submit.
     */
    public function store(Request $request)
    {
        // Backward compatibility: jika dikirim via single field 'maksimal_honor'
        if ($request->filled('maksimal_honor') && !$request->filled('maksimal_honor_pendataan')) {
            $rawSingle = preg_replace('/[^\d]/', '', (string) $request->input('maksimal_honor'));
            $request->merge([
                'maksimal_honor_pendataan'  => $rawSingle,
                'maksimal_honor_pengolahan' => $rawSingle,
                'keterangan_pendataan'      => $request->input('keterangan', 'SBM Honor Mitra Pendataan Lapangan BPS'),
                'keterangan_pengolahan'     => $request->input('keterangan', 'SBM Honor Mitra Pengolahan BPS'),
            ]);
        }

        // Bersihkan pemisah ribuan jika dikirim dalam format angka berpemisah
        foreach (['maksimal_honor_pendataan', 'maksimal_honor_pengolahan'] as $field) {
            $raw = $request->input($field);
            if (is_string($raw)) {
                $request->merge([$field => preg_replace('/[^\d]/', '', $raw)]);
            }
        }

        $validated = $request->validate([
            'maksimal_honor_pendataan'  => 'required|numeric|min:0',
            'maksimal_honor_pengolahan' => 'required|numeric|min:0',
            'tahun'                     => 'nullable|integer|min:2020|max:2050',
            'bulan'                     => 'nullable|integer|min:1|max:12',
            'keterangan_pendataan'      => 'nullable|string|max:255',
            'keterangan_pengolahan'     => 'nullable|string|max:255',
        ], [
            'maksimal_honor_pendataan.required'  => 'Nominal maksimal honor Mitra Pendataan Lapangan wajib diisi.',
            'maksimal_honor_pendataan.numeric'   => 'Nominal harus berupa angka.',
            'maksimal_honor_pengolahan.required' => 'Nominal maksimal honor Mitra Pengolahan wajib diisi.',
            'maksimal_honor_pengolahan.numeric'  => 'Nominal harus berupa angka.',
        ]);

        $tahun = $request->filled('tahun') ? (int) $request->tahun : null;
        $bulan = $request->filled('bulan') ? (int) $request->bulan : null;

        // Simpan Mitra Pendataan Lapangan
        PengaturanHonorMitra::updateOrCreate(
            [
                'jenis_mitra' => PengaturanHonorMitra::JENIS_PENDATAAN,
                'tahun'       => $tahun,
                'bulan'       => $bulan,
            ],
            [
                'maksimal_honor' => (int) $validated['maksimal_honor_pendataan'],
                'updated_by'     => auth()->id(),
                'keterangan'     => $validated['keterangan_pendataan'] ?? 'SBM Honor Mitra Pendataan Lapangan BPS',
            ]
        );

        // Simpan Mitra Pengolahan
        PengaturanHonorMitra::updateOrCreate(
            [
                'jenis_mitra' => PengaturanHonorMitra::JENIS_PENGOLAHAN,
                'tahun'       => $tahun,
                'bulan'       => $bulan,
            ],
            [
                'maksimal_honor' => (int) $validated['maksimal_honor_pengolahan'],
                'updated_by'     => auth()->id(),
                'keterangan'     => $validated['keterangan_pengolahan'] ?? 'SBM Honor Mitra Pengolahan BPS',
            ]
        );

        $fmtPendataan  = 'Rp ' . number_format((int) $validated['maksimal_honor_pendataan'], 0, ',', '.');
        $fmtPengolahan = 'Rp ' . number_format((int) $validated['maksimal_honor_pengolahan'], 0, ',', '.');
        $periodeKet    = $tahun ? ($bulan ? "untuk periode {$bulan}/{$tahun}" : "untuk Tahun {$tahun}") : 'secara global';

        $redirectParams = [];
        if ($tahun) {
            $redirectParams['tahun'] = $tahun;
        }
        if ($bulan) {
            $redirectParams['bulan'] = $bulan;
        }

        return redirect()->route('admin.honor_mitra.index', $redirectParams)
            ->with('success', "Batas honor mitra berhasil disimpan {$periodeKet}. Pendataan Lapangan: {$fmtPendataan} | Pengolahan: {$fmtPengolahan}.");
    }
}
