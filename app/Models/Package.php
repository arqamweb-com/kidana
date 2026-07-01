<?php

namespace App\Models;

use App\Enum\PackageOrderAction;
use App\Models\Concerns\HasAutoSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Package extends Model
{
    use HasAutoSlug;
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'service_id',
        'destination_id',
        'description',
        'features',
        'price',
        'order_action',
        'location_label',
        'start_date',
        'end_date',
        'max_guests',
        'days',
        'image_url',
        'tags',
        'highlights',
        'itinerary',
        'included_items',
        'excluded_items',
        'gallery',
        'is_active',
        'sort_order',
    ];

    /**
     * @var array<int, string>
     */
    public array $translatable = [
        'name',
        'description',
        'location_label',
        'highlights',
        'itinerary',
        'included_items',
        'excluded_items',
        'gallery',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'features' => 'array',
            'tags' => 'array',
            'is_active' => 'boolean',
            'service_id' => 'integer',
            'destination_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'price' => 'integer',
            'order_action' => PackageOrderAction::class,
            // NOTE: included_items, excluded_items, highlights, itinerary and gallery are
            // translatable (see $translatable) — Spatie handles their JSON, so they must
            // NOT have an 'array' cast here or reading breaks.
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
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

    public function scopeTagged(Builder $query, string $tag): Builder
    {
        return $query->whereJsonContains('tags', $tag);
    }
}
