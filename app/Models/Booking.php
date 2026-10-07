<?php

namespace App\Models;

use App\Enum\BookingSource;
use App\Enum\BookingStatus;
use App\Enum\PackageOrderAction;
use App\Jobs\SendApiClientWebhook;
use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'source' => 'web',
    ];

    protected $fillable = [
        'reference',
        'package_id',
        'hotel_id',
        'hotel_room_id',
        'customer_name',
        'customer_email',
        'customer_mobile',
        'guests',
        'travel_date',
        'check_in',
        'check_out',
        'nights',
        'rooms',
        'adults',
        'children',
        'message',
        'locale',
        'type',
        'source',
        'api_client_id',
        'external_reference',
        'idempotency_key',
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
            'check_in' => 'date',
            'check_out' => 'date',
            'nights' => 'integer',
            'rooms' => 'integer',
            'adults' => 'integer',
            'children' => 'integer',
            'type' => PackageOrderAction::class,
            'source' => BookingSource::class,
            'status' => BookingStatus::class,
            'total_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'payment_success_email_sent_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking): void {
            $booking->reference ??= static::newReference();
        });

        static::updated(function (Booking $booking): void {
            if ($booking->api_client_id !== null && $booking->wasChanged('status')) {
                SendApiClientWebhook::dispatch($booking, $booking->status)->afterCommit();
            }
        });
    }

    /**
     * A short public booking reference that partners and customers can quote.
     */
    public static function newReference(): string
    {
        return 'KD-'.Str::upper(Str::random(10));
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function hotelRoom(): BelongsTo
    {
        return $this->belongsTo(HotelRoom::class);
    }

    public function apiClient(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class);
    }

    public function isFromApi(): bool
    {
        return $this->source === BookingSource::Api;
    }

    public function isHotelBooking(): bool
    {
        return $this->hotel_id !== null;
    }

    /**
     * The name of what was booked, shown to customers and on payment providers.
     */
    public function bookedItemName(): string
    {
        if ($this->isHotelBooking()) {
            return collect([$this->hotel?->name, $this->hotelRoom?->name])->filter()->implode(' - ');
        }

        return (string) $this->package?->name;
    }

    public function bookedItemUrl(): ?string
    {
        if ($this->isHotelBooking()) {
            return $this->hotel ? route('hotels.show', ['locale' => $this->locale, 'hotel' => $this->hotel->slug]) : null;
        }

        return $this->package ? route('packages.show', ['locale' => $this->locale, 'package' => $this->package->slug]) : null;
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
