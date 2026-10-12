@extends('layouts.app')

@section('title', 'Penilaian Kinerja Mitra')
@section('page_title', 'Penilaian Kinerja Mitra')

@section('content')
<div class="space-y-6" 
     x-data="{
        modalPenilaianOpen: false,
        hoverBintang: 0,
        penilaianTarget: {
            alokasi_id: '',
            nama_mitra: '',
            kegiatan: '',
            posisi: '',
            bintang: 0,
            komentar: ''
        },
        bukaModalNilai(alokasiId, namaMitra, kegiatan, posisi, existingBintang, existingKomentar) {
            this.penilaianTarget.alokasi_id = alokasiId;
            this.penilaianTarget.nama_mitra = namaMitra;
            this.penilaianTarget.kegiatan = kegiatan;
            this.penilaianTarget.posisi = posisi;
            this.penilaianTarget.bintang = existingBintang || 0;
            this.penilaianTarget.komentar = existingKomentar || '';
            this.modalPenilaianOpen = true;
        }
     }">

    {{-- Banner Header --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 text-xs font-bold uppercase tracking-wider mb-2 border border-amber-200 dark:border-amber-900/60">
                <i class="fa-solid fa-star text-amber-500"></i>
                <span>Evaluasi Kinerja Sobat BPS</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                Penilaian Kinerja Mitra Statistik
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-2xl leading-relaxed">
                @if(Auth::user()->isKepala())
                    Anda login sebagai <strong class="text-bps-blue">Kepala BPS</strong> (Akses Monitoring / Viewer). Memantau daftar seluruh penugasan dan evaluasi kinerja mitra.
                @else
                    Berikan penilaian bintang (skala 1–5) dan catatan evaluasi (minimal 20 karakter) untuk setiap kegiatan yang diselesaikan mitra statistik.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3.5 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs">
                Periode: {{ \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->translatedFormat('F Y') }}
            </span>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="flex flex-wrap items-center justify-between gap-4 bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-800">
        
        <form method="GET" action="{{ route('kinerja-mitra.penilaian.index') }}" class="flex flex-wrap items-center gap-2.5">
            <div>
                <select name="bulan" onchange="this.form.submit()" 
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    @foreach($daftarBulan as $num => $nama)
                        <option value="{{ $num }}" {{ $currentMonth == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="tahun" onchange="this.form.submit()" 
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    @foreach($daftarTahun as $th)
                        <option value="{{ $th }}" {{ $currentYear == $th ? 'selected' : '' }}>{{ $th }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="status_penilaian" onchange="this.form.submit()" 
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    <option value="">Semua Status Penilaian</option>
                    <option value="belum" {{ request('status_penilaian') === 'belum' ? 'selected' : '' }}>Belum Dinilai</option>
                    <option value="sudah" {{ request('status_penilaian') === 'sudah' ? 'selected' : '' }}>Sudah Dinilai</option>
                </select>
            </div>
        </form>

        <form method="GET" action="{{ route('kinerja-mitra.penilaian.index') }}" class="relative min-w-[220px]">
            <input type="hidden" name="tahun" value="{{ $currentYear }}">
            <input type="hidden" name="bulan" value="{{ $currentMonth }}">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Cari survei / mitra..." 
                   class="w-full pl-8 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
        </form>
    </div>

    {{-- Tabel Kegiatan & Penilaian --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200/80 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-100 dark:border-slate-800 text-[10px] uppercase font-black text-slate-400 tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5 text-center w-12">No</th>
                        <th class="px-4 py-3.5 min-w-[200px]">Mitra Statistik</th>
                        <th class="px-4 py-3.5 min-w-[220px]">Survei &amp; Rincian Kegiatan</th>
                        <th class="px-4 py-3.5">Posisi</th>
                        <th class="px-4 py-3.5 min-w-[250px] bg-amber-50/40 dark:bg-amber-950/20 text-amber-800 dark:text-amber-300 border-l border-amber-100 dark:border-amber-900/50">
                            Penilaian &amp; Ulasan Kinerja
                        </th>
                        @if(Auth::user()->canRateMitra())
                        <th class="px-4 py-3.5 text-center w-28">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($alokasiList as $idx => $item)
                    @php
                        $penilaianSaya = $item->penilaianOleh(Auth::id());
                    @endphp
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="px-4 py-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                        
                        {{-- Mitra --}}
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ $item->mitra->foto_url ?? '' }}" alt="{{ $item->mitra->nama ?? '' }}" class="w-9 h-9 rounded-2xl object-cover ring-1 ring-slate-200 dark:ring-slate-700">
                                <div>
                                    <a href="{{ route('kinerja-mitra.mitra.show', $item->mitra_id) }}" class="font-extrabold text-slate-900 dark:text-white hover:text-bps-blue text-xs block line-clamp-1">
                                        {{ $item->mitra->nama ?? '-' }}
                                    </a>
                                    <span class="font-mono text-[10px] text-slate-400 block">{{ $item->mitra->sobat_id ?? '-' }}</span>
                                    <div class="flex items-center gap-1 text-[10px] text-amber-500 font-bold mt-0.5">
                                        <i class="fa-solid fa-star text-[9px]"></i>
                                        <span>Avg: {{ number_format($item->mitra->rata_rata_bintang ?? 0, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Survei & Kegiatan --}}
                        <td class="px-4 py-4">
                            <p class="font-bold text-slate-900 dark:text-white text-xs">{{ $item->nama_kegiatan }}</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $item->kegiatan }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-2 py-0.5 rounded-md bg-blue-50 dark:bg-slate-800 text-bps-blue dark:text-blue-400 text-[10px] font-bold">
                                    Target: {{ $item->target_kuantitas }} {{ $item->satuan }}
                                </span>
                                <span class="text-[10px] text-slate-400">
                                    {{ $item->tanggal_mulai?->format('d/m') }} - {{ $item->tanggal_selesai?->format('d/m/y') }}
                                </span>
                            </div>
                        </td>

                        {{-- Posisi --}}
                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-[11px] whitespace-nowrap">
                                {{ $item->posisi }}
                            </span>
                        </td>

                        {{-- Kolom Penilaian & Komentar --}}
                        <td class="px-4 py-4 bg-amber-50/20 dark:bg-amber-950/10 border-l border-amber-100 dark:border-amber-900/40">
                            @if($item->penilaians->isNotEmpty())
                                <div class="space-y-2">
                                    @foreach($item->penilaians as $p)
                                    <div class="p-2.5 rounded-xl bg-white dark:bg-slate-800/90 border border-amber-200/60 dark:border-amber-800/40 shadow-2xs space-y-1">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                                                @for($s = 1; $s <= 5; $s++)
                                                    <i class="fa-solid fa-star {{ $s <= $p->bintang ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700' }}"></i>
                                                @endfor
                                                <span class="ml-1 text-[11px] font-black text-slate-800 dark:text-slate-200">({{ $p->bintang }}/5)</span>
                                            </div>
                                            <span class="text-[9px] text-slate-400">{{ $p->created_at?->diffForHumans() }}</span>
                                        </div>

                                        <p class="text-[11px] text-slate-700 dark:text-slate-300 italic leading-relaxed">
                                            &ldquo;{{ $p->komentar }}&rdquo;
                                        </p>

                                        <div class="pt-1 flex items-center justify-between text-[10px] text-slate-400 dark:text-slate-500 border-t border-slate-100 dark:border-slate-700/60">
                                            <span>Penilai: <strong class="text-slate-600 dark:text-slate-300">{{ $p->penilai->name ?? 'Pegawai' }}</strong></span>
                                            <span>{{ $p->created_at?->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex items-center gap-1.5 text-slate-400 dark:text-slate-500 italic text-[11px]">
                                    <i class="fa-regular fa-clock text-xs"></i>
                                    <span>Belum ada penilaian</span>
                                </div>
                            @endif
                        </td>

                        {{-- Tombol Aksi Nilai (Khusus Ketua Tim & Anggota Tim) --}}
                        @if(Auth::user()->canRateMitra())
                        <td class="px-4 py-4 text-center">
                            <button type="button"
                                    @click="bukaModalNilai({{ $item->id }}, '{{ addslashes($item->mitra->nama ?? 'Mitra') }}', '{{ addslashes($item->nama_kegiatan) }}', '{{ addslashes($item->posisi) }}', {{ $penilaianSaya ? $penilaianSaya->bintang : 0 }}, '{{ $penilaianSaya ? addslashes($penilaianSaya->komentar) : '' }}')"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl {{ $penilaianSaya ? 'bg-amber-500 text-white hover:bg-amber-600 shadow-md shadow-amber-500/20' : 'bg-amber-50 hover:bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:hover:bg-amber-900/50 dark:text-amber-300' }} text-xs font-bold transition duration-200 cursor-pointer"
                                    title="{{ $penilaianSaya ? 'Perbarui Penilaian Anda' : 'Beri Penilaian Kinerja' }}">
                                <i class="fa-solid fa-star text-xs"></i>
                                <span>{{ $penilaianSaya ? 'Ubah Nilai' : 'Beri Nilai' }}</span>
                            </button>
                        </td>
                        @endif

                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->canRateMitra() ? '6' : '5' }}" class="px-4 py-12 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xl text-slate-400 mb-2">
                                    <i class="fa-regular fa-folder-open"></i>
                                </div>
                                <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">Tidak Ditemukan Penugasan Mitra</p>
                                <p class="text-xs text-slate-400 mt-0.5">Belum ada alokasi survei pada periode ini atau filter tidak cocok.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-850 flex items-center justify-between text-xs text-slate-500">
            <span>{{ $alokasiList->total() }} total kegiatan mitra</span>
            <div>
                {{ $alokasiList->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL PENILAIAN KINERJA (2.2) --}}
    @include('mitra.modal_penilaian')

</div>
@endsection
