@extends('layouts.app')

@section('title', 'Pengaturan Penomoran Surat - e-Surat BPS')
@section('page_title', 'Pengaturan & Log Penomoran Otomatis')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-list-ol text-bps-blue"></i>
                <span>Log & Register Penomoran Surat (PRD 10.10 & 16)</span>
            </h2>
            <p class="text-xs text-slate-500">Pencatatan nomor urut dinas resmi BPS per tahun dan klasifikasi surat</p>
        </div>
    </div>

    <!-- Info Card Pola Penomoran BPS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-5 rounded-2xl bg-bps-faint border border-bps-pale">
            <span class="text-[11px] font-bold uppercase text-bps-blue tracking-wider">Satker Aktif</span>
            <p class="text-xl font-black text-slate-900 mt-1">53131</p>
            <p class="text-xs text-slate-600 mt-0.5">BPS Kabupaten Manggarai</p>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-200">
            <span class="text-[11px] font-bold uppercase text-slate-400 tracking-wider">Tahun Berjalan</span>
            <p class="text-xl font-black text-slate-900 mt-1">{{ date('Y') }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Penomoran tereset otomatis tiap 1 Januari</p>
        </div>

        <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-100">
            <span class="text-[11px] font-bold uppercase text-emerald-700 tracking-wider">Total Nomor Terbit</span>
            <p class="text-xl font-black text-emerald-800 mt-1">{{ $nomorRecords->total() }}</p>
            <p class="text-xs text-emerald-600 mt-0.5">Nomor resmi terkunci unik</p>
        </div>
    </div>

    <!-- Table of Issued Numbers -->
    <div class="glass-panel rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-sm">
        <div class="p-4 border-b border-slate-200/80 flex items-center justify-between">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                Buku Register Nomor Surat Resmi BPS
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold text-[11px]">
                        <th class="p-3.5 w-12 text-center">No Urut</th>
                        <th class="p-3.5">Nomor Lengkap Naskah</th>
                        <th class="p-3.5">Jenis Surat</th>
                        <th class="p-3.5">Klasifikasi</th>
                        <th class="p-3.5">Perihal Naskah Dinas</th>
                        <th class="p-3.5">Tanggal Terbit</th>
                        <th class="p-3.5 text-center">Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($nomorRecords as $nr)
                    <tr class="hover:bg-slate-50/80">
                        <td class="p-3.5 text-center font-mono font-bold text-slate-500">{{ $nr->nomor_urut }}</td>
                        <td class="p-3.5 font-mono font-black text-slate-900">{{ $nr->nomor_lengkap }}</td>
                        <td class="p-3.5 font-semibold text-slate-800">{{ $nr->jenisSurat->nama }}</td>
                        <td class="p-3.5 font-mono text-slate-600">{{ $nr->kode_klasifikasi }}</td>
                        <td class="p-3.5 max-w-xs truncate font-medium text-slate-700">
                            {{ $nr->surat->perihal ?? '-' }}
                        </td>
                        <td class="p-3.5 text-slate-500 whitespace-nowrap">{{ $nr->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-3.5 text-center">
                            @if($nr->surat_id)
                            <a href="{{ route('surat.show', $nr->surat_id) }}" class="p-1.5 rounded-lg text-bps-blue hover:bg-bps-faint" title="Lihat">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">Belum ada nomor naskah dinas yang diterbitkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($nomorRecords->hasPages())
        <div class="p-4 border-t border-slate-100">{{ $nomorRecords->links() }}</div>
        @endif
    </div>

</div>
@endsection
