<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $surat->jenisSurat->nama }} - {{ !empty($surat->nomor_surat) ? $surat->nomor_surat : ($surat->nomorSuratRecord->nomor_lengkap ?? 'Resmi') }}</title>
    
    <!-- System fonts only — no external CDN for fast print loading -->

    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 20mm 20mm 20mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
            font-size: 12pt;
            line-height: 1.5;
        }

        .paper {
            background: #fff;
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm 20mm;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            box-sizing: border-box;
            position: relative;
        }

        /* Kop Surat BPS */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-logo {
            width: 80px;
            text-align: center;
            vertical-align: middle;
        }

        .kop-logo img {
            width: 75px;
            height: auto;
        }

        .kop-text {
            text-align: center;
            vertical-align: middle;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .kop-text h3 {
            margin: 2px 0 0 0;
            font-size: 13pt;
            font-weight: 800;
            text-transform: uppercase;
        }

        .kop-text p {
            margin: 4px 0 0 0;
            font-size: 9pt;
            font-weight: 400;
            color: #333;
        }

        /* Title */
        .doc-title {
            text-align: center;
            margin: 20px 0;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .doc-title h1 {
            margin: 0;
            font-size: 13pt;
            font-weight: 800;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .doc-title p {
            margin: 3px 0 0 0;
            font-size: 11pt;
            font-weight: 600;
        }

        /* Content */
        .content {
            margin-top: 20px;
            text-align: justify;
        }

        .content p {
            margin-bottom: 12px;
        }

        /* Docx uploaded content — let inline styles from parseDocxToHtml take full control */
        .docx-content {
            margin-top: 0;
            text-align: left;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        /* Reset paragraph spacing override for docx — honour margin from inline style */
        div.docx-content p {
            margin: 0 !important;
        }

        /* Tables inside docx */
        .docx-content table {
            width: 100%;
            border-collapse: collapse;
        }

        .docx-content td, .docx-content th {
            vertical-align: top;
        }

        /* Embedded images inside docx */
        .docx-content img {
            max-width: 100%;
            height: auto;
        }

        /* Table Pegawai */
        .pegawai-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10.5pt;
        }

        .pegawai-table th, .pegawai-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }

        .pegawai-table th {
            background-color: #f8fafc;
            text-align: center;
            font-weight: bold;
        }

        /* Signature */
        .signature-block {
            margin-top: 35px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 250px;
            text-align: center;
        }

        .qr-tte {
            margin: 10px auto;
            width: 70px;
            height: 70px;
            border: 1px solid #ccc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 8pt;
            color: #1565C0;
            font-family: sans-serif;
        }

        /* Print Controls floating bar */
        .print-bar {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(15, 23, 42, 0.9);
            color: #fff;
            padding: 10px 20px;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            z-index: 1000;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .print-btn {
            background: #1565C0;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 9999px;
            font-weight: bold;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .print-btn:hover {
            background: #0D47A1;
        }

        .close-btn {
            background: transparent;
            color: #cbd5e1;
            border: none;
            font-size: 13px;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #fff;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .paper {
                box-shadow: none;
                padding: 0;
                margin: 0;
                width: 100%;
                min-height: auto;
            }
            .print-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Print Toolbar -->
    <div class="print-bar">
        <span style="font-size: 13px; font-weight: 600;">Naskah Dinas Resmi BPS</span>
        <button class="print-btn" onclick="window.print()">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg> Cetak / Simpan PDF
        </button>
        <button class="close-btn" onclick="window.close()">Tutup</button>
    </div>

    <div class="paper">
        @if($surat->isDibuatDiLuar())
            <!-- Naskah Asli Dokumen yang Diunggah (Identik dengan Dokumen Asli) -->
            <div class="content docx-content">
                {!! $renderedContent !!}
            </div>
        @else
            <!-- Kop Surat Resmi BPS -->
            <table class="kop-table">
                <tr>
                    <td class="kop-logo">
                        <!-- BPS Logo -->
                        <img src="{{ asset('images/logo_bps.png') }}" alt="Logo BPS">
                    </td>
                    <td class="kop-text">
                        <h2>BADAN PUSAT STATISTIK</h2>
                        <h3>KABUPATEN MANGGARAI</h3>
                        <p>Jalan Motang Rua No. 01, Ruteng 86511, Telepon (0385) 21154</p>
                        <p>Pos-el: bps5313@bps.go.id, Laman: manggaraikab.bps.go.id</p>
                    </td>
                </tr>
            </table>

            <!-- Judul Dokumen -->
            <div class="doc-title">
                <h1>{{ $surat->jenisSurat->nama }}</h1>
                <p>NOMOR: {{ !empty($surat->nomor_surat) ? $surat->nomor_surat : ($surat->nomorSuratRecord->nomor_lengkap ?? 'B-...../53131/' . ($surat->kode_klasifikasi ?? 'VS.330') . '/' . date('Y')) }}</p>
            </div>

            <!-- Metadata Ringkas Surat Keluar jika ada tujuan -->
            @if($surat->penerimas->isNotEmpty())
            <div style="margin-bottom: 20px; font-size: 11pt;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 80px; vertical-align: top;">Hal</td>
                        <td style="width: 15px; vertical-align: top;">:</td>
                        <td style="vertical-align: top; font-weight: bold;">{{ $surat->perihal }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">Lampiran</td>
                        <td style="vertical-align: top;">:</td>
                        <td style="vertical-align: top;">{{ $surat->lampirans->count() > 0 ? $surat->lampirans->count() . ' berkas' : '-' }}</td>
                    </tr>
                </table>

                <div style="margin-top: 15px;">
                    <p style="margin: 0;">Yth.</p>
                    @foreach($surat->penerimas as $penerima)
                    <p style="margin: 2px 0 0 0; font-weight: bold;">{{ $penerima->nama_penerima }}</p>
                    @if($penerima->instansi)<p style="margin: 0;">{{ $penerima->instansi }}</p>@endif
                    @if($penerima->alamat)<p style="margin: 0;">{{ $penerima->alamat }}</p>@endif
                    @endforeach
                    <p style="margin: 4px 0 0 0;">Di Tempat</p>
                </div>
            </div>
            @endif

            <!-- Body / Content -->
            <div class="content">
                {!! nl2br(e($renderedContent)) !!}
            </div>

            <!-- Tabel Pegawai yang Ditugaskan (PRD 9: Multiple Pegawai) -->
            @if($surat->pegawais->isNotEmpty())
            <div style="margin-top: 15px;">
                <p style="margin-bottom: 6px; font-weight: bold;">Daftar Pegawai yang Ditugaskan:</p>
                <table class="pegawai-table">
                    <thead>
                        <tr>
                            <th style="width: 30px;">No</th>
                            <th>Nama / NIP</th>
                            <th>Jabatan / Golongan</th>
                            <th>Peran dalam Tim</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($surat->pegawais as $idx => $sp)
                        <tr>
                            <td style="text-align: center;">{{ $idx + 1 }}</td>
                            <td>
                                <strong>{{ $sp->pegawai->name ?? '-' }}</strong><br>
                                <span style="font-size: 9.5pt; color: #444;">NIP: {{ $sp->pegawai->nip ?? '-' }}</span>
                            </td>
                            <td>
                                {{ $sp->pegawai->jabatan ?? '-' }}
                                @if($sp->pegawai->golongan) ({{ $sp->pegawai->golongan }}) @endif
                            </td>
                            <td style="font-weight: bold;">{{ $sp->peran }}</td>
                            <td>{{ $sp->keterangan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Keterangan Tambahan -->
            @if($surat->detail?->keterangan)
            <p style="margin-top: 15px; font-size: 11pt;">
                Catatan: {{ $surat->detail->keterangan }}
            </p>
            @endif

            <!-- Penandatangan Resmi BPS -->
            <div class="signature-block">
                <div class="signature-box">
                    <p style="margin: 0;">Ruteng, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</p>
                    <p style="margin: 2px 0 0 0; font-weight: bold;">
                        Kepala Badan Pusat Statistik<br>Kabupaten Manggarai,
                    </p>

                    @if($surat->isSelesai())
                    <div class="qr-tte">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="#1565C0"><path d="M3 11h2V3H3v8zm0 8h2v-6H3v6zM5 3h6v2H5V3zm8 0v2h6V3h-6zm6 8h2V3h-2v8zm0 2h-2v6h2v-2h2v-2h-2v-2zm-4 4h-2v2h2v2h2v-2h-2v-2zm-6 2v2h2v-2h2v-2H9v2H7zm0-4H5v2h2v2h2v-2h2v-2H9v-2H7v2zm8-4h2v-2h-2v2zm-4 0v-2H9v2H7v2h4v-2h2z"/></svg>
                        <span style="font-size: 6pt; margin-top: 2px; font-weight: bold; color: #1565C0;">TTE VALID</span>
                    </div>
                    @else
                    <div style="height: 65px;"></div>
                    @endif

                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                        {{ $kepala?->name ?? 'Kepala BPS Kab. Manggarai' }}
                    </p>
                    <p style="margin: 2px 0 0 0; font-size: 10pt;">
                        NIP: {{ $kepala?->nip ?? '19xxxxxxxxxxxxxx' }}
                    </p>
                </div>
            </div>
        @endif
    </div>

</body>
</html>
