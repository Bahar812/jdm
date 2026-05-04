<?php

namespace Tests\Feature;

use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderStatusFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_order_status_step_by_step_and_history_is_saved(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->createOrder([
            'status' => Order::STATUS_PROCESSING,
            'payment_status' => Order::PAYMENT_PAID,
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($admin)->put(route('admin.orders.update', $order), [
            'status' => Order::STATUS_SHIPPED,
            'payment_status' => Order::PAYMENT_PAID,
            'notes' => 'Paket diserahkan ke kurir.',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_SHIPPED,
            'payment_status' => Order::PAYMENT_PAID,
        ]);

        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'from_status' => Order::STATUS_PROCESSING,
            'to_status' => Order::STATUS_SHIPPED,
            'from_payment_status' => Order::PAYMENT_PAID,
            'to_payment_status' => Order::PAYMENT_PAID,
            'source' => 'admin_dashboard',
            'changed_by' => $admin->id,
        ]);
    }

    public function test_admin_marking_pending_order_as_paid_creates_stock_out_movement(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::query()->create([
            'name' => 'Daging Stok',
            'slug' => 'daging-stok',
            'category' => 'Daging',
            'unit' => 'kg',
            'price' => 100000,
            'stock' => 8,
            'is_active' => true,
        ]);
        $order = $this->createOrder([
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_PENDING,
            'total_amount' => 300000,
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_slug' => $product->slug,
            'category' => $product->category,
            'price' => 100000,
            'quantity' => 3,
            'subtotal' => 300000,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.orders.update', $order), [
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_PAID,
            'notes' => 'Pembayaran diterima.',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));

        $this->assertSame(5, $product->fresh()->stock);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_PROCESSING,
            'payment_status' => Order::PAYMENT_PAID,
        ]);
        $this->assertDatabaseHas('inventory_movements', [
            'product_id' => $product->id,
            'order_id' => $order->id,
            'created_by' => $admin->id,
            'type' => InventoryMovement::TYPE_OUT,
            'quantity' => 3,
            'previous_stock' => 8,
            'new_stock' => 5,
        ]);
    }

    public function test_admin_cannot_skip_from_processing_directly_to_completed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->createOrder([
            'status' => Order::STATUS_PROCESSING,
            'payment_status' => Order::PAYMENT_PAID,
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($admin)->from(route('admin.orders.show', $order))->put(route('admin.orders.update', $order), [
            'status' => Order::STATUS_COMPLETED,
            'payment_status' => Order::PAYMENT_PAID,
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $response->assertSessionHasErrors('status');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_PROCESSING,
            'payment_status' => Order::PAYMENT_PAID,
        ]);
    }

    public function test_midtrans_webhook_paid_sets_order_to_processing_and_creates_history(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $serverKey = 'test-server-key';
        config()->set('services.midtrans.server_key', $serverKey);

        $order = $this->createOrder([
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_PENDING,
            'total_amount' => 150000,
        ]);

        $grossAmount = number_format($order->total_amount, 2, '.', '');
        $statusCode = '200';
        $signature = hash('sha512', $order->order_number.$statusCode.$grossAmount.$serverKey);

        $response = $this->post(route('payments.midtrans.notification'), [
            'order_id' => $order->order_number,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'transaction_id' => 'trx-123',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_PROCESSING,
            'payment_status' => Order::PAYMENT_PAID,
            'midtrans_transaction_id' => 'trx-123',
        ]);

        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'from_status' => Order::STATUS_PENDING,
            'to_status' => Order::STATUS_PROCESSING,
            'from_payment_status' => Order::PAYMENT_PENDING,
            'to_payment_status' => Order::PAYMENT_PAID,
            'source' => 'midtrans_webhook',
        ]);
    }

    private function createOrder(array $attributes = []): Order
    {
        return Order::query()->create(array_merge([
            'order_number' => 'ORD-TEST-'.Str::upper(Str::random(6)),
            'customer_name' => 'Customer Test',
            'customer_email' => 'customer@example.test',
            'customer_phone' => '08123456789',
            'shipping_address' => 'Alamat testing',
            'total_amount' => 100000,
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_PENDING,
            'payment_method' => 'midtrans',
        ], $attributes));
    }
}
