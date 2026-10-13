@extends('layouts.app')

@section('title', 'Alokasi Pekerjaan Pegawai')
@section('page_title', 'Alokasi Pekerjaan Pegawai')

@section('content')
<div class="space-y-6" x-data="{
    modalDetail: false,
    selectedPegawai: null,
    formatTanggal(val) {
        if (!val) return '-';
        const clean = String(val).split('T')[0];
        const parts = clean.split('-');
        if (parts.length === 3) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const mIdx = parseInt(parts[1], 10) - 1;
            const mStr = months[mIdx] || parts[1];
            return `${parts[2]} ${mStr} ${parts[0]}`;
        }
        return clean;
    },
    formatRentang(mulai, selesai) {
        if (!mulai && !selesai) return '-';
        const fMulai = this.formatTanggal(mulai);
        const fSelesai = this.formatTanggal(selesai);
        if (!selesai || fMulai === fSelesai) return fMulai;
        return `${fMulai} s/d ${fSelesai}`;
    }
}">

    <!-- Header & Filter -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-lg font-black text-slate-900">Rekapitulasi Alokasi Pekerjaan per Pegawai</h2>
            <p class="text-xs text-slate-500 mt-0.5">Pemantauan beban estimasi waktu (jam), target realisasi kuantitas, dan capaian CKP bulanan tiap pegawai</p>
        </div>

        <!-- Filter Periode & Tim -->
        <form method="GET" action="{{ route('alokasi.pegawai') }}" class="flex flex-wrap items-center gap-2">
            <select name="bulan" class="text-xs font-bold px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white cursor-pointer">
                @foreach($daftarBulan as $num => $nama)
                    <option value="{{ $num }}" class="bg-white dark:bg-slate-800 text-slate-800 dark:text-white" {{ $currentMonth == $num ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>

            <select name="tahun" class="text-xs font-bold px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white cursor-pointer">
                @foreach($daftarTahun as $th)
                    <option value="{{ $th }}" class="bg-white dark:bg-slate-800 text-slate-800 dark:text-white" {{ $currentYear == $th ? 'selected' : '' }}>{{ $th }}</option>
                @endforeach
            </select>

            <select name="tim_id" class="text-xs font-bold px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white cursor-pointer">
                <option value="" class="bg-white dark:bg-slate-800 text-slate-800 dark:text-white">Semua Tim</option>
                @foreach($daftarTim as $tim)
                    <option value="{{ $tim->id }}" class="bg-white dark:bg-slate-800 text-slate-800 dark:text-white" {{ $timId == $tim->id ? 'selected' : '' }}>{{ $tim->nama_tim }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold transition shadow-sm cursor-pointer flex items-center gap-1.5">
                <i class="fa-solid fa-filter text-[11px]"></i>
                <span>Terapkan</span>
            </button>
        </form>
    </div>

    <!-- Kotak Penjelasan Formula Skor Kinerja & CKP -->
    <div class="p-5 rounded-3xl bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 border border-indigo-200/70 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-2xl bg-bps-blue text-white flex items-center justify-center flex-shrink-0 shadow-md">
                <i class="fa-solid fa-square-root-variable text-sm"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-extrabold text-slate-900 mb-1 flex items-center gap-2">
                    <span>Panduan Formula: Skor Kinerja & Nilai CKP</span>
                    <span class="px-2 py-0.5 rounded-full bg-bps-blue text-white text-[10px] font-bold">Formula</span>
                </h4>
                <p class="text-xs text-slate-600 mb-3 leading-relaxed">
                    Setiap metrik kinerja dihitung secara otomatis dari data alokasi dan realisasi. Berikut adalah rumus yang digunakan sistem.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="p-3 rounded-2xl bg-white/80 border border-indigo-100 shadow-xs">
                        <p class="text-[10px] font-extrabold uppercase text-indigo-600 tracking-wider mb-1.5">📊 Skor Kinerja Pegawai</p>
                        <code class="block text-[11px] font-mono text-slate-800 bg-slate-50 px-3 py-2 rounded-lg border border-slate-200">
                            (Total Jam Estimasi / Jam Kerja Aktif) × 100%
                        </code>
                        <p class="text-[10px] text-slate-500 mt-1.5 italic">Persentase beban jam kerja terhadap jam kerja aktif bulanan</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/80 border border-purple-100 shadow-xs">
                        <p class="text-[10px] font-extrabold uppercase text-purple-700 tracking-wider mb-1.5">🏆 Nilai CKP Akhir</p>
                        <code class="block text-[11px] font-mono text-slate-800 bg-slate-50 px-3 py-2 rounded-lg border border-slate-200">
                            CKP = (% Kuantitas + % Kualitas) / 2
                        </code>
                        <p class="text-[10px] text-slate-500 mt-1.5 italic">Rata-rata kuantitas (a) dan kualitas (b)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Ringkasan Pegawai (Sesuai Spesifikasi Bagian J) -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Daftar Pegawai & Metrik Kinerja</h3>
                <p class="text-xs text-slate-500">Klik baris pegawai atau tombol "Lihat Detail" untuk memeriksa rincian pekerjaan</p>
            </div>
            <span class="text-xs font-bold text-slate-400">Total: {{ count($rekapPegawai) }} Pegawai</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-[10px] font-extrabold uppercase text-slate-500 tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Nama Pegawai</th>
                        <th class="py-3.5 px-4">Tim Kerja</th>
                        <th class="py-3.5 px-4 text-center">Jumlah Pekerjaan</th>
                        <th class="py-3.5 px-4 text-center">Pekerjaan Utama</th>
                        <th class="py-3.5 px-4 text-center">Pekerjaan Tambahan</th>
                        <th class="py-3.5 px-4 text-center">Total Target</th>
                        <th class="py-3.5 px-4 text-center">Total Realisasi</th>
                        <th class="py-3.5 px-4 text-center">Total Estimasi Waktu</th>
                        <th class="py-3.5 px-4 text-center">Skor Kinerja</th>
                        <th class="py-3.5 px-4 text-center">Nilai CKP</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rekapPegawai as $data)
                        <tr class="hover:bg-slate-50/70 transition cursor-pointer" @click="selectedPegawai = {{ json_encode($data) }}; modalDetail = true">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $data['pegawai']->avatar_url }}" alt="{{ $data['pegawai']->name }}" class="w-8 h-8 rounded-lg object-cover ring-1 ring-slate-200">
                                    <div>
                                        <p class="font-bold text-slate-900 leading-tight">{{ $data['pegawai']->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono">{{ $data['pegawai']->nip ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                {{ $data['pegawai']->tim ? $data['pegawai']->tim->nama_tim : 'Lintas Tim' }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-800">
                                {{ $data['total_pekerjaan'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-lg bg-blue-50 text-bps-blue font-bold text-xs">
                                    {{ $data['jumlah_utama'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-lg bg-purple-50 text-purple-700 font-bold text-xs">
                                    {{ $data['jumlah_tambahan'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-800">{{ $data['total_target'] }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-emerald-600">{{ $data['total_realisasi'] }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-700">{{ $data['total_estimasi_jam'] }} Jam</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-xl text-xs font-black
                                    @if($data['skor_kinerja'] >= 100) bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($data['skor_kinerja'] >= 80) bg-blue-50 text-bps-blue border border-blue-200
                                    @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                                    {{ $data['skor_kinerja'] }}%
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-black text-slate-800">
                                {{ $data['nilai_ckp'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <button type="button" 
                                        @click.stop="selectedPegawai = {{ json_encode($data) }}; modalDetail = true"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl font-bold text-xs bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white dark:bg-slate-800 dark:hover:bg-blue-600 dark:text-blue-400 dark:hover:text-white border border-blue-200/80 hover:border-blue-600 transition-all duration-200 shadow-xs hover:shadow-md hover:scale-105 active:scale-95 cursor-pointer group">
                                    <span>Detail</span>
                                    <i class="fa-solid fa-arrow-right text-[10px] transition-transform duration-200 group-hover:translate-x-0.5"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-8 text-center text-slate-400">Belum ada pegawai yang sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detail Pekerjaan Pegawai (Sesuai Spesifikasi Bagian J) -->
    <div x-show="modalDetail" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="modalDetail = false" class="bg-white rounded-3xl max-w-4xl w-full p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-6 max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <img :src="selectedPegawai?.pegawai?.avatar_url || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(selectedPegawai?.pegawai?.name || 'User') + '&background=1565C0&color=ffffff&bold=true')" 
                         :alt="selectedPegawai?.pegawai?.name" 
                         x-on:error="$event.target.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(selectedPegawai?.pegawai?.name || 'User') + '&background=1565C0&color=ffffff&bold=true'"
                         class="w-10 h-10 rounded-xl object-cover ring-2 ring-bps-blue/20">
                    <div>
                        <h3 class="text-base font-black text-slate-900" x-text="selectedPegawai?.pegawai?.name"></h3>
                        <p class="text-xs text-slate-500" x-text="(selectedPegawai?.pegawai?.tim?.nama_tim || 'Lintas Tim') + ' • Periode: ' + '{{ $daftarBulan[$currentMonth] }} {{ $currentYear }}'"></p>
                    </div>
                </div>
                <button @click="modalDetail = false" class="text-slate-400 hover:text-slate-600 p-2"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <!-- Mini Summary Pegawai Terpilih -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase">Total Jam</span>
                    <p class="text-lg font-black text-slate-800" x-text="selectedPegawai?.total_estimasi_jam + ' Jam'"></p>
                </div>
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase">Skor Kinerja</span>
                    <p class="text-lg font-black text-bps-blue" x-text="selectedPegawai?.skor_kinerja + '%'"></p>
                </div>
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase">Rata Kuantitas</span>
                    <p class="text-lg font-black text-emerald-600" x-text="selectedPegawai?.avg_kuantitas + '%'"></p>
                </div>
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase">Nilai CKP</span>
                    <p class="text-lg font-black text-amber-500" x-text="selectedPegawai?.nilai_ckp"></p>
                </div>
            </div>

            <!-- Detail List Pekerjaan (Sesuai Kolom Bagian J) -->
            <div class="space-y-3">
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Rincian Seluruh Pekerjaan (Utama & Tambahan):</h4>
                
                <div class="overflow-x-auto border border-slate-100 rounded-2xl">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-[10px] font-extrabold uppercase text-slate-500">
                            <tr>
                                <th class="p-3">Nama Kegiatan</th>
                                <th class="p-3 text-center">Jenis</th>
                                <th class="p-3 text-center">Satuan</th>
                                <th class="p-3 text-center">Target</th>
                                <th class="p-3 text-center">Realisasi</th>
                                <th class="p-3 text-center">Kuantitas</th>
                                <th class="p-3 text-center">Kualitas</th>
                                <th class="p-3 text-center">Estimasi Jam</th>
                                <th class="p-3">Tanggal</th>
                                <th class="p-3 text-center">Nilai CKP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="item in selectedPegawai?.items" :key="item.id">
                                <tr class="hover:bg-slate-50/70">
                                    <td class="p-3 font-bold text-slate-800" x-text="item.nama_kegiatan"></td>
                                    <td class="p-3 text-center">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase"
                                              :class="item.jenis_pekerjaan === 'utama' ? 'bg-blue-50 text-bps-blue' : 'bg-purple-50 text-purple-700'"
                                              x-text="item.jenis_pekerjaan"></span>
                                    </td>
                                    <td class="p-3 text-center font-medium" x-text="item.satuan"></td>
                                    <td class="p-3 text-center font-bold text-slate-800" x-text="item.target_kuantitas"></td>
                                    <td class="p-3 text-center font-bold text-emerald-600" x-text="item.realisasi_kuantitas"></td>
                                    <td class="p-3 text-center font-bold" x-text="item.persentase_kuantitas"></td>
                                    <td class="p-3 text-center font-bold text-amber-600" x-text="item.kualitas > 0 ? item.kualitas : '-'"></td>
                                    <td class="p-3 text-center font-bold text-slate-700" x-text="item.estimasi_waktu + ' Jam'"></td>
                                    <td class="p-3 text-slate-600 text-[11px] font-medium whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5">
                                            <i class="fa-regular fa-calendar text-[10px] text-slate-400"></i>
                                            <span x-text="formatRentang(item.tanggal_mulai, item.tanggal_selesai)"></span>
                                        </div>
                                    </td>
                                    <td class="p-3 text-center font-black text-amber-600" x-text="(item.nilai_ckp && Number(item.nilai_ckp) > 0) ? item.nilai_ckp : (item.kualitas > 0 ? (((Number(item.persentase_kuantitas) || 0) + (Number(item.kualitas) || 0)) / 2).toFixed(1) : '-')"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="button" @click="modalDetail = false" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>

</div>
@endsection
