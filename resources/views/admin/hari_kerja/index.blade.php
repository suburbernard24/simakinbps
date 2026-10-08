@extends('layouts.app')

@section('title', 'Hari Kerja Aktif Bulanan')
@section('page_title', 'Hari Kerja Aktif Bulanan')

@section('content')
<div class="space-y-6" x-data="{ modalEdit: false, editData: {} }">

    <!-- Top Card & Year Filter -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-lg font-black text-slate-900">Pengaturan Hari Kerja Aktif Bulanan</h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Jumlah hari kerja aktif bulanan menjadi dasar pembagi (<em class="italic">denominator</em>) dalam perhitungan skor kinerja pegawai.
            </p>
        </div>

        <form method="GET" action="{{ route('admin.hari_kerja.index') }}" class="flex items-center gap-2">
            <label class="text-xs font-bold text-slate-500 uppercase shrink-0">Tahun:</label>
            {{-- Tombol tahun sebelumnya --}}
            <a href="{{ route('admin.hari_kerja.index', ['tahun' => $tahun - 1]) }}"
               class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 transition"
               title="Tahun sebelumnya">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </a>
            {{-- Input tahun manual --}}
            <input type="number" name="tahun" value="{{ $tahun }}"
                   min="2020" max="2099"
                   class="w-24 text-sm font-black text-center px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50"
                   onchange="this.form.submit()">
            {{-- Tombol tahun berikutnya --}}
            <a href="{{ route('admin.hari_kerja.index', ['tahun' => $tahun + 1]) }}"
               class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 transition"
               title="Tahun berikutnya">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
            {{-- Tombol pergi ke tahun yang diketik --}}
            <button type="submit"
                class="px-3 py-2 rounded-xl bg-bps-blue text-white text-xs font-bold hover:bg-bps-light transition">
                Tampilkan
            </button>
        </form>
    </div>

    <!-- Formula Info Banner -->
    <div class="p-4 rounded-2xl bg-bps-faint border border-bps-pale text-slate-700 text-xs flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-bps-blue text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-calculator"></i>
        </div>
        <div>
            <p class="font-bold text-bps-dark">Rumus Konsistensi Basis Waktu:</p>
            <p class="text-slate-600">
                <strong>Total Jam Kerja Aktif Bulanan</strong> = <code>Hari Kerja Aktif × Jam Kerja Efektif per Hari (7.5 Jam)</code>. 
                Skor Kinerja Pegawai dihitung dari: <code>(Total Estimasi Jam Kerja Pegawai / Total Jam Kerja Aktif Bulanan) × 100%</code>.
            </p>
        </div>
    </div>

    <!-- 12 Months Grid Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-extrabold uppercase text-slate-500 tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">No</th>
                        <th class="py-3.5 px-4">Bulan</th>
                        <th class="py-3.5 px-4 text-center">Hari Kerja Aktif</th>
                        <th class="py-3.5 px-4 text-center">Jam Kerja / Hari</th>
                        <th class="py-3.5 px-4 text-center">Total Jam Bulanan</th>
                        <th class="py-3.5 px-4">Keterangan</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($daftarHariKerja as $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-400">{{ $item['bulan_angka'] }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 text-sm">{{ $item['bulan_nama'] }} {{ $tahun }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-3 py-1 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 font-extrabold text-xs">
                                    {{ $item['hari_kerja'] }} Hari
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-700">
                                {{ $item['jam_per_hari'] }} Jam
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-3 py-1 rounded-xl bg-bps-faint text-bps-blue border border-bps-pale font-extrabold text-xs">
                                    {{ $item['total_jam'] }} Jam
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                                {{ $item['keterangan'] ?: 'Hari kerja reguler BPS' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <button @click="editData = {{ json_encode($item) }}; editData.tahun = {{ $tahun }}; modalEdit = true"
                                        class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-bps-blue hover:text-white font-bold text-slate-700 text-xs transition">
                                    <i class="fa-solid fa-pen mr-1"></i> Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit Hari Kerja -->
    <div x-show="modalEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="modalEdit = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Ubah Hari Kerja Aktif</h3>
                    <p class="text-xs text-slate-500" x-text="editData.bulan_nama + ' ' + editData.tahun"></p>
                </div>
                <button @click="modalEdit = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form method="POST" action="{{ route('admin.hari_kerja.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="tahun" :value="editData.tahun">
                <input type="hidden" name="bulan" :value="editData.bulan_angka">

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jumlah Hari Kerja Aktif <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah_hari_kerja" min="1" max="31" x-model="editData.hari_kerja" required class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    <p class="text-[10px] text-slate-400 mt-1">Contoh: 22 hari aktif</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Kerja Efektif per Hari <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.25" min="1" max="24" name="jam_kerja_per_hari" x-model="editData.jam_per_hari" required class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    <p class="text-[10px] text-slate-400 mt-1">Standar BPS: 7.50 jam / hari (37.5 jam/minggu)</p>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-600">
                    <p class="text-[11px] font-bold text-slate-700">Total Jam Kerja Bulan Tersebut:</p>
                    <p class="text-lg font-black text-bps-blue mt-0.5" x-text="(editData.hari_kerja * editData.jam_per_hari).toFixed(1) + ' Jam'"></p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Keterangan / Catatan Libur</label>
                    <input type="text" name="keterangan" x-model="editData.keterangan" placeholder="Contoh: Dipotong libur nasional & cuti bersama" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="modalEdit = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-bps-blue text-white text-xs font-bold shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
