<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'shopping_cart';

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->getRawCart();
        $currentQty = (int) ($cart[$product->id] ?? 0);
        $cart[$product->id] = max(1, min($product->stock, $currentQty + $quantity));
        Session::put(self::SESSION_KEY, $cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->getRawCart();
        if (! array_key_exists($productId, $cart)) {
            return;
        }

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $product = Product::query()->find($productId);
            if (! $product) {
                unset($cart[$productId]);
            } else {
                $cart[$productId] = min($product->stock, $quantity);
            }
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->getRawCart();
        unset($cart[$productId]);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function itemCount(): int
    {
        return $this->items()->sum('quantity');
    }

    public function subtotal(): int
    {
        return $this->items()->sum('subtotal');
    }

    public function items(): Collection
    {
        $cart = $this->getRawCart();
        if ($cart === []) {
            return collect();
        }

        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $items = collect();
        foreach ($cart as $productId => $quantity) {
            $product = $products->get((int) $productId);
            if (! $product) {
                continue;
            }

            $safeQty = max(1, min($product->stock, (int) $quantity));
            $items->push([
                'product' => $product,
                'quantity' => $safeQty,
                'subtotal' => $safeQty * $product->price,
            ]);
        }

        return $items;
    }

    public function getRawCart(): array
    {
        $raw = Session::get(self::SESSION_KEY, []);

        return is_array($raw) ? $raw : [];
    }
}
