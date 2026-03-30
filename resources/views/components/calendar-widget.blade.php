@props(['monthLabel' => null, 'year' => null, 'eventDays' => [], 'prevUrl' => null, 'nextUrl' => null, 'startDay' => 0, 'daysInMonth' => 31])

@php
$label = $monthLabel ?? now()->format('F Y');
$today = now()->day;
$isCurrentMonth = $label === now()->format('F Y');
@endphp

<div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
    {{-- Header --}}
    <div class="bg-purple-900 text-white px-5 py-4 flex items-center justify-between">
        @if($prevUrl)
            <a href="{{ $prevUrl }}" class="text-purple-300 hover:text-white transition-colors p-1 -m-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
        @else
            <div class="w-6"></div>
        @endif
        <h3 class="font-semibold text-sm">{{ $label }}</h3>
        @if($nextUrl)
            <a href="{{ $nextUrl }}" class="text-purple-300 hover:text-white transition-colors p-1 -m-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <div class="w-6"></div>
        @endif
    </div>

    <div class="p-4">
        {{-- Day headers --}}
        <div class="grid grid-cols-7 mb-2">
            @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $d)
                <div class="text-center text-xs text-slate-400 font-medium py-1">{{ $d }}</div>
            @endforeach
        </div>

        {{-- Day cells --}}
        <div class="grid grid-cols-7 gap-0.5">
            @for($i = 0; $i < $startDay; $i++)
                <div></div>
            @endfor

            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $hasEvent = in_array($day, $eventDays);
                    $isToday = $isCurrentMonth && $day === $today;
                @endphp
                <div class="flex flex-col items-center py-0.5">
                    <div class="w-8 h-8 flex items-center justify-center text-xs rounded-full
                        {{ $isToday ? 'bg-gold-500 text-purple-950 font-bold' : ($hasEvent ? 'text-purple-900 font-semibold hover:bg-purple-50' : 'text-slate-500 hover:bg-slate-50') }}
                        transition-colors cursor-default">
                        {{ $day }}
                    </div>
                    @if($hasEvent)
                        <div class="w-1 h-1 rounded-full bg-gold-500 mt-0.5"></div>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    <div class="px-4 pb-3 flex items-center gap-3 text-xs text-slate-400">
        <div class="flex items-center gap-1.5">
            <div class="w-2 h-2 rounded-full bg-gold-500"></div>
            <span>Event day</span>
        </div>
        <div class="flex items-center gap-1.5">
            <div class="w-4 h-4 rounded-full bg-gold-500 flex items-center justify-center text-purple-950 text-xs font-bold">•</div>
            <span>Today</span>
        </div>
    </div>
</div>
