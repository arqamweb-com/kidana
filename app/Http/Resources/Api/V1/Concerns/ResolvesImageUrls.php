<?php

namespace App\Http\Resources\Api\V1\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ResolvesImageUrls
{
    protected function imageUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * @param  array<int, mixed>|null  $paths
     * @return array<int, string>
     */
    protected function imageUrls(?array $paths): array
    {
        return collect($paths ?? [])
            ->map(fn (mixed $path): ?string => is_string($path) ? $this->imageUrl($path) : null)
            ->filter()
            ->values()
            ->all();
    }
}
