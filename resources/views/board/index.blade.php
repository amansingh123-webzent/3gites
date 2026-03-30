@extends('layouts.app')
@section('title', 'Message Board')

@section('content')

<x-page-header title="Message Board" subtitle="Share news, memories, and keep in touch">
    @can('create', \App\Models\Post::class)
    <x-slot name="actions">
        <a href="{{ route('posts.create') }}" class="btn-gold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Post
        </a>
    </x-slot>
    @endcan
</x-page-header>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Pinned posts --}}
    @if ($pinned->isNotEmpty())
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-4 h-4 text-gold-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M16 4v6l2 2v2h-5v6l-1 1-1-1v-6H6v-2l2-2V4h8zM9 4h6v5.17L13 11.34V11H11v.34L9 9.17V4z"/>
            </svg>
            <span class="text-xs font-bold text-gold-600 uppercase tracking-widest">Pinned</span>
        </div>
        <div class="space-y-3">
            @foreach ($pinned as $post)
                @include('board._post-card', ['post' => $post, 'isPinned' => true])
            @endforeach
        </div>
    </div>

    <div class="flex items-center gap-3 mb-6">
        <div class="flex-1 h-px bg-slate-200"></div>
        <span class="text-xs text-slate-400 uppercase tracking-widest">Recent Posts</span>
        <div class="flex-1 h-px bg-slate-200"></div>
    </div>
    @endif

    {{-- Regular posts --}}
    @if ($posts->isEmpty() && $pinned->isEmpty())
    <div class="card p-16 text-center">
        <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <p class="text-slate-500 text-lg font-medium">No posts yet.</p>
        <p class="text-slate-400 text-sm mt-1">Be the first to start a conversation.</p>
        @can('create', \App\Models\Post::class)
        <a href="{{ route('posts.create') }}" class="mt-4 inline-block btn-primary text-sm">Write the first post →</a>
        @endcan
    </div>
    @else
    <div class="space-y-3">
        @foreach ($posts as $post)
            @include('board._post-card', ['post' => $post, 'isPinned' => false])
        @endforeach
    </div>

    @if ($posts->hasPages())
    <div class="mt-8">{{ $posts->links() }}</div>
    @endif
    @endif

</div>

@endsection
