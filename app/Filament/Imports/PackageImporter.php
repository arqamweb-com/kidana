<?php

namespace App\Filament\Imports;

use App\Enum\PackageOrderAction;
use App\Models\Package;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class PackageImporter extends Importer
{
    protected static ?string $model = Package::class;

    /**
     * @return array<int, ImportColumn>
     */
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('slug')
                ->rules(['max:255']),
            ImportColumn::make('service_id')
                ->numeric()
                ->rules(['nullable', 'integer', 'exists:services,id']),
            ImportColumn::make('destination_id')
                ->numeric()
                ->rules(['nullable', 'integer', 'exists:destinations,id']),
            ImportColumn::make('description')
                ->rules(['nullable']),
            ImportColumn::make('price')
                ->castStateUsing(static fn (mixed $state): int => blank($state) ? 0 : (int) $state)
                ->rules(['nullable', 'integer', 'min:0']),
            ImportColumn::make('order_action')
                ->rules(['nullable', 'in:'.implode(',', array_column(PackageOrderAction::cases(), 'value'))]),
            ImportColumn::make('location_label')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('start_date')
                ->rules(['nullable', 'date']),
            ImportColumn::make('end_date')
                ->rules(['nullable', 'date']),
            ImportColumn::make('max_guests')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:0']),
            ImportColumn::make('days')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:0']),
            ImportColumn::make('image_url')
                ->rules(['nullable', 'max:2048']),
            ImportColumn::make('sort_order')
                ->numeric()
                ->rules(['nullable', 'integer']),
            ImportColumn::make('is_active')
                ->boolean()
                ->rules(['nullable', 'boolean']),

            // Flat list columns (pipe-separated strings)
            ImportColumn::make('features')
                ->castStateUsing(static fn (?string $state): ?array => self::toList($state)),
            ImportColumn::make('tags')
                ->castStateUsing(static fn (?string $state): ?array => self::toList($state)),

            // Repeater-backed columns — stored as arrays of {icon, title} to match the form & front-end.
            ImportColumn::make('highlights')
                ->castStateUsing(static fn (?string $state): ?array => self::toIconTitleList($state, 'heroicon-o-star')),
            ImportColumn::make('included_items')
                ->castStateUsing(static fn (?string $state): ?array => self::toIconTitleList($state, 'heroicon-o-check')),
            ImportColumn::make('excluded_items')
                ->castStateUsing(static fn (?string $state): ?array => self::toIconTitleList($state, 'heroicon-o-x-mark')),

            // Itinerary — stored as {day_label, icon, title, description}.
            ImportColumn::make('itinerary')
                ->castStateUsing(static fn (?string $state): ?array => self::toItinerary($state)),

            // Gallery — stored as {image, caption}.
            ImportColumn::make('gallery')
                ->castStateUsing(static fn (?string $state): ?array => self::toGallery($state)),
        ];
    }

    public function resolveRecord(): Package
    {
        // Update an existing package when a matching slug is supplied, otherwise create a new one.
        if (filled($this->data['slug'] ?? null)) {
            return Package::firstOrNew(['slug' => $this->data['slug']]);
        }

        return new Package;
    }

    /**
     * Fill the record honouring the locale chosen in the import action.
     *
     * For the application's fallback locale the record is filled normally. For any
     * other locale only the translatable attributes (name, description) are set for
     * that locale, so shared columns and existing translations stay intact.
     */
    public function fillRecord(): void
    {
        $locale = $this->options['locale'] ?? config('app.fallback_locale');

        app()->setLocale($locale);

        if ($locale === config('app.fallback_locale')) {
            parent::fillRecord();

            return;
        }

        // Non-base locale: only add translations for the translatable attributes,
        // keeping shared columns and other languages untouched.
        foreach (Package::make()->getTranslatableAttributes() as $attribute) {
            if (array_key_exists($attribute, $this->data) && filled($this->data[$attribute])) {
                $this->record->setTranslation($attribute, $locale, $this->data[$attribute]);
            }
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your package import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }

    /**
     * Split a pipe-separated cell into a trimmed list of values.
     *
     * @return array<int, string>|null
     */
    protected static function toList(?string $state): ?array
    {
        if (blank($state)) {
            return null;
        }

        return collect(explode('|', $state))
            ->map(static fn (string $value): string => trim($value))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Convert a pipe-separated cell into Repeater rows of {icon, title}.
     *
     * @return array<int, array<string, string>>|null
     */
    protected static function toIconTitleList(?string $state, string $icon): ?array
    {
        $items = self::toList($state);

        if ($items === null) {
            return null;
        }

        return array_map(static fn (string $title): array => [
            'icon' => $icon,
            'title' => $title,
        ], $items);
    }

    /**
     * Convert a pipe-separated cell into itinerary rows of {day_label, icon, title, description}.
     *
     * @return array<int, array<string, string>>|null
     */
    protected static function toItinerary(?string $state): ?array
    {
        $items = self::toList($state);

        if ($items === null) {
            return null;
        }

        // day_label left blank so the front-end uses its own localized "Day N" fallback.
        return array_map(static fn (string $title): array => [
            'day_label' => '',
            'icon' => 'heroicon-o-map-pin',
            'title' => $title,
            'description' => '',
        ], $items);
    }

    /**
     * Convert a pipe-separated cell of image paths into gallery rows of {image, caption}.
     *
     * @return array<int, array<string, string>>|null
     */
    protected static function toGallery(?string $state): ?array
    {
        $items = self::toList($state);

        if ($items === null) {
            return null;
        }

        return array_map(static fn (string $image): array => [
            'image' => $image,
            'caption' => '',
        ], $items);
    }
}
