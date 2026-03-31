@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')

<x-page-header title="Admin Dashboard" subtitle="3Gites-1975 · Site Management" />

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="card p-5 text-center">
            <div class="text-3xl font-bold font-display text-emerald-600">{{ $stats['active_members'] }}</div>
            <div class="text-xs font-semibold text-slate-500 mt-1.5">Active Members</div>
        </div>
        <div class="card p-5 text-center">
            <div class="text-3xl font-bold font-display text-amber-500">{{ $stats['searching'] }}</div>
            <div class="text-xs font-semibold text-slate-500 mt-1.5">Searching</div>
        </div>
        <div class="card p-5 text-center">
            <div class="text-3xl font-bold font-display text-slate-400">{{ $stats['deceased'] }}</div>
            <div class="text-xs font-semibold text-slate-500 mt-1.5">In Memoriam</div>
        </div>
        <div class="card p-5 text-center">
            <div class="text-3xl font-bold font-display text-red-500">{{ $stats['locked_accounts'] }}</div>
            <div class="text-xs font-semibold text-slate-500 mt-1.5">Locked Accounts</div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div>
        <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach ([
                ['Create Member',    route('admin.members.create'),    'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
                ['Manage Events',    route('admin.events.index'),      'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['Manage Polls',     route('admin.polls.index'),       'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['Store & Orders',   route('admin.orders.index'),      'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                ['Tribute Pages',    route('tributes.index'),          'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                ['Broadcast Email',  route('admin.broadcast.index'),   'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['Products',         route('admin.products.index'),    'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['Donate',           'https://www.zeffy.com/en-US/donation-form/donate-to-3gites--1975', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ] as [$label, $url, $path])
            <a href="{{ $url }}"
               @if($label === 'Donate') target="_blank" @endif
               class="card card-hover p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $path }}"/>
                    </svg>
                </div>
                <span class="font-semibold text-sm text-slate-700">{{ $label }}</span>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Recent Members --}}
    <div class="card overflow-hidden">
        <div class="px-6 py-4 bg-purple-900 flex items-center justify-between">
            <h2 class="font-semibold text-white text-sm">Recent Members</h2>
            <a href="{{ route('members.index') }}" class="text-gold-400 text-xs font-semibold hover:text-gold-300 transition-colors">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Member</th>
                        <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Email</th>
                        <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach ($recentMembers as $member)
                    @php $status = $member->member_status ?? $member->status ?? 'active'; @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                                    <span class="font-bold text-purple-700 text-xs font-display">{{ strtoupper(substr($member->name, 0, 2)) }}</span>
                                </div>
                                <span class="font-medium text-slate-800">{{ $member->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-slate-500">{{ $member->email ?? '—' }}</td>
                        <td class="px-5 py-3"><x-badge :type="$status" /></td>
                        <td class="px-5 py-3">
                            @if ($member->account_locked ?? false)
                            <form method="POST" action="{{ route('admin.members.toggle-lock', $member) }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs text-purple-700 hover:underline font-semibold">Activate</button>
                            </form>
                            @else
                            <a href="{{ route('profile.edit', $member) }}" class="text-xs text-gold-600 hover:underline font-semibold">Edit</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
