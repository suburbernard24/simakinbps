<?php

namespace App\Http\Controllers;

use App\Models\AlokasiPekerjaan;
use App\Models\HariKerjaAktif;
use App\Models\Team;
use App\Models\User;
use App\Services\KinerjaService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AlokasiController extends Controller
{
    // =========================================================================
    // H. INPUT ALOKASI PEKERJAAN — Khusus Ketua Tim
    // =========================================================================

    public function input(Request $request)
    {
        $user = auth()->user();
        $currentYear = (int) $request->input('tahun', Carbon::now()->year);
        $currentMonth = (int) $request->input('bulan', Carbon::now()->month);

        // Hanya pekerjaan yang dialokasikan oleh Ketua Tim ini
        $alokasi = AlokasiPekerjaan::with(['pegawai', 'tim'])
            ->where('created_by', $user->id)
            ->where('jenis_pekerjaan', 'utama')
            ->where(function ($q) use ($currentYear, $currentMonth) {
                $q->where(function ($sq) use ($currentYear, $currentMonth) {
                    $sq->where('tahun', $currentYear)->where('bulan', $currentMonth);
                })->orWhere(function ($sq) use ($currentYear, $currentMonth) {
                    $sq->whereNull('tahun')
                       ->whereYear('tanggal_mulai', $currentYear)
                       ->whereMonth('tanggal_mulai', $currentMonth);
                });
            })
            ->latest()
            ->get();

        // Pegawai yang boleh ditugaskan:
        // Ketua Tim hanya boleh mengalokasikan kepada pegawai dalam timnya ATAU dirinya sendiri
        $pegawais = User::where('is_active', true)
            ->where('role', '!=', 'admin')
            ->where(function ($q) use ($user) {
                $q->where('id', $user->id);
                if ($user->tim_id) {
                    $q->orWhere('tim_id', $user->tim_id);
                }
            })
            ->orderBy('name')
            ->get();

        $timSaya = $user->tim;
        $daftarTahun = $this->getDaftarTahun();
        $daftarBulan = $this->getDaftarBulan();

        return view('alokasi.input', compact('alokasi', 'pegawais', 'timSaya', 'currentYear', 'currentMonth', 'daftarTahun', 'daftarBulan'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama_kegiatan'    => 'required|string|max:255',
            'satuan'           => 'required|string|max:100',
            'target_kuantitas' => 'required|numeric|min:0.01',
            'asal_tim'         => 'required|string|max:100',
            'pegawai_id'       => 'required|exists:users,id',
            'keterangan'       => 'nullable|string',
            'estimasi_waktu'   => 'required|numeric|min:1', // Satuan Jam
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $targetUser = User::findOrFail($validated['pegawai_id']);

        // Validasi Relasi Bisnis: Hanya boleh alokasikan ke diri sendiri atau anggota timnya
        if (!$user->canAllocateTo($targetUser)) {
            return back()->withInput()->with('error', 'Validasi Gagal: Ketua Tim hanya dapat mengalokasikan pekerjaan kepada pegawai yang berada dalam cakupan timnya atau dirinya sendiri.');
        }

        $mulai = Carbon::parse($validated['tanggal_mulai']);
        $validated['created_by']      = $user->id;
        $validated['jenis_pekerjaan'] = 'utama'; // Selalu 'utama' dari form input alokasi
        $validated['tim_id']          = $user->tim_id;
        $validated['tahun']           = $mulai->year;
        $validated['bulan']           = $mulai->month;
        $validated['status']          = 'terjadwal'; // Default status
        $validated['realisasi_kuantitas']  = 0;
        $validated['persentase_kuantitas'] = 0;
        $validated['status_penilaian']     = 'belum_dinilai';

        AlokasiPekerjaan::create($validated);

        return redirect()->route('alokasi.input', ['tahun' => $validated['tahun'], 'bulan' => $validated['bulan']])
                         ->with('success', "Alokasi pekerjaan utama '{$validated['nama_kegiatan']}' berhasil ditugaskan.");
    }

    public function edit($id)
    {
        $user = auth()->user();

        $item = AlokasiPekerjaan::where('created_by', $user->id)->findOrFail($id);

        $pegawais = User::where('is_active', true)
            ->where('role', '!=', 'admin')
            ->where(function ($q) use ($user) {
                $q->where('id', $user->id);
                if ($user->tim_id) {
                    $q->orWhere('tim_id', $user->tim_id);
                }
            })
            ->orderBy('name')
            ->get();

        return view('alokasi.edit', compact('item', 'pegawais'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $item = AlokasiPekerjaan::where('created_by', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'nama_kegiatan'    => 'required|string|max:255',
            'satuan'           => 'required|string|max:100',
            'target_kuantitas' => 'required|numeric|min:0.01',
            'asal_tim'         => 'required|string|max:100',
            'pegawai_id'       => 'required|exists:users,id',
            'keterangan'       => 'nullable|string',
            'estimasi_waktu'   => 'required|numeric|min:1', // Satuan Jam
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $targetUser = User::findOrFail($validated['pegawai_id']);
        if (!$user->canAllocateTo($targetUser)) {
            return back()->withInput()->with('error', 'Validasi Gagal: Ketua Tim hanya dapat mengalokasikan pekerjaan kepada pegawai dalam timnya atau dirinya sendiri.');
        }

        $mulai = Carbon::parse($validated['tanggal_mulai']);
        $validated['tahun'] = $mulai->year;
        $validated['bulan'] = $mulai->month;

        $item->update($validated);

        return redirect()->route('alokasi.input', ['tahun' => $validated['tahun'], 'bulan' => $validated['bulan']])
                         ->with('success', 'Alokasi pekerjaan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = auth()->user();

        $item = AlokasiPekerjaan::where('created_by', $user->id)->findOrFail($id);
        $tahun = $item->tahun;
        $bulan = $item->bulan;
        $item->delete();

        return redirect()->route('alokasi.input', ['tahun' => $tahun, 'bulan' => $bulan])
                         ->with('success', 'Alokasi pekerjaan berhasil dihapus.');
    }

    // =========================================================================
    // H2. DOWNLOAD TEMPLATE EXCEL — Untuk import alokasi banyak anggota
    // =========================================================================

    public function downloadTemplate()
    {
        $user = auth()->user();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Alokasi');

        // Header baris 1: Panduan
        $sheet->setCellValue('A1', 'TEMPLATE IMPORT ALOKASI PEKERJAAN - ' . ($user->tim?->nama_tim ?? 'Tim'));
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF003087']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        // Header baris 2: Panduan pengisian
        $sheet->setCellValue('A2', 'Petunjuk: Isi data mulai baris ke-4. Kolom No diisi otomatis. Jangan ubah baris header (baris 3).');
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 9, 'color' => ['argb' => 'FF555555']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEEF3FB']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, 'indent' => 1],
        ]);

        // Header kolom (baris 3)
        $headers = [
            'A3' => 'No',
            'B3' => 'Nama Kegiatan',
            'C3' => 'Satuan',
            'D3' => 'Target Kuantitas',
            'E3' => 'Asal Tim Penugasan',
            'F3' => 'Pegawai yang Ditugaskan',
            'G3' => 'Catatan/Keterangan Pekerjaan',
            'H3' => 'Estimasi Waktu (Jam)',
            'I3' => 'Tanggal Mulai (YYYY-MM-DD)',
            'J3' => 'Tanggal Selesai (YYYY-MM-DD)',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        $sheet->getStyle('A3:J3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1565C0']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => 'FFAAAAAA']]],
        ]);

        // Contoh data (baris 4)
        $namaTim = $user->tim?->nama_tim ?? 'Tim A';
        $contohData = [
            'A4' => '1',
            'B4' => 'Contoh: Pengolahan Data Survei',
            'C4' => 'Dokumen',
            'D4' => '5',
            'E4' => $namaTim,
            'F4' => 'Nama Lengkap Pegawai',
            'G4' => 'Catatan opsional pekerjaan',
            'H4' => '8',
            'I4' => date('Y-m-d'),
            'J4' => date('Y-m-d', strtotime('+7 days')),
        ];

        foreach ($contohData as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        $sheet->getStyle('A4:J4')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['argb' => 'FF888888']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFAFAFA']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['argb' => 'FFDDDDDD']]],
        ]);

        // Lebar kolom
        $colWidths = ['A' => 5, 'B' => 35, 'C' => 15, 'D' => 17, 'E' => 22, 'F' => 28, 'G' => 35, 'H' => 20, 'I' => 24, 'J' => 24];
        foreach ($colWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }
        $sheet->getRowDimension(3)->setRowHeight(35);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'template_alokasi_pekerjaan_' . date('Ymd') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    // =========================================================================
    // H3. IMPORT EXCEL — Proses import alokasi banyak anggota dari file Excel
    // =========================================================================

    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls|max:5120',
        ], [
            'file_excel.required' => 'File Excel wajib diunggah.',
            'file_excel.mimes'    => 'File harus berformat .xlsx atau .xls.',
            'file_excel.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $user = auth()->user();
        $file = $request->file('file_excel');

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        // Ambil pegawai dalam tim user untuk validasi nama
        $pegawaisInTim = User::where('is_active', true)
            ->where('role', '!=', 'admin')
            ->where(function ($q) use ($user) {
                $q->where('id', $user->id);
                if ($user->tim_id) {
                    $q->orWhere('tim_id', $user->tim_id);
                }
            })
            ->get()
            ->keyBy(fn($u) => strtolower(trim($u->name)));

        $imported = 0;
        $errors   = [];

        // Data mulai baris ke-4 (baris 1: judul, 2: panduan, 3: header)
        foreach ($rows as $rowNum => $row) {
            if ($rowNum < 4) continue;

            $namaKegiatan   = trim($row['B'] ?? '');
            $satuan         = trim($row['C'] ?? '');
            $targetKuantitas = trim($row['D'] ?? '');
            $asalTim        = trim($row['E'] ?? '');
            $namaPegawai    = trim($row['F'] ?? '');
            $keterangan     = trim($row['G'] ?? '');
            $estimasiWaktu  = trim($row['H'] ?? '');
            $tanggalMulai   = trim($row['I'] ?? '');
            $tanggalSelesai = trim($row['J'] ?? '');

            // Lewati baris kosong atau baris contoh
            if (empty($namaKegiatan) || strtolower($namaKegiatan) === 'contoh: pengolahan data survei') continue;

            $rowErrors = [];

            if (empty($satuan))         $rowErrors[] = 'Satuan kosong';
            if (!is_numeric($targetKuantitas) || $targetKuantitas <= 0) $rowErrors[] = 'Target Kuantitas tidak valid';
            if (empty($asalTim))        $rowErrors[] = 'Asal Tim kosong';
            if (empty($namaPegawai))    $rowErrors[] = 'Nama Pegawai kosong';
            if (!is_numeric($estimasiWaktu) || $estimasiWaktu < 1) $rowErrors[] = 'Estimasi Waktu tidak valid (min 1 jam)';
            if (empty($tanggalMulai))   $rowErrors[] = 'Tanggal Mulai kosong';
            if (empty($tanggalSelesai)) $rowErrors[] = 'Tanggal Selesai kosong';

            // Cari pegawai berdasarkan nama
            $targetUser = $pegawaisInTim[strtolower($namaPegawai)] ?? null;
            if (!$targetUser) {
                $rowErrors[] = "Pegawai '{$namaPegawai}' tidak ditemukan dalam tim Anda";
            }

            if (!empty($rowErrors)) {
                $errors[] = "Baris {$rowNum}: " . implode(', ', $rowErrors);
                continue;
            }

            try {
                $mulai = Carbon::parse($tanggalMulai);
                $selesai = Carbon::parse($tanggalSelesai);

                if ($selesai->lt($mulai)) {
                    $errors[] = "Baris {$rowNum}: Tanggal Selesai harus setelah atau sama dengan Tanggal Mulai";
                    continue;
                }

                AlokasiPekerjaan::create([
                    'nama_kegiatan'         => $namaKegiatan,
                    'satuan'                => $satuan,
                    'target_kuantitas'      => (float) $targetKuantitas,
                    'asal_tim'              => $asalTim,
                    'pegawai_id'            => $targetUser->id,
                    'keterangan'            => $keterangan ?: null,
                    'estimasi_waktu'        => (float) $estimasiWaktu,
                    'tanggal_mulai'         => $mulai->toDateString(),
                    'tanggal_selesai'       => $selesai->toDateString(),
                    'status'                => 'terjadwal',
                    'jenis_pekerjaan'       => 'utama',
                    'created_by'            => $user->id,
                    'tim_id'                => $user->tim_id,
                    'tahun'                 => $mulai->year,
                    'bulan'                 => $mulai->month,
                    'realisasi_kuantitas'   => 0,
                    'persentase_kuantitas'  => 0,
                    'status_penilaian'      => 'belum_dinilai',
                ]);

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$rowNum}: Gagal disimpan - " . $e->getMessage();
            }
        }

        $message = "Berhasil mengimpor {$imported} data alokasi pekerjaan.";
        if (!empty($errors)) {
            $message .= ' Beberapa baris gagal: ' . implode(' | ', array_slice($errors, 0, 5));
            if (count($errors) > 5) $message .= ' (dan ' . (count($errors) - 5) . ' error lainnya)';
        }

        $sessionKey = $imported > 0 ? 'success' : 'error';
        return redirect()->route('alokasi.input')->with($sessionKey, $message);
    }

    // =========================================================================
    // I. ALOKASI TIM SAYA — Ketua Tim, Kepala BPS, Pegawai (Read-only cakupan)
    // =========================================================================

    public function tim(Request $request)
    {
        $user = auth()->user();

        $currentYear = (int) $request->input('tahun', Carbon::now()->year);
        $currentMonth = (int) $request->input('bulan', Carbon::now()->month);

        $query = AlokasiPekerjaan::with(['pegawai', 'pembuat', 'tim']);

        // Periode filter
        $query->where(function ($q) use ($currentYear, $currentMonth) {
            $q->where(function ($sq) use ($currentYear, $currentMonth) {
                $sq->where('tahun', $currentYear)->where('bulan', $currentMonth);
            })->orWhere(function ($sq) use ($currentYear, $currentMonth) {
                $sq->whereNull('tahun')
                   ->whereYear('tanggal_mulai', $currentYear)
                   ->whereMonth('tanggal_mulai', $currentMonth);
            });
        });

        // Deteksi tim user jika ada
        $userTim = $user->tim ?? Team::where('ketua_id', $user->id)->first();
        $userTimId = $userTim ? $userTim->id : null;
        $selectedAsalTim = $request->has('asal_tim') ? $request->input('asal_tim') : ($userTim ? $userTim->nama_tim : null);

        // Filter: Asal Tim / Tim
        if ($selectedAsalTim) {
            $query->where(function ($q) use ($selectedAsalTim) {
                $q->where('asal_tim', $selectedAsalTim)
                  ->orWhereHas('tim', function ($sq) use ($selectedAsalTim) {
                      $sq->where('nama_tim', $selectedAsalTim);
                  });
            });
        } elseif (($user->isKetuaTim() || $user->isPegawai()) && $userTimId) {
            // Jika memilih Semua Tim atau belum pilih, default alokasi pekerjaan untuk dirinya sendiri dan anggota timnya
            $anggotaTimIds = User::where('tim_id', $userTimId)->pluck('id')->push($user->id)->unique()->toArray();
            $query->where(function ($q) use ($userTimId, $anggotaTimIds) {
                $q->whereIn('pegawai_id', $anggotaTimIds)
                  ->orWhere('tim_id', $userTimId);
            });
        }

        // Filter: Pegawai
        if ($request->filled('pegawai_id')) {
            $query->where('pegawai_id', $request->pegawai_id);
        }

        // Filter: Jenis Pekerjaan (Utama / Tambahan)
        if ($request->filled('jenis_pekerjaan')) {
            $query->where('jenis_pekerjaan', $request->jenis_pekerjaan);
        }

        // Filter: Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $alokasi = $query->orderBy('tanggal_mulai', 'asc')->get();

        $daftarTim = Team::where('is_active', true)->orderBy('nama_tim')->get();
        $daftarPegawai = User::where('is_active', true)->whereNotIn('role', ['admin'])->orderBy('name')->get();

        $daftarTahun = $this->getDaftarTahun();
        $daftarBulan = $this->getDaftarBulan();

        return view('alokasi.tim', compact(
            'alokasi',
            'daftarTim',
            'daftarPegawai',
            'selectedAsalTim',
            'currentYear',
            'currentMonth',
            'daftarTahun',
            'daftarBulan'
        ));
    }

    // =========================================================================
    // J. ALOKASI PEKERJAAN PEGAWAI — Ringkasan per Pegawai & Detail Interaktif
    // =========================================================================

    public function pegawai(Request $request)
    {
        $currentYear = (int) $request->input('tahun', Carbon::now()->year);
        $currentMonth = (int) $request->input('bulan', Carbon::now()->month);
        $timId = $request->filled('tim_id') ? (int) $request->tim_id : null;

        // Ambil rekap kinerja dan alokasi seluruh pegawai dari KinerjaService
        $rekapPegawai = KinerjaService::rekapKinerjaSemuaPegawai($currentYear, $currentMonth, $timId);

        $daftarTim = Team::where('is_active', true)->orderBy('nama_tim')->get();
        $daftarTahun = $this->getDaftarTahun();
        $daftarBulan = $this->getDaftarBulan();

        return view('alokasi.pegawai', compact(
            'rekapPegawai',
            'daftarTim',
            'currentYear',
            'currentMonth',
            'timId',
            'daftarTahun',
            'daftarBulan'
        ));
    }

    // =========================================================================
    // P. KALENDER PEKERJAAN — Membedakan Pekerjaan Utama & Pekerjaan Tambahan
    // =========================================================================

    public function kalender(Request $request)
    {
        $user = auth()->user();

        $query = AlokasiPekerjaan::with(['pegawai', 'pembuat', 'tim']);

        // Jika pegawai biasa, tampilkan pekerjaan dirinya dan timnya
        if ($user->isPegawai()) {
            $query->where(function ($q) use ($user) {
                $q->where('pegawai_id', $user->id)
                  ->orWhere('tim_id', $user->tim_id);
            });
        }

        $alokasi = $query->get();

        return view('alokasi.kalender', compact('alokasi'));
    }
}
