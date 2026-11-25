<!-- =========================================================================
     MODAL 1: FORM BOOKING KONSULTASI ONLINE 24/7
========================================================================= -->
<div id="modal-booking-konsultasi" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative my-8">
        <!-- Close Button -->
        <button type="button" onclick="tutupModalKonsultasi()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm transition cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Header -->
        <div class="flex items-start gap-3.5 pb-4 border-b border-slate-100 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl shrink-0 shadow-xs">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider border border-emerald-100 mb-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Siaga Layanan 24 Jam / 7 Hari
                </div>
                <h3 class="text-base sm:text-lg font-extrabold text-slate-900">
                    Form Online Booking Konsultasi &amp; Permintaan Data
                </h3>
                <p class="text-xs text-slate-500">
                    Pelayanan Statistik Terpadu (PST) — BPS Kabupaten Manggarai
                </p>
            </div>
        </div>

        <form onsubmit="event.preventDefault(); simpanBookingKonsultasi();" class="space-y-4">
            <!-- Pertanyaan Utama: Status Jam Pengajuan (Dalam / Luar Jam Layanan) -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/90 space-y-2.5">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-800 uppercase tracking-wide">
                        <i class="fa-solid fa-clock text-[#0B4F82] mr-1.5"></i>
                        Status Waktu Pengajuan Konsultasi <span class="text-rose-500">*</span>
                    </label>
                    <span id="label-live-clock" class="text-[11px] font-mono text-slate-500 bg-white px-2 py-0.5 rounded border border-slate-200">
                        WITA
                    </span>
                </div>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Jam operasional kantor BPS: <strong class="text-slate-700">Senin – Jumat, pukul 08:00 – 15:30 WITA</strong>. Di luar jam tersebut, form booking online ini tetap aktif siaga 24/7 dan akan ditindaklanjuti pada hari kerja berikutnya.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <!-- Opsi 1: Dalam Jam Layanan -->
                    <label class="cursor-pointer">
                        <input type="radio" name="waktu_layanan" id="radio-jam-dalam" value="dalam" checked onchange="updateInfoJamLayanan()" class="peer sr-only">
                        <div class="p-3 rounded-xl border-2 border-slate-200 peer-checked:border-emerald-600 peer-checked:bg-emerald-50/50 transition-all">
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 rounded-full border-2 border-slate-300 peer-checked:border-emerald-600 flex items-center justify-center">
                                    <span class="w-2 h-2 rounded-full bg-emerald-600 hidden peer-checked:block"></span>
                                </span>
                                <span class="text-xs font-bold text-slate-800">Di Dalam Jam Layanan</span>
                            </div>
                            <p class="text-[10px] text-slate-500 mt-1 pl-6 leading-tight">Senin – Jumat, 08:00 – 15:30 WITA. Respons langsung hari ini.</p>
                        </div>
                    </label>

                    <!-- Opsi 2: Luar Jam Layanan (24/7) -->
                    <label class="cursor-pointer">
                        <input type="radio" name="waktu_layanan" id="radio-jam-luar" value="luar" onchange="updateInfoJamLayanan()" class="peer sr-only">
                        <div class="p-3 rounded-xl border-2 border-slate-200 peer-checked:border-sky-600 peer-checked:bg-sky-50/50 transition-all">
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 rounded-full border-2 border-slate-300 peer-checked:border-sky-600 flex items-center justify-center">
                                    <span class="w-2 h-2 rounded-full bg-sky-600 hidden peer-checked:block"></span>
                                </span>
                                <span class="text-xs font-bold text-slate-800">Di Luar Jam Layanan (24/7)</span>
                            </div>
                            <p class="text-[10px] text-slate-500 mt-1 pl-6 leading-tight">Malam hari / akhir pekan / libur. Booking dicatat 24/7.</p>
                        </div>
                    </label>
                </div>

                <!-- Dynamic Alert Banner for Luar Jam Layanan -->
                <div id="banner-luar-jam" class="hidden p-3 rounded-xl bg-sky-100/70 border border-sky-200 text-sky-900 text-xs flex items-start gap-2.5">
                    <i class="fa-solid fa-moon text-sky-600 text-sm mt-0.5 shrink-0"></i>
                    <p class="leading-relaxed text-[11px]">
                        <strong>Layanan Siaga 24/7:</strong> Anda mengirim permohonan di luar jam kerja operasional kantor. Permohonan Anda tetap kami terima secara online dan petugas PST BPS Kabupaten Manggarai akan segera menghubungi Anda kembali melalui WhatsApp/Email pada jam kerja berikutnya.
                    </p>
                </div>
            </div>

            <!-- Identitas Pemohon -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" id="konsul-nama" required placeholder="Contoh: Yohanes Bosco" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor WhatsApp / HP <span class="text-rose-500">*</span></label>
                    <input type="tel" id="konsul-kontak" required placeholder="Contoh: 081234567890" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" id="konsul-email" required placeholder="Contoh: yohanes@mail.com" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Instansi / Universitas / Lembaga <span class="text-rose-500">*</span></label>
                    <input type="text" id="konsul-instansi" required placeholder="Contoh: Bappeda Manggarai / Unika St. Paulus" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none">
                </div>
            </div>

            <!-- Preferensi Layanan & Topik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Moda Konsultasi yang Diinginkan <span class="text-rose-500">*</span></label>
                    <select id="konsul-moda" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white">
                        <option value="">-- Pilih Moda Konsultasi --</option>
                        <option value="Tatap Muka di PST BPS Manggarai">Tatap Muka di Kantor PST BPS Manggarai (Ruteng)</option>
                        <option value="Daring via Zoom / Google Meet">Daring via Video Conference (Zoom / Google Meet)</option>
                        <option value="Tertulis via WhatsApp Resmi PST">Layanan Tertulis Cepat via WhatsApp Resmi PST</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori Topik Konsultasi <span class="text-rose-500">*</span></label>
                    <select id="konsul-topik" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white">
                        <option value="">-- Pilih Topik Konsultasi --</option>
                        <option value="Statistik Sosial & Kependudukan (IPM, Kemiskinan, SP2020)">Statistik Sosial &amp; Kependudukan (IPM, Kemiskinan, SP)</option>
                        <option value="Statistik Ekonomi, PDRB & Inflasi">Statistik Ekonomi, PDRB &amp; Inflasi</option>
                        <option value="Statistik Pertanian & Sensus ST2023">Statistik Pertanian &amp; Sensus ST2023</option>
                        <option value="Rekomendasi Kegiatan Statistik (ROMANTIK OPD)">Rekomendasi Kegiatan Statistik (ROMANTIK OPD)</option>
                        <option value="Metodologi, Sampling & Permintaan Data Mikro">Metodologi, Sampling &amp; Permintaan Data</option>
                        <option value="Konsultasi Statistik Umum Lainnya">Konsultasi Statistik Umum Lainnya</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Rencana Tanggal Konsultasi</label>
                    <input type="date" id="konsul-tanggal" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Preferensi Waktu Konsultasi</label>
                    <select id="konsul-waktu" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white">
                        <option value="Pagi (09:00 - 11:30 WITA)">Pagi (09:00 – 11:30 WITA)</option>
                        <option value="Siang (13:30 - 15:00 WITA)">Siang (13:30 – 15:00 WITA)</option>
                        <option value="Fleksibel / Sesuai Kesepakatan">Fleksibel / Sesuai Kesepakatan</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Rincian Pertanyaan / Kebutuhan Data <span class="text-rose-500">*</span></label>
                <textarea id="konsul-rincian" rows="3" required placeholder="Jelaskan secara ringkas data apa yang dibutuhkan atau materi yang ingin dikonsultasikan..." class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none resize-none"></textarea>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="tutupModalKonsultasi()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Kirim Booking Konsultasi 24/7</span>
                </button>
            </div>
        </form>
    </div>
