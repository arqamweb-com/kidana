@extends('layout.master')

@section('title', $package->name)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags((string) $package->description), 155))
@section('canonical', route('packages.show', ['package' => $package->slug]))
@section('seo_image', filled($package->image_url) && \Illuminate\Support\Str::startsWith($package->image_url, ['http://', 'https://']) ? $package->image_url : (filled($package->image_url) ? \Illuminate\Support\Facades\Storage::url($package->image_url) : asset('favicon.ico')))

@section('content')
    @php
        $features = collect($package->features ?? [])->filter(fn($feature) => filled($feature));
        $galleryImages = collect($package->gallery ?? [])
            ->filter(fn ($item): bool => filled(data_get($item, 'image')))
            ->values();
        $itineraryItems = collect($package->itinerary ?? [])
            ->filter(fn ($item): bool => filled(data_get($item, 'title')) || filled(data_get($item, 'description')))
            ->values();
        $includedItems = collect($package->included_items ?? [])
            ->filter(fn ($item): bool => filled(data_get($item, 'title')))
            ->values();
        $excludedItems = collect($package->excluded_items ?? [])
            ->filter(fn ($item): bool => filled(data_get($item, 'title')))
            ->values();
        $highlightItems = collect($package->highlights ?? [])
            ->filter(fn ($item): bool => filled(data_get($item, 'title')))
            ->values();
        $resolveImageUrl = static function (?string $imagePath, string $fallback): string {
            if (blank($imagePath)) {
                return $fallback;
            }

            if (\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://'])) {
                return $imagePath;
            }

            return \Illuminate\Support\Facades\Storage::url($imagePath);
        };
        $packageImageUrl = $resolveImageUrl(
            $package->image_url,
            'https://images.unsplash.com/photo-1503220317375-aaad61436b1b?w=1200&q=80',
        );
        $whatsappText = rawurlencode(__('packages.show.whatsapp_message', ['package' => $package->name]));
        $whatsappUrl = "https://wa.me/201033455433?text={$whatsappText}";
        $orderUrl = $package->order_action === \App\Enum\PackageOrderAction::FawryPayment
            ? route('packages.checkout', ['package' => $package->slug])
            : route('packages.request', ['package' => $package->slug]);
        $orderLabel = $package->order_action === \App\Enum\PackageOrderAction::FawryPayment
            ? __('packages.show.pay_now')
            : __('packages.show.book_now');
        $displayGalleryImages = $galleryImages->isNotEmpty()
            ? $galleryImages
            : collect([['image' => $package->image_url, 'caption' => $package->name]])->filter(fn ($item): bool => filled(data_get($item, 'image')))->values();
        $featuredGalleryImage = $displayGalleryImages->first();
        $thumbnailGalleryImages = $displayGalleryImages->take(4)->values();
        $renderPackageIcon = static function (?string $icon): string {
            $iconName = filled($icon) ? $icon : 'heroicon-o-map';

            try {
                return svg($iconName, 'h-5 w-5 text-primary')->toHtml();
            } catch (\Throwable) {
                return svg('heroicon-o-map', 'h-5 w-5 text-primary')->toHtml();
            }
        };
    @endphp
    <div class="min-h-screen">
        <section class="relative h-[68vh] min-h-[500px] flex items-end overflow-hidden"><img
                src="{{$packageImageUrl}}" alt="{{ $package->name }}"
                class="absolute inset-0 w-full h-full object-cover scale-105">
            <div
                class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/70 via-[hsl(var(--hero-overlay))]/40 to-[hsl(var(--hero-overlay))]/95"></div>
            <div class="relative z-10 container mx-auto px-4 md:px-8 pb-16 text-primary-foreground animate-fade-in-up">
                <x-breadcrumbs
                    variant="overlay"
                    align="center"
                    class="mb-6"
                    :items="[
                    ['label' => __('nav.packages'), 'url' => route('packages')],
                    ['label' => $package->name],
                ]"
                />
                <div class="flex flex-wrap gap-2 mb-5"><span
                        class="inline-flex items-center gap-1 bg-primary text-primary-foreground text-[11px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-flame w-3 h-3"><path
                                d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path></svg> {{ __('packages.show.popular') }}</span><span
                        class="bg-primary-foreground/20 backdrop-blur text-primary-foreground text-[11px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full border border-primary-foreground/20">{{ __('packages.show.customizable') }}</span>
                </div>
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold mb-4 max-w-3xl leading-tight">{{$package->name}}</h1>

                @if (filled($package->description))
                    <p class="text-primary-foreground/85 text-base md:text-lg max-w-2xl mb-7">{{$package->description}}</p>
                @endif

                <div class="flex flex-wrap items-center gap-3 mb-8">


                    @if ($package->days)
                        <span
                            class="inline-flex items-center gap-2 bg-primary-foreground/15 backdrop-blur border border-primary-foreground/20 px-4 py-2 rounded-xl text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-clock w-4 h-4 text-primary"><circle cx="12" cy="12"
                                                                                         r="10"></circle><polyline
                                    points="12 6 12 12 16 14"></polyline></svg> {{ __('packages.show.days', ['count' => $package->days]) }}</span>
                    @endif



                    @if ($package->destination)
                        <span
                            class="inline-flex items-center gap-2 bg-primary-foreground/15 backdrop-blur border border-primary-foreground/20 px-4 py-2 rounded-xl text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-map-pin w-4 h-4 text-primary"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle
                                    cx="12" cy="10" r="3"></circle>
                        </svg> {{$package->destination->name}}</span>
                    @endif


                    @if (filled($package->location_label))
                        <span
                            class="inline-flex items-center gap-2 bg-primary-foreground/15 backdrop-blur border border-primary-foreground/20 px-4 py-2 rounded-xl text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-tag w-4 h-4 text-primary"><path
                                    d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"></path><circle
                                    cx="7.5" cy="7.5" r=".5" fill="currentColor"></circle></svg> {{$package->location_label}}</span>
                    @endif
                </div>
                <a href="{{ $orderUrl }}"
                   class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-10 py-7 text-sm font-semibold gap-2">
                    {{ $orderLabel }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-arrow-right w-4 h-4">
                        <path d="M5 12h14"></path>
                        <path d="m12 5 7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('packages.search') }}"
                   class="ms-3 inline-flex items-center justify-center rounded-xl border border-primary-foreground/20 bg-primary-foreground/10 px-6 py-3 text-sm font-semibold text-primary-foreground backdrop-blur transition-colors hover:bg-primary-foreground/20">
                    {{ __('packages.show.search_packages') }}
                </a>
            </div>
        </section>
        <section class="bg-card border-b border-border/60 sticky top-[68px] z-30 backdrop-blur">
            <div class="container mx-auto px-4 py-5">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-5 items-center">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-clock w-5 h-5 text-primary shrink-0">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-muted-foreground">{{ __('packages.show.duration') }}</p>
                            <p class="text-sm font-bold text-foreground">
                                @if($package->days)
                                    {{ __('packages.show.days', ['count' => $package->days]) }}
                                @else
                                    {{ __('packages.show.flexible') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-map-pin w-5 h-5 text-primary shrink-0">
                            <path
                                d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-muted-foreground">{{ __('packages.show.location') }}</p>
                            <p class="text-sm font-bold text-foreground truncate">{{$package?->destination->name}}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-credit-card w-5 h-5 text-primary shrink-0">
                            <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                            <line x1="2" x2="22" y1="10" y2="10"></line>
                        </svg>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-muted-foreground">{{ __('packages.show.starting_from') }}</p>
                            <p class="text-sm font-bold text-foreground">
                                EGP {{ number_format((float) $package->price, 0) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-calendar-days w-5 h-5 text-primary shrink-0">
                            <path d="M8 2v4"></path>
                            <path d="M16 2v4"></path>
                            <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                            <path d="M3 10h18"></path>
                            <path d="M8 14h.01"></path>
                            <path d="M12 14h.01"></path>
                            <path d="M16 14h.01"></path>
                            <path d="M8 18h.01"></path>
                            <path d="M12 18h.01"></path>
                            <path d="M16 18h.01"></path>
                        </svg>
                        <div><p class="text-[10px] uppercase tracking-wider text-muted-foreground">{{ __('packages.show.availability') }}</p>
                            <p class="text-sm font-bold text-foreground">{{ __('packages.show.flexible_dates') }}</p></div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-6xl">
                @if ($features->isNotEmpty())
                    <div class="mb-14 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($features as $feature)
                            <div class="rounded-2xl border border-border/60 bg-card p-5 shadow-sm">
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M20 6 9 17l-5-5"></path>
                                        </svg>
                                    </span>
                                    <p class="text-sm font-semibold leading-6 text-foreground">{{ $feature }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($featuredGalleryImage)
                    @php
                        $featuredGalleryImageUrl = $resolveImageUrl(data_get($featuredGalleryImage, 'image'), $packageImageUrl);
                        $featuredGalleryImageCaption = data_get($featuredGalleryImage, 'caption');
                    @endphp

                    <div data-package-gallery class="grid gap-4 md:gap-6 lg:grid-cols-[2fr_1fr]">
                        <div class="group relative overflow-hidden rounded-3xl">
                            <img data-package-gallery-featured src="{{ $featuredGalleryImageUrl }}"
                                 alt="{{ $featuredGalleryImageCaption ?: __('packages.show.gallery_image_alt', ['package' => $package->name, 'number' => 1]) }}"
                                 class="aspect-[16/10] h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">

                            <div data-package-gallery-caption-wrapper
                                 class="{{ filled($featuredGalleryImageCaption) ? '' : 'hidden' }} absolute inset-x-0 bottom-0 bg-gradient-to-t from-secondary/80 to-transparent p-6 pt-16">
                                <p data-package-gallery-caption class="text-sm font-semibold text-primary-foreground">
                                    {{ $featuredGalleryImageCaption }}
                                </p>
                            </div>
                        </div>

                        @if ($thumbnailGalleryImages->count() > 1)
                            <div class="grid grid-cols-2 gap-4 md:gap-6 lg:grid-cols-1">
                                @foreach ($thumbnailGalleryImages as $galleryImage)
                                    @php
                                        $galleryImageUrl = $resolveImageUrl(data_get($galleryImage, 'image'), $packageImageUrl);
                                        $galleryImageCaption = data_get($galleryImage, 'caption');
                                        $galleryImageAlt = $galleryImageCaption ?: __('packages.show.gallery_image_alt', ['package' => $package->name, 'number' => $loop->iteration]);
                                    @endphp

                                    <button type="button"
                                            data-package-gallery-thumbnail
                                            data-package-gallery-src="{{ $galleryImageUrl }}"
                                            data-package-gallery-alt="{{ $galleryImageAlt }}"
                                            data-package-gallery-caption="{{ $galleryImageCaption }}"
                                            class="group relative overflow-hidden rounded-2xl transition-all {{ $loop->first ? 'ring-2 ring-primary ring-offset-2 ring-offset-background' : 'ring-0 ring-transparent ring-offset-0' }}"
                                            aria-label="{{ __('packages.show.view_image', ['number' => $loop->iteration]) }}"
                                            aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                                        <img src="{{ $galleryImageUrl }}"
                                             alt="{{ $galleryImageAlt }}"
                                             loading="lazy"
                                             class="aspect-[4/3] h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">

                                        @if (filled($galleryImageCaption))
                                            <div
                                                class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-secondary/75 to-transparent p-4 pt-10">
                                                <p class="text-xs font-semibold text-primary-foreground">
                                                    {{ $galleryImageCaption }}
                                                </p>
                                            </div>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </section>
        <section class="bg-muted/40">
            <div class="container mx-auto max-w-6xl px-4 md:px-8 py-16 md:py-24">
                <div class="grid lg:grid-cols-[1fr_380px] gap-10">
                    <div class="space-y-16">
                        <div class="animate-fade-in-up"><p
                                class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-3">{{ __('packages.show.overview_eyebrow') }}</p>
                            <h2 class="text-2xl md:text-4xl font-bold text-foreground mb-6 leading-tight">{{ __('packages.show.overview_title') }}</h2>
                            <p class="text-muted-foreground text-base md:text-lg leading-relaxed">{{$package->description}}</p>
                        </div>
                        <div class="animate-fade-in-up"><p
                                class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-3">{{ __('packages.show.itinerary_eyebrow') }}</p>
                            <h2 class="text-2xl md:text-4xl font-bold text-foreground mb-6 leading-tight">{{ __('packages.show.itinerary_title') }}</h2>
                            @if ($itineraryItems->isNotEmpty())
                                <div class="space-y-3" data-package-accordion>
                                    @foreach ($itineraryItems as $itineraryItem)
                                        @php
                                            $isOpen = $loop->first;
                                            $triggerId = 'package-itinerary-trigger-' . $loop->iteration;
                                            $panelId = 'package-itinerary-panel-' . $loop->iteration;
                                            $dayLabel = data_get($itineraryItem, 'day_label') ?: __('packages.show.day', ['number' => $loop->iteration]);
                                            $title = data_get($itineraryItem, 'title') ?: $dayLabel;
                                            $description = data_get($itineraryItem, 'description');
                                        @endphp

                                        <div data-package-accordion-item
                                             data-state="{{ $isOpen ? 'open' : 'closed' }}"
                                             class="bg-card rounded-2xl border border-border/60 px-6 shadow-sm">
                                            <h3 class="flex">
                                                <button type="button"
                                                        id="{{ $triggerId }}"
                                                        aria-controls="{{ $panelId }}"
                                                        aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                                                        data-package-accordion-trigger
                                                        data-state="{{ $isOpen ? 'open' : 'closed' }}"
                                                        class="flex flex-1 items-center justify-between gap-4 py-5 text-left font-medium transition-all hover:no-underline">
                                                    <div class="flex items-center gap-4">
                                                        <div
                                                            class="shrink-0 w-12 h-12 rounded-xl bg-primary/10 text-primary font-bold flex items-center justify-center text-sm">
                                                            {!! $renderPackageIcon(data_get($itineraryItem, 'icon')) !!}
                                                        </div>
                                                        <div>
                                                            <p class="text-[10px] uppercase tracking-[0.2em] text-primary font-bold mb-1">
                                                                {{ $dayLabel }}
                                                            </p>
                                                            <p class="text-base md:text-lg font-bold text-foreground">
                                                                {{ $title }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                         class="h-4 w-4 shrink-0 transition-transform duration-200 {{ $isOpen ? 'rotate-180' : '' }}">
                                                        <path d="m6 9 6 6 6-6"></path>
                                                    </svg>
                                                </button>
                                            </h3>
                                            <div id="{{ $panelId }}"
                                                 role="region"
                                                 aria-labelledby="{{ $triggerId }}"
                                                 data-package-accordion-panel
                                                 data-state="{{ $isOpen ? 'open' : 'closed' }}"
                                                 class="{{ $isOpen ? '' : 'hidden' }} border-t border-border/60 pb-6 pt-5 text-sm leading-relaxed text-muted-foreground md:text-base">
                                                {{ $description }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div
                                    class="rounded-3xl border border-dashed border-border/70 bg-card px-8 py-12 text-center">
                                    <h3 class="text-2xl font-bold text-foreground">{{ __('packages.show.itinerary_empty_title') }}</h3>
                                    <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
                                        {{ __('packages.show.itinerary_empty_description') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div class="grid md:grid-cols-2 gap-6 animate-fade-in-up">
                            <div class="bg-card rounded-2xl p-7 border border-border/60 shadow-sm">
                                <h3 class="text-xl font-bold text-foreground mb-5 flex items-center gap-2">
                                    {!! $renderPackageIcon('heroicon-o-check') !!}
                                    {{ __('packages.show.included_title') }}
                                </h3>

                                @if ($includedItems->isNotEmpty())
                                    <ul class="space-y-3">
                                        @foreach ($includedItems as $includedItem)
                                            <li class="flex items-start gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                                    {!! $renderPackageIcon(data_get($includedItem, 'icon')) !!}
                                                </div>
                                                <span class="text-sm text-foreground leading-relaxed pt-1">
                                                    {{ data_get($includedItem, 'title') }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm leading-relaxed text-muted-foreground">
                                        {{ __('packages.show.included_empty') }}
                                    </p>
                                @endif
                            </div>

                            <div class="bg-card rounded-2xl p-7 border border-border/60 shadow-sm">
                                <h3 class="text-xl font-bold text-foreground mb-5 flex items-center gap-2">
                                    {!! $renderPackageIcon('heroicon-o-x-mark') !!}
                                    {{ __('packages.show.excluded_title') }}
                                </h3>

                                @if ($excludedItems->isNotEmpty())
                                    <ul class="space-y-3">
                                        @foreach ($excludedItems as $excludedItem)
                                            <li class="flex items-start gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-muted flex items-center justify-center shrink-0">
                                                    {!! $renderPackageIcon(data_get($excludedItem, 'icon')) !!}
                                                </div>
                                                <span class="text-sm text-muted-foreground leading-relaxed pt-1">
                                                    {{ data_get($excludedItem, 'title') }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm leading-relaxed text-muted-foreground">
                                        {{ __('packages.show.excluded_empty') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div><p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-3">
                                {{ __('packages.show.highlights_eyebrow') }}</p>
                            <h2 class="text-2xl md:text-4xl font-bold text-foreground mb-6 leading-tight">{{ __('packages.show.highlights_title') }}</h2>

                            @if ($highlightItems->isNotEmpty())
                                <div class="grid sm:grid-cols-2 gap-4">
                                    @foreach ($highlightItems as $highlightItem)
                                        <div
                                            class="bg-card rounded-2xl p-5 border border-border/60 shadow-sm flex items-center gap-4 hover:border-primary/40 hover:shadow-md transition-all">
                                            <div
                                                class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                                                {!! $renderPackageIcon(data_get($highlightItem, 'icon')) !!}
                                            </div>
                                            <span class="text-sm font-semibold text-foreground">
                                                {{ data_get($highlightItem, 'title') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div
                                    class="rounded-3xl border border-dashed border-border/70 bg-card px-8 py-12 text-center">
                                    <h3 class="text-2xl font-bold text-foreground">{{ __('packages.show.highlights_empty_title') }}</h3>
                                    <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
                                        {{ __('packages.show.highlights_empty_description') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <aside class="lg:sticky lg:top-32 self-start">
                        <div id="pkg-cta"
                             class="bg-card rounded-3xl border border-border/60 shadow-2xl p-7 md:p-8 space-y-5">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-muted-foreground mb-1">{{ __('packages.show.starting_from') }}</p>
                                <p class="text-3xl md:text-4xl font-bold text-primary">
                                    EGP {{ number_format((float) $package->price, 0) }}</p>
                                <p class="text-xs text-muted-foreground mt-1">{{ __('packages.show.price_note') }}</p>
                            </div>
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 px-4 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl w-full py-7 text-base font-bold gap-2">
                                {{ __('packages.show.book_now') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="lucide lucide-arrow-right w-5 h-5">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </a>
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border bg-background h-10 px-4 rounded-xl w-full py-6 text-sm font-semibold gap-2 border-border hover:bg-primary hover:text-primary-foreground hover:border-primary transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="lucide lucide-message-circle w-4 h-4">
                                        <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                                    </svg>
                                    {{ __('packages.show.whatsapp') }}
                            </a>
                            <div class="pt-4 border-t border-border/60 space-y-2.5 text-xs text-muted-foreground">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="lucide lucide-shield-check w-4 h-4 text-primary shrink-0">
                                        <path
                                            d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                        <path d="m9 12 2 2 4-4"></path>
                                    </svg>
                                    <span>{{ __('packages.show.secure_booking') }}</span></div>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="lucide lucide-sparkles w-4 h-4 text-primary shrink-0">
                                        <path
                                            d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path>
                                        <path d="M20 3v4"></path>
                                        <path d="M22 5h-4"></path>
                                        <path d="M4 17v2"></path>
                                        <path d="M5 18H3"></path>
                                    </svg>
                                    <span>{{ __('packages.show.flexible_cancellation') }}</span></div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-14"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('packages.show.trust_eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('packages.show.trust_title') }}</h2></div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        class="text-center bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-lg transition-all duration-300">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-shield-check w-6 h-6 text-primary">
                                <path
                                    d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-foreground">{{ __('packages.show.trust_items.0') }}</h3></div>
                    <div
                        class="text-center bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-lg transition-all duration-300">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-award w-6 h-6 text-primary">
                                <path
                                    d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"></path>
                                <circle cx="12" cy="8" r="6"></circle>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-foreground">{{ __('packages.show.trust_items.1') }}</h3></div>
                    <div
                        class="text-center bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-lg transition-all duration-300">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-heart w-6 h-6 text-primary">
                                <path
                                    d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-foreground">{{ __('packages.show.trust_items.2') }}</h3></div>
                    <div
                        class="text-center bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-lg transition-all duration-300">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-headphones w-6 h-6 text-primary">
                                <path
                                    d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-foreground">{{ __('packages.show.trust_items.3') }}</h3></div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-muted/40">
            <div class="mx-auto max-w-3xl">
                <div class="text-center mb-12"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('packages.show.faq_eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('packages.show.faq_title') }}</h2></div>
                @include('sections.faq', ['faqs' => $package->faqs])
            </div>
        </section>
        <section class="relative py-28 overflow-hidden"><img src="/assets/kaaba-umrah-BTvk3hmK.jpg" alt=""
                                                             class="absolute inset-0 w-full h-full object-cover">
            <div
                class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/90 to-[hsl(var(--secondary))]/90"></div>
            <div class="relative z-10 container mx-auto max-w-3xl text-center text-primary-foreground px-4"><h2
                    class="text-3xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">{{ __('packages.show.cta_title') }}</h2>
                <p class="text-primary-foreground/85 text-base md:text-lg mb-10">{{ __('packages.show.cta_description') }}</p>
                <div class="flex flex-col sm:flex-row gap-5 justify-center">
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-12 py-7 text-base font-bold gap-2">
                        {{ __('packages.show.book_now') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right w-5 h-5">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border h-10 bg-primary-foreground border-primary-foreground/20 text-secondary hover:bg-primary hover:text-primary-foreground hover:border-primary rounded-xl px-12 py-7 text-base font-bold gap-2 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-phone w-5 h-5">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            {{ __('packages.show.contact_us') }}
                    </a></div>
            </div>
        </section>
        <a href="{{ $whatsappUrl }}"
           target="_blank" rel="noopener noreferrer" aria-label="{{ __('packages.show.chat_whatsapp') }}"
           class="fixed bottom-6 right-6 z-40 w-14 h-14 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-2xl hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-message-circle w-7 h-7">
                <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
            </svg>
        </a>
    </div>
@endsection
