@extends('layouts.app')

@section('title', 'Detail Informasi Mitra - ' . $mitra->nama)
@section('page_title', 'Detail Informasi Mitra')

@section('content')
<div class="space-y-6" 
     x-data="{
        modalPenilaianOpen: false,
        hoverBintang: 0,
        penilaianTarget: {
            alokasi_id: '',
            nama_mitra: '{{ addslashes($mitra->nama) }}',
            kegiatan: '',
            posisi: '',
            bintang: 0,
            komentar: ''
        },
        bukaModalNilai(alokasiId, kegiatan, posisi, existingBintang, existingKomentar) {
            this.penilaianTarget.alokasi_id = alokasiId;
            this.penilaianTarget.kegiatan = kegiatan;
            this.penilaianTarget.posisi = posisi;
            this.penilaianTarget.bintang = existingBintang || 0;
            this.penilaianTarget.komentar = existingKomentar || '';
            this.modalPenilaianOpen = true;
        }
     }">

    {{-- Breadcrumb Navigation & Top Action --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <a href="{{ route('kinerja-mitra.mitra.index') }}" 
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:border-bps-blue transition shadow-2xs">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Katalog Mitra</span>
        </a>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl text-xs font-bold {{ $mitra->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border border-slate-200' }}">
                <i class="fa-solid fa-circle text-[8px] mr-1.5 {{ $mitra->is_active ? 'text-emerald-500' : 'text-slate-400' }}"></i>
                {{ $mitra->is_active ? 'Mitra Aktif' : 'Nonaktif' }}
            </span>
        </div>
    </div>

    {{-- Layout Utama: Profil Kiri, Tab Histori Kanan (Sobat BPS Style) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        {{-- ==================== KOLOM KIRI: KARTU PROFIL MITRA ==================== --}}
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden p-6 text-center space-y-4">
                
                {{-- Foto Profil --}}
                <div class="relative inline-block mx-auto">
                    <img src="{{ $mitra->foto_url }}" 
                         alt="{{ $mitra->nama }}" 
                         class="w-40 h-40 rounded-3xl object-cover ring-4 ring-blue-50 dark:ring-slate-800 shadow-lg mx-auto">
                    <span class="absolute -bottom-1.5 -right-1.5 w-7 h-7 rounded-full {{ $mitra->is_active ? 'bg-emerald-500' : 'bg-slate-400' }} border-2 border-white dark:border-slate-900 flex items-center justify-center text-white text-xs" title="{{ $mitra->is_active ? 'Aktif' : 'Nonaktif' }}">
                        <i class="fa-solid {{ $mitra->is_active ? 'fa-check' : 'fa-minus' }}"></i>
                    </span>
                </div>

                <div>
                    <h2 class="text-lg font-black text-slate-900 dark:text-white">{{ $mitra->nama }}</h2>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 dark:bg-slate-800 text-bps-blue dark:text-blue-400 font-mono text-xs font-bold mt-1.5 border border-blue-100 dark:border-slate-700">
                        <i class="fa-solid fa-id-badge text-[10px]"></i>
                        <span>{{ $mitra->sobat_id }}</span>
                    </div>
                </div>

                {{-- Rating Bintang Rata-rata --}}
                <div class="p-3.5 rounded-2xl bg-amber-50/70 dark:bg-amber-950/30 border border-amber-200/70 dark:border-amber-900/50 flex items-center justify-between">
                    <div class="text-left">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-700 dark:text-amber-400 block">Rating Kinerja</span>
                        <div class="flex items-center gap-1 mt-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star text-xs {{ $i <= round($mitra->rata_rata_bintang) ? 'text-amber-400' : 'text-slate-300 dark:text-slate-700' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-base font-black text-slate-900 dark:text-white">{{ number_format($mitra->rata_rata_bintang, 1) }}</span>
                        <span class="text-[10px] text-slate-400 block">dari {{ $mitra->total_ulasan }} ulasan</span>
                    </div>
                </div>

                {{-- Info Detail Profil --}}
                <div class="text-left space-y-3 pt-3 border-t border-slate-100 dark:border-slate-800 text-xs">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider block">Posisi / Kategori</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $mitra->posisi_default ?? 'Petugas Pencacah Lapangan (PCL)' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider block">Wilayah Tugas</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $mitra->kabupaten }}, {{ $mitra->provinsi }}</span>
                    </div>
                    @if($mitra->nik)
                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider block">NIK</span>
                        <span class="font-mono text-slate-700 dark:text-slate-300">{{ $mitra->nik }}</span>
                    </div>
                    @endif
                    @if($mitra->telepon)
                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider block">No. Kontak / WhatsApp</span>
                        <a href="https://wa.me/{{ preg_replace('/[^\d]/', '', $mitra->telepon) }}" target="_blank" class="text-bps-blue hover:underline font-semibold flex items-center gap-1">
                            <i class="fa-brands fa-whatsapp text-emerald-500"></i>
                            <span>{{ $mitra->telepon }}</span>
                        </a>
                    </div>
                    @endif
                    @if($mitra->email)
                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider block">Email</span>
                        <span class="text-slate-700 dark:text-slate-300">{{ $mitra->email }}</span>
                    </div>
                    @endif
                    @if($mitra->alamat)
                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider block">Alamat Domisili</span>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ $mitra->alamat }}</p>
                    </div>
                    @endif
                </div>

                {{-- Status Batas Kuota Honor Bulan Berjalan --}}
                <div class="p-4 rounded-2xl bg-blue-50/50 dark:bg-slate-800/80 border border-blue-100 dark:border-slate-700 text-left space-y-2 text-xs">
                    <div class="flex items-center justify-between text-[11px] font-bold">
                        <span class="text-slate-600 dark:text-slate-300">Honor Bulan {{ \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->translatedFormat('F Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Total Terjadwal (Dibayar):</span>
                        <span class="font-extrabold text-bps-blue dark:text-blue-400">Rp {{ number_format($totalHonorBulanIni, 0, ',', '.') }}</span>
                    </div>
                    @php
                        $tidakDibayarBulanIni = $mitra->totalHonorTidakDibayarBulan($currentYear, $currentMonth);
                    @endphp
                    @if($tidakDibayarBulanIni > 0)
                    <div class="flex items-center justify-between text-xs text-amber-600 dark:text-amber-400 font-bold">
                        <span>Melebihi Batas (Tidak Dibayar):</span>
                        <span>Rp {{ number_format($tidakDibayarBulanIni, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Sisa Kuota:</span>
                        <span class="font-extrabold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($sisaKuotaBulanIni, 0, ',', '.') }}</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- ==================== KOLOM KANAN: TABEL HISTORI SURVEI / KEGIATAN ==================== --}}
        <div class="lg:col-span-9 space-y-6">
            
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
                
                {{-- Header Card & Filter Bar --}}
                <div class="p-6 border-b border-slate-100 dark:border-slate-800 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-bps-faint dark:bg-slate-800 text-bps-blue dark:text-blue-400 flex items-center justify-center font-bold text-sm">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Histori Survei &amp; Kegiatan</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Daftar penugasan survei statistik yang diikuti oleh mitra</p>
                            </div>
                        </div>

                        <span class="px-3 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs self-start sm:self-auto">
                            Total: {{ $historiKegiatan->count() }} Kegiatan
                        </span>
                    </div>

                    {{-- Filter Pencarian --}}
                    <form method="GET" action="{{ route('kinerja-mitra.mitra.show', $mitra->id) }}" class="flex flex-wrap items-center gap-3">
                        <div class="relative flex-1 min-w-[200px]">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" 
                                   name="search_kegiatan" 
                                   value="{{ request('search_kegiatan') }}" 
                                   placeholder="Cari nama survei / rincian..." 
                                   class="w-full pl-8 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-bps-blue outline-none">
                        </div>

                        <button type="submit" class="px-4 py-2 bg-bps-blue hover:bg-bps-dark text-white rounded-xl text-xs font-bold transition">
                            Filter
                        </button>

                        @if(request('search_kegiatan'))
                        <a href="{{ route('kinerja-mitra.mitra.show', $mitra->id) }}" class="text-xs text-slate-500 hover:text-rose-500">
                            Reset
                        </a>
                        @endif
                    </form>
                </div>

                {{-- TABEL HISTORI SURVEI (Dengan Kolom Baru Penilaian/Komentar) --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-100 dark:border-slate-800 text-[10px] uppercase font-black text-slate-400 tracking-wider">
                            <tr>
                                <th class="px-4 py-3.5 text-center w-10">No</th>
                                <th class="px-4 py-3.5 min-w-[180px]">Survei</th>
                                <th class="px-4 py-3.5 min-w-[180px]">Kegiatan</th>
                                <th class="px-4 py-3.5">Posisi</th>
                                <th class="px-4 py-3.5">Provinsi</th>
                                <th class="px-4 py-3.5">Kabupaten/Kota</th>
                                {{-- KOLOM BARU SESUAI PERSYARATAN 2.1 --}}
                                <th class="px-4 py-3.5 min-w-[240px] bg-amber-50/40 dark:bg-amber-950/20 text-amber-800 dark:text-amber-300 border-l border-amber-100 dark:border-amber-900/50">
                                    Penilaian / Komentar
                                </th>
                                @if(Auth::user()->canRateMitra())
                                <th class="px-4 py-3.5 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($historiKegiatan as $idx => $keg)
                            @php
                                $penilaianUser = $keg->penilaianOleh(Auth::id());
                                $penilaianTerbaru = $keg->penilaianTerbaru;
                            @endphp
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-4 py-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                

                                {{-- Kolom Survei --}}
                                <td class="px-4 py-4">
                                    <p class="font-bold text-slate-800 dark:text-white text-xs">{{ $keg->nama_kegiatan }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $keg->tim?->nama_tim ?? 'BPS Kabupaten Manggarai' }}</p>
                                </td>

                                {{-- Kolom Kegiatan --}}
                                <td class="px-4 py-4">
                                    <p class="text-xs text-slate-700 dark:text-slate-300 font-medium">{{ $keg->kegiatan }}</p>
                                    <span class="text-[10px] font-bold text-bps-blue dark:text-blue-400 mt-0.5 block">
                                        Target: {{ $keg->target_kuantitas }} {{ $keg->satuan }}
                                    </span>
                                </td>

                                {{-- Kolom Posisi --}}
                                <td class="px-4 py-4">
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-[11px] whitespace-nowrap">
                                        {{ $keg->posisi }}
                                    </span>
                                </td>

                                {{-- Kolom Provinsi --}}
                                <td class="px-4 py-4 text-slate-600 dark:text-slate-400 whitespace-nowrap">
                                    {{ $keg->provinsi }}
                                </td>

                                {{-- Kolom Kabupaten/Kota --}}
                                <td class="px-4 py-4 text-slate-600 dark:text-slate-400 whitespace-nowrap">
                                    {{ $keg->kabupaten }}
                                </td>

                                {{-- ==================== KOLOM BARU: PENILAIAN / KOMENTAR ==================== --}}
                                <td class="px-4 py-4 bg-amber-50/20 dark:bg-amber-950/10 border-l border-amber-100 dark:border-amber-900/40">
                                    @if($keg->penilaians->isNotEmpty())
                                        <div class="space-y-2">
                                            @foreach($keg->penilaians as $p)
                                            <div class="p-2.5 rounded-xl bg-white dark:bg-slate-800/90 border border-amber-200/60 dark:border-amber-800/40 shadow-2xs space-y-1">
                                                <div class="flex items-center justify-between gap-2">
                                                    {{-- Bintang --}}
                                                    <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                                                        @for($s = 1; $s <= 5; $s++)
                                                            <i class="fa-solid fa-star {{ $s <= $p->bintang ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700' }}"></i>
                                                        @endfor
                                                        <span class="ml-1 text-[11px] font-black text-slate-800 dark:text-slate-200">({{ $p->bintang }}/5)</span>
                                                    </div>
                                                    <span class="text-[9px] text-slate-400">{{ $p->created_at?->diffForHumans() }}</span>
                                                </div>

                                                {{-- Komentar --}}
                                                <p class="text-[11px] text-slate-700 dark:text-slate-300 italic leading-relaxed">
                                                    &ldquo;{{ $p->komentar }}&rdquo;
                                                </p>

                                                {{-- Penilai & Waktu --}}
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
                                            <span>Belum dinilai</span>
                                        </div>
                                    @endif
                                </td>

                                {{-- Aksi Beri Penilaian (Hanya Ketua Tim & Anggota Tim) --}}
                                @if(Auth::user()->canRateMitra())
                                <td class="px-4 py-4 text-center">
                                    <button type="button"
                                            @click="bukaModalNilai({{ $keg->id }}, '{{ addslashes($keg->nama_kegiatan) }}', '{{ addslashes($keg->posisi) }}', {{ $penilaianUser ? $penilaianUser->bintang : 0 }}, '{{ $penilaianUser ? addslashes($penilaianUser->komentar) : '' }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl {{ $penilaianUser ? 'bg-amber-500 text-white hover:bg-amber-600 shadow-sm shadow-amber-500/20' : 'bg-amber-50 hover:bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:hover:bg-amber-900/50 dark:text-amber-300' }} text-xs font-bold transition duration-200"
                                            title="{{ $penilaianUser ? 'Ubah Penilaian Anda' : 'Beri Penilaian Kinerja' }}">
                                        <i class="fa-solid fa-star text-xs"></i>
                                        <span>{{ $penilaianUser ? 'Ubah Nilai' : 'Beri Nilai' }}</span>
                                    </button>
                                </td>
                                @endif

                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ Auth::user()->canRateMitra() ? '8' : '7' }}" class="px-4 py-12 text-center text-slate-400 dark:text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xl text-slate-400 mb-3">
                                            <i class="fa-regular fa-folder-open"></i>
                                        </div>
                                        <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">Belum Ada Histori Survei / Kegiatan</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Mitra ini belum dialokasikan ke survei statistik atau filter tidak cocok.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

    {{-- MODAL DETAIL PENILAIAN KINERJA (2.2) --}}
    @include('mitra.modal_penilaian')

</div>
@endsection
