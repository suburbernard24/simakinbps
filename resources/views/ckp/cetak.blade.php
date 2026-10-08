<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CKP-R - {{ $user->name }} - {{ $daftarBulan[$bulan] }} {{ $tahun }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
        }

        body {
            background-color: #f1f5f9;
            color: #0f172a;
            font-size: 11pt;
            line-height: 1.4;
            padding: 24px;
        }

        .no-print-toolbar {
            max-width: 1100px;
            margin: 0 auto 20px auto;
            background: #ffffff;
            padding: 14px 20px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
        }

        .btn-primary {
            background: #059669;
            color: #ffffff;
        }
        .btn-primary:hover {
            background: #047857;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #334155;
        }
        .btn-secondary:hover {
            background: #cbd5e1;
        }

        .sheet {
            max-width: 1100px;
            margin: 0 auto;
            background: #ffffff;
            padding: 36px 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .header-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .header-title h1 {
            font-size: 15pt;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #0f172a;
            text-transform: uppercase;
        }

        .header-title h2 {
            font-size: 13pt;
            font-weight: 700;
            color: #1e293b;
            margin-top: 2px;
        }

        .header-title p {
            font-size: 11pt;
            font-weight: 600;
            color: #475569;
            margin-top: 4px;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            font-size: 10.5pt;
        }

        .meta-table td {
            padding: 3px 6px;
            vertical-align: top;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            font-size: 10pt;
        }

        .table-data th, .table-data td {
            border: 1px solid #334155;
            padding: 6px 8px;
        }

        .table-data th {
            background-color: #f8fafc;
            font-weight: 700;
            text-align: center;
            vertical-align: middle;
        }

        .table-data tr.section-header td {
            background-color: #f1f5f9;
            font-weight: 800;
            text-transform: uppercase;
        }

        .table-data td.text-center { text-align: center; }
        .table-data td.text-right { text-align: right; }
        .table-data td.font-bold { font-weight: 700; }

        .signature-container {
            width: 100%;
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
            font-size: 10.5pt;
        }

        .signature-box {
            width: 42%;
            text-align: center;
        }

        .signature-space {
            height: 70px;
        }

        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .no-print-toolbar {
                display: none !important;
            }
            .sheet {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
                border-radius: 0;
            }
            @page {
                size: A4 landscape;
                margin: 1.2cm;
            }
        }
    </style>
