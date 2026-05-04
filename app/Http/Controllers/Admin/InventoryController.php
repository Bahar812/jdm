<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InventoryMovementRequest;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function __construct(
        private readonly InventoryService $inventoryService,
    ) {}

    public function index(Request $request): View
    {
        $productId = $request->integer('product_id') ?: null;

        $movements = InventoryMovement::query()
            ->select(['id', 'product_id', 'created_by', 'type', 'quantity', 'previous_stock', 'new_stock', 'created_at'])
            ->with(['product:id,name,stock', 'creator:id,name'])
            ->when($productId, fn ($query) => $query->where('product_id', $productId))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.inventory.index', [
            'movements' => $movements,
            'products' => Product::query()->orderBy('name')->get(['id', 'name', 'stock']),
            'selectedProductId' => $productId,
        ]);
    }

    public function store(InventoryMovementRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->inventoryService->applyMovement(
            product: (int) $validated['product_id'],
            type: (string) $validated['type'],
            quantity: (int) $validated['quantity'],
            createdBy: $request->user()?->id,
            note: $validated['note'] ?? null,
        );

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Perubahan stok berhasil disimpan.');
    }
}
