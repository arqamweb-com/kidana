<?php

namespace App\Models;

use App\Enum\BookingStatus;
use App\Enum\PackageOrderAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    protected $fillable = [
        'package_id',
        'customer_name',
        'customer_email',
        'customer_mobile',
        'guests',
        'travel_date',
        'message',
        'locale',
        'type',
        'status',
        'total_amount',
        'currency',
        'paid_at',
        'payment_success_email_sent_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'guests' => 'integer',
            'travel_date' => 'date',
            'type' => PackageOrderAction::class,
            'status' => BookingStatus::class,
            'total_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'payment_success_email_sent_at' => 'datetime',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
