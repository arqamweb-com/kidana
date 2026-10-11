<?php

namespace App\Console\Commands;

use App\Services\LegacyPackageImageLocalizer;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

#[Signature('packages:localize-legacy-images
    {--dry-run : List the images and target paths without downloading or writing}
    {--host=sharm-club.com : Only images on this host (with or without www.) are moved}
    {--limit= : Only process the first N unique image URLs}')]
#[Description('Download package images hotlinked from the old site to the public disk and point the packages at the local copies.')]
class LocalizeLegacyPackageImages extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(LegacyPackageImageLocalizer $localizer): int
    {
        $images = $localizer->collect((string) $this->option('host'));
        $total = $images->count();

        if (filled($this->option('limit'))) {
            $images = $images->take(max(0, (int) $this->option('limit')));
        }

        if ($images->isEmpty()) {
            $this->info('No images left to localize.');

            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->table(
                ['URL', 'Target path', 'Packages', 'Already on disk'],
                $images->map(fn (array $image): array => [
                    $image['url'],
                    $image['path'],
                    count($image['package_ids']),
                    Storage::disk('public')->exists($image['path']) ? 'yes' : 'no',
                ])->values()->all(),
            );

            $packageCount = $images->pluck('package_ids')->flatten()->unique()->count();
            $this->info("Dry run: {$images->count()} of {$total} unique image URL(s) used by {$packageCount} package(s). Nothing was downloaded or written.");

            return self::SUCCESS;
        }

        $this->info("Localizing {$images->count()} of {$total} unique image URL(s)...");

        $result = $localizer->localize($images);

        if ($result['failed'] !== []) {
            $this->warn('These images could not be downloaded and were left as they are:');
            $this->table(
                ['URL', 'Reason', 'Package IDs'],
                array_map(fn (array $failure): array => [
                    $failure['url'],
                    $failure['reason'],
                    implode(', ', $failure['package_ids']),
                ], $result['failed']),
            );
        }

        $this->table(['Downloaded', 'Skipped (already present)', 'Failed', 'Packages updated', 'Total downloaded'], [[
            $result['downloaded'],
            $result['skipped'],
            count($result['failed']),
            $result['packages_updated'],
            Number::fileSize($result['bytes']),
        ]]);

        return $result['failed'] === [] ? self::SUCCESS : self::FAILURE;
    }
}
