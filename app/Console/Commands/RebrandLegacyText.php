<?php

namespace App\Console\Commands;

use App\Services\LegacyBrandRewriter;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('content:rebrand-legacy-text {--dry-run : List the replacements without writing anything}')]
#[Description('Replace the old Sharm Club brand text in package descriptions with Kidana Travel, leaving URLs untouched.')]
class RebrandLegacyText extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(LegacyBrandRewriter $rewriter): int
    {
        $changes = $rewriter->findChanges();

        $rows = $changes->flatMap(fn (array $change): array => array_map(fn (array $match): array => [
            $change['table'],
            $change['id'],
            $change['slug'] ?? '-',
            $change['column'],
            $match['path'],
            $match['before'],
            $match['after'],
            $change['writable'] ? 'rewrite' : 'report only',
        ], $change['matches']));

        if ($rows->isNotEmpty()) {
            $this->table(['Table', 'ID', 'Slug', 'Column', 'Locale', 'Before', 'After', 'Action'], $rows->all());
        }

        $writable = $changes->where('writable', true);
        $reportOnly = $changes->where('writable', false);
        $matchCount = $writable->sum(fn (array $change): int => count($change['matches']));

        if ($reportOnly->isNotEmpty()) {
            $this->warn("Found {$reportOnly->count()} column value(s) outside packages.description that mention the old brand. They were not changed; review them by hand.");
        }

        if ($this->option('dry-run')) {
            $this->info("Dry run: {$matchCount} replacement(s) in {$writable->count()} column value(s) across {$writable->unique('id')->count()} row(s). Nothing was written.");

            return self::SUCCESS;
        }

        $updated = $rewriter->apply($changes);

        $this->info("Done: {$matchCount} replacement(s) in {$updated} column value(s) across {$writable->unique('id')->count()} row(s).");

        return self::SUCCESS;
    }
}
