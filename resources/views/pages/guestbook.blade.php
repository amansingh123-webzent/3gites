@extends('layouts.app')
@section('title', 'Guestbook')

@section('content')

<x-page-header title="Guestbook" subtitle="Leave a note for the Class of 1975" />

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Add comment form (members only) --}}
    @auth
        @if(auth()->user()->member_status === 'active' || auth()->user()->status === 'active')
        <div class="card overflow-hidden mb-8">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                <h2 class="font-semibold text-slate-700 text-sm">Leave a Message</h2>
            </div>
            <form method="POST" action="{{ route('guestbook.store') }}" class="px-6 py-5">
                @csrf
                <textarea name="body" rows="3" required maxlength="1000"
                    class="w-full border {{ $errors->has('body') ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 resize-none transition"
                    placeholder="Share a memory, a thought, or a greeting for your classmates…"></textarea>
                @error('body')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                <div class="mt-3 flex justify-end">
                    <button type="submit" class="btn-primary text-sm">Post Message</button>
                </div>
            </form>
        </div>
        @endif
    @else
    <div class="bg-purple-50 border border-purple-100 rounded-2xl px-6 py-5 mb-8 text-center text-sm text-slate-500">
        <a href="{{ route('login') }}" class="text-gold-600 hover:underline font-semibold">Sign in</a>
        to leave a message in the guestbook.
    </div>
    @endauth

    {{-- Comments listing --}}
    @forelse ($comments as $comment)
    @php $photo = $comment->user->photo_recent ?? $comment->user->profile?->recent_photo ?? null; @endphp
    <div class="group card px-6 py-5 mb-3 hover:border-slate-200 transition-colors">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-purple-100 overflow-hidden flex items-center justify-center flex-shrink-0">
                @if ($photo)
                <img src="{{ Storage::url($photo) }}" class="w-10 h-10 object-cover" alt="{{ $comment->user->name }}">
                @else
                <span class="font-bold text-purple-700 text-sm font-display">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                @endif
            </div>

            <div class="flex-1">
                <div class="flex items-center gap-2 flex-wrap mb-2">
                    <a href="{{ route('members.show', $comment->user) }}"
                       class="font-semibold text-sm text-slate-800 hover:text-purple-900 transition-colors">
                        {{ $comment->user->name }}
                    </a>
                    <span class="text-slate-300">·</span>
                    <time class="text-xs text-slate-400" datetime="{{ $comment->created_at->toIso8601String() }}">
                        {{ $comment->created_at->format('F j, Y') }}
                    </time>
                </div>
                <p class="text-sm text-slate-700 leading-relaxed">{{ $comment->body }}</p>
            </div>

            @can('delete', $comment)
            <form method="POST" action="{{ route('guestbook.destroy', $comment) }}"
                  class="opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0"
                  onsubmit="return confirm('Remove this message?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors" title="Remove">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </form>
            @endcan
        </div>
    </div>
    @empty
    <div class="text-center py-16 text-slate-400">
        <p class="text-lg">No messages yet.</p>
        <p class="text-sm mt-1">Be the first to leave a note!</p>
    </div>
    @endforelse

    @if ($comments->hasPages())
    <div class="mt-8">{{ $comments->links() }}</div>
    @endif

</div>

@endsection
