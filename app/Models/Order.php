<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_SHIPPED = 'shipped';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_PENDING = 'pending';

    public const PAYMENT_PAID = 'paid';

    public const PAYMENT_FAILED = 'failed';

    public const PAYMENT_EXPIRED = 'expired';

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'midtrans_snap_token',
        'midtrans_redirect_url',
        'midtrans_transaction_id',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public static function orderStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_SHIPPED,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
        ];
    }

    public static function paymentStatuses(): array
    {
        return [
            self::PAYMENT_PENDING,
            self::PAYMENT_PAID,
            self::PAYMENT_FAILED,
            self::PAYMENT_EXPIRED,
        ];
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_SHIPPED => 'Dikirim',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => strtoupper($status),
        };
    }

    public static function paymentStatusLabel(string $status): string
    {
        return match ($status) {
            self::PAYMENT_PENDING => 'Pending',
            self::PAYMENT_PAID => 'Lunas',
            self::PAYMENT_FAILED => 'Gagal',
            self::PAYMENT_EXPIRED => 'Kedaluwarsa',
            default => strtoupper($status),
        };
    }

    public function allowedStatusUpdates(?string $nextPaymentStatus = null): array
    {
        return array_values(array_filter(
            self::orderStatuses(),
            fn (string $candidate): bool => $this->canTransitionTo($candidate, $nextPaymentStatus)
        ));
    }

    public function canTransitionTo(string $targetStatus, ?string $nextPaymentStatus = null): bool
    {
        $current = (string) $this->status;
        $target = (string) $targetStatus;
        $paymentStatus = (string) ($nextPaymentStatus ?? $this->payment_status);

        if (! in_array($target, self::orderStatuses(), true)) {
            return false;
        }

        if ($target === $current) {
            return true;
        }

        if (
            $paymentStatus !== self::PAYMENT_PAID &&
            in_array($target, [self::STATUS_PROCESSING, self::STATUS_SHIPPED, self::STATUS_COMPLETED], true)
        ) {
            return false;
        }

        if (in_array($current, [self::STATUS_COMPLETED, self::STATUS_CANCELLED], true)) {
            return false;
        }

        return match ($target) {
            self::STATUS_PROCESSING => $current === self::STATUS_PENDING,
            self::STATUS_SHIPPED => $current === self::STATUS_PROCESSING,
            self::STATUS_COMPLETED => $current === self::STATUS_SHIPPED,
            self::STATUS_CANCELLED => in_array($current, [self::STATUS_PENDING, self::STATUS_PROCESSING], true),
            default => false,
        };
    }

    public function nextFulfillmentStatus(): ?string
    {
        return match ($this->status) {
            self::STATUS_PENDING => $this->isPaid() ? self::STATUS_PROCESSING : null,
            self::STATUS_PROCESSING => self::STATUS_SHIPPED,
            self::STATUS_SHIPPED => self::STATUS_COMPLETED,
            default => null,
        };
    }

    public static function statusFromTransactionActivity(string $paymentStatus, string $currentStatus): string
    {
        if ($paymentStatus === self::PAYMENT_PAID) {
            if (in_array($currentStatus, [self::STATUS_PROCESSING, self::STATUS_SHIPPED, self::STATUS_COMPLETED, self::STATUS_CANCELLED], true)) {
                return $currentStatus;
            }

            return self::STATUS_PROCESSING;
        }

        if (in_array($paymentStatus, [self::PAYMENT_FAILED, self::PAYMENT_EXPIRED], true)) {
            if (in_array($currentStatus, [self::STATUS_SHIPPED, self::STATUS_COMPLETED], true)) {
                return $currentStatus;
            }

            return self::STATUS_CANCELLED;
        }

        return $currentStatus === self::STATUS_PENDING ? self::STATUS_PENDING : $currentStatus;
    }
}
