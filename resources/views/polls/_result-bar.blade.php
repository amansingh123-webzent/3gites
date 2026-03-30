{{--
    Props:
      $result     — ['id', 'text', 'count', 'percent']
      $myVotedId  — the poll_option_id the current user voted for (nullable)
      $totalVotes — total vote count for the poll
--}}

@php
    $isMyVote  = $myVotedId && $result['id'] === $myVotedId;
    $pct       = $result['percent'];
    $barColor  = $isMyVote
        ? 'bg-gold-500'
        : 'bg-navy-700';
    $textColor = $isMyVote ? 'text-gold-700 font-semibold' : 'text-slate-700';
@endphp

<div>
    {{-- Label row --}}
    <div class="flex items-center justify-between mb-1.5">
        <div class="flex items-center gap-2 min-w-0">
            @if ($isMyVote)
                <svg class="w-4 h-4 text-gold-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            @endif
            <span class="text-sm {{ $textColor }} truncate">
                {{ $result['text'] }}
                @if ($isMyVote)
                    <span class="text-xs text-gold-500 ml-1">(your vote)</span>
                @endif
            </span>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0 ml-4">
            <span class="text-sm font-bold {{ $isMyVote ? 'text-gold-700' : 'text-slate-700' }}">
                {{ $pct }}%
            </span>
            <span class="text-xs text-slate-400 w-14 text-right">
                {{ $result['count'] }} {{ Str::plural('vote', $result['count']) }}
            </span>
        </div>
    </div>

    {{-- Bar track --}}
    <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
        <div
            class="{{ $barColor }} h-full rounded-full transition-all duration-700 ease-out"
            style="width: {{ $pct }}%"
            role="progressbar"
            aria-valuenow="{{ $pct }}"
            aria-valuemin="0"
            aria-valuemax="100"
        ></div>
    </div>
</div>
