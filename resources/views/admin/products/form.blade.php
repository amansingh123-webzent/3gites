@extends('layouts.app')
@section('title', $product->exists ? 'Edit Product' : 'New Product')

@section('content')

<x-page-header :title="$product->exists ? 'Edit Product' : 'New Product'"
    :back="route('admin.products.index')" backLabel="Products" />

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="card overflow-hidden">
        <x-card-header :title="$product->exists ? 'Edit Product' : 'New Product'"
            :subtitle="$product->exists ? $product->name : 'Fill in the details below'" />

        <form method="POST" enctype="multipart/form-data"
              action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
              class="px-7 py-7 space-y-5">
            @csrf
            @if ($product->exists) @method('PATCH') @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}"
                        required maxlength="255"
                        class="w-full border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition @error('name') border-red-400 @else border-slate-300 @enderror">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Price ($) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}"
                        required min="0.01" max="9999.99" step="0.01"
                        class="w-full border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition @error('price') border-red-400 @else border-slate-300 @enderror"
                        placeholder="25.00">
                    @error('price')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Stock Quantity <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}"
                        required min="0" max="9999"
                        class="w-full border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition @error('stock') border-red-400 @else border-slate-300 @enderror">
                    @error('stock')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Description</label>
                <textarea name="description" rows="4" maxlength="2000"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition resize-y"
                    placeholder="Describe this product…">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Product Image</label>
                @if ($product->exists && $product->image)
                <div class="mb-3 w-24 h-24 rounded-xl overflow-hidden border border-slate-200">
                    <img src="{{ Storage::url($product->image) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                </div>
                @endif
                <input type="file" name="image" accept="image/*"
                    class="block w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-900 file:text-white hover:file:bg-purple-800 cursor-pointer transition">
                <p class="text-xs text-slate-400 mt-1">JPEG, PNG, WebP · Max 2MB</p>
                @error('image')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                    {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-slate-300 text-gold-500 focus:ring-gold-500">
                <label for="is_active" class="text-sm font-semibold text-slate-700 cursor-pointer">
                    Product is active (visible in store)
                </label>
            </div>

            <div class="flex items-center gap-4 pt-2 border-t border-slate-100">
                <button type="submit" class="btn-primary">
                    {{ $product->exists ? 'Save Changes' : 'Create Product' }}
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
