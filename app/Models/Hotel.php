<?php

namespace App\Models;

use App\Enum\HotelBoardBasis;
use App\Enum\PackageOrderAction;
use App\Models\Concerns\HasAutoSlug;
use Database\Factories\HotelFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Hotel extends Model
{
    use HasAutoSlug;

    /** @use HasFactory<HotelFactory> */
    use HasFactory;

    use HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'destination_id',
        'description',
        'address',
        'stars',
        'map_url',
        'board_basis',
        'check_in_time',
        'check_out_time',
        'cancellation_policy',
        'order_action',
        'image_url',
        'gallery',
        'amenities',
        'policies',
        'tags',
        'is_active',
        'sort_order',
    ];

    /**
     * @var array<int, string>
     */
    public array $translatable = [
        'name',
        'description',
        'address',
        'cancellation_policy',
        'amenities',
        'policies',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'destination_id' => 'integer',
            'stars' => 'integer',
            'board_basis' => HotelBoardBasis::class,
            'order_action' => PackageOrderAction::class,
            'gallery' => 'array',
            'tags' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            // NOTE: amenities and policies are translatable (see $translatable) — Spatie
            // handles their JSON, so they must NOT have an 'array' cast here.
        ];
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(HotelRoom::class);
    }

    public function faqs(): BelongsToMany
    {
        return $this->belongsToMany(Faq::class);
    }

    public function testimonials(): BelongsToMany
    {
        return $this->belongsToMany(Testimonial::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
