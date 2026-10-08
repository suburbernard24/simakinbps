<?php

namespace App\Services;

use App\Models\JenisSurat;
use App\Models\Surat;
use App\Models\SuratDetail;
use App\Models\SuratPegawai;
use App\Models\SuratPenerima;
use App\Models\TemplateSurat;
use App\Models\UnitKerja;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Pembuatan surat massal berdasarkan data Excel (mail merge).
 *
 * Setiap baris pada Excel dibaca sebagai data surat: perihal, pegawai yang ditugaskan,
 * tempat & tanggal kegiatan, penerima, dsb. Data tersebut diisikan ke template surat
 * sistem sehingga naskah surat ter-generate otomatis.
 */
class SuratExcelService
{
    /**
     * Kolom yang dikenali: key internal => [Judul kolom di template, ...alias lain]
     */
    public const COLUMNS = [
        'perihal'               => ['Perihal', 'hal', 'judul', 'perihal surat', 'hal surat', 'uraian ba', 'uraian berita acara', 'uraian'],
        'tanggal_surat'         => ['Tanggal Surat', 'tgl surat', 'tanggal', 'hari/tanggal berita acara', 'hari tanggal berita acara', 'tanggal berita acara', 'tgl ba'],
        'fungsi'                => ['Fungsi', 'unit kerja', 'unit', 'bagian'],
        'nip'                   => ['NIP Pegawai', 'nip', 'nip petugas'],
        'nama_pegawai'          => ['Nama Pegawai', 'nama', 'nama petugas', 'petugas', 'pegawai'],
        'peran'                 => ['Peran', 'peran tim', 'jabatan tim', 'tugas sebagai'],
        'wilayah'               => ['Wilayah Tugas', 'wilayah', 'desa', 'keterangan pegawai', 'lokasi tugas'],
        'tempat_kegiatan'       => ['Tempat Kegiatan', 'tempat', 'lokasi', 'tempat pelaksanaan'],
        'tanggal_kegiatan'      => ['Tanggal Mulai', 'tanggal kegiatan', 'tgl mulai', 'tanggal pelaksanaan', 'tanggal acara'],
        'tanggal_selesai'       => ['Tanggal Selesai', 'tgl selesai', 'sampai tanggal'],
        'nama_penerima'         => ['Nama Penerima', 'penerima', 'tujuan', 'kepada', 'yth'],
        'instansi_penerima'     => ['Instansi Penerima', 'instansi', 'instansi tujuan'],
        'alamat_penerima'       => ['Alamat Penerima', 'alamat', 'alamat tujuan'],
        'pihak_pertama'         => ['Pihak Pertama'],
        'jabatan_pihak_pertama' => ['Jabatan Pihak Pertama'],
        'pihak_kedua'           => ['Pihak Kedua'],
        'jabatan_pihak_kedua'   => ['Jabatan Pihak Kedua'],
        'dasar_penetapan'       => ['Dasar Penetapan', 'konsiderans', 'dasar hukum', 'menimbang'],
        'uraian_kegiatan'       => ['Uraian / Isi Surat', 'uraian', 'isi surat', 'uraian kegiatan', 'uraian tugas', 'isi', 'uraian ba'],
        'keterangan'            => ['Keterangan', 'sumber anggaran', 'keterangan tambahan'],
        'nomor_surat'           => ['Nomor Surat (Opsional)', 'nomor surat', 'no surat', 'nomor', 'nomor ba', 'no ba'],
    ];

    /**
     * Kolom template yang disarankan untuk setiap jenis surat.
     */
    public static function templateColumns(?string $kodeJenis): array
    {
        if (strtoupper((string) $kodeJenis) === 'BA') {
            return ['fungsi', 'tanggal_surat', 'nomor_surat', 'perihal', 'keterangan'];
        }

        $middle = match (strtoupper((string) $kodeJenis)) {
            'ST'  => ['nip', 'nama_pegawai', 'peran', 'wilayah', 'tempat_kegiatan', 'tanggal_kegiatan', 'tanggal_selesai'],
            'SK'  => ['nip', 'nama_pegawai', 'peran', 'wilayah', 'dasar_penetapan', 'tempat_kegiatan', 'tanggal_kegiatan', 'tanggal_selesai'],
            'B'   => ['nama_penerima', 'instansi_penerima', 'alamat_penerima'],
            'UND' => ['nama_penerima', 'instansi_penerima', 'alamat_penerima', 'tempat_kegiatan', 'tanggal_kegiatan'],
            default => ['nama_penerima', 'instansi_penerima'],
        };

        return array_merge(['perihal', 'tanggal_surat'], $middle, ['uraian_kegiatan', 'keterangan', 'nomor_surat']);
    }

