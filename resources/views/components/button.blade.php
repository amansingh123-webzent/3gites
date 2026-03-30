@props(['variant' => 'primary', 'href' => null, 'type' => 'button', 'disabled' => false])

@php
$classes = match($variant) {
    'gold'    => 'bg-gold-500 hover:bg-gold-400 text-purple-950 font-bold px-5 py-2.5 rounded-xl shadow inline-flex items-center gap-2 transition-colors focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2',
    'ghost'   => 'border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-800 font-semibold px-5 py-2.5 rounded-xl inline-flex items-center gap-2 transition-colors focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2',
    'danger'  => 'text-red-500 hover:text-red-700 text-sm transition-colors focus:outline-none',
    default   => 'bg-purple-900 hover:bg-purple-800 text-white font-bold px-6 py-3 rounded-xl text-sm transition-colors inline-flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2',
};
if ($disabled) $classes .= ' opacity-50 cursor-not-allowed pointer-events-none';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
