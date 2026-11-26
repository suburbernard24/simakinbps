@extends('layouts.app')

@section('title', 'Antrian & Pemanggilan PST')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                Antrian &amp; Pemanggilan Layanan PST
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Kelola antrian pengunjung hari ini dan panggil nomor antrian ke layar monitor PST.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <button type="button" id="btn-toggle-suara" onclick="toggleSuaraPanggilan()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 transition">
                <i id="icon-suara" class="fa-solid fa-volume-high text-bps-blue" aria-hidden="true"></i>
                <span id="label-suara">Suara Panggilan: Aktif</span>
            </button>
            <a href="{{ route('antrian') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-bps-blue hover:bg-blue-700 text-white text-xs font-semibold transition">
                <i class="fa-solid fa-display text-xs" aria-hidden="true"></i>
                <span>Buka Layar Monitor FE</span>
            </a>
        </div>
    </div>

    <!-- Sedang Dipanggil & Ringkasan -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-5 bg-[#123B63] text-white rounded-2xl p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between border-b border-white/15 pb-3">
                <span class="text-xs font-semibold text-blue-200">Sedang Dipanggil</span>
                <span id="admin-jumlah-menunggu" class="text-xs font-semibold bg-[#3686C8] text-white px-2.5 py-0.5 rounded-md">0 Menunggu</span>
            </div>

            <div class="py-6 text-center">
                <p id="admin-display-nomor" class="text-5xl sm:text-6xl font-extrabold tabular-nums tracking-tight">--</p>
                <p id="admin-display-loket" class="text-base font-bold text-blue-200 mt-3">Belum ada panggilan</p>
                <p id="admin-display-nama" class="text-xs text-slate-200 mt-1">-</p>
            </div>

            <div class="pt-3 border-t border-white/15 flex items-center justify-between text-[11px] text-slate-300">
                <span id="admin-display-layanan">-</span>
                <span id="admin-display-waktu">-</span>
            </div>
        </div>

        <div class="lg:col-span-7 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-4">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white">Riwayat Panggilan Hari Ini</h2>
                <button type="button" onclick="muatStatusAntrian()" class="text-xs font-semibold text-bps-blue hover:underline inline-flex items-center gap-1">
                    <i class="fa-solid fa-rotate text-[11px]" aria-hidden="true"></i>
                    <span>Refresh</span>
                </button>
            </div>
            <div id="admin-riwayat-list" class="space-y-2 text-xs">
                <p class="text-slate-400 py-6 text-center">Belum ada riwayat panggilan hari ini.</p>
            </div>
        </div>
    </div>

    <!-- Daftar Antrian Menunggu & Aktif -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800 mb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white">Daftar Antrian Pengunjung Hari Ini</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tekan <strong>Panggil</strong> untuk memanggil nomor antrian ke layar monitor PST.</p>
            </div>
        </div>

        <div id="admin-antrian-list" class="space-y-3">
            <p class="text-xs text-slate-500 py-6 text-center">Memuat daftar antrian...</p>
        </div>
    </div>
</div>

<script>
const adminCsrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
let suaraAktif = true;

function toggleSuaraPanggilan() {
    suaraAktif = !suaraAktif;
    const label = document.getElementById('label-suara');
    const icon  = document.getElementById('icon-suara');
    if (label && icon) {
        label.textContent = suaraAktif ? 'Suara Panggilan: Aktif' : 'Suara Panggilan: Nonaktif';
        icon.className = suaraAktif ? 'fa-solid fa-volume-high text-bps-blue' : 'fa-solid fa-volume-xmark text-slate-400';
    }
}

function playHospitalChime() {
    try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();
        [523.25, 659.25].forEach((freq, i) => {
            const osc  = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.value = freq;
            gain.gain.setValueAtTime(0.001, ctx.currentTime + i * 0.22);
            gain.gain.exponentialRampToValueAtTime(0.18, ctx.currentTime + i * 0.22 + 0.04);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.22 + 0.45);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(ctx.currentTime + i * 0.22);
            osc.stop(ctx.currentTime + i * 0.22 + 0.5);
        });
    } catch (e) {}
}

function ucapkanPanggilan(antrian) {
    if (!suaraAktif || !antrian) return;
    playHospitalChime();

    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        const ejaanNomor = antrian.nomor_antrian
            .replace('-', ' ')
            .split('')
            .map(ch => ch === '0' ? 'nol' : ch)
            .join(' ');
        const teks = `Nomor antrian ${ejaanNomor}, atas nama ${antrian.nama_pemohon}, silakan menuju ${antrian.loket}, ${antrian.nama_layanan}.`;
        const utter = new SpeechSynthesisUtterance(teks);
        utter.lang = 'id-ID';
        utter.rate = 0.92;
        setTimeout(() => window.speechSynthesis.speak(utter), 500);
    }
}

function formatJam(iso) {
    if (!iso) return '-';
    return new Date(iso).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WITA';
}

