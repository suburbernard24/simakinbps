@props(['name', 'label', 'value' => '#000000'])
<div>
    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">{{ $label }}</label>
    <div class="flex items-center gap-2">
        <input type="color" name="{{ $name }}" value="{{ $value }}"
               id="color-{{ $name }}"
               class="w-11 h-10 rounded-lg border border-slate-200 cursor-pointer p-0.5">
        <input type="text" id="colortext-{{ $name }}" value="{{ $value }}"
               class="flex-1 text-sm px-3 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none font-mono"
               maxlength="7" pattern="#[0-9A-Fa-f]{6}"
               onchange="document.getElementById('color-{{ $name }}').value = this.value">
    </div>
</div>
