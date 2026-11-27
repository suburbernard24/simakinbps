{{--
    Pengumuman: Fixed Floating Widget (pojok kanan bawah)
    Muncul di semua halaman, sticky tidak ikut scroll
    Dipanggil dari layouts/app.blade.php
--}}
@php
    use Carbon\Carbon;
    $today    = Carbon::today();
    $listPeng = isset($pengumumanAktif) ? $pengumumanAktif : collect();

    $getLabel = function($p) use ($today) {
        $ref  = ($p->tanggal_mulai instanceof \Carbon\Carbon ? $p->tanggal_mulai : Carbon::parse($p->tanggal_mulai ?? $p->created_at))->startOfDay();
        $diff = $today->diffInDays($ref, false);
        if ($diff === 0)  return ['text' => 'Hari ini',           'class' => 'bg-blue-100 text-blue-700'];
        if ($diff === -1) return ['text' => 'Kemarin',            'class' => 'bg-slate-100 text-slate-500'];
        if ($diff === 1)  return ['text' => 'Besok',              'class' => 'bg-amber-100 text-amber-700'];
        if ($diff < 0)   return ['text' => abs((int)$diff).' hari lalu',  'class' => 'bg-slate-100 text-slate-500'];
        return ['text' => (int)$diff.' hari lagi', 'class' => 'bg-violet-100 text-violet-700'];
    };
@endphp

<div x-data="{ open: {{ $listPeng->isNotEmpty() ? 'true' : 'false' }} }"
     class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-2"
     style="max-width: 340px;">

    {{-- Card (expandable) --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="w-80 bg-white rounded-2xl shadow-[0_8px_40px_rgba(0,0,0,0.14)] border border-slate-200/80 overflow-hidden flex flex-col"
         style="max-height: 420px;">

        {{-- Header Card --}}
        <div class="px-4 py-3 border-b border-slate-100 bg-gradient-to-r from-bps-faint/60 to-transparent flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-bps-faint text-bps-blue flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-bullhorn text-[11px]"></i>
                </div>
                <div>
                    <p class="text-xs font-extrabold text-slate-800 leading-tight">Pengumuman</p>
                    <p class="text-[9px] text-slate-400 font-medium">{{ Carbon::now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
            <button @click="open = false"
                    class="text-slate-300 hover:text-slate-500 transition-colors p-1 rounded-lg hover:bg-slate-100">
                <i class="fa-solid fa-chevron-down text-xs"></i>
            </button>
        </div>

        {{-- List Pengumuman --}}
        <div class="overflow-y-auto flex-1 divide-y divide-slate-100">
            @forelse($listPeng as $p)
            @php
                $label = $getLabel($p);
                $tipeColor = match($p->tipe) {
                    'sukses'    => ['bar' => 'bg-emerald-500', 'icon' => 'fa-circle-check text-emerald-500'],
                    'peringatan'=> ['bar' => 'bg-amber-500',   'icon' => 'fa-triangle-exclamation text-amber-500'],
                    'bahaya'    => ['bar' => 'bg-rose-500',    'icon' => 'fa-circle-xmark text-rose-500'],
                    default     => ['bar' => 'bg-bps-blue',   'icon' => 'fa-circle-info text-blue-500'],
                };
                $refDate = ($p->tanggal_mulai instanceof \Carbon\Carbon ? $p->tanggal_mulai : Carbon::parse($p->tanggal_mulai ?? $p->created_at));
            @endphp
            <div class="flex gap-0">
                <div class="w-1 {{ $tipeColor['bar'] }} shrink-0"></div>
                <div class="flex-1 px-3.5 py-3">
                    {{-- Label waktu --}}
                    <div class="flex items-center gap-1.5 mb-1.5">
                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-wide {{ $label['class'] }}">
                            <i class="fa-regular fa-clock text-[8px]"></i>
                            {{ $label['text'] }}
                        </span>
                        <span class="text-[9px] text-slate-400">{{ $refDate->translatedFormat('d M Y') }}</span>
                    </div>
                    {{-- Judul --}}
                    <div class="flex items-start gap-1.5 mb-1">
                        <i class="fa-solid {{ $tipeColor['icon'] }} text-[11px] mt-0.5 shrink-0"></i>
                        <p class="text-[11px] font-bold text-slate-800 leading-snug">{{ $p->judul }}</p>
                    </div>
                    {{-- Isi --}}
                    <p class="text-xs text-slate-700 leading-relaxed pl-4">{{ $p->isi }}</p>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-8 px-5 text-center">
                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center mb-2">
                    <i class="fa-regular fa-bell-slash text-lg text-slate-300"></i>
                </div>
                <p class="text-xs font-bold text-slate-500">Tidak ada pengumuman</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Semua informasi terkini akan muncul di sini.</p>
            </div>
            @endforelse
        </div>

        @if($listPeng->count() > 0)
        <div class="px-4 py-2 border-t border-slate-100 bg-slate-50/60 shrink-0">
            <p class="text-[9px] text-slate-400 text-center">{{ $listPeng->count() }} pengumuman aktif</p>
        </div>
        @endif
    </div>

    {{-- Floating Toggle Button --}}
    <button @click="open = !open"
            class="relative w-11 h-11 rounded-2xl bg-bps-blue hover:bg-bps-dark text-white shadow-lg shadow-bps-blue/40 flex items-center justify-center transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-bps-blue/40">
        <i class="fa-solid fa-bullhorn text-sm" x-show="!open"></i>
        <i class="fa-solid fa-xmark text-sm" x-show="open" x-cloak></i>
        @if($listPeng->count() > 0)
        <span class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-rose-500 text-white text-[9px] font-extrabold flex items-center justify-center ring-2 ring-white">
            {{ $listPeng->count() }}
        </span>
        @endif
    </button>
</div>
