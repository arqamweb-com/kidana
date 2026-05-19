@extends('layout.master')
@section('title', __('packages.index.title'))
@section('meta_description', __('packages.index.meta_description'))

@section('content')
    <section class="relative h-[40vh] min-h-[320px] flex items-center justify-center overflow-hidden"><img
            src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=1200&amp;q=80" alt="{{ __('packages.index.hero_alt') }}"
            class="absolute inset-0 w-full h-full object-cover">
        <div
            class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/80 via-[hsl(var(--hero-overlay))]/50 to-[hsl(var(--hero-overlay))]/30">
        </div>
        <div class="relative z-10 text-center text-primary-foreground px-4">
            <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('packages.index.eyebrow') }}</p>
            <h1 class="text-4xl md:text-6xl font-bold">{{ __('packages.index.heading') }}</h1>
        </div>
    </section>

    <section class="section-padding bg-background">
        @php
            $resolveImageUrl = static function (?string $imagePath, string $fallback): string {
                if (blank($imagePath)) {
                    return $fallback;
                }

                if (\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://'])) {
                    return $imagePath;
                }

                return \Illuminate\Support\Facades\Storage::url($imagePath);
            };

            $filterDestinations = $packages
                ->pluck('destination')
                ->filter(fn ($destination) => filled($destination?->slug))
                ->unique('slug')
                ->values()
                ->take(7);
        @endphp

        <div class="container mx-auto max-w-6xl">
            <div class="mb-12 text-center">
                <p class="text-muted-foreground text-lg leading-relaxed mb-10 text-center max-w-3xl mx-auto">{{ __('packages.index.description') }}</p>

                @if(!$packages->isEmpty())
                    <div id="filter" class="flex flex-wrap justify-center gap-3 mb-14">
                        <button type="button" data-package-filter="all" aria-pressed="true"
                                class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 border bg-primary text-primary-foreground border-primary shadow-[0_4px_16px_-4px_hsl(var(--primary)/0.5)]">
                            {{ __('packages.index.all_packages') }}
                        </button>
                        @foreach ($filterDestinations as $destination)
                            <button type="button" data-package-filter="{{ $destination->slug }}" aria-pressed="false"
                                    class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 border bg-card text-foreground border-border hover:border-primary hover:text-primary">
                                {{ $destination->name }}
                            </button>
                        @endforeach
                    </div>
                @endif

                <p class="mt-3 text-sm text-muted-foreground">
                    {{ trans_choice('packages.index.available_count', $packages->count(), ['count' => $packages->count()]) }}
                </p>
            </div>

            @if ($packages->isEmpty())
                <div class="rounded-3xl border border-dashed border-border/70 bg-muted/40 px-8 py-14 text-center">
                    <h3 class="text-2xl font-bold text-foreground">{{ __('packages.index.empty_title') }}</h3>
                    <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
                        {{ __('packages.index.empty_description') }}
                    </p>
                </div>
            @else
                @include('sections.packages-grid')
                <div data-package-filter-empty class="hidden rounded-3xl border border-dashed border-border/70 bg-muted/40 px-8 py-14 text-center">
                    <h3 class="text-2xl font-bold text-foreground">{{ __('packages.index.filter_empty_title') }}</h3>
                    <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
                        {{ __('packages.index.filter_empty_description') }}
                    </p>
                </div>
            @endif
        </div>
    </section>
@endsection