</div>
<!-- END: MODAL 1 -->


<!-- =========================================================================
     MODAL 2: BUKU TAMU DIGITAL PST BPS (REFER TO DIGILIB GUESTBOOK)
========================================================================= -->
<div id="modal-buku-tamu" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative my-8">
        <!-- Close Button -->
        <button type="button" onclick="tutupModalBukuTamu()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm transition cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Header -->
        <div class="flex items-start gap-3.5 pb-4 border-b border-slate-100 mb-5">
            <div class="w-12 h-12 rounded-2xl bg-[#0B4F82]/10 text-[#0B4F82] flex items-center justify-center text-xl shrink-0 shadow-xs">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-50 text-[#0B4F82] text-[10px] font-bold uppercase tracking-wider border border-blue-100 mb-1">
                    <span>Standar Digilib Guestbook BPS</span>
                </div>
                <h3 class="text-base sm:text-lg font-extrabold text-slate-900">
                    Buku Tamu Digital PST BPS Kabupaten Manggarai
                </h3>
                <p class="text-xs text-slate-500">
                    Pencatatan resmi data kunjungan konsumen statistik sesuai standar Perpustakaan Digital BPS RI
                </p>
            </div>
        </div>

        <form onsubmit="event.preventDefault(); simpanBukuTamuLengkap();" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" id="tamu-nama-lengkap" required placeholder="Contoh: Maria Yosefina" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select id="tamu-gender" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] outline-none bg-white">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor WhatsApp / HP <span class="text-rose-500">*</span></label>
                    <input type="tel" id="tamu-kontak-lengkap" required placeholder="Contoh: 081234567890" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" id="tamu-email-lengkap" required placeholder="Contoh: maria@domain.com" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori Pengguna <span class="text-rose-500">*</span></label>
                    <select id="tamu-kategori-lengkap" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] outline-none bg-white">
                        <option value="">-- Pilih Kategori Pengguna --</option>
                        <option value="ASN / Lembaga Pemerintah / OPD Pemda">ASN / Lembaga Pemerintah / OPD Pemda</option>
                        <option value="Mahasiswa / Pelajar">Mahasiswa / Pelajar</option>
                        <option value="Dosen / Peneliti / Akademisi">Dosen / Peneliti / Akademisi</option>
                        <option value="Lembaga Swasta / BUMN / BUMD">Lembaga Swasta / BUMN / BUMD</option>
                        <option value="LSM / Media Massa / Pers">LSM / Media Massa / Pers</option>
                        <option value="Masyarakat Umum">Masyarakat Umum</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pendidikan Terakhir <span class="text-rose-500">*</span></label>
                    <select id="tamu-pendidikan-lengkap" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] outline-none bg-white">
                        <option value="">-- Pilih Pendidikan Terakhir --</option>
                        <option value="SMA / SMK / Sederajat">SMA / SMK / Sederajat</option>
                        <option value="Diploma (D1/D2/D3)">Diploma (D1 / D2 / D3)</option>
                        <option value="Sarjana (S1 / D4)">Sarjana (S1 / D4)</option>
                        <option value="Magister (S2)">Magister (S2)</option>
                        <option value="Doktor (S3)">Doktor (S3)</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Instansi / Perguruan Tinggi / Lembaga <span class="text-rose-500">*</span></label>
                <input type="text" id="tamu-instansi-lengkap" required placeholder="Contoh: Universitas Flores / Bappeda Litbang Kab. Manggarai" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kebutuhan Data / Layanan <span class="text-rose-500">*</span></label>
                    <textarea id="tamu-kebutuhan-lengkap" rows="2" required placeholder="Contoh: Permintaan tabel data PDRB per kapita dan angka kemiskinan..." class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] outline-none resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Rencana Pemanfaatan Data</label>
                    <textarea id="tamu-pemanfaatan-lengkap" rows="2" placeholder="Contoh: Penyusunan skripsi, analisis kebijakan pembangunan daerah, referensi bisnis..." class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] outline-none resize-none"></textarea>
                </div>
            </div>

            <!-- Penilaian Kepuasan Pelayanan PST (Bintang 1-5) -->
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase">
                        Penilaian Kepuasan Layanan PST (IKK)
                    </label>
                    <div class="flex items-center gap-1.5" id="rating-stars-container">
                        <button type="button" onclick="setTamuRating(1)" class="text-slate-300 hover:text-amber-400 text-lg transition rating-star" data-val="1"><i class="fa-solid fa-star"></i></button>
                        <button type="button" onclick="setTamuRating(2)" class="text-slate-300 hover:text-amber-400 text-lg transition rating-star" data-val="2"><i class="fa-solid fa-star"></i></button>
                        <button type="button" onclick="setTamuRating(3)" class="text-slate-300 hover:text-amber-400 text-lg transition rating-star" data-val="3"><i class="fa-solid fa-star"></i></button>
                        <button type="button" onclick="setTamuRating(4)" class="text-amber-400 text-lg transition rating-star" data-val="4"><i class="fa-solid fa-star"></i></button>
                        <button type="button" onclick="setTamuRating(5)" class="text-amber-400 text-lg transition rating-star" data-val="5"><i class="fa-solid fa-star"></i></button>
                        <span id="rating-text" class="text-xs font-bold text-amber-700 ml-2">Sangat Puas (5/5)</span>
                    </div>
                </div>
                <input type="hidden" id="tamu-rating-val" value="5">
                <div class="mt-2.5">
                    <input type="text" id="tamu-saran-lengkap" placeholder="Kritik, saran, atau masukan untuk peningkatan pelayanan PST BPS Manggarai (opsional)..." class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0B4F82] outline-none bg-white">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="tutupModalBukuTamu()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-check-double text-xs"></i>
                    <span>Kirim Buku Tamu</span>
                </button>
            </div>
        </form>
    </div>