    /**
     * Contoh isian baris untuk template Excel.
     */
    public static function exampleRows(?string $kodeJenis): array
    {
        $pegawais = User::where('is_active', true)
            ->where('role', '!=', 'admin')
            ->whereNotNull('nip')
            ->orderBy('name')
            ->take(2)
            ->get();

        if (strtoupper((string) $kodeJenis) === 'BA') {
            return [
                [
                    'fungsi'        => 'Nerwilis',
                    'tanggal_surat' => 'Selasa/24/02/2026',
                    'nomor_surat'   => '001 TAHUN 2026',
                    'perihal'       => 'Berita Acara Serah Terima Angka PDRB Kabupaten Manggarai Menurut Lapangan Usaha Tahun 2021-2025',
                    'keterangan'    => '',
                ],
                [
                    'fungsi'        => 'Nerwilis',
                    'tanggal_surat' => 'Selasa/24/02/2026',
                    'nomor_surat'   => '002 TAHUN 2026',
                    'perihal'       => 'Berita Acara Serah Terima Angka PDRB Kabupaten Manggarai Menurut Pengeluaran Tahun 2021-2025',
                    'keterangan'    => '',
                ],
                [
                    'fungsi'        => 'Umum',
                    'tanggal_surat' => 'selasa/30/06/2026',
                    'nomor_surat'   => '007 TAHUN 2026',
                    'perihal'       => 'BA Pemantauan Periodik Wasdal Semester I 2026',
                    'keterangan'    => '',
                ],
            ];
        }

        $rows = [];
        for ($i = 0; $i < 2; $i++) {
            $p = $pegawais[$i] ?? null;
            $rows[] = [
                'perihal'               => 'Surat Tugas Pendataan Survei Sosial Ekonomi Nasional',
                'tanggal_surat'         => Carbon::now()->format('Y-m-d'),
                'nip'                   => $p?->nip ?? '19900101201501100' . ($i + 1),
                'nama_pegawai'          => $p?->name ?? 'Nama Pegawai ' . ($i + 1),
                'peran'                 => $i === 0 ? 'Petugas Pengawas' : 'Petugas Pencacah',
                'wilayah'               => $i === 0 ? 'Kecamatan Langke Rembong' : 'Kecamatan Ruteng',
                'tempat_kegiatan'       => 'Kabupaten Manggarai',
                'tanggal_kegiatan'      => Carbon::now()->addDays(3)->format('Y-m-d'),
                'tanggal_selesai'       => Carbon::now()->addDays(10)->format('Y-m-d'),
                'nama_penerima'         => $i === 0 ? 'Kepala Dinas Pertanian' : 'Camat Langke Rembong',
                'instansi_penerima'     => $i === 0 ? 'Dinas Pertanian Kabupaten Manggarai' : 'Kantor Kecamatan Langke Rembong',
                'alamat_penerima'       => 'Ruteng, Kabupaten Manggarai',
                'pihak_pertama'         => $p?->name ?? 'Nama Pegawai BPS',
                'jabatan_pihak_pertama' => $p?->jabatan ?? 'Statistisi',
                'pihak_kedua'           => 'Nama Mitra ' . ($i + 1),
                'jabatan_pihak_kedua'   => 'Mitra Statistik',
                'dasar_penetapan'       => 'Undang-Undang Nomor 16 Tahun 1997 tentang Statistik',
                'uraian_kegiatan'       => 'Melaksanakan pendataan lapangan sesuai jadwal dan pedoman yang berlaku',
                'keterangan'            => 'Beban DIPA BPS Kabupaten Manggarai',
                'nomor_surat'           => '',
            ];
        }

        return $rows;
    }

