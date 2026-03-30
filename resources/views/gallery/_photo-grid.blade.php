{{--
    Reusable photo grid with integrated Alpine.js lightbox.

    Props (passed via @include):
        $photos    — paginated or collection of Photo models
        $galleryId — unique string ID for this gallery's lightbox
        $showOwner — bool: show uploader name on card
        $isAdmin   — bool: show delete button for admin
        $owner     — optional User: if set, show delete for that user's own photos
--}}

@php
    // Build the flat JS array of photo data for the lightbox
    $lightboxPhotos = $photos->map(fn ($p) => [
        'src'     => Storage::disk('public')->url($p->file_path),
        'caption' => $p->caption ?? '',
        'id'      => $p->id,
    ])->values()->toJson();
@endphp

<div
    x-data="photoLightbox({{ $lightboxPhotos }})"
    @keydown.escape.window="close()"
    @keydown.arrow-left.window="prev()"
    @keydown.arrow-right.window="next()"
    id="{{ $galleryId }}"
>

    {{-- Photo grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 sm:gap-3">
        @foreach ($photos as $index => $photo)
            @php
                $canDelete = auth()->check() && (
                    ($owner ?? null)?->id === auth()->id()
                    || $isAdmin
                    || auth()->id() === $photo->user_id
                );
            @endphp

            <div class="relative group aspect-square bg-slate-100 rounded-xl overflow-hidden cursor-pointer shadow-sm hover:shadow-md transition-shadow duration-200">

                {{-- Thumbnail --}}
                <img
                    src="{{ Storage::disk('public')->url($photo->file_path) }}"
                    alt="{{ $photo->caption ?? 'Gallery photo' }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    loading="lazy"
                    @click="open({{ $loop->index }})"
                >

                {{-- Hover overlay --}}
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent
                           opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex flex-col justify-end p-2.5"
                    @click="open({{ $loop->index }})"
                >
                    @if ($photo->caption)
                        <p class="text-white text-xs leading-snug line-clamp-2">{{ $photo->caption }}</p>
                    @endif
                    @if ($showOwner ?? false)
                        <p class="text-white/60 text-[10px] mt-0.5">{{ $photo->user->name }}</p>
                    @endif
                </div>

                {{-- Delete button (appears on hover, only for owner/admin) --}}
                @if ($canDelete)
                    <form
                        method="POST"
                        action="{{ route('gallery.destroy', $photo) }}"
                        class="absolute top-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition-opacity duration-150 z-10"
                        onsubmit="return confirm('Delete this photo?')"
                        @click.stop
                    >
                        @csrf @method('DELETE')
                        <button
                            type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md transition-colors"
                            title="Delete photo"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </form>
                @endif

            </div>
        @endforeach
    </div>

    {{-- ── Lightbox Modal ──────────────────────────────────────────────────── --}}
    <div
        x-show="isOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center"
        style="background: rgba(0,0,0,0.92)"
        @click.self="close()"
    >
        {{-- Close button --}}
        <button
            @click="close()"
            class="absolute top-4 right-4 text-white/60 hover:text-white transition-colors z-20"
            aria-label="Close"
        >
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Previous --}}
        <button
            @click="prev()"
            x-show="photos.length > 1"
            class="absolute left-3 sm:left-6 text-white/60 hover:text-white transition-colors z-20 p-2 rounded-full hover:bg-white/10"
            aria-label="Previous photo"
        >
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        {{-- Next --}}
        <button
            @click="next()"
            x-show="photos.length > 1"
            class="absolute right-3 sm:right-6 text-white/60 hover:text-white transition-colors z-20 p-2 rounded-full hover:bg-white/10"
            aria-label="Next photo"
        >
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        {{-- Image container --}}
        <div class="relative flex flex-col items-center justify-center max-w-5xl w-full px-16 sm:px-24">

            {{-- Main image --}}
            <template x-if="currentPhoto">
                <div class="flex flex-col items-center">
                    <img
                        :src="currentPhoto.src"
                        :alt="currentPhoto.caption || 'Gallery photo'"
                        class="max-h-[80vh] max-w-full object-contain rounded-lg shadow-2xl"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                    >

                    {{-- Caption + counter --}}
                    <div class="mt-4 text-center">
                        <p
                            x-show="currentPhoto.caption"
                            x-text="currentPhoto.caption"
                            class="text-white/80 text-sm max-w-xl leading-relaxed"
                        ></p>
                        <p class="text-white/40 text-xs mt-2">
                            <span x-text="currentIndex + 1"></span> / <span x-text="photos.length"></span>
                        </p>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
