@extends('layouts.app')
@section('title', 'Polls & Surveys')

@section('content')

<x-page-header title="Polls & Surveys" subtitle="Have your say — one vote per poll">
    @can('admin')
    <x-slot name="actions">
        <a href="{{ route('admin.polls.create') }}" class="btn-gold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Poll
        </a>
    </x-slot>
    @endcan
</x-page-header>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Tab switcher --}}
    <div class="flex gap-1 mb-8 bg-slate-100 p-1 rounded-xl w-fit">
        <a href="{{ route('polls.index', ['tab' => 'open']) }}"
           class="px-5 py-2 rounded-lg text-sm font-semibold transition-colors {{ $tab !== 'closed' ? 'bg-white text-purple-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            Open Polls
            @if ($open->isNotEmpty())
            <span class="ml-1.5 text-xs {{ $tab !== 'closed' ? 'text-gold-600' : 'text-slate-400' }}">{{ $open->count() }}</span>
            @endif
        </a>
        <a href="{{ route('polls.index', ['tab' => 'closed']) }}"
           class="px-5 py-2 rounded-lg text-sm font-semibold transition-colors {{ $tab === 'closed' ? 'bg-white text-purple-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            Closed Polls
            @if ($closed->isNotEmpty())
            <span class="ml-1.5 text-xs {{ $tab === 'closed' ? 'text-gold-600' : 'text-slate-400' }}">{{ $closed->count() }}</span>
            @endif
        </a>
    </div>

    {{-- Open polls --}}
    @if ($tab !== 'closed')
        @forelse ($open as $poll)
        @php $hasVoted = in_array($poll->id, $votedPollIds); @endphp
        <div class="card mb-4 overflow-hidden">
            @if (! $hasVoted)
            <div class="h-0.5 bg-gradient-to-r from-gold-400 to-gold-500"></div>
            @endif
            <div class="px-6 py-5">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="flex-1">
                        <a href="{{ route('polls.show', $poll) }}" class="group">
                            <h2 class="font-display text-xl font-semibold text-slate-800 group-hover:text-purple-900 transition-colors leading-snug">
                                {{ $poll->question }}
                            </h2>
                        </a>
                        <p class="text-xs text-slate-400 mt-1.5">
                            {{ $poll->votes_count }} {{ Str::plural('vote', $poll->votes_count) }} cast
                            · {{ $poll->options_count ?? $poll->options->count() }} options
                        </p>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        @if ($hasVoted)
                        <span class="flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Voted
                        </span>
                        @else
                        <span class="text-xs font-semibold text-gold-700 bg-gold-100 px-3 py-1 rounded-full">Vote Now</span>
                        @endif
                        <a href="{{ route('polls.show', $poll) }}" class="btn-primary text-xs">
                            {{ $hasVoted ? 'See Results' : 'Vote' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-16 card border-dashed border-slate-200">
            <svg class="w-12 h-12 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-slate-400 text-lg font-medium">No open polls right now.</p>
            <p class="text-slate-400 text-sm mt-1">Check back soon — the administrator will post new polls here.</p>
        </div>
        @endforelse

    {{-- Closed polls --}}
    @else
        @forelse ($closed as $poll)
        <div class="card mb-4 overflow-hidden opacity-80">
            <div class="px-6 py-5">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="flex-1">
                        <a href="{{ route('polls.show', $poll) }}" class="group">
                            <h2 class="font-display text-xl font-semibold text-slate-800 group-hover:text-purple-900 transition-colors">
                                {{ $poll->question }}
                            </h2>
                        </a>
                        <p class="text-xs text-slate-400 mt-1.5">
                            {{ $poll->votes_count }} {{ Str::plural('vote', $poll->votes_count) }} · Closed
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">Closed</span>
                        <a href="{{ route('polls.show', $poll) }}" class="text-xs bg-slate-700 hover:bg-slate-600 text-white font-bold px-4 py-2 rounded-lg transition-colors">View Results</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-16 card border-dashed border-slate-200">
            <p class="text-slate-400 text-lg">No closed polls yet.</p>
        </div>
        @endforelse
    @endif

</div>

@endsection
