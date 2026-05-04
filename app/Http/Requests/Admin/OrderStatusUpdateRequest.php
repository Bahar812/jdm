<?php

namespace App\Http\Requests\Admin;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class OrderStatusUpdateRequest extends FormRequest
{
    private ?string $resolvedTargetStatus = null;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => trim((string) $this->input('status')),
            'payment_status' => trim((string) $this->input('payment_status')),
            'notes' => $this->filled('notes') ? trim((string) $this->input('notes')) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(Order::orderStatuses())],
            'payment_status' => ['required', Rule::in(Order::paymentStatuses())],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $order = $this->route('order');
            if (! $order instanceof Order) {
                return;
            }

            $targetPaymentStatus = $this->paymentStatus();
            $targetStatus = $this->targetStatus();

            if (
                $targetPaymentStatus !== Order::PAYMENT_PAID &&
                in_array($targetStatus, [Order::STATUS_PROCESSING, Order::STATUS_SHIPPED, Order::STATUS_COMPLETED], true)
            ) {
                $validator->errors()->add('status', 'Status diproses, dikirim, dan selesai hanya untuk order yang sudah lunas.');

                return;
            }

            if (! $order->canTransitionTo($targetStatus, $targetPaymentStatus)) {
                $validator->errors()->add('status', 'Transisi status tidak valid. Gunakan alur bertahap: diproses -> dikirim -> selesai.');
            }
        });
    }

    public function targetStatus(): string
    {
        return $this->resolvedTargetStatus ??= $this->resolveTargetStatus(
            (string) $this->input('status'),
            $this->paymentStatus(),
        );
    }

    public function paymentStatus(): string
    {
        return (string) $this->input('payment_status');
    }

    private function resolveTargetStatus(string $selectedStatus, string $targetPaymentStatus): string
    {
        if ($targetPaymentStatus === Order::PAYMENT_PAID && $selectedStatus === Order::STATUS_PENDING) {
            return Order::STATUS_PROCESSING;
        }

        if (
            in_array($targetPaymentStatus, [Order::PAYMENT_FAILED, Order::PAYMENT_EXPIRED], true) &&
            ! in_array($selectedStatus, [Order::STATUS_SHIPPED, Order::STATUS_COMPLETED], true)
        ) {
            return Order::STATUS_CANCELLED;
        }

        return $selectedStatus;
    }
}
