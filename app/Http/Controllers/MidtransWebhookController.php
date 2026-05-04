<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\InventoryService;
use App\Services\OrderStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MidtransWebhookController extends Controller
{
    public function __construct(
        private readonly OrderStatusService $orderStatusService,
        private readonly InventoryService $inventoryService,
    ) {}

    public function __invoke(Request $request): Response
    {
        $payload = $request->all();
        if (! $this->isValidSignature($payload)) {
            Log::warning('Midtrans webhook signature invalid.', ['payload' => $payload]);

            return response('Invalid signature', 403);
        }

        $orderNumber = $payload['order_id'] ?? null;
        if (! $orderNumber) {
            return response('Order ID missing', 422);
        }

        $order = Order::query()->where('order_number', $orderNumber)->with('items')->first();
        if (! $order) {
            return response('Order not found', 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? '';
        $statusCode = $this->mapPaymentStatus($transactionStatus, $fraudStatus);
        $isPaySuccess = $statusCode === Order::PAYMENT_PAID;
        $syncedOrderStatus = Order::statusFromTransactionActivity($statusCode, (string) $order->status);

        DB::transaction(function () use ($order, $payload, $statusCode, $isPaySuccess, $syncedOrderStatus, $transactionStatus): void {
            $wasPaid = $order->isPaid();

            $this->orderStatusService->applyStateChange($order, [
                'payment_status' => $statusCode,
                'status' => $syncedOrderStatus,
                'midtrans_transaction_id' => $payload['transaction_id'] ?? $order->midtrans_transaction_id,
                'paid_at' => $isPaySuccess ? ($order->paid_at ?: now()) : $order->paid_at,
            ], source: 'midtrans_webhook', note: 'Sinkronisasi webhook Midtrans: '.$transactionStatus);

            if ($isPaySuccess && ! $wasPaid && ! $order->inventoryMovements()->where('type', 'out')->exists()) {
                $this->inventoryService->applyStockOutForPaidOrder($order);
            }
        });

        return response('OK', 200);
    }

    private function mapPaymentStatus(string $transactionStatus, string $fraudStatus): string
    {
        if ($transactionStatus === 'capture') {
            return $fraudStatus === 'challenge' ? Order::PAYMENT_PENDING : Order::PAYMENT_PAID;
        }

        return match ($transactionStatus) {
            'settlement' => Order::PAYMENT_PAID,
            'pending' => Order::PAYMENT_PENDING,
            'deny', 'cancel', 'failure' => Order::PAYMENT_FAILED,
            'expire' => Order::PAYMENT_EXPIRED,
            default => Order::PAYMENT_PENDING,
        };
    }

    private function isValidSignature(array $payload): bool
    {
        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signature = (string) ($payload['signature_key'] ?? '');
        $serverKey = (string) config('services.midtrans.server_key');

        if ($orderId === '' || $statusCode === '' || $grossAmount === '' || $signature === '' || $serverKey === '') {
            return false;
        }

        $generated = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        return hash_equals($generated, $signature);
    }
}
