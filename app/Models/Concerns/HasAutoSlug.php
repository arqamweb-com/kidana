<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait HasAutoSlug
{
    protected static function bootHasAutoSlug(): void
    {
        static::creating(function (Model $model): void {
            if (blank($model->getAttribute('slug'))) {
                $model->setAttribute('slug', static::makeUniqueSlug($model));
            }
        });

        static::saving(function (Model $model): void {
            if (filled($model->getAttribute('slug'))) {
                $model->setAttribute('slug', Str::slug((string) $model->getAttribute('slug')));
            }
        });
    }

    protected static function makeUniqueSlug(Model $model): string
    {
        $slug = Str::slug(static::slugSource($model)) ?: Str::lower(class_basename($model));
        $uniqueSlug = $slug;
        $counter = 2;

        while ($model->newQuery()->where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = "{$slug}-{$counter}";
            $counter++;
        }

        return $uniqueSlug;
    }

    protected static function slugSource(Model $model): string
    {
        $source = $model->getAttribute('name');

        if (is_array($source)) {
            return (string) (Arr::get($source, app()->getLocale())
                ?? Arr::get($source, config('app.fallback_locale'))
                ?? Arr::first($source)
                ?? '');
        }

        return (string) $source;
    }
}
