<?php

namespace App\Http\Controllers;

use App\Models\AlokasiPekerjaan;
use App\Models\FrontendGallery;
use App\Models\FrontendInfografis;
use App\Models\FrontendQuote;
use App\Models\FrontendKegiatan;
use App\Models\FrontendIndikator;
use App\Models\FrontendSetting;
use App\Models\HariKerjaAktif;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AdminController extends Controller
{

    /**
     * Dashboard Administrator
     */
    public function dashboard()
    {
        $totalPegawai = User::where('is_active', true)->where('role', '!=', 'admin')->count();
        $totalTim = Team::where('is_active', true)->count();

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $hariKerjaBulanIni = HariKerjaAktif::where('tahun', $currentYear)->where('bulan', $currentMonth)->first();

        $recentUsers = User::with('tim')->latest()->take(5)->get();
        $teams = Team::with('ketua')->withCount('pegawais')->get();

        return view('admin.dashboard', compact(
            'totalPegawai',
            'totalTim',
            'hariKerjaBulanIni',
            'recentUsers',
            'teams',
            'currentYear',
            'currentMonth'
        ));
    }

    // =========================================================================
    // 1. MANAGE PEGAWAI
    // =========================================================================

    public function pegawaiIndex(Request $request)
    {
        $query = User::with('tim');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('tim_id')) {
            $query->where('tim_id', $request->tim_id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('nip', 'like', "%{$s}%")
                  ->orWhere('username', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('no_hp', 'like', "%{$s}%")
                  ->orWhere('no_whatsapp', 'like', "%{$s}%")
                  ->orWhere('jabatan', 'like', "%{$s}%");
            });
        }

        $pegawais = $query->orderBy('name')->paginate(15);
        $teams = Team::where('is_active', true)->orderBy('nama_tim')->get();

        return view('admin.pegawai.index', compact('pegawais', 'teams'));
    }

    public function pegawaiCreate()
    {
        $teams = Team::where('is_active', true)->orderBy('nama_tim')->get();
        return view('admin.pegawai.create', compact('teams'));
    }

    public function pegawaiStore(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'username'     => 'required|string|max:100|unique:users,username',
            'email'        => 'nullable|email|max:255|unique:users,email',
            'no_hp'        => 'nullable|string|max:30',
            'nip'          => 'nullable|string|max:30',
            'jabatan'      => 'nullable|string|max:255',
            'golongan'     => 'nullable|string|max:50',
            'password'     => 'required|string|min:6',
            'role'         => 'required|in:admin,kepala_bps,ketua_tim,pegawai',
            'tim_id'       => 'nullable|exists:teams,id',
        ]);

        $isKetuaTim = $request->role === 'ketua_tim';

        $user = User::create([
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'no_hp'        => $request->no_hp,
            'no_whatsapp'  => $request->no_hp,
            'nip'          => $request->nip,
            'jabatan'      => $request->jabatan,
            'golongan'     => $request->golongan,
            'password'     => Hash::make($request->password),
            'role'         => $request->role,
            'tim_id'       => $request->tim_id,
            'is_ketua_tim' => $isKetuaTim,
            'is_active'    => true, // Pegawai baru selalu aktif; ubah via tombol toggle di Kelola Pegawai
        ]);

        // Jika user adalah Ketua Tim untuk suatu tim, update tim tersebut
        if ($isKetuaTim && $request->tim_id) {
            Team::where('id', $request->tim_id)->update(['ketua_id' => $user->id]);
        }

        return redirect()->route('admin.pegawai.index')->with('success', "Pegawai '{$user->name}' berhasil ditambahkan.");
    }

    /**
     * Unduh Template Excel untuk Input Banyak Pegawai Sekaligus
     */
    public function pegawaiDownloadTemplate()
    {
        $spreadsheet = new Spreadsheet();

        // 1. Sheet Referensi untuk Dropdown Data Validation
        $refSheet = $spreadsheet->createSheet();
        $refSheet->setTitle('Data_Referensi');

        $teams = Team::where('is_active', true)->orderBy('nama_tim')->get();
        $refSheet->setCellValue('A1', '-- Tanpa Tim --');
        $rowTim = 2;
        foreach ($teams as $team) {
            $refSheet->setCellValue('A' . $rowTim, $team->nama_tim);
            $rowTim++;
        }
        $totalTimRows = $rowTim - 1;

        // Role Akses
        $roles = ['Anggota Tim', 'Ketua Tim', 'Kepala BPS', 'Administrator Sistem'];
        foreach ($roles as $idx => $roleName) {
            $refSheet->setCellValue('B' . ($idx + 1), $roleName);
        }
        $totalRoleRows = count($roles);

        // Status / Boolean Options
        $refSheet->setCellValue('C1', 'Ya');
        $refSheet->setCellValue('C2', 'Tidak');
        $totalBoolRows = 2;

        // Sembunyikan sheet referensi agar tampilan template tetap bersih
        $refSheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);

        // 2. Sheet Utama (Template Pegawai)
        $sheet = $spreadsheet->setActiveSheetIndex(0);
        $sheet->setTitle('Template Pegawai');

        // Baris 1: Judul Banner
        $sheet->setCellValue('A1', 'TEMPLATE IMPORT PEGAWAI - BPS KABUPATEN MANGGARAI');
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF003087']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(36);

        // Baris 2: Petunjuk
        $sheet->setCellValue('A2', 'Petunjuk: Isi data pegawai mulai baris ke-4. Kolom (*) wajib diisi. Kolom bertanda dropdown (Tim, Role, Status) silakan pilih dari opsi dropdown. Baris 4 adalah baris contoh dan dapat diganti.');
        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 9, 'color' => ['argb' => 'FF475569']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEEF3FB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'indent' => 1],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(25);

        // Baris 3: Header Kolom
        $headers = [
            'A3' => 'No',
            'B3' => 'Nama dan Gelar (*)',
            'C3' => 'Username Login (*)',
            'D3' => 'Password (*)',
            'E3' => 'Tim Kerja BPS',
            'F3' => 'NIP',
            'G3' => 'Email Resmi',
            'H3' => 'No. Handphone / WhatsApp',
            'I3' => 'Jabatan',
            'J3' => 'Golongan/Pangkat',
            'K3' => 'Role Akses (*)',
            'L3' => 'Status Akun Aktif (*)',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        $sheet->getStyle('A3:L3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1565C0']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCBD5E1']]],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(36);

        // Baris 4: Data Contoh
        $contohTim = $teams->isNotEmpty() ? $teams->first()->nama_tim : '-- Tanpa Tim --';
        $contohData = [
            'A4' => '1',
            'B4' => 'Budi Santoso, S.Tr.Stat.',
            'C4' => 'budisantoso',
            'D4' => 'bps123456',
            'E4' => $contohTim,
            'F4' => '199505102018011004',
            'G4' => 'budi.santoso@bps.go.id',
            'H4' => '081234567890',
            'I4' => 'Statistisi Ahli Pertama',
            'J4' => 'III/a',
            'K4' => 'Anggota Tim',
            'L4' => 'Ya',
        ];

        foreach ($contohData as $cell => $value) {
            $sheet->setCellValueExplicit($cell, $value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        }

        $sheet->getStyle('A4:L4')->applyFromArray([
            'font' => ['italic' => true, 'size' => 10, 'color' => ['argb' => 'FF64748B']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF8FAFC']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE2E8F0']]],
        ]);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set Lebar Kolom
        $colWidths = [
            'A' => 6,
            'B' => 30,
            'C' => 22,
            'D' => 18,
            'E' => 28,
            'F' => 24,
            'G' => 28,
            'H' => 26,
            'I' => 26,
            'J' => 18,
            'K' => 24,
            'L' => 20,
        ];
        foreach ($colWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Terapkan Data Validation (Dropdown) Baris 4 s.d. 150
        for ($r = 4; $r <= 150; $r++) {
            // Dropdown Tim Kerja (Kolom E)
            $valTim = $sheet->getCell('E' . $r)->getDataValidation();
            $valTim->setType(DataValidation::TYPE_LIST);
            $valTim->setErrorStyle(DataValidation::STYLE_STOP);
            $valTim->setAllowBlank(true);
            $valTim->setShowDropDown(true);
            $valTim->setShowErrorMessage(true);
            $valTim->setErrorTitle('Tim Tidak Valid');
            $valTim->setError('Silakan pilih tim dari daftar dropdown yang tersedia.');
            $valTim->setFormula1("Data_Referensi!\$A\$1:\$A\${$totalTimRows}");

            // Dropdown Role Akses (Kolom K)
            $valRole = $sheet->getCell('K' . $r)->getDataValidation();
            $valRole->setType(DataValidation::TYPE_LIST);
            $valRole->setErrorStyle(DataValidation::STYLE_STOP);
            $valRole->setAllowBlank(false);
            $valRole->setShowDropDown(true);
            $valRole->setShowErrorMessage(true);
            $valRole->setErrorTitle('Role Tidak Valid');
            $valRole->setError('Silakan pilih role dari daftar dropdown.');
            $valRole->setFormula1('"Anggota Tim, Ketua Tim, Kepala BPS, Administrator Sistem"');

            // Dropdown Status Akun Aktif (Kolom L)
            $valActive = $sheet->getCell('L' . $r)->getDataValidation();
            $valActive->setType(DataValidation::TYPE_LIST);
            $valActive->setErrorStyle(DataValidation::STYLE_STOP);
            $valActive->setAllowBlank(true);
            $valActive->setShowDropDown(true);
            $valActive->setShowErrorMessage(true);
            $valActive->setErrorTitle('Pilihan Tidak Valid');
            $valActive->setError('Pilih Ya atau Tidak.');
            $valActive->setFormula1('"Ya, Tidak"');
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'template_import_pegawai_bps_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Proses Import Banyak Pegawai Sekaligus dari File Excel
     */
    public function pegawaiImportExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls|max:5120',
        ], [
            'file_excel.required' => 'File Excel wajib diunggah.',
            'file_excel.mimes'    => 'File harus berformat Excel (.xlsx atau .xls).',
            'file_excel.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $file = $request->file('file_excel');

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
        } catch (\Exception $e) {
            return redirect()->route('admin.pegawai.index')
                ->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        // Peta Tim Kerja Aktif
        $teams = Team::where('is_active', true)->get();
        $teamMap = [];
        foreach ($teams as $t) {
            $teamMap[strtolower(trim($t->nama_tim))] = $t->id;
            if (!empty($t->singkatan)) {
                $teamMap[strtolower(trim($t->singkatan))] = $t->id;
            }
        }

        // Cek data existing di database untuk validasi unique
        $existingUsernames = User::pluck('username')->map(fn($u) => strtolower(trim($u)))->flip()->toArray();
        $existingEmails    = User::whereNotNull('email')->where('email', '!=', '')->pluck('email')->map(fn($e) => strtolower(trim($e)))->flip()->toArray();
        $existingNips      = User::whereNotNull('nip')->where('nip', '!=', '')->pluck('nip')->map(fn($n) => trim($n))->flip()->toArray();

        // Lacak duplikasi di dalam file yang sama
        $fileUsernames = [];
        $fileEmails    = [];
        $fileNips      = [];

        $roleMap = [
            'anggota tim'          => 'pegawai',
            'anggota'              => 'pegawai',
            'pegawai'              => 'pegawai',
            'ketua tim'            => 'ketua_tim',
            'kepala bps'           => 'kepala_bps',
            'kepala'               => 'kepala_bps',
            'administrator sistem' => 'admin',
            'administrator'        => 'admin',
            'admin'                => 'admin',
        ];

        // Deteksi posisi kolom berdasarkan header baris ke-3 (fallback ke B..L)
        $colMap = [
            'nama'     => 'B',
            'username' => 'C',
            'password' => 'D',
            'tim'      => 'E',
            'nip'      => 'F',
            'email'    => 'G',
            'no_hp'    => 'H',
            'jabatan'  => 'I',
            'golongan' => 'J',
            'role'     => 'K',
            'active'   => 'L',
            'ketua'    => null,
        ];

        if (isset($rows[3])) {
            foreach ($rows[3] as $colKey => $cellVal) {
                $headerLower = strtolower(trim((string)$cellVal));
                if (str_contains($headerLower, 'ketua')) {
                    $colMap['ketua'] = $colKey;
                } elseif (str_contains($headerLower, 'role') || str_contains($headerLower, 'akses')) {
                    $colMap['role'] = $colKey;
                } elseif (str_contains($headerLower, 'tim')) {
                    $colMap['tim'] = $colKey;
                } elseif (str_contains($headerLower, 'nama')) {
                    $colMap['nama'] = $colKey;
                } elseif (str_contains($headerLower, 'user')) {
                    $colMap['username'] = $colKey;
                } elseif (str_contains($headerLower, 'pass') || str_contains($headerLower, 'sandi')) {
                    $colMap['password'] = $colKey;
                } elseif (str_contains($headerLower, 'nip')) {
                    $colMap['nip'] = $colKey;
                } elseif (str_contains($headerLower, 'email')) {
                    $colMap['email'] = $colKey;
                } elseif (str_contains($headerLower, 'hp') || str_contains($headerLower, 'wa') || str_contains($headerLower, 'telepon') || str_contains($headerLower, 'handphone')) {
                    $colMap['no_hp'] = $colKey;
                } elseif (str_contains($headerLower, 'jabat')) {
                    $colMap['jabatan'] = $colKey;
                } elseif (str_contains($headerLower, 'golong') || str_contains($headerLower, 'pangkat')) {
                    $colMap['golongan'] = $colKey;
                } elseif (str_contains($headerLower, 'aktif') || str_contains($headerLower, 'status')) {
                    $colMap['active'] = $colKey;
                }
            }
        }

        $toInsert = [];
        $errors   = [];

        // Data mulai dari baris ke-4
        foreach ($rows as $rowNum => $row) {
            if ($rowNum < 4) {
                continue;
            }

            $nama      = trim((string)($row[$colMap['nama']] ?? ''));
            $username  = trim((string)($row[$colMap['username']] ?? ''));
            $password  = trim((string)($row[$colMap['password']] ?? ''));
            $timRaw    = trim((string)($row[$colMap['tim']] ?? ''));
            $nip       = trim((string)($row[$colMap['nip']] ?? ''));
            $email     = trim((string)($row[$colMap['email']] ?? ''));
            $noHp      = !empty($colMap['no_hp']) ? trim((string)($row[$colMap['no_hp']] ?? '')) : null;
            $jabatan   = trim((string)($row[$colMap['jabatan']] ?? ''));
            $golongan  = trim((string)($row[$colMap['golongan']] ?? ''));
            $roleRaw   = trim((string)($row[$colMap['role']] ?? ''));
            $activeRaw = trim((string)($row[$colMap['active']] ?? ''));
            $ketuaRaw  = trim((string)($row[$colMap['ketua']] ?? ''));

            // Lewati baris kosong
            if (empty($nama) && empty($username) && empty($password) && empty($nip) && empty($email) && empty($noHp)) {
                continue;
            }

            // Lewati jika merupakan baris contoh bawaan template
            if (strtolower($username) === 'budisantoso' && strtolower($nama) === 'budi santoso, s.tr.stat.') {
                continue;
            }

            $hasRowError = false;

            // 1. Validasi Nama
            if (empty($nama)) {
                $errors[] = "Baris {$rowNum}: Kolom Nama Lengkap & Gelar wajib diisi.";
                $hasRowError = true;
            }

            // 2. Validasi Username
            // Auto-sanitize: ubah spasi → titik, hapus karakter tidak valid, lowercase
            if (!empty($username)) {
                $username = preg_replace('/\s+/', '.', $username);          // spasi → titik
                $username = preg_replace('/[^a-zA-Z0-9._-]/', '', $username); // buang karakter selain huruf/angka/titik/strip/underscore
                $username = trim($username, '.-_');                          // buang titik/strip/underscore di awal & akhir
                $username = strtolower($username);                           // lowercase
            }
            $cleanUsername = $username;
            if (empty($username)) {
                $errors[] = "Baris {$rowNum}: Kolom Username Login wajib diisi atau nama tidak dapat dikonversi menjadi username valid.";
                $hasRowError = true;
            } elseif (isset($existingUsernames[$cleanUsername])) {
                $errors[] = "Baris {$rowNum}: Username '{$username}' sudah terdaftar di sistem.";
                $hasRowError = true;
            } elseif (isset($fileUsernames[$cleanUsername])) {
                $errors[] = "Baris {$rowNum}: Username '{$username}' duplikat dengan baris {$fileUsernames[$cleanUsername]} di file Excel ini.";
                $hasRowError = true;
            } else {
                $fileUsernames[$cleanUsername] = $rowNum;
            }

            // 3. Validasi Password
            if (empty($password)) {
                $errors[] = "Baris {$rowNum}: Password awal wajib diisi.";
                $hasRowError = true;
            } elseif (strlen($password) < 6) {
                $errors[] = "Baris {$rowNum}: Password untuk '{$nama}' minimal 6 karakter.";
                $hasRowError = true;
            }

            // 4. Validasi Email (opsional namun jika diisi harus valid & unique)
            $cleanEmail = !empty($email) ? strtolower($email) : null;
            if (!empty($cleanEmail)) {
                if (!filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Baris {$rowNum}: Format email '{$email}' tidak valid.";
                    $hasRowError = true;
                } elseif (isset($existingEmails[$cleanEmail])) {
                    $errors[] = "Baris {$rowNum}: Email '{$email}' sudah terdaftar di sistem.";
                    $hasRowError = true;
                } elseif (isset($fileEmails[$cleanEmail])) {
                    $errors[] = "Baris {$rowNum}: Email '{$email}' duplikat dengan baris {$fileEmails[$cleanEmail]} di file Excel ini.";
                    $hasRowError = true;
                } else {
                    $fileEmails[$cleanEmail] = $rowNum;
                }
            }

            // 5. Validasi NIP (opsional, jika diisi dicek duplikasi)
            if (!empty($nip)) {
                if (isset($existingNips[$nip])) {
                    $errors[] = "Baris {$rowNum}: NIP '{$nip}' sudah terdaftar di sistem.";
                    $hasRowError = true;
                } elseif (isset($fileNips[$nip])) {
                    $errors[] = "Baris {$rowNum}: NIP '{$nip}' duplikat dengan baris {$fileNips[$nip]} di file Excel ini.";
                    $hasRowError = true;
                } else {
                    $fileNips[$nip] = $rowNum;
                }
            }

            // 6. Validasi Role Akses
            $normRole = strtolower($roleRaw);
            if (empty($roleRaw)) {
                $role = 'pegawai';
            } elseif (isset($roleMap[$normRole])) {
                $role = $roleMap[$normRole];
            } else {
                $errors[] = "Baris {$rowNum}: Role akses '{$roleRaw}' tidak dikenali. Pilih: Anggota Tim, Ketua Tim, Kepala BPS, atau Administrator Sistem.";
                $hasRowError = true;
                $role = 'pegawai';
            }

            // 7. Validasi Tim Kerja
            $timId = null;
            $normTim = strtolower($timRaw);
            if (!empty($timRaw) && !in_array($normTim, ['--', '---', '-- tanpa tim --', 'tanpa tim', '-'])) {
                if (isset($teamMap[$normTim])) {
                    $timId = $teamMap[$normTim];
                } else {
                    $errors[] = "Baris {$rowNum}: Tim Kerja '{$timRaw}' tidak ditemukan dalam daftar tim aktif BPS.";
                    $hasRowError = true;
                }
            }

            // 8. Validasi Status Akun Aktif
            $normActive = strtolower($activeRaw);
            $isActive = true;
            if (!empty($activeRaw)) {
                if (in_array($normActive, ['ya', 'aktif', '1', 'true', 'yes'])) {
                    $isActive = true;
                } elseif (in_array($normActive, ['tidak', 'nonaktif', '0', 'false', 'no'])) {
                    $isActive = false;
                } else {
                    $errors[] = "Baris {$rowNum}: Status Akun Aktif '{$activeRaw}' tidak valid (Gunakan pilihan 'Ya' atau 'Tidak').";
                    $hasRowError = true;
                }
            }

            // 9. Validasi Penugasan Ketua Tim
            $normKetua = strtolower($ketuaRaw);
            $isKetuaTim = ($role === 'ketua_tim');
            if (!$isKetuaTim && !empty($ketuaRaw)) {
                if (in_array($normKetua, ['ya', '1', 'true', 'yes'])) {
                    $isKetuaTim = true;
                } elseif (in_array($normKetua, ['tidak', '0', 'false', 'no'])) {
                    $isKetuaTim = false;
                } else {
                    $errors[] = "Baris {$rowNum}: Pilihan Tugaskan sebagai Ketua Tim '{$ketuaRaw}' tidak valid (Gunakan 'Ya' atau 'Tidak').";
                    $hasRowError = true;
                }
            }

            if (!$hasRowError) {
                $toInsert[] = [
                    'name'         => $nama,
                    'username'     => $username,
                    'email'        => $cleanEmail,
                    'no_hp'        => !empty($noHp) ? $noHp : null,
                    'no_whatsapp'  => !empty($noHp) ? $noHp : null,
                    'nip'          => !empty($nip) ? $nip : null,
                    'jabatan'      => !empty($jabatan) ? $jabatan : null,
                    'golongan'     => !empty($golongan) ? $golongan : null,
                    'password'     => Hash::make($password),
                    'role'         => $role,
                    'tim_id'       => $timId,
                    'is_ketua_tim' => $isKetuaTim,
                    'is_active'    => $isActive,
                ];
            }
        }

        // Jika terdapat error validasi baris, batalkan import dan tampilkan rincian
        if (!empty($errors)) {
            return redirect()->route('admin.pegawai.index')
                ->with('import_errors', $errors)
                ->with('error', 'Gagal mengimpor data. Ditemukan ' . count($errors) . ' kesalahan pada file Excel. Silakan periksa daftar kesalahan berikut dan unggah kembali.');
        }

        if (empty($toInsert)) {
            return redirect()->route('admin.pegawai.index')
                ->with('error', 'Tidak ada data pegawai baru yang ditemukan di dalam file Excel.');
        }

        // Simpan data dalam database transaction
        DB::transaction(function () use ($toInsert) {
            foreach ($toInsert as $userData) {
                $user = User::create($userData);

                // Jika ditugaskan sebagai Ketua Tim dan memilih tim, update ketua_id di tabel teams
                if ($userData['is_ketua_tim'] && $userData['tim_id']) {
                    Team::where('id', $userData['tim_id'])->update(['ketua_id' => $user->id]);
                }
            }
        });

        $totalSukses = count($toInsert);
        return redirect()->route('admin.pegawai.index')
            ->with('success', "Berhasil menambahkan {$totalSukses} pegawai baru sekaligus dari file Excel.");
    }

    public function pegawaiEdit($id)
    {
        $pegawai = User::findOrFail($id);
        $teams = Team::where('is_active', true)->orderBy('nama_tim')->get();

        return view('admin.pegawai.edit', compact('pegawai', 'teams'));
    }

    public function pegawaiUpdate(Request $request, $id)
    {
        $pegawai = User::findOrFail($id);

        $request->validate([
            'name'         => 'required|string|max:255',
            'username'     => 'required|string|max:100|unique:users,username,' . $pegawai->id,
            'email'        => 'nullable|email|max:255|unique:users,email,' . $pegawai->id,
            'no_hp'        => 'nullable|string|max:30',
            'nip'          => 'nullable|string|max:30',
            'jabatan'      => 'nullable|string|max:255',
            'golongan'     => 'nullable|string|max:50',
            'password'     => 'nullable|string|min:6',
            'role'         => 'required|in:admin,kepala_bps,ketua_tim,pegawai',
            'tim_id'       => 'nullable|exists:teams,id',
        ]);

        $isKetuaTim = $request->role === 'ketua_tim';

        $data = [
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'no_hp'        => $request->no_hp,
            'no_whatsapp'  => $request->no_hp,
            'nip'          => $request->nip,
            'jabatan'      => $request->jabatan,
            'golongan'     => $request->golongan,
            'role'         => $request->role,
            'tim_id'       => $request->tim_id,
            'is_ketua_tim' => $isKetuaTim,
            // is_active tidak diubah via form edit; hanya lewat tombol toggle di Kelola Pegawai
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pegawai->update($data);

        if ($isKetuaTim && $request->tim_id) {
            Team::where('id', $request->tim_id)->update(['ketua_id' => $pegawai->id]);
        } elseif (!$isKetuaTim) {
            Team::where('ketua_id', $pegawai->id)->update(['ketua_id' => null]);
        }

        return redirect()->route('admin.pegawai.index')->with('success', "Data pegawai '{$pegawai->name}' berhasil diperbarui.");
    }

    public function pegawaiToggleStatus($id)
    {
        $pegawai = User::findOrFail($id);
        $pegawai->update(['is_active' => !$pegawai->is_active]);

        $statusText = $pegawai->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Pegawai '{$pegawai->name}' berhasil {$statusText}.");
    }

    public function pegawaiDestroy($id)
    {
        if ((int)$id === (int)auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.');
        }

        $pegawai = User::findOrFail($id);
        $namaPegawai = $pegawai->name;

        DB::transaction(function () use ($pegawai) {
            // 1. Lepaskan jabatan ketua dari tim jika pegawai ini adalah ketua tim
            Team::where('ketua_id', $pegawai->id)->update(['ketua_id' => null]);

            // 2. Kosongkan penilai_id pada alokasi pekerjaan jika pegawai ini bertindak sebagai penilai
            AlokasiPekerjaan::where('penilai_id', $pegawai->id)->update(['penilai_id' => null]);

            // 3. Jika pegawai ini pernah membuat alokasi pekerjaan untuk pegawai lain, alihkan created_by ke akun admin
            //    agar pekerjaan rekan kerja tidak ikut terhapus akibat cascade delete
            AlokasiPekerjaan::where('created_by', $pegawai->id)
                ->where('pegawai_id', '!=', $pegawai->id)
                ->update(['created_by' => auth()->id()]);

            // 4. Hapus seluruh alokasi pekerjaan milik pegawai ini
            AlokasiPekerjaan::where('pegawai_id', $pegawai->id)->delete();

            // 5. Hapus alokasi buatan sendiri yang tersisa
            AlokasiPekerjaan::where('created_by', $pegawai->id)->delete();

            // 6. Bersihkan data pada tabel ibk dan ckp jika ada
            if (DB::getSchemaBuilder()->hasTable('ibk')) {
                DB::table('ibk')->where('user_id', $pegawai->id)->delete();
            }
            if (DB::getSchemaBuilder()->hasTable('ckp')) {
                DB::table('ckp')->where('user_id', $pegawai->id)->delete();
            }

            // 7. Hapus sesi login aktif user di tabel sessions sehingga langsung logout dari sistem
            if (DB::getSchemaBuilder()->hasTable('sessions')) {
                DB::table('sessions')->where('user_id', $pegawai->id)->delete();
            }

            // 8. Hapus token reset password jika ada
            if ($pegawai->email && DB::getSchemaBuilder()->hasTable('password_reset_tokens')) {
                DB::table('password_reset_tokens')->where('email', $pegawai->email)->delete();
            }

            // 9. Hapus file foto profil jika ada
            if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
                Storage::disk('public')->delete($pegawai->foto);
            }

            // 10. Hapus data pegawai dari database
            $pegawai->delete();
        });

        return redirect()->route('admin.pegawai.index')->with('success', "Akun pegawai '{$namaPegawai}' beserta sesi login-nya berhasil dihapus secara permanen.");
    }

    // =========================================================================
    // 2. MANAGE TIM
    // =========================================================================

    public function timIndex()
    {
        $teams = Team::with('ketua')->withCount('pegawais')->get();
        $ketuaCandidates = User::where('is_active', true)->whereNotIn('role', ['admin'])->orderBy('name')->get();

        return view('admin.tim.index', compact('teams', 'ketuaCandidates'));
    }

    public function timStore(Request $request)
    {
        $request->validate([
            'nama_tim'   => 'required|string|max:255',
            'singkatan'  => 'nullable|string|max:50',
            'ketua_id'   => 'nullable|exists:users,id',
            'keterangan' => 'nullable|string',
        ]);

        $tim = Team::create($request->all());

        if ($request->ketua_id) {
            User::where('id', $request->ketua_id)->update([
                'tim_id'       => $tim->id,
                'is_ketua_tim' => true,
                'role'         => 'ketua_tim',
            ]);
        }

        return redirect()->route('admin.tim.index')->with('success', "Tim '{$tim->nama_tim}' berhasil dibuat.");
    }

    public function timUpdate(Request $request, $id)
    {
        $tim = Team::findOrFail($id);

        $request->validate([
            'nama_tim'   => 'required|string|max:255',
            'singkatan'  => 'nullable|string|max:50',
            'ketua_id'   => 'nullable|exists:users,id',
            'keterangan' => 'nullable|string',
        ]);

        $tim->update($request->all());

        if ($request->ketua_id) {
            User::where('id', $request->ketua_id)->update([
                'tim_id'       => $tim->id,
                'is_ketua_tim' => true,
                'role'         => 'ketua_tim',
            ]);
        }

        return redirect()->route('admin.tim.index')->with('success', "Data Tim '{$tim->nama_tim}' berhasil diperbarui.");
    }

    public function timDestroy($id)
    {
        $tim = Team::findOrFail($id);
        $namaTim = $tim->nama_tim;

        // Cek apakah tim masih memiliki pegawai
        $jumlahPegawai = User::where('tim_id', $tim->id)->count();
        if ($jumlahPegawai > 0) {
            return redirect()->back()->with('error', "Tim '{$namaTim}' tidak dapat dihapus karena masih memiliki {$jumlahPegawai} pegawai. Pindahkan atau hapus pegawai terlebih dahulu.");
        }

        DB::transaction(function () use ($tim) {
            // Hapus relasi alokasi pekerjaan yang terkait tim ini
            AlokasiPekerjaan::where('tim_id', $tim->id)->delete();

            // Hapus tim
            $tim->delete();
        });

        return redirect()->route('admin.tim.index')->with('success', "Tim '{$namaTim}' berhasil dihapus.");
    }

    // =========================================================================
    // 3. INPUT HARI KERJA AKTIF BULANAN
    // =========================================================================

    public function hariKerjaIndex(Request $request)
    {
        $tahun = (int) $request->input('tahun', Carbon::now()->year);

        $bulanList = $this->getDaftarBulan();

        // Ambil data yang sudah ada untuk tahun ini
        $existing = HariKerjaAktif::where('tahun', $tahun)->get()->keyBy('bulan');

        $daftarHariKerja = [];
        foreach ($bulanList as $blnNum => $blnName) {
            $record = $existing->get($blnNum);
            $daftarHariKerja[$blnNum] = [
                'bulan_angka' => $blnNum,
                'bulan_nama'  => $blnName,
                'hari_kerja'  => $record ? $record->jumlah_hari_kerja : 22,
                'jam_per_hari'=> $record ? $record->jam_kerja_per_hari : 7.50,
                'total_jam'   => $record ? $record->total_jam : (22 * 7.50),
                'keterangan'  => $record ? $record->keterangan : '',
                'updated_at'  => $record ? $record->updated_at : null,
            ];
        }

        return view('admin.hari_kerja.index', compact('tahun', 'daftarHariKerja'));
    }

    public function hariKerjaStore(Request $request)
    {
        $request->validate([
            'tahun'              => 'required|integer|min:2020|max:2050',
            'bulan'              => 'required|integer|min:1|max:12',
            'jumlah_hari_kerja'  => 'required|integer|min:1|max:31',
            'jam_kerja_per_hari' => 'required|numeric|min:1|max:24',
            'keterangan'         => 'nullable|string|max:255',
        ]);

        HariKerjaAktif::updateOrCreate(
            ['tahun' => $request->tahun, 'bulan' => $request->bulan],
            [
                'jumlah_hari_kerja'  => $request->jumlah_hari_kerja,
                'jam_kerja_per_hari' => $request->jam_kerja_per_hari,
                'keterangan'         => $request->keterangan,
            ]
        );

        $bulanNama = (new HariKerjaAktif(['bulan' => $request->bulan]))->nama_bulan;
        return redirect()->route('admin.hari_kerja.index', ['tahun' => $request->tahun])
                         ->with('success', "Hari kerja aktif bulan {$bulanNama} {$request->tahun} berhasil diperbarui ({$request->jumlah_hari_kerja} hari kerja, total " . ($request->jumlah_hari_kerja * $request->jam_kerja_per_hari) . " jam).");
    }

    // =========================================================================
    // 4. MANAGE USER PUBLIK
    // =========================================================================

    public function userPublikIndex()
    {
        $users = User::where('is_public_user', true)->orderBy('name')->get();
        return view('admin.user_publik.index', compact('users'));
    }

    public function userPublikStore(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'email'    => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'           => $request->name,
            'username'       => $request->username,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => 'pegawai',
            'is_public_user' => true,
            'is_active'      => true,
            'jabatan'        => 'Pengguna Publik',
        ]);

        return redirect()->route('admin.user_publik.index')->with('success', 'User publik berhasil ditambahkan.');
    }

    public function userPublikDestroy($id)
    {
        $user = User::where('is_public_user', true)->findOrFail($id);

        if ((int)$user->id === (int)auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $namaUser = $user->name;

        DB::transaction(function () use ($user) {
            if (DB::getSchemaBuilder()->hasTable('sessions')) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }
            if ($user->email && DB::getSchemaBuilder()->hasTable('password_reset_tokens')) {
                DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            }
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $user->delete();
        });

        return redirect()->route('admin.user_publik.index')->with('success', "User publik '{$namaUser}' berhasil dihapus.");
    }

    // =========================================================================
    // 5. MANAGE TAMPILAN FRONTEND (Seluruh Landing Page)
    // =========================================================================

    /**
     * Definisi SEMUA field yang bisa diatur admin pada landing page.
     * group: navbar | hero | fitur | galeri | info | footer | gambar
     */
    private function frontendFields(): array
    {
        return [
            // ── FRONT OFFICE & BRANDING ──────────────────────────────────────────
            ['key' => 'brand_name',      'label' => 'Nama Web Front Office',          'type' => 'text',     'group' => 'teks',   'default' => 'NATAS BPS MANGGARAI'],
            ['key' => 'brand_sub',       'label' => 'Kepanjangan / Sub-Judul',        'type' => 'text',     'group' => 'teks',   'default' => 'Navigasi Akses Terpadu Layanan Statistik BPS Manggarai'],
            ['key' => 'brand_tagline',   'label' => 'Tagline Utama',                  'type' => 'text',     'group' => 'teks',   'default' => 'Satu Ruang, Beragam Layanan Statistik.'],
            ['key' => 'brand_desc',      'label' => 'Deskripsi Singkat Portal',       'type' => 'textarea', 'group' => 'teks',   'default' => 'NATAS (Navigasi Akses Terpadu Layanan Statistik) BPS Kabupaten Manggarai adalah portal satu ruang untuk beragam layanan statistik terpadu, publikasi resmi, data indikator makro, rekomendasi kegiatan, dan konsultasi interaktif secara terintegrasi, transparan, dan terpercaya.'],
            ['key' => 'btn_masuk_label', 'label' => 'Label Tombol Masuk',             'type' => 'text',     'group' => 'teks',   'default' => 'Masuk'],

            // ── PANEL KANAN: Teks Header ────────────────────────────────────────
            ['key' => 'header_nama',     'label' => 'Nama Lembaga (Header)',          'type' => 'text',     'group' => 'teks',   'default' => 'BPS KABUPATEN'],
            ['key' => 'header_lokasi',   'label' => 'Lokasi Lembaga (Header)',        'type' => 'text',     'group' => 'teks',   'default' => 'MANGGARAI'],

            // ── WARNA ────────────────────────────────────────────────────────────
            ['key' => 'bg_left_color',   'label' => 'Warna Latar Panel Kiri',         'type' => 'color',    'group' => 'warna',  'default' => '#BEDAF0'],
            ['key' => 'btn_color',       'label' => 'Warna Tombol Masuk',             'type' => 'color',    'group' => 'warna',  'default' => '#1565C0'],

            // ── GAMBAR ───────────────────────────────────────────────────────────
            ['key' => 'img_logo_natas',   'label' => 'Logo Front Office (NATAS)',      'type' => 'image',    'group' => 'gambar', 'default' => null],
            ['key' => 'img_logo_simakin', 'label' => 'Logo SIMAKIN (Panel Kiri)',      'type' => 'image',    'group' => 'gambar', 'default' => null],
            ['key' => 'img_logo_bps',     'label' => 'Logo BPS (Panel Kanan Atas)',    'type' => 'image',    'group' => 'gambar', 'default' => null],
            ['key' => 'img_kantor',       'label' => 'Gambar Gedung / Kantor',         'type' => 'image',    'group' => 'gambar', 'default' => null],
            ['key' => 'img_quote_avatar', 'label' => 'Foto Kepala BPS (Quotes)',       'type' => 'image',    'group' => 'gambar', 'default' => null],

            // ── PENGUMUMAN TICKER ────────────────────────────────────────────────
            ['key' => 'ticker_text',      'label' => 'Teks Pengumuman Berjalan',       'type' => 'textarea', 'group' => 'pengumuman', 'default' => 'Selamat datang di NATAS BPS MANGGARAI (Navigasi Akses Terpadu Layanan Statistik BPS Kabupaten Manggarai) — Satu Ruang, Beragam Layanan Statistik. Hubungi Halo PST melalui WhatsApp untuk konsultasi dan permohonan data.'],

            // ── 8 INDIKATOR STRATEGIS (DATA POKOK) ────────────────────────────────
            ['key' => 'stat_penduduk',             'label' => 'Jumlah Penduduk (Jiwa)',             'type' => 'text', 'group' => 'data', 'default' => '338.414'],
            ['key' => 'stat_pertumbuhan_ekonomi',  'label' => 'Pertumbuhan Ekonomi (%)',            'type' => 'text', 'group' => 'data', 'default' => '5,02%'],
            ['key' => 'stat_inflasi',              'label' => 'Tingkat Inflasi y-on-y (%)',         'type' => 'text', 'group' => 'data', 'default' => '2,15%'],
            ['key' => 'stat_kemiskinan',           'label' => 'Persentase Penduduk Miskin (%)',     'type' => 'text', 'group' => 'data', 'default' => '19,84%'],
            ['key' => 'stat_tpt',                  'label' => 'Tingkat Pengangguran Terbuka TPT (%)','type' => 'text', 'group' => 'data', 'default' => '2,82%'],
            ['key' => 'stat_ipm',                  'label' => 'Indeks Pembangunan Manusia (IPM)',   'type' => 'text', 'group' => 'data', 'default' => '67,03'],
            ['key' => 'stat_gini',                 'label' => 'Gini Ratio (Ketimpangan)',           'type' => 'text', 'group' => 'data', 'default' => '0,312'],
            ['key' => 'stat_pdrb_kapita',          'label' => 'PDRB Per Kapita ADHB',               'type' => 'text', 'group' => 'data', 'default' => 'Rp 27,64 Jt'],

            // ── QUOTES OF THE DAY ────────────────────────────────────────────────
            ['key' => 'quote_nama',       'label' => 'Nama Tokoh / Pimpinan',          'type' => 'text',     'group' => 'quotes', 'default' => 'Dr. Ir. Habibie'],
            ['key' => 'quote_jabatan',    'label' => 'Jabatan Pimpinan',               'type' => 'text',     'group' => 'quotes', 'default' => 'KEPALA BPS KABUPATEN MANGGARAI'],
            ['key' => 'quote_text',       'label' => 'Isi Kutipan (Quotes)',           'type' => 'textarea', 'group' => 'quotes', 'default' => 'Data yang akurat dan berkualitas adalah fondasi utama bagi perencanaan, evaluasi pembangunan bangsa yang berkelanjutan serta kesejahteraan masyarakat secara merata.'],

            // ── LAYANAN PST & PENGADUAN ──────────────────────────────────────────
            ['key' => 'pst_whatsapp',     'label' => 'Nomor WhatsApp Halo PST',        'type' => 'text',     'group' => 'kontak', 'default' => '08113904990'],
            ['key' => 'pst_jam_layanan',  'label' => 'Jam Operasional PST',            'type' => 'text',     'group' => 'kontak', 'default' => 'Senin s.d. Jumat, 08.00 - 15.30 WITA'],
            ['key' => 'pst_alamat',       'label' => 'Alamat Ruang PST',               'type' => 'text',     'group' => 'kontak', 'default' => 'Kantor BPS Kab. Manggarai, Jl. Ranaka, Ruteng, Flores, NTT'],
            ['key' => 'pengaduan_email',  'label' => 'Email Saluran Pengaduan Resmi',  'type' => 'text',     'group' => 'kontak', 'default' => 'bps5310@bps.go.id'],
            ['key' => 'skm_link',         'label' => 'Link Survei Kepuasan Masyarakat (SKM)', 'type' => 'text', 'group' => 'kontak', 'default' => ''],

            // ── INTEGRASI API BPS (WEBAPI) ────────────────────────────────────────
            ['key' => 'bps_api_key',      'label' => 'BPS WebAPI Key (webapi.bps.go.id)', 'type' => 'text', 'group' => 'api_bps', 'default' => ''],
            ['key' => 'bps_domain',       'label' => 'Kode Domain Wilayah BPS',        'type' => 'text',     'group' => 'api_bps', 'default' => '5310'],
        ];
    }

    /** Kunci gambar yang valid untuk reset */
    private function imageKeys(): array
    {
        return array_values(array_map(
            fn($f) => $f['key'],
            array_filter($this->frontendFields(), fn($f) => $f['type'] === 'image')
        ));
    }

    public function frontendIndex()
    {
        $fields  = $this->frontendFields();
        $saved   = FrontendSetting::allKeyed();
        $settings = [];
        foreach ($fields as $field) {
            $settings[$field['key']] = [
                'label'   => $field['label'],
                'type'    => $field['type'],
                'group'   => $field['group'],
                'value'   => $saved[$field['key']] ?? $field['default'],
                'default' => $field['default'],
            ];
        }

        $navMenus   = FrontendSetting::getNavMenu();
        $galeri     = FrontendGallery::orderBy('urutan')->orderByDesc('created_at')->get();
        $infografis = FrontendInfografis::orderBy('urutan')->get();
        $quotes     = FrontendQuote::orderBy('urutan')->get();
        $kegiatans  = FrontendKegiatan::orderBy('urutan')->get();
        $indikators = FrontendIndikator::orderBy('urutan')->get();

        return view('admin.frontend.index', compact(
            'settings', 'galeri', 'navMenus', 'infografis', 'quotes', 'kegiatans', 'indikators'
        ));
    }

    public function frontendUpdate(Request $request)
    {
        // 1. Reset Navigasi ke Default jika diminta
        if ($request->has('reset_nav_menu')) {
            FrontendSetting::set('home_nav_menu', json_encode(FrontendSetting::defaultNavMenu()));
            return redirect()->route('admin.frontend.index')
                             ->with('success', 'Menu navigasi beranda berhasil dikembalikan ke standar awal.');
        }

        // 2. Simpan Pengaturan Menu Navigasi jika ada input nav_menu
        if ($request->has('nav_menu') && is_array($request->input('nav_menu'))) {
            $menus = [];
            foreach ($request->input('nav_menu') as $item) {
                if (!empty($item['label'])) {
                    $menus[] = [
                        'label'     => trim($item['label']),
                        'url'       => trim($item['url'] ?? '#'),
                        'target'    => ($item['target'] ?? '_self') === '_blank' ? '_blank' : '_self',
                        'is_active' => !empty($item['is_active']),
                    ];
                }
            }
            FrontendSetting::set('home_nav_menu', json_encode($menus));
        }

        $fields = $this->frontendFields();

        foreach ($fields as $field) {
            $key  = $field['key'];
            $type = $field['type'];

            if ($type === 'image') {
                if ($request->hasFile($key)) {
                    $request->validate([$key => 'image|mimes:jpg,jpeg,png,gif,webp|max:6144']);
                    $oldVal = FrontendSetting::get($key);
                    if ($oldVal && Storage::disk('public')->exists($oldVal)) {
                        Storage::disk('public')->delete($oldVal);
                    }
                    $path = $request->file($key)->store('frontend', 'public');
                    FrontendSetting::set($key, $path);
                }
            } elseif ($type === 'toggle') {
                FrontendSetting::set($key, $request->has($key) ? '1' : '0');
            } else {
                if ($request->has($key)) {
                    FrontendSetting::set($key, $request->input($key));
                }
            }
        }

        return redirect()->route('admin.frontend.index')
                         ->with('success', 'Tampilan halaman depan dan menu navigasi berhasil diperbarui.');
    }

    public function frontendResetImage(Request $request)
    {
        $key = $request->input('key');
        if (!in_array($key, $this->imageKeys())) {
            return redirect()->back()->with('error', 'Key gambar tidak valid.');
        }
        $oldVal = FrontendSetting::get($key);
        if ($oldVal && Storage::disk('public')->exists($oldVal)) {
            Storage::disk('public')->delete($oldVal);
        }
        FrontendSetting::where('key', $key)->delete();
        return redirect()->route('admin.frontend.index')
                         ->with('success', 'Gambar berhasil direset ke bawaan sistem.');
    }

    // ── GALERI CRUD ──────────────────────────────────────────────────────────

    public function galeriStore(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'image'     => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:6144',
            'kategori'  => 'required|in:kegiatan,dokumentasi,prestasi',
            'urutan'    => 'nullable|integer|min:0',
        ]);

        $path = $request->file('image')->store('galeri', 'public');

        FrontendGallery::create([
            'judul'      => $request->judul,
            'deskripsi'  => $request->deskripsi,
            'image_path' => $path,
            'kategori'   => $request->kategori,
            'urutan'     => $request->input('urutan', 0),
            'is_active'  => true,
        ]);

        return redirect()->route('admin.frontend.index', ['tab' => 'galeri'])
                         ->with('success', "Foto '" . $request->judul . "' berhasil ditambahkan ke galeri.");
    }

    public function galeriUpdate(Request $request, $id)
    {
        $foto = FrontendGallery::findOrFail($id);

        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:6144',
            'kategori'  => 'required|in:kegiatan,dokumentasi,prestasi',
            'urutan'    => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($foto->image_path)) {
                Storage::disk('public')->delete($foto->image_path);
            }
            $foto->image_path = $request->file('image')->store('galeri', 'public');
        }

        $foto->update([
            'judul'      => $request->judul,
            'deskripsi'  => $request->deskripsi,
            'image_path' => $foto->image_path,
            'kategori'   => $request->kategori,
            'urutan'     => $request->input('urutan', $foto->urutan),
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.frontend.index', ['tab' => 'galeri'])
                         ->with('success', "Foto '{$foto->judul}' berhasil diperbarui.");
    }

    public function galeriDestroy($id)
    {
        $foto = FrontendGallery::findOrFail($id);
        if (Storage::disk('public')->exists($foto->image_path)) {
            Storage::disk('public')->delete($foto->image_path);
        }
        $judul = $foto->judul;
        $foto->delete();

        return redirect()->route('admin.frontend.index', ['tab' => 'galeri'])
                         ->with('success', "Foto '{$judul}' berhasil dihapus dari galeri.");
    }

    public function galeriToggle($id)
    {
        $foto = FrontendGallery::findOrFail($id);
        $foto->update(['is_active' => !$foto->is_active]);
        $status = $foto->is_active ? 'ditampilkan' : 'disembunyikan';
        return redirect()->route('admin.frontend.index', ['tab' => 'galeri'])
                         ->with('success', "Foto '{$foto->judul}' berhasil {$status}.");
    }

    // ── INFOGRAFIS CRUD ───────────────────────────────────────────────────────
    public function infografisStore(Request $request)
    {
        $request->validate([
            'judul'       => 'required|string|max:255',
            'kategori'    => 'required|string|max:100',
            'badge_text'  => 'nullable|string|max:100',
            'bg_gradient' => 'nullable|string|max:100',
            'sumber'      => 'nullable|string|max:255',
            'tahun'       => 'nullable|string|max:100',
            'link_url'    => 'nullable|string|max:500',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:6144',
            'urutan'      => 'nullable|integer|min:0',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('infografis', 'public');
        }

        $dataPoints = [];
        if ($request->has('data_labels') && is_array($request->input('data_labels'))) {
            $labels = $request->input('data_labels');
            $values = $request->input('data_values', []);
            foreach ($labels as $i => $lbl) {
                if (!empty($lbl)) {
                    $dataPoints[] = [
                        'label' => trim($lbl),
                        'value' => trim($values[$i] ?? ''),
                    ];
                }
            }
        }

        FrontendInfografis::create([
            'judul'       => $request->judul,
            'kategori'    => strtolower($request->kategori),
            'badge_text'  => $request->badge_text ?: strtoupper('STATISTIK ' . $request->kategori),
            'bg_gradient' => $request->bg_gradient ?: 'from-blue-600 to-indigo-700',
            'data_points' => !empty($dataPoints) ? json_encode($dataPoints) : null,
            'sumber'      => $request->sumber ?: 'BPS Kabupaten Manggarai',
            'tahun'       => $request->tahun ?: date('Y'),
            'image_path'  => $imagePath,
            'link_url'    => $request->link_url ?: 'https://manggaraikab.bps.go.id',
            'urutan'      => $request->input('urutan', 0),
            'is_active'   => true,
        ]);

        return redirect()->route('admin.frontend.index', ['tab' => 'infografis'])
                         ->with('success', "Infografis '{$request->judul}' berhasil ditambahkan.");
    }

    public function infografisUpdate(Request $request, $id)
    {
        $info = FrontendInfografis::findOrFail($id);

        $request->validate([
            'judul'       => 'required|string|max:255',
            'kategori'    => 'required|string|max:100',
            'badge_text'  => 'nullable|string|max:100',
            'bg_gradient' => 'nullable|string|max:100',
            'sumber'      => 'nullable|string|max:255',
            'tahun'       => 'nullable|string|max:100',
            'link_url'    => 'nullable|string|max:500',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:6144',
            'urutan'      => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($info->image_path && Storage::disk('public')->exists($info->image_path)) {
                Storage::disk('public')->delete($info->image_path);
            }
            $info->image_path = $request->file('image')->store('infografis', 'public');
        }

        $dataPoints = [];
        if ($request->has('data_labels') && is_array($request->input('data_labels'))) {
            $labels = $request->input('data_labels');
            $values = $request->input('data_values', []);
            foreach ($labels as $i => $lbl) {
                if (!empty($lbl)) {
                    $dataPoints[] = [
                        'label' => trim($lbl),
                        'value' => trim($values[$i] ?? ''),
                    ];
                }
            }
        }

        $info->update([
            'judul'       => $request->judul,
            'kategori'    => strtolower($request->kategori),
            'badge_text'  => $request->badge_text ?: $info->badge_text,
            'bg_gradient' => $request->bg_gradient ?: $info->bg_gradient,
            'data_points' => !empty($dataPoints) ? json_encode($dataPoints) : $info->data_points,
            'sumber'      => $request->sumber ?: $info->sumber,
            'tahun'       => $request->tahun ?: $info->tahun,
            'image_path'  => $info->image_path,
            'link_url'    => $request->link_url ?: $info->link_url,
            'urutan'      => $request->input('urutan', $info->urutan),
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.frontend.index', ['tab' => 'infografis'])
                         ->with('success', "Infografis '{$info->judul}' berhasil diperbarui.");
    }

    public function infografisDestroy($id)
    {
        $info = FrontendInfografis::findOrFail($id);
        if ($info->image_path && Storage::disk('public')->exists($info->image_path)) {
            Storage::disk('public')->delete($info->image_path);
        }
        $judul = $info->judul;
        $info->delete();

        return redirect()->route('admin.frontend.index', ['tab' => 'infografis'])
                         ->with('success', "Infografis '{$judul}' berhasil dihapus.");
    }

    // ── QUOTES CRUD ───────────────────────────────────────────────────────────
    public function quoteStore(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'quote'   => 'required|string|max:1000',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:6144',
            'urutan'  => 'nullable|integer|min:0',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('quotes', 'public');
        }

        FrontendQuote::create([
            'nama'      => $request->nama,
            'jabatan'   => $request->jabatan ?: 'KEPALA BPS KABUPATEN MANGGARAI',
            'quote'     => $request->quote,
            'foto'      => $fotoPath,
            'urutan'    => $request->input('urutan', 0),
            'is_active' => true,
        ]);

        return redirect()->route('admin.frontend.index', ['tab' => 'quotes'])
                         ->with('success', "Kutipan dari '{$request->nama}' berhasil ditambahkan.");
    }

    public function quoteUpdate(Request $request, $id)
    {
        $q = FrontendQuote::findOrFail($id);

        $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'quote'   => 'required|string|max:1000',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:6144',
            'urutan'  => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('foto')) {
            if ($q->foto && Storage::disk('public')->exists($q->foto)) {
                Storage::disk('public')->delete($q->foto);
            }
            $q->foto = $request->file('foto')->store('quotes', 'public');
        }

        $q->update([
            'nama'      => $request->nama,
            'jabatan'   => $request->jabatan,
            'quote'     => $request->quote,
            'foto'      => $q->foto,
            'urutan'    => $request->input('urutan', $q->urutan),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.frontend.index', ['tab' => 'quotes'])
                         ->with('success', "Kutipan dari '{$q->nama}' berhasil diperbarui.");
    }

    public function quoteDestroy($id)
    {
        $q = FrontendQuote::findOrFail($id);
        if ($q->foto && Storage::disk('public')->exists($q->foto)) {
            Storage::disk('public')->delete($q->foto);
        }
        $nama = $q->nama;
        $q->delete();

        return redirect()->route('admin.frontend.index', ['tab' => 'quotes'])
                         ->with('success', "Kutipan dari '{$nama}' berhasil dihapus.");
    }

    // ── KEGIATAN CRUD ─────────────────────────────────────────────────────────
    public function kegiatanStore(Request $request)
    {
        $request->validate([
            'judul'           => 'required|string|max:255',
            'kategori'        => 'required|string|max:100',
            'tanggal_display' => 'nullable|string|max:100',
            'deskripsi'       => 'required|string|max:2000',
            'badge_color'     => 'nullable|string|max:50',
            'link_url'        => 'nullable|string|max:500',
            'urutan'          => 'nullable|integer|min:0',
        ]);

        FrontendKegiatan::create([
            'judul'           => $request->judul,
            'kategori'        => $request->kategori,
            'tanggal_display' => $request->tanggal_display ?: date('d F Y'),
            'deskripsi'       => $request->deskripsi,
            'badge_color'     => $request->badge_color ?: 'blue',
            'link_url'        => $request->link_url ?: '#',
            'urutan'          => $request->input('urutan', 0),
            'is_active'       => true,
        ]);

        return redirect()->route('admin.frontend.index', ['tab' => 'kegiatan'])
                         ->with('success', "Kegiatan '{$request->judul}' berhasil ditambahkan.");
    }

    public function kegiatanUpdate(Request $request, $id)
    {
        $keg = FrontendKegiatan::findOrFail($id);

        $request->validate([
            'judul'           => 'required|string|max:255',
            'kategori'        => 'required|string|max:100',
            'tanggal_display' => 'nullable|string|max:100',
            'deskripsi'       => 'required|string|max:2000',
            'badge_color'     => 'nullable|string|max:50',
            'link_url'        => 'nullable|string|max:500',
            'urutan'          => 'nullable|integer|min:0',
        ]);

        $keg->update([
            'judul'           => $request->judul,
            'kategori'        => $request->kategori,
            'tanggal_display' => $request->tanggal_display ?: $keg->tanggal_display,
            'deskripsi'       => $request->deskripsi,
            'badge_color'     => $request->badge_color ?: $keg->badge_color,
            'link_url'        => $request->link_url ?: $keg->link_url,
            'urutan'          => $request->input('urutan', $keg->urutan),
            'is_active'       => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.frontend.index', ['tab' => 'kegiatan'])
                         ->with('success', "Kegiatan '{$keg->judul}' berhasil diperbarui.");
    }

    public function kegiatanDestroy($id)
    {
        $keg = FrontendKegiatan::findOrFail($id);
        $judul = $keg->judul;
        $keg->delete();

        return redirect()->route('admin.frontend.index', ['tab' => 'kegiatan'])
                         ->with('success', "Kegiatan '{$judul}' berhasil dihapus.");
    }

    // ── INDIKATOR CAROUSEL CRUD ───────────────────────────────────────────────
    public function indikatorStore(Request $request)
    {
        $request->validate([
            'judul'      => 'required|string|max:255',
            'sub_judul'  => 'nullable|string|max:100',
            'nilai'      => 'required|string|max:100',
            'periode'    => 'nullable|string|max:100',
            'deskripsi'  => 'nullable|string|max:500',
            'badge_text' => 'nullable|string|max:100',
            'link_tabel' => 'nullable|string|max:500',
            'urutan'     => 'nullable|integer|min:0',
        ]);

        FrontendIndikator::create([
            'judul'      => $request->judul,
            'sub_judul'  => $request->sub_judul,
            'nilai'      => $request->nilai,
            'periode'    => $request->periode,
            'deskripsi'  => $request->deskripsi ?: 'Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.',
            'badge_text' => $request->badge_text ?: 'Tabel Dinamis',
            'link_tabel' => $request->link_tabel ?: '#datapokok',
            'urutan'     => $request->input('urutan', 0),
            'is_active'  => true,
        ]);

        return redirect()->route('admin.frontend.index', ['tab' => 'indikator'])
                         ->with('success', "Indikator '{$request->judul}' berhasil ditambahkan ke carousel.");
    }

    public function indikatorUpdate(Request $request, $id)
    {
        $ind = FrontendIndikator::findOrFail($id);

        $request->validate([
            'judul'      => 'required|string|max:255',
            'sub_judul'  => 'nullable|string|max:100',
            'nilai'      => 'required|string|max:100',
            'periode'    => 'nullable|string|max:100',
            'deskripsi'  => 'nullable|string|max:500',
            'badge_text' => 'nullable|string|max:100',
            'link_tabel' => 'nullable|string|max:500',
            'urutan'     => 'nullable|integer|min:0',
        ]);

        $ind->update([
            'judul'      => $request->judul,
            'sub_judul'  => $request->sub_judul,
            'nilai'      => $request->nilai,
            'periode'    => $request->periode,
            'deskripsi'  => $request->deskripsi ?: $ind->deskripsi,
            'badge_text' => $request->badge_text ?: $ind->badge_text,
            'link_tabel' => $request->link_tabel ?: $ind->link_tabel,
            'urutan'     => $request->input('urutan', $ind->urutan),
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.frontend.index', ['tab' => 'indikator'])
                         ->with('success', "Indikator '{$ind->judul}' berhasil diperbarui.");
    }

    public function indikatorDestroy($id)
    {
        $ind = FrontendIndikator::findOrFail($id);
        $judul = $ind->judul;
        $ind->delete();

        return redirect()->route('admin.frontend.index', ['tab' => 'indikator'])
                         ->with('success', "Indikator '{$judul}' berhasil dihapus.");
    }

    // =========================================================================
    // PENGATURAN TAMPILAN BACKEND
    // =========================================================================

    public function tampilanIndex()
    {
        $settings = \App\Models\AppSetting::pluck('value', 'key')->toArray();
        return view('admin.tampilan.index', compact('settings'));
    }

    public function tampilanUpdate(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'app_name'       => 'nullable|string|max:100',
            'primary_color'  => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'primary_dark'   => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'primary_light'  => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'primary_faint'  => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sidebar_style'  => 'nullable|in:light,dark',
            'logo'           => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        $keys = ['app_name', 'primary_color', 'primary_dark', 'primary_light', 'primary_faint', 'sidebar_style'];
        foreach ($keys as $key) {
            if ($request->filled($key)) {
                \App\Models\AppSetting::set($key, $request->input($key));
            }
        }

        if ($request->hasFile('logo')) {
            // Remove old logo
            $oldLogo = \App\Models\AppSetting::get('logo_path');
            if ($oldLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldLogo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('logo')->store('branding', 'public');
            \App\Models\AppSetting::set('logo_path', $path);
        }

        // Clear cached settings
        cache()->forget('app_settings');

        return redirect()->route('admin.tampilan.index')->with('success', 'Pengaturan tampilan berhasil disimpan.');
    }

    public function tampilanReset()
    {
        // Delete logo file
        $oldLogo = \App\Models\AppSetting::get('logo_path');
        if ($oldLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldLogo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldLogo);
        }

        \App\Models\AppSetting::truncate();
        cache()->forget('app_settings');

        return redirect()->route('admin.tampilan.index')->with('success', 'Pengaturan tampilan berhasil direset ke default.');
    }

    // =========================================================================
    // PENGUMUMAN BACKEND
    // =========================================================================

    public function pengumumanIndex()
    {
        $pengumumans = \App\Models\Pengumuman::with('creator')
            ->orderByDesc('created_at')
            ->paginate(15);
        return view('admin.pengumuman.index', compact('pengumumans'));
    }

    public function pengumumanStore(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'judul'           => 'required|string|max:255',
            'isi'             => 'required|string',
            'tipe'            => 'required|in:info,sukses,peringatan,bahaya',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        \App\Models\Pengumuman::create([
            'judul'           => $request->judul,
            'isi'             => $request->isi,
            'tipe'            => $request->tipe,
            'is_active'       => $request->boolean('is_active', true),
            'tanggal_mulai'   => $request->tanggal_mulai ?: null,
            'tanggal_selesai' => $request->tanggal_selesai ?: null,
            'created_by'      => auth()->id(),
        ]);

        cache()->forget('pengumuman_aktif');
        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function pengumumanUpdate(\Illuminate\Http\Request $request, $id)
    {
        $p = \App\Models\Pengumuman::findOrFail($id);

        $request->validate([
            'judul'           => 'required|string|max:255',
            'isi'             => 'required|string',
            'tipe'            => 'required|in:info,sukses,peringatan,bahaya',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $p->update([
            'judul'           => $request->judul,
            'isi'             => $request->isi,
            'tipe'            => $request->tipe,
            'is_active'       => $request->boolean('is_active', $p->is_active),
            'tanggal_mulai'   => $request->tanggal_mulai ?: null,
            'tanggal_selesai' => $request->tanggal_selesai ?: null,
        ]);

        cache()->forget('pengumuman_aktif');
        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function pengumumanDestroy($id)
    {
        $p = \App\Models\Pengumuman::findOrFail($id);
        $judul = $p->judul;
        $p->delete();
        cache()->forget('pengumuman_aktif');
        return redirect()->route('admin.pengumuman.index')->with('success', "Pengumuman '{$judul}' berhasil dihapus.");
    }

    public function pengumumanToggle($id)
    {
        $p = \App\Models\Pengumuman::findOrFail($id);
        $p->update(['is_active' => !$p->is_active]);
        cache()->forget('pengumuman_aktif');
        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Status pengumuman berhasil diubah.');
    }
}
