<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService) {}

    public function index(): View
    {
        $items = $this->cartService->items();

        return view('cart', [
            'items' => $items,
            'subtotal' => $items->sum('subtotal'),
            'itemCount' => $items->sum('quantity'),
        ]);
    }

    public function add(AddToCartRequest $request, Product $product): RedirectResponse
    {
        if (! $product->is_active) {
            return back()->with('error', 'Produk sedang tidak aktif.');
        }

        if ($product->stock <= 0) {
            return back()->with('error', 'Stok produk habis.');
        }

        $this->cartService->add($product, $request->quantity());

        return back()->with('success', 'Produk masuk ke keranjang.');
    }

    public function update(UpdateCartRequest $request, Product $product): RedirectResponse
    {
        $this->cartService->update($product->id, $request->quantity());

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove(Product $product): RedirectResponse
    {
        $this->cartService->remove($product->id);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear(): RedirectResponse
    {
        $this->cartService->clear();

        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
