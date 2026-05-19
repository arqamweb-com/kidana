@extends('layout.master')
@section('title', __('packages.search.title'))
@section('meta_description', __('packages.search.meta_description'))
@section('content')
    <div class="min-h-screen bg-background text-foreground antialiased">
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
        @endphp

        <main class="min-h-screen">
            <section class="border-b border-border/40 bg-muted/30 py-20">
                <div class="container mx-auto px-4 md:px-8">
                    <div class="mx-auto max-w-6xl">
                        <a href="{{ route('home') }}"
                           class="mb-8 inline-flex items-center gap-2 text-sm font-medium text-muted-foreground transition-colors duration-300 hover:text-primary">
                            <span>&larr;</span>
                            <span>{{ __('packages.search.back_home') }}</span>
                        </a>

                        <div class="mb-10">
                            <p class="mb-4 text-xs font-semibold uppercase tracking-[0.3em] text-primary">{{ __('packages.search.eyebrow') }}</p>
                            <h1 class="text-3xl font-bold text-foreground md:text-5xl">{{ __('packages.search.heading') }}</h1>
                            <p class="mt-4 max-w-2xl text-sm leading-relaxed text-muted-foreground md:text-base">
                                {{ __('packages.search.description') }}
                            </p>
                        </div>

                        <form action="{{ route('packages.search') }}" method="GET"
                              class="grid gap-4 rounded-3xl border border-border/50 bg-card p-6 shadow-[0_18px_60px_-20px_hsl(var(--foreground)/0.15)] md:grid-cols-[1.3fr_1fr_0.7fr_auto] md:items-center">
                            <div class="rounded-2xl border border-border/60 bg-background px-5 py-4">
                                <label for="destination"
                                       class="mb-2 block text-xs font-semibold uppercase tracking-[0.22em] text-muted-foreground">
                                    {{ __('packages.search.destination') }}
                                </label>
                                <input id="destination" name="destination" list="search-destinations"
                                       value="{{ $filters['destination'] ?? '' }}" placeholder="{{ __('packages.search.destination_placeholder') }}"
                                       class="w-full bg-transparent text-sm font-medium text-foreground outline-none placeholder:text-muted-foreground/50">
                                <datalist id="search-destinations">
                                    @foreach ($destinations as $destination)
                                        <option value="{{ $destination->slug }}">{{ $destination->name }}</option>
                                    @endforeach
                                </datalist>
                            </div>

                            <div class="rounded-2xl border border-border/60 bg-background px-5 py-4">
                                <label for="travel_date"
                                       class="mb-2 block text-xs font-semibold uppercase tracking-[0.22em] text-muted-foreground">
                                    {{ __('packages.search.travel_date') }}
                                </label>
                                <input id="travel_date" type="date" name="travel_date"
                                       value="{{ $filters['travel_date'] ?? '' }}"
                                       class="w-full bg-transparent text-sm font-medium text-foreground outline-none [color-scheme:light]">
                            </div>

                            <div class="rounded-2xl border border-border/60 bg-background px-5 py-4">
                                <label for="guests"
                                       class="mb-2 block text-xs font-semibold uppercase tracking-[0.22em] text-muted-foreground">
                                    {{ __('packages.search.guests') }}
                                </label>
                                <input id="guests" type="number" min="1" name="guests"
                                       value="{{ $filters['guests'] ?? '' }}" placeholder="{{ __('packages.search.guests_placeholder') }}"
                                       class="w-full bg-transparent text-sm font-medium text-foreground outline-none placeholder:text-muted-foreground/50">
                            </div>

                            <button type="submit"
                                    class="btn-premium inline-flex items-center justify-center gap-2 rounded-2xl bg-primary px-8 py-5 text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90">
                                {{ __('packages.search.submit') }}
                            </button>
                        </form>
                    </div>
                </div>
            </section>

            <section class="section-padding bg-background">
                <div class="container mx-auto px-4 md:px-8">
                    <div class="mb-12 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="mb-4 text-xs font-semibold uppercase tracking-[0.3em] text-primary">{{ __('packages.search.results') }}</p>
                            <h2 class="text-3xl font-bold text-foreground md:text-4xl">
                                {{ trans_choice('packages.search.results_count', method_exists($packages, 'total') ? $packages->total() : $packages->count(), ['count' => method_exists($packages, 'total') ? $packages->total() : $packages->count()]) }}
                            </h2>
                        </div>

                        @if (filled($filters['destination'] ?? null) || filled($filters['travel_date'] ?? null) || filled($filters['guests'] ?? null))
                            <div class="text-sm text-muted-foreground">
                                {{ __('packages.search.filtered_notice') }}
                            </div>
                        @endif
                    </div>
                    @include('sections.packages-grid', [
                        'emptyTitle' => __('packages.search.empty_title'),
                        'emptyDescription' => __('packages.search.empty_description'),
                    ])
                </div>
            </section>
        </main>
    </div>

@endsection
