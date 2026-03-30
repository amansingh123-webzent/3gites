@props(['number', 'label', 'sublabel' => null, 'color' => 'purple', 'icon' => null])

<div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6 text-center">
    @if($icon)
        <div class="text-3xl mb-2">{{ $icon }}</div>
    @endif
    <div class="font-display text-5xl font-bold text-{{ $color }}-900 leading-none">{{ $number }}</div>
    <div class="text-slate-600 font-semibold text-sm mt-2">{{ $label }}</div>
    @if($sublabel)
        <div class="text-slate-400 text-xs mt-0.5">{{ $sublabel }}</div>
    @endif
</div>
