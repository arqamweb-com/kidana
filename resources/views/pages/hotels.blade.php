@extends('layout.master')
@section('title', __('hotels.index.title'))
@section('meta_description', __('hotels.index.meta_description'))

@section('content')
    <section class="relative h-[40vh] min-h-[320px] flex items-center justify-center overflow-hidden">
        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&amp;q=80"
             alt="{{ __('hotels.index.hero_alt') }}" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/80 via-[hsl(var(--hero-overlay))]/50 to-[hsl(var(--hero-overlay))]/30"></div>
        <div class="relative z-10 text-center text-primary-foreground px-4">
            <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('hotels.index.eyebrow') }}</p>
            <h1 class="text-4xl md:text-6xl font-bold">{{ __('hotels.index.heading') }}</h1>
        </div>
    </section>

    <section class="section-padding bg-background">
        <div class="container mx-auto max-w-6xl">
            <div class="mb-12 text-center">
                <p class="text-muted-foreground text-lg leading-relaxed mb-10 max-w-3xl mx-auto">{{ __('hotels.index.description') }}</p>

                <form method="GET" action="{{ route('hotels') }}"
                      class="mx-auto mb-6 flex max-w-3xl flex-col gap-3 rounded-2xl border border-border/60 bg-card p-3 shadow-sm sm:flex-row">
                    <select name="destination" aria-label="{{ __('hotels.index.all_destinations') }}"
                            class="flex-1 rounded-xl border border-border bg-background px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                        <option value="">{{ __('hotels.index.all_destinations') }}</option>
                        @foreach ($destinations as $destination)
                            <option value="{{ $destination->slug }}" @selected($selectedDestination === $destination->slug)>{{ $destination->name }}</option>
                        @endforeach
                    </select>
                    <select name="stars" aria-label="{{ __('hotels.index.any_stars') }}"
                            class="flex-1 rounded-xl border border-border bg-background px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                        <option value="">{{ __('hotels.index.any_stars') }}</option>
                        @foreach ([5, 4, 3, 2, 1] as $starsOption)
                            <option value="{{ $starsOption }}" @selected($selectedStars === $starsOption)>{{ __('hotels.index.stars_option', ['count' => $starsOption]) }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="rounded-xl bg-primary px-8 py-3 text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90">
                        {{ __('hotels.index.filter') }}
                    </button>
                </form>

                <p class="text-sm text-muted-foreground">
                    {{ trans_choice('hotels.index.available_count', $hotels->total(), ['count' => $hotels->total()]) }}
                </p>
            </div>

            @if ($hotels->isEmpty())
                <div class="rounded-3xl border border-dashed border-border/70 bg-muted/40 px-8 py-14 text-center">
                    <h3 class="text-2xl font-bold text-foreground">{{ __('hotels.index.empty_title') }}</h3>
                    <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
                        {{ __('hotels.index.empty_description') }}
                    </p>
                </div>
            @else
                @include('sections.hotels-grid')

                @if ($hotels->hasPages())
                    <div class="mt-12">
                        {{ $hotels->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection
