@props(['date', 'size' => 'md'])

@php
$carbonDate = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
@endphp

<div class="bg-purple-900 rounded-xl text-center py-2 px-1 flex flex-col items-center justify-center flex-shrink-0 {{ $size === 'lg' ? 'w-16' : 'w-14' }}">
    <span class="text-gold-400 text-xs font-semibold uppercase leading-none">{{ $carbonDate->format('M') }}</span>
    <span class="text-white font-bold font-display leading-tight {{ $size === 'lg' ? 'text-3xl' : 'text-2xl' }}">{{ $carbonDate->format('d') }}</span>
</div>