function renderAdminAntrian(data) {
    const active   = data.sedang_dipanggil;
    const menunggu = data.menunggu || [];
    const riwayat  = data.riwayat_dipanggil || [];

    document.getElementById('admin-jumlah-menunggu').textContent = `${data.jumlah_menunggu || 0} Menunggu`;

    if (active) {
        document.getElementById('admin-display-nomor').textContent   = active.nomor_antrian;
        document.getElementById('admin-display-loket').textContent   = active.loket;
        document.getElementById('admin-display-nama').textContent    = active.nama_pemohon;
        document.getElementById('admin-display-layanan').textContent = active.nama_layanan;
        document.getElementById('admin-display-waktu').textContent   = 'Dipanggil ' + formatJam(active.called_at);
    } else {
        document.getElementById('admin-display-nomor').textContent   = '--';
        document.getElementById('admin-display-loket').textContent   = 'Belum ada panggilan';
        document.getElementById('admin-display-nama').textContent    = '-';
        document.getElementById('admin-display-layanan').textContent = '-';
        document.getElementById('admin-display-waktu').textContent   = '-';
    }

    const riwayatEl = document.getElementById('admin-riwayat-list');
    if (riwayat.length === 0) {
        riwayatEl.innerHTML = '<p class="text-slate-400 py-6 text-center">Belum ada riwayat panggilan hari ini.</p>';
    } else {
        riwayatEl.innerHTML = riwayat.map(item => `
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 px-3.5 py-2.5 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="font-extrabold text-[#123B63] dark:text-blue-300 tabular-nums shrink-0">${item.nomor_antrian}</span>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-800 dark:text-slate-200 truncate">${item.nama_pemohon}</p>
                        <p class="text-[11px] text-slate-500 truncate">${item.loket} · ${item.nama_layanan}</p>
                    </div>
                </div>
                <span class="text-[11px] font-semibold px-2 py-0.5 rounded ${item.status === 'dipanggil' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'} shrink-0">
                    ${item.status === 'dipanggil' ? 'Dipanggil' : 'Selesai'}
                </span>
            </div>
        `).join('');
    }

    const listEl = document.getElementById('admin-antrian-list');
    const items = [];
    if (active) items.push(active);
    menunggu.forEach(m => items.push(m));

    if (items.length === 0) {
        listEl.innerHTML = '<p class="text-xs text-slate-500 py-6 text-center">Belum ada antrian menunggu hari ini.</p>';
    } else {
        listEl.innerHTML = items.map(item => {
            const isCalled = item.status === 'dipanggil';
            return `
                <div class="rounded-xl border ${isCalled ? 'border-blue-500 bg-blue-50/40 dark:bg-blue-950/20' : 'border-slate-200 dark:border-slate-800'} p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-12 rounded-lg ${isCalled ? 'bg-[#123B63] text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-white'} font-extrabold text-sm flex items-center justify-center tabular-nums shrink-0">
                            ${item.nomor_antrian}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-sm text-slate-900 dark:text-white">${item.nama_pemohon}</span>
                                <span class="text-[11px] font-semibold px-2 py-0.5 rounded ${isCalled ? 'bg-blue-600 text-white' : 'bg-amber-100 text-amber-800'}">
                                    ${isCalled ? 'Sedang Dilayani' : 'Menunggu'}
                                </span>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">${item.loket} · ${item.nama_layanan} · WA: ${item.no_wa}</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">${item.keperluan}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <button type="button" onclick="panggilAntrian(${item.id})" class="px-4 py-2 rounded-lg bg-[#1E5B91] hover:bg-[#123B63] text-white text-xs font-semibold transition inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-bullhorn text-[11px]" aria-hidden="true"></i>
                            <span>${isCalled ? 'Panggil Ulang' : 'Panggil'}</span>
                        </button>
                        ${isCalled ? `
                        <button type="button" onclick="selesaiAntrian(${item.id})" class="px-4 py-2 rounded-lg bg-white dark:bg-slate-800 hover:bg-slate-100 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-semibold transition">
                            Selesai
                        </button>
                        ` : ''}
                    </div>
                </div>
            `;
        }).join('');
    }
}

async function muatStatusAntrian() {
    try {
        const res = await fetch('/antrian/status', { headers: { 'Accept': 'application/json' } });
        if (!res.ok) return;
        const data = await res.json();
        renderAdminAntrian(data);
    } catch (e) {}
}

async function panggilAntrian(id) {
    try {
        const res = await fetch(`/admin/antrian/${id}/panggil`, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': adminCsrf },
        });
        if (!res.ok) return;
        const data = await res.json();
        ucapkanPanggilan(data.antrian);
        await muatStatusAntrian();
    } catch (e) {}
}

async function selesaiAntrian(id) {
    try {
        const res = await fetch(`/admin/antrian/${id}/selesai`, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': adminCsrf },
        });
        if (!res.ok) return;
        await muatStatusAntrian();
    } catch (e) {}
}

document.addEventListener('DOMContentLoaded', function() {
    muatStatusAntrian();
    setInterval(muatStatusAntrian, 5000);
});
</script>
@endsection
