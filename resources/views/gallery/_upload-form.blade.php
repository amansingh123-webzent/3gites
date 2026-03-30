{{--
    Reusable upload form.
    Props: $action, $used, $limit, $label, $description
--}}

@php $remaining = max(0, $limit - $used); @endphp

<form
    method="POST"
    action="{{ $action }}"
    enctype="multipart/form-data"
    x-data="{
        file: null,
        preview: null,
        caption: '',
        dragging: false,
        onFile(e) {
            const f = e.target.files[0] ?? e.dataTransfer?.files[0];
            if (!f) return;
            this.file = f;
            const reader = new FileReader();
            reader.onload = (ev) => this.preview = ev.target.result;
            reader.readAsDataURL(f);
        }
    }"
    @dragover.prevent="dragging = true"
    @dragleave.prevent="dragging = false"
    @drop.prevent="dragging = false; onFile($event)"
>
    @csrf

    <div class="flex flex-wrap items-start gap-5">

        {{-- Drop zone / preview --}}
        <div
            class="relative w-32 h-32 rounded-xl border-2 border-dashed transition-colors cursor-pointer flex-shrink-0 overflow-hidden
                   {{ $remaining === 0 ? 'border-slate-600 cursor-not-allowed' : 'border-slate-500 hover:border-gold-400' }}"
            :class="{ 'border-gold-400 bg-gold-400/10': dragging }"
            @click="$refs.fileInput.click()"
        >
            <template x-if="preview">
                <img :src="preview" class="w-full h-full object-cover">
            </template>
            <template x-if="!preview">
                <div class="w-full h-full flex flex-col items-center justify-center gap-1 text-slate-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span class="text-xs text-center leading-tight">Drop or click<br>to select</span>
                </div>
            </template>
            <input
                x-ref="fileInput"
                type="file"
                name="photo"
                accept="image/jpeg,image/png,image/gif,image/webp"
                class="hidden"
                @change="onFile($event)"
                {{ $remaining === 0 ? 'disabled' : '' }}
            >
        </div>

        {{-- Caption + submit --}}
        <div class="flex-1 min-w-[200px] space-y-3">
            <div>
                <p class="text-sm font-semibold text-white mb-0.5">{{ $label }}</p>
                <p class="text-xs text-slate-400">{{ $description }}</p>
            </div>

            @if ($remaining === 0)
                <div class="bg-red-900/40 border border-red-700 text-red-300 text-xs rounded-lg px-3 py-2">
                    Gallery full ({{ $limit }}/{{ $limit }}). Delete some photos to upload more.
                </div>
            @else
                <input
                    type="text"
                    name="caption"
                    x-model="caption"
                    maxlength="500"
                    class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500"
                    placeholder="Caption (optional)"
                >

                {{-- Validation errors --}}
                @error('photo')
                    <p class="text-xs text-red-400">{{ $message }}</p>
                @enderror

                <div class="flex items-center gap-3">
                    <button
                        type="submit"
                        :disabled="!file"
                        class="bg-gold-500 hover:bg-gold-400 disabled:opacity-40 disabled:cursor-not-allowed text-navy-900 font-bold px-5 py-2 rounded-lg text-sm transition-colors"
                    >
                        Upload
                    </button>
                    <span class="text-xs text-slate-400">
                        {{ $remaining }} {{ Str::plural('slot', $remaining) }} remaining
                    </span>
                </div>
            @endif
        </div>
    </div>
</form>
