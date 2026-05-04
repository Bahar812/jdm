<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryService
{
    public function applyMovement(
        int|Product $product,
        string $type,
        int $quantity,
        ?Order $order = null,
        ?int $createdBy = null,
        ?string $note = null,
        bool $allowInsufficientStock = false,
    ): InventoryMovement {
        return DB::transaction(function () use ($product, $type, $quantity, $order, $createdBy, $note, $allowInsufficientStock): InventoryMovement {
            $lockedProduct = Product::query()
                ->lockForUpdate()
                ->findOrFail($product instanceof Product ? $product->getKey() : $product);

            $previousStock = (int) $lockedProduct->stock;
            $newStock = $this->calculateNewStock($previousStock, $type, $quantity, $allowInsufficientStock);

            $lockedProduct->update(['stock' => $newStock]);

            return $lockedProduct->inventoryMovements()->create([
                'order_id' => $order?->id,
                'created_by' => $createdBy,
                'type' => $type,
                'quantity' => $quantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'note' => $note,
            ]);
        });
    }

    public function applyStockOutForPaidOrder(Order $order, ?int $createdBy = null, ?string $note = null): void
    {
        $order->loadMissing('items');

        foreach ($order->items as $item) {
            if (! $item->product_id) {
                continue;
            }

            $this->applyMovement(
                product: (int) $item->product_id,
                type: InventoryMovement::TYPE_OUT,
                quantity: (int) $item->quantity,
                order: $order,
                createdBy: $createdBy,
                note: $note ?: 'Stok keluar untuk order '.$order->order_number,
                allowInsufficientStock: true,
            );
        }
    }

    private function calculateNewStock(int $previousStock, string $type, int $quantity, bool $allowInsufficientStock): int
    {
        if ($type === InventoryMovement::TYPE_IN) {
            return $previousStock + $quantity;
        }

        if ($type === InventoryMovement::TYPE_ADJUSTMENT) {
            return $quantity;
        }

        if ($type === InventoryMovement::TYPE_OUT) {
            if (! $allowInsufficientStock && $quantity > $previousStock) {
                throw ValidationException::withMessages([
                    'quantity' => 'Quantity stock out tidak boleh melebihi stok tersedia.',
                ]);
            }

            return max(0, $previousStock - $quantity);
        }

        throw ValidationException::withMessages([
            'type' => 'Tipe pergerakan stok tidak valid.',
        ]);
    }
}
