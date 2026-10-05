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

    $isRtl = config('locales.supported.' . app()->getLocale() . '.direction') === 'rtl';
    $forwardArrowPath = $isRtl ? 'm15 18-6-6 6-6' : 'm12 5 7 7-7 7';
@endphp

<div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
    @foreach ($hotels as $hotel)
        @php
            $hotelUrl = route('hotels.show', ['hotel' => $hotel->slug]);
        @endphp

        <article data-hotel-card
                 class="group overflow-hidden rounded-3xl border border-border/50 bg-card transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_24px_80px_-30px_hsl(var(--foreground)/0.18)]">
            <div class="relative h-64 overflow-hidden bg-muted">
                <a href="{{ $hotelUrl }}" class="block h-full w-full">
                    <img src="{{ $resolveImageUrl($hotel->image_url, 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&q=80') }}"
                         alt="{{ $hotel->name }}" loading="lazy"
                         class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                </a>

                <div class="absolute end-4 top-4 rounded-xl bg-primary px-4 py-2 text-sm font-bold text-primary-foreground shadow-[0_8px_24px_-8px_hsl(var(--primary)/0.6)]">
                    @if ($hotel->starting_price)
                        EGP {{ number_format((float) $hotel->starting_price, 0) }}
                        <span class="text-xs font-medium">{{ __('hotels.card.per_night') }}</span>
                    @else
                        {{ __('hotels.card.on_request') }}
                    @endif
                </div>

                @if ($hotel->stars)
                    <div class="absolute start-4 top-4 inline-flex items-center gap-0.5 rounded-lg bg-secondary px-3 py-1.5 text-secondary-foreground shadow-md"
                         aria-label="{{ __('hotels.card.stars', ['count' => $hotel->stars]) }}">
                        @for ($star = 0; $star < $hotel->stars; $star++)
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-3 w-3 fill-current" aria-hidden="true">
                                <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>
                            </svg>
                        @endfor
                    </div>
                @endif
            </div>

            <div class="p-7">
                <div class="mb-4 flex flex-wrap items-center gap-2">
                    @if ($hotel->destination?->slug)
                        <a href="{{ route('destinations.show', ['destination' => $hotel->destination->slug]) }}"
                           class="rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-primary transition-colors hover:bg-primary hover:text-primary-foreground">
                            {{ $hotel->destination->name }}
                        </a>
                    @endif

                    @if ($hotel->board_basis)
                        <span class="rounded-full bg-accent px-3 py-1 text-xs font-medium text-accent-foreground">
                            {{ $hotel->board_basis->label() }}
                        </span>
                    @endif
                </div>

                <h3 class="mb-3 text-xl font-bold text-foreground">
                    <a href="{{ $hotelUrl }}" class="transition-colors hover:text-primary">{{ $hotel->name }}</a>
                </h3>

                @if (filled($hotel->address))
                    <p class="mb-6 flex items-start gap-1.5 text-sm text-muted-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 h-4 w-4 shrink-0 text-secondary" aria-hidden="true">
                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span class="line-clamp-2">{{ $hotel->address }}</span>
                    </p>
                @endif

                <a href="{{ $hotelUrl }}"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-border bg-background px-4 py-3.5 text-sm font-semibold shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-primary hover:bg-primary hover:text-primary-foreground hover:shadow-lg">
                    {{ __('hotels.card.view_details') }}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                        <path d="M5 12h14"/>
                        <path d="{{ $forwardArrowPath }}"/>
                    </svg>
                </a>
            </div>
        </article>
    @endforeach
</div>
