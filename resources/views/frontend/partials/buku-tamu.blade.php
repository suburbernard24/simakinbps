<!-- BEGIN: BukuTamuSection -->
<section class="py-14 sm:py-18 bg-[#F4F7FA] border-b border-slate-200" data-purpose="buku-tamu" id="buku-tamu">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
            <!-- Left Information Column -->
            <div class="lg:col-span-5 space-y-4">
                <p class="text-xs font-semibold text-[#1E5B91]">
                    Buku Tamu PST BPS
                </p>

                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#123B63] tracking-tight leading-tight">
                    Buku Tamu Digital Pelayanan Statistik Terpadu
                </h2>

                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Pencatatan kehadiran dan data pengunjung resmi Pelayanan Statistik Terpadu (PST) BPS Kabupaten Manggarai, merujuk pada standar Buku Tamu Digital Perpustakaan BPS RI (<em class="italic">digilib guestbook</em>).
                </p>

                <div class="pt-2 border-t border-slate-200 space-y-2 text-xs">
                    <p class="font-semibold text-[#123B63]">Tujuan Pencatatan Pengunjung:</p>
                    <ul class="space-y-1.5 text-slate-600 pl-4 list-disc leading-relaxed">
                        <li>Memetakan kebutuhan dan karakteristik konsumen data statistik di Manggarai.</li>
                        <li>Mengukur Indeks Kepuasan Konsumen (IKK) dan Kebutuhan Data (SKD).</li>
                        <li>Menjamin pelayanan yang akuntabel, transparan, dan berkelanjutan.</li>
                    </ul>
                </div>

                <div class="pt-3 flex flex-wrap items-center gap-3">
                    <button type="button" onclick="bukaModalBukuTamu()" class="inline-flex items-center gap-2 px-4 py-2.5 min-h-[40px] rounded-md bg-[#1E5B91] hover:bg-[#123B63] text-white font-semibold text-xs transition-colors">
                        <i class="fa-solid fa-pen-nib" aria-hidden="true"></i>
                        <span>Buka Form Buku Tamu Lengkap</span>
                    </button>
                    <a href="https://perpustakaan.bps.go.id/digilib/guestbook" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2.5 min-h-[40px] rounded-md bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-300 transition-colors">
                        <span>Digilib BPS RI</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-500" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <!-- Right Quick Interactive Guest Form (Direct Inline Card) -->
            <div class="lg:col-span-7 bg-white rounded-lg border border-slate-200 p-6 sm:p-8">
                <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-5">
                    <h3 class="text-sm font-bold text-[#123B63]">Form Cepat Buku Tamu PST</h3>
                    <span class="text-xs font-medium text-slate-500">NATAS Manggarai</span>
                </div>

                <form onsubmit="event.preventDefault(); simpanBukuTamuInline();" id="form-inline-buku-tamu" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="inline-tamu-nama" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-600">*</span></label>
                            <input type="text" id="inline-tamu-nama" required placeholder="Masukkan nama lengkap" class="w-full text-xs px-3.5 py-2.5 rounded-md border border-slate-300 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] transition-colors">
                        </div>
                        <div>
                            <label for="inline-tamu-gender" class="block text-xs font-semibold text-slate-700 mb-1.5">Jenis Kelamin <span class="text-rose-600">*</span></label>
                            <select id="inline-tamu-gender" required class="w-full text-xs px-3.5 py-2.5 rounded-md border border-slate-300 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] transition-colors bg-white">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="inline-tamu-kontak" class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor WhatsApp / HP <span class="text-rose-600">*</span></label>
                            <input type="tel" id="inline-tamu-kontak" required placeholder="08xxxxxxxxxx" class="w-full text-xs px-3.5 py-2.5 rounded-md border border-slate-300 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] transition-colors">
                        </div>
                        <div>
                            <label for="inline-tamu-kategori" class="block text-xs font-semibold text-slate-700 mb-1.5">Kategori Pengguna <span class="text-rose-600">*</span></label>
                            <select id="inline-tamu-kategori" required class="w-full text-xs px-3.5 py-2.5 rounded-md border border-slate-300 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] transition-colors bg-white">
                                <option value="">Pilih Kategori Pengguna</option>
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
                            <label for="inline-tamu-instansi" class="block text-xs font-semibold text-slate-700 mb-1.5">Instansi / Universitas <span class="text-rose-600">*</span></label>
                            <input type="text" id="inline-tamu-instansi" required placeholder="Nama instansi atau universitas" class="w-full text-xs px-3.5 py-2.5 rounded-md border border-slate-300 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] transition-colors">
                        </div>
                        <div>
                            <label for="inline-tamu-pendidikan" class="block text-xs font-semibold text-slate-700 mb-1.5">Pendidikan Terakhir <span class="text-rose-600">*</span></label>
                            <select id="inline-tamu-pendidikan" required class="w-full text-xs px-3.5 py-2.5 rounded-md border border-slate-300 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] transition-colors bg-white">
                                <option value="">Pilih Pendidikan</option>
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
                        <label for="inline-tamu-keperluan" class="block text-xs font-semibold text-slate-700 mb-1.5">Kebutuhan Data / Keperluan Layanan <span class="text-rose-600">*</span></label>
                        <textarea id="inline-tamu-keperluan" rows="2" required placeholder="Tuliskan data atau layanan yang Anda butuhkan..." class="w-full text-xs px-3.5 py-2.5 rounded-md border border-slate-300 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] resize-none transition-colors"></textarea>
                    </div>

                    <div class="pt-2 flex flex-wrap items-center justify-between gap-4">
                        <button type="button" onclick="bukaModalBukuTamu()" class="text-xs text-[#1E5B91] hover:text-[#123B63] font-semibold underline underline-offset-2">
                            Butuh opsi penilaian kepuasan &amp; saran lengkap?
                        </button>
                        <button type="submit" class="px-5 py-2.5 min-h-[40px] rounded-md bg-[#1E5B91] hover:bg-[#123B63] text-white text-xs font-semibold transition-colors inline-flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane text-xs" aria-hidden="true"></i>
                            <span>Kirim Buku Tamu</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- END: BukuTamuSection -->
