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
        $maksimalHonorPendataan = PengaturanHonorMitra::getMaksimalHonor(PengaturanHonorMitra::JENIS_PENDATAAN, $currentYear, $currentMonth);
        $maksimalHonorPengolahan = PengaturanHonorMitra::getMaksimalHonor(PengaturanHonorMitra::JENIS_PENGOLAHAN, $currentYear, $currentMonth);
        $maksimalHonor = $maksimalHonorPendataan; // backward compat untuk view

        $daftarTahun = $this->getDaftarTahun();
        $daftarBulan = $this->getDaftarBulan();

        return view('alokasi_mitra.index', compact(
            'alokasiList',
            'mitras',
            'currentYear',
            'currentMonth',
            'maksimalHonor',
            'maksimalHonorPendataan',
            'maksimalHonorPengolahan',
            'daftarTahun',
            'daftarBulan'
        ));
    }

    /**
     * Simpan alokasi pekerjaan mitra (Web Form) — mendukung multi-row kegiatan per mitra.
     * Format baru: Mitra dipilih sekali, kemudian bisa menginput banyak baris kegiatan.
     * Setiap baris: kegiatan, volume (target_kuantitas), satuan, harga_satuan, tanggal, akun_pok.
     * Honor total per baris = volume × harga_satuan.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->isKetuaTim()) {
            abort(403, 'Akses Ditolak: Hanya Ketua Tim yang berwenang menambah alokasi pekerjaan mitra.');
        }

        $request->validate([
            'mitra_id'              => 'required|exists:mitras,id',
            'posisi'                => 'required|string|max:100',
            'jenis_mitra'           => 'required|in:pendataan_lapangan,pengolahan',
            'rows'                  => 'required|array|min:1',
            'rows.*.kegiatan'       => 'required|string|max:500',
            'rows.*.volume'         => 'required|numeric|min:0.01',
            'rows.*.satuan'         => 'required|string|max:100',
            'rows.*.harga_satuan'   => 'required|numeric|min:0',
            'rows.*.tanggal_mulai'  => 'required|date',
            'rows.*.tanggal_selesai'=> 'required|date|after_or_equal:rows.*.tanggal_mulai',
            'rows.*.akun_pok'       => 'nullable|string|max:255',
        ], [
            'mitra_id.required'             => 'Mitra statistik wajib dipilih.',
            'posisi.required'               => 'Posisi penugasan mitra wajib diisi.',
            'jenis_mitra.required'          => 'Jenis mitra wajib dipilih.',
            'rows.required'                 => 'Minimal satu baris kegiatan wajib diisi.',
            'rows.*.kegiatan.required'      => 'Uraian kegiatan wajib diisi.',
            'rows.*.volume.required'        => 'Volume wajib diisi.',
            'rows.*.satuan.required'        => 'Satuan wajib diisi.',
            'rows.*.harga_satuan.required'  => 'Harga satuan wajib diisi.',
            'rows.*.tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'rows.*.tanggal_selesai.required'=> 'Tanggal selesai wajib diisi.',
        ]);

        $mitra     = Mitra::findOrFail($request->mitra_id);
        $jenisMitra= $request->jenis_mitra;
        $posisi    = $request->posisi;

        // Hitung total honor batch baru ini
        $totalHonorBatch = 0;
        $rowsData = [];
        foreach ($request->rows as $i => $row) {
            if (empty(trim($row['kegiatan'] ?? ''))) continue;
            $volume      = (float) $row['volume'];
            $hargaSatuan = (int) preg_replace('/[^\d]/', '', $row['harga_satuan']);
            $honor       = (int) round($volume * $hargaSatuan);
            $mulai       = Carbon::parse($row['tanggal_mulai']);
            $totalHonorBatch += $honor;
            $rowsData[] = [
                'kegiatan'       => trim($row['kegiatan']),
                'volume'         => $volume,
                'satuan'         => trim($row['satuan']),
                'harga_satuan'   => $hargaSatuan,
                'honor'          => $honor,
                'tanggal_mulai'  => $mulai->toDateString(),
                'tanggal_selesai'=> Carbon::parse($row['tanggal_selesai'])->toDateString(),
                'tahun'          => $mulai->year,
                'bulan'          => $mulai->month,
                'akun_pok'       => trim($row['akun_pok'] ?? '') ?: null,
            ];
        }

        if (empty($rowsData)) {
            return back()->withInput()->with('error', 'Minimal satu baris kegiatan harus diisi.');
        }

        // Validasi batas honor: gunakan tanggal_mulai baris pertama sebagai referensi bulan
        $refTahun = $rowsData[0]['tahun'];
        $refBulan = $rowsData[0]['bulan'];
        $sisaKuota     = $mitra->sisaKuotaHonor($refTahun, $refBulan, null, $jenisMitra);
        $maksimalHonor = PengaturanHonorMitra::getMaksimalHonor($jenisMitra, $refTahun, $refBulan);

        if ($totalHonorBatch > $sisaKuota) {
            $honorFmt = 'Rp ' . number_format($totalHonorBatch, 0, ',', '.');
            $sisaFmt  = 'Rp ' . number_format($sisaKuota, 0, ',', '.');
            $maxFmt   = 'Rp ' . number_format($maksimalHonor, 0, ',', '.');
            return back()->withInput()->with('error', "Validasi Batas Honor Gagal: Total honor batch ({$honorFmt}) melebihi batas maksimal bulanan ({$maxFmt}). Sisa kuota mitra '{$mitra->nama}': {$sisaFmt}.");
        }

        // Simpan semua baris
        $namaKegiatan = $mitra->nama . ' - ' . now()->format('M Y');
        foreach ($rowsData as $row) {
            AlokasiMitra::create([
                'mitra_id'         => $mitra->id,
                'tim_id'           => $user->tim_id,
                'created_by'       => $user->id,
                'nama_kegiatan'    => $namaKegiatan,
                'kegiatan'         => $row['kegiatan'],
                'posisi'           => $posisi,
                'jenis_mitra'      => $jenisMitra,
                'satuan'           => $row['satuan'],
                'harga_satuan'     => $row['harga_satuan'],
                'target_kuantitas' => $row['volume'],
                'honor'            => $row['honor'],
                'tanggal_mulai'    => $row['tanggal_mulai'],
                'tanggal_selesai'  => $row['tanggal_selesai'],
                'tahun'            => $row['tahun'],
                'bulan'            => $row['bulan'],
                'status'           => 'terjadwal',
                'provinsi'         => $mitra->provinsi,
                'kabupaten'        => $mitra->kabupaten,
                'akun_pok'         => $row['akun_pok'],
            ]);
        }

        return redirect()->route('kinerja-mitra.alokasi.index', ['tahun' => $refTahun, 'bulan' => $refBulan])
            ->with('success', count($rowsData) . " kegiatan alokasi pekerjaan untuk mitra '{$mitra->nama}' berhasil disimpan.");
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
        $jenisMitra = $alokasi->jenis_mitra ?? PengaturanHonorMitra::JENIS_PENDATAAN;
        $maksimalHonor = PengaturanHonorMitra::getMaksimalHonor($jenisMitra, $alokasi->tahun, $alokasi->bulan);
        $sisaKuotaSaatIni = $alokasi->mitra->sisaKuotaHonor($alokasi->tahun, $alokasi->bulan, $alokasi->id, $jenisMitra);

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

        // Sanitasi harga_satuan
        $rawHargaSatuan = $request->input('harga_satuan', 0);
        $request->merge(['harga_satuan' => preg_replace('/[^\d]/', '', (string) $rawHargaSatuan)]);

        $validated = $request->validate([
            'mitra_id'        => 'required|exists:mitras,id',
            'nama_kegiatan'   => 'required|string|max:500',
            'kegiatan'        => 'required|string|max:500',
            'posisi'          => 'required|string|max:100',
            'jenis_mitra'     => 'required|in:pendataan_lapangan,pengolahan',
            'satuan'          => 'required|string|max:100',
            'harga_satuan'    => 'required|numeric|min:0',
            'target_kuantitas'=> 'required|numeric|min:0.01',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status'          => 'required|in:terjadwal,berjalan,selesai,terlambat,diterima',
            'akun_pok'        => 'nullable|string|max:255',
            'keterangan'      => 'nullable|string',
        ]);

        $mitra = Mitra::findOrFail($validated['mitra_id']);
        $mulai = Carbon::parse($validated['tanggal_mulai']);
        $tahun = $mulai->year;
        $bulan = $mulai->month;
        $hargaSatuan = (int) $validated['harga_satuan'];
        $volume      = (float) $validated['target_kuantitas'];
        $honorBaru   = (int) round($volume * $hargaSatuan);

        // Cek sisa kuota honor dengan mengabaikan alokasi saat ini
        $jenisMitra = $validated['jenis_mitra'];
        $sisaKuota = $mitra->sisaKuotaHonor($tahun, $bulan, $alokasi->id, $jenisMitra);
        $maksimalHonor = PengaturanHonorMitra::getMaksimalHonor($jenisMitra, $tahun, $bulan);

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
            'jenis_mitra'      => $validated['jenis_mitra'],
            'satuan'           => $validated['satuan'],
            'harga_satuan'     => $hargaSatuan,
            'target_kuantitas' => $volume,
            'honor'            => $honorBaru,
            'tanggal_mulai'    => $mulai->toDateString(),
            'tanggal_selesai'  => Carbon::parse($validated['tanggal_selesai'])->toDateString(),
            'tahun'            => $tahun,
            'bulan'            => $bulan,
            'status'           => $validated['status'],
            'akun_pok'         => $validated['akun_pok'] ?? null,
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

        $alokasi = AlokasiMitra::findOrFail($id);
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
     * Format sesuai laporan ALOKASI MITRA BPS:
     * No | Nama / Sobat-ID | Kegiatan | Volume | Satuan | Harga Satuan | Jumlah | Tanggal Pelaksanaan | Akun POK | Jenis Mitra
     */
    public function downloadTemplate()
    {
        $user = auth()->user();
        $kabupaten = $user->tim?->kabupaten ?? 'BPS';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Alokasi Mitra');

        // ── Baris 1: Judul utama
        $sheet->setCellValue('A1', 'ALOKASI MITRA');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FF1E3A5F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // ── Baris 2: Nama BPS
        $sheet->setCellValue('A2', 'BPS ' . strtoupper($kabupaten));
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['argb' => 'FF1E3A5F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // ── Baris 3: Bulan
        $sheet->setCellValue('A3', 'Bulan : ' . now()->translatedFormat('F Y'));
        $sheet->mergeCells('A3:J3');
        $sheet->getStyle('A3')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FF334155']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(18);

        // ── Baris 4: Panduan
        $sheet->setCellValue('A4', 'Petunjuk: Isi data mulai baris ke-6. Kolom Mitra: Sobat-ID atau Nama Mitra. Harga Satuan dalam angka murni. Jumlah dihitung otomatis. Baris 6 adalah contoh.');
        $sheet->mergeCells('A4:J4');
        $sheet->getStyle('A4')->applyFromArray([
            'font'  => ['italic' => true, 'size' => 8, 'color' => ['argb' => 'FF64748B']],
            'fill'  => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFF8E1']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 1],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(18);

        // ── Baris 5: Header kolom
        $headers = [
            'A5' => 'No',
            'B5' => 'Nama / Sobat-ID Mitra (*)',
            'C5' => 'Kegiatan (*)',
            'D5' => 'Volume (*)',
            'E5' => 'Satuan (*)',
            'F5' => 'Harga Satuan (*)',
            'G5' => 'Jumlah (Otomatis)',
            'H5' => 'Tanggal Pelaksanaan (*)',
            'I5' => 'Beban Anggaran / Akun POK',
            'J5' => 'Jenis Mitra (*)',
        ];
        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }
        $sheet->getStyle('A5:J5')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 9],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E40AF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFBFDBFE']]],
        ]);
        $sheet->getRowDimension(5)->setRowHeight(36);

        // ── Baris 6: Contoh data
        $contohMitra = Mitra::first();
        $contohId = $contohMitra ? $contohMitra->sobat_id : '5310-M001';
        $contohRows = [
            ['1', $contohId, 'Melakukan pencacahan lapangan Survei Susenas', '10', 'Dokumen', '55000', '=D6*F6', '1-20 ' . now()->translatedFormat('F Y'), 'Belanja Honor Output Kegiatan', 'pendataan_lapangan'],
            ['',  '',        'Melakukan pemeriksaan lapangan Susenas',        '10', 'Dokumen', '51000', '=D7*F7', '1-20 ' . now()->translatedFormat('F Y'), 'Belanja Honor Output Kegiatan', 'pendataan_lapangan'],
        ];
        $rowNum = 6;
        foreach ($contohRows as $r) {
            $sheet->fromArray($r, null, 'A' . $rowNum);
            $sheet->getStyle('A' . $rowNum . ':J' . $rowNum)->applyFromArray([
                'font'    => ['italic' => true, 'color' => ['argb' => 'FF64748B'], 'size' => 8],
                'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF8FAFC']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE2E8F0']]],
            ]);
            $rowNum++;
        }

        // ── Lebar kolom
        $colWidths = ['A' => 5, 'B' => 28, 'C' => 42, 'D' => 10, 'E' => 14, 'F' => 16, 'G' => 18, 'H' => 26, 'I' => 30, 'J' => 22];
        foreach ($colWidths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        // ── Freeze panes
        $sheet->freezePane('A6');

        $writer   = new Xlsx($spreadsheet);
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
            // Format baru: data mulai baris 6 (baris 1-5 adalah header/judul/contoh)
            if ($rowNum < 6) continue;

            $mitraInput     = trim($row['B'] ?? '');
            $rincianKegiatan= trim($row['C'] ?? '');  // Kolom C = Kegiatan
            $targetKuantitas= trim($row['D'] ?? '');  // Kolom D = Volume
            $satuan         = trim($row['E'] ?? '');  // Kolom E = Satuan
            $hargaSatuanRaw = trim($row['F'] ?? '');  // Kolom F = Harga Satuan
            // Kolom G = Jumlah (auto, diabaikan — dihitung ulang)
            $tanggalRaw     = trim($row['H'] ?? '');  // Kolom H = Tanggal Pelaksanaan
            $akunPok        = trim($row['I'] ?? '');  // Kolom I = Akun POK
            $jenisMitraRaw  = strtolower(trim($row['J'] ?? ''));  // Kolom J = Jenis Mitra

            // Parse tanggal: bisa "1-20 Februari 2026" atau range, ambil bagian akhir sebagai tanggal selesai
            // Coba parse langsung jika YYYY-MM-DD, atau gunakan awal bulan jika berupa teks range
            $tanggalMulai   = '';
            $tanggalSelesai = '';
            if (!empty($tanggalRaw)) {
                // Coba apakah mengandung '-' sebagai range "1-20 Feb 2026"
                if (preg_match('/^(\d+)\s*[-–]\s*(\d+)\s+(.+)$/', $tanggalRaw, $m)) {
                    $bulanTahun = trim($m[3]);
                    $tanggalMulai   = date('Y-m-d', strtotime($m[1] . ' ' . $bulanTahun));
                    $tanggalSelesai = date('Y-m-d', strtotime($m[2] . ' ' . $bulanTahun));
                } elseif (strtotime($tanggalRaw) !== false) {
                    $tanggalMulai   = date('Y-m-d', strtotime($tanggalRaw));
                    $tanggalSelesai = $tanggalMulai;
                } else {
                    $tanggalMulai   = date('Y-m-01');
                    $tanggalSelesai = date('Y-m-t');
                }
            }

            $namaKegiatan   = 'Alokasi Mitra ' . now()->format('M Y'); // default
            $posisi         = 'Petugas Pencacah Lapangan (PCL)';       // default, bisa diperluas

            // Lewati baris kosong
            if (empty($mitraInput) && empty($rincianKegiatan)) {
                continue;
            }
            // Lewati baris contoh
            if (strtolower($rincianKegiatan) === 'melakukan pencacahan lapangan survei susenas') {
                continue;
            }

            $rowErrors = [];

            if (empty($mitraInput))      $rowErrors[] = 'Kolom Mitra / Sobat-ID kosong';
            if (empty($rincianKegiatan)) $rowErrors[] = 'Kegiatan kosong';
            if (empty($satuan))          $rowErrors[] = 'Satuan kosong';
            if (!is_numeric($targetKuantitas) || (float)$targetKuantitas <= 0) $rowErrors[] = 'Volume tidak valid';

            $hargaSatuanClean = (int) preg_replace('/[^\d]/', '', $hargaSatuanRaw);
            if ($hargaSatuanClean < 0) $rowErrors[] = 'Harga Satuan tidak valid';
            $honorClean = (int) round((float)$targetKuantitas * $hargaSatuanClean);

            if (empty($tanggalMulai))    $rowErrors[] = 'Tanggal Pelaksanaan kosong atau tidak dikenali';
            if (empty($tanggalSelesai))  $tanggalSelesai = $tanggalMulai; // fallback

            // Jenis mitra dari kolom J
            $jenisMitraRow = (str_contains($jenisMitraRaw, 'pengolahan'))
                ? PengaturanHonorMitra::JENIS_PENGOLAHAN
                : PengaturanHonorMitra::JENIS_PENDATAAN;

            // Posisi default berdasarkan jenis mitra
            $posisi = $jenisMitraRow === PengaturanHonorMitra::JENIS_PENGOLAHAN
                ? 'Petugas Pengolahan Data'
                : 'Petugas Pencacah Lapangan (PCL)';

            // Temukan mitra
            $mitra = $mitraBySobatId[strtolower(trim($mitraInput))] ?? $mitraByName[strtolower(trim($mitraInput))] ?? null;
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
                $honor = $honorClean;
                $keyTracking = "{$mitra->id}_{$tahun}_{$bulan}_{$jenisMitraRow}";

                $maksimalHonor = PengaturanHonorMitra::getMaksimalHonor($jenisMitraRow, $tahun, $bulan);
                $sisaKuotaDb   = $mitra->sisaKuotaHonor($tahun, $bulan, null, $jenisMitraRow);
                $sudahDipakaiBatch = $batchHonorUsage[$keyTracking] ?? 0;
                $sisaKuotaNyata = $sisaKuotaDb - $sudahDipakaiBatch;

                if ($honor > $sisaKuotaNyata) {
                    $labelJenis = $jenisMitraRow === PengaturanHonorMitra::JENIS_PENGOLAHAN ? 'Pengolahan' : 'Pendataan Lapangan';
                    $sisaFmt  = 'Rp ' . number_format(max(0, $sisaKuotaNyata), 0, ',', '.');
                    $honorFmt = 'Rp ' . number_format($honor, 0, ',', '.');
                    $errors[] = "Baris {$rowNum}: Honor {$honorFmt} melebihi batas maksimal [{$labelJenis}] untuk '{$mitra->nama}' (sisa kuota: {$sisaFmt})";
                    continue;
                }

                AlokasiMitra::create([
                    'mitra_id'         => $mitra->id,
                    'tim_id'           => $user->tim_id,
                    'created_by'       => $user->id,
                    'nama_kegiatan'    => 'Alokasi Mitra ' . now()->format('M Y'),
                    'kegiatan'         => $rincianKegiatan,
                    'posisi'           => $posisi,
                    'jenis_mitra'      => $jenisMitraRow,
                    'satuan'           => $satuan,
                    'harga_satuan'     => $hargaSatuanClean,
                    'target_kuantitas' => (float) $targetKuantitas,
                    'honor'            => $honor,
                    'tanggal_mulai'    => $mulai->toDateString(),
                    'tanggal_selesai'  => Carbon::parse($tanggalSelesai)->toDateString(),
                    'tahun'            => $tahun,
                    'bulan'            => $bulan,
                    'status'           => 'terjadwal',
                    'provinsi'         => $mitra->provinsi,
                    'kabupaten'        => $mitra->kabupaten,
                    'akun_pok'         => $akunPok ?: null,
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
