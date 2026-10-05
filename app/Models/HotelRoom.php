<?php

namespace App\Models;

use App\Enum\BookingStatus;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Database\Factories\HotelRoomFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class HotelRoom extends Model
{
    /** @use HasFactory<HotelRoomFactory> */
    use HasFactory;

    use HasTranslations;

    /**
     * How long an unpaid Fawry checkout keeps its rooms reserved.
     */
    public const PENDING_PAYMENT_HOLD_MINUTES = 60;

    protected $fillable = [
        'hotel_id',
        'name',
        'description',
        'bed_type',
        'image_url',
        'size_sqm',
        'max_adults',
        'max_children',
        'price_per_night',
        'quantity',
        'amenities',
        'is_active',
        'sort_order',
    ];

    /**
     * @var array<int, string>
     */
    public array $translatable = [
        'name',
        'description',
        'bed_type',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hotel_id' => 'integer',
            'size_sqm' => 'integer',
            'max_adults' => 'integer',
            'max_children' => 'integer',
            'price_per_night' => 'integer',
            'quantity' => 'integer',
            'amenities' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Number of rooms of this type still free on every night of the stay.
     */
    public function availableRoomsBetween(CarbonInterface $checkIn, CarbonInterface $checkOut): int
    {
        $overlappingBookings = $this->bookings()
            ->whereDate('check_in', '<', $checkOut->toDateString())
            ->whereDate('check_out', '>', $checkIn->toDateString())
            ->where(function (Builder $query): void {
                $query->where('status', BookingStatus::Paid)
                    ->orWhere(function (Builder $query): void {
                        $query->where('status', BookingStatus::AwaitingPayment)
                            ->where('created_at', '>=', now()->subMinutes(self::PENDING_PAYMENT_HOLD_MINUTES));
                    });
            })
            ->get(['check_in', 'check_out', 'rooms']);

        $busiestNight = collect(CarbonPeriod::create($checkIn->toDateString(), $checkOut->copy()->subDay()->toDateString()))
            ->map(fn (CarbonInterface $night): int => (int) $overlappingBookings
                ->filter(fn (Booking $booking): bool => $booking->check_in->lte($night) && $booking->check_out->gt($night))
                ->sum('rooms'))
            ->max() ?? 0;

        return max(0, $this->quantity - $busiestNight);
    }
}
