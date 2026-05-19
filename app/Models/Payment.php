<?php

namespace App\Models;

use App\Enum\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'provider',
        'merchant_ref_number',
        'fawry_reference_number',
        'payment_method',
        'status',
        'amount',
        'fawry_fees',
        'currency',
        'paid_at',
        'expires_at',
        'request_payload',
        'response_payload',
        'webhook_payload',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PaymentStatus::class,
            'amount' => 'decimal:2',
            'fawry_fees' => 'decimal:2',
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
            'request_payload' => 'array',
            'response_payload' => 'array',
            'webhook_payload' => 'array',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
