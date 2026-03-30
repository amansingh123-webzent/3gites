@extends('layouts.app')
@section('title', 'Donation History')

@section('content')

<x-page-header title="Donation History" subtitle="All donations made through the portal"
    :back="route('admin.dashboard')" backLabel="Admin" />

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Summary cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="card p-5 text-center">
            <div class="text-3xl font-bold font-display text-gold-600">${{ number_format($totals['all_time'], 0) }}</div>
            <div class="text-xs text-slate-500 mt-1 uppercase tracking-wide">All-Time Total</div>
        </div>
        <div class="card p-5 text-center">
            <div class="text-3xl font-bold font-display text-emerald-600">${{ number_format($totals['this_year'], 0) }}</div>
            <div class="text-xs text-slate-500 mt-1 uppercase tracking-wide">This Year</div>
        </div>
        <div class="card p-5 text-center">
            <div class="text-3xl font-bold font-display text-purple-900">{{ $totals['count'] }}</div>
            <div class="text-xs text-slate-500 mt-1 uppercase tracking-wide">Total Donations</div>
        </div>
    </div>

    {{-- Donations table --}}
    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Member</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Amount</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Message</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($donations as $donation)
                @php
                    $badge = match($donation->status) {
                        'completed' => 'bg-emerald-100 text-emerald-700',
                        'pending'   => 'bg-amber-100 text-amber-700',
                        'failed'    => 'bg-red-100 text-red-600',
                        default     => 'bg-slate-100 text-slate-500',
                    };
                @endphp
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3 font-medium text-slate-800">{{ $donation->user?->name ?? '—' }}</td>
                    <td class="px-5 py-3 font-bold text-gold-600">${{ number_format($donation->amount, 2) }}</td>
                    <td class="px-5 py-3 text-slate-500 italic max-w-xs truncate">
                        {{ $donation->message ? '"' . $donation->message . '"' : '—' }}
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badge }}">
                            {{ ucfirst($donation->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-slate-400 text-xs">{{ $donation->created_at->format('M j, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-12 text-center text-slate-400">No donations yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($donations->hasPages())
    <div class="mt-6">{{ $donations->links() }}</div>
    @endif

</div>

@endsection
