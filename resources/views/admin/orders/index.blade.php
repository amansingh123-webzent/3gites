@extends('layouts.app')
@section('title', 'Orders')

@section('content')

<x-page-header title="Store Orders" subtitle="Review and manage member purchases"
    :back="route('admin.dashboard')" backLabel="Admin" />

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Status filters --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach (['all', 'pending', 'paid', 'failed', 'refunded'] as $s)
        <a href="{{ route('admin.orders.index', ['status' => $s]) }}"
           class="px-4 py-1.5 rounded-full text-xs font-semibold border transition-colors
               {{ $status === $s
                   ? 'bg-purple-900 text-white border-purple-900'
                   : 'bg-white text-slate-500 border-slate-200 hover:border-purple-300 hover:text-purple-700' }}">
            {{ ucfirst($s) }}
            <span class="ml-1 opacity-70">{{ $counts[$s] }}</span>
        </a>
        @endforeach
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">#</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Member</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Items</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Total</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Date</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($orders as $order)
                @php
                    $badge = match($order->status) {
                        'paid'     => 'bg-emerald-100 text-emerald-700',
                        'pending'  => 'bg-amber-100 text-amber-700',
                        'failed'   => 'bg-red-100 text-red-600',
                        'refunded' => 'bg-slate-100 text-slate-500',
                        default    => 'bg-slate-100 text-slate-500',
                    };
                @endphp
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3 font-mono text-xs text-slate-400">#{{ $order->id }}</td>
                    <td class="px-5 py-3 font-medium text-slate-800">{{ $order->user->name }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</td>
                    <td class="px-5 py-3 font-bold text-slate-800">${{ number_format($order->total, 2) }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badge }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-slate-400 text-xs">{{ $order->created_at->format('M j, Y') }}</td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="text-xs text-gold-600 hover:text-gold-700 font-medium transition-colors">View →</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-slate-400">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($orders->hasPages())
    <div class="mt-6">{{ $orders->links() }}</div>
    @endif

</div>

@endsection
