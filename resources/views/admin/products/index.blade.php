@extends('layouts.app')
@section('title', 'Manage Products')

@section('content')

<x-page-header title="Store Products" subtitle="Manage items available for purchase"
    :back="route('admin.dashboard')" backLabel="Admin">
    <x-slot name="actions">
        <a href="{{ route('admin.products.create') }}" class="btn-gold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Product
        </a>
    </x-slot>
</x-page-header>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="card divide-y divide-slate-50 overflow-hidden">
        @forelse ($products as $product)
        <div class="flex items-center gap-4 px-5 py-4 hover:bg-slate-50 transition-colors">
            {{-- Thumbnail --}}
            <div class="w-12 h-12 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0">
                @if ($product->image)
                <img src="{{ Storage::url($product->image) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                @endif
            </div>

            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm text-slate-800 truncate">{{ $product->name }}</p>
                <p class="text-xs text-slate-400 mt-0.5">
                    ${{ number_format($product->price, 2) }}
                    · Stock: {{ $product->stock }}
                    @if ($product->stock === 0)
                    <span class="text-red-500 font-semibold">· OUT OF STOCK</span>
                    @endif
                </p>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <form method="POST" action="{{ route('admin.products.toggle', $product) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs px-3 py-1.5 rounded-lg border transition-colors
                        {{ $product->is_active
                            ? 'border-emerald-200 text-emerald-600 hover:border-red-300 hover:text-red-500'
                            : 'border-slate-200 text-slate-400 hover:border-emerald-300 hover:text-emerald-600' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </button>
                </form>
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="text-xs px-3 py-1.5 rounded-lg border border-slate-200 text-slate-500 hover:border-purple-300 hover:text-purple-700 transition-colors">
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                      onsubmit="return confirm('Delete {{ addslashes($product->name) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs px-3 py-1.5 rounded-lg border border-slate-200 text-slate-400 hover:border-red-300 hover:text-red-500 transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-slate-400">
            No products yet.
            <a href="{{ route('admin.products.create') }}" class="text-gold-600 hover:underline ml-1">Add one →</a>
        </div>
        @endforelse
    </div>

    @if ($products->hasPages())
    <div class="mt-6">{{ $products->links() }}</div>
    @endif

</div>

@endsection
