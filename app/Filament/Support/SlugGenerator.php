<?php

namespace App\Filament\Support;

use Closure;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class SlugGenerator
{
    public static function updateSlug(string $slugField = 'slug'): Closure
    {
        return static function (Get $get, Set $set, ?string $old, ?string $state) use ($slugField): void {
            $currentSlug = $get($slugField);

            if (filled($currentSlug) && $currentSlug !== Str::slug((string) $old)) {
                return;
            }

            $set($slugField, Str::slug((string) $state));
        };
    }

    public static function normalize(?string $state): ?string
    {
        if (blank($state)) {
            return null;
        }

        return Str::slug($state);
    }
}
