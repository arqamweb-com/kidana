<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug')->unique();
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->foreignId('destination_id')
                ->nullable()
                ->after('service_id')
                ->constrained('destinations')
                ->nullOnDelete();

            $table->index(['is_active', 'destination_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('destination')->nullable()->after('description');
        });

        $destinations = DB::table('destinations')
            ->get(['id', 'name'])
            ->mapWithKeys(function (object $destination): array {
                $name = json_decode((string)$destination->name, true);

                return [
                    $destination->id => is_array($name)
                        ? (Arr::get($name, config('app.fallback_locale')) ?? Arr::first($name))
                        : null,
                ];
            });

        DB::table('packages')
            ->whereNotNull('destination_id')
            ->get(['id', 'destination_id'])
            ->each(function (object $package) use ($destinations): void {
                DB::table('packages')
                    ->where('id', $package->id)
                    ->update([
                        'destination' => $destinations->get($package->destination_id),
                    ]);
            });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'destination_id']);
            $table->dropConstrainedForeignId('destination_id');
            $table->index(['is_active', 'destination']);
        });

        Schema::dropIfExists('destinations');
    }

    private function migratePackageDestinationNames(): void
    {
        $now = now();
        $localeKeys = array_keys(config('locales.supported', [config('app.fallback_locale') => []]));
        $localeKeys = $localeKeys === [] ? [config('app.fallback_locale')] : $localeKeys;

        DB::table('packages')
            ->whereNotNull('destination')
            ->where('destination', '!=', '')
            ->select('destination')
            ->distinct()
            ->orderBy('destination')
            ->get()
            ->each(function (object $package) use ($localeKeys, $now): void {
                $destinationName = trim((string)$package->destination);
                $slug = $this->uniqueDestinationSlug($destinationName);

                $destinationId = DB::table('destinations')->insertGetId([
                    'name' => json_encode(array_fill_keys($localeKeys, $destinationName)),
                    'slug' => $slug,
                    'is_active' => true,
                    'sort_order' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                DB::table('packages')
                    ->where('destination', $package->destination)
                    ->update([
                        'destination_id' => $destinationId,
                        'updated_at' => $now,
                    ]);
            });
    }

    private function uniqueDestinationSlug(string $destinationName): string
    {
        $baseSlug = Str::slug($destinationName) ?: 'destination';
        $slug = $baseSlug;
        $counter = 2;

        while (DB::table('destinations')->where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
};
