<!-- BEGIN: BukuTamuSection -->
<section class="reveal-on-scroll py-14 sm:py-18 bg-[#F4F7FA] border-b border-slate-200/80" data-purpose="buku-tamu" id="buku-tamu">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            <!-- Left Information Column -->
            <div class="lg:col-span-5 space-y-4">
                <h2 class="text-2xl sm:text-3xl font-bold text-[#123B63] tracking-tight leading-tight">
                    Buku Tamu Digital Pelayanan Statistik Terpadu
                </h2>

                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Pencatatan kehadiran pengunjung Pelayanan Statistik Terpadu (PST) BPS Kabupaten Manggarai sesuai standar <em class="italic">digilib guestbook</em> BPS RI.
                </p>

                <div class="pt-2 flex flex-wrap items-center gap-3">
                    <button type="button" onclick="bukaModalBukuTamu()" class="inline-flex items-center gap-2 px-4 py-2.5 min-h-[40px] rounded-lg bg-[#1E5B91] hover:bg-[#123B63] text-white font-semibold text-xs transition-colors">
                        <span>Buka Form Lengkap</span>
                    </button>
                    <a href="https://perpustakaan.bps.go.id/digilib/guestbook" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2.5 min-h-[40px] rounded-lg bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200 transition-colors">
                        <span>Digilib BPS RI</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <!-- Right Quick Interactive Guest Form -->
            <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200/80 p-6 sm:p-8">
                <h3 class="text-sm font-bold text-[#123B63] pb-4 border-b border-slate-100 mb-5">Form Cepat Buku Tamu PST</h3>

                <form onsubmit="event.preventDefault(); simpanBukuTamuInline();" id="form-inline-buku-tamu" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="inline-tamu-nama" class="block text-xs font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-600">*</span></label>
                            <input type="text" id="inline-tamu-nama" required placeholder="Masukkan nama lengkap" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-200 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] transition-colors">
                        </div>
                        <div>
                            <label for="inline-tamu-gender" class="block text-xs font-medium text-slate-700 mb-1.5">Jenis Kelamin <span class="text-rose-600">*</span></label>
                            <select id="inline-tamu-gender" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-200 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] transition-colors bg-white">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="inline-tamu-kontak" class="block text-xs font-medium text-slate-700 mb-1.5">Nomor WhatsApp / HP <span class="text-rose-600">*</span></label>
                            <input type="tel" id="inline-tamu-kontak" required placeholder="08xxxxxxxxxx" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-200 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] transition-colors">
                        </div>
                        <div>
                            <label for="inline-tamu-kategori" class="block text-xs font-medium text-slate-700 mb-1.5">Kategori Pengguna <span class="text-rose-600">*</span></label>
                            <select id="inline-tamu-kategori" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-200 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] transition-colors bg-white">
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
                            <label for="inline-tamu-instansi" class="block text-xs font-medium text-slate-700 mb-1.5">Instansi / Universitas <span class="text-rose-600">*</span></label>
                            <input type="text" id="inline-tamu-instansi" required placeholder="Nama instansi atau universitas" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-200 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] transition-colors">
                        </div>
                        <div>
                            <label for="inline-tamu-pendidikan" class="block text-xs font-medium text-slate-700 mb-1.5">Pendidikan Terakhir <span class="text-rose-600">*</span></label>
                            <select id="inline-tamu-pendidikan" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-200 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] transition-colors bg-white">
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
                        <label for="inline-tamu-keperluan" class="block text-xs font-medium text-slate-700 mb-1.5">Kebutuhan Data / Keperluan Layanan <span class="text-rose-600">*</span></label>
                        <textarea id="inline-tamu-keperluan" rows="2" required placeholder="Tuliskan data atau layanan yang Anda butuhkan..." class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-200 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] resize-none transition-colors"></textarea>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 min-h-[40px] rounded-lg bg-[#1E5B91] hover:bg-[#123B63] text-white text-xs font-semibold transition-colors">
                            Kirim Buku Tamu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
