<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SalesClient extends Model
{
    use HasFactory;

    public const TYPE_RESTAURANT = 'resto';

    public const TYPE_CAFE = 'cafe';

    public const TYPE_OTHER = 'lainnya';

    public const STATUS_PROSPECT = 'prospek';

    public const STATUS_NEGOTIATION = 'negosiasi';

    public const STATUS_DEAL = 'deal';

    public const STATUS_FOLLOW_UP = 'follow_up';

    protected $fillable = [
        'business_name',
        'business_type',
        'contact_person',
        'phone',
        'email',
        'address',
        'cooperation_offer',
        'status',
        'last_contacted_at',
        'next_follow_up_at',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'last_contacted_at' => 'date',
            'next_follow_up_at' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(SalesActivity::class);
    }

    public function latestActivity(): HasOne
    {
        return $this->hasOne(SalesActivity::class)->latestOfMany('activity_date');
    }

    public static function businessTypes(): array
    {
        return [
            self::TYPE_RESTAURANT,
            self::TYPE_CAFE,
            self::TYPE_OTHER,
        ];
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PROSPECT,
            self::STATUS_NEGOTIATION,
            self::STATUS_DEAL,
            self::STATUS_FOLLOW_UP,
        ];
    }

    public static function businessTypeLabel(string $type): string
    {
        return match ($type) {
            self::TYPE_RESTAURANT => 'Resto',
            self::TYPE_CAFE => 'Cafe',
            self::TYPE_OTHER => 'Lainnya',
            default => strtoupper($type),
        };
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_PROSPECT => 'Prospek',
            self::STATUS_NEGOTIATION => 'Negosiasi',
            self::STATUS_DEAL => 'Deal',
            self::STATUS_FOLLOW_UP => 'Follow Up',
            default => strtoupper($status),
        };
    }

    public static function statusBadgeClass(string $status): string
    {
        return match ($status) {
            self::STATUS_PROSPECT => 'bg-sky-100 text-sky-700',
            self::STATUS_NEGOTIATION => 'bg-amber-100 text-amber-700',
            self::STATUS_DEAL => 'bg-emerald-100 text-emerald-700',
            self::STATUS_FOLLOW_UP => 'bg-indigo-100 text-indigo-700',
            default => 'bg-slate-100 text-slate-600',
        };
    }
}