    // =========================================================================
    // PEMBUATAN FILE TEMPLATE (.xlsx)
    // =========================================================================

    /**
     * Membuat file template .xlsx (tanpa library eksternal) dan mengembalikan path file sementara.
     */
    public static function buildTemplateXlsx(?string $kodeJenis): string
    {
        $keys = self::templateColumns($kodeJenis);
        $header = array_map(fn ($k) => self::COLUMNS[$k][0] . ($k === 'perihal' ? ' *' : ''), $keys);
        $rows = [$header];
        foreach (self::exampleRows($kodeJenis) as $ex) {
            $rows[] = array_map(fn ($k) => (string) ($ex[$k] ?? ''), $keys);
        }

        $sheetRows = '';
        foreach ($rows as $r => $cells) {
            $rowNum = $r + 1;
            $sheetRows .= '<row r="' . $rowNum . '">';
            foreach ($cells as $c => $val) {
                $ref = self::columnLetter($c) . $rowNum;
                $style = $r === 0 ? ' s="1"' : '';
                $sheetRows .= '<c r="' . $ref . '" t="inlineStr"' . $style . '><is><t xml:space="preserve">'
                    . htmlspecialchars($val, ENT_XML1) . '</t></is></c>';
            }
            $sheetRows .= '</row>';
        }

        $cols = '';
        foreach ($keys as $c => $k) {
            $width = in_array($k, ['perihal', 'uraian_kegiatan', 'keterangan', 'dasar_penetapan']) ? 45 : 24;
            $cols .= '<col min="' . ($c + 1) . '" max="' . ($c + 1) . '" width="' . $width . '" customWidth="1"/>';
        }

        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'
            . '<cols>' . $cols . '</cols>'
            . '<sheetData>' . $sheetRows . '</sheetData>'
            . '</worksheet>';

        $stylesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font></fonts>'
            . '<fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF0B5ED7"/><bgColor indexed="64"/></patternFill></fill></fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="2"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1"/></cellXfs>'
            . '</styleSheet>';

        $files = [
            '[Content_Types].xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
                . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
                . '<Default Extension="xml" ContentType="application/xml"/>'
                . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
                . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
                . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
                . '</Types>',
            '_rels/.rels' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
                . '</Relationships>',
            'xl/workbook.xml' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
                . '<sheets><sheet name="Data Surat" sheetId="1" r:id="rId1"/></sheets></workbook>',
            'xl/_rels/workbook.xml.rels' => '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
                . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
                . '</Relationships>',
            'xl/worksheets/sheet1.xml' => $sheetXml,
            'xl/styles.xml' => $stylesXml,
        ];

        $tmp = tempnam(sys_get_temp_dir(), 'tplsurat_') . '.xlsx';
        $zip = new \ZipArchive();
        $zip->open($tmp, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        foreach ($files as $name => $content) {
            $zip->addFromString($name, $content);
        }
        $zip->close();

        return $tmp;
    }

    private static function columnLetter(int $index): string
    {
        $letters = '';
        $index++;
        while ($index > 0) {
            $mod = ($index - 1) % 26;
            $letters = chr(65 + $mod) . $letters;
            $index = intdiv($index - $mod, 26);
        }
        return $letters;
    }

    private static function columnIndex(string $letters): int
    {
        $index = 0;
        foreach (str_split(strtoupper($letters)) as $ch) {
            $index = $index * 26 + (ord($ch) - 64);
        }
        return $index - 1;
    }

    // =========================================================================
    // PEMBACAAN FILE EXCEL
    // =========================================================================

    /**
     * Membaca file .xlsx / .xls (HTML) / .csv menjadi array baris (index kolom 0..n).
     */
    public static function readRows(string $path, string $extension): array
    {
        $extension = strtolower($extension);

        if (in_array($extension, ['csv', 'txt'])) {
            return self::readCsv($path);
        }
        if ($extension === 'xlsx') {
            return self::readXlsx($path);
        }
        if ($extension === 'xls') {
            return self::readHtmlTable($path);
        }

        return [];
    }

    private static function readCsv(string $path): array
    {
        $rows = [];
        $handle = fopen($path, 'r');
        if (!$handle) {
            return [];
        }
        $firstLine = (string) fgets($handle);
        rewind($handle);
        // Lewati BOM UTF-8
        if (str_starts_with($firstLine, "\xEF\xBB\xBF")) {
            fread($handle, 3);
        }
        $delimiter = substr_count($firstLine, ';') >= substr_count($firstLine, ',') ? ';' : ',';
        if (substr_count($firstLine, "\t") > max(substr_count($firstLine, ';'), substr_count($firstLine, ','))) {
            $delimiter = "\t";
        }

        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rows[] = array_map(fn ($v) => trim((string) $v), $data);
        }
        fclose($handle);

        return $rows;
    }

