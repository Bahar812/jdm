<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.midtrans.server_key', null);
        config()->set('services.midtrans.client_key', null);
    }

    public function test_customer_can_add_product_to_cart_and_checkout(): void
    {
        $product = $this->createProduct(['stock' => 10, 'price' => 75000]);

        $this->post(route('cart.add', $product), ['quantity' => 2])
            ->assertRedirect();

        $checkoutResponse = $this->from(route('cart'))->post(route('checkout.store'), [
            'customer_name' => 'Customer Test',
            'customer_email' => 'customer@example.test',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jalan Testing Morowali nomor 1',
            'notes' => 'Kirim pagi.',
        ]);

        $order = Order::query()->firstOrFail();

        $checkoutResponse->assertRedirect(route('checkout.show', $order));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_name' => 'Customer Test',
            'total_amount' => 150000,
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_PENDING,
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'subtotal' => 150000,
        ]);
        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'source' => 'checkout',
            'to_status' => Order::STATUS_PENDING,
            'to_payment_status' => Order::PAYMENT_PENDING,
        ]);
    }

    public function test_checkout_rejects_cart_when_stock_is_no_longer_sufficient(): void
    {
        $product = $this->createProduct(['stock' => 2]);

        $this->post(route('cart.add', $product), ['quantity' => 2])
            ->assertRedirect();

        $product->update(['stock' => 1]);

        $response = $this->from(route('cart'))->post(route('checkout.store'), [
            'customer_name' => 'Customer Test',
            'customer_email' => 'customer@example.test',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jalan Testing Morowali nomor 1',
            'notes' => null,
        ]);

        $response->assertRedirect(route('cart'));
        $response->assertSessionHasErrors('cart');
        $this->assertDatabaseCount('orders', 0);
    }

    private function createProduct(array $overrides = []): Product
    {
        return Product::query()->create(array_merge([
            'name' => 'Daging Checkout',
            'slug' => 'daging-checkout',
            'category' => 'Daging',
            'unit' => 'kg',
            'price' => 100000,
            'stock' => 10,
            'is_active' => true,
        ], $overrides));
    }
}
