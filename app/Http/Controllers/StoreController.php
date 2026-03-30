<?php

namespace App\Http\Controllers;

use App\Models\StoreProduct;
use Illuminate\View\View;

class StoreController extends Controller
{
    /**
     * GET /store
     * Public product listing.
     */
    public function index(): View
    {
        $products = StoreProduct::where('is_active', true)
            ->orderBy('name')
            ->paginate(12);

        $cartCount = array_sum(CartController::getCart());

        return view('store.index', compact('products', 'cartCount'));
    }
}
