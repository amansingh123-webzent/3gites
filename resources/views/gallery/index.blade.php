@extends('layouts.app')
@section('title', 'Photo Gallery')

@section('content')

<div x-data="{ 
    showMemberUpload: false, 
    showAdminUpload: false,
    toggleMemberUpload() { 
        this.showMemberUpload = !this.showMemberUpload; 
    },
    toggleAdminUpload() { 
        this.showAdminUpload = !this.showAdminUpload; 
    }
}">

<x-page-header title="Photo Gallery" subtitle="Memories of the Class of 1975">
    @auth
    @if (auth()->user()->member_status === 'active' || auth()->user()->status === 'active')
    <x-slot name="actions">
        <button @click="@if(auth()->user()->hasRole('admin')) toggleAdminUpload() @else toggleMemberUpload() @endif" class="btn-gold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Add Photo
        </button>
    </x-slot>
    @endif
    @endauth
</x-page-header>

{{-- Member upload panel --}}
@auth
@if (auth()->user()->member_status === 'active' || auth()->user()->status === 'active')
<div x-show="showMemberUpload" 
     x-collapse
     class="bg-slate-800 border-b border-slate-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        @include('gallery._upload-form', [
            'action'      => route('gallery.upload'),
            'used'        => $myPhotoCount ?? 0,
            'limit'       => 50,
            'label'       => 'Add to My Gallery',
            'description' => 'Your gallery: ' . ($myPhotoCount ?? 0) . ' / 50 photos used',
        ])
    </div>
</div>
@endif
@endauth

{{-- Admin upload panel --}}
@can('admin')
<div x-show="showAdminUpload" 
     x-collapse
     class="bg-slate-700 border-b border-slate-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-gold-400 uppercase tracking-widest">Admin</span>
                <span class="text-slate-400 text-sm">
                    Class Gallery: {{ $adminCapacity['used'] ?? 0 }} / {{ $adminCapacity['limit'] ?? 500 }} photos
                </span>
                <div class="w-32 h-1.5 bg-slate-600 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 {{ ($adminCapacity['percent'] ?? 0) > 90 ? 'bg-red-500' : 'bg-gold-500' }}"
                         style="width: {{ $adminCapacity['percent'] ?? 0 }}%"></div>
                </div>
            </div>
            <button @click="toggleAdminUpload()" class="text-xs text-gold-400 hover:text-gold-300 font-medium border border-gold-600 hover:border-gold-400 px-3 py-1.5 rounded-lg transition-colors">
                + Upload to Class Gallery
            </button>
        </div>
        
        {{-- Upload form - always show when panel is open --}}
        <div class="border-t border-slate-600 pt-4">
            @include('gallery._upload-form', [
                'action'      => route('gallery.admin.upload'),
                'used'        => $adminCapacity['used'] ?? 0,
                'limit'       => $adminCapacity['limit'] ?? 500,
                'label'       => 'Upload to Class Gallery',
                'description' => 'This photo will appear in the shared class gallery.',
            ])
        </div>
    </div>
</div>
@endcan

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Tab switcher --}}
    <div class="flex gap-1 mb-8 bg-slate-100 p-1 rounded-xl w-fit">
        <a href="{{ route('gallery.index', ['tab' => 'class']) }}"
           class="px-5 py-2 rounded-lg text-sm font-semibold transition-colors {{ $tab === 'class' ? 'bg-white text-purple-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            Class Gallery
            <span class="ml-1.5 text-xs {{ $tab === 'class' ? 'text-gold-600' : 'text-slate-400' }}">
                {{ $adminCapacity['used'] ?? 0 }}
            </span>
        </a>
        <a href="{{ route('gallery.index', ['tab' => 'members']) }}"
           class="px-5 py-2 rounded-lg text-sm font-semibold transition-colors {{ $tab === 'members' ? 'bg-white text-purple-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            Member Galleries
            <span class="ml-1.5 text-xs {{ $tab === 'members' ? 'text-gold-600' : 'text-slate-400' }}">
                {{ $totalMemberPhotos ?? 0 }}
            </span>
        </a>
    </div>

    {{-- Class Gallery Tab --}}
    @if ($tab === 'class')
        @if ($adminPhotos->isEmpty())
        <div class="text-center py-20">
            <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-slate-400 text-lg">No photos in the class gallery yet.</p>
        </div>
        @else
            @include('gallery._photo-grid', [
                'photos'    => $adminPhotos,
                'galleryId' => 'class-gallery',
                'showOwner' => false,
                'isAdmin'   => auth()->check() && auth()->user()->hasRole('admin'),
            ])
            @if ($adminPhotos->hasPages())
            <div class="mt-10">{{ $adminPhotos->links() }}</div>
            @endif
        @endif

    {{-- Member Galleries Tab --}}
    @else
        @if ($memberPhotos->isEmpty())
        <div class="text-center py-20">
            <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-slate-400 text-lg">No member photos yet.</p>
        </div>
        @else
            @include('gallery._photo-grid', [
                'photos'    => $memberPhotos,
                'galleryId' => 'member-gallery',
                'showOwner' => true,
                'isAdmin'   => auth()->check() && auth()->user()->hasRole('admin'),
            ])
            @if ($memberPhotos->hasPages())
            <div class="mt-10">{{ $memberPhotos->links() }}</div>
            @endif
        @endif
    @endif

</div>

@endsection