    private static function readXlsx(string $path): array
    {
        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return [];
        }

        // Shared strings
        $sharedStrings = [];
        $ssXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($ssXml) {
            $xml = @simplexml_load_string($ssXml);
            if ($xml) {
                foreach ($xml->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string) $si->t;
                    } else {
                        $text = '';
                        foreach ($si->r as $r) {
                            $text .= (string) $r->t;
                        }
                        $sharedStrings[] = $text;
                    }
                }
            }
        }

        // Cari sheet pertama melalui workbook.xml
        $sheetPath = null;
        $wbXml = $zip->getFromName('xl/workbook.xml');
        $relsXml = $zip->getFromName('xl/_rels/workbook.xml.rels');
        if ($wbXml && $relsXml) {
            $wb = @simplexml_load_string($wbXml);
            $rels = @simplexml_load_string($relsXml);
            if ($wb && $rels && isset($wb->sheets->sheet[0])) {
                $rid = (string) $wb->sheets->sheet[0]->attributes('r', true)->id;
                foreach ($rels->Relationship as $rel) {
                    if ((string) $rel['Id'] === $rid) {
                        $target = ltrim((string) $rel['Target'], '/');
                        $sheetPath = str_starts_with($target, 'xl/') ? $target : 'xl/' . $target;
                    }
                }
            }
        }
        $sheetXml = $sheetPath ? $zip->getFromName($sheetPath) : false;
        if (!$sheetXml) {
            $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        }
        $zip->close();

        if (!$sheetXml) {
            return [];
        }

        $xml = @simplexml_load_string($sheetXml);
        if (!$xml || !isset($xml->sheetData->row)) {
            return [];
        }

        $rows = [];
        foreach ($xml->sheetData->row as $row) {
            $rowData = [];
            $seq = 0;
            foreach ($row->c as $c) {
                // Gunakan referensi sel (mis. "C5") karena sel kosong tidak disimpan di xlsx
                $ref = (string) $c['r'];
                $col = $seq;
                if ($ref !== '' && preg_match('/^([A-Z]+)/i', $ref, $m)) {
                    $col = self::columnIndex($m[1]);
                }
                $type = (string) $c['t'];
                if ($type === 's') {
                    $val = $sharedStrings[(int) $c->v] ?? '';
                } elseif ($type === 'inlineStr') {
                    $val = isset($c->is->t) ? (string) $c->is->t : '';
                    if ($val === '' && isset($c->is->r)) {
                        foreach ($c->is->r as $r) {
                            $val .= (string) $r->t;
                        }
                    }
                } else {
                    $val = (string) $c->v;
                }
                $rowData[$col] = trim($val);
                $seq = $col + 1;
            }
            if (!empty($rowData)) {
                $max = max(array_keys($rowData));
                $filled = [];
                for ($i = 0; $i <= $max; $i++) {
                    $filled[$i] = $rowData[$i] ?? '';
                }
                $rows[] = $filled;
            }
        }

        return $rows;
    }

    private static function readHtmlTable(string $path): array
    {
        $content = (string) file_get_contents($path);
        $rows = [];
        if (preg_match_all('/<tr[^>]*>(.*?)<\/tr>/is', $content, $trs)) {
            foreach ($trs[1] as $tr) {
                if (preg_match_all('/<t[dh][^>]*>(.*?)<\/t[dh]>/is', $tr, $tds)) {
                    $rows[] = array_map(fn ($v) => trim(html_entity_decode(strip_tags($v))), $tds[1]);
                }
            }
        }
        return $rows;
    }

    // =========================================================================
    // PEMETAAN KOLOM
    // =========================================================================

    private static function normalizeHeader(string $text): string
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/\(.*?\)/', '', $text);      // hapus keterangan dalam kurung
        $text = preg_replace('/[^a-z0-9]+/', ' ', $text);  // hanya huruf & angka
        return trim(preg_replace('/\s+/', ' ', $text));
    }

    /**
     * Ubah baris mentah menjadi daftar record [key => nilai] berdasarkan baris judul kolom.
     *
     * @return array{records: array, unknown: array}
     */
    public static function mapRecords(array $rows): array
    {
        $aliases = [];
        foreach (self::COLUMNS as $key => $labels) {
            foreach ($labels as $label) {
                $aliases[self::normalizeHeader($label)] = $key;
            }
        }

        // Cari baris judul (harus ada kolom Perihal) dalam 10 baris pertama
        $headerIndex = null;
        $map = [];
        foreach (array_slice($rows, 0, 10, true) as $idx => $row) {
            $candidate = [];
            foreach ($row as $col => $cell) {
                $norm = self::normalizeHeader((string) $cell);
                if (isset($aliases[$norm]) && !in_array($aliases[$norm], $candidate, true)) {
                    $candidate[$col] = $aliases[$norm];
                }
            }
            if (in_array('perihal', $candidate, true)) {
                $headerIndex = $idx;
                $map = $candidate;
                break;
            }
        }

        if ($headerIndex === null) {
            throw new \RuntimeException('Baris judul kolom tidak ditemukan. Pastikan baris pertama berisi judul kolom dan minimal terdapat kolom "Perihal". Gunakan tombol Unduh Template.');
        }

        $unknown = [];
        foreach ($rows[$headerIndex] as $col => $cell) {
            if (!isset($map[$col]) && trim((string) $cell) !== '') {
                $unknown[] = trim((string) $cell);
            }
        }

        $records = [];
        foreach ($rows as $idx => $row) {
            if ($idx <= $headerIndex) {
                continue;
            }
            $record = ['_baris' => $idx + 1];
            $hasValue = false;
            foreach ($map as $col => $key) {
                $val = trim((string) ($row[$col] ?? ''));
                $record[$key] = $val;
                if ($val !== '') {
                    $hasValue = true;
                }
            }
            if ($hasValue) {
                $records[] = $record;
            }
        }

        return ['records' => $records, 'unknown' => $unknown];
    }

    /**
     * Parse tanggal dari berbagai format (serial Excel, d/m/Y, Y-m-d, "7 Oktober 2026").
     */
    public static function parseDate(?string $raw): ?string
    {
        $raw = trim((string) $raw);
        if ($raw === '') {
            return null;
        }

        try {
            if (is_numeric($raw) && (float) $raw > 20000 && (float) $raw < 80000) {
                return Carbon::create(1899, 12, 30)->addDays((int) floor((float) $raw))->format('Y-m-d');
            }
            if (preg_match('/^(?:[a-z]+[\/\s,]+)?(\d{1,2})[\/\-.](\d{1,2})[\/\-.](\d{4})$/i', $raw, $m)) {
                return Carbon::createFromDate((int) $m[3], (int) $m[2], (int) $m[1])->format('Y-m-d');
            }
            if (preg_match('/^(\d{4})[\/\-.](\d{1,2})[\/\-.](\d{1,2})/', $raw, $m)) {
                return Carbon::createFromDate((int) $m[1], (int) $m[2], (int) $m[3])->format('Y-m-d');
            }

            $bulan = [
                'januari' => 'january', 'februari' => 'february', 'maret' => 'march', 'april' => 'april',
                'mei' => 'may', 'juni' => 'june', 'juli' => 'july', 'agustus' => 'august',
                'september' => 'september', 'oktober' => 'october', 'november' => 'november',
                'desember' => 'december', 'agu' => 'aug', 'okt' => 'oct', 'des' => 'dec',
            ];
            $en = str_ireplace(array_keys($bulan), array_values($bulan), mb_strtolower($raw));
            return Carbon::parse($en)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private static function splitMulti(?string $value): array
    {
        $value = trim((string) $value);
        if ($value === '') {
            return [];
        }
        return array_values(array_filter(array_map('trim', preg_split('/[;\n,]+/', $value)), fn ($v) => $v !== ''));
    }

    // =========================================================================
    // PEMBUATAN SURAT DARI DATA EXCEL
    // =========================================================================

    /**
     * Buat surat dari record Excel.
     *
     * @param array $options [jenis_surat_id, template_surat_id, unit_kerja_id, kode_klasifikasi, mode (per_baris|gabung), status (selesai|draft)]
     * @return array{surats: array<Surat>, warnings: array<string>}
     */
    public static function createSurats(array $records, array $options, User $user): array
    {
        $jenisSurat = JenisSurat::findOrFail($options['jenis_surat_id']);
        $template = !empty($options['template_surat_id'])
            ? TemplateSurat::find($options['template_surat_id'])
            : TemplateSurat::where('jenis_surat_id', $jenisSurat->id)->where('aktif', true)->first();
        $unitKerjaId = $options['unit_kerja_id'] ?? $user->unit_kerja_id;
        $unitKerja = $unitKerjaId ? UnitKerja::find($unitKerjaId) : null;
        $kodeKlasifikasi = $options['kode_klasifikasi'] ?: ($jenisSurat->kode_klasifikasi_default ?? 'VS.330');
        $status = ($options['status'] ?? 'selesai') === 'draft' ? 'draft' : 'selesai';
        $mode = ($options['mode'] ?? 'per_baris') === 'gabung' ? 'gabung' : 'per_baris';

        // Master pegawai untuk pencocokan NIP / nama
        $pegawais = User::where('role', '!=', 'admin')->get();
        $byNip = [];
        $byName = [];
        foreach ($pegawais as $p) {
            if ($p->nip) {
                $byNip[preg_replace('/\D/', '', $p->nip)] = $p;
            }
            $byName[mb_strtolower(trim($p->name))] = $p;
        }

        $warnings = [];

        // Kelompokkan baris
        $groups = [];
        foreach ($records as $rec) {
            if (trim($rec['perihal'] ?? '') === '') {
                $warnings[] = "Baris {$rec['_baris']}: kolom Perihal kosong, baris dilewati.";
                continue;
            }
            if ($mode === 'gabung') {
                $key = mb_strtolower(implode('|', [
                    $rec['perihal'] ?? '', $rec['tanggal_surat'] ?? '',
                    $rec['nomor_surat'] ?? '', $rec['nama_penerima'] ?? '',
                ]));
                $groups[$key][] = $rec;
            } else {
                $groups[] = [$rec];
            }
        }

        $surats = [];

        DB::transaction(function () use (
            $groups, $jenisSurat, $template, $unitKerjaId, $unitKerja, $kodeKlasifikasi,
            $status, $user, $byNip, $byName, &$warnings, &$surats
        ) {
            foreach ($groups as $rows) {
                $first = $rows[0];
                $barisLabel = count($rows) > 1
                    ? 'Baris ' . $rows[0]['_baris'] . '-' . end($rows)['_baris']
                    : 'Baris ' . $first['_baris'];

                $tanggalSurat = self::parseDate($first['tanggal_surat'] ?? null);
                if (!empty($first['tanggal_surat']) && !$tanggalSurat) {
                    $warnings[] = "{$barisLabel}: format Tanggal Surat \"{$first['tanggal_surat']}\" tidak dikenali, memakai tanggal hari ini.";
                }

                $surat = Surat::create([
                    'jenis_surat_id'    => $jenisSurat->id,
                    'template_surat_id' => $template?->id,
                    'user_id'           => $user->id,
                    'unit_kerja_id'     => $unitKerjaId,
                    'tanggal_pengajuan' => Carbon::now(),
                    'tanggal_surat'     => $tanggalSurat ?? Carbon::now()->format('Y-m-d'),
                    'perihal'           => $first['perihal'],
                    'fungsi'            => $unitKerja?->nama ?? 'BPS Kabupaten Manggarai',
                    'status'            => $status,
                    'sumber_surat'      => 'sistem',
                    'nomor_surat'       => ($first['nomor_surat'] ?? '') !== '' ? $first['nomor_surat'] : null,
                    'kode_klasifikasi'  => $kodeKlasifikasi,
                    'kode_satker'       => '53131',
                ]);

                SuratDetail::create([
                    'surat_id'              => $surat->id,
                    'tanggal_kegiatan'      => self::parseDate($first['tanggal_kegiatan'] ?? null),
                    'tanggal_selesai'       => self::parseDate($first['tanggal_selesai'] ?? null),
                    'tempat_kegiatan'       => ($first['tempat_kegiatan'] ?? '') ?: null,
                    'uraian_kegiatan'       => ($first['uraian_kegiatan'] ?? '') ?: $first['perihal'],
                    'pejabat_penetap'       => 'Kepala Badan Pusat Statistik Kabupaten Manggarai',
                    'dasar_penetapan'       => ($first['dasar_penetapan'] ?? '') ?: null,
                    'pihak_pertama'         => ($first['pihak_pertama'] ?? '') ?: null,
                    'pihak_kedua'           => ($first['pihak_kedua'] ?? '') ?: null,
                    'jabatan_pihak_pertama' => ($first['jabatan_pihak_pertama'] ?? '') ?: null,
                    'jabatan_pihak_kedua'   => ($first['jabatan_pihak_kedua'] ?? '') ?: null,
                    'keterangan'            => ($first['keterangan'] ?? '') ?: null,
                ]);

                // Pegawai yang ditugaskan (bisa lebih dari satu per baris, dipisah koma/titik koma)
                $urutan = 0;
                $sudah = [];
                foreach ($rows as $row) {
                    $nips = self::splitMulti($row['nip'] ?? '');
                    $namas = self::splitMulti($row['nama_pegawai'] ?? '');
                    $count = max(count($nips), count($namas));
                    for ($i = 0; $i < $count; $i++) {
                        $nip = $nips[$i] ?? '';
                        $nama = $namas[$i] ?? '';
                        $pegawai = null;
                        if ($nip !== '') {
                            $pegawai = $byNip[preg_replace('/\D/', '', $nip)] ?? null;
                        }
                        if (!$pegawai && $nama !== '') {
                            $pegawai = $byName[mb_strtolower($nama)] ?? null;
                        }
                        if (!$pegawai) {
                            $label = trim($nama . ($nip !== '' ? " (NIP {$nip})" : ''));
                            $warnings[] = "Baris {$row['_baris']}: pegawai \"{$label}\" tidak ditemukan di master pegawai.";
                            continue;
                        }
                        if (isset($sudah[$pegawai->id])) {
                            continue;
                        }
                        $sudah[$pegawai->id] = true;
                        SuratPegawai::create([
                            'surat_id'   => $surat->id,
                            'pegawai_id' => $pegawai->id,
                            'peran'      => ($row['peran'] ?? '') ?: 'Anggota Tim',
                            'urutan'     => ++$urutan,
                            'keterangan' => ($row['wilayah'] ?? '') ?: null,
                        ]);
                    }
                }

                if (($first['nama_penerima'] ?? '') !== '' || ($first['instansi_penerima'] ?? '') !== '') {
                    SuratPenerima::create([
                        'surat_id'       => $surat->id,
                        'nama_penerima'  => ($first['nama_penerima'] ?? '') ?: $first['instansi_penerima'],
                        'instansi'       => ($first['instansi_penerima'] ?? '') ?: null,
                        'alamat'         => ($first['alamat_penerima'] ?? '') ?: null,
                        'jenis_penerima' => 'Eksternal',
                    ]);
                }

                if ($status === 'selesai') {
                    $nomor = $surat->nomor_surat ?: SuratService::generateNomorSurat($surat);
                    $surat->refresh();
                    SuratService::logStatus($surat, 'selesai', $user->id, "Surat dibuat otomatis dari data Excel ({$barisLabel}) dengan nomor resmi: {$nomor}");
                } else {
                    SuratService::logStatus($surat, 'draft', $user->id, "Draft surat dibuat otomatis dari data Excel ({$barisLabel}).");
                }

                $surats[] = $surat;
            }
        });

        return ['surats' => $surats, 'warnings' => $warnings];
    }
}
