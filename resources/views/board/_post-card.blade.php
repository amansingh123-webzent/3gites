@php
    $commentCount = $post->comments->count();
    $isAuthor     = auth()->check() && auth()->id() === $post->user_id;
    $isAdmin      = auth()->check() && auth()->user()->hasRole('admin');
    $authorPhoto  = $post->user->photo_recent ?? $post->user->profile?->recent_photo ?? null;
@endphp

<div class="card {{ $isPinned ? 'border-gold-300 shadow-md' : '' }} hover:shadow-md hover:-translate-y-0.5 transition-all duration-150 overflow-hidden">

    @if ($isPinned)
    <div class="h-1 bg-gradient-to-r from-gold-400 to-gold-500"></div>
    @endif

    <div class="px-6 py-5">
        <div class="flex items-start justify-between gap-4">

            {{-- Left: avatar + content --}}
            <div class="flex items-start gap-4 flex-1 min-w-0">

                {{-- Author avatar --}}
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if ($authorPhoto)
                    <img src="{{ Storage::url($authorPhoto) }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-full object-cover">
                    @else
                    <span class="font-bold text-purple-700 text-sm font-display">{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                    @endif
                </div>

                {{-- Post info --}}
                <div class="flex-1 min-w-0">
                    <a href="{{ route('posts.show', $post) }}" class="block group">
                        <h2 class="font-display font-bold text-slate-800 group-hover:text-purple-900 transition-colors text-lg leading-snug truncate">
                            {{ $post->title }}
                        </h2>
                    </a>
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-slate-400">
                        <a href="{{ route('members.show', $post->user) }}" class="hover:text-purple-700 font-medium text-slate-500 transition-colors">
                            {{ $post->user->name }}
                        </a>
                        <span>·</span>
                        <time datetime="{{ $post->created_at->toIso8601String() }}" title="{{ $post->created_at->format('F j, Y g:i A') }}">
                            {{ $post->created_at->diffForHumans() }}
                        </time>
                        @if ($post->created_at != $post->updated_at)
                        <span class="italic text-slate-300">edited</span>
                        @endif
                    </div>

                    <p class="mt-2 text-sm text-slate-600 leading-relaxed line-clamp-2">
                        {{ Str::limit(strip_tags($post->body), 180) }}
                    </p>
                </div>
            </div>

            {{-- Right: comment count + admin actions --}}
            <div class="flex flex-col items-end gap-2 flex-shrink-0">

                <a href="{{ route('posts.show', $post) }}#comments"
                   class="flex items-center gap-1.5 text-slate-400 hover:text-purple-700 transition-colors"
                   title="{{ $commentCount }} {{ Str::plural('comment', $commentCount) }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span class="text-xs font-semibold">{{ $commentCount }}</span>
                </a>

                @if ($isAdmin)
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('posts.pin', $post) }}">
                        @csrf @method('PATCH')
                        <button type="submit" title="{{ $post->is_pinned ? 'Unpin post' : 'Pin post' }}" class="text-slate-300 hover:text-gold-500 transition-colors">
                            <svg class="w-4 h-4 {{ $post->is_pinned ? 'text-gold-500' : '' }}" fill="{{ $post->is_pinned ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Delete this post? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" title="Delete post" class="text-slate-300 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
                @elseif ($isAuthor)
                <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Delete your post?')">
                    @csrf @method('DELETE')
                    <button type="submit" title="Delete post" class="text-slate-300 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
                @endif
            </div>

        </div>
    </div>
</div>