</div>
<!-- END: MODAL 2 -->


<!-- =========================================================================
     MODAL 3: TOKEN API BPS WEBAPI V2
========================================================================= -->
<div id="modal-api-token" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative">
        <button onclick="tutupModalApiToken()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm transition cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-5">
            <div class="w-10 h-10 rounded-2xl bg-sky-100 text-[#0B4F82] flex items-center justify-center text-lg">
                <i class="fa-solid fa-key"></i>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Akses BPS WebAPI v2</h3>
                <p class="text-xs text-slate-500">Domain Wilayah: 5310 (BPS Kabupaten Manggarai)</p>
            </div>
        </div>
        <div class="space-y-4 text-xs text-slate-600">
            <p>Endpoint publikasi, indikator makro, dan katalog tabel BPS Kabupaten Manggarai terhubung langsung melalui gateway resmi <strong>webapi.bps.go.id</strong>.</p>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 font-mono text-[11px] text-slate-700 space-y-1">
                <div class="text-[#0B4F82] font-bold">GET /api/bps/publikasi</div>
                <div class="text-[#0B4F82] font-bold">GET /api/bps/indikator</div>
                <div class="text-slate-500">Domain Wilayah: 5310 (Kab. Manggarai)</div>
            </div>
            <p>Bagi pengembang sistem daerah (OPD/Pemkab/Akademisi), Anda dapat mendaftar dan memperoleh App Key langsung melalui portal pengembang BPS RI.</p>
            <div class="pt-2 flex items-center justify-end gap-2">
                <button onclick="tutupModalApiToken()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">Tutup</button>
                <a href="https://webapi.bps.go.id/developer" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-xl bg-[#0B4F82] hover:bg-[#083E67] text-white text-xs font-bold shadow transition flex items-center gap-1.5">
                    <span>Buka WebAPI Developer</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- END: MODAL 3 -->


<!-- =========================================================================
     TOAST NOTIFICATION FLOATING
========================================================================= -->
<div id="toast-notif" class="fixed bottom-6 right-20 z-50 hidden transform transition-all duration-300">
    <div class="px-5 py-3 rounded-2xl bg-slate-900 text-white shadow-2xl border border-slate-700 flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-sm shrink-0">
            <i class="fa-solid fa-check"></i>
        </div>
        <div>
            <span class="block text-xs font-extrabold text-white" id="toast-title">Berhasil Disalin!</span>
            <span class="block text-[11px] text-slate-300" id="toast-body">Data siap digunakan.</span>
        </div>
    </div>
</div>
