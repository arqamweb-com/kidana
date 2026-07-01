<?php

namespace App\Services;

use App\Models\Package;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PackageCsvExporter
{
    /**
     * Scalar columns written as-is, in CSV order.
     *
     * @var array<int, string>
     */
    public const SCALAR_COLUMNS = [
        'name',
        'slug',
        'service_id',
        'destination_id',
        'description',
        'price',
        'order_action',
        'location_label',
        'start_date',
        'end_date',
        'max_guests',
        'days',
        'image_url',
        'sort_order',
        'is_active',
    ];

    /**
     * Array columns whose values are joined with " | ", in CSV order.
     *
     * @var array<int, string>
     */
    public const LIST_COLUMNS = [
        'features',
        'tags',
        'highlights',
        'itinerary',
        'included_items',
        'excluded_items',
        'gallery',
    ];

    /**
     * The ordered header row for the CSV file.
     *
     * @return array<int, string>
     */
    public static function headers(): array
    {
        return [...self::SCALAR_COLUMNS, ...self::LIST_COLUMNS];
    }

    /**
     * Map a single package to an ordered CSV row.
     *
     * @return array<int, string>
     */
    public static function row(Package $package): array
    {
        $row = [];

        foreach (self::SCALAR_COLUMNS as $column) {
            $value = $package->getAttribute($column);

            if ($value instanceof \BackedEnum) {
                $value = $value->value;
            }

            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            }

            $row[] = (string) ($value ?? '');
        }

        foreach (self::LIST_COLUMNS as $column) {
            $value = $package->getAttribute($column);

            if (! is_array($value)) {
                $row[] = (string) ($value ?? '');

                continue;
            }

            // Repeater-backed columns store rows of arrays (e.g. {icon, title} or {image, caption}).
            // Flatten each row to its human-readable value so export never fails on nested arrays.
            $row[] = collect($value)
                ->map(static function ($item): string {
                    if (is_array($item)) {
                        return (string) (data_get($item, 'title') ?? data_get($item, 'image') ?? '');
                    }

                    return (string) $item;
                })
                ->filter()
                ->implode(' | ');
        }

        return $row;
    }

    /**
     * Stream all packages (respecting the given query) as a downloadable CSV.
     */
    public function download(Builder $query, string $fileName): StreamedResponse
    {
        $headers = self::headers();

        return response()->streamDownload(function () use ($query, $headers): void {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM so Excel renders Arabic text correctly.
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers);

            $query->chunk(200, function ($packages) use ($handle): void {
                foreach ($packages as $package) {
                    fputcsv($handle, self::row($package));
                }
            });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
