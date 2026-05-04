<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function createSnapToken(Order $order): string
    {
        $this->configure();

        $items = $order->items->map(function ($item): array {
            return [
                'id' => $item->product_id ?: $item->product_slug ?: $item->id,
                'price' => (int) $item->price,
                'quantity' => (int) $item->quantity,
                'name' => mb_substr($item->product_name, 0, 50),
            ];
        })->values()->all();

        $payload = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->total_amount,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
                'billing_address' => [
                    'address' => $order->shipping_address,
                ],
                'shipping_address' => [
                    'address' => $order->shipping_address,
                ],
            ],
        ];

        return Snap::getSnapToken($payload);
    }

    public function isConfigured(): bool
    {
        return (bool) config('services.midtrans.server_key')
            && (bool) config('services.midtrans.client_key');
    }

    private function configure(): void
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
}
