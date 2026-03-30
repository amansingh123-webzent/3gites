@extends('layouts.app')
@section('title', $poll->question)

@section('content')

<div class="bg-purple-900 text-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-purple-300 mb-4">
            <a href="{{ route('polls.index') }}" class="hover:text-white transition-colors">Polls</a>
            <span class="mx-2">›</span>
            <span class="text-white">Poll</span>
        </nav>

        <div class="flex items-start justify-between gap-4 flex-wrap">
            <h1 class="font-display text-3xl font-semibold text-white leading-snug flex-1">
                {{ $poll->question }}
            </h1>
            <div class="flex items-center gap-2 flex-shrink-0">
                @if ($poll->is_closed)
                <span class="bg-slate-600 text-slate-200 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wide">Closed</span>
                @else
                <span class="bg-emerald-600/80 text-emerald-100 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wide">Open</span>
                @endif

                @can('admin')
                <a href="{{ route('admin.polls.edit', $poll) }}"
                   class="text-xs border border-purple-700 text-purple-200 hover:border-gold-400 hover:text-gold-400 px-3 py-1.5 rounded-lg transition-colors">Edit</a>
                <form method="POST" action="{{ route('admin.polls.close', $poll) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs border border-purple-700 text-purple-200 hover:border-amber-400 hover:text-amber-400 px-3 py-1.5 rounded-lg transition-colors">
                        {{ $poll->is_closed ? 'Re-open' : 'Close Poll' }}
                    </button>
                </form>
                @endcan
            </div>
        </div>

        <p class="text-purple-200 text-sm mt-3">
            {{ $totalVotes }} {{ Str::plural('vote', $totalVotes) }} cast
            @if ($myVote)
            · You voted for <strong class="text-gold-400">{{ $myVote->option->option_text }}</strong>
            @endif
        </p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Vote form --}}
    @if (! $hasVoted && ! $poll->is_closed)
    <div class="card overflow-hidden mb-6">
        <div class="px-7 py-5 border-b border-slate-100 bg-slate-50">
            <h2 class="font-semibold text-slate-700">Cast Your Vote</h2>
            <p class="text-xs text-slate-400 mt-0.5">Select one option. Your vote is final and cannot be changed.</p>
        </div>

        <form method="POST" action="{{ route('polls.vote', $poll) }}" class="px-7 py-6">
            @csrf
            <div class="space-y-3" x-data="{ selected: null }">
                @foreach ($poll->options as $option)
                <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-150"
                       :class="selected === '{{ $option->id }}' ? 'border-gold-500 bg-gold-50 shadow-sm' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                    <input type="radio" name="poll_option_id" value="{{ $option->id }}"
                        @click="selected = '{{ $option->id }}'"
                        class="w-4 h-4 text-gold-500 border-slate-300 focus:ring-gold-500">
                    <span class="text-sm font-medium transition-colors"
                          :class="selected === '{{ $option->id }}' ? 'text-slate-800' : 'text-slate-700'">
                        {{ $option->option_text }}
                    </span>
                </label>
                @endforeach
            </div>

            @error('poll_option_id')<p class="mt-3 text-xs text-red-600">{{ $message }}</p>@enderror

            <div class="mt-6 flex items-center gap-4">
                <button type="submit" class="btn-primary">
                    Submit Vote
                </button>
                <p class="text-xs text-slate-400">Results are revealed after you vote.</p>
            </div>
        </form>
    </div>
    @endif

    {{-- Results --}}
    @if ($hasVoted || $poll->is_closed || auth()->check() && auth()->user()->hasRole('admin'))
    <div class="card overflow-hidden">
        <div class="px-7 py-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-slate-700">
                    {{ $poll->is_closed ? 'Final Results' : 'Current Results' }}
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ $totalVotes }} {{ Str::plural('vote', $totalVotes) }} total</p>
            </div>
            @if ($hasVoted && ! $poll->is_closed)
            <span class="text-xs text-emerald-700 bg-emerald-100 font-semibold px-3 py-1 rounded-full flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                Your vote is recorded
            </span>
            @endif
        </div>

        <div class="px-7 py-6 space-y-4">
            @foreach ($results as $result)
            @php
                $votes = $result['count'] ?? 0;
                $pct   = $totalVotes > 0 ? round($votes / $totalVotes * 100) : 0;
                $isOwn = $myVote && $myVote->poll_option_id === $result['id'];
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm font-medium text-slate-700 flex items-center gap-1.5">
                        @if ($isOwn)<svg class="w-3.5 h-3.5 text-gold-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>@endif
                        {{ $result['text'] }}
                    </span>
                    <span class="text-xs font-bold {{ $isOwn ? 'text-gold-600' : 'text-slate-500' }}">
                        {{ $pct }}% ({{ $votes }})
                    </span>
                </div>
                <div class="h-2 bg-slate-100 rounded-full overflow-hidden" role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                    <div class="h-full rounded-full transition-all duration-700 {{ $isOwn ? 'bg-gold-500' : 'bg-purple-700' }}"
                         style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach

            @if ($totalVotes === 0)
            <p class="text-center text-slate-400 text-sm py-4">No votes have been cast yet.</p>
            @endif
        </div>
    </div>
    @endif

    @if (! $hasVoted && ! $poll->is_closed && !(auth()->check() && auth()->user()->hasRole('admin')))
    <div class="mt-4 text-center text-slate-400 text-sm">
        Results will be revealed after you vote.
    </div>
    @endif

    <div class="mt-8">
        <a href="{{ route('polls.index') }}" class="text-sm text-slate-400 hover:text-purple-900 transition-colors">
            ← Back to Polls
        </a>
    </div>

</div>

@endsection
