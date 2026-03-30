@props(['item'])

<div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-5" x-data="{ qty: {{ $item->quantity }} }">
    <div class="flex items-center gap-4">
        {{-- Product thumbnail --}}
        <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
            @if($item->product->image)
                <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-slate-300 text-2xl">🛍️</div>
            @endif
        </div>

        {{-- Product info --}}
        <div class="flex-1 min-w-0">
            <h4 class="font-semibold text-slate-800 text-sm">{{ $item->product->name }}</h4>
            <p class="text-slate-400 text-xs mt-0.5">${{ number_format($item->product->price / 100, 2) }} each</p>
        </div>

        {{-- Quantity control --}}
        <div class="flex items-center gap-2">
            <button
                type="button"
                @click="if(qty > 1) { qty--; $refs.qtyInput{{ $item->id }}.value = qty; $refs.updateForm{{ $item->id }}.submit() }"
                class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gold-500"
                aria-label="Decrease quantity"
            >−</button>
            <span class="w-8 text-center font-semibold text-slate-800 text-sm" x-text="qty"></span>
            <button
                type="button"
                @click="qty++; $refs.qtyInput{{ $item->id }}.value = qty; $refs.updateForm{{ $item->id }}.submit()"
                class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gold-500"
                aria-label="Increase quantity"
            >+</button>
        </div>

        {{-- Line total --}}
        <div class="text-right min-w-[60px]">
            <p class="font-bold text-slate-800 text-sm">${{ number_format($item->product->price * $item->quantity / 100, 2) }}</p>
            <form action="{{ route('store.cart.remove', $item) }}" method="POST" class="inline" onsubmit="return confirm('Remove this item?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-400 hover:text-red-600 text-xs mt-1 transition-colors">Remove</button>
            </form>
        </div>
    </div>

    {{-- Hidden update form --}}
    <form id="form-{{ $item->id }}" x-ref="updateForm{{ $item->id }}" action="{{ route('store.cart.update', $item) }}" method="POST" class="hidden">
        @csrf @method('PATCH')
        <input type="number" x-ref="qtyInput{{ $item->id }}" name="quantity" :value="qty">
    </form>
</div>