</head>
<body>

    <!-- TOOLBAR (Hidden when printed) -->
    <div class="no-print-toolbar">
        <div>
            <span style="font-weight: 800; font-size: 14px; color: #065f46;">
                <i class="fa-solid fa-file-contract mr-2"></i> Pratinjau Form CKP-R (Realisasi)
            </span>
            <span style="font-size: 12px; color: #64748b; margin-left: 10px;">
                Standar Badan Pusat Statistik
            </span>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('ckp.index', ['tahun' => $tahun, 'bulan' => $bulan]) }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- PRINT SHEET -->
    <div class="sheet">
        <!-- HEADER -->
        <div class="header-title">
            <h1>BADAN PUSAT STATISTIK KABUPATEN MANGGARAI</h1>
            <h2>CAPAIAN KINERJA PEGAWAI (CKP-R)</h2>
            <p>Periode: Bulan {{ $daftarBulan[$bulan] }} {{ $tahun }}</p>
        </div>

        <!-- METADATA PEGAWAI & PENILAI -->
        <table class="meta-table">
            <tr>
                <td style="width: 20%; font-weight: 700;">Pegawai Yang Dinilai</td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%; font-weight: 700;">{{ $user->name }}</td>
                <td style="width: 18%; font-weight: 700;">Pejabat Penilai</td>
                <td style="width: 2%;">:</td>
                <td style="width: 20%; font-weight: 700;">{{ $kepala->name }}</td>
            </tr>
            <tr>
                <td style="font-weight: 600;">NIP</td>
                <td>:</td>
                <td>{{ $user->nip ?? '-' }}</td>
                <td style="font-weight: 600;">NIP</td>
                <td>:</td>
                <td>{{ $kepala->nip ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Jabatan</td>
                <td>:</td>
                <td>{{ $user->jabatan ?? '-' }}</td>
                <td style="font-weight: 600;">Jabatan</td>
                <td>:</td>
                <td>{{ $kepala->jabatan ?? 'Kepala BPS Kabupaten Manggarai' }}</td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Golongan / Ruang</td>
                <td>:</td>
                <td>{{ $user->golongan ?? '-' }}</td>
                <td style="font-weight: 600;">Unit Kerja</td>
                <td>:</td>
                <td>BPS Kabupaten Manggarai (5310)</td>
            </tr>
        </table>

        <!-- TABEL CAPAIAN KINERJA -->
        <table class="table-data">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 32px;">No</th>
                    <th rowspan="2">Uraian Kegiatan</th>
                    <th rowspan="2" style="width: 75px;">Satuan</th>
                    <th colspan="3">Kuantitas</th>
                    <th rowspan="2" style="width: 65px;">Tingkat Kualitas (%)</th>
                    <th rowspan="2" style="width: 65px;">Angka Kredit</th>
                    <th rowspan="2" style="width: 65px;">Nilai CKP</th>
                    <th rowspan="2" style="width: 140px;">Keterangan</th>
                </tr>
                <tr>
                    <th style="width: 55px;">Target</th>
                    <th style="width: 55px;">Realisasi</th>
                    <th style="width: 55px;">%</th>
                </tr>
                <tr style="font-size: 8pt; background: #fafafa; text-align: center;">
                    <td>(1)</td>
                    <td>(2)</td>
                    <td>(3)</td>
                    <td>(4)</td>
                    <td>(5)</td>
                    <td>(6)</td>
                    <td>(7)</td>
                    <td>(8)</td>
                    <td>(9)</td>
                    <td>(10)</td>
                </tr>
            </thead>
            <tbody>
                <!-- BAGIAN UTAMA -->
                <tr class="section-header">
                    <td colspan="10"><strong>UTAMA</strong></td>
                </tr>
                @forelse($ckp as $i => $item)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $item->uraian_kegiatan }}</td>
                        <td class="text-center">{{ $item->satuan }}</td>
                        <td class="text-right">{{ number_format($item->target_kuantitas, 0) }}</td>
                        <td class="text-right font-bold">{{ number_format($item->realisasi_kuantitas, 0) }}</td>
                        <td class="text-right">{{ number_format($item->persentase_kuantitas, 1) }}</td>
                        <td class="text-right">{{ $item->kualitas > 0 ? number_format($item->kualitas, 1) : '-' }}</td>
                        <td class="text-right">{{ $item->angka_kredit ? number_format($item->angka_kredit, 3) : '-' }}</td>
                        <td class="text-right font-bold">{{ $item->nilai_ckp > 0 ? number_format($item->nilai_ckp, 1) : '-' }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center" style="font-style: italic; color: #64748b;">Tidak ada kegiatan utama.</td>
                    </tr>
                @endforelse

                <!-- BAGIAN TAMBAHAN -->
                <tr class="section-header">
                    <td colspan="10"><strong>TAMBAHAN</strong></td>
                </tr>
                @forelse($ckp_tambahan as $i => $item)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $item->uraian_kegiatan }}</td>
                        <td class="text-center">{{ $item->satuan }}</td>
                        <td class="text-right">{{ number_format($item->target_kuantitas, 0) }}</td>
                        <td class="text-right font-bold">{{ number_format($item->realisasi_kuantitas, 0) }}</td>
                        <td class="text-right">{{ number_format($item->persentase_kuantitas, 1) }}</td>
                        <td class="text-right">{{ $item->kualitas > 0 ? number_format($item->kualitas, 1) : '-' }}</td>
                        <td class="text-right">{{ $item->angka_kredit ? number_format($item->angka_kredit, 3) : '-' }}</td>
                        <td class="text-right font-bold">{{ $item->nilai_ckp > 0 ? number_format($item->nilai_ckp, 1) : '-' }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center" style="font-style: italic; color: #64748b;">Tidak ada kegiatan tambahan.</td>
                    </tr>
                @endforelse

                <!-- RATA-RATA CAPAIAN KINERJA -->
                <tr style="background-color: #f8fafc; font-weight: 700;">
                    <td colspan="3" class="text-center">RATA-RATA CAPAIAN KINERJA</td>
                    <td class="text-right">{{ number_format($totalTarget, 0) }}</td>
                    <td class="text-right font-bold">{{ number_format($totalRealisasi, 0) }}</td>
                    <td class="text-right">{{ number_format($avgPersentaseKuantitas, 1) }}</td>
                    <td class="text-right">{{ $avgKualitas > 0 ? number_format($avgKualitas, 1) : '-' }}</td>
                    <td class="text-right">-</td>
                    <td class="text-right font-bold" style="color: #065f46;">{{ $avgNilaiCkp > 0 ? number_format($avgNilaiCkp, 1) : '-' }}</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>

        <!-- TANDA TANGAN -->
        <div class="signature-container">
            <div class="signature-box">
                <p>Pegawai Yang Dinilai,</p>
                <div class="signature-space"></div>
                <p style="font-weight: 700; text-decoration: underline;">{{ $user->name }}</p>
                <p>NIP. {{ $user->nip ?? '-' }}</p>
            </div>

            <div class="signature-box">
                <p>Ruteng, {{ date('t', strtotime("$tahun-$bulan-01")) }} {{ $daftarBulan[$bulan] }} {{ $tahun }}</p>
                <p>Pejabat Penilai,</p>
                <div class="signature-space"></div>
                <p style="font-weight: 700; text-decoration: underline;">{{ $kepala->name }}</p>
                <p>NIP. {{ $kepala->nip ?? '-' }}</p>
            </div>
        </div>
    </div>

</body>
</html>
