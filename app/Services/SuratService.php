<?php

namespace App\Services;

use App\Models\NomorSurat;
use App\Models\Surat;
use App\Models\SuratStatusHistory;
use App\Models\SuratVerifikasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SuratService
{
    /**
     * Otomatisasi generate nomor surat resmi saat disetujui (PRD 16 & 10.10)
     */
    public static function generateNomorSurat(Surat $surat): string
    {
        if (!empty($surat->nomor_surat)) {
            return $surat->nomor_surat;
        }

        // Cek jika sudah pernah tersimpan di tabel nomor_surats
        $existingNomor = NomorSurat::where('surat_id', $surat->id)->first();
        if ($existingNomor) {
            $surat->nomor_surat = $existingNomor->nomor_lengkap;
            $surat->status = 'selesai';
            $surat->save();
            DB::table('surats')->where('id', $surat->id)->update([
                'nomor_surat' => $existingNomor->nomor_lengkap,
                'status' => 'selesai',
            ]);
            return $existingNomor->nomor_lengkap;
        }

        return DB::transaction(function () use ($surat) {
            $tahun = Carbon::parse($surat->tanggal_surat ?? now())->year;
            $satker = $surat->kode_satker ?: '53131';
            $klasifikasi = $surat->kode_klasifikasi ?: ($surat->jenisSurat->kode_klasifikasi_default ?? 'VS.330');
            $jenisSurat = $surat->jenisSurat;

            // Hitung nomor urut per tahun dan per jenis surat
            $lastNomor = NomorSurat::where('jenis_surat_id', $surat->jenis_surat_id)
                ->where('tahun', $tahun)
                ->max('nomor_urut') ?? 0;

            $nomorUrut = $lastNomor + 1;
            $nomorUrutPadded = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);

            // Format nomor: B-{nomor_urut}/{kode_satker}/{kode_klasifikasi}/{tahun}
            $format = $jenisSurat->format_nomor ?: 'B-{nomor_urut}/{kode_satker}/{kode_klasifikasi}/{tahun}';
            $nomorLengkap = str_replace(
                ['{kode}', '{nomor_urut}', '{kode_satker}', '{kode_klasifikasi}', '{tahun}'],
                [$jenisSurat->kode, $nomorUrutPadded, $satker, $klasifikasi, $tahun],
                $format
            );

            // Save ke tabel nomor_surats
            NomorSurat::create([
                'surat_id'         => $surat->id,
                'jenis_surat_id'   => $surat->jenis_surat_id,
                'tahun'            => $tahun,
                'nomor_urut'       => $nomorUrut,
                'kode_satker'      => $satker,
                'kode_klasifikasi' => $klasifikasi,
                'nomor_lengkap'    => $nomorLengkap,
            ]);

            // Update surat model dan direct DB update
            $surat->nomor_surat = $nomorLengkap;
            $surat->status = 'selesai';
            $surat->save();

            DB::table('surats')->where('id', $surat->id)->update([
                'nomor_surat' => $nomorLengkap,
                'status' => 'selesai',
            ]);

            return $nomorLengkap;
        });
    }

    /**
     * Catat riwayat perubahan status (PRD 17 & 10.13)
     */
    public static function logStatus(Surat $surat, string $status, ?int $userId = null, ?string $catatan = null): SuratStatusHistory
    {
        return SuratStatusHistory::create([
            'surat_id' => $surat->id,
            'user_id'  => $userId ?? auth()->id(),
            'status'   => $status,
            'catatan'  => $catatan,
        ]);
    }

    /**
     * Render template surat dengan mengganti placeholder variabel (PRD 15)
     */
    public static function renderContent(Surat $surat): string
    {
        $nomorSuratVal = !empty($surat->nomor_surat) 
            ? $surat->nomor_surat 
            : ($surat->nomorSuratRecord->nomor_lengkap ?? '-');

        // Jika surat diunggah dari luar sistem dengan format Word (.docx), naskah diekstrak langsung dari file aslinya
        if ($surat->isDibuatDiLuar() && $surat->file_surat) {
            $path = storage_path('app/public/' . $surat->file_surat);
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if ($ext === 'docx' && file_exists($path)) {
                self::syncDocxNomorSurat($surat);
                $html = self::parseDocxToHtml($path, $nomorSuratVal);
                if (!empty(trim(strip_tags($html)))) {
                    return $html;
                }
            }
        }

        $template = $surat->templateSurat;
        $content = $template ? $template->isi_template : ($surat->detail->uraian_kegiatan ?? '');

        $pegawaiUtama = $surat->pegawais->first()?->pegawai;
        $penerimaUtama = $surat->penerimas->first();

        $tglSurat = Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y');
        $tglKegiatan = '';
        if ($surat->detail?->tanggal_kegiatan) {
            $tglKegiatan = Carbon::parse($surat->detail->tanggal_kegiatan)->translatedFormat('d F Y');
            if ($surat->detail->tanggal_selesai && $surat->detail->tanggal_selesai != $surat->detail->tanggal_kegiatan) {
                $tglKegiatan .= ' s.d. ' . Carbon::parse($surat->detail->tanggal_selesai)->translatedFormat('d F Y');
            }
        }

        $replaces = [
            '{nomor_surat}'       => $nomorSuratVal,
            '{tanggal_surat}'     => $tglSurat,
            '{nama}'              => $pegawaiUtama?->name ?? ($surat->pemohon->name ?? ''),
            '{nip}'               => $pegawaiUtama?->nip ?? ($surat->pemohon->nip ?? '-'),
            '{jabatan}'           => $pegawaiUtama?->jabatan ?? ($surat->pemohon->jabatan ?? '-'),
            '{unit_kerja}'        => $surat->unitKerja?->nama ?? ($surat->fungsi ?? 'BPS Kabupaten Manggarai'),
            '{tujuan}'            => $penerimaUtama?->nama_penerima ?? ($penerimaUtama?->instansi ?? 'Pihak Terkait'),
            '{perihal}'           => $surat->perihal,
            '{tempat}'            => $surat->detail?->tempat_kegiatan ?? 'Kabupaten Manggarai',
            '{tanggal_kegiatan}'  => $tglKegiatan,
            '{uraian_kegiatan}'   => $surat->detail?->uraian_kegiatan ?? '',
            '{dasar_penetapan}'   => $surat->detail?->dasar_penetapan ?? '',
        ];

        return str_replace(array_keys($replaces), array_values($replaces), $content);
    }

    /**
     * Sinkronisasi dan update placeholder nomor surat langsung pada berkas Word (.docx)
     */
    public static function syncDocxNomorSurat(Surat $surat): bool
    {
        if (!$surat->isDibuatDiLuar() || !$surat->file_surat || empty($surat->nomor_surat)) {
            return false;
        }

        $filePath = storage_path('app/public/' . $surat->file_surat);
        if (!file_exists($filePath)) {
            return false;
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if ($ext !== 'docx') {
            return false;
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            return false;
        }

        $xml = $zip->getFromName('word/document.xml');
        if (!$xml) {
            $zip->close();
            return false;
        }

        $dom = new \DOMDocument();
        @$dom->loadXML($xml);
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

        $modified = false;
        foreach ($xpath->query('//w:p') as $p) {
            $text = '';
            foreach ($xpath->query('.//w:t', $p) as $t) {
                $text .= $t->nodeValue;
            }

            if (preg_match('/^(\s*NOMOR\s*[:\.]?)\s*([…\.\/]+.*|\s+TAHUN\s+[…\.]+.*)?$/ui', $text)) {
                $runs = $xpath->query('.//w:r', $p);
                if ($runs->length > 0) {
                    $firstRun = $runs->item(0);
                    $firstT = $xpath->query('.//w:t', $firstRun)->item(0);
                    if ($firstT) {
                        $firstT->nodeValue = 'NOMOR: ' . $surat->nomor_surat;
                    }
                    for ($i = $runs->length - 1; $i > 0; $i--) {
                        $p->removeChild($runs->item($i));
                    }
                    $modified = true;
                }
            }
        }

        if ($modified) {
            $newXml = $dom->saveXML();
            $zip->addFromString('word/document.xml', $newXml);
        }

        $zip->close();
        return $modified;
    }

    /**
     * Ekstrak teks, gambar, dan tabel dari file Word (.docx) menjadi HTML dengan fidelitas tinggi.
     * Mendukung: gambar (base64), bold/italic/underline, nama font, ukuran font,
     *            spasi baris, spasi paragraf, rata teks, tabel, dan penggantian nomor surat.
     */
    public static function parseDocxToHtml(string $filePath, string $nomorSurat = ''): string
    {
        if (!file_exists($filePath)) {
            return '';
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            return '';
        }

        $xml        = $zip->getFromName('word/document.xml');
        $relsXml    = $zip->getFromName('word/_rels/document.xml.rels');

        // Extract all embedded images by relationship ID
        $images = [];
        if ($relsXml) {
            $rdom = new \DOMDocument();
            @$rdom->loadXML($relsXml);
            foreach ($rdom->getElementsByTagNameNS(
                'http://schemas.openxmlformats.org/package/2006/relationships', 'Relationship'
            ) as $rel) {
                $type   = $rel->getAttribute('Type');
                $rid    = $rel->getAttribute('Id');
                $target = $rel->getAttribute('Target');
                if (strpos($type, '/image') !== false) {
                    $imgData = $zip->getFromName('word/' . $target);
                    if ($imgData !== false) {
                        $ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));
                        $mime = match($ext) {
                            'png'  => 'image/png',
                            'jpg','jpeg' => 'image/jpeg',
                            'gif'  => 'image/gif',
                            'svg'  => 'image/svg+xml',
                            default => 'image/png',
                        };
                        $images[$rid] = 'data:' . $mime . ';base64,' . base64_encode($imgData);
                    }
                }
            }
        }

        $zip->close();

        if (!$xml) {
            return '';
        }

        $dom = new \DOMDocument();
        @$dom->loadXML($xml);
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('w',   'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $xpath->registerNamespace('r',   'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $xpath->registerNamespace('wp',  'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing');
        $xpath->registerNamespace('pic', 'http://schemas.openxmlformats.org/drawingml/2006/picture');
        $xpath->registerNamespace('a',   'http://schemas.openxmlformats.org/drawingml/2006/main');

        $output = [];
        $body = $xpath->query('//w:body')->item(0);
        if (!$body) {
            return '';
        }

        foreach ($body->childNodes as $node) {
            $tag = $node->localName;

            if ($tag === 'p') {
                $output[] = self::renderDocxParagraph($node, $xpath, $images, $nomorSurat);
            } elseif ($tag === 'tbl') {
                $output[] = self::renderDocxTable($node, $xpath, $images, $nomorSurat);
            }
            // sectPr and other structural nodes are ignored
        }

        return implode("\n", $output);
    }

    /**
     * Render satu paragraf docx menjadi HTML <p> dengan format run-level penuh.
     */
    private static function renderDocxParagraph(
        \DOMNode $p, \DOMXPath $xpath, array $images, string $nomorSurat = ''
    ): string {
        // --- Paragraph-level properties ---
        $align   = 'left';
        $lineH   = null;  // null = inherit
        $spBefore = 0;    // pt
        $spAfter  = 0;    // pt
        $indent  = null;  // left indent in pt

        $pPr = $xpath->query('w:pPr', $p)->item(0);
        if ($pPr) {
            $jc = $xpath->query('w:jc', $pPr)->item(0);
            if ($jc) {
                $val = $jc->getAttribute('w:val');
                $align = match($val) {
                    'center' => 'center',
                    'right'  => 'right',
                    'both'   => 'justify',
                    default  => 'left',
                };
            }
            $spacing = $xpath->query('w:spacing', $pPr)->item(0);
            if ($spacing) {
                $line  = $spacing->getAttribute('w:line');  // twips for line spacing
                $rule  = $spacing->getAttribute('w:lineRule');
                $after = $spacing->getAttribute('w:after'); // twips
                $before= $spacing->getAttribute('w:before');
                // Convert twips to CSS line-height (240 twips = single, 480 = double)
                if ($line && $rule === 'auto') {
                    $lineH = round((float)$line / 240, 2);
                }
                if ($after)  $spAfter  = round((float)$after  / 20, 1); // twips→pt
                if ($before) $spBefore = round((float)$before / 20, 1);
            }
            $indNode = $xpath->query('w:ind', $pPr)->item(0);
            if ($indNode) {
                $leftTwips = $indNode->getAttribute('w:left');
                if ($leftTwips) $indent = round((float)$leftTwips / 20, 1); // twips→pt
            }
        }

        // --- Collect full plain text for nomor injection check ---
        $fullText = '';
        foreach ($xpath->query('.//w:t', $p) as $t) {
            $fullText .= $t->nodeValue;
        }

        // --- Check for image-only paragraph ---
        $hasDrawing = $xpath->query('.//w:drawing', $p)->length > 0;

        // Build paragraph style
        $pStyle = "text-align:{$align};";
        if ($lineH !== null) $pStyle .= " line-height:{$lineH};";
        else $pStyle .= " line-height:1.5;";
        $pStyle .= " margin-top:{$spBefore}pt; margin-bottom:{$spAfter}pt;";
        if ($indent !== null) $pStyle .= " margin-left:{$indent}pt;";

        if (trim($fullText) === '' && !$hasDrawing) {
            return "<p style=\"min-height:1em;{$pStyle}\">&nbsp;</p>";
        }

        // --- Apply nomor_surat replacement at paragraph level if entire paragraph is the nomor line ---
        $nomorReplaced = false;
        if (!empty($nomorSurat)) {
            if (preg_match('/^\s*NOMOR\s*[:\.]?\s*[\d…\.\/\s]*$/ui', $fullText)
                || preg_match('/^\s*NOMOR\s*[:\.]?\s*$/ui', $fullText)) {
                $nomorReplaced = true;
            }
        }

        // --- Render runs ---
        $innerHtml = '';
        if ($nomorReplaced) {
            // Render single-run replacement inheriting first run's formatting
            $firstRun = $xpath->query('w:r', $p)->item(0);
            $runStyle = $firstRun ? self::getRunStyle($firstRun, $xpath) : '';
            $innerHtml = "<span style=\"{$runStyle}\">"
                . htmlspecialchars('NOMOR: ' . $nomorSurat)
                . "</span>";
        } else {
            foreach ($p->childNodes as $child) {
                $cTag = $child->localName;
                if ($cTag === 'r') {
                    $innerHtml .= self::renderDocxRun($child, $xpath, $images, $nomorSurat);
                } elseif ($cTag === 'hyperlink') {
                    // Render hyperlink runs
                    foreach ($child->childNodes as $hChild) {
                        if ($hChild->localName === 'r') {
                            $innerHtml .= self::renderDocxRun($hChild, $xpath, $images, $nomorSurat);
                        }
                    }
                }
                // pPr and bookmarks are skipped
            }
        }

        return "<p style=\"{$pStyle}\">{$innerHtml}</p>";
    }

    /**
     * Render satu run (w:r) menjadi HTML span dengan format lengkap.
     */
    private static function renderDocxRun(
        \DOMNode $r, \DOMXPath $xpath, array $images, string $nomorSurat = ''
    ): string {
        $output = '';

        // Check for drawing/image
        $drawings = $xpath->query('w:drawing', $r);
        foreach ($drawings as $drawing) {
            $blip = $xpath->query('.//' .
                'pic:pic/pic:blipFill/a:blip', $drawing)->item(0);
            if ($blip) {
                $rid = $blip->getAttributeNS(
                    'http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'embed'
                );
                // Try extent for dimensions
                $extent = $xpath->query('.//wp:extent', $drawing)->item(0);
                $width  = $extent ? round($extent->getAttribute('cx') / 9144) : 75; // EMU→px (approx)
                $height = $extent ? round($extent->getAttribute('cy') / 9144) : 75;
                if ($rid && isset($images[$rid])) {
                    $output .= "<img src=\"{$images[$rid]}\" "
                        . "width=\"{$width}\" height=\"{$height}\" "
                        . "style=\"max-width:100%;vertical-align:middle;\" alt=\"gambar\">";
                }
            }
        }

        // Collect run text parts
        $texts = $xpath->query('w:t', $r);
        if ($texts->length === 0) {
            return $output;
        }

        $text = '';
        foreach ($texts as $t) {
            $text .= $t->nodeValue;
        }

        if ($text === '') {
            return $output;
        }

        // Inject nomor surat into run text if this run contains the NOMOR placeholder
        if (!empty($nomorSurat)) {
            if (preg_match('/NOMOR\s*[:\.]?\s*[…\.\/0-9\s]*/ui', $text) && strpos($text, 'NOMOR') !== false) {
                $text = preg_replace('/^(\s*NOMOR\s*[:\.]?)\s*[…\.\/0-9\s]*/ui', '$1 ' . $nomorSurat, $text);
            }
        }

        $runStyle = self::getRunStyle($r, $xpath);

        if ($runStyle) {
            $output .= "<span style=\"{$runStyle}\">"
                . htmlspecialchars($text)
                . "</span>";
        } else {
            $output .= htmlspecialchars($text);
        }

        return $output;
    }

    /**
     * Build CSS style string from run properties (w:rPr).
     */
    private static function getRunStyle(\DOMNode $r, \DOMXPath $xpath): string
    {
        $rPr = $xpath->query('w:rPr', $r)->item(0);
        if (!$rPr) {
            return '';
        }

        $styles = [];

        // Bold
        $bNode = $xpath->query('w:b', $rPr)->item(0);
        if ($bNode && $bNode->getAttribute('w:val') !== 'false' && $bNode->getAttribute('w:val') !== '0') {
            $styles[] = 'font-weight:bold';
        }

        // Italic
        $iNode = $xpath->query('w:i', $rPr)->item(0);
        if ($iNode && $iNode->getAttribute('w:val') !== 'false' && $iNode->getAttribute('w:val') !== '0') {
            $styles[] = 'font-style:italic';
        }

        // Underline
        $uNode = $xpath->query('w:u', $rPr)->item(0);
        if ($uNode) {
            $uVal = $uNode->getAttribute('w:val');
            if ($uVal && $uVal !== 'none') {
                $styles[] = 'text-decoration:underline';
            }
        }

        // Strikethrough
        $strikeNode = $xpath->query('w:strike', $rPr)->item(0);
        if ($strikeNode) {
            $styles[] = 'text-decoration:line-through';
        }

        // Font family
        $fontsNode = $xpath->query('w:rFonts', $rPr)->item(0);
        if ($fontsNode) {
            $fontName = $fontsNode->getAttribute('w:ascii')
                ?: $fontsNode->getAttribute('w:hAnsi')
                ?: $fontsNode->getAttribute('w:cs');
            if ($fontName) {
                $styles[] = "font-family:'{$fontName}',serif";
            }
        }

        // Font size: w:sz is in half-points
        $szNode = $xpath->query('w:sz', $rPr)->item(0);
        if ($szNode) {
            $szVal = (int)$szNode->getAttribute('w:val');
            if ($szVal > 0) {
                $ptSize = $szVal / 2;
                $styles[] = "font-size:{$ptSize}pt";
            }
        }

        // Color
        $colorNode = $xpath->query('w:color', $rPr)->item(0);
        if ($colorNode) {
            $colorVal = $colorNode->getAttribute('w:val');
            if ($colorVal && $colorVal !== 'auto') {
                $styles[] = "color:#{$colorVal}";
            }
        }

        // Highlight
        $highlightNode = $xpath->query('w:highlight', $rPr)->item(0);
        if ($highlightNode) {
            $hlColor = $highlightNode->getAttribute('w:val');
            if ($hlColor && $hlColor !== 'none') {
                $styles[] = "background-color:{$hlColor}";
            }
        }

        // Superscript/Subscript
        $vertAlign = $xpath->query('w:vertAlign', $rPr)->item(0);
        if ($vertAlign) {
            $va = $vertAlign->getAttribute('w:val');
            if ($va === 'superscript') $styles[] = 'vertical-align:super;font-size:75%';
            if ($va === 'subscript')   $styles[] = 'vertical-align:sub;font-size:75%';
        }

        return implode(';', $styles);
    }

    /**
     * Render tabel docx (w:tbl) menjadi HTML table.
     */
    private static function renderDocxTable(
        \DOMNode $tbl, \DOMXPath $xpath, array $images, string $nomorSurat = ''
    ): string {
        $html = '<table style="width:100%;border-collapse:collapse;margin:4px 0;">';

        foreach ($tbl->childNodes as $row) {
            if ($row->localName !== 'tr') continue;
            $html .= '<tr>';
            foreach ($row->childNodes as $cell) {
                if ($cell->localName !== 'tc') continue;

                // Cell properties: colspan, width, borders, vertical alignment
                $cellStyle = 'vertical-align:top;padding:2px 4px;';
                $colspan   = 1;
                $tcPr = $xpath->query('w:tcPr', $cell)->item(0);
                if ($tcPr) {
                    $gridSpan = $xpath->query('w:gridSpan', $tcPr)->item(0);
                    if ($gridSpan) $colspan = (int)$gridSpan->getAttribute('w:val');
                    $tcW = $xpath->query('w:tcW', $tcPr)->item(0);
                    if ($tcW && $tcW->getAttribute('w:type') === 'dxa') {
                        $widthPt = round((int)$tcW->getAttribute('w:w') / 20);
                        $cellStyle .= "width:{$widthPt}pt;";
                    }
                    // Borders
                    $borders = $xpath->query('w:tcBorders', $tcPr)->item(0);
                    if ($borders) {
                        foreach (['top','bottom','left','right'] as $side) {
                            $borderNode = $xpath->query("w:{$side}", $borders)->item(0);
                            if ($borderNode) {
                                $bVal = $borderNode->getAttribute('w:val');
                                $bSz  = (int)$borderNode->getAttribute('w:sz');
                                if ($bVal === 'nil' || $bVal === 'none') {
                                    $cellStyle .= "border-{$side}:none;";
                                } elseif ($bSz > 0) {
                                    $bPt = max(1, round($bSz / 8));
                                    $cellStyle .= "border-{$side}:{$bPt}px solid #000;";
                                }
                            }
                        }
                    }
                    // Vertical align
                    $vAlign = $xpath->query('w:vAlign', $tcPr)->item(0);
                    if ($vAlign) {
                        $va = $vAlign->getAttribute('w:val');
                        if ($va === 'center') $cellStyle = str_replace('vertical-align:top', 'vertical-align:middle', $cellStyle);
                        if ($va === 'bottom') $cellStyle = str_replace('vertical-align:top', 'vertical-align:bottom', $cellStyle);
                    }
                }

                $colspanAttr = $colspan > 1 ? " colspan=\"{$colspan}\"" : '';
                $html .= "<td{$colspanAttr} style=\"{$cellStyle}\">";
                foreach ($cell->childNodes as $cellChild) {
                    if ($cellChild->localName === 'p') {
                        $html .= self::renderDocxParagraph($cellChild, $xpath, $images, $nomorSurat);
                    } elseif ($cellChild->localName === 'tbl') {
                        $html .= self::renderDocxTable($cellChild, $xpath, $images, $nomorSurat);
                    }
                }
                $html .= '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</table>';
        return $html;
    }
}
