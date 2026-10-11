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
     * Resolve a list of image paths, or of repeater rows shaped {image, caption}, to URLs.
     *
     * @param  array<int, mixed>|null  $paths
     * @return array<int, string>
     */
    protected function imageUrls(?array $paths): array
    {
        return collect($paths ?? [])
            ->map(fn (mixed $item): mixed => is_array($item) ? data_get($item, 'image') : $item)
            ->map(fn (mixed $path): ?string => is_string($path) ? $this->imageUrl($path) : null)
            ->filter()
            ->values()
            ->all();
    }
}
