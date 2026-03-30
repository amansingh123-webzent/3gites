@props(['label', 'votes', 'total', 'isOwn' => false])

@php
$percentage = $total > 0 ? round(($votes / $total) * 100) : 0;
@endphp

<div role="progressbar" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $label }}: {{ $percentage }}%">
    <div class="flex items-center justify-between mb-1.5">
        <span class="text-sm font-medium {{ $isOwn ? 'text-gold-600 font-bold' : 'text-slate-700' }}">
            {{ $label }}
            @if($isOwn)
                <span class="text-gold-500 text-xs ml-1">← Your vote</span>
            @endif
        </span>
        <span class="text-sm text-slate-500 font-medium">{{ $votes }} ({{ $percentage }}%)</span>
    </div>
    <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
        <div
            class="h-full rounded-full transition-all duration-700 ease-out {{ $isOwn ? 'bg-gold-500' : 'bg-purple-800' }}"
            style="width: {{ $percentage }}%"
        ></div>
    </div>
</div>
