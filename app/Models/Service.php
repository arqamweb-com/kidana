<?php

namespace App\Models;

use App\Models\Concerns\HasAutoSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasFactory;
    use HasAutoSlug;
    use HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'hero_title',
        'hero_subtitle',
        'hero_description',
        'hero_image',
        'intro_subtitle',
        'intro_title',
        'intro_text',
        'stats',
        'benefits',
        'steps',
        'gallery',
        'cta_title',
        'cta_subtitle',
        'icon',
        'image_url',
        'is_active',
        'sort_order',
    ];

    /**
     * @var array<int, string>
     */
    public array $translatable = [
        'name',
        'description',
        'hero_title',
        'hero_subtitle',
        'hero_description',
        'intro_subtitle',
        'intro_title',
        'intro_text',
        'cta_title',
        'cta_subtitle',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'stats' => 'array',
            'benefits' => 'array',
            'steps' => 'array',
            'gallery' => 'array',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class, 'service_id');
    }

    public function faqs(): BelongsToMany
    {
        return $this->belongsToMany(Faq::class);
    }

    public function testimonials(): BelongsToMany
    {
        return $this->belongsToMany(Testimonial::class);
    }
}
