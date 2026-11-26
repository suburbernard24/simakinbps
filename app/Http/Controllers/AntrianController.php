<?php

namespace App\Http\Controllers;

use App\Models\PstAntrian;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AntrianController extends Controller
{
    private const LAYANAN_MAP = [
        'K' => ['nama' => 'Konsultasi Statistik', 'loket' => 'Loket 1'],
        'D' => ['nama' => 'Permintaan Data & Publikasi', 'loket' => 'Loket 2'],
        'R' => ['nama' => 'Rekomendasi Statistik (ROMANTIK)', 'loket' => 'Loket 3'],
        'M' => ['nama' => 'Layanan Data Mikro & Peta (SILASTIK)', 'loket' => 'Loket 4'],
    ];

    public function index()
    {
        return view('frontend.antrian');
    }

    public function status(): JsonResponse
    {
        $today = now()->toDateString();

        $sedangDipanggil = PstAntrian::whereDate('tanggal', $today)
            ->where('status', 'dipanggil')
            ->orderByDesc('called_at')
            ->first();

        $menunggu = PstAntrian::whereDate('tanggal', $today)
            ->where('status', 'menunggu')
            ->orderBy('id')
            ->get();

        $riwayatDipanggil = PstAntrian::whereDate('tanggal', $today)
            ->whereIn('status', ['dipanggil', 'selesai'])
            ->orderByDesc('called_at')
            ->limit(6)
            ->get();

        return response()->json([
            'sedang_dipanggil'  => $sedangDipanggil,
            'menunggu'          => $menunggu,
            'riwayat_dipanggil' => $riwayatDipanggil,
            'jumlah_menunggu'   => $menunggu->count(),
        ]);
    }

    public function ambil(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode_layanan' => 'required|in:K,D,R,M',
            'nama_pemohon' => 'required|string|max:120',
            'no_wa'        => 'required|string|max:30',
            'instansi'     => 'nullable|string|max:120',
            'keperluan'    => 'required|string|max:500',
        ]);

        $today = now()->toDateString();
        $kode  = $validated['kode_layanan'];
        $info  = self::LAYANAN_MAP[$kode];

        $antrian = DB::transaction(function () use ($today, $kode, $info, $validated) {
            $lastUrutan = PstAntrian::whereDate('tanggal', $today)
                ->where('kode_layanan', $kode)
                ->lockForUpdate()
                ->max('urutan') ?? 0;

            $nextUrutan = $lastUrutan + 1;
            $nomor      = sprintf('%s-%03d', $kode, $nextUrutan);

            return PstAntrian::create([
                'tanggal'       => $today,
                'kode_layanan'  => $kode,
                'nama_layanan'  => $info['nama'],
                'loket'         => $info['loket'],
                'urutan'        => $nextUrutan,
                'nomor_antrian' => $nomor,
                'nama_pemohon'  => $validated['nama_pemohon'],
                'no_wa'         => $validated['no_wa'],
                'instansi'      => $validated['instansi'] ?? null,
                'keperluan'     => $validated['keperluan'],
                'status'        => 'menunggu',
            ]);
        });

        return response()->json([
            'message' => 'Nomor antrian berhasil dibuat.',
            'antrian' => $antrian,
        ], 201);
    }

    public function panggil(int $id): JsonResponse
    {
        $antrian = PstAntrian::findOrFail($id);

        // Antrian lain yang sebelumnya berstatus dipanggil pada hari yang sama ditandai selesai
        PstAntrian::whereDate('tanggal', $antrian->tanggal)
            ->where('status', 'dipanggil')
            ->where('id', '!=', $antrian->id)
            ->update(['status' => 'selesai']);

        $antrian->update([
            'status'    => 'dipanggil',
            'called_at' => now(),
        ]);

        return response()->json([
            'message' => "Memanggil {$antrian->nomor_antrian} ke {$antrian->loket}.",
            'antrian' => $antrian->fresh(),
        ]);
    }

    public function selesai(int $id): JsonResponse
    {
        $antrian = PstAntrian::findOrFail($id);
        $antrian->update(['status' => 'selesai']);

        return response()->json([
            'message' => "Antrian {$antrian->nomor_antrian} selesai dilayani.",
            'antrian' => $antrian->fresh(),
        ]);
    }
}
