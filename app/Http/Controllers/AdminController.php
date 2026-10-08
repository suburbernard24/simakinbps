<?php

namespace App\Http\Controllers;

use App\Models\AlokasiPekerjaan;
use App\Models\FrontendGallery;
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
            'nip'          => 'nullable|string|max:30',
            'jabatan'      => 'nullable|string|max:255',
            'golongan'     => 'nullable|string|max:50',
            'password'     => 'required|string|min:6',
            'role'         => 'required|in:admin,kepala_bps,ketua_tim,pegawai',
            'tim_id'       => 'nullable|exists:teams,id',
            'is_ketua_tim' => 'nullable|boolean',
            'is_active'    => 'nullable|boolean',
        ]);

        $isKetuaTim = $request->role === 'ketua_tim' || $request->boolean('is_ketua_tim');

        $user = User::create([
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'nip'          => $request->nip,
            'jabatan'      => $request->jabatan,
            'golongan'     => $request->golongan,
            'password'     => Hash::make($request->password),
            'role'         => $request->role,
            'tim_id'       => $request->tim_id,
            'is_ketua_tim' => $isKetuaTim,
            'is_active'    => $request->boolean('is_active', true),
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
        $sheet->setCellValue('A2', 'Petunjuk: Isi data pegawai mulai baris ke-4. Kolom (*) wajib diisi. Kolom bertanda dropdown (Tim, Role, Status, Ketua Tim) silakan pilih dari opsi dropdown. Baris 4 adalah baris contoh dan dapat diganti.');
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
            'H3' => 'Jabatan',
            'I3' => 'Golongan/Pangkat',
            'J3' => 'Role Akses (*)',
            'K3' => 'Status Akun Aktif (*)',
            'L3' => 'Tugaskan sebagai Ketua Tim (*)',
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
            'H4' => 'Statistisi Ahli Pertama',
            'I4' => 'III/a',
            'J4' => 'Anggota Tim',
            'K4' => 'Ya',
            'L4' => 'Tidak',
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
        $sheet->getStyle('K4:L4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

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
            'I' => 18,
            'J' => 24,
            'K' => 20,
            'L' => 28,
        ];
        foreach ($colWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Terapkan Data Validation (Dropdown) Baris 4 s.d. 150
        for ($r = 4; $r <= 150; $r++) {
            // Dropdown Tim Kerja (Kolom E)
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

            // Dropdown Role Akses (Kolom J)
            $valRole = $sheet->getCell('J' . $r)->getDataValidation();
            $valRole->setType(DataValidation::TYPE_LIST);
            $valRole->setErrorStyle(DataValidation::STYLE_STOP);
            $valRole->setAllowBlank(false);
            $valRole->setShowDropDown(true);
            $valRole->setShowErrorMessage(true);
            $valRole->setErrorTitle('Role Tidak Valid');
            $valRole->setError('Silakan pilih role dari daftar dropdown.');
            $valRole->setFormula1('"Anggota Tim, Ketua Tim, Kepala BPS, Administrator Sistem"');

            // Dropdown Status Akun Aktif (Kolom K)
            $valActive = $sheet->getCell('K' . $r)->getDataValidation();
            $valActive->setType(DataValidation::TYPE_LIST);
            $valActive->setErrorStyle(DataValidation::STYLE_STOP);
            $valActive->setAllowBlank(true);
            $valActive->setShowDropDown(true);
            $valActive->setShowErrorMessage(true);
            $valActive->setErrorTitle('Pilihan Tidak Valid');
            $valActive->setError('Pilih Ya atau Tidak.');
            $valActive->setFormula1('"Ya, Tidak"');

            // Dropdown Tugaskan sebagai Ketua Tim (Kolom L)
            $valKetua = $sheet->getCell('L' . $r)->getDataValidation();
            $valKetua->setType(DataValidation::TYPE_LIST);
            $valKetua->setErrorStyle(DataValidation::STYLE_STOP);
            $valKetua->setAllowBlank(true);
            $valKetua->setShowDropDown(true);
            $valKetua->setShowErrorMessage(true);
            $valKetua->setErrorTitle('Pilihan Tidak Valid');
            $valKetua->setError('Pilih Ya atau Tidak.');
            $valKetua->setFormula1('"Ya, Tidak"');
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
            'jabatan'  => 'H',
            'golongan' => 'I',
            'role'     => 'J',
            'active'   => 'K',
            'ketua'    => 'L',
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

            $nama      = trim($row[$colMap['nama']] ?? '');
            $username  = trim($row[$colMap['username']] ?? '');
            $password  = trim($row[$colMap['password']] ?? '');
            $timRaw    = trim($row[$colMap['tim']] ?? '');
            $nip       = trim($row[$colMap['nip']] ?? '');
            $email     = trim($row[$colMap['email']] ?? '');
            $jabatan   = trim($row[$colMap['jabatan']] ?? '');
            $golongan  = trim($row[$colMap['golongan']] ?? '');
            $roleRaw   = trim($row[$colMap['role']] ?? '');
            $activeRaw = trim($row[$colMap['active']] ?? '');
            $ketuaRaw  = trim($row[$colMap['ketua']] ?? '');

            // Lewati baris kosong
            if (empty($nama) && empty($username) && empty($password) && empty($nip) && empty($email)) {
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
            $cleanUsername = strtolower($username);
            if (empty($username)) {
                $errors[] = "Baris {$rowNum}: Kolom Username Login wajib diisi.";
                $hasRowError = true;
            } elseif (!preg_match('/^[a-zA-Z0-9._-]+$/', $username)) {
                $errors[] = "Baris {$rowNum}: Username '{$username}' hanya boleh berupa huruf, angka, titik, strip (-), atau underscore (_).";
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
            'nip'          => 'nullable|string|max:30',
            'jabatan'      => 'nullable|string|max:255',
            'golongan'     => 'nullable|string|max:50',
            'password'     => 'nullable|string|min:6',
            'role'         => 'required|in:admin,kepala_bps,ketua_tim,pegawai',
            'tim_id'       => 'nullable|exists:teams,id',
            'is_ketua_tim' => 'nullable|boolean',
            'is_active'    => 'nullable|boolean',
        ]);

        $isKetuaTim = $request->role === 'ketua_tim' || $request->boolean('is_ketua_tim');

        $data = [
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'nip'          => $request->nip,
            'jabatan'      => $request->jabatan,
            'golongan'     => $request->golongan,
            'role'         => $request->role,
            'tim_id'       => $request->tim_id,
            'is_ketua_tim' => $isKetuaTim,
            'is_active'    => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pegawai->update($data);

        if ($isKetuaTim && $request->tim_id) {
            Team::where('id', $request->tim_id)->update(['ketua_id' => $pegawai->id]);
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
            // ── PANEL KIRI: Teks Branding ──────────────────────────────────────
            ['key' => 'brand_tagline',   'label' => 'Judul / Tagline Utama',          'type' => 'text',     'group' => 'teks',   'default' => 'Sistem Informasi Manajemen Kinerja'],
            ['key' => 'brand_sub',       'label' => 'Sub-Judul (Nama Instansi)',       'type' => 'text',     'group' => 'teks',   'default' => 'BPS Kabupaten Manggarai'],
            ['key' => 'brand_desc',      'label' => 'Deskripsi Aplikasi',             'type' => 'textarea', 'group' => 'teks',   'default' => 'SIMAKINBPS adalah platform digital manajemen kinerja pegawai di BPS Kabupaten Manggarai yang dirancang untuk mendukung perencanaan, pemantauan, dan evaluasi kinerja secara terintegrasi, transparan, dan efektif.'],
            ['key' => 'btn_masuk_label', 'label' => 'Label Tombol Masuk',             'type' => 'text',     'group' => 'teks',   'default' => 'Masuk'],

            // ── PANEL KANAN: Teks Header ────────────────────────────────────────
            ['key' => 'header_nama',     'label' => 'Nama Instansi (Panel Kanan)',    'type' => 'text',     'group' => 'teks',   'default' => 'BPS KABUPATEN'],
            ['key' => 'header_lokasi',   'label' => 'Lokasi Instansi (Panel Kanan)', 'type' => 'text',     'group' => 'teks',   'default' => 'MANGGARAI'],

            // ── WARNA ────────────────────────────────────────────────────────────
            ['key' => 'bg_left_color',   'label' => 'Warna Latar Panel Kiri',         'type' => 'color',    'group' => 'warna',  'default' => '#BEDAF0'],
            ['key' => 'btn_color',       'label' => 'Warna Tombol Masuk',             'type' => 'color',    'group' => 'warna',  'default' => '#1565C0'],

            // ── GAMBAR ───────────────────────────────────────────────────────────
            ['key' => 'img_logo_simakin','label' => 'Logo SIMAKIN (Panel Kiri)',      'type' => 'image',    'group' => 'gambar', 'default' => null],
            ['key' => 'img_logo_bps',    'label' => 'Logo BPS (Panel Kanan Atas)',    'type' => 'image',    'group' => 'gambar', 'default' => null],
            ['key' => 'img_kantor',      'label' => 'Gambar Gedung / Kantor',         'type' => 'image',    'group' => 'gambar', 'default' => null],
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

        $galeri = FrontendGallery::orderBy('urutan')->orderByDesc('created_at')->get();

        return view('admin.frontend.index', compact('settings', 'galeri'));
    }

    public function frontendUpdate(Request $request)
    {
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
                         ->with('success', 'Tampilan halaman depan berhasil diperbarui.');
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
}
