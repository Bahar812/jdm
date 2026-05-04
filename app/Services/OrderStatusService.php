<?php

namespace App\Services;

use App\Models\Order;

class OrderStatusService
{
    public function recordInitialState(
        Order $order,
        string $source = 'system',
        ?string $note = null,
        ?int $changedBy = null,
    ): void {
        if ($order->statusHistories()->exists()) {
            return;
        }

        $order->statusHistories()->create([
            'from_status' => null,
            'to_status' => $order->status,
            'from_payment_status' => null,
            'to_payment_status' => $order->payment_status,
            'source' => $source,
            'note' => $note,
            'changed_by' => $changedBy,
        ]);
    }

    public function applyStateChange(
        Order $order,
        array $attributes,
        string $source = 'system',
        ?string $note = null,
        ?int $changedBy = null,
    ): void {
        $fromStatus = (string) $order->status;
        $fromPaymentStatus = (string) $order->payment_status;

        $order->fill($attributes);

        $statusChanged = $order->isDirty('status');
        $paymentChanged = $order->isDirty('payment_status');

        if ($order->isDirty()) {
            $order->save();
        }

        if (! $statusChanged && ! $paymentChanged) {
            return;
        }

        $order->statusHistories()->create([
            'from_status' => $fromStatus,
            'to_status' => $order->status,
            'from_payment_status' => $fromPaymentStatus,
            'to_payment_status' => $order->payment_status,
            'source' => $source,
            'note' => $note,
            'changed_by' => $changedBy,
        ]);
    }
}
