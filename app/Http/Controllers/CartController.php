<?php

namespace App\Http\Controllers;

use App\Models\StoreProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    private const SESSION_KEY = 'cart'; // ['product_id' => quantity]

    /**
     * GET /cart
     */
    public function index(): View
    {
        $cart     = $this->getCart();
        $items    = $this->hydrateCart($cart);
        $subtotal = collect($items)->sum(fn ($i) => $i['total']);

        return view('store.cart', compact('items', 'subtotal'));
    }

    /**
     * POST /cart/add
     */
    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:store_products,id'],
            'quantity'   => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $product = StoreProduct::findOrFail($validated['product_id']);

        if (! $product->is_active || $product->stock < 1) {
            return back()->with('error', 'This product is no longer available.');
        }

        $cart = $this->getCart();
        $cart[$product->id] = min(
            ($cart[$product->id] ?? 0) + $validated['quantity'],
            $product->stock
        );
        $this->saveCart($cart);

        return back()->with('success', "\"{$product->name}\" added to your cart.");
    }

    /**
     * PATCH /cart/{productId}
     * Update quantity for a single item.
     */
    public function update(Request $request, int $productId): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            $product = StoreProduct::find($productId);
            $cart[$productId] = min($validated['quantity'], $product?->stock ?? 1);
            $this->saveCart($cart);
        }

        return back()->with('success', 'Cart updated.');
    }

    /**
     * DELETE /cart/{productId}
     * Remove one item.
     */
    public function remove(int $productId): RedirectResponse
    {
        $cart = $this->getCart();
        unset($cart[$productId]);
        $this->saveCart($cart);

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * DELETE /cart
     * Clear entire cart.
     */
    public function clear(): RedirectResponse
    {
        session()->forget(self::SESSION_KEY);
        return redirect()->route('store.index')->with('success', 'Cart cleared.');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public static function getCart(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public static function saveCart(array $cart): void
    {
        session([self::SESSION_KEY => $cart]);
    }

    /**
     * Load Product models for cart items, skip unavailable ones.
     */
    public static function hydrateCart(array $cart): array
    {
        if (empty($cart)) {
            return [];
        }

        $products = StoreProduct::whereIn('id', array_keys($cart))
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $items = [];
        foreach ($cart as $productId => $qty) {
            $product = $products->get($productId);
            if (! $product) {
                continue; // product deleted/deactivated
            }
            $items[] = [
                'product'  => $product,
                'quantity' => $qty,
                'total'    => $product->price * $qty,
            ];
        }
        return $items;
    }
}
