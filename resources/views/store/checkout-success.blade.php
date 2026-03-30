@extends('layouts.app')
@section('title', 'Order Confirmed!')
@section('content')

<div class="min-h-[60vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-lg w-full text-center">

        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="font-playfair text-4xl font-bold text-navy-900 mb-3">Order Confirmed!</h1>
        <p class="text-slate-600 text-lg mb-2">Thank you for supporting the Class of 1975.</p>
        <p class="text-slate-400 text-sm mb-8">A confirmation email has been sent to <strong>{{ auth()->user()->email }}</strong>.</p>

        @if ($order)
            <div class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-5 mb-8 text-left">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Order #{{ $order->id }}</p>
                @foreach ($order->items as $item)
                    <div class="flex justify-between text-sm py-1.5 border-b border-slate-100 last:border-0">
                        <span class="text-slate-600">{{ $item->product?->name ?? 'Product' }} × {{ $item->quantity }}</span>
                        <span class="font-semibold text-navy-900">${{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between text-sm pt-3 font-bold">
                    <span>Total</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        @else
            <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 mb-8 text-sm text-amber-700">
                Your payment is being processed. You'll receive a confirmation email shortly.
            </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('store.index') }}" class="bg-navy-900 hover:bg-navy-800 text-white font-semibold px-6 py-3 rounded-xl text-sm transition-colors">
                Back to Store
            </a>
            <a href="{{ route('home') }}" class="border border-slate-200 text-slate-600 hover:border-slate-300 font-semibold px-6 py-3 rounded-xl text-sm transition-colors">
                Go to Homepage
            </a>
        </div>
    </div>
</div>

@endsection
