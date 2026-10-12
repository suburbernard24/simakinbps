<?php

namespace App\Http\Controllers;

use App\Models\AlokasiMitra;
use App\Models\Mitra;
use App\Models\PengaturanHonorMitra;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AlokasiMitraController extends Controller
{
    /**
     * Tampilan daftar alokasi pekerjaan mitra untuk Ketua Tim.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Validasi otoritas: hanya Ketua Tim yang boleh mengelola
        if (!$user->isKetuaTim()) {
            abort(403, 'Akses Ditolak: Hanya Ketua Tim yang berwenang mengelola alokasi pekerjaan mitra.');
        }

        $currentYear = (int) $request->input('tahun', Carbon::now()->year);
        $currentMonth = (int) $request->input('bulan', Carbon::now()->month);

        $query = AlokasiMitra::with(['mitra', 'tim', 'pembuat', 'penilaians.penilai'])
            ->where('created_by', $user->id)
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

        if ($request->filled('mitra_id')) {
            $query->where('mitra_id', $request->mitra_id);
        }

        $alokasiList = $query->latest('tanggal_mulai')->get();

        // Mitra aktif yang siap dialokasikan
        $mitras = Mitra::where('is_active', true)->orderBy('nama')->get();
        $maksimalHonor = PengaturanHonorMitra::getMaksimalHonor($currentYear, $currentMonth);

        $daftarTahun = $this->getDaftarTahun();
        $daftarBulan = $this->getDaftarBulan();

        return view('alokasi_mitra.index', compact(
            'alokasiList',
            'mitras',
            'currentYear',
            'currentMonth',
            'maksimalHonor',
            'daftarTahun',
            'daftarBulan'
        ));
    }

    /**
     * Simpan alokasi pekerjaan 1 orang mitra (Web Form).
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->isKetuaTim()) {
            abort(403, 'Akses Ditolak: Hanya Ketua Tim yang berwenang menambah alokasi pekerjaan mitra.');
        }

        // Sanitasi honor jika dikirim berformat ribuan
        $rawHonor = $request->input('honor');
        if (is_string($rawHonor)) {
            $request->merge(['honor' => preg_replace('/[^\d]/', '', $rawHonor)]);
        }

        $validated = $request->validate([
            'mitra_id'        => 'required|exists:mitras,id',
            'nama_kegiatan'   => 'required|string|max:255',
            'kegiatan'        => 'required|string|max:255',
            'posisi'          => 'required|string|max:100',
            'satuan'          => 'required|string|max:100',
            'target_kuantitas'=> 'required|numeric|min:0.01',
            'honor'           => 'required|numeric|min:0',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status'          => 'nullable|in:terjadwal,berjalan,selesai,terlambat,diterima',
            'keterangan'      => 'nullable|string',
        ], [
            'mitra_id.required'         => 'Mitra statistik wajib dipilih.',
            'nama_kegiatan.required'    => 'Nama kegiatan / survei wajib diisi.',
            'kegiatan.required'         => 'Uraian rincian kegiatan wajib diisi.',
            'posisi.required'           => 'Posisi penugasan mitra wajib diisi.',
            'satuan.required'           => 'Satuan target wajib diisi.',
            'target_kuantitas.required' => 'Target kuantitas wajib diisi.',
            'honor.required'            => 'Nominal honor mitra wajib diisi.',
            'tanggal_mulai.required'    => 'Tanggal mulai penugasan wajib diisi.',
            'tanggal_selesai.required'  => 'Tanggal selesai penugasan wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ]);

        $mitra = Mitra::findOrFail($validated['mitra_id']);
        $mulai = Carbon::parse($validated['tanggal_mulai']);
        $tahun = $mulai->year;
        $bulan = $mulai->month;
        $honorBaru = (int) $validated['honor'];

        // =========================================================================
        // VALIDASI ATURAN BISNIS: MAKSIMAL HONOR BULANAN MITRA
        // =========================================================================
        $sisaKuota = $mitra->sisaKuotaHonor($tahun, $bulan);
        $maksimalHonor = PengaturanHonorMitra::getMaksimalHonor($tahun, $bulan);

        if ($honorBaru > $sisaKuota) {
            $honorFmt = 'Rp ' . number_format($honorBaru, 0, ',', '.');
            $sisaFmt  = 'Rp ' . number_format($sisaKuota, 0, ',', '.');
            $maxFmt   = 'Rp ' . number_format($maksimalHonor, 0, ',', '.');

            return back()->withInput()->with('error', "Validasi Batas Honor Gagal: Total honor mitra '{$mitra->nama}' untuk periode {$bulan}/{$tahun} melebihi batas maksimal bulanan ({$maxFmt}). Kuota honor yang tersisa untuk mitra ini hanya {$sisaFmt}, sedangkan alokasi baru sebesar {$honorFmt}.");
        }

        AlokasiMitra::create([
            'mitra_id'         => $mitra->id,
            'tim_id'           => $user->tim_id,
            'created_by'       => $user->id,
            'nama_kegiatan'    => $validated['nama_kegiatan'],
            'kegiatan'         => $validated['kegiatan'],
            'posisi'           => $validated['posisi'],
            'satuan'           => $validated['satuan'],
            'target_kuantitas' => (float) $validated['target_kuantitas'],
            'honor'            => $honorBaru,
            'tanggal_mulai'    => $mulai->toDateString(),
            'tanggal_selesai'  => Carbon::parse($validated['tanggal_selesai'])->toDateString(),
            'tahun'            => $tahun,
            'bulan'            => $bulan,
            'status'           => $validated['status'] ?? 'terjadwal',
            'provinsi'         => $mitra->provinsi,
            'kabupaten'        => $mitra->kabupaten,
            'keterangan'       => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('kinerja-mitra.alokasi.index', ['tahun' => $tahun, 'bulan' => $bulan])
            ->with('success', "Alokasi pekerjaan untuk mitra '{$mitra->nama}' berhasil disimpan.");
    }

    /**
     * Form edit alokasi pekerjaan mitra.
     */
    public function edit($id)
    {
        $user = auth()->user();
        if (!$user->isKetuaTim()) {
            abort(403, 'Akses Ditolak: Hanya Ketua Tim yang berwenang mengubah alokasi pekerjaan mitra.');
        }

        $alokasi = AlokasiMitra::where('created_by', $user->id)->findOrFail($id);
        $mitras = Mitra::where('is_active', true)->orderBy('nama')->get();
        $maksimalHonor = PengaturanHonorMitra::getMaksimalHonor($alokasi->tahun, $alokasi->bulan);
        $sisaKuotaSaatIni = $alokasi->mitra->sisaKuotaHonor($alokasi->tahun, $alokasi->bulan, $alokasi->id);

        return view('alokasi_mitra.edit', compact('alokasi', 'mitras', 'maksimalHonor', 'sisaKuotaSaatIni'));
    }

    /**
     * Update alokasi pekerjaan mitra.
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user->isKetuaTim()) {
            abort(403, 'Akses Ditolak: Hanya Ketua Tim yang berwenang mengubah alokasi pekerjaan mitra.');
        }

        $alokasi = AlokasiMitra::where('created_by', $user->id)->findOrFail($id);

        $rawHonor = $request->input('honor');
        if (is_string($rawHonor)) {
            $request->merge(['honor' => preg_replace('/[^\d]/', '', $rawHonor)]);
        }

        $validated = $request->validate([
            'mitra_id'        => 'required|exists:mitras,id',
            'nama_kegiatan'   => 'required|string|max:255',
            'kegiatan'        => 'required|string|max:255',
            'posisi'          => 'required|string|max:100',
            'satuan'          => 'required|string|max:100',
            'target_kuantitas'=> 'required|numeric|min:0.01',
            'honor'           => 'required|numeric|min:0',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status'          => 'required|in:terjadwal,berjalan,selesai,terlambat,diterima',
            'keterangan'      => 'nullable|string',
        ]);

        $mitra = Mitra::findOrFail($validated['mitra_id']);
        $mulai = Carbon::parse($validated['tanggal_mulai']);
        $tahun = $mulai->year;
        $bulan = $mulai->month;
        $honorBaru = (int) $validated['honor'];

        // Cek sisa kuota honor dengan mengabaikan alokasi saat ini
        $sisaKuota = $mitra->sisaKuotaHonor($tahun, $bulan, $alokasi->id);
        $maksimalHonor = PengaturanHonorMitra::getMaksimalHonor($tahun, $bulan);

        if ($honorBaru > $sisaKuota) {
            $honorFmt = 'Rp ' . number_format($honorBaru, 0, ',', '.');
            $sisaFmt  = 'Rp ' . number_format($sisaKuota, 0, ',', '.');
            $maxFmt   = 'Rp ' . number_format($maksimalHonor, 0, ',', '.');

            return back()->withInput()->with('error', "Validasi Batas Honor Gagal: Total honor mitra '{$mitra->nama}' untuk periode {$bulan}/{$tahun} melebihi batas maksimal ({$maxFmt}). Kuota honor yang tersisa adalah {$sisaFmt}.");
        }

        $alokasi->update([
            'mitra_id'         => $mitra->id,
            'nama_kegiatan'    => $validated['nama_kegiatan'],
            'kegiatan'         => $validated['kegiatan'],
            'posisi'           => $validated['posisi'],
            'satuan'           => $validated['satuan'],
            'target_kuantitas' => (float) $validated['target_kuantitas'],
            'honor'            => $honorBaru,
            'tanggal_mulai'    => $mulai->toDateString(),
            'tanggal_selesai'  => Carbon::parse($validated['tanggal_selesai'])->toDateString(),
            'tahun'            => $tahun,
            'bulan'            => $bulan,
            'status'           => $validated['status'],
            'keterangan'       => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('kinerja-mitra.alokasi.index', ['tahun' => $tahun, 'bulan' => $bulan])
            ->with('success', "Alokasi pekerjaan mitra '{$mitra->nama}' berhasil diperbarui.");
    }

    /**
     * Hapus alokasi pekerjaan mitra.
     */
    public function destroy($id)
    {
        $user = auth()->user();
        if (!$user->isKetuaTim()) {
            abort(403, 'Akses Ditolak: Hanya Ketua Tim yang berwenang menghapus alokasi pekerjaan mitra.');
        }

        $alokasi = AlokasiMitra::where('created_by', $user->id)->findOrFail($id);
        $tahun = $alokasi->tahun;
        $bulan = $alokasi->bulan;
        $namaMitra = $alokasi->mitra->nama ?? 'Mitra';
        $namaKegiatan = $alokasi->nama_kegiatan;

        $alokasi->delete();

        return redirect()->route('kinerja-mitra.alokasi.index', ['tahun' => $tahun, 'bulan' => $bulan])
            ->with('success', "Alokasi '{$namaKegiatan}' untuk mitra {$namaMitra} berhasil dihapus.");
    }

    /**
     * Unduh Template Excel untuk Alokasi Banyak Mitra Sekaligus.
     */
    public function downloadTemplate()
    {
        $user = auth()->user();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Alokasi Mitra');

        // Judul baris 1
        $sheet->setCellValue('A1', 'TEMPLATE IMPORT ALOKASI PEKERJAAN MITRA - ' . ($user->tim?->nama_tim ?? 'BPS'));
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF003087']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(36);

        // Panduan baris 2
        $sheet->setCellValue('A2', 'Petunjuk: Isi data alokasi mulai baris ke-4. Sobat-ID atau Nama Mitra harus cocok dengan data mitra. Honor dalam angka murni (tanpa titik/koma). Baris 4 adalah contoh.');
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 9, 'color' => ['argb' => 'FF475569']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEEF3FB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'indent' => 1],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(25);

        // Header baris 3
        $headers = [
            'A3' => 'No',
            'B3' => 'Sobat-ID / Nama Mitra (*)',
            'C3' => 'Survei / Kegiatan Utama (*)',
            'D3' => 'Rincian Kegiatan (*)',
            'E3' => 'Posisi Penugasan (*)',
            'F3' => 'Satuan Target (*)',
            'G3' => 'Target Kuantitas (*)',
            'H3' => 'Honor (IDR Rupiah) (*)',
            'I3' => 'Tanggal Mulai (YYYY-MM-DD) (*)',
            'J3' => 'Tanggal Selesai (YYYY-MM-DD) (*)',
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        $sheet->getStyle('A3:J3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1565C0']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCBD5E1']]],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(32);

        // Contoh baris 4
        $contohMitra = Mitra::first();
        $contohSobatId = $contohMitra ? $contohMitra->sobat_id : '5310-M001';
        $contohData = [
            'A4' => '1',
            'B4' => $contohSobatId,
            'C4' => 'Survei Sosial Ekonomi Nasional (Susenas)',
            'D4' => 'Pencacahan Lapangan Blok Sensus Terpilih',
            'E4' => 'Petugas Pencacah Lapangan (PCL)',
            'F4' => 'Dokumen',
            'G4' => '10',
            'H4' => '2500000',
            'I4' => date('Y-m-01'),
            'J4' => date('Y-m-20'),
        ];
        foreach ($contohData as $cell => $val) {
            $sheet->setCellValueExplicit($cell, $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        }
        $sheet->getStyle('A4:J4')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['argb' => 'FF64748B']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF8FAFC']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE2E8F0']]],
        ]);

        $colWidths = ['A' => 6, 'B' => 26, 'C' => 30, 'D' => 32, 'E' => 28, 'F' => 16, 'G' => 16, 'H' => 20, 'I' => 22, 'J' => 22];
        foreach ($colWidths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'template_alokasi_mitra_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Import alokasi banyak mitra sekaligus dari file Excel dengan validasi per baris & batas maksimal honor.
     */
    public function importExcel(Request $request)
    {
        $user = auth()->user();
        if (!$user->isKetuaTim()) {
            abort(403, 'Akses Ditolak: Hanya Ketua Tim yang berwenang mengimpor alokasi pekerjaan mitra.');
        }

        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls|max:5120',
        ], [
            'file_excel.required' => 'File Excel wajib diunggah.',
            'file_excel.mimes'    => 'Format file harus .xlsx atau .xls.',
            'file_excel.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $file = $request->file('file_excel');

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
        } catch (\Exception $e) {
            return redirect()->route('kinerja-mitra.alokasi.index')
                ->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        // Cache mitra aktif untuk pencarian cepat berdasarkan sobat_id atau nama
        $allMitras = Mitra::where('is_active', true)->get();
        $mitraBySobatId = $allMitras->keyBy(fn($m) => strtolower(trim($m->sobat_id)));
        $mitraByName = $allMitras->keyBy(fn($m) => strtolower(trim($m->nama)));

        $imported = 0;
        $errors   = [];

        // Lacak alokasi honor tambahan dalam batch import yang sama per mitra per (tahun-bulan)
        $batchHonorUsage = [];

        foreach ($rows as $rowNum => $row) {
            if ($rowNum < 4) continue;

            $mitraInput     = trim($row['B'] ?? '');
            $namaKegiatan   = trim($row['C'] ?? '');
            $rincianKegiatan= trim($row['D'] ?? '');
            $posisi         = trim($row['E'] ?? '');
            $satuan         = trim($row['F'] ?? '');
            $targetKuantitas= trim($row['G'] ?? '');
            $honorRaw       = trim($row['H'] ?? '');
            $tanggalMulai   = trim($row['I'] ?? '');
            $tanggalSelesai = trim($row['J'] ?? '');

            // Lewati baris kosong atau baris contoh
            if (empty($namaKegiatan) || strtolower($namaKegiatan) === 'survei sosial ekonomi nasional (susenas)') {
                continue;
            }

            $rowErrors = [];

            if (empty($mitraInput))      $rowErrors[] = 'Kolom Mitra / Sobat-ID kosong';
            if (empty($rincianKegiatan)) $rowErrors[] = 'Rincian Kegiatan kosong';
            if (empty($posisi))          $rowErrors[] = 'Posisi kosong';
            if (empty($satuan))          $rowErrors[] = 'Satuan kosong';
            if (!is_numeric($targetKuantitas) || (float)$targetKuantitas <= 0) $rowErrors[] = 'Target Kuantitas tidak valid';
            
            $honorClean = preg_replace('/[^\d]/', '', $honorRaw);
            if (!is_numeric($honorClean) || (int)$honorClean < 0) $rowErrors[] = 'Honor tidak valid';
            if (empty($tanggalMulai))    $rowErrors[] = 'Tanggal Mulai kosong';
            if (empty($tanggalSelesai))  $rowErrors[] = 'Tanggal Selesai kosong';

            // Temukan mitra
            $mitra = $mitraBySobatId[strtolower($mitraInput)] ?? $mitraByName[strtolower($mitraInput)] ?? null;
            if (!$mitra) {
                $rowErrors[] = "Mitra '{$mitraInput}' tidak ditemukan atau tidak aktif";
            }

            if (!empty($rowErrors)) {
                $errors[] = "Baris {$rowNum}: " . implode(', ', $rowErrors);
                continue;
            }

            try {
                $mulai = Carbon::parse($tanggalMulai);
                $selesai = Carbon::parse($tanggalSelesai);

                if ($selesai->lt($mulai)) {
                    $errors[] = "Baris {$rowNum}: Tanggal selesai harus sama atau setelah tanggal mulai";
                    continue;
                }

                $tahun = $mulai->year;
                $bulan = $mulai->month;
                $honor = (int) $honorClean;
                $keyTracking = "{$mitra->id}_{$tahun}_{$bulan}";

                $maksimalHonor = PengaturanHonorMitra::getMaksimalHonor($tahun, $bulan);
                $sisaKuotaDb = $mitra->sisaKuotaHonor($tahun, $bulan);
                $sudahDipakaiBatch = $batchHonorUsage[$keyTracking] ?? 0;
                $sisaKuotaNyata = $sisaKuotaDb - $sudahDipakaiBatch;

                if ($honor > $sisaKuotaNyata) {
                    $sisaFmt = 'Rp ' . number_format(max(0, $sisaKuotaNyata), 0, ',', '.');
                    $honorFmt = 'Rp ' . number_format($honor, 0, ',', '.');
                    $errors[] = "Baris {$rowNum}: Honor {$honorFmt} melebihi batas maksimal untuk '{$mitra->nama}' (sisa kuota: {$sisaFmt})";
                    continue;
                }

                AlokasiMitra::create([
                    'mitra_id'         => $mitra->id,
                    'tim_id'           => $user->tim_id,
                    'created_by'       => $user->id,
                    'nama_kegiatan'    => $namaKegiatan,
                    'kegiatan'         => $rincianKegiatan,
                    'posisi'           => $posisi,
                    'satuan'           => $satuan,
                    'target_kuantitas' => (float) $targetKuantitas,
                    'honor'            => $honor,
                    'tanggal_mulai'    => $mulai->toDateString(),
                    'tanggal_selesai'  => $selesai->toDateString(),
                    'tahun'            => $tahun,
                    'bulan'            => $bulan,
                    'status'           => 'terjadwal',
                    'provinsi'         => $mitra->provinsi,
                    'kabupaten'        => $mitra->kabupaten,
                ]);

                $batchHonorUsage[$keyTracking] = ($batchHonorUsage[$keyTracking] ?? 0) + $honor;
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$rowNum}: Gagal disimpan - " . $e->getMessage();
            }
        }

        $msg = "Berhasil mengimpor {$imported} data alokasi pekerjaan mitra.";
        if (!empty($errors)) {
            $msg .= ' Beberapa baris gagal: ' . implode(' | ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $msg .= ' (dan ' . (count($errors) - 5) . ' error lainnya)';
            }
        }

        return redirect()->route('kinerja-mitra.alokasi.index')
            ->with($imported > 0 ? 'success' : 'error', $msg);
    }
}
