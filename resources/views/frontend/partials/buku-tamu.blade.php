<!-- BEGIN: BukuTamuSection -->
<section class="py-16 sm:py-20 bg-white border-b border-slate-200/70 relative" data-purpose="buku-tamu" id="buku-tamu">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-slate-50 via-white to-blue-50/40 rounded-3xl border-2 border-slate-200/80 p-6 sm:p-10 lg:p-12 shadow-sm relative overflow-hidden">
            <!-- Background Watermark -->
            <div class="absolute -right-12 -bottom-12 opacity-5 pointer-events-none text-slate-800">
                <i class="fa-solid fa-address-book text-[260px]"></i>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                <!-- Left Information Column -->
                <div class="lg:col-span-5 space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 tracking-wide uppercase">
                        <i class="fa-solid fa-clipboard-check"></i>
                        <span>Buku Tamu PST BPS</span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#172B4D] tracking-tight leading-tight">
                        Buku Tamu Digital Pelayanan Statistik Terpadu
                    </h2>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Pencatatan kehadiran dan data pengunjung resmi Pelayanan Statistik Terpadu (PST) BPS Kabupaten Manggarai, merujuk pada standar Buku Tamu Digital Perpustakaan BPS RI (<em class="italic">digilib guestbook</em>).
                    </p>

                    <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs space-y-2 text-xs">
                        <div class="flex items-center gap-2 text-slate-700 font-semibold">
                            <i class="fa-solid fa-circle-info text-sky-600"></i>
                            <span>Tujuan Pencatatan Pengunjung:</span>
                        </div>
                        <ul class="space-y-1.5 text-slate-500 pl-6 list-disc text-[11px] leading-relaxed">
                            <li>Memetakan kebutuhan dan karakteristik konsumen data statistik di Manggarai.</li>
                            <li>Mengukur Indeks Kepuasan Konsumen (IKK) dan Kebutuhan Data (SKD).</li>
                            <li>Menjamin pelayanan yang akuntabel, transparan, dan berkelanjutan.</li>
                        </ul>
                    </div>

                    <div class="pt-2 flex flex-wrap items-center gap-3">
                        <button type="button" onclick="bukaModalBukuTamu()" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition-all active:scale-[0.98] cursor-pointer">
                            <i class="fa-solid fa-pen-nib"></i>
                            <span>Buka Form Buku Tamu Lengkap</span>
                        </button>
                        <a href="https://perpustakaan.bps.go.id/digilib/guestbook" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-3 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200 transition-all">
                            <span>Digilib BPS RI</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
                        </a>
                    </div>
                </div>

                <!-- Right Quick Interactive Guest Form (Direct Inline Card) -->
                <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-md">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold">
                                <i class="fa-solid fa-user-pen"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900">Form Cepat Buku Tamu PST</h3>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">NATAS Manggarai</span>
                    </div>

                    <form onsubmit="event.preventDefault(); simpanBukuTamuInline();" id="form-inline-buku-tamu" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" id="inline-tamu-nama" required placeholder="Contoh: Maria Yosefina" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] focus:border-transparent outline-none transition">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                                <select id="inline-tamu-gender" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] focus:border-transparent outline-none transition bg-white">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Nomor WhatsApp / HP <span class="text-rose-500">*</span></label>
                                <input type="tel" id="inline-tamu-kontak" required placeholder="Contoh: 081234567890" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] focus:border-transparent outline-none transition">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Kategori Pengguna <span class="text-rose-500">*</span></label>
                                <select id="inline-tamu-kategori" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] focus:border-transparent outline-none transition bg-white">
                                    <option value="">-- Pilih Kategori Pengguna --</option>
                                    <option value="ASN/OPD Pemda">ASN / OPD Pemerintah Daerah</option>
                                    <option value="Mahasiswa/Pelajar">Mahasiswa / Pelajar</option>
                                    <option value="Dosen/Peneliti">Dosen / Peneliti / Akademisi</option>
                                    <option value="Swasta/BUMN">Lembaga Swasta / BUMN / BUMD</option>
                                    <option value="LSM/Media">LSM / Pers / Media</option>
                                    <option value="Masyarakat Umum">Masyarakat Umum</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Instansi / Universitas <span class="text-rose-500">*</span></label>
                                <input type="text" id="inline-tamu-instansi" required placeholder="Contoh: Universitas Flores / Bappeda" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] focus:border-transparent outline-none transition">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Pendidikan Terakhir <span class="text-rose-500">*</span></label>
                                <select id="inline-tamu-pendidikan" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] focus:border-transparent outline-none transition bg-white">
                                    <option value="">-- Pilih Pendidikan --</option>
                                    <option value="SMA/SMK/Sederajat">SMA / SMK / Sederajat</option>
                                    <option value="Diploma (D1/D2/D3)">Diploma (D1 / D2 / D3)</option>
                                    <option value="Sarjana (S1/D4)">Sarjana (S1 / D4)</option>
                                    <option value="Magister (S2)">Magister (S2)</option>
                                    <option value="Doktor (S3)">Doktor (S3)</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Kebutuhan Data / Keperluan Layanan <span class="text-rose-500">*</span></label>
                            <textarea id="inline-tamu-keperluan" rows="2" required placeholder="Contoh: Permintaan data PDRB per kapita dan angka kemiskinan tahun 2021-2024 untuk penyusunan kajian skripsi..." class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] focus:border-transparent outline-none resize-none transition"></textarea>
                        </div>

                        <div class="pt-2 flex items-center justify-between gap-4">
                            <button type="button" onclick="bukaModalBukuTamu()" class="text-xs text-[#0B4F82] hover:text-[#083E67] font-semibold underline underline-offset-2">
                                Butuh opsi penilaian kepuasan &amp; saran lengkap?
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition flex items-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                                <span>Kirim Buku Tamu</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END: BukuTamuSection -->
