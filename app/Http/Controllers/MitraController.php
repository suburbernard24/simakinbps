<?php

namespace App\Http\Controllers;

use App\Models\AlokasiMitra;
use App\Models\Mitra;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MitraController extends Controller
{
    // =========================================================================
    // A. NON-ADMIN: KATALOG & DETAIL INFORMASI MITRA (Sobat BPS Style)
    // =========================================================================

    /**
     * Tampilan daftar mitra statistik untuk seluruh non-admin (Ketua Tim, Anggota Tim, Kepala BPS).
     */
    public function index(Request $request)
    {
        $query = Mitra::withCount('alokasiMitras')
            ->with(['penilaians']);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('sobat_id', 'like', "%{$s}%")
                  ->orWhere('nik', 'like', "%{$s}%")
                  ->orWhere('posisi_default', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $mitras = $query->orderBy('nama')->paginate(12)->withQueryString();

        return view('mitra.index', compact('mitras'));
    }

    /**
     * Tampilan Detail Informasi Mitra (Sobat BPS Style)
     * Kartu profil di kiri, Tab/Tabel Histori Survei/Kegiatan di kanan dengan kolom Penilaian/Komentar.
     */
    public function show(Request $request, $id)
    {
        $mitra = Mitra::with(['alokasiMitras' => function ($q) {
            $q->with(['tim', 'pembuat', 'penilaians.penilai'])->latest('tanggal_mulai');
        }])->findOrFail($id);

        $currentYear = (int) $request->input('tahun', Carbon::now()->year);
        $currentMonth = (int) $request->input('bulan', Carbon::now()->month);

        // Filter histori kegiatan
        $queryHistori = $mitra->alokasiMitras()->with(['tim', 'pembuat', 'penilaians.penilai']);

        if ($request->filled('search_kegiatan')) {
            $sk = trim($request->search_kegiatan);
            $queryHistori->where(function ($q) use ($sk) {
                $q->where('nama_kegiatan', 'like', "%{$sk}%")
                  ->orWhere('kegiatan', 'like', "%{$sk}%")
                  ->orWhere('posisi', 'like', "%{$sk}%");
            });
        }

        if ($request->filled('status_kegiatan')) {
            $queryHistori->where('status', $request->status_kegiatan);
        }

        $historiKegiatan = $queryHistori->latest('tanggal_mulai')->get();

        $totalHonorBulanIni = $mitra->totalHonorBulan($currentYear, $currentMonth);
        $sisaKuotaBulanIni = $mitra->sisaKuotaHonor($currentYear, $currentMonth);

        return view('mitra.show', compact(
            'mitra',
            'historiKegiatan',
            'currentYear',
            'currentMonth',
            'totalHonorBulanIni',
            'sisaKuotaBulanIni'
        ));
    }

    // =========================================================================
    // B. ADMINISTRATOR: MANAJEMEN MASTER MITRA (CRUD & IMPORT EXCEL)
    // =========================================================================

    public function adminIndex(Request $request)
    {
        $query = Mitra::withCount('alokasiMitras');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('sobat_id', 'like', "%{$s}%")
                  ->orWhere('nik', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('telepon', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === '1');
        }

        $mitras = $query->orderBy('nama')->paginate(15)->withQueryString();

        return view('admin.mitra.index', compact('mitras'));
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'sobat_id'        => 'required|string|max:50|unique:mitras,sobat_id',
            'nama'            => 'required|string|max:255',
            'nik'             => 'nullable|string|max:30',
            'jenis_kelamin'   => 'nullable|string|in:Laki-laki,Perempuan',
            'email'           => 'nullable|email|max:255',
            'telepon'         => 'nullable|string|max:30',
            'provinsi'        => 'required|string|max:100',
            'kabupaten'       => 'required|string|max:100',
            'posisi_default'  => 'nullable|string|max:100',
            'alamat'          => 'nullable|string',
            'is_active'       => 'nullable|boolean',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'sobat_id.required' => 'Sobat-ID Mitra wajib diisi.',
            'sobat_id.unique'   => 'Sobat-ID sudah terdaftar pada mitra lain.',
            'nama.required'     => 'Nama lengkap mitra wajib diisi.',
            'foto.image'        => 'File foto harus berupa gambar.',
            'foto.max'          => 'Ukuran foto maksimal 2 MB.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('mitra-foto', 'public');
        }

        Mitra::create([
            'sobat_id'        => trim($validated['sobat_id']),
            'nama'            => trim($validated['nama']),
            'nik'             => $validated['nik'] ? trim($validated['nik']) : null,
            'jenis_kelamin'   => $validated['jenis_kelamin'] ?? null,
            'email'           => $validated['email'] ?? null,
            'telepon'         => $validated['telepon'] ?? null,
            'provinsi'        => $validated['provinsi'],
            'kabupaten'       => $validated['kabupaten'],
            'posisi_default'  => $validated['posisi_default'] ?? 'Petugas Pencacah Lapangan (PCL)',
            'alamat'          => $validated['alamat'] ?? null,
            'is_active'       => $request->boolean('is_active', true),
            'foto'            => $fotoPath,
        ]);

        return redirect()->route('admin.mitra.index')->with('success', "Data mitra '{$validated['nama']}' berhasil ditambahkan.");
    }

    public function adminUpdate(Request $request, $id)
    {
        $mitra = Mitra::findOrFail($id);

        $validated = $request->validate([
            'sobat_id'        => 'required|string|max:50|unique:mitras,sobat_id,' . $mitra->id,
            'nama'            => 'required|string|max:255',
            'nik'             => 'nullable|string|max:30',
            'jenis_kelamin'   => 'nullable|string|in:Laki-laki,Perempuan',
            'email'           => 'nullable|email|max:255',
            'telepon'         => 'nullable|string|max:30',
            'provinsi'        => 'required|string|max:100',
            'kabupaten'       => 'required|string|max:100',
            'posisi_default'  => 'nullable|string|max:100',
            'alamat'          => 'nullable|string',
            'is_active'       => 'nullable|boolean',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'sobat_id.required' => 'Sobat-ID Mitra wajib diisi.',
            'sobat_id.unique'   => 'Sobat-ID sudah terdaftar pada mitra lain.',
            'nama.required'     => 'Nama lengkap mitra wajib diisi.',
        ]);

        if ($request->hasFile('foto')) {
            if ($mitra->foto && Storage::disk('public')->exists($mitra->foto)) {
                Storage::disk('public')->delete($mitra->foto);
            }
            $validated['foto'] = $request->file('foto')->store('mitra-foto', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $mitra->update($validated);

        return redirect()->route('admin.mitra.index')->with('success', "Data mitra '{$mitra->nama}' berhasil diperbarui.");
    }

    public function adminToggleStatus($id)
    {
        $mitra = Mitra::findOrFail($id);
        $mitra->is_active = !$mitra->is_active;
        $mitra->save();

        $statusTeks = $mitra->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Status mitra '{$mitra->nama}' berhasil {$statusTeks}.");
    }

    public function adminDestroy($id)
    {
        $mitra = Mitra::findOrFail($id);
        $nama = $mitra->nama;

        DB::transaction(function () use ($mitra) {
            if ($mitra->foto && Storage::disk('public')->exists($mitra->foto)) {
                Storage::disk('public')->delete($mitra->foto);
            }
            $mitra->delete();
        });

        return redirect()->route('admin.mitra.index')->with('success', "Data mitra '{$nama}' berhasil dihapus.");
    }

    /**
     * Download template import mitra statistik untuk admin.
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Mitra');

        // Header 1
        $sheet->setCellValue('A1', 'TEMPLATE IMPORT DATA MITRA STATISTIK - BPS KABUPATEN MANGGARAI');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF003087']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(36);

        // Header 2 (Petunjuk)
        $sheet->setCellValue('A2', 'Petunjuk: Isi data mitra mulai baris ke-4. Sobat-ID wajib unik. Kolom (*) wajib diisi. Baris 4 adalah contoh.');
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 9, 'color' => ['argb' => 'FF475569']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEEF3FB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'indent' => 1],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(25);

        // Header kolom baris 3
        $headers = [
            'A3' => 'No',
            'B3' => 'Sobat-ID (*)',
            'C3' => 'Nama Lengkap Mitra (*)',
            'D3' => 'NIK (16 Digit)',
            'E3' => 'Jenis Kelamin (Laki-laki / Perempuan)',
            'F3' => 'Nomor Telepon/WA',
            'G3' => 'Email',
            'H3' => 'Posisi Default (PCL / PML / Pengolahan)',
            'I3' => 'Alamat Domisili',
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        $sheet->getStyle('A3:I3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1565C0']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCBD5E1']]],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(32);

        // Baris 4: Contoh
        $contoh = [
            'A4' => '1',
            'B4' => '5310-M001',
            'C4' => 'Yohanes Bosco',
            'D4' => '5310123456780001',
            'E4' => 'Laki-laki',
            'F4' => '081234567890',
            'G4' => 'yohanes@example.com',
            'H4' => 'Petugas Pencacah Lapangan (PCL)',
            'I4' => 'Kel. Watu, Kec. Langke Rembong, Kab. Manggarai',
        ];
        foreach ($contoh as $cell => $val) {
            $sheet->setCellValueExplicit($cell, $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        }
        $sheet->getStyle('A4:I4')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['argb' => 'FF64748B']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF8FAFC']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE2E8F0']]],
        ]);

        $colWidths = ['A' => 6, 'B' => 18, 'C' => 28, 'D' => 22, 'E' => 22, 'F' => 20, 'G' => 25, 'H' => 30, 'I' => 35];
        foreach ($colWidths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'template_import_mitra_bps_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Import data master mitra dari file Excel.
     */
    public function importExcel(Request $request)
    {
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
            return redirect()->route('admin.mitra.index')->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        $imported = 0;
        $errors = [];

        foreach ($rows as $rowNum => $row) {
            if ($rowNum < 4) continue;

            $sobatId   = trim($row['B'] ?? '');
            $nama      = trim($row['C'] ?? '');
            $nik       = trim($row['D'] ?? '');
            $jk        = trim($row['E'] ?? '');
            $telepon   = trim($row['F'] ?? '');
            $email     = trim($row['G'] ?? '');
            $posisi    = trim($row['H'] ?? '');
            $alamat    = trim($row['I'] ?? '');

            if (empty($sobatId) || strtolower($sobatId) === '5310-m001' && strtolower($nama) === 'yohanes bosco') {
                continue;
            }

            if (empty($nama)) {
                $errors[] = "Baris {$rowNum}: Nama mitra kosong.";
                continue;
            }

            // Cek apakah sobat_id sudah ada
            $existing = Mitra::where('sobat_id', $sobatId)->first();
            if ($existing) {
                // Update data jika sudah ada
                $existing->update([
                    'nama'           => $nama,
                    'nik'            => $nik ?: $existing->nik,
                    'jenis_kelamin'  => $jk ?: $existing->jenis_kelamin,
                    'telepon'        => $telepon ?: $existing->telepon,
                    'email'          => $email ?: $existing->email,
                    'posisi_default' => $posisi ?: $existing->posisi_default,
                    'alamat'         => $alamat ?: $existing->alamat,
                ]);
                $imported++;
                continue;
            }

            try {
                Mitra::create([
                    'sobat_id'        => $sobatId,
                    'nama'            => $nama,
                    'nik'             => $nik ?: null,
                    'jenis_kelamin'   => $jk ?: null,
                    'telepon'         => $telepon ?: null,
                    'email'           => $email ?: null,
                    'provinsi'        => 'Nusa Tenggara Timur',
                    'kabupaten'       => 'Kabupaten Manggarai',
                    'posisi_default'  => $posisi ?: 'Petugas Pencacah Lapangan (PCL)',
                    'alamat'          => $alamat ?: null,
                    'is_active'       => true,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$rowNum}: Gagal menyimpan ({$e->getMessage()})";
            }
        }

        $msg = "Berhasil mengimpor/memperbarui {$imported} data mitra.";
        if (!empty($errors)) {
            $msg .= ' Beberapa baris bermasalah: ' . implode(' | ', array_slice($errors, 0, 5));
        }

        return redirect()->route('admin.mitra.index')->with($imported > 0 ? 'success' : 'error', $msg);
    }
}
