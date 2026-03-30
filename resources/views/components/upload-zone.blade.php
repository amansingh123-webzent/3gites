@props(['name' => 'photo', 'accept' => 'image/*', 'multiple' => false, 'label' => 'Upload Photo'])

<div
    x-data="{ dragging: false, preview: null, fileName: null }"
    @dragover.prevent="dragging = true"
    @dragleave.prevent="dragging = false"
    @drop.prevent="
        dragging = false;
        const file = $event.dataTransfer.files[0];
        if (file) {
            fileName = file.name;
            const reader = new FileReader();
            reader.onload = e => preview = e.target.result;
            reader.readAsDataURL(file);
            const dt = new DataTransfer();
            dt.items.add(file);
            $refs.fileInput.files = dt.files;
        }
    "
    :class="dragging ? 'border-gold-500 bg-gold-50' : 'border-slate-300 bg-slate-50 hover:bg-white'"
    class="border-2 border-dashed rounded-xl p-8 text-center cursor-pointer transition-colors"
    @click="$refs.fileInput.click()"
>
    <div x-show="!preview">
        <div class="text-4xl mb-3">📷</div>
        <p class="font-semibold text-slate-600 text-sm">Drag & drop or click to browse</p>
        <p class="text-slate-400 text-xs mt-1">JPG, PNG up to 10MB</p>
    </div>
    <div x-show="preview" @click.stop>
        <img :src="preview" class="max-h-40 mx-auto rounded-xl shadow-sm mb-2" alt="Preview">
        <p class="text-slate-500 text-xs" x-text="fileName"></p>
        <button type="button" @click="preview = null; fileName = null; $refs.fileInput.value = ''" class="text-red-400 hover:text-red-600 text-xs mt-1 transition-colors">Remove</button>
    </div>
    <input
        type="file"
        name="{{ $name }}"
        accept="{{ $accept }}"
        {{ $multiple ? 'multiple' : '' }}
        x-ref="fileInput"
        class="hidden"
        @change="
            const file = $el.files[0];
            if (file) {
                fileName = file.name;
                const reader = new FileReader();
                reader.onload = e => preview = e.target.result;
                reader.readAsDataURL(file);
            }
        "
    >
</div>
