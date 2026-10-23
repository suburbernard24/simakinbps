<!-- BEGIN: Modal Buku Tamu Digital PST -->
<div id="modal-buku-tamu" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative">
        <button onclick="tutupModalBukuTamu()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm transition">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-5">
            <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Buku Tamu Digital SAPA BPS</h3>
                <p class="text-xs text-slate-500">Registrasi Kunjungan Konsultasi &amp; Pelayanan Statistik Terpadu (PST)</p>
            </div>
        </div>
        <form onsubmit="event.preventDefault(); simpanBukuTamu();" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap</label>
                <input type="text" id="tamu-nama" required placeholder="Contoh: Maria Yosefina" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-600 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Instansi / Universitas</label>
                <input type="text" id="tamu-instansi" required placeholder="Contoh: Universitas Flores / Bappeda" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-600 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keperluan / Kebutuhan Data</label>
                <textarea id="tamu-keperluan" rows="3" required placeholder="Contoh: Konsultasi data PDRB dan kemiskinan untuk kajian skripsi..." class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-600 outline-none resize-none"></textarea>
            </div>
            <div class="pt-2 flex items-center justify-end gap-2">
                <button type="button" onclick="tutupModalBukuTamu()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow transition flex items-center gap-1.5">
                    <i class="fa-solid fa-paper-plane text-[10px]"></i>
                    <span>Kirim Buku Tamu</span>
                </button>
            </div>
        </form>
    </div>
</div>
<!-- END: Modal Buku Tamu Digital PST -->

<!-- BEGIN: Modal Token API BPS -->
<div id="modal-api-token" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative">
        <button onclick="tutupModalApiToken()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm transition">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-5">
            <div class="w-10 h-10 rounded-2xl bg-bps-100 text-bps-700 flex items-center justify-center text-lg">
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
                <div class="text-bps-700 font-bold">GET /api/bps/publikasi</div>
                <div class="text-bps-700 font-bold">GET /api/bps/indikator</div>
                <div class="text-slate-500">Domain Wilayah: 5310</div>
            </div>
            <p>Bagi pengembang sistem daerah (OPD/Pemkab/Akademisi), Anda dapat mendaftar dan memperoleh App Key langsung melalui portal pengembang BPS RI.</p>
            <div class="pt-2 flex items-center justify-end gap-2">
                <button onclick="tutupModalApiToken()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">Tutup</button>
                <a href="https://webapi.bps.go.id/developer" target="_blank" class="px-4 py-2 rounded-xl bg-bps-600 hover:bg-bps-700 text-white text-xs font-bold shadow transition flex items-center gap-1.5">
                    <span>Buka WebAPI Developer</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- END: Modal Token API BPS -->

<!-- BEGIN: Toast Notification -->
<div id="toast-notif" class="fixed bottom-6 right-20 z-50 hidden transform transition-all duration-300">
    <div class="px-4 py-3 rounded-2xl bg-slate-900 text-white shadow-2xl border border-slate-700 flex items-center gap-3">
        <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs">
            <i class="fa-solid fa-check"></i>
        </div>
        <div>
            <span class="block text-xs font-extrabold text-white" id="toast-title">Berhasil Disalin!</span>
            <span class="block text-[11px] text-slate-300" id="toast-body">Data siap digunakan.</span>
        </div>
    </div>
</div>
<!-- END: Toast Notification -->
