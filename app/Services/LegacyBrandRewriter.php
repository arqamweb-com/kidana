<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Finds the old "Sharm Club" brand in content columns and rewrites it to Kidana Travel.
 *
 * Only text outside URLs is touched, so hotlinked images such as
 * https://www.sharm-club.com/assets/images/... keep working.
 */
class LegacyBrandRewriter
{
    /**
     * A brand mention that is not part of a URL: not preceded by a word character, "/" or "www.",
     * and not followed by a word character or "/". The ".com" suffix is possessive so a URL like
     * "sharm-club.com/assets" can not backtrack into a bare "sharm-club" match.
     */
    private const PATTERN = '~(?<![\w/])(?<!www\.)sharm[- ]club(?:\.com)?+(?![\w/])~i';

    private const CONTEXT_LENGTH = 20;

    /**
     * Columns rewritten by the command.
     *
     * @var array<string, list<string>>
     */
    public const WRITABLE_COLUMNS = [
        'packages' => ['description'],
    ];

    /**
     * Columns only scanned and reported. Slugs, image columns and transactional tables are never read.
     *
     * @var array<string, list<string>>
     */
    public const REPORTED_COLUMNS = [
        'packages' => ['name', 'location_label', 'features', 'highlights', 'itinerary', 'included_items', 'excluded_items'],
        'services' => ['name', 'description', 'hero_title', 'hero_subtitle', 'hero_description', 'intro_subtitle', 'intro_title', 'intro_text', 'stats', 'benefits', 'steps', 'cta_title', 'cta_subtitle'],
        'destinations' => ['name'],
        'hotels' => ['name', 'description', 'address', 'board_basis', 'cancellation_policy', 'amenities', 'policies'],
        'hotel_rooms' => ['name', 'description', 'bed_type', 'amenities'],
        'faqs' => ['title', 'answer'],
        'testimonials' => ['name', 'testimonial', 'position'],
        'offices' => ['name', 'description', 'location'],
    ];

    /**
     * Rewrite every brand mention in a piece of text.
     *
     * @return array{text: string, matches: list<array{before: string, after: string}>}
     */
    public function rewriteText(string $text): array
    {
        $matches = [];

        $rewritten = preg_replace_callback(self::PATTERN, function (array $match) use ($text, &$matches): string {
            [$found, $byteOffset] = $match[0];
            $replacement = $this->replacementFor($found);

            $left = mb_substr(substr($text, 0, $byteOffset), -self::CONTEXT_LENGTH);
            $right = mb_substr(substr($text, $byteOffset + strlen($found)), 0, self::CONTEXT_LENGTH);

            $matches[] = [
                'before' => $left.$found.$right,
                'after' => $left.$replacement.$right,
            ];

            return $replacement;
        }, $text, flags: PREG_OFFSET_CAPTURE);

        return ['text' => $rewritten, 'matches' => $matches];
    }

    /**
     * Scan the configured tables and describe every column value that mentions the brand.
     *
     * @return Collection<int, array{table: string, id: int, slug: ?string, column: string, writable: bool, value: string, matches: list<array{path: string, before: string, after: string}>}>
     */
    public function findChanges(): Collection
    {
        $changes = collect();

        foreach ($this->scannedColumns() as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $hasSlug = Schema::hasColumn($table, 'slug');
            $existingColumns = array_values(array_filter(
                array_keys($columns),
                fn (string $column): bool => Schema::hasColumn($table, $column),
            ));

            DB::table($table)
                ->select(array_merge(['id'], $hasSlug ? ['slug'] : [], $existingColumns))
                ->lazyById()
                ->each(function (object $row) use ($table, $columns, $existingColumns, $hasSlug, $changes): void {
                    foreach ($existingColumns as $column) {
                        $change = $this->rewriteColumnValue($row->{$column});

                        if ($change === null) {
                            continue;
                        }

                        $changes->push([
                            'table' => $table,
                            'id' => (int) $row->id,
                            'slug' => $hasSlug ? $row->slug : null,
                            'column' => $column,
                            'writable' => $columns[$column],
                            ...$change,
                        ]);
                    }
                });
        }

        return $changes;
    }

    /**
     * Write the writable changes inside one transaction and return how many columns were updated.
     *
     * @param  Collection<int, array{table: string, id: int, column: string, writable: bool, value: string}>  $changes
     */
    public function apply(Collection $changes): int
    {
        $writable = $changes->where('writable', true);

        DB::transaction(function () use ($writable): void {
            foreach ($writable as $change) {
                DB::table($change['table'])
                    ->where('id', $change['id'])
                    ->update([
                        $change['column'] => $change['value'],
                        'updated_at' => now(),
                    ]);
            }
        });

        return $writable->count();
    }

    /**
     * @return array<string, array<string, bool>>
     */
    private function scannedColumns(): array
    {
        $scanned = [];

        foreach (self::WRITABLE_COLUMNS as $table => $columns) {
            foreach ($columns as $column) {
                $scanned[$table][$column] = true;
            }
        }

        foreach (self::REPORTED_COLUMNS as $table => $columns) {
            foreach ($columns as $column) {
                $scanned[$table][$column] ??= false;
            }
        }

        return $scanned;
    }

    /**
     * Rewrite a raw column value. JSON values are decoded, rewritten string by string and
     * re-encoded with real UTF-8 characters and unescaped slashes, matching the existing rows.
     *
     * @return array{value: string, matches: list<array{path: string, before: string, after: string}>}|null
     */
    private function rewriteColumnValue(mixed $raw): ?array
    {
        if (! is_string($raw) || $raw === '') {
            return null;
        }

        $decoded = json_decode($raw, true);
        $isJson = json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_string($decoded));

        $matches = [];
        $rewritten = $this->rewriteValue($isJson ? $decoded : $raw, '', $matches);

        if ($matches === []) {
            return null;
        }

        return [
            'value' => $isJson
                ? json_encode($rewritten, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                : $rewritten,
            'matches' => $matches,
        ];
    }

    /**
     * @param  list<array{path: string, before: string, after: string}>  $matches
     */
    private function rewriteValue(mixed $value, string $path, array &$matches): mixed
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->rewriteValue($item, $path === '' ? (string) $key : "{$path}.{$key}", $matches);
            }

            return $value;
        }

        if (! is_string($value)) {
            return $value;
        }

        $result = $this->rewriteText($value);

        foreach ($result['matches'] as $match) {
            $matches[] = ['path' => $path === '' ? '-' : $path, ...$match];
        }

        return $result['text'];
    }

    private function replacementFor(string $found): string
    {
        return str_ends_with(strtolower($found), '.com') ? 'kidanatravel.com' : 'Kidana Travel';
    }
}
