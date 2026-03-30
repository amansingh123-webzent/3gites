@extends('layouts.app')
@section('title', $post->title)

@section('content')

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-slate-500 mb-6">
        <a href="{{ route('posts.index') }}" class="hover:text-purple-700 transition-colors">Message Board</a>
        <span class="mx-2">›</span>
        <span class="text-slate-800 truncate">{{ Str::limit($post->title, 60) }}</span>
    </nav>

    {{-- Original Post --}}
    <div class="card {{ $post->is_pinned ? 'border-gold-300' : '' }} overflow-hidden mb-6">

        @if ($post->is_pinned)
        <div class="h-1 bg-gradient-to-r from-gold-400 to-gold-500"></div>
        @endif

        <div class="px-7 pt-7 pb-5">

            @if ($post->is_pinned)
            <div class="flex items-center gap-1.5 text-gold-600 text-xs font-bold uppercase tracking-widest mb-3">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16 4v6l2 2v2h-5v6l-1 1-1-1v-6H6v-2l2-2V4h8zM9 4h6v5.17L13 11.34V11H11v.34L9 9.17V4z"/>
                </svg>
                Pinned Post
            </div>
            @endif

            <h1 class="font-display text-3xl font-bold text-slate-800 leading-snug mb-4">{{ $post->title }}</h1>

            {{-- Author + date --}}
            <div class="flex items-center gap-3 mb-6 pb-5 border-b border-slate-100">
                @php $authorPhoto = $post->user->photo_recent ?? $post->user->profile?->recent_photo ?? null; @endphp
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                    @if ($authorPhoto)
                    <img src="{{ Storage::url($authorPhoto) }}" alt="{{ $post->user->name }}" class="w-10 h-10 object-cover">
                    @else
                    <span class="font-bold text-purple-700 text-sm font-display">{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div>
                    <a href="{{ route('members.show', $post->user) }}" class="font-semibold text-sm text-slate-800 hover:text-purple-900 transition-colors">
                        {{ $post->user->name }}
                    </a>
                    <div class="text-xs text-slate-400 mt-0.5">
                        <time datetime="{{ $post->created_at->toIso8601String() }}">
                            {{ $post->created_at->format('F j, Y \a\t g:i A') }}
                        </time>
                        @if ($post->updated_at->gt($post->created_at->addMinute()))
                        <span class="ml-2 italic">(edited {{ $post->updated_at->diffForHumans() }})</span>
                        @endif
                    </div>
                </div>

                {{-- Admin controls --}}
                <div class="ml-auto flex items-center gap-2">
                    @can('pin', \App\Models\Post::class)
                    <form method="POST" action="{{ route('posts.pin', $post) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="flex items-center gap-1 text-xs px-3 py-1.5 rounded-lg border transition-colors {{ $post->is_pinned ? 'border-gold-300 bg-gold-50 text-gold-700 hover:bg-gold-100' : 'border-slate-200 text-slate-500 hover:border-gold-300 hover:text-gold-600' }}">
                            <svg class="w-3.5 h-3.5" fill="{{ $post->is_pinned ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                            {{ $post->is_pinned ? 'Unpin' : 'Pin' }}
                        </button>
                    </form>
                    @endcan

                    @can('delete', $post)
                    <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Delete this post permanently?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="flex items-center gap-1 text-xs px-3 py-1.5 rounded-lg border border-slate-200 text-slate-500 hover:border-red-300 hover:text-red-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                    @endcan
                </div>
            </div>

            <div class="prose prose-slate prose-sm max-w-none leading-relaxed text-slate-700">
                {!! nl2br(e($post->body)) !!}
            </div>

        </div>
    </div>

    {{-- Comments Section --}}
    <div id="comments">

        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <h2 class="font-semibold text-slate-700">
                {{ $post->comments->count() }} {{ Str::plural('Reply', $post->comments->count()) }}
            </h2>
        </div>

        @forelse ($post->comments as $comment)
        @php $commentPhoto = $comment->user->photo_recent ?? $comment->user->profile?->recent_photo ?? null; @endphp
        <div id="comment-{{ $comment->id }}" class="group bg-white rounded-xl border border-slate-100 px-5 py-4 mb-3 hover:border-slate-200 transition-colors">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if ($commentPhoto)
                    <img src="{{ Storage::url($commentPhoto) }}" alt="{{ $comment->user->name }}" class="w-8 h-8 object-cover">
                    @else
                    <span class="text-xs font-bold text-purple-700 font-display">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap mb-1.5">
                        <a href="{{ route('members.show', $comment->user) }}" class="font-semibold text-sm text-slate-800 hover:text-purple-900 transition-colors">
                            {{ $comment->user->name }}
                        </a>
                        <span class="text-slate-300">·</span>
                        <time class="text-xs text-slate-400" datetime="{{ $comment->created_at->toIso8601String() }}" title="{{ $comment->created_at->format('F j, Y g:i A') }}">
                            {{ $comment->created_at->diffForHumans() }}
                        </time>
                    </div>
                    <p class="text-sm text-slate-700 leading-relaxed">{!! nl2br(e($comment->body)) !!}</p>
                </div>

                @can('delete', $comment)
                <form method="POST" action="{{ route('comments.destroy', [$post, $comment]) }}"
                      class="opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0"
                      onsubmit="return confirm('Remove this comment?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors" title="Delete comment">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </form>
                @endcan
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-slate-400 text-sm">No replies yet — be the first to respond.</div>
        @endforelse

        {{-- Add a comment --}}
        @can('create', \App\Models\Comment::class)
        <div class="mt-6 card overflow-hidden">
            <div class="px-5 py-3 bg-slate-50 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-700">Add a Reply</h3>
            </div>

            @php $myPhoto = auth()->user()->photo_recent ?? auth()->user()->profile?->recent_photo ?? null; @endphp
            <form method="POST" action="{{ route('comments.store', $post) }}" class="px-5 py-4" x-data="{ body: '', len: 0 }">
                @csrf
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0 overflow-hidden mt-1">
                        @if ($myPhoto)
                        <img src="{{ Storage::url($myPhoto) }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 object-cover">
                        @else
                        <span class="text-xs font-bold text-purple-700 font-display">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <textarea name="body" rows="3" required maxlength="2000"
                            x-model="body" @input="len = body.length"
                            class="w-full border {{ $errors->has('body') ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition resize-none"
                            placeholder="Write a reply…"></textarea>
                        @error('body')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-slate-400" :class="len > 1800 ? 'text-amber-500' : ''">
                                <span x-text="len"></span> / 2,000
                            </span>
                            <button type="submit" class="btn-primary text-sm">Post Reply</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endcan

    </div>

    <div class="mt-8">
        <a href="{{ route('posts.index') }}" class="text-sm text-slate-400 hover:text-purple-700 transition-colors">
            ← Back to Message Board
        </a>
    </div>

</div>

@endsection
