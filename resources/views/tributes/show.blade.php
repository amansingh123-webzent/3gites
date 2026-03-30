@extends('layouts.app')
@section('title', 'In Loving Memory — ' . $tribute->member_name)

@section('content')

{{-- Memorial header --}}
<div class="relative bg-gradient-to-b from-slate-900 to-slate-800 py-16 overflow-hidden">
    <div class="absolute inset-0 flex items-center justify-center opacity-5">
        <svg class="w-64 h-64 text-white" fill="currentColor" viewBox="0 0 100 100">
            <rect x="43" y="5" width="14" height="90" rx="2"/>
            <rect x="10" y="30" width="80" height="14" rx="2"/>
        </svg>
    </div>

    <div class="relative max-w-3xl mx-auto px-4 text-center">
        <p class="text-gold-400 text-xs tracking-[0.3em] uppercase mb-4">In Loving Memory</p>

        <div class="mx-auto mb-6 relative w-36 h-36 rounded-full overflow-hidden border-4 border-gold-500/50 shadow-xl">
            @if ($tribute->photo)
            <img src="{{ Storage::url($tribute->photo) }}" alt="{{ $tribute->member_name }}"
                 class="w-full h-full object-cover grayscale">
            @else
            <div class="w-full h-full bg-slate-700 flex items-center justify-center">
                <span class="font-display text-4xl font-bold text-slate-400">
                    {{ strtoupper(substr($tribute->member_name, 0, 1)) }}
                </span>
            </div>
            @endif
        </div>

        <h1 class="font-display text-4xl font-bold text-white mb-2">{{ $tribute->member_name }}</h1>

        @if ($tribute->birth_year || $tribute->death_year)
        <p class="text-slate-400 text-lg tracking-widest">
            {{ $tribute->birth_year ?? '?' }}
            <span class="text-gold-500 mx-2">·</span>
            {{ $tribute->death_year ?? '?' }}
        </p>
        @endif

        <div class="mt-6 w-16 h-0.5 bg-gold-500/50 mx-auto"></div>
    </div>
</div>

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 px-8 py-8">
        <blockquote class="font-display text-slate-700 text-lg leading-relaxed italic text-center">
            "{{ $tribute->tribute_text }}"
        </blockquote>
    </div>

    @can('admin')
    <div class="mt-6 text-center">
        <a href="{{ route('admin.tributes.edit', $tribute) }}"
           class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-gold-600 border border-slate-200 hover:border-gold-400 px-4 py-2 rounded-lg transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Tribute
        </a>
    </div>
    @endcan

    <div class="mt-8 text-center">
        <a href="{{ route('tributes.index') }}" class="text-sm text-slate-400 hover:text-purple-900 transition-colors">
            ← View all tributes
        </a>
    </div>
</div>

@endsection
