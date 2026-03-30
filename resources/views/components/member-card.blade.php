@props(['member'])

<a href="{{ route('members.show', $member) }}" class="group block">
    <div class="relative aspect-square rounded-2xl overflow-hidden bg-purple-100 group-hover:shadow-md group-hover:-translate-y-0.5 transition-all duration-200">
        @if($member->photo_recent || $member->photo_1975)
            <img
                src="{{ Storage::url($member->photo_recent ?? $member->photo_1975) }}"
                alt="{{ $member->name }}"
                class="w-full h-full object-cover {{ $member->status === 'memoriam' ? 'grayscale' : '' }}"
            >
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-purple-200">
                <span class="font-display font-bold text-5xl text-purple-600">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
            </div>
        @endif

        @if($member->status === 'memoriam')
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/75 to-transparent flex items-end p-3">
                <span class="text-white text-xs italic font-display">In Memoriam</span>
            </div>
        @endif

        @if($member->status === 'searching')
            <div class="absolute top-2 right-2">
                <span class="bg-amber-400 text-amber-900 text-xs font-bold px-2 py-0.5 rounded-full shadow">Searching</span>
            </div>
        @endif
    </div>
    <div class="mt-2 text-center px-1">
        <p class="font-semibold text-slate-800 text-sm leading-tight line-clamp-2">{{ $member->name }}</p>
    </div>
</a>
