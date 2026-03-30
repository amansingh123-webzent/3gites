@extends('layouts.app')
@section('title', 'Your Cart')

@section('content')

<x-page-header title="Your Cart" :back="route('store.index')" backLabel="Continue Shopping" />

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if (empty($items))
    <div class="text-center py-20 card border-dashed border-slate-200">
        <svg class="w-12 h-12 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <p class="text-slate-400 text-lg">Your cart is empty.</p>
        <a href="{{ route('store.index') }}" class="mt-3 inline-block text-sm text-gold-600 hover:underline">Browse the store →</a>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Cart items --}}
        <div class="lg:col-span-2 space-y-3">
            @foreach ($items as $item)
            <div class="card px-5 py-4 flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0">
                    @if ($item['product']->image)
                    <img src="{{ Storage::url($item['product']->image) }}" class="w-full h-full object-cover" alt="{{ $item['product']->name }}">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm text-slate-800">{{ $item['product']->name }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">${{ number_format($item['product']->price, 2) }} each</p>
                </div>

                <form method="POST" action="{{ route('cart.update', $item['product']->id) }}" class="flex items-center gap-2">
                    @csrf @method('PATCH')
                    <select name="quantity" onchange="this.form.submit()"
                        class="border border-slate-300 rounded-lg px-2 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-gold-500">
                        @for ($q = 1; $q <= min(10, $item['product']->stock); $q++)
                        <option value="{{ $q }}" {{ $item['quantity'] === $q ? 'selected' : '' }}>{{ $q }}</option>
                        @endfor
                    </select>
                </form>

                <span class="font-bold text-sm text-slate-800 min-w-[60px] text-right">
                    ${{ number_format($item['total'], 2) }}
                </span>

                <form method="POST" action="{{ route('cart.remove', $item['product']->id) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors" title="Remove">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </form>
            </div>
            @endforeach

            <form method="POST" action="{{ route('cart.clear') }}" class="text-right">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs text-slate-400 hover:text-red-500 transition-colors">
                    Clear cart
                </button>
            </form>
        </div>

        {{-- Order summary --}}
        <div class="lg:col-span-1">
            <div class="card px-6 py-6 sticky top-20">
                <h2 class="font-display text-lg font-semibold text-slate-800 mb-4">Order Summary</h2>

                <div class="space-y-2 text-sm mb-4">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-400 text-xs">
                        <span>Shipping</span>
                        <span>Calculated at checkout</span>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-3 mb-5 flex justify-between font-bold text-slate-800">
                    <span>Total</span>
                    <span>${{ number_format($subtotal, 2) }}+</span>
                </div>

                <form method="POST" action="{{ route('checkout') }}">
                    @csrf
                    <button type="submit" class="w-full btn-primary justify-center">
                        Proceed to Checkout →
                    </button>
                </form>

                <p class="text-center text-xs text-slate-400 mt-3 flex items-center justify-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Secure checkout via Stripe
                </p>
            </div>
        </div>
    </div>
    @endif

</div>

@endsection
