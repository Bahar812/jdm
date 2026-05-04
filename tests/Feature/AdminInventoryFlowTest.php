<?php

namespace Tests\Feature;

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminInventoryFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_record_stock_in_and_stock_adjustment(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = $this->createProduct(['stock' => 10]);

        $stockInResponse = $this->actingAs($admin)->post(route('admin.inventory.store'), [
            'product_id' => $product->id,
            'type' => InventoryMovement::TYPE_IN,
            'quantity' => 5,
            'note' => 'Restock gudang',
        ]);

        $stockInResponse->assertRedirect(route('admin.inventory.index'));
        $this->assertSame(15, $product->fresh()->stock);

        $adjustmentResponse = $this->actingAs($admin)->post(route('admin.inventory.store'), [
            'product_id' => $product->id,
            'type' => InventoryMovement::TYPE_ADJUSTMENT,
            'quantity' => 7,
            'note' => 'Stock opname',
        ]);

        $adjustmentResponse->assertRedirect(route('admin.inventory.index'));
        $this->assertSame(7, $product->fresh()->stock);

        $this->assertDatabaseHas('inventory_movements', [
            'product_id' => $product->id,
            'created_by' => $admin->id,
            'type' => InventoryMovement::TYPE_IN,
            'quantity' => 5,
            'previous_stock' => 10,
            'new_stock' => 15,
        ]);

        $this->assertDatabaseHas('inventory_movements', [
            'product_id' => $product->id,
            'created_by' => $admin->id,
            'type' => InventoryMovement::TYPE_ADJUSTMENT,
            'quantity' => 7,
            'previous_stock' => 15,
            'new_stock' => 7,
        ]);
    }

    public function test_admin_cannot_stock_out_more_than_available_stock(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = $this->createProduct(['stock' => 3]);

        $response = $this->actingAs($admin)
            ->from(route('admin.inventory.index'))
            ->post(route('admin.inventory.store'), [
                'product_id' => $product->id,
                'type' => InventoryMovement::TYPE_OUT,
                'quantity' => 4,
                'note' => 'Pengeluaran berlebih',
            ]);

        $response->assertRedirect(route('admin.inventory.index'));
        $response->assertSessionHasErrors('quantity');
        $this->assertSame(3, $product->fresh()->stock);
        $this->assertDatabaseCount('inventory_movements', 0);
    }

    private function createProduct(array $overrides = []): Product
    {
        return Product::query()->create(array_merge([
            'name' => 'Daging Test',
            'slug' => 'daging-test',
            'category' => 'Daging',
            'unit' => 'kg',
            'price' => 100000,
            'stock' => 10,
            'is_active' => true,
        ], $overrides));
    }
}
