@extends('layouts.app')
@section('title', 'Edit Tribute — ' . $tribute->member_name)

@section('content')

<x-page-header :title="'Edit Tribute'" :subtitle="'In Loving Memory · ' . $tribute->member_name"
    :back="route('tributes.index')" backLabel="Tributes" />

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="card overflow-hidden">

        {{-- Memorial photo upload --}}
        <div class="px-7 py-6 border-b border-slate-100">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Memorial Photo</h2>
            <div class="flex items-start gap-5">
                <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gold-300 flex-shrink-0">
                    @if ($tribute->photo)
                    <img src="{{ Storage::url($tribute->photo) }}" class="w-full h-full object-cover grayscale" alt="{{ $tribute->member_name }}">
                    @else
                    <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                        <span class="font-display text-2xl font-bold text-slate-400">
                            {{ strtoupper(substr($tribute->member_name, 0, 1)) }}
                        </span>
                    </div>
                    @endif
                </div>
                <form method="POST" action="{{ route('admin.tributes.photo', $tribute) }}"
                      enctype="multipart/form-data" class="flex-1">
                    @csrf
                    <input type="file" name="photo" accept="image/*"
                        class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-900 file:text-white hover:file:bg-purple-800 cursor-pointer mb-2 transition">
                    <p class="text-xs text-slate-400 mb-2">JPEG, PNG, WebP · Max 2MB</p>
                    <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors">
                        Upload Photo
                    </button>
                </form>
            </div>
        </div>

        {{-- Tribute details form --}}
        <form method="POST" action="{{ route('admin.tributes.update', $tribute) }}"
              class="px-7 py-7 space-y-6">
            @csrf @method('PATCH')

            <div>
                <label for="member_name" class="block text-sm font-semibold text-slate-700 mb-1.5">Full Name</label>
                <input id="member_name" type="text" name="member_name"
                    value="{{ old('member_name', $tribute->member_name) }}" required
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition @error('member_name') border-red-400 @enderror">
                @error('member_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="birth_year" class="block text-sm font-semibold text-slate-700 mb-1.5">Birth Year</label>
                    <input id="birth_year" type="number" name="birth_year"
                        value="{{ old('birth_year', $tribute->birth_year) }}"
                        min="1920" max="1985" placeholder="1957"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition">
                </div>
                <div>
                    <label for="death_year" class="block text-sm font-semibold text-slate-700 mb-1.5">Year of Passing</label>
                    <input id="death_year" type="number" name="death_year"
                        value="{{ old('death_year', $tribute->death_year) }}"
                        min="1920" max="2100" placeholder="2020"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition">
                </div>
            </div>

            <div x-data="{ count: {{ str_word_count(strip_tags($tribute->tribute_text)) }} }">
                <div class="flex items-center justify-between mb-1.5">
                    <label for="tribute_text" class="block text-sm font-semibold text-slate-700">Tribute Text</label>
                    <span class="text-xs" :class="count > 250 ? 'text-red-500 font-bold' : 'text-slate-400'">
                        <span x-text="count"></span> / 250 words
                    </span>
                </div>
                <textarea id="tribute_text" name="tribute_text" rows="8" required
                    @input="count = $el.value.trim().split(/\s+/).filter(w => w).length"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition resize-y @error('tribute_text') border-red-400 @enderror"
                    placeholder="Share a few words about this beloved classmate…">{{ old('tribute_text', $tribute->tribute_text) }}</textarea>
                @error('tribute_text')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-4 pt-2 border-t border-slate-100">
                <button type="submit" class="btn-primary">Save Tribute</button>
                <a href="{{ route('tributes.show', $tribute) }}" class="btn-ghost">View Page</a>
            </div>
        </form>
    </div>
</div>

@endsection
