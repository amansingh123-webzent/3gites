@extends('layouts.app')
@section('title', 'Class Directory')

@section('content')

{{-- Page header --}}
<x-page-header title="Class Directory" subtitle="Clarendon College · Class of 1975" />

{{-- Stats bar --}}
<div class="bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center gap-3">
        <span class="text-xs font-semibold text-slate-500 mr-2 uppercase tracking-wide">Members:</span>
        <span class="badge-active">{{ $counts['active'] ?? 0 }} Active</span>
        <span class="badge-searching">{{ $counts['searching'] ?? 0 }} Searching</span>
        <span class="badge-memoriam">{{ $counts['memoriam'] ?? $counts['deceased'] ?? 0 }} In Memoriam</span>
    </div>
</div>

{{-- Filter tabs (Alpine) --}}
<div class="bg-white border-b border-slate-200" x-data="{ tab: '{{ request('status', 'all') }}' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex gap-0 overflow-x-auto">
            @foreach(['all' => 'All Members', 'active' => 'Active', 'searching' => 'Searching', 'memoriam' => 'In Memoriam'] as $key => $label)
            <button
                @click="tab = '{{ $key }}'"
                :class="tab === '{{ $key }}' ? 'border-b-2 border-purple-900 text-purple-900 font-semibold' : 'text-slate-500 hover:text-slate-700'"
                class="px-4 sm:px-6 py-3 text-sm whitespace-nowrap transition-colors focus:outline-none"
            >{{ $label }}</button>
            @endforeach
        </div>
    </div>

    {{-- Member grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
            @forelse($members as $member)
            @php $status = $member->status ?? $member->member_status ?? 'active'; @endphp
            <div
                x-show="tab === 'all' || tab === '{{ $status }}'"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                class="group"
            >
                <a href="{{ route('members.show', $member) }}" class="block">
                    <div class="relative aspect-square rounded-2xl overflow-hidden bg-purple-100 group-hover:shadow-md group-hover:-translate-y-0.5 transition-all duration-200">
                        @if($member->photo_recent || $member->photo_1975 || $member->profile?->recent_photo || $member->profile?->teen_photo)
                        @php $photoPath = $member->photo_recent ?? $member->photo_1975 ?? $member->profile?->recent_photo ?? $member->profile?->teen_photo; @endphp
                        <img
                            src="{{ Storage::url($photoPath) }}"
                            alt="{{ $member->name }}"
                            loading="lazy"
                            class="w-full h-full object-cover {{ $status === 'memoriam' || $status === 'deceased' ? 'grayscale' : '' }}"
                        >
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-purple-200">
                            <span class="font-display font-bold text-5xl text-purple-600">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                        </div>
                        @endif

                        @if($status === 'memoriam' || $status === 'deceased')
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/75 to-transparent flex items-end p-3">
                            <span class="text-white text-xs italic font-display">In Memoriam</span>
                        </div>
                        @endif

                        @if($status === 'searching')
                        <div class="absolute top-2 right-2">
                            <span class="bg-amber-400 text-amber-900 text-xs font-bold px-2 py-0.5 rounded-full shadow">Searching</span>
                        </div>
                        @endif
                    </div>
                    <div class="mt-2 text-center px-1">
                        <p class="font-semibold text-slate-800 text-sm leading-tight line-clamp-2">{{ $member->name }}</p>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-span-full py-16 text-center">
                <div class="text-5xl mb-4">👥</div>
                <p class="text-slate-400">No members found.</p>
            </div>
            @endforelse
        </div>

        @if(method_exists($members, 'links'))
        <div class="mt-8">{{ $members->links() }}</div>
        @endif
    </div>
</div>

@endsection
