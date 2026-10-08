@extends('layouts.app')

@section('title', 'Detail Surat - ' . $surat->perihal)
@section('page_title', 'Detail & Pratinjau Naskah Dinas')

@section('content')
<div class="space-y-6" x-data="{}">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('surat.index') }}" class="text-xs font-semibold text-bps-blue hover:underline inline-flex items-center gap-1.5 mb-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Surat
            </a>
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <span>{{ $surat->jenisSurat->nama }}</span>
                    @if($surat->nomor_surat)
                        <span class="text-xs font-mono font-bold px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ $surat->nomor_surat }}
                        </span>
                    @endif
                </h2>
                @if($surat->isDibuatDiLuar())
                    <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200 inline-flex items-center gap-1.5 shadow-2xs">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Naskah Luar Sistem (Diunggah)
                    </span>
                @else
                    <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-lg bg-blue-50 text-bps-blue border border-blue-200 inline-flex items-center gap-1.5 shadow-2xs">
                        <i class="fa-solid fa-file-signature"></i> Dibuat di Sistem
                    </span>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-2">
            <!-- Unduh berkas jika luar -->
            @if($surat->isDibuatDiLuar() && $surat->file_surat)
            <a href="{{ route('surat.dokumen.download', $surat->id) }}"
               class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition inline-flex items-center gap-2">
                <i class="fa-solid fa-download"></i>
                <span>Unduh Naskah Dokumen</span>
            </a>
            @endif

            <!-- Cetak PDF -->
            @if($surat->isSelesai() || $surat->nomor_surat)
            <a href="{{ route('surat.cetak', $surat->id) }}" target="_blank"
               class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition inline-flex items-center gap-2">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Naskah Resmi (PDF)</span>
            </a>
            @endif

            <!-- Terbitkan jika draft -->
            @if($surat->isDraft() && ($surat->user_id === Auth::id() || Auth::user()->isAdmin()))
            <form method="POST" action="{{ route('surat.submit', $surat->id) }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-bps-blue hover:bg-bps-dark text-white font-bold text-xs shadow-md shadow-bps-blue/20 transition inline-flex items-center gap-2">
                    <i class="fa-solid fa-stamp"></i>
                    <span>Terbitkan Nomor Surat</span>
                </button>
            </form>
            @endif

            <!-- Edit jika draft -->
            @if(($surat->canEdit() && $surat->user_id === Auth::id()) || Auth::user()->isAdmin())
            <a href="{{ route('surat.edit', $surat->id) }}" 
               class="px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold text-xs shadow-sm transition inline-flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Edit Surat</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Alert Status & Catatan Revisi -->
    @if($surat->isDikembalikan() && $surat->catatan)
    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-3 shadow-sm">
        <div class="w-8 h-8 rounded-xl bg-amber-500 text-slate-900 flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="flex-1">
            <p class="text-xs font-bold uppercase tracking-wider text-amber-800">Surat Dikembalikan untuk Perbaikan</p>
            <p class="text-xs text-amber-900 mt-1 font-medium bg-white/70 p-3 rounded-xl border border-amber-200">
                "{{ $surat->catatan }}"
            </p>
            <div class="mt-2 flex items-center gap-2">
                <a href="{{ route('surat.edit', $surat->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 text-slate-900 font-bold text-xs rounded-lg shadow-sm">
                    <i class="fa-solid fa-pen"></i> Perbaiki Sekarang
                </a>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Preview Dokumen & Rincian Penugasan -->
        <div class="lg:col-span-2 space-y-6">

            <!-- PREVIEW BERKAS JIKA DIUPLOAD DARI LUAR -->
            @if($surat->isDibuatDiLuar())
            <div class="glass-panel rounded-2xl border border-indigo-200 bg-white overflow-hidden shadow-sm space-y-4 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-indigo-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl flex-shrink-0">
                            @php
                                $ext = strtolower(pathinfo($surat->file_surat, PATHINFO_EXTENSION));
                            @endphp
                            @if($ext === 'pdf')
                                <i class="fa-solid fa-file-pdf text-red-500 text-2xl"></i>
                            @elseif(in_array($ext, ['xlsx', 'xls', 'csv']))
                                <i class="fa-solid fa-file-excel text-emerald-600 text-2xl"></i>
                            @else
                                <i class="fa-solid fa-file-word text-blue-600 text-2xl"></i>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-sm font-bold text-slate-800">{{ $surat->nama_file_asli ?: 'Berkas Dokumen Naskah Surat' }}</h3>
                                @if($surat->file_surat)
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-indigo-100 text-indigo-700">
                                    {{ strtoupper(pathinfo($surat->file_surat, PATHINFO_EXTENSION)) }}
                                </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">Naskah dinas resmi dibuat di luar sistem dan diunggah ke SIMANJA BPS.</p>
                        </div>
                    </div>
                    @if($surat->file_surat)
                    <div class="flex items-center gap-2">
                        <a href="{{ route('surat.dokumen.download', $surat->id) }}"
                           class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition inline-flex items-center gap-2">
                            <i class="fa-solid fa-download"></i> Unduh File
                        </a>
                        @if(strtolower(pathinfo($surat->file_surat, PATHINFO_EXTENSION)) === 'pdf')
                        <a href="{{ asset('storage/' . $surat->file_surat) }}" target="_blank"
                           class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Tab Baru
                        </a>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Preview Iframe Viewer jika PDF -->
                @if($surat->file_surat && strtolower(pathinfo($surat->file_surat, PATHINFO_EXTENSION)) === 'pdf')
                <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-100">
                    <iframe src="{{ asset('storage/' . $surat->file_surat) }}" class="w-full h-[650px] rounded-xl" frameborder="0"></iframe>
                </div>
                @else
                <div class="rounded-xl border border-slate-200 bg-white p-6 sm:p-10 shadow-inner text-slate-900 max-h-[700px] overflow-y-auto text-xs sm:text-sm leading-relaxed" style="font-family: 'Bookman Old Style', 'Times New Roman', serif;">
                    {!! $renderedContent !!}
                </div>
                @endif
            </div>
            @endif

            <!-- Official Document Preview Box (PRD 10.11 & 15) -->
            @if($surat->isDibuatDiSistem())
            <div class="glass-panel rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-sm">
                <!-- Preview Header -->
                <div class="p-4 bg-slate-50/80 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                        <i class="fa-regular fa-file-pdf text-rose-500 text-sm"></i>
                        <span>Pratinjau Dokumen Naskah Dinas BPS</span>
                    </div>
                    @if($surat->isSelesai() || $surat->nomor_surat)
                    <a href="{{ route('surat.cetak', $surat->id) }}" target="_blank" class="text-xs font-bold text-bps-blue hover:underline inline-flex items-center gap-1">
                        <i class="fa-solid fa-up-right-from-square"></i> Buka Tampilan Cetak
                    </a>
                    @endif
                </div>

                <!-- Document Sheet Simulation -->
                <div class="p-6 md:p-10 bg-white space-y-6 font-serif text-slate-900 border-x sm:border-x-0">
                    <!-- Kop Surat Resmi -->
                    <div class="text-center border-b-2 border-slate-900 pb-4 space-y-1">
                        <h4 class="font-sans font-extrabold text-sm tracking-wider uppercase text-slate-900">
                            BADAN PUSAT STATISTIK KABUPATEN MANGGARAI
                        </h4>
                        <p class="font-sans text-[11px] text-slate-600">
                            Jl. Motang Rua No. 01, Ruteng 86511 | Telp: (0385) 21154 | Email: bps5313@bps.go.id
                        </p>
                    </div>

                    <!-- Judul Naskah / Jenis Surat -->
                    <div class="text-center space-y-1 pt-2">
                        <h3 class="font-sans font-black text-base uppercase underline tracking-wide">
                            {{ $surat->jenisSurat->nama }}
                        </h3>
                        <p class="font-sans text-xs font-semibold text-slate-700">
                            NOMOR: {{ !empty($surat->nomor_surat) ? $surat->nomor_surat : ($surat->nomorSuratRecord->nomor_lengkap ?? '[ NOMOR OTOMATIS ]') }}
                        </p>
                    </div>

                    <!-- Hal / Perihal -->
                    <div class="font-sans text-xs space-y-1 text-slate-800 bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <div class="grid grid-cols-4 gap-2">
                            <span class="font-bold text-slate-500">Perihal :</span>
                            <span class="col-span-3 font-semibold text-slate-900">{{ $surat->perihal }}</span>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <span class="font-bold text-slate-500">Tanggal :</span>
                            <span class="col-span-3">{{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</span>
                        </div>
                        @if($surat->detail?->tempat_kegiatan)
                        <div class="grid grid-cols-4 gap-2">
                            <span class="font-bold text-slate-500">Tempat :</span>
                            <span class="col-span-3">{{ $surat->detail->tempat_kegiatan }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Body / Rendered Content -->
                    <div class="font-sans text-xs leading-relaxed text-slate-800 whitespace-pre-line space-y-3">
                        {!! nl2br(e($renderedContent)) !!}
                    </div>

                    <!-- Multiple Pegawai Table (Jika Surat Tugas / SK - PRD 9) -->
                    @if($surat->pegawais->isNotEmpty())
                    <div class="font-sans pt-2">
                        <p class="text-xs font-bold text-slate-800 mb-2">Daftar Pegawai yang Ditugaskan:</p>
                        <div class="overflow-x-auto border border-slate-200 rounded-xl">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold text-[11px]">
                                        <th class="p-2.5 w-10 text-center">No</th>
                                        <th class="p-2.5">Nama & NIP</th>
                                        <th class="p-2.5">Jabatan / Golongan</th>
                                        <th class="p-2.5">Peran dalam Tugas</th>
                                        <th class="p-2.5">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($surat->pegawais as $idx => $sp)
                                    <tr>
                                        <td class="p-2.5 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                        <td class="p-2.5">
                                            <p class="font-bold text-slate-900">{{ $sp->pegawai->name ?? '-' }}</p>
                                            <p class="text-[10px] text-slate-500 font-mono">NIP: {{ $sp->pegawai->nip ?? '-' }}</p>
                                        </td>
                                        <td class="p-2.5 text-slate-700">
                                            {{ $sp->pegawai->jabatan ?? '-' }}
                                            @if($sp->pegawai->golongan) ({{ $sp->pegawai->golongan }}) @endif
                                        </td>
                                        <td class="p-2.5">
                                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-bold text-[10px]">
                                                {{ $sp->peran }}
                                            </span>
                                        </td>
                                        <td class="p-2.5 text-slate-500 text-[11px]">
                                            {{ $sp->keterangan ?? '-' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Penerima (Jika Surat Keluar) -->
                    @if($surat->penerimas->isNotEmpty())
                    <div class="font-sans pt-2">
                        <p class="text-xs font-bold text-slate-800 mb-1">Tujuan / Penerima:</p>
                        @foreach($surat->penerimas as $pen)
                        <div class="p-3 rounded-xl bg-slate-50 text-xs space-y-0.5 border border-slate-100">
                            <p class="font-bold text-slate-900">{{ $pen->nama_penerima }}</p>
                            @if($pen->instansi)<p class="text-slate-600">{{ $pen->instansi }}</p>@endif
                            @if($pen->alamat)<p class="text-slate-500 text-[11px]">{{ $pen->alamat }}</p>@endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Signature Simulation -->
                    <div class="font-sans pt-6 flex justify-end text-xs">
                        <div class="text-center w-72 space-y-1">
                            <p>Ruteng, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</p>
                            <p class="font-bold">Kepala Badan Pusat Statistik<br>Kabupaten Manggarai,</p>
                            
                            @if($surat->isSelesai())
                            <div class="py-2 flex flex-col items-center justify-center">
                                <div class="p-2 border border-slate-200 rounded-lg inline-block bg-slate-50">
                                    <i class="fa-solid fa-qrcode text-3xl text-slate-800"></i>
                                </div>
                                <p class="text-[9px] text-emerald-700 font-bold mt-1">Ditandatangani secara Elektronik (TTE)</p>
                            </div>
                            @else
                            <div class="h-16"></div>
                            @endif

                            <p class="font-bold underline text-slate-900">
                                {{ \App\Models\User::getKepala()?->name ?? 'Kepala BPS Kab. Manggarai' }}
                            </p>
                            <p class="text-[10px] text-slate-500 font-mono">
                                NIP: {{ \App\Models\User::getKepala()?->nip ?? '19xxxxxxxxxxxxxx' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Lampiran Berkas List (PRD 10.12) -->
            <div class="glass-panel p-5 rounded-2xl border border-slate-200/80 bg-white space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <h3 class="text-xs font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-paperclip text-bps-blue"></i>
                        <span>Lampiran Berkas ({{ $surat->lampirans->count() }})</span>
                    </h3>
                </div>

                @if($surat->lampirans->isEmpty())
                    <p class="text-xs text-slate-400 italic">Tidak ada berkas lampiran yang diunggah.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($surat->lampirans as $lampiran)
                        <div class="p-3 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2.5 min-w-0">
                                @php
                                    $lExt = strtolower(pathinfo($lampiran->nama_file, PATHINFO_EXTENSION));
                                @endphp
                                <div class="w-8 h-8 rounded-lg {{ in_array($lExt, ['xlsx', 'xls', 'csv']) ? 'bg-emerald-50 text-emerald-600' : ($lExt === 'pdf' ? 'bg-rose-50 text-rose-600' : 'bg-bps-faint text-bps-blue') }} flex items-center justify-center font-bold text-xs shrink-0">
                                    @if(in_array($lExt, ['xlsx', 'xls', 'csv']))
                                        <i class="fa-solid fa-file-excel"></i>
                                    @elseif($lExt === 'pdf')
                                        <i class="fa-solid fa-file-pdf"></i>
                                    @elseif(in_array($lExt, ['doc', 'docx']))
                                        <i class="fa-solid fa-file-word"></i>
                                    @else
                                        <i class="fa-regular fa-file"></i>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 truncate" title="{{ $lampiran->nama_file }}">{{ $lampiran->nama_file }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $lampiran->ukuran }} KB &bull; Oleh {{ $lampiran->uploader->name ?? '-' }}</p>
                                </div>
                            </div>
                            <a href="{{ route('surat.lampiran.download', $lampiran->id) }}" 
                               class="p-2 rounded-lg bg-white border border-slate-200 text-bps-blue hover:bg-bps-faint transition shrink-0 font-bold" title="Unduh">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- Right 1 Col: Metadata & Audit Status Timeline -->
        <div class="space-y-6">

            <!-- Status Card -->
            <div class="glass-panel p-5 rounded-2xl border border-slate-200/80 bg-white space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Status Saat Ini</span>
                    @php $badge = $surat->status_badge; @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badge['bg'] }}">
                        {{ $badge['label'] }}
                    </span>
                </div>

                <div class="space-y-2.5 pt-2 border-t border-slate-100 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Pemohon:</span>
                        <span class="font-bold text-slate-800">{{ $surat->pemohon->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Fungsi / Unit:</span>
                        <span class="font-semibold text-slate-700">{{ $surat->fungsi ?? ($surat->unitKerja->nama ?? '-') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Tanggal Pengajuan:</span>
                        <span class="text-slate-700">{{ \Carbon\Carbon::parse($surat->tanggal_pengajuan)->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Kode Klasifikasi:</span>
                        <span class="font-mono font-bold text-slate-700">{{ $surat->kode_klasifikasi }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Kode Satker:</span>
                        <span class="font-mono text-slate-700">{{ $surat->kode_satker }}</span>
                    </div>
                </div>
            </div>

            <!-- Audit Timeline / Riwayat Status (PRD 10.13) -->
            <div class="glass-panel p-5 rounded-2xl border border-slate-200/80 bg-white space-y-4">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-timeline text-bps-blue"></i>
                    <span>Riwayat Alur Dokumen</span>
                </h3>

                <div class="relative pl-6 space-y-4 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    @forelse($surat->statusHistories as $hist)
                    <div class="relative text-xs">
                        <div class="absolute -left-6 top-1 w-3.5 h-3.5 rounded-full bg-bps-blue ring-4 ring-white"></div>
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-800 capitalize">{{ $hist->status }}</span>
                            <span class="text-[10px] text-slate-400">{{ $hist->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-0.5">Oleh: {{ $hist->user->name ?? 'Sistem' }}</p>
                        @if($hist->catatan)
                        <p class="text-[11px] text-slate-600 bg-slate-50 p-2 rounded-lg mt-1 border border-slate-100">
                            {{ $hist->catatan }}
                        </p>
                        @endif
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 italic">Belum ada riwayat proses tercatat.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>


</div>
@endsection
