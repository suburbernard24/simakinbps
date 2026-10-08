<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan CKP Bulanan</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #000000;
            padding: 6px 8px;
        }
        th {
            background-color: #D9E1F2;
            font-weight: bold;
            text-align: center;
        }
        .header-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .bg-summary {
            background-color: #FFF2CC;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <table>
        <tr>
            <td colspan="9" class="header-title" style="border: none;">BADAN PUSAT STATISTIK KABUPATEN MANGGARAI</td>
        </tr>
        <tr>
            <td colspan="9" class="header-title" style="border: none;">LAPORAN CAPAIAN KINERJA PEGAWAI (CKP) BULANAN</td>
        </tr>
        <tr>
            <td colspan="9" class="text-center" style="border: none; font-style: italic;">Periode: {{ $namaBulan }} {{ $tahun }}</td>
        </tr>
        <tr><td colspan="9" style="border: none;"></td></tr>

        <!-- Identitas Pegawai -->
        <tr>
            <td colspan="2" style="border: none; font-weight: bold;">Nama Pegawai</td>
            <td colspan="7" style="border: none;">: {{ $pegawai->name }}</td>
        </tr>
        <tr>
            <td colspan="2" style="border: none; font-weight: bold;">NIP</td>
            <td colspan="7" style="border: none;">: {{ $pegawai->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="2" style="border: none; font-weight: bold;">Jabatan / Golongan</td>
            <td colspan="7" style="border: none;">: {{ $pegawai->jabatan ?? '-' }} / {{ $pegawai->golongan ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="2" style="border: none; font-weight: bold;">Tim Kerja</td>
            <td colspan="7" style="border: none;">: {{ $pegawai->tim ? $pegawai->tim->nama_tim : 'Lintas Tim' }}</td>
        </tr>
        <tr>
            <td colspan="2" style="border: none; font-weight: bold;">Hari Kerja Aktif Bulanan</td>
            <td colspan="7" style="border: none;">: {{ $kinerja['hari_kerja_aktif'] }} Hari ({{ $kinerja['jam_kerja_aktif'] }} Jam)</td>
        </tr>
        <tr><td colspan="9" style="border: none;"></td></tr>

        <!-- Table Header -->
        <thead>
            <tr>
                <th>No</th>
                <th>Uraian Kegiatan</th>
                <th>Jenis Pekerjaan</th>
                <th>Satuan</th>
                <th>Target</th>
                <th>Realisasi</th>
                <th>% Kuantitas (a)</th>
                <th>% Kualitas (b)</th>
                <th>Estimasi Jam</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kinerja['items'] as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $item->nama_kegiatan }}</td>
                    <td class="text-center">{{ ucfirst($item->jenis_pekerjaan) }}</td>
                    <td class="text-center">{{ $item->satuan }}</td>
                    <td class="text-center">{{ $item->target_kuantitas }}</td>
                    <td class="text-center">{{ $item->realisasi_kuantitas }}</td>
                    <td class="text-center">{{ $item->persentase_kuantitas }}%</td>
                    <td class="text-center">{{ $item->kualitas > 0 ? $item->kualitas . '%' : '-' }}</td>
                    <td class="text-center">{{ $item->estimasi_waktu }}</td>
                </tr>
            @endforeach

            <!-- Rangkuman / Formula CKP Bulanan Sesuai Bagian N -->
            <tr class="bg-summary">
                <td colspan="6" class="text-right">Rata-rata Persentase Kuantitas (c) :</td>
                <td class="text-center">{{ $kinerja['avg_kuantitas'] }}%</td>
                <td colspan="2"></td>
            </tr>
            <tr class="bg-summary">
                <td colspan="7" class="text-right">Rata-rata Persentase Kualitas (d) :</td>
                <td class="text-center">{{ $kinerja['avg_kualitas'] }}%</td>
                <td></td>
            </tr>
            <tr class="bg-summary">
                <td colspan="7" class="text-right">Nilai CKP Bulanan = (c + d) / 2 :</td>
                <td class="text-center" style="font-size: 13px; color: #1565C0;">{{ $kinerja['nilai_ckp'] }}</td>
                <td></td>
            </tr>
            <tr class="bg-summary">
                <td colspan="7" class="text-right">Total Estimasi Jam Kerja :</td>
                <td colspan="2" class="text-center">{{ $kinerja['total_estimasi_jam'] }} Jam</td>
            </tr>
            <tr class="bg-summary">
                <td colspan="7" class="text-right">Skor Kinerja Pegawai = (Total Jam / Jam Aktif) × 100% :</td>
                <td colspan="2" class="text-center" style="font-size: 13px; color: #2E7D32;">{{ $kinerja['skor_kinerja'] }}%</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
