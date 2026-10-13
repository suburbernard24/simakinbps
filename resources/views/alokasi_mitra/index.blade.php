@extends('layouts.app')

@section('title', 'Alokasi Pekerjaan Mitra')
@section('page_title', 'Alokasi Pekerjaan Mitra')

@section('content')
<div class="space-y-6" x-data="{
    pilihMetodeModal: false,
    modalOpen: {{ $errors->any() ? 'true' : 'false' }},
    importModal: false,
    deleteId: null,
    deleteModal: false,
    deleteNama: '',
    selectedMitraId: '',
    jenisMitraModal: '{{ old('jenis_mitra', 'pendataan_lapangan') }}',
    honorInput: '',
    formatRupiah(val) {
        let clean = (val + '').replace(/\D/g, '');
        if (!clean) return '';
        return new Intl.NumberFormat('id-ID').format(clean);
    }
}">

    {{-- Action & Info Bar --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl shadow-sm border border-slate-200/80 dark:border-slate-800">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-bps-faint dark:bg-slate-800 text-bps-blue dark:text-blue-400 flex items-center justify-center text-lg shadow-2xs">
                <i class="fa-solid fa-users-viewfinder"></i>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white">Alokasi Pekerjaan Mitra Statistik</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Kelola penugasan survei dan honor bulanan mitra statistik tim Anda.
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="px-4 py-2 rounded-2xl bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 text-xs">
                <span class="text-slate-500 dark:text-slate-400 text-[10px] uppercase font-bold block">Batas Maksimal Honor / Bulan / Mitra:</span>
                <div class="flex items-center gap-3 mt-0.5">
                    <span class="text-[10px] text-slate-500">🗺️ Pendataan:</span>
                    <span class="font-black text-bps-blue dark:text-blue-400">Rp {{ number_format($maksimalHonorPendataan, 0, ',', '.') }}</span>
                    <span class="text-[10px] text-slate-400">•</span>
                    <span class="text-[10px] text-slate-500">🖥️ Pengolahan:</span>
                    <span class="font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($maksimalHonorPengolahan, 0, ',', '.') }}</span>
                </div>
            </div>

            <button @click="pilihMetodeModal = true"
                    class="px-5 py-2.5 bg-bps-blue hover:bg-bps-dark text-white text-xs font-bold rounded-2xl shadow-md shadow-bps-blue/30 transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Alokasi Mitra</span>
            </button>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="flex flex-wrap items-center justify-between gap-4 bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-800">
        
        <form method="GET" action="{{ route('kinerja-mitra.alokasi.index') }}" class="flex flex-wrap items-center gap-3">
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
                <select name="mitra_id" onchange="this.form.submit()" 
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-bps-blue outline-none max-w-[200px]">
                    <option value="">Semua Mitra</option>
                    @foreach($mitras as $m)
                        <option value="{{ $m->id }}" {{ request('mitra_id') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="relative min-w-[220px]">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="searchInput" placeholder="Cari survei / mitra..." oninput="filterTable()"
                   class="w-full pl-8 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
        </div>
    </div>

    {{-- Tabel Alokasi Mitra --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200/80 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300" id="alokasiMitraTable">
                <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-100 dark:border-slate-800 text-[10px] uppercase font-black text-slate-400 tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5 text-center w-12">No</th>
                        <th class="px-4 py-3.5 min-w-[190px]">Mitra Statistik</th>
                        <th class="px-4 py-3.5 min-w-[200px]">Survei &amp; Kegiatan</th>
                        <th class="px-4 py-3.5">Posisi</th>
                        <th class="px-4 py-3.5">Satuan &amp; Target</th>
                        <th class="px-4 py-3.5 min-w-[130px]">Honor (IDR)</th>
                        <th class="px-4 py-3.5 min-w-[140px]">Jadwal Pelaksanaan</th>
                        <th class="px-4 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($alokasiList as $idx => $item)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors table-row"
                        data-search="{{ strtolower($item->nama_kegiatan . ' ' . $item->kegiatan . ' ' . ($item->mitra->nama ?? '')) }}">
                        <td class="px-4 py-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                        
                        {{-- Mitra --}}
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ $item->mitra->foto_url ?? '' }}" alt="{{ $item->mitra->nama ?? '' }}" class="w-8 h-8 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700">
                                <div>
                                    <a href="{{ route('kinerja-mitra.mitra.show', $item->mitra_id) }}" class="font-bold text-slate-900 dark:text-white hover:text-bps-blue text-xs block line-clamp-1">
                                        {{ $item->mitra->nama ?? '-' }}
                                    </a>
                                    <span class="font-mono text-[10px] text-slate-400 block">{{ $item->mitra->sobat_id ?? '-' }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Survei & Kegiatan --}}
                        <td class="px-4 py-4">
                            <p class="font-extrabold text-slate-900 dark:text-white text-xs">{{ $item->nama_kegiatan }}</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1">{{ $item->kegiatan }}</p>
                            @if($item->keterangan)
                            <p class="text-[10px] text-slate-400 italic mt-0.5"><i class="fa-regular fa-comment-dots mr-1"></i>{{ $item->keterangan }}</p>
                            @endif
                        </td>

                        {{-- Posisi --}}
                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-[11px] whitespace-nowrap">
                                {{ $item->posisi }}
                            </span>
                        </td>

                        {{-- Satuan & Target --}}
                        <td class="px-4 py-4">
                            <span class="font-extrabold text-bps-blue dark:text-blue-400 text-xs">{{ $item->target_kuantitas }}</span>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium ml-1">{{ $item->satuan }}</span>
                        </td>

                        {{-- Honor --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="font-extrabold text-slate-900 dark:text-white text-xs">
                                Rp {{ number_format($item->honor, 0, ',', '.') }}
                            </div>
                            @if($item->melebihi_batas || $item->honor_tidak_dibayar > 0)
                                <div class="mt-1 space-y-0.5">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800 text-[10px] font-bold text-amber-700 dark:text-amber-300">
                                        <i class="fa-solid fa-triangle-exclamation text-[9px]"></i>
                                        Rp {{ number_format($item->honor_tidak_dibayar, 0, ',', '.') }} Tidak Dibayar
                                    </span>
                                    <span class="text-[10px] text-slate-400 block">
                                        Dibayar: <strong class="text-emerald-600 dark:text-emerald-400">Rp {{ number_format($item->honor_dibayar, 0, ',', '.') }}</strong>
                                    </span>
                                </div>
                            @else
                                <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold block mt-0.5">
                                    <i class="fa-solid fa-check text-[9px]"></i> Dibayar Penuh
                                </span>
                            @endif
                        </td>

                        {{-- Jadwal --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            <p class="font-semibold text-slate-700 dark:text-slate-300">
                                {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d M') : '-' }} - {{ $item->tanggal_selesai ? $item->tanggal_selesai->format('d M Y') : '-' }}
                            </p>
                        </td>


                        {{-- Aksi --}}
                        <td class="px-4 py-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('kinerja-mitra.alokasi.edit', $item->id) }}"
                                   class="w-7 h-7 rounded-lg bg-bps-faint dark:bg-slate-800 hover:bg-bps-blue hover:text-white text-bps-blue dark:text-blue-400 flex items-center justify-center transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen text-[10px]"></i>
                                </a>
                                <button type="button" 
                                        @click="deleteModal = true; deleteId = {{ $item->id }}; deleteNama = '{{ addslashes($item->nama_kegiatan) }} ({{ addslashes($item->mitra->nama ?? 'Mitra') }})'"
                                        class="w-7 h-7 rounded-lg bg-red-50 dark:bg-red-950/30 hover:bg-red-500 hover:text-white text-red-500 flex items-center justify-center transition-colors cursor-pointer" title="Hapus">
                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                </button>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xl text-slate-400 mb-2">
                                    <i class="fa-regular fa-folder-open"></i>
                                </div>
                                <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">Belum Ada Alokasi Pekerjaan Mitra</p>
                                <p class="text-xs text-slate-400 mt-0.5">Silakan klik "Tambah Alokasi Mitra" untuk menugaskan pekerjaan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-850 flex items-center justify-between text-xs text-slate-500">
            <span>Total: {{ $alokasiList->count() }} alokasi pekerjaan mitra</span>
        </div>
    </div>

    {{-- ==================== MODAL: PILIH METODE TAMBAH ==================== --}}
    <div x-show="pilihMetodeModal" x-cloak style="display:none;"
         class="fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="pilihMetodeModal = false"
             x-show="pilihMetodeModal" x-transition.duration.200ms
             class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-md overflow-hidden">
            
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-gradient-to-r from-blue-500/10 to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-bps-faint dark:bg-slate-800 text-bps-blue dark:text-blue-400 flex items-center justify-center">
                        <i class="fa-solid fa-layer-group text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">Tambah Alokasi Mitra</h3>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400">Pilih metode pengalokasian</p>
                    </div>
                </div>
                <button @click="pilihMetodeModal = false" class="text-slate-400 hover:text-rose-500">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-6 space-y-3">
                {{-- Opsi 1: Satu Mitra --}}
                <button @click="pilihMetodeModal = false; modalOpen = true"
                        class="w-full flex items-center gap-4 p-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700 hover:border-bps-blue hover:bg-bps-faint/30 dark:hover:bg-slate-800 transition text-left group cursor-pointer">
                    <div class="w-12 h-12 rounded-xl bg-bps-faint dark:bg-slate-800 group-hover:bg-bps-blue/10 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-user-plus text-bps-blue text-base"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 dark:text-slate-100 text-sm">1 Orang Mitra</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Alokasikan pekerjaan untuk 1 mitra via formulir langsung</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-bps-blue ml-auto"></i>
                </button>

                {{-- Opsi 2: Banyak Mitra via Excel --}}
                <button @click="pilihMetodeModal = false; importModal = true"
                        class="w-full flex items-center gap-4 p-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700 hover:border-emerald-500 hover:bg-emerald-50/40 dark:hover:bg-slate-800 transition text-left group cursor-pointer">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 group-hover:bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-file-excel text-emerald-600 dark:text-emerald-400 text-base"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 dark:text-slate-100 text-sm">Banyak Mitra Sekaligus (Excel)</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Unduh template Excel, lengkapi data, lalu import massal</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-emerald-500 ml-auto"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL: FORM 1 ORANG MITRA ==================== --}}
    <div x-show="modalOpen" x-cloak style="display: none;" 
         class="fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-2 sm:p-4">
        <div @click.away="modalOpen = false" x-show="modalOpen" x-transition.duration.200ms 
             class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-7xl overflow-hidden flex flex-col max-h-[95vh]">
            
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-bps-blue dark:text-blue-300 flex items-center justify-center">
                        <i class="fa-solid fa-user-check text-xs"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-sm">Tambah Alokasi Pekerjaan — 1 Orang Mitra</h3>
                </div>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-rose-500"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form method="POST" action="{{ route('kinerja-mitra.alokasi.store') }}" 
                  x-ref="formAlokasi"
                  class="flex flex-col flex-1 overflow-hidden"
                  x-data="{
                      selectedMitraId: '{{ old('mitra_id', '') }}',
                      jenisMitraModal: '{{ old('jenis_mitra', 'pendataan_lapangan') }}',
                      quotaMap: @js($mitraQuotaMap),
                      popOverLimitModal: false,
                      konfirmasiMelebihiBatas: false,
                      rows: [{ kegiatan: '', volume: '', satuan: 'Dokumen', harga_satuan: '', tanggal_mulai: '{{ date('Y-m-01') }}', tanggal_selesai: '{{ date('Y-m-t') }}', akun_pok: '' }],
                      addRow() {
                          this.rows.push({ kegiatan: '', volume: '', satuan: 'Dokumen', harga_satuan: '', tanggal_mulai: '{{ date('Y-m-01') }}', tanggal_selesai: '{{ date('Y-m-t') }}', akun_pok: '' });
                      },
                      removeRow(i) {
                          if (this.rows.length > 1) this.rows.splice(i, 1);
                      },
                      jumlah(row) {
                          let v = parseFloat((row.volume + '').replace(/,/g, '.')) || 0;
                          let h = parseInt((row.harga_satuan + '').replace(/\D/g, '')) || 0;
                          return v * h;
                      },
                      totalHonor() {
                          return this.rows.reduce((s, r) => s + this.jumlah(r), 0);
                      },
                      formatRp(val) {
                          if (!val && val !== 0) return 'Rp 0';
                          return 'Rp\u00a0' + new Intl.NumberFormat('id-ID').format(Math.round(val));
                      },
                      formatInput(row) {
                          let clean = (row.harga_satuan + '').replace(/\D/g, '');
                          row.harga_satuan = clean;
                      },
                      getMitraData() {
                          if (!this.selectedMitraId || !this.quotaMap[this.selectedMitraId]) return null;
                          return this.quotaMap[this.selectedMitraId];
                      },
                      getMitraNama() {
                          let m = this.getMitraData();
                          return m ? m.nama : 'Mitra Terpilih';
                      },
                      getMitraInfo() {
                          let m = this.getMitraData();
                          if (!m) return null;
                          return m[this.jenisMitraModal] || null;
                      },
                      getMaksimalHonor() {
                          let info = this.getMitraInfo();
                          if (info) return info.maksimal;
                          return this.jenisMitraModal === 'pengolahan' ? {{ (int) $maksimalHonorPengolahan }} : {{ (int) $maksimalHonorPendataan }};
                      },
                      getTerpakaiHonor() {
                          let info = this.getMitraInfo();
                          return info ? info.terpakai : 0;
                      },
                      getSisaHonor() {
                          let info = this.getMitraInfo();
                          return info ? info.sisa : this.getMaksimalHonor();
                      },
                      isMelebihiBatas() {
                          if (!this.selectedMitraId) return false;
                          return this.totalHonor() > this.getSisaHonor();
                      },
                      getKelebihanHonor() {
                          if (!this.isMelebihiBatas()) return 0;
                          return this.totalHonor() - this.getSisaHonor();
                      },
                      getHonorDibayar() {
                          return Math.min(this.totalHonor(), this.getSisaHonor());
                      },
                      getHonorTidakDibayar() {
                          return Math.max(0, this.totalHonor() - this.getSisaHonor());
                      },
                      handleFormSubmit() {
                          if (!this.selectedMitraId) {
                              alert('Silakan pilih Mitra Statistik yang ditugaskan terlebih dahulu.');
                              return;
                          }
                          if (this.totalHonor() <= 0) {
                              alert('Silakan lengkapi kegiatan, volume, dan harga satuan honor dengan benar.');
                              return;
                          }

                          // Cek apakah alokasi pekerjaan dan honor melewati batas maksimum
                          if (this.isMelebihiBatas()) {
                              // TAMPILKAN POP UP NOTIFIKASI
                              this.popOverLimitModal = true;
                          } else {
                              // Masih dalam batas kuota: simpan langsung secara normal
                              this.konfirmasiMelebihiBatas = false;
                              this.$refs.formAlokasi.submit();
                          }
                      },
                      confirmAndSubmit() {
                          this.konfirmasiMelebihiBatas = true;
                          this.popOverLimitModal = false;
                          this.$nextTick(() => {
                              this.$refs.formAlokasi.submit();
                          });
                      }
                  }">
                @csrf
                <input type="hidden" name="konfirmasi_melebihi_batas" :value="konfirmasiMelebihiBatas ? '1' : '0'">

                {{-- ===== HEADER: Mitra, Posisi, Jenis Mitra ===== --}}
                <div class="px-6 pt-5 pb-4 border-b border-slate-100 dark:border-slate-800 space-y-3 text-xs">

                    {{-- Pilihan Mitra --}}
                    <div>
                        <label class="block font-bold mb-1 text-slate-700 dark:text-slate-300">Mitra Statistik yang Ditugaskan <span class="text-rose-500">*</span></label>
                        <select name="mitra_id" required
                                x-model="selectedMitraId"
                                class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue font-medium text-slate-800 dark:text-slate-200">
                            <option value="">-- Pilih Mitra Statistik Terdaftar --</option>
                            @foreach($mitras as $m)
                                <option value="{{ $m->id }}" {{ old('mitra_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama }} ({{ $m->sobat_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Posisi --}}
                        <div>
                            <label class="block font-bold mb-1 text-slate-700 dark:text-slate-300">Posisi Penugasan <span class="text-rose-500">*</span></label>
                            <select name="posisi" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue text-slate-800 dark:text-slate-200">
                                <option value="Petugas Pencacah Lapangan (PCL)">Petugas Pencacah Lapangan (PCL)</option>
                                <option value="Petugas Pemeriksa Lapangan (PML)">Petugas Pemeriksa Lapangan (PML)</option>
                                <option value="Petugas Pengolahan Data">Petugas Pengolahan Data</option>
                                <option value="Petugas Listing / Pemetaan">Petugas Listing / Pemetaan</option>
                                <option value="Operator Entri Data">Operator Entri Data</option>
                            </select>
                        </div>
                        {{-- Jenis Mitra --}}
                        <div>
                            <label class="block font-bold mb-1 text-slate-700 dark:text-slate-300">Jenis Mitra <span class="text-rose-500">*</span></label>
                            <select name="jenis_mitra" required x-model="jenisMitraModal"
                                    class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue text-slate-800 dark:text-slate-200">
                                <option value="pendataan_lapangan">🗺️ Mitra Pendataan Lapangan</option>
                                <option value="pengolahan">🖥️ Mitra Pengolahan</option>
                            </select>
                        </div>
                    </div>

                    {{-- ==================== KARTU STATUS & SISA HONOR MITRA ==================== --}}
                    <div x-show="selectedMitraId" class="p-3.5 rounded-2xl border transition-all duration-200"
                         :class="isMelebihiBatas() 
                             ? 'bg-rose-50/70 dark:bg-rose-950/30 border-rose-200 dark:border-rose-900/60' 
                             : 'bg-emerald-50/60 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900/50'">
                        
                        <div class="flex flex-wrap items-center justify-between gap-2 pb-2.5 border-b"
                             :class="isMelebihiBatas() ? 'border-rose-100 dark:border-rose-900/40' : 'border-emerald-100 dark:border-emerald-900/30'">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full" :class="isMelebihiBatas() ? 'bg-rose-500 animate-pulse' : 'bg-emerald-500'"></span>
                                <span class="font-bold text-xs text-slate-800 dark:text-slate-100">
                                    Status Honor Mitra: <strong x-text="getMitraNama()"></strong>
                                </span>
                            </div>
                            <div class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                                Batas Maksimal Honor: <span class="font-black text-slate-700 dark:text-slate-200" x-text="formatRp(getMaksimalHonor())"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-2.5 text-center">
                            {{-- Honor Terpakai --}}
                            <div class="p-2.5 rounded-xl bg-white/80 dark:bg-slate-900/80 border border-slate-100 dark:border-slate-800 shadow-2xs">
                                <span class="text-[10px] text-slate-400 font-semibold block uppercase">Sudah Dialokasikan</span>
                                <span class="font-bold text-slate-700 dark:text-slate-300 text-xs mt-0.5 block" x-text="formatRp(getTerpakaiHonor())"></span>
                            </div>

                            {{-- Sisa Honor Yang Tersedia --}}
                            <div class="p-2.5 rounded-xl bg-white/80 dark:bg-slate-900/80 border border-slate-100 dark:border-slate-800 shadow-2xs">
                                <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold block uppercase">Sisa Honor Tersedia</span>
                                <span class="font-black text-emerald-600 dark:text-emerald-400 text-sm mt-0.5 block" x-text="formatRp(getSisaHonor())"></span>
                            </div>

                            {{-- Honor Rencana Baru --}}
                            <div class="p-2.5 rounded-xl bg-white/80 dark:bg-slate-900/80 border border-slate-100 dark:border-slate-800 shadow-2xs">
                                <span class="text-[10px] text-bps-blue dark:text-blue-400 font-bold block uppercase">Rencana Alokasi Ini</span>
                                <span class="font-black text-bps-blue dark:text-blue-400 text-sm mt-0.5 block" x-text="formatRp(totalHonor())"></span>
                            </div>

                            {{-- Status Sisa / Melebihi --}}
                            <div class="p-2.5 rounded-xl border shadow-2xs"
                                 :class="isMelebihiBatas() 
                                     ? 'bg-rose-100/90 dark:bg-rose-950/70 border-rose-300 dark:border-rose-900 text-rose-700 dark:text-rose-300' 
                                     : 'bg-emerald-100/90 dark:bg-emerald-950/70 border-emerald-300 dark:border-emerald-900 text-emerald-700 dark:text-emerald-300'">
                                <span class="text-[10px] font-bold block uppercase" x-text="isMelebihiBatas() ? 'Melebihi Batas' : 'Sisa Setelah Ini'"></span>
                                <span class="font-black text-sm mt-0.5 block" 
                                      x-text="isMelebihiBatas() ? '+ ' + formatRp(getKelebihanHonor()) : formatRp(getSisaHonor() - totalHonor())"></span>
                            </div>
                        </div>

                        {{-- Alert Jika Melebihi Batas --}}
                        <div x-show="isMelebihiBatas()" class="mt-2.5 p-2.5 rounded-xl bg-rose-100/90 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900/60 flex items-start gap-2.5 text-[11px] text-rose-800 dark:text-rose-200">
                            <i class="fa-solid fa-triangle-exclamation text-rose-500 mt-0.5 shrink-0 text-xs"></i>
                            <div class="flex-1">
                                <p class="font-bold">Peringatan: Total Honor Melewati Batas Maksimum!</p>
                                <p class="text-[10px] text-rose-700 dark:text-rose-300 mt-0.5 leading-relaxed">
                                    Honor baru (<strong x-text="formatRp(totalHonor())"></strong>) melebihi sisa honor mitra (<strong x-text="formatRp(getSisaHonor())"></strong>).
                                    Nominal kelebihan sebesar <strong class="underline" x-text="formatRp(getKelebihanHonor())"></strong> tidak akan dibayarkan jika penugasan ini dialokasikan.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Fallback info jika mitra belum dipilih --}}
                    <div x-show="!selectedMitraId" class="flex items-center gap-2 text-[10px] text-slate-400 bg-blue-50 dark:bg-slate-800/60 rounded-xl px-3 py-2">
                        <i class="fa-solid fa-circle-info text-bps-blue"></i>
                        <span>Batas honor bulanan:</span>
                        <strong class="text-bps-blue dark:text-blue-400"
                                x-text="jenisMitraModal === 'pengolahan' ? 'Rp {{ number_format($maksimalHonorPengolahan, 0, ',', '.') }}' : 'Rp {{ number_format($maksimalHonorPendataan, 0, ',', '.') }}'"></strong>
                        <span>/ mitra / bulan. Pilih mitra di atas untuk melihat rincian sisa kuota honor mitra.</span>
                    </div>
                </div>

                {{-- ===== TABEL BARIS KEGIATAN ===== --}}
                <div class="flex-1 overflow-auto p-4">
                    <table class="w-full text-xs border-collapse min-w-[1100px]">
                        <thead>
                            <tr class="bg-slate-700 dark:bg-slate-800 text-white text-center">
                                <th class="px-2 py-3 rounded-tl-lg w-10 font-bold">No</th>
                                <th class="px-3 py-3 font-bold text-left min-w-[280px]">Kegiatan <span class="text-rose-300">*</span></th>
                                <th class="px-3 py-3 font-bold w-20">Volume <span class="text-rose-300">*</span></th>
                                <th class="px-3 py-3 font-bold w-24">Satuan <span class="text-rose-300">*</span></th>
                                <th class="px-3 py-3 font-bold w-36">Harga Satuan <span class="text-rose-300">*</span></th>
                                <th class="px-3 py-3 font-bold w-36">Jumlah Honor</th>
                                <th class="px-3 py-3 font-bold w-36">Tgl Mulai <span class="text-rose-300">*</span></th>
                                <th class="px-3 py-3 font-bold w-36">Tgl Selesai <span class="text-rose-300">*</span></th>
                                <th class="px-3 py-3 font-bold min-w-[200px]">Beban Anggaran / Akun POK</th>
                                <th class="px-2 py-3 rounded-tr-lg w-10"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(row, i) in rows" :key="i">
                                <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50/60 dark:hover:bg-slate-800/30">
                                    <td class="px-2 py-2.5 text-center text-slate-400 font-bold" x-text="i + 1"></td>
                                    <td class="px-2 py-2">
                                        <input type="text" :name="`rows[${i}][kegiatan]`" x-model="row.kegiatan" required
                                               placeholder="Uraian kegiatan..."
                                               class="w-full px-3 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg outline-none focus:ring-2 focus:ring-bps-blue text-slate-800 dark:text-slate-200">
                                    </td>
                                    <td class="px-2 py-2">
                                        <input type="number" :name="`rows[${i}][volume]`" x-model="row.volume" required min="0.01" step="any"
                                               placeholder="0"
                                               class="w-full px-2 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg outline-none focus:ring-2 focus:ring-bps-blue text-center text-slate-800 dark:text-slate-200">
                                    </td>
                                    <td class="px-2 py-2">
                                        <input type="text" :name="`rows[${i}][satuan]`" x-model="row.satuan" required
                                               placeholder="Dokumen"
                                               class="w-full px-3 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg outline-none focus:ring-2 focus:ring-bps-blue text-slate-800 dark:text-slate-200">
                                    </td>
                                    <td class="px-2 py-2">
                                        <div class="relative">
                                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[11px] font-bold">Rp</span>
                                            <input type="text" :name="`rows[${i}][harga_satuan]`" x-model="row.harga_satuan"
                                                   @input="formatInput(row)"
                                                   required placeholder="0"
                                                   class="w-full pl-7 pr-2 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg outline-none focus:ring-2 focus:ring-bps-blue text-slate-800 dark:text-slate-200">
                                        </div>
                                    </td>
                                    <td class="px-2 py-2 text-center">
                                        <span class="font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap text-sm"
                                              x-text="formatRp(jumlah(row))"></span>
                                    </td>
                                    <td class="px-2 py-2">
                                        <input type="date" :name="`rows[${i}][tanggal_mulai]`" x-model="row.tanggal_mulai" required
                                               class="w-full px-2 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg outline-none focus:ring-2 focus:ring-bps-blue text-slate-800 dark:text-slate-200">
                                    </td>
                                    <td class="px-2 py-2">
                                        <input type="date" :name="`rows[${i}][tanggal_selesai]`" x-model="row.tanggal_selesai" required
                                               class="w-full px-2 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg outline-none focus:ring-2 focus:ring-bps-blue text-slate-800 dark:text-slate-200">
                                    </td>
                                    <td class="px-2 py-2">
                                        <input type="text" :name="`rows[${i}][akun_pok]`" x-model="row.akun_pok"
                                               placeholder="Belanja Honor Output..."
                                               class="w-full px-3 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg outline-none focus:ring-2 focus:ring-bps-blue text-slate-800 dark:text-slate-200">
                                    </td>
                                    <td class="px-2 py-2 text-center">
                                        <button type="button" @click="removeRow(i)"
                                                :disabled="rows.length === 1"
                                                :class="rows.length === 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-rose-500 hover:text-white cursor-pointer'"
                                                class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/30 text-rose-400 flex items-center justify-center transition-colors mx-auto">
                                            <i class="fa-solid fa-minus text-[10px]"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>

                            {{-- Baris TOTAL --}}
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-t-2 border-slate-200 dark:border-slate-700">
                                <td colspan="5" class="px-3 py-2 font-extrabold text-slate-700 dark:text-slate-300 text-right">TOTAL</td>
                                <td class="px-2 py-2 text-center font-extrabold text-emerald-600 dark:text-emerald-400 whitespace-nowrap"
                                    x-text="formatRp(totalHonor())"></td>
                                <td colspan="4"></td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Tombol tambah baris --}}
                    <button type="button" @click="addRow()"
                            class="mt-3 flex items-center gap-2 px-4 py-1.5 rounded-xl border-2 border-dashed border-bps-blue/40 hover:border-bps-blue hover:bg-bps-faint/40 text-bps-blue text-[11px] font-bold transition-colors cursor-pointer">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        Tambah Baris Kegiatan
                    </button>
                </div>

                {{-- Footer form --}}
                <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-850 flex items-center justify-between gap-3 shrink-0">
                    <div class="text-[11px] text-slate-500">
                        Total honor: <strong class="text-emerald-600 dark:text-emerald-400" x-text="formatRp(totalHonor())"></strong>
                        <span class="ml-2 text-slate-400">•</span>
                        <span class="ml-2" x-text="rows.length + ' baris kegiatan'"></span>
                        <template x-if="selectedMitraId && isMelebihiBatas()">
                            <span class="ml-2 px-2 py-0.5 rounded-md bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300 font-bold text-[10px]">
                                Melebihi batas honor
                            </span>
                        </template>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300">Batal</button>
                        <button type="button" @click="handleFormSubmit()" 
                                class="px-5 py-2 bg-bps-blue hover:bg-bps-dark text-white rounded-xl text-xs font-bold shadow-md flex items-center gap-2 cursor-pointer transition">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan Alokasi
                        </button>
                    </div>
                </div>

                {{-- ==================== POP UP NOTIFIKASI MELEWATI BATAS ==================== --}}
                <div x-show="popOverLimitModal" x-cloak style="display: none;"
                     class="fixed inset-0 z-[80] bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4">
                    <div @click.away="popOverLimitModal = false"
                         x-show="popOverLimitModal" x-transition.duration.200ms
                         class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 w-full max-w-md overflow-hidden text-center p-6 space-y-5">
                        
                        {{-- Ikon Pop Up --}}
                        <div class="w-16 h-16 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl mx-auto shadow-inner">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>

                        {{-- Teks Pertanyaan Konfirmasi Sesuai Permintaan User --}}
                        <div class="space-y-2">
                            <h3 class="font-black text-slate-900 dark:text-white text-base">
                                Konfirmasi Alokasi Pekerjaan Mitra
                            </h3>
                            <p class="text-sm font-extrabold text-amber-600 dark:text-amber-400 leading-snug px-2">
                                Apakah ingin mengalokasikan pekerjaan kepada Mitra? Jika Ya maka honor yang melebihi batas akan tidak dibayar
                            </p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                Ketentuan ini hanya berlaku ketika alokasi pekerjaan dan honor melewati batas maksimum kuota mitra.
                            </p>
                        </div>

                        {{-- Rincian Kalkulasi Honor --}}
                        <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 text-xs text-left space-y-2">
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-slate-500">Nama Mitra:</span>
                                <strong class="text-slate-800 dark:text-slate-200" x-text="getMitraNama()"></strong>
                            </div>
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-slate-500">Sisa Kuota Honor Tersedia:</span>
                                <strong class="text-emerald-600 dark:text-emerald-400" x-text="formatRp(getSisaHonor())"></strong>
                            </div>
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-slate-500">Total Honor Rencana Alokasi:</span>
                                <strong class="text-bps-blue dark:text-blue-400" x-text="formatRp(totalHonor())"></strong>
                            </div>
                            <div class="border-t border-slate-200 dark:border-slate-700 pt-2 flex justify-between items-center font-bold">
                                <span class="text-rose-600 dark:text-rose-400">Honor Melebihi Batas (TIDAK DIBAYAR):</span>
                                <span class="text-rose-600 dark:text-rose-400 font-black text-sm" x-text="formatRp(getKelebihanHonor())"></span>
                            </div>
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-slate-500">Honor yang Tetap Dibayar:</span>
                                <strong class="text-emerald-600 dark:text-emerald-400" x-text="formatRp(getHonorDibayar())"></strong>
                            </div>
                        </div>

                        {{-- Tombol Pilihan Aksi Pop Up --}}
                        <div class="flex items-center justify-center gap-3 pt-1">
                            <button type="button" @click="popOverLimitModal = false"
                                    class="w-1/2 py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition cursor-pointer">
                                Batal
                            </button>
                            <button type="button" @click="confirmAndSubmit()"
                                    class="w-1/2 py-2.5 px-4 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-md shadow-amber-600/30 transition cursor-pointer flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-check"></i>
                                Ya, Alokasikan
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>

    {{-- ==================== MODAL: IMPORT EXCEL (BANYAK MITRA) ==================== --}}
    <div x-show="importModal" x-cloak style="display:none;" 
         class="fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="importModal = false" x-show="importModal" x-transition.duration.200ms
             class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-lg overflow-hidden">
            
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-gradient-to-r from-emerald-500/10 to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <i class="fa-solid fa-file-excel text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">Import Alokasi Banyak Mitra via Excel</h3>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400">Input penugasan massal sekaligus</p>
                    </div>
                </div>
                <button @click="importModal = false" class="text-slate-400 hover:text-rose-500">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-6 space-y-4 text-xs">
                {{-- Langkah 1: Unduh Template --}}
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-bps-blue text-white flex items-center justify-center text-xs font-black shrink-0">1</div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 dark:text-slate-200 text-xs mb-1">Unduh Template Excel Alokasi Mitra</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-3">Kolom: Sobat-ID/Nama Mitra, Survei, Kegiatan, Posisi, Satuan, Target, Honor (IDR), Tanggal Mulai, Tanggal Selesai.</p>
                        <a href="{{ route('kinerja-mitra.alokasi.template') }}" target="_blank"
                           class="inline-flex items-center gap-2 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition">
                            <i class="fa-solid fa-download"></i>
                            <span>Unduh Template Excel</span>
                        </a>
                    </div>
                </div>

                {{-- Langkah 2: Upload File --}}
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-bps-blue text-white flex items-center justify-center text-xs font-black shrink-0">2</div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 dark:text-slate-200 text-xs mb-1">Unggah Berkas yang Sudah Diisi</p>
                        <form method="POST" action="{{ route('kinerja-mitra.alokasi.import') }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <div>
                                <input type="file" name="file_excel" accept=".xlsx,.xls" required
                                       class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-bps-faint file:text-bps-blue hover:file:bg-bps-blue hover:file:text-white file:transition-colors cursor-pointer border border-slate-200 dark:border-slate-700 rounded-xl py-1.5 bg-white dark:bg-slate-900">
                            </div>

                            <div class="p-3 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/50 rounded-xl text-[11px] text-amber-800 dark:text-amber-200">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                                <strong>Validasi Batas Honor:</strong> Jika total honor mitra melebihi batas maksimal bulan terkait, baris tersebut akan ditolak otomatis dan dilaporkan.
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" @click="importModal = false" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-bps-blue hover:bg-bps-dark text-white rounded-xl text-xs font-bold shadow-md">Proses Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ==================== MODAL: KONFIRMASI HAPUS ==================== --}}
    <div x-show="deleteModal" x-cloak style="display: none;" 
         class="fixed inset-0 z-[70] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="deleteModal = false" x-show="deleteModal" x-transition.duration.200ms 
             class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-sm overflow-hidden text-center p-6 space-y-4">
            <div class="w-14 h-14 rounded-full bg-rose-100 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl mx-auto">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 dark:text-white text-base">Hapus Alokasi Mitra?</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1" x-text="deleteNama"></p>
                <p class="text-[11px] text-rose-500 font-semibold mt-2">Data alokasi dan histori penilaian kegiatan ini akan dihapus permanen.</p>
            </div>
            <div class="flex items-center justify-center gap-3 pt-2">
                <button @click="deleteModal = false" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold">Batal</button>
                <form :action="'{{ url('kinerja-mitra/alokasi') }}/' + deleteId" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                        Ya, Hapus Alokasi
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#alokasiMitraTable .table-row').forEach(row => {
        const text = row.dataset.search || '';
        row.style.display = text.includes(search) ? '' : 'none';
    });
}
</script>
@endpush
@endsection
