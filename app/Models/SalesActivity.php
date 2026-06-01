<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_client_id',
        'user_id',
        'activity_date',
        'status',
        'description',
        'next_follow_up_at',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'next_follow_up_at' => 'date',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(SalesClient::class, 'sales_client_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
