@extends('layouts.app')
@section('title', 'Order #' . $order->id)

@section('content')

<x-page-header :title="'Order #' . $order->id"
    :back="route('admin.orders.index')" backLabel="Orders" />

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Customer + Items --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Customer info --}}
            <div class="card px-6 py-5">
                <h2 class="font-display text-lg font-bold text-slate-800 mb-4">Customer Information</h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Name</dt>
                        <dd class="font-medium text-slate-800">{{ $order->user->name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Email</dt>
                        <dd class="font-medium text-slate-800">{{ $order->user->email }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Member Status</dt>
                        <dd class="font-medium text-slate-800">{{ ucfirst($order->user->member_status) }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Order items --}}
            <div class="card px-6 py-5">
                <h2 class="font-display text-lg font-bold text-slate-800 mb-4">Order Items</h2>
                <div class="space-y-3">
                    @foreach ($order->items as $item)
                    <div class="flex items-center gap-4 pb-3 border-b border-slate-100 last:border-0">
                        <div class="w-12 h-12 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0">
                            @if ($item->product?->image)
                            <img src="{{ Storage::url($item->product->image) }}" class="w-full h-full object-cover" alt="{{ $item->product->name }}">
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-slate-800">{{ $item->product?->name ?? 'Product' }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">${{ number_format($item->price, 2) }} each</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs text-slate-400">Qty {{ $item->quantity }}</p>
                            <p class="font-bold text-sm text-slate-800">${{ number_format($item->price * $item->quantity, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right: Status + Summary + Timestamps --}}
        <div class="space-y-6">

            {{-- Order status --}}
            <div class="card px-6 py-5">
                <h2 class="font-display text-lg font-bold text-slate-800 mb-4">Order Status</h2>
                @php
                    $badge = match($order->status) {
                        'paid'     => 'bg-emerald-100 text-emerald-700',
                        'pending'  => 'bg-amber-100 text-amber-700',
                        'failed'   => 'bg-red-100 text-red-600',
                        'refunded' => 'bg-slate-100 text-slate-500',
                        default    => 'bg-slate-100 text-slate-500',
                    };
                @endphp
                <div class="text-center py-2 mb-3">
                    <span class="text-sm font-semibold px-3 py-1.5 rounded-full {{ $badge }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                @if ($order->status === 'paid')
                <form method="POST" action="{{ route('admin.orders.refund', $order) }}"
                      onsubmit="return confirm('Refund this order? This will process a full refund via Stripe.')">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors">
                        Issue Refund
                    </button>
                </form>
                @endif
            </div>

            {{-- Order summary --}}
            <div class="card px-6 py-5">
                <h2 class="font-display text-lg font-bold text-slate-800 mb-4">Order Summary</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-400 text-xs">
                        <span>Shipping</span>
                        <span>Calculated at checkout</span>
                    </div>
                    <div class="border-t border-slate-100 pt-2 flex justify-between font-bold text-slate-800">
                        <span>Total</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Timestamps --}}
            <div class="card px-6 py-5">
                <h2 class="font-display text-lg font-bold text-slate-800 mb-4">Timestamps</h2>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Placed</span>
                        <span class="text-slate-700">{{ $order->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Updated</span>
                        <span class="text-slate-700">{{ $order->updated_at->format('M j, Y g:i A') }}</span>
                    </div>
                    @if ($order->stripe_payment_id)
                    <div class="flex justify-between gap-2 mt-1">
                        <span class="text-slate-500 flex-shrink-0">Stripe ID</span>
                        <span class="font-mono text-slate-400 truncate">{{ $order->stripe_payment_id }}</span>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>

@endsection
