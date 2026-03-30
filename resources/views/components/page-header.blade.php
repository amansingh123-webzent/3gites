@props(['title', 'subtitle' => null, 'back' => null, 'backLabel' => 'Back'])

<div class="bg-purple-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if($back)
            <a href="{{ $back }}" class="inline-flex items-center gap-1 text-purple-300 hover:text-white text-sm mb-3 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                {{ $backLabel }}
            </a>
        @endif
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="font-display text-3xl font-semibold text-white">{{ $title }}</h1>
                @if($subtitle)
                    <p class="mt-1 text-purple-200 text-sm">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex items-center gap-3">{{ $actions }}</div>
            @endisset
        </div>
    </div>
</div>
