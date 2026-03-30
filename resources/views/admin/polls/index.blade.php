@extends('layouts.app')
@section('title', 'Manage Polls')

@section('content')

<x-page-header title="Manage Polls" subtitle="Create and manage polls for the class"
    :back="route('admin.dashboard')" backLabel="Admin">
    <x-slot name="actions">
        <a href="{{ route('admin.polls.create') }}" class="btn-gold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Poll
        </a>
    </x-slot>
</x-page-header>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Question</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Options</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Votes</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Age</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">State</th>
                    <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($polls as $poll)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3 max-w-xs">
                        <a href="{{ route('polls.show', $poll) }}" class="font-medium text-slate-800 hover:text-purple-900 transition-colors">
                            {{ Str::limit($poll->question, 60) }}
                        </a>
                    </td>
                    <td class="px-5 py-3 text-slate-500">{{ $poll->options_count }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $poll->votes_count }}</td>
                    <td class="px-5 py-3 text-slate-400 text-xs">{{ $poll->created_at->diffForHumans() }}</td>
                    <td class="px-5 py-3">
                        @if ($poll->is_closed)
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500">Closed</span>
                        @elseif ($poll->is_published)
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">Open</span>
                        @else
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Draft</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if (!$poll->is_published)
                            <form method="POST" action="{{ route('admin.polls.publish', $poll) }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs px-2.5 py-1 border border-slate-200 rounded-lg text-slate-500 hover:border-emerald-300 hover:text-emerald-600 transition-colors">Publish</button>
                            </form>
                            @endif

                            @if ($poll->is_published)
                            <form method="POST" action="{{ route('admin.polls.publish', $poll) }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs px-2.5 py-1 border border-slate-200 rounded-lg text-slate-500 hover:border-amber-300 hover:text-amber-600 transition-colors">Unpublish</button>
                            </form>
                            <form method="POST" action="{{ route('admin.polls.close', $poll) }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs px-2.5 py-1 border border-slate-200 rounded-lg text-slate-500 hover:border-slate-400 transition-colors">
                                    {{ $poll->is_closed ? 'Re-open' : 'Close' }}
                                </button>
                            </form>
                            @endif

                            @if ($poll->votes_count == 0)
                            <a href="{{ route('admin.polls.edit', $poll) }}"
                               class="text-xs px-2.5 py-1 border border-slate-200 rounded-lg text-slate-500 hover:border-purple-300 hover:text-purple-700 transition-colors">Edit</a>
                            @endif

                            <form method="POST" action="{{ route('admin.polls.destroy', $poll) }}" class="inline"
                                  onsubmit="return confirm('Delete this poll and all {{ $poll->votes_count }} votes?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs px-2.5 py-1 border border-slate-200 rounded-lg text-slate-400 hover:border-red-300 hover:text-red-500 transition-colors">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                        <p>No polls yet.</p>
                        <a href="{{ route('admin.polls.create') }}" class="text-gold-600 hover:underline text-sm mt-1 inline-block">Create your first poll →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($polls->hasPages())
    <div class="mt-6">{{ $polls->links() }}</div>
    @endif

</div>

@endsection
