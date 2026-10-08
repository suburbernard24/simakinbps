<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_CKP_{{ $pegawai->username }}_{{ $namaBulan }}_{{ $tahun }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { font-size: 11pt; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
            @page { size: A4 portrait; margin: 15mm 15mm 15mm 15mm; }
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 p-4 sm:p-8">

    <!-- Floating Print Controls (No Print) -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('ckp.laporan', ['tahun' => $tahun, 'bulan' => $bulan, 'pegawai_id' => $pegawai->id]) }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold shadow-xs hover:bg-slate-50 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Sistem
        </a>
        <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold shadow-md transition flex items-center gap-2">
            <i class="fa-solid fa-print"></i>
            <span>Cetak Dokumen / Simpan PDF</span>
        </button>
    </div>

    <!-- Official Document Sheet -->
    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-sm border border-slate-200 print:shadow-none print:border-none print:p-0">

        <!-- KOP RESMI BPS -->
        <div class="border-b-2 border-slate-900 pb-4 mb-6 flex items-center gap-5">
            <img src="{{ asset('images/simakin.png') }}" alt="Logo BPS" class="h-16 w-auto object-contain">
            <div class="flex-1 text-center">
                <h1 class="text-base font-extrabold uppercase tracking-wider text-slate-900">BADAN PUSAT STATISTIK KABUPATEN MANGGARAI</h1>
                <h2 class="text-sm font-bold text-slate-700 uppercase mt-0.5">LAPORAN CAPAIAN KINERJA PEGAWAI (CKP) BULANAN</h2>
                <p class="text-xs text-slate-500 mt-1">Jl. Pelita No. 8, Ruteng, Flores, NTT &bull; Periode: <strong>{{ $namaBulan }} {{ $tahun }}</strong></p>
            </div>
        </div>

        <!-- Identitas Pegawai Table -->
        <div class="grid grid-cols-2 gap-4 mb-6 text-xs">
            <div class="space-y-1">
                <div class="flex"><span class="w-36 text-slate-500 font-semibold">Nama Pegawai</span><span class="font-bold text-slate-900">: {{ $pegawai->name }}</span></div>
                <div class="flex"><span class="w-36 text-slate-500 font-semibold">NIP</span><span class="font-mono text-slate-800">: {{ $pegawai->nip ?? '-' }}</span></div>
                <div class="flex"><span class="w-36 text-slate-500 font-semibold">Jabatan</span><span class="text-slate-800">: {{ $pegawai->jabatan ?? '-' }}</span></div>
                <div class="flex"><span class="w-36 text-slate-500 font-semibold">Pangkat / Golongan</span><span class="text-slate-800">: {{ $pegawai->golongan ?? '-' }}</span></div>
            </div>
            <div class="space-y-1 text-right sm:text-left sm:pl-8">
                <div class="flex"><span class="w-36 text-slate-500 font-semibold">Tim Kerja</span><span class="font-bold text-slate-900">: {{ $pegawai->tim ? $pegawai->tim->nama_tim : 'Lintas Tim' }}</span></div>
                <div class="flex"><span class="w-36 text-slate-500 font-semibold">Hari Kerja Aktif</span><span class="text-slate-800">: {{ $kinerja['hari_kerja_aktif'] }} Hari</span></div>
                <div class="flex"><span class="w-36 text-slate-500 font-semibold">Basis Jam Kerja</span><span class="text-slate-800">: {{ $kinerja['jam_kerja_aktif'] }} Jam (7.5 Jam/hari)</span></div>
                <div class="flex"><span class="w-36 text-slate-500 font-semibold">Skor Kinerja Pegawai</span><span class="font-bold text-emerald-700">: {{ $kinerja['skor_kinerja'] }}%</span></div>
            </div>
        </div>

        <!-- Tabel Rincian Pekerjaan -->
        <table class="w-full text-left text-xs border border-slate-300 mb-6">
            <thead class="bg-slate-100 text-[10px] font-extrabold uppercase text-slate-700 tracking-wider">
                <tr class="border-b border-slate-300">
                    <th class="p-2.5 text-center border-r border-slate-300 w-10">No</th>
                    <th class="p-2.5 border-r border-slate-300">Uraian Kegiatan</th>
                    <th class="p-2.5 text-center border-r border-slate-300">Jenis</th>
                    <th class="p-2.5 text-center border-r border-slate-300">Satuan</th>
                    <th class="p-2.5 text-center border-r border-slate-300">Target</th>
                    <th class="p-2.5 text-center border-r border-slate-300">Realisasi</th>
                    <th class="p-2.5 text-center border-r border-slate-300">% Kuantitas (a)</th>
                    <th class="p-2.5 text-center border-r border-slate-300">% Kualitas (b)</th>
                    <th class="p-2.5 text-center">Estimasi Jam</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($kinerja['items'] as $idx => $task)
                    <tr>
                        <td class="p-2 text-center border-r border-slate-200">{{ $idx + 1 }}</td>
                        <td class="p-2 border-r border-slate-200 font-semibold">{{ $task->nama_kegiatan }}</td>
                        <td class="p-2 text-center border-r border-slate-200 uppercase text-[10px]">{{ $task->jenis_pekerjaan }}</td>
                        <td class="p-2 text-center border-r border-slate-200">{{ $task->satuan }}</td>
                        <td class="p-2 text-center border-r border-slate-200">{{ $task->target_kuantitas }}</td>
                        <td class="p-2 text-center border-r border-slate-200 font-bold text-emerald-700">{{ $task->realisasi_kuantitas }}</td>
                        <td class="p-2 text-center border-r border-slate-200 font-bold">{{ $task->persentase_kuantitas }}%</td>
                        <td class="p-2 text-center border-r border-slate-200 font-bold text-amber-700">{{ $task->kualitas > 0 ? $task->kualitas . '%' : '-' }}</td>
                        <td class="p-2 text-center">{{ $task->estimasi_waktu }}</td>
                    </tr>
                @endforeach

                <!-- Summary Breakdown Rows -->
                <tr class="bg-slate-50 font-bold border-t-2 border-slate-400">
                    <td colspan="6" class="p-2 text-right border-r border-slate-300">Rata-rata Persentase Kuantitas (c) = AVERAGE(a) :</td>
                    <td class="p-2 text-center border-r border-slate-300 text-blue-700">{{ $kinerja['avg_kuantitas'] }}%</td>
                    <td colspan="2"></td>
                </tr>
                <tr class="bg-slate-50 font-bold">
                    <td colspan="7" class="p-2 text-right border-r border-slate-300">Rata-rata Persentase Kualitas (d) = AVERAGE(b) :</td>
                    <td class="p-2 text-center border-r border-slate-300 text-indigo-700">{{ $kinerja['avg_kualitas'] }}%</td>
                    <td></td>
                </tr>
                <tr class="bg-amber-50/70 font-extrabold text-sm border-t border-b border-amber-300">
                    <td colspan="7" class="p-2.5 text-right border-r border-amber-300 text-amber-950">NILAI CKP BULANAN = (c + d) / 2 :</td>
                    <td class="p-2.5 text-center border-r border-amber-300 text-amber-700">{{ $kinerja['nilai_ckp'] }}</td>
                    <td></td>
                </tr>
                <tr class="bg-slate-50 font-bold">
                    <td colspan="7" class="p-2 text-right border-r border-slate-300">Total Estimasi Waktu Kerja Pegawai :</td>
                    <td colspan="2" class="p-2 text-center text-slate-800">{{ $kinerja['total_estimasi_jam'] }} Jam</td>
                </tr>
                <tr class="bg-emerald-50/70 font-extrabold text-sm border-t border-b border-emerald-300">
                    <td colspan="7" class="p-2.5 text-right border-r border-emerald-300 text-emerald-950">SKOR KINERJA = (Total Jam / Jam Aktif) × 100% :</td>
                    <td colspan="2" class="p-2.5 text-center text-emerald-800">{{ $kinerja['skor_kinerja'] }}%</td>
                </tr>
            </tbody>
        </table>

        <!-- Tanda Tangan Resmi -->
        <div class="grid grid-cols-2 gap-8 text-xs text-center mt-12 pt-4">
            <div class="space-y-16">
                <div>
                    <p class="text-slate-500">Pegawai Yang Dinilai,</p>
                </div>
                <div>
                    <p class="font-extrabold text-slate-900 underline">{{ $pegawai->name }}</p>
                    <p class="text-slate-600 font-mono">NIP. {{ $pegawai->nip ?? '-' }}</p>
                </div>
            </div>

            <div class="space-y-16">
                <div>
                    <p class="text-slate-500">Ruteng, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p class="text-slate-500">Pejabat Penilai Kinerja,</p>
                </div>
                <div>
                    <p class="font-extrabold text-slate-900 underline">{{ $kepala->name }}</p>
                    <p class="text-slate-600 font-mono">NIP. {{ $kepala->nip ?? '-' }}</p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
