@extends('layouts.app')
@section('title', 'In Loving Memory')

@section('content')

<div class="bg-slate-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-gold-400 text-xs tracking-[0.3em] uppercase mb-3">Class of 1975</p>
        <h1 class="font-display text-4xl font-bold text-white">In Loving Memory</h1>
        <p class="text-slate-400 mt-3">We remember our classmates who have passed on.</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if ($tributes->isEmpty())
    <div class="text-center py-16 text-slate-400">
        <p class="text-lg">No tributes yet.</p>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($tributes as $tribute)
        <a href="{{ route('tributes.show', $tribute) }}"
           class="group bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 text-center flex flex-col">
            <div class="aspect-square bg-slate-100 overflow-hidden">
                @if ($tribute->photo)
                <img src="{{ Storage::url($tribute->photo) }}" alt="{{ $tribute->member_name }}"
                     class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500" loading="lazy">
                @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                    <span class="font-display text-3xl font-bold text-slate-400">
                        {{ strtoupper(substr($tribute->member_name, 0, 1)) }}
                    </span>
                </div>
                @endif
            </div>
            <div class="p-4 flex-1 flex flex-col justify-center">
                <p class="font-display font-bold text-slate-800 group-hover:text-gold-600 transition-colors">
                    {{ $tribute->member_name }}
                </p>
                @if ($tribute->birth_year || $tribute->death_year)
                <p class="text-xs text-slate-400 mt-1">
                    {{ $tribute->birth_year ?? '?' }} – {{ $tribute->death_year ?? '?' }}
                </p>
                @endif
            </div>
        </a>
        @endforeach
    </div>
    @endif
</div>

@endsection
