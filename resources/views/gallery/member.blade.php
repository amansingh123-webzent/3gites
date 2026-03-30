@extends('layouts.app')
@section('title', $user->name . '\'s Gallery')

@section('content')

<div x-data>
<div class="bg-purple-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-purple-300 mb-4">
            <a href="{{ route('gallery.index', ['tab' => 'members']) }}" class="hover:text-white transition-colors">Member Galleries</a>
            <span class="mx-2">›</span>
            <span class="text-white">{{ $user->name }}</span>
        </nav>
        <div class="flex items-end justify-between gap-4 flex-wrap">
            <div>
                <h1 class="font-display text-3xl font-semibold text-white">{{ $user->name }}'s Gallery</h1>
                <p class="text-purple-200 text-sm mt-1">{{ $total }} {{ Str::plural('photo', $total) }}</p>
            </div>
            @auth
            @if (auth()->id() === $user->id && $total < 50)
            <button @click="$refs.uploadPanel.classList.toggle('hidden')" class="btn-gold text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Photo
            </button>
            @endif
            @endauth
        </div>
    </div>
</div>

{{-- Upload panel (owner only) --}}
@auth
@if (auth()->id() === $user->id)
<div x-ref="uploadPanel" class="hidden bg-slate-800 border-b border-slate-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        @include('gallery._upload-form', [
            'action'      => route('gallery.upload'),
            'used'        => $total,
            'limit'       => 50,
            'label'       => 'Upload Photo',
            'description' => "Your gallery: {$total} / 50 photos",
        ])
    </div>
</div>
@endif
@endauth
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if ($photos->isEmpty())
    <div class="text-center py-20">
        <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <p class="text-slate-400 text-lg">No photos yet.</p>
    </div>
    @else
        @include('gallery._photo-grid', [
            'photos'    => $photos,
            'galleryId' => 'member-gallery-' . $user->id,
            'showOwner' => false,
            'isAdmin'   => auth()->check() && auth()->user()->hasRole('admin'),
            'owner'     => $user,
        ])
        @if ($photos->hasPages())
        <div class="mt-10">{{ $photos->links() }}</div>
        @endif
    @endif

    <div class="mt-8">
        <a href="{{ route('gallery.index', ['tab' => 'members']) }}" class="text-sm text-slate-400 hover:text-purple-900 transition-colors">
            ← Back to Member Galleries
        </a>
    </div>
</div>

@endsection
