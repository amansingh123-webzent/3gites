<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreProduct;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    public function __construct(private ImageUploadService $imageService) {}

    public function index(): View
    {
        $products = StoreProduct::orderBy('name')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.form', ['product' => new StoreProduct]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);
        $imagePath = $this->handleImage($request, null);

        StoreProduct::create(array_merge(
            $validated,
            ['image' => $imagePath, 'is_active' => $request->boolean('is_active', true)]
        ));

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created.');
    }

    public function edit(StoreProduct $product): View
    {
        return view('admin.products.form', compact('product'));
    }

    public function update(Request $request, StoreProduct $product): RedirectResponse
    {
        $validated = $this->validateProduct($request, $product->id);
        $imagePath = $this->handleImage($request, $product);

        $product->update(array_merge(
            $validated,
            ['is_active' => $request->boolean('is_active', true)],
            $imagePath !== null ? ['image' => $imagePath] : []
        ));

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated.');
    }

    public function destroy(StoreProduct $product): RedirectResponse
    {
        if ($product->image) {
            $this->imageService->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted.');
    }

    public function toggle(StoreProduct $product): RedirectResponse
    {
        $product->update(['is_active' => ! $product->is_active]);
        $state = $product->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Product {$state}.");
    }

    private function validateProduct(Request $request, ?int $exceptId = null): array
    {
        return $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price'       => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
            'stock'       => ['required', 'integer', 'min:0', 'max:9999'],
        ]);
    }

    private function handleImage(Request $request, ?StoreProduct $product): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $request->validate([
            'image' => ['image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
        ]);

        if ($product?->image) {
            $this->imageService->delete($product->image);
        }

        return $this->imageService->store($request->file('image'), 'products');
    }
}
