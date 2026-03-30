@extends('layouts.app')
@section('title', 'New Post')

@section('content')

<x-page-header title="New Post" subtitle="Share something with the Class of 1975"
    :back="route('posts.index')" backLabel="Message Board" />

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="card overflow-hidden">
        <x-card-header title="Compose Post" subtitle="Your message will be visible to all members" />

        <form method="POST" action="{{ route('posts.store') }}" class="px-8 py-8 space-y-6"
            x-data="{
                title: '{{ old('title') }}',
                body: `{{ old('body') }}`,
                bodyLen: {{ strlen(old('body', '')) }},
                titleLen: {{ strlen(old('title', '')) }}
            }">
            @csrf

            {{-- Title --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="title" class="block text-sm font-semibold text-slate-700">
                        Subject <span class="text-red-500">*</span>
                    </label>
                    <span class="text-xs" :class="titleLen > 180 ? 'text-amber-500' : 'text-slate-400'">
                        <span x-text="titleLen"></span> / 200
                    </span>
                </div>
                <input id="title" type="text" name="title" required maxlength="200"
                    x-model="title" @input="titleLen = title.length"
                    class="w-full border {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition"
                    placeholder="What's on your mind? Give it a meaningful subject…">
                @error('title')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Body --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="body" class="block text-sm font-semibold text-slate-700">
                        Message <span class="text-red-500">*</span>
                    </label>
                    <span class="text-xs" :class="bodyLen > 9500 ? 'text-amber-500 font-semibold' : 'text-slate-400'">
                        <span x-text="bodyLen.toLocaleString()"></span> / 10,000
                    </span>
                </div>
                <textarea id="body" name="body" rows="10" required maxlength="10000"
                    x-model="body" @input="bodyLen = body.length"
                    class="w-full border {{ $errors->has('body') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition resize-y leading-relaxed"
                    placeholder="Write your message here… Share a memory, update your classmates, ask a question."></textarea>
                @error('body')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="bg-purple-50 rounded-xl px-4 py-3 text-xs text-purple-700 border border-purple-100">
                📌 Posts are visible to all members. Please keep the conversation respectful and kind.
                The administrator may remove posts that don't meet community standards.
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Publish Post
                </button>
                <a href="{{ route('posts.index') }}" class="btn-ghost">Cancel</a>
            </div>

        </form>
    </div>
</div>

@endsection
