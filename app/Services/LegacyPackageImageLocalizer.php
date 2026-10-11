<?php

namespace App\Services;

use finfo;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Copies package images hotlinked from an old site onto the public disk and points
 * packages.image_url and every packages.gallery entry at the local copy.
 */
class LegacyPackageImageLocalizer
{
    private const TARGET_DIRECTORY = 'packages/legacy';

    private const SOURCE_DIRECTORY = '/assets/images/';

    private const USER_AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36';

    /**
     * Every unique image URL on the given host, in first-seen order, with its target path and the packages using it.
     *
     * @return Collection<string, array{url: string, path: string, package_ids: list<int>}>
     */
    public function collect(string $host): Collection
    {
        $images = collect();

        DB::table('packages')
            ->select(['id', 'image_url', 'gallery'])
            ->lazyById()
            ->each(function (object $package) use ($host, $images): void {
                $urls = array_merge(
                    [$package->image_url],
                    $this->galleryImages($this->decodeGallery($package->gallery)),
                );

                foreach ($urls as $url) {
                    if (! is_string($url) || ! $this->matchesHost($url, $host)) {
                        continue;
                    }

                    $path = $this->targetPath($url);

                    if ($path === null) {
                        continue;
                    }

                    $image = $images->get($url, ['url' => $url, 'path' => $path, 'package_ids' => []]);

                    if (! in_array((int) $package->id, $image['package_ids'], true)) {
                        $image['package_ids'][] = (int) $package->id;
                    }

                    $images->put($url, $image);
                }
            });

        return $images;
    }

    /**
     * Download the given images (skipping files already on disk) and rewrite the packages that use them.
     *
     * @param  Collection<string, array{url: string, path: string, package_ids: list<int>}>  $images
     * @return array{downloaded: int, skipped: int, bytes: int, packages_updated: int, failed: list<array{url: string, reason: string, package_ids: list<int>}>}
     */
    public function localize(Collection $images): array
    {
        $disk = Storage::disk('public');
        $localPaths = [];
        $failed = [];
        $downloaded = 0;
        $skipped = 0;
        $bytes = 0;

        foreach ($images as $url => $image) {
            if ($disk->exists($image['path'])) {
                $localPaths[$url] = $image['path'];
                $skipped++;

                continue;
            }

            $error = $this->download($url, $image['path'], $bytes);

            if ($error !== null) {
                $failed[] = ['url' => $url, 'reason' => $error, 'package_ids' => $image['package_ids']];

                continue;
            }

            $localPaths[$url] = $image['path'];
            $downloaded++;
        }

        return [
            'downloaded' => $downloaded,
            'skipped' => $skipped,
            'bytes' => $bytes,
            'packages_updated' => $this->rewritePackages($images, $localPaths),
            'failed' => $failed,
        ];
    }

    public function matchesHost(string $url, string $host): bool
    {
        $urlHost = parse_url($url, PHP_URL_HOST);

        if (! is_string($urlHost)) {
            return false;
        }

        return $this->withoutWww($urlHost) === $this->withoutWww($host);
    }

    /**
     * Map https://www.sharm-club.com/assets/images/cities/aswan/x.jpg to packages/legacy/cities/aswan/x.jpg.
     */
    public function targetPath(string $url): ?string
    {
        $path = rawurldecode((string) parse_url($url, PHP_URL_PATH));

        $relative = str_contains($path, self::SOURCE_DIRECTORY)
            ? Str::after($path, self::SOURCE_DIRECTORY)
            : ltrim($path, '/');

        $segments = array_filter(explode('/', $relative), fn (string $segment): bool => $segment !== '');

        if ($segments === [] || in_array('..', $segments, true)) {
            return null;
        }

        return self::TARGET_DIRECTORY.'/'.implode('/', $segments);
    }

    /**
     * Download one image to the public disk. Returns null on success or the reason it failed.
     */
    private function download(string $url, string $path, int &$bytes): ?string
    {
        try {
            $response = Http::withUserAgent(self::USER_AGENT)
                ->timeout(20)
                ->retry(3, 500, fn (Throwable $exception): bool => $exception instanceof ConnectionException
                    || ($exception instanceof RequestException && $exception->response->serverError()), throw: false)
                ->get($url);
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }

        if (! $response->successful() || $response->status() !== 200) {
            return "HTTP {$response->status()}";
        }

        $body = $response->body();
        $contentType = strtolower((string) $response->header('Content-Type'));

        // The old site serves some .webp files without a Content-Type, so sniff the bytes when the header is missing.
        if ($contentType === '') {
            $contentType = (string) (new finfo(FILEINFO_MIME_TYPE))->buffer($body);
        }

        if (! str_starts_with($contentType, 'image/')) {
            return "Not an image ({$contentType})";
        }

        if (! Storage::disk('public')->put($path, $body)) {
            return 'Could not write to the public disk';
        }

        $bytes += strlen($body);

        return null;
    }

    /**
     * @param  Collection<string, array{url: string, path: string, package_ids: list<int>}>  $images
     * @param  array<string, string>  $localPaths
     */
    private function rewritePackages(Collection $images, array $localPaths): int
    {
        if ($localPaths === []) {
            return 0;
        }

        $packageIds = $images->only(array_keys($localPaths))->pluck('package_ids')->flatten()->unique()->values();
        $updated = 0;

        DB::transaction(function () use ($packageIds, $localPaths, &$updated): void {
            DB::table('packages')
                ->whereIn('id', $packageIds)
                ->select(['id', 'image_url', 'gallery'])
                ->lazyById()
                ->each(function (object $package) use ($localPaths, &$updated): void {
                    $changes = [];

                    if (is_string($package->image_url) && isset($localPaths[$package->image_url])) {
                        $changes['image_url'] = $localPaths[$package->image_url];
                    }

                    $gallery = $this->decodeGallery($package->gallery);
                    $rewrittenGallery = $this->rewriteGallery($gallery, $localPaths);

                    if ($rewrittenGallery !== $gallery) {
                        $changes['gallery'] = json_encode($rewrittenGallery, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    }

                    if ($changes === []) {
                        return;
                    }

                    DB::table('packages')->where('id', $package->id)->update([...$changes, 'updated_at' => now()]);
                    $updated++;
                });
        });

        return $updated;
    }

    private function decodeGallery(mixed $raw): mixed
    {
        return is_string($raw) ? json_decode($raw, true) : null;
    }

    /**
     * Every "image" value in a gallery, whatever the nesting ({locale: [{image, caption}]} or a plain list).
     *
     * @return list<mixed>
     */
    private function galleryImages(mixed $gallery): array
    {
        if (! is_array($gallery)) {
            return [];
        }

        $images = [];

        foreach ($gallery as $key => $value) {
            if ($key === 'image') {
                $images[] = $value;
            } elseif (is_array($value)) {
                array_push($images, ...$this->galleryImages($value));
            }
        }

        return $images;
    }

    /**
     * Replace mapped "image" values and leave captions and every other key as they are.
     *
     * @param  array<string, string>  $localPaths
     */
    private function rewriteGallery(mixed $gallery, array $localPaths): mixed
    {
        if (! is_array($gallery)) {
            return $gallery;
        }

        foreach ($gallery as $key => $value) {
            if ($key === 'image' && is_string($value) && isset($localPaths[$value])) {
                $gallery[$key] = $localPaths[$value];
            } elseif (is_array($value)) {
                $gallery[$key] = $this->rewriteGallery($value, $localPaths);
            }
        }

        return $gallery;
    }

    private function withoutWww(string $host): string
    {
        return Str::of($host)->lower()->chopStart('www.')->toString();
    }
}
