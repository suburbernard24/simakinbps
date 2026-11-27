{{--
    Partial: Pengumuman Card
    Tampil di: dashboard seluruh role (admin, kepala_bps, ketua_tim, pegawai)
    Data: $pengumumanAktif (di-share via AppServiceProvider)
--}}
@php
    use Carbon\Carbon;
    $today    = Carbon::today();
    $listPeng = isset($pengumumanAktif) ? $pengumumanAktif : collect();

    // Fungsi label relatif berdasarkan tanggal_mulai (atau created_at jika null)
    $getLabel = function($p) use ($today) {
        $ref = $p->tanggal_mulai ?? $p->created_at->startOfDay();
        $diff = $today->diffInDays($ref, false); // positif = masa depan, negatif = masa lalu
        if ($diff === 0)  return ['text' => 'Hari ini',  'class' => 'bg-blue-100 text-blue-700'];
        if ($diff === -1) return ['text' => 'Kemarin',   'class' => 'bg-slate-100 text-slate-500'];
        if ($diff === 1)  return ['text' => 'Besok',     'class' => 'bg-amber-100 text-amber-700'];
        if ($diff < 0)   return ['text' => abs((int)$diff) . ' hari lalu', 'class' => 'bg-slate-100 text-slate-500'];
        return ['text' => (int)$diff . ' hari lagi', 'class' => 'bg-violet-100 text-violet-700'];
    };
@endphp

<div class="bg-white rounded-2xl shadow-glass border border-slate-200/60 overflow-hidden flex flex-col">

    {{-- Header --}}
    <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-bps-faint/80 to-transparent flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-bps-blue flex items-center justify-center shrink-0">
                <i class="fa-solid fa-megaphone text-white text-xs"></i>
            </div>
            <div>
                <p class="text-xs font-extrabold text-slate-800">Pengumuman</p>
                <p class="text-[10px] text-slate-400 font-medium">
                    {{ Carbon::now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
        </div>
        @if($listPeng->isNotEmpty())
        <span class="text-[10px] font-extrabold bg-bps-blue text-white px-2 py-0.5 rounded-full">
            {{ $listPeng->count() }}
        </span>
        @endif
    </div>

    {{-- Content --}}
    <div class="flex-1 divide-y divide-slate-100 overflow-y-auto max-h-[420px]">

        @forelse($listPeng as $p)
        @php
            $label = $getLabel($p);
            $tipeColor = match($p->tipe) {
                'sukses'    => ['bar' => 'bg-emerald-500', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'icon' => 'fa-circle-check text-emerald-500'],
                'peringatan'=> ['bar' => 'bg-amber-500',   'badge' => 'bg-amber-50 text-amber-700 border-amber-200',     'icon' => 'fa-triangle-exclamation text-amber-500'],
                'bahaya'    => ['bar' => 'bg-rose-500',    'badge' => 'bg-rose-50 text-rose-700 border-rose-200',         'icon' => 'fa-circle-xmark text-rose-500'],
                default     => ['bar' => 'bg-bps-blue',   'badge' => 'bg-blue-50 text-blue-700 border-blue-200',         'icon' => 'fa-circle-info text-blue-500'],
            };
        @endphp
        <div class="flex gap-0 group">
            {{-- Color bar kiri --}}
            <div class="w-1 {{ $tipeColor['bar'] }} shrink-0 rounded-bl-none"></div>
            <div class="flex-1 px-4 py-3.5">
                {{-- Label relatif --}}
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-wide {{ $label['class'] }}">
                        <i class="fa-regular fa-clock text-[8px]"></i>
                        {{ $label['text'] }}
                    </span>
                    <span class="text-[9px] text-slate-400">
                        {{ ($p->tanggal_mulai ?? $p->created_at)->translatedFormat('d M Y') }}
                    </span>
                </div>

                {{-- Judul --}}
                <div class="flex items-start gap-2 mb-1">
                    <i class="fa-solid {{ $tipeColor['icon'] }} text-xs mt-0.5 shrink-0"></i>
                    <p class="text-xs font-bold text-slate-800 leading-tight">{{ $p->judul }}</p>
                </div>

                {{-- Isi --}}
                <p class="text-[11px] text-slate-500 leading-relaxed pl-5">{{ $p->isi }}</p>
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center py-10 px-6 text-center">
            <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center mb-3">
                <i class="fa-regular fa-bell-slash text-xl text-slate-300"></i>
            </div>
            <p class="text-xs font-bold text-slate-500">Tidak ada pengumuman</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Semua informasi terkini akan muncul di sini.</p>
        </div>
        @endforelse
    </div>

    {{-- Footer --}}
    @if($listPeng->count() > 3)
    <div class="px-5 py-2.5 border-t border-slate-100 bg-slate-50/60 text-center">
        <p class="text-[10px] text-slate-400">Menampilkan {{ $listPeng->count() }} pengumuman aktif</p>
    </div>
    @endif
</div>
