<?php

namespace App\Http\Controllers;

use App\Models\PengaturanHonorMitra;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HonorMitraController extends Controller
{
    /**
     * Halaman Pengaturan Batas Maksimal Honor Mitra oleh Administrator.
     */
    public function index(Request $request)
    {
        $currentYear = (int) $request->input('tahun', Carbon::now()->year);
        $currentMonth = (int) $request->input('bulan', Carbon::now()->month);

        $currentHonor = PengaturanHonorMitra::getMaksimalHonor($currentYear, $currentMonth);
        $settingKhusus = PengaturanHonorMitra::where('tahun', $currentYear)->where('bulan', $currentMonth)->first();
        $settingGlobal = PengaturanHonorMitra::whereNull('tahun')->whereNull('bulan')->latest('id')->first();
        $riwayatPengaturan = PengaturanHonorMitra::with('updater')->latest()->paginate(10);

        return view('admin.honor_mitra.index', compact(
            'currentHonor',
            'settingKhusus',
            'settingGlobal',
            'riwayatPengaturan',
            'currentYear',
            'currentMonth'
        ));
    }

    /**
     * Simpan pengaturan batas maksimal honor mitra.
     */
    public function store(Request $request)
    {
        // Bersihkan pemisah ribuan jika dikirim dalam format angka berpemisah
        $rawNominal = $request->input('maksimal_honor');
        if (is_string($rawNominal)) {
            $cleaned = preg_replace('/[^\d]/', '', $rawNominal);
            $request->merge(['maksimal_honor' => $cleaned]);
        }

        $validated = $request->validate([
            'maksimal_honor' => 'required|numeric|min:0',
            'tahun'          => 'nullable|integer|min:2020|max:2050',
            'bulan'          => 'nullable|integer|min:1|max:12',
            'keterangan'     => 'nullable|string|max:255',
        ], [
            'maksimal_honor.required' => 'Nominal maksimal honor wajib diisi.',
            'maksimal_honor.numeric'  => 'Nominal maksimal honor harus berupa angka.',
            'maksimal_honor.min'      => 'Nominal maksimal honor tidak boleh negatif.',
        ]);

        $tahun = $request->filled('tahun') ? (int) $request->tahun : null;
        $bulan = $request->filled('bulan') ? (int) $request->bulan : null;

        PengaturanHonorMitra::updateOrCreate(
            ['tahun' => $tahun, 'bulan' => $bulan],
            [
                'maksimal_honor' => (int) $validated['maksimal_honor'],
                'updated_by'     => auth()->id(),
                'keterangan'     => $validated['keterangan'] ?? 'Pengaturan batas honor mitra BPS',
            ]
        );

        $formatted = 'Rp ' . number_format((int) $validated['maksimal_honor'], 0, ',', '.');
        $periodeKet = ($tahun && $bulan) ? "untuk periode {$bulan}/{$tahun}" : "secara global";

        return redirect()->route('admin.honor_mitra.index', ['tahun' => $tahun ?: Carbon::now()->year, 'bulan' => $bulan ?: Carbon::now()->month])
            ->with('success', "Batas maksimal honor mitra {$periodeKet} berhasil disimpan menjadi {$formatted}.");
    }
}
