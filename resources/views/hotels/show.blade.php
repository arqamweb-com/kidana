@extends('layout.master')

@section('title', $hotel->name)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags((string) $hotel->description), 155))
@section('canonical', route('hotels.show', ['hotel' => $hotel->slug]))
@section('seo_image', filled($hotel->image_url) && \Illuminate\Support\Str::startsWith($hotel->image_url,
    ['http://', 'https://']) ? $hotel->image_url : (filled($hotel->image_url) ?
    \Illuminate\Support\Facades\Storage::url($hotel->image_url) : asset('favicon.ico')))

@section('content')
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
        $renderIcon = static function (?string $icon, string $classes = 'h-5 w-5 text-primary'): string {
            try {
                return svg(filled($icon) ? $icon : 'heroicon-o-check', $classes)->toHtml();
            } catch (\Throwable) {
                return svg('heroicon-o-check', $classes)->toHtml();
            }
        };
        $hotelImageUrl = $resolveImageUrl($hotel->image_url, 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&q=80');
        $galleryImages = collect($hotel->gallery ?? [])
            ->filter(fn ($item): bool => filled(data_get($item, 'image')))
            ->values();
        $featuredGalleryImage = $galleryImages->first();
        $thumbnailGalleryImages = $galleryImages->take(4)->values();
        $amenityItems = collect($hotel->amenities ?? [])
            ->filter(fn ($item): bool => filled(data_get($item, 'title')))
            ->values();
        $policyItems = collect($hotel->policies ?? [])
            ->filter(fn ($item): bool => filled(data_get($item, 'title')))
            ->values();
        $faqItems = collect($hotel->faqs ?? [])
            ->filter(fn ($faq): bool => filled($faq->title) || filled($faq->answer))
            ->values();
        $rooms = $hotel->rooms;
        $startingPrice = $rooms->min('price_per_night');
        $selectedRoomId = (int) old('room_id', $rooms->count() === 1 ? $rooms->first()->id : 0);
        $whatsappText = rawurlencode(__('hotels.show.whatsapp_message', ['hotel' => $hotel->name]));
        $whatsappUrl = 'https://wa.me/' . config('app.whatsapp_number') . "?text={$whatsappText}";
        $inputClasses = 'w-full rounded-xl border border-border bg-background px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30';
    @endphp

    <div class="min-h-screen">
        <section class="relative flex min-h-[max(500px,68vh)] items-end overflow-hidden">
            <img src="{{ $hotelImageUrl }}" alt="{{ $hotel->name }}" class="absolute inset-0 h-full w-full scale-105 object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/70 via-[hsl(var(--hero-overlay))]/40 to-[hsl(var(--hero-overlay))]/95"></div>
            <div class="relative z-10 container mx-auto px-4 pb-12 pt-32 text-primary-foreground animate-fade-in-up md:px-8 md:pb-16 md:pt-40">
                <x-breadcrumbs variant="overlay" align="center" class="mb-6" :items="[['label' => __('nav.hotels'), 'url' => route('hotels')], ['label' => $hotel->name]]" />

                @if ($hotel->stars)
                    <div class="mb-4 flex items-center gap-1 text-primary" aria-label="{{ __('hotels.card.stars', ['count' => $hotel->stars]) }}">
                        @for ($star = 0; $star < $hotel->stars; $star++)
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                                <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>
                            </svg>
                        @endfor
                    </div>
                @endif

                <h1 class="mb-4 max-w-3xl text-3xl font-bold leading-tight md:text-5xl lg:text-6xl">{{ $hotel->name }}</h1>

                @if (filled($hotel->address))
                    <p class="mb-7 max-w-2xl text-base text-primary-foreground/85 md:text-lg">{{ $hotel->address }}</p>
                @endif

                <div class="flex flex-wrap gap-3">
                    <a href="#availability"
                       class="btn-premium inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-10 py-4 text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90">
                        {{ __('hotels.show.availability_title') }}
                    </a>
                    @if (filled($hotel->map_url))
                        <a href="{{ $hotel->map_url }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center justify-center rounded-xl border border-primary-foreground/20 bg-primary-foreground/10 px-6 py-4 text-sm font-semibold text-primary-foreground backdrop-blur transition-colors hover:bg-primary-foreground/20">
                            {{ __('hotels.show.view_map') }}
                        </a>
                    @endif
                </div>
            </div>
        </section>

        <section class="sticky top-[68px] z-30 border-b border-border/60 bg-card backdrop-blur">
            <div class="container mx-auto px-4 py-5">
                <dl class="grid grid-cols-2 items-center gap-5 md:grid-cols-4">
                    <div class="flex items-center gap-3">
                        {!! $renderIcon('heroicon-o-map-pin', 'h-5 w-5 shrink-0 text-primary') !!}
                        <div>
                            <dt class="text-[10px] uppercase tracking-wider text-muted-foreground">{{ __('hotels.show.location') }}</dt>
                            <dd class="truncate text-sm font-bold text-foreground">{{ $hotel->destination?->name ?? '—' }}</dd>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        {!! $renderIcon('heroicon-o-cake', 'h-5 w-5 shrink-0 text-primary') !!}
                        <div>
                            <dt class="text-[10px] uppercase tracking-wider text-muted-foreground">{{ __('hotels.show.board_basis') }}</dt>
                            <dd class="text-sm font-bold text-foreground">{{ $hotel->board_basis?->label() ?? '—' }}</dd>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        {!! $renderIcon('heroicon-o-clock', 'h-5 w-5 shrink-0 text-primary') !!}
                        <div>
                            <dt class="text-[10px] uppercase tracking-wider text-muted-foreground">{{ __('hotels.show.check_in_time') }} / {{ __('hotels.show.check_out_time') }}</dt>
                            <dd class="text-sm font-bold text-foreground" dir="ltr">{{ $hotel->check_in_time ?? '—' }} / {{ $hotel->check_out_time ?? '—' }}</dd>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        {!! $renderIcon('heroicon-o-credit-card', 'h-5 w-5 shrink-0 text-primary') !!}
                        <div>
                            <dt class="text-[10px] uppercase tracking-wider text-muted-foreground">{{ __('hotels.show.starting_from') }}</dt>
                            <dd class="text-sm font-bold text-foreground">
                                @if ($startingPrice)
                                    EGP {{ number_format((float) $startingPrice, 0) }} {{ __('hotels.show.per_night') }}
                                @else
                                    {{ __('hotels.card.on_request') }}
                                @endif
                            </dd>
                        </div>
                    </div>
                </dl>
            </div>
        </section>

        @if ($featuredGalleryImage)
            @php
                $featuredGalleryImageUrl = $resolveImageUrl(data_get($featuredGalleryImage, 'image'), $hotelImageUrl);
                $featuredGalleryImageCaption = data_get($featuredGalleryImage, 'caption');
            @endphp
            <section class="section-padding bg-background">
                <div class="container mx-auto max-w-6xl">
                    <div data-package-gallery class="grid gap-4 md:gap-6 lg:grid-cols-[2fr_1fr]">
                        <div class="group relative overflow-hidden rounded-3xl">
                            <img data-package-gallery-featured src="{{ $featuredGalleryImageUrl }}"
                                 alt="{{ $featuredGalleryImageCaption ?: __('hotels.show.gallery_image_alt', ['hotel' => $hotel->name, 'number' => 1]) }}"
                                 class="aspect-[16/10] h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <div data-package-gallery-caption-wrapper
                                 class="{{ filled($featuredGalleryImageCaption) ? '' : 'hidden' }} absolute inset-x-0 bottom-0 bg-gradient-to-t from-secondary/80 to-transparent p-6 pt-16">
                                <p data-package-gallery-caption class="text-sm font-semibold text-primary-foreground">{{ $featuredGalleryImageCaption }}</p>
                            </div>
                        </div>

                        @if ($thumbnailGalleryImages->count() > 1)
                            <div class="grid grid-cols-2 gap-4 md:gap-6 lg:grid-cols-1">
                                @foreach ($thumbnailGalleryImages as $galleryImage)
                                    @php
                                        $galleryImageUrl = $resolveImageUrl(data_get($galleryImage, 'image'), $hotelImageUrl);
                                        $galleryImageCaption = data_get($galleryImage, 'caption');
                                        $galleryImageAlt = $galleryImageCaption ?: __('hotels.show.gallery_image_alt', ['hotel' => $hotel->name, 'number' => $loop->iteration]);
                                    @endphp
                                    <button type="button" data-package-gallery-thumbnail
                                            data-package-gallery-src="{{ $galleryImageUrl }}"
                                            data-package-gallery-alt="{{ $galleryImageAlt }}"
                                            data-package-gallery-caption="{{ $galleryImageCaption }}"
                                            class="group relative overflow-hidden rounded-2xl transition-all {{ $loop->first ? 'ring-2 ring-primary ring-offset-2 ring-offset-background' : 'ring-0 ring-transparent ring-offset-0' }}"
                                            aria-label="{{ __('hotels.show.view_image', ['number' => $loop->iteration]) }}"
                                            aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                                        <img src="{{ $galleryImageUrl }}" alt="{{ $galleryImageAlt }}" loading="lazy"
                                             class="aspect-[4/3] h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        <section class="bg-muted/40">
            <div class="container mx-auto max-w-6xl px-4 py-16 md:px-8 md:py-24">
                <div class="grid gap-10 lg:grid-cols-[1fr_380px]">
                    <div class="space-y-16">
                        @if (filled($hotel->description))
                            <div>
                                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.3em] text-primary">{{ __('hotels.show.overview_eyebrow') }}</p>
                                <h2 class="mb-6 text-2xl font-bold leading-tight text-foreground md:text-4xl">{{ __('hotels.show.overview_title') }}</h2>
                                <div class="text-base leading-relaxed text-muted-foreground md:text-lg">{!! $hotel->description !!}</div>
                            </div>
                        @endif

                        @if ($amenityItems->isNotEmpty())
                            <div>
                                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.3em] text-primary">{{ __('hotels.show.amenities_eyebrow') }}</p>
                                <h2 class="mb-6 text-2xl font-bold leading-tight text-foreground md:text-4xl">{{ __('hotels.show.amenities_title') }}</h2>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    @foreach ($amenityItems as $amenity)
                                        <div class="flex items-center gap-4 rounded-2xl border border-border/60 bg-card p-5 shadow-sm">
                                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10">
                                                {!! $renderIcon(data_get($amenity, 'icon')) !!}
                                            </div>
                                            <span class="text-sm font-semibold text-foreground">{{ data_get($amenity, 'title') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div id="availability" class="scroll-mt-40">
                            <p class="mb-3 text-xs font-semibold uppercase tracking-[0.3em] text-primary">{{ __('hotels.show.rooms_eyebrow') }}</p>
                            <h2 class="mb-6 text-2xl font-bold leading-tight text-foreground md:text-4xl">{{ __('hotels.show.rooms_title') }}</h2>

                            @if ($rooms->isEmpty())
                                <div class="rounded-2xl border border-dashed border-border/70 bg-card px-6 py-10 text-center text-sm text-muted-foreground">
                                    {{ __('hotels.show.no_rooms') }}
                                </div>
                            @else
                                <form method="GET" action="{{ route('hotels.book', ['hotel' => $hotel->slug]) }}" data-hotel-booking class="space-y-6">
                                    @if ($errors->any())
                                        <div class="rounded-xl border border-destructive/30 bg-destructive/10 px-4 py-3 text-sm text-destructive">
                                            {{ $errors->first() }}
                                        </div>
                                    @endif

                                    <fieldset class="grid gap-4 rounded-2xl border border-border/60 bg-card p-5 shadow-sm sm:grid-cols-2 lg:grid-cols-5">
                                        <legend class="sr-only">{{ __('hotels.show.availability_title') }}</legend>
                                        <label class="space-y-2 lg:col-span-1">
                                            <span class="text-xs font-semibold text-foreground">{{ __('hotels.show.check_in') }}</span>
                                            <input type="date" name="check_in" value="{{ old('check_in') }}" min="{{ now()->toDateString() }}" required data-hotel-check-in class="{{ $inputClasses }}">
                                        </label>
                                        <label class="space-y-2 lg:col-span-1">
                                            <span class="text-xs font-semibold text-foreground">{{ __('hotels.show.check_out') }}</span>
                                            <input type="date" name="check_out" value="{{ old('check_out') }}" min="{{ now()->addDay()->toDateString() }}" required data-hotel-check-out class="{{ $inputClasses }}">
                                        </label>
                                        <label class="space-y-2">
                                            <span class="text-xs font-semibold text-foreground">{{ __('hotels.show.rooms') }}</span>
                                            <input type="number" name="rooms" value="{{ old('rooms', 1) }}" min="1" max="{{ \App\Http\Requests\HotelStayRequest::MAX_ROOMS }}" required data-hotel-rooms class="{{ $inputClasses }}">
                                        </label>
                                        <label class="space-y-2">
                                            <span class="text-xs font-semibold text-foreground">{{ __('hotels.show.adults') }}</span>
                                            <input type="number" name="adults" value="{{ old('adults', 2) }}" min="1" max="99" required class="{{ $inputClasses }}">
                                        </label>
                                        <label class="space-y-2">
                                            <span class="text-xs font-semibold text-foreground">{{ __('hotels.show.children') }}</span>
                                            <input type="number" name="children" value="{{ old('children', 0) }}" min="0" max="99" class="{{ $inputClasses }}">
                                        </label>
                                    </fieldset>

                                    <div class="space-y-4">
                                        @foreach ($rooms as $room)
                                            <label class="group flex cursor-pointer flex-col overflow-hidden rounded-2xl border border-border/60 bg-card shadow-sm transition-all hover:border-primary/50 has-[:checked]:border-primary has-[:checked]:ring-2 has-[:checked]:ring-primary/30 sm:flex-row">
                                                <img src="{{ $resolveImageUrl($room->image_url, $hotelImageUrl) }}" alt="{{ $room->name }}" loading="lazy"
                                                     class="h-48 w-full object-cover sm:h-auto sm:w-56">
                                                <div class="flex flex-1 flex-col gap-4 p-5 md:flex-row md:items-center md:justify-between">
                                                    <div class="space-y-2">
                                                        <h3 class="text-lg font-bold text-foreground">{{ $room->name }}</h3>
                                                        <p class="text-sm text-muted-foreground">
                                                            {{ $room->max_children > 0
                                                                ? __('hotels.show.capacity', ['adults' => $room->max_adults, 'children' => $room->max_children])
                                                                : __('hotels.show.capacity_adults_only', ['adults' => $room->max_adults]) }}
                                                            @if ($room->bed_type)
                                                                · {{ $room->bed_type }}
                                                            @endif
                                                            @if ($room->size_sqm)
                                                                · {{ __('hotels.show.size', ['size' => $room->size_sqm]) }}
                                                            @endif
                                                        </p>
                                                        @if (filled($room->description))
                                                            <p class="text-sm leading-relaxed text-muted-foreground">{{ $room->description }}</p>
                                                        @endif
                                                        @if (filled($room->amenities))
                                                            <ul class="flex flex-wrap gap-2">
                                                                @foreach ($room->amenities as $roomAmenity)
                                                                    <li class="rounded-full bg-muted px-3 py-1 text-xs font-medium text-foreground">{{ $roomAmenity }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                    <div class="shrink-0 space-y-3 md:text-end">
                                                        <p class="text-2xl font-bold text-primary">
                                                            EGP {{ number_format((float) $room->price_per_night, 0) }}
                                                            <span class="text-xs font-medium text-muted-foreground">{{ __('hotels.show.per_night') }}</span>
                                                        </p>
                                                        <span class="inline-flex items-center gap-2 rounded-xl border border-border px-4 py-2 text-sm font-semibold text-foreground group-has-[:checked]:border-primary group-has-[:checked]:bg-primary group-has-[:checked]:text-primary-foreground">
                                                            <input type="radio" name="room_id" value="{{ $room->id }}" required
                                                                   data-hotel-room data-price="{{ $room->price_per_night }}"
                                                                   @checked($selectedRoomId === $room->id)
                                                                   class="h-4 w-4 accent-primary">
                                                            {{ __('hotels.show.select_room') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                    <div class="flex flex-col gap-4 rounded-2xl border border-border/60 bg-card p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-xs uppercase tracking-wider text-muted-foreground">{{ __('hotels.show.estimated_total') }}</p>
                                            <p class="text-2xl font-bold text-foreground">EGP <span data-hotel-estimate>—</span></p>
                                            <p class="text-xs text-muted-foreground">{{ __('hotels.show.estimate_note') }}</p>
                                        </div>
                                        <button type="submit"
                                                class="btn-premium inline-flex items-center justify-center rounded-xl bg-primary px-10 py-4 text-sm font-bold text-primary-foreground transition-colors hover:bg-primary/90">
                                            {{ __('hotels.show.continue') }}
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>

                        @if ($policyItems->isNotEmpty() || filled($hotel->cancellation_policy))
                            <div>
                                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.3em] text-primary">{{ __('hotels.show.policies_eyebrow') }}</p>
                                <h2 class="mb-6 text-2xl font-bold leading-tight text-foreground md:text-4xl">{{ __('hotels.show.policies_title') }}</h2>
                                <dl class="divide-y divide-border/60 rounded-2xl border border-border/60 bg-card shadow-sm">
                                    @foreach ($policyItems as $policy)
                                        <div class="grid gap-1 px-6 py-4 sm:grid-cols-[200px_1fr] sm:gap-6">
                                            <dt class="text-sm font-semibold text-foreground">{{ data_get($policy, 'title') }}</dt>
                                            <dd class="text-sm leading-relaxed text-muted-foreground">{{ data_get($policy, 'description') }}</dd>
                                        </div>
                                    @endforeach
                                    @if (filled($hotel->cancellation_policy))
                                        <div class="grid gap-1 px-6 py-4 sm:grid-cols-[200px_1fr] sm:gap-6">
                                            <dt class="text-sm font-semibold text-foreground">{{ __('hotels.show.cancellation_policy') }}</dt>
                                            <dd class="whitespace-pre-line text-sm leading-relaxed text-muted-foreground">{{ $hotel->cancellation_policy }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        @endif
                    </div>

                    <aside class="self-start lg:sticky lg:top-32">
                        <div class="space-y-5 rounded-3xl border border-border/60 bg-card p-7 shadow-2xl md:p-8">
                            <div>
                                <p class="mb-1 text-xs uppercase tracking-wider text-muted-foreground">{{ __('hotels.show.starting_from') }}</p>
                                <p class="text-3xl font-bold text-primary md:text-4xl">
                                    @if ($startingPrice)
                                        EGP {{ number_format((float) $startingPrice, 0) }}
                                        <span class="text-sm font-medium text-muted-foreground">{{ __('hotels.show.per_night') }}</span>
                                    @else
                                        {{ __('hotels.card.on_request') }}
                                    @endif
                                </p>
                            </div>
                            @if ($rooms->isNotEmpty())
                                <a href="#availability"
                                   class="btn-premium inline-flex w-full items-center justify-center rounded-xl bg-primary px-4 py-4 text-base font-bold text-primary-foreground transition-colors hover:bg-primary/90">
                                    {{ __('hotels.show.availability_title') }}
                                </a>
                            @endif
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-border bg-background px-4 py-3.5 text-sm font-semibold transition-all hover:border-primary hover:bg-primary hover:text-primary-foreground">
                                {{ __('hotels.show.whatsapp') }}
                            </a>
                            <div class="space-y-2.5 border-t border-border/60 pt-4 text-xs text-muted-foreground">
                                <p class="flex items-center gap-2">
                                    {!! $renderIcon('heroicon-o-shield-check', 'h-4 w-4 shrink-0 text-primary') !!}
                                    <span>{{ __('packages.show.secure_booking') }}</span>
                                </p>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        @if ($faqItems->isNotEmpty())
            <section class="section-padding bg-background">
                <div class="mx-auto max-w-3xl">
                    <div class="mb-12 text-center">
                        <p class="mb-4 text-xs font-semibold uppercase tracking-[0.3em] text-primary">{{ __('hotels.show.faq_eyebrow') }}</p>
                        <h2 class="text-3xl font-bold text-foreground md:text-5xl">{{ __('hotels.show.faq_title') }}</h2>
                    </div>
                    @include('sections.faq', ['faqs' => $faqItems])
                </div>
            </section>
        @endif

        @if ($hotel->testimonials->isNotEmpty())
            @include('sections.testimonials.index', ['style' => 'style-2', 'testimonials' => $hotel->testimonials])
        @endif
    </div>
@endsection
