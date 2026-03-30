@extends('layouts.app')
@section('title', 'Class Reunion Store')

@section('content')

<x-page-header title="Class Reunion Store" subtitle="Exclusive Class of 1975 memorabilia">
    @auth
    <x-slot name="actions">
        <a href="{{ route('cart.index') }}" class="relative inline-flex items-center gap-2 btn-gold text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Cart
            @if ($cartCount > 0)
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center">
                {{ $cartCount }}
            </span>
            @endif
        </a>
    </x-slot>
    @endauth
</x-page-header>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if ($products->isEmpty())
    <div class="text-center py-20">
        <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
        <p class="text-slate-400 text-lg">No products available yet.</p>
        @can('admin')
        <a href="{{ route('admin.products.create') }}" class="mt-2 inline-block text-sm text-gold-600 hover:underline">Add the first product →</a>
        @endcan
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($products as $product)
        <div class="card overflow-hidden flex flex-col card-hover">

            {{-- Product image --}}
            <div class="aspect-square bg-slate-100 overflow-hidden">
                @if ($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                     class="w-full h-full object-cover" loading="lazy">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="p-4 flex flex-col flex-1">
                <h2 class="font-semibold text-slate-800 text-sm leading-snug">{{ $product->name }}</h2>
                @if ($product->description)
                <p class="text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2 flex-1">
                    {{ $product->description }}
                </p>
                @endif

                <div class="mt-3 flex items-center justify-between">
                    <span class="font-bold text-slate-800">${{ number_format($product->price, 2) }}</span>
                    @if ($product->stock < 5 && $product->stock > 0)
                    <span class="text-xs text-amber-600 font-semibold">Only {{ $product->stock }} left</span>
                    @elseif ($product->stock === 0)
                    <span class="text-xs text-red-600 font-semibold">Out of stock</span>
                    @endif
                </div>

                @auth
                    @if ($product->stock > 0)
                    <form method="POST" action="{{ route('cart.add') }}" class="mt-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full btn-primary justify-center text-xs">Add to Cart</button>
                    </form>
                    @else
                    <button disabled class="mt-3 w-full bg-slate-200 text-slate-400 font-semibold py-2.5 rounded-xl text-xs cursor-not-allowed">
                        Out of Stock
                    </button>
                    @endif
                @else
                <a href="{{ route('login') }}" class="mt-3 block w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-2.5 rounded-xl text-xs transition-colors">
                    Sign In to Buy
                </a>
                @endauth
            </div>
        </div>
        @endforeach
    </div>

    @if ($products->hasPages())
    <div class="mt-10">{{ $products->links() }}</div>
    @endif
    @endif

</div>

@endsection
