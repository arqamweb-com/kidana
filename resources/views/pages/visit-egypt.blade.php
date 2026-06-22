@extends('layout.master')
@section('title', __('visit_egypt.title'))
@section('meta_description', __('visit_egypt.meta_description'))
@section('content')

    <div class="min-h-screen">
        <section data-hero-section class="relative min-h-screen flex items-center justify-center overflow-hidden">
            <div data-hero-bg
                 class="absolute inset-0 bg-cover bg-center scale-105 transition-opacity duration-[1800ms] ease-in-out"
                 style="background-image: url(&quot;{{ asset('kidana-home-assets/egypt-pyramids.jpg') }}&quot;); opacity: 0;"></div>
            <div data-hero-bg
                 class="absolute inset-0 bg-cover bg-center scale-105 transition-opacity duration-[1800ms] ease-in-out"
                 style="background-image: url(&quot;{{ asset('kidana-home-assets/nile-cruise.jpg') }}&quot;); opacity: 0;"></div>
            <div data-hero-bg
                 class="absolute inset-0 bg-cover bg-center scale-105 transition-opacity duration-[1800ms] ease-in-out"
                 style="background-image: url(&quot;{{ asset('kidana-home-assets/egypt-cairo.jpg') }}&quot;); opacity: 0;"></div>
            <div data-hero-bg
                 class="absolute inset-0 bg-cover bg-center scale-105 transition-opacity duration-[1800ms] ease-in-out"
                 style="background-image: url(&quot;{{ asset('kidana-home-assets/cairo-pyramids.jpg') }}&quot;); opacity: 1;"></div>
            <div
                class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/85 via-[hsl(var(--hero-overlay))]/55 to-[hsl(var(--hero-overlay))]/95"></div>
            <div
                class="relative z-10 container mx-auto px-4 md:px-8 text-center text-primary-foreground py-32 max-w-4xl">
                <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs md:text-sm mb-5 animate-fade-in">
                    {{ __('visit_egypt.hero.eyebrow') }}</p>
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold leading-[1.05] mb-6 animate-fade-in-up">
                    {{ __('visit_egypt.hero.title_before') }} <span class="text-primary">{{ __('visit_egypt.hero.title_highlight') }}</span> {{ __('visit_egypt.hero.title_after') }}</h1>
                <p class="text-base md:text-xl text-primary-foreground/80 max-w-2xl mx-auto leading-relaxed mb-6 animate-fade-in-up"
                   style="animation-delay: 0.15s;">{{ __('visit_egypt.hero.intro') }}</p>
                <p class="text-sm md:text-base text-primary-foreground/70 max-w-2xl mx-auto leading-relaxed mb-10 animate-fade-in-up"
                   style="animation-delay: 0.25s;">{{ __('visit_egypt.hero.description') }}</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up"
                     style="animation-delay: 0.35s;">
                    <a href="{{ route('packages') }}"
                       class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-10 py-7 text-sm font-semibold gap-2">
                        {{ __('visit_egypt.hero.explore') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right w-4 h-4">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('contact') }}">
                        <button
                            class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border h-10 bg-primary-foreground/10 backdrop-blur border-primary-foreground/30 text-primary-foreground hover:bg-primary-foreground hover:text-secondary rounded-xl px-10 py-7 text-sm font-semibold gap-2">
                            {{ __('visit_egypt.hero.customize') }}
                        </button>
                    </a>
                </div>
                <div class="flex justify-center gap-3 mt-12">
                    <button data-hero-indicator aria-label="{{ __('visit_egypt.hero.slide', ['number' => 1]) }}"
                            class="h-1.5 rounded-full transition-all duration-500 w-5 bg-primary-foreground/40 hover:bg-primary-foreground/70"></button>
                    <button data-hero-indicator aria-label="{{ __('visit_egypt.hero.slide', ['number' => 2]) }}"
                            class="h-1.5 rounded-full transition-all duration-500 w-5 bg-primary-foreground/40 hover:bg-primary-foreground/70"></button>
                    <button data-hero-indicator aria-label="{{ __('visit_egypt.hero.slide', ['number' => 3]) }}"
                            class="h-1.5 rounded-full transition-all duration-500 w-5 bg-primary-foreground/40 hover:bg-primary-foreground/70"></button>
                    <button data-hero-indicator aria-label="{{ __('visit_egypt.hero.slide', ['number' => 4]) }}"
                            class="h-1.5 rounded-full transition-all duration-500 w-10 bg-primary"></button>
                </div>
                <x-breadcrumbs
                    variant="overlay"
                    align="center"
                    class="mt-10"
                    :items="[
                        ['label' => __('nav.visit_egypt')],
                    ]"
                />
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('visit_egypt.why.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('visit_egypt.why.title') }}</h2></div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-5 group-hover:bg-primary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-landmark w-6 h-6 text-primary group-hover:text-primary-foreground transition-colors">
                                <line x1="3" x2="21" y1="22" y2="22"></line>
                                <line x1="6" x2="6" y1="18" y2="11"></line>
                                <line x1="10" x2="10" y1="18" y2="11"></line>
                                <line x1="14" x2="14" y1="18" y2="11"></line>
                                <line x1="18" x2="18" y1="18" y2="11"></line>
                                <polygon points="12 2 20 7 4 7"></polygon>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('visit_egypt.why.items.0.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('visit_egypt.why.items.0.description') }}</p></div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.1s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-5 group-hover:bg-primary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-waves w-6 h-6 text-primary group-hover:text-primary-foreground transition-colors">
                                <path
                                    d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"></path>
                                <path
                                    d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"></path>
                                <path
                                    d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('visit_egypt.why.items.1.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('visit_egypt.why.items.1.description') }}</p></div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.2s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-5 group-hover:bg-primary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-sparkles w-6 h-6 text-primary group-hover:text-primary-foreground transition-colors">
                                <path
                                    d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path>
                                <path d="M20 3v4"></path>
                                <path d="M22 5h-4"></path>
                                <path d="M4 17v2"></path>
                                <path d="M5 18H3"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('visit_egypt.why.items.2.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('visit_egypt.why.items.2.description') }}</p></div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.3s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-5 group-hover:bg-primary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-compass w-6 h-6 text-primary group-hover:text-primary-foreground transition-colors">
                                <path
                                    d="m16.24 7.76-1.804 5.411a2 2 0 0 1-1.265 1.265L7.76 16.24l1.804-5.411a2 2 0 0 1 1.265-1.265z"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('visit_egypt.why.items.3.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('visit_egypt.why.items.3.description') }}</p></div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-muted/40">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('visit_egypt.destinations.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('visit_egypt.destinations.title') }}</h2></div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        class="group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer aspect-[4/5] animate-fade-in-up"
                        style="animation-delay: 0s;"><img src="{{ asset('kidana-home-assets/cairo-pyramids.jpg') }}"
                                                          alt="{{ __('visit_egypt.destinations.items.0.name') }}"
                                                          loading="lazy"
                                                          class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/95 via-[hsl(var(--hero-overlay))]/30 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-7 text-primary-foreground"><h3
                                class="text-2xl font-bold mb-2">{{ __('visit_egypt.destinations.items.0.name') }}</h3>
                            <p class="text-sm text-primary-foreground/80 leading-relaxed">{{ __('visit_egypt.destinations.items.0.description') }}</p></div>
                    </div>
                    <div
                        class="group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer aspect-[4/5] animate-fade-in-up"
                        style="animation-delay: 0.1s;"><img src="{{ asset('kidana-home-assets/nile-cruise.jpg') }}"
                                                            alt="{{ __('visit_egypt.destinations.items.1.name') }}"
                                                            loading="lazy"
                                                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/95 via-[hsl(var(--hero-overlay))]/30 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-7 text-primary-foreground"><h3
                                class="text-2xl font-bold mb-2">{{ __('visit_egypt.destinations.items.1.name') }}</h3>
                            <p class="text-sm text-primary-foreground/80 leading-relaxed">{{ __('visit_egypt.destinations.items.1.description') }}</p></div>
                    </div>
                    <div
                        class="group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer aspect-[4/5] animate-fade-in-up"
                        style="animation-delay: 0.2s;"><img src="{{ asset('kidana-home-assets/nile-cruise.jpg') }}"
                                                            alt="{{ __('visit_egypt.destinations.items.2.name') }}"
                                                            loading="lazy"
                                                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/95 via-[hsl(var(--hero-overlay))]/30 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-7 text-primary-foreground"><h3
                                class="text-2xl font-bold mb-2">{{ __('visit_egypt.destinations.items.2.name') }}</h3>
                            <p class="text-sm text-primary-foreground/80 leading-relaxed">{{ __('visit_egypt.destinations.items.2.description') }}</p></div>
                    </div>
                    <div
                        class="group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer aspect-[4/5] animate-fade-in-up"
                        style="animation-delay: 0.3s;"><img src="{{ asset('kidana-home-assets/maldives.jpg') }}"
                                                            alt="{{ __('visit_egypt.destinations.items.3.name') }}" loading="lazy"
                                                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/95 via-[hsl(var(--hero-overlay))]/30 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-7 text-primary-foreground"><h3
                                class="text-2xl font-bold mb-2">{{ __('visit_egypt.destinations.items.3.name') }}</h3>
                            <p class="text-sm text-primary-foreground/80 leading-relaxed">{{ __('visit_egypt.destinations.items.3.description') }}</p></div>
                    </div>
                    <div
                        class="group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer aspect-[4/5] animate-fade-in-up"
                        style="animation-delay: 0.4s;"><img src="{{ asset('kidana-home-assets/egypt-cairo.jpg') }}"
                                                            alt="{{ __('visit_egypt.destinations.items.4.name') }}"
                                                            loading="lazy"
                                                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/95 via-[hsl(var(--hero-overlay))]/30 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-7 text-primary-foreground"><h3
                                class="text-2xl font-bold mb-2">{{ __('visit_egypt.destinations.items.4.name') }}</h3>
                            <p class="text-sm text-primary-foreground/80 leading-relaxed">{{ __('visit_egypt.destinations.items.4.description') }}</p></div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-5xl">
                <div class="text-center mb-12"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('visit_egypt.video.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('visit_egypt.video.title') }}</h2></div>
                <button type="button" data-video-trigger data-video-id="BapSQFJPMM0"
                        class="relative w-full overflow-hidden rounded-3xl shadow-2xl group cursor-pointer"
                        aria-label="{{ __('visit_egypt.video.play') }}"><img
                        src="{{ asset('kidana-home-assets/egypt-pyramids.jpg') }}"
                        alt="{{ __('visit_egypt.video.image_alt') }}"
                        class="w-full aspect-video object-cover transition-transform duration-700 group-hover:scale-105">
                    <div
                        class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/40 to-[hsl(var(--hero-overlay))]/70 group-hover:from-[hsl(var(--hero-overlay))]/30 transition-all"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div
                            class="w-24 h-24 rounded-full bg-primary text-primary-foreground flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-circle-play w-12 h-12">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polygon points="10 8 16 12 10 16 10 8"></polygon>
                            </svg>
                        </div>
                    </div>
                    <div class="absolute bottom-6 left-6 text-primary-foreground"><p
                            class="text-xs uppercase tracking-[0.25em] opacity-80">{{ __('visit_egypt.video.watch') }}</p>
                        <p class="text-lg font-bold">{{ __('visit_egypt.video.caption') }}</p></div>
                </button>
            </div>
        </section>
        <div data-video-modal
             class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
            <div class="relative w-full max-w-4xl">
                <button type="button" data-video-close aria-label="{{ __('visit_egypt.video.close') }}"
                        class="absolute -top-12 right-0 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="h-6 w-6">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
                <div class="relative aspect-video w-full overflow-hidden rounded-2xl bg-black shadow-2xl">
                    <iframe data-video-frame class="absolute inset-0 h-full w-full" src="" title="{{ __('visit_egypt.video.iframe_title') }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <section id="egypt-packages" class="section-padding bg-muted/40">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('visit_egypt.packages.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('visit_egypt.packages.title') }}</h2></div>
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
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($egyptPackages as $package)
                        @php
                            $packageImageUrl = $resolveImageUrl(
                                $package->image_url,
                                asset('kidana-home-assets/egypt-pyramids.jpg'),
                            );
                        @endphp
                        <a class="group flex flex-col bg-card rounded-2xl overflow-hidden border border-border/50 transition-all duration-300 hover:-translate-y-2 hover:scale-[1.01]"
                           href="{{ route('packages.show', ['package' => $package->slug]) }}"
                           style="box-shadow: 0 4px 24px -6px hsl(var(--foreground) / 0.1), 0 12px 48px -12px hsl(var(--foreground) / 0.08);">
                            <div class="relative h-60 overflow-hidden">
                                <img src="{{ $packageImageUrl }}" alt="{{ $package->name }}" loading="lazy"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div
                                    class="absolute top-4 right-4 bg-primary text-primary-foreground text-sm font-bold px-5 py-2.5 rounded-xl shadow-lg">
                                    @if ($package->price > 0)
                                        {{ __('visit_egypt.packages.price_from', ['price' => number_format((float) $package->price, 0)]) }}
                                    @else
                                        {{ __('packages.show.on_request') }}
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-col flex-1 p-7">
                                <h3 class="text-lg font-bold text-foreground mb-3">{{ $package->name }}</h3>
                                <div class="flex items-center gap-4 text-muted-foreground text-sm mb-4">
                                    @if (filled($package->location_label) || $package->destination?->name)
                                        <span class="flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="lucide lucide-map-pin w-4 h-4 text-secondary"><path
                                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle
                                                    cx="12" cy="10" r="3"></circle></svg>
                                            {{ $package->location_label ?: $package->destination?->name }}
                                        </span>
                                    @endif
                                    @if (filled($package->days))
                                        <span class="flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="lucide lucide-clock w-4 h-4 text-secondary"><circle cx="12"
                                                                                                            cy="12"
                                                                                                            r="10"></circle><polyline
                                                    points="12 6 12 12 16 14"></polyline></svg>
                                            {{ __('packages.card.days', ['count' => $package->days]) }}
                                        </span>
                                    @endif
                                </div>
                                @if ($package->description)
                                    <p class="text-muted-foreground text-sm leading-relaxed mb-5 flex-1 line-clamp-3">{{ $package->description }}</p>
                                @endif
                                <span
                                    class="inline-flex items-center gap-2 text-sm font-semibold text-primary group-hover:gap-3 transition-all">
                                    {{ __('packages.card.view_details') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4"><path
                                            d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                    <a class="group flex flex-col items-center justify-center text-center bg-secondary text-secondary-foreground rounded-2xl p-10 border border-border/50 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300"
                       href="{{ route('contact') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-sparkles w-12 h-12 text-primary mb-5">
                            <path
                                d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path>
                            <path d="M20 3v4"></path>
                            <path d="M22 5h-4"></path>
                            <path d="M4 17v2"></path>
                            <path d="M5 18H3"></path>
                        </svg>
                        <h3 class="text-xl font-bold mb-3">{{ __('visit_egypt.packages.custom_title') }}</h3>
                        <p class="text-sm text-secondary-foreground/85 leading-relaxed mb-6">{{ __('visit_egypt.packages.custom_description') }}
                        </p>
                        <span
                            class="inline-flex items-center gap-2 text-sm font-semibold text-primary group-hover:gap-3 transition-all">{{ __('visit_egypt.packages.custom_cta') }} <svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4"><path
                                    d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                        </span>
                    </a>
                </div>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-4xl">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('visit_egypt.journey.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('visit_egypt.journey.title') }}</h2></div>
                <div class="relative">
                    <div class="absolute left-6 md:left-1/2 md:-translate-x-1/2 top-0 bottom-0 w-[2px] bg-border"></div>
                    <div class="space-y-10">
                        <div class="relative md:grid md:grid-cols-2 md:gap-12 items-start animate-fade-in-up"
                             style="animation-delay: 0s;">
                            <div class="md:text-right  pl-16 md:pl-0">
                                <div
                                    class="bg-card rounded-2xl p-6 border border-border/60 shadow-sm hover:shadow-lg transition-shadow">
                                    <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-2">{{ __('visit_egypt.journey.items.0.day') }}</p>
                                    <h3 class="text-xl font-bold text-foreground mb-2">{{ __('visit_egypt.journey.items.0.title') }}</h3>
                                    <p class="text-sm text-muted-foreground leading-relaxed">{{ __('visit_egypt.journey.items.0.description') }}</p></div>
                            </div>
                            <div class="hidden md:block "></div>
                            <span
                                class="absolute left-4 md:left-1/2 md:-translate-x-1/2 top-6 w-5 h-5 rounded-full bg-primary border-4 border-background shadow-md"></span>
                        </div>
                        <div class="relative md:grid md:grid-cols-2 md:gap-12 items-start animate-fade-in-up"
                             style="animation-delay: 0.15s;">
                            <div class="md:text-right md:order-2 md:text-left pl-16 md:pl-0">
                                <div
                                    class="bg-card rounded-2xl p-6 border border-border/60 shadow-sm hover:shadow-lg transition-shadow">
                                    <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-2">{{ __('visit_egypt.journey.items.1.day') }}</p>
                                    <h3 class="text-xl font-bold text-foreground mb-2">{{ __('visit_egypt.journey.items.1.title') }}</h3>
                                    <p class="text-sm text-muted-foreground leading-relaxed">{{ __('visit_egypt.journey.items.1.description') }}</p></div>
                            </div>
                            <div class="hidden md:block md:order-1"></div>
                            <span
                                class="absolute left-4 md:left-1/2 md:-translate-x-1/2 top-6 w-5 h-5 rounded-full bg-primary border-4 border-background shadow-md"></span>
                        </div>
                        <div class="relative md:grid md:grid-cols-2 md:gap-12 items-start animate-fade-in-up"
                             style="animation-delay: 0.3s;">
                            <div class="md:text-right  pl-16 md:pl-0">
                                <div
                                    class="bg-card rounded-2xl p-6 border border-border/60 shadow-sm hover:shadow-lg transition-shadow">
                                    <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-2">{{ __('visit_egypt.journey.items.2.day') }}</p>
                                    <h3 class="text-xl font-bold text-foreground mb-2">{{ __('visit_egypt.journey.items.2.title') }}</h3>
                                    <p class="text-sm text-muted-foreground leading-relaxed">{{ __('visit_egypt.journey.items.2.description') }}</p></div>
                            </div>
                            <div class="hidden md:block "></div>
                            <span
                                class="absolute left-4 md:left-1/2 md:-translate-x-1/2 top-6 w-5 h-5 rounded-full bg-primary border-4 border-background shadow-md"></span>
                        </div>
                        <div class="relative md:grid md:grid-cols-2 md:gap-12 items-start animate-fade-in-up"
                             style="animation-delay: 0.45s;">
                            <div class="md:text-right md:order-2 md:text-left pl-16 md:pl-0">
                                <div
                                    class="bg-card rounded-2xl p-6 border border-border/60 shadow-sm hover:shadow-lg transition-shadow">
                                    <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-2">{{ __('visit_egypt.journey.items.3.day') }}</p>
                                    <h3 class="text-xl font-bold text-foreground mb-2">{{ __('visit_egypt.journey.items.3.title') }}</h3>
                                    <p class="text-sm text-muted-foreground leading-relaxed">{{ __('visit_egypt.journey.items.3.description') }}</p></div>
                            </div>
                            <div class="hidden md:block md:order-1"></div>
                            <span
                                class="absolute left-4 md:left-1/2 md:-translate-x-1/2 top-6 w-5 h-5 rounded-full bg-primary border-4 border-background shadow-md"></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-muted/40">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('visit_egypt.gallery.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('visit_egypt.gallery.title') }}</h2></div>
                <div class="space-y-4 md:space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <button class="overflow-hidden rounded-2xl group cursor-zoom-in" aria-label="{{ __('visit_egypt.gallery.open_image', ['number' => 1]) }}"><img
                                src="{{ asset('kidana-home-assets/egypt-pyramids.jpg') }}" alt="{{ __('visit_egypt.gallery.image_alt', ['number' => 1]) }}" loading="lazy"
                                class="w-full h-full object-cover aspect-[16/10] group-hover:scale-110 transition-transform duration-700">
                        </button>
                        <button class="overflow-hidden rounded-2xl group cursor-zoom-in" aria-label="{{ __('visit_egypt.gallery.open_image', ['number' => 2]) }}"><img
                                src="{{ asset('kidana-home-assets/nile-cruise.jpg') }}" alt="{{ __('visit_egypt.gallery.image_alt', ['number' => 2]) }}" loading="lazy"
                                class="w-full h-full object-cover aspect-[16/10] group-hover:scale-110 transition-transform duration-700">
                        </button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                        <button class="overflow-hidden rounded-2xl group cursor-zoom-in" aria-label="{{ __('visit_egypt.gallery.open_image', ['number' => 3]) }}"><img
                                src="{{ asset('kidana-home-assets/egypt-cairo.jpg') }}" alt="{{ __('visit_egypt.gallery.image_alt', ['number' => 3]) }}" loading="lazy"
                                class="w-full h-full object-cover aspect-[4/3] group-hover:scale-110 transition-transform duration-700">
                        </button>
                        <button class="overflow-hidden rounded-2xl group cursor-zoom-in" aria-label="{{ __('visit_egypt.gallery.open_image', ['number' => 4]) }}"><img
                                src="{{ asset('kidana-home-assets/cairo-pyramids.jpg') }}" alt="{{ __('visit_egypt.gallery.image_alt', ['number' => 4]) }}" loading="lazy"
                                class="w-full h-full object-cover aspect-[4/3] group-hover:scale-110 transition-transform duration-700">
                        </button>
                        <button class="overflow-hidden rounded-2xl group cursor-zoom-in" aria-label="{{ __('visit_egypt.gallery.open_image', ['number' => 5]) }}"><img
                                src="{{ asset('kidana-home-assets/maldives.jpg') }}" alt="{{ __('visit_egypt.gallery.image_alt', ['number' => 5]) }}" loading="lazy"
                                class="w-full h-full object-cover aspect-[4/3] group-hover:scale-110 transition-transform duration-700">
                        </button>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-5xl">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('visit_egypt.included.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('visit_egypt.included.title') }}</h2></div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-5">
                    <div
                        class="text-center bg-card rounded-2xl p-6 border border-border/50 hover:border-primary/40 hover:shadow-md transition-all animate-fade-in-up"
                        style="animation-delay: 0s;">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-bed-double w-6 h-6 text-primary">
                                <path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"></path>
                                <path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"></path>
                                <path d="M12 4v6"></path>
                                <path d="M2 18h20"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-foreground leading-snug">{{ __('visit_egypt.included.items.0') }}</h3></div>
                    <div
                        class="text-center bg-card rounded-2xl p-6 border border-border/50 hover:border-primary/40 hover:shadow-md transition-all animate-fade-in-up"
                        style="animation-delay: 0.08s;">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-compass w-6 h-6 text-primary">
                                <path
                                    d="m16.24 7.76-1.804 5.411a2 2 0 0 1-1.265 1.265L7.76 16.24l1.804-5.411a2 2 0 0 1 1.265-1.265z"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-foreground leading-snug">{{ __('visit_egypt.included.items.1') }}</h3></div>
                    <div
                        class="text-center bg-card rounded-2xl p-6 border border-border/50 hover:border-primary/40 hover:shadow-md transition-all animate-fade-in-up"
                        style="animation-delay: 0.16s;">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-user-check w-6 h-6 text-primary">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <polyline points="16 11 18 13 22 9"></polyline>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-foreground leading-snug">{{ __('visit_egypt.included.items.2') }}</h3></div>
                    <div
                        class="text-center bg-card rounded-2xl p-6 border border-border/50 hover:border-primary/40 hover:shadow-md transition-all animate-fade-in-up"
                        style="animation-delay: 0.24s;">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-plane w-6 h-6 text-primary">
                                <path
                                    d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-foreground leading-snug">{{ __('visit_egypt.included.items.3') }}</h3>
                    </div>
                    <div
                        class="text-center bg-card rounded-2xl p-6 border border-border/50 hover:border-primary/40 hover:shadow-md transition-all animate-fade-in-up"
                        style="animation-delay: 0.32s;">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-map w-6 h-6 text-primary">
                                <path
                                    d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"></path>
                                <path d="M15 5.764v15"></path>
                                <path d="M9 3.236v15"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-foreground leading-snug">{{ __('visit_egypt.included.items.4') }}</h3></div>
                </div>
            </div>
        </section>
        <section class="relative py-28 overflow-hidden"
                 style="background: linear-gradient(135deg, hsl(var(--primary)) 0%, hsl(var(--secondary)) 100%);">
            <div
                class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_30%_20%,white,transparent_50%)]"></div>
            <div class="relative z-10 container mx-auto max-w-3xl text-center text-primary-foreground px-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-sparkles w-12 h-12 mx-auto mb-6 opacity-90">
                    <path
                        d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path>
                    <path d="M20 3v4"></path>
                    <path d="M22 5h-4"></path>
                    <path d="M4 17v2"></path>
                    <path d="M5 18H3"></path>
                </svg>
                <h2 class="text-3xl md:text-5xl font-bold mb-6 leading-tight">{{ __('visit_egypt.custom.title') }}</h2>
                <p class="text-primary-foreground/90 text-base md:text-lg mb-10 leading-relaxed">{{ __('visit_egypt.custom.description') }}</p><a
                    href="{{ route('contact') }}">
                    <button
                        class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 btn-premium bg-primary-foreground text-secondary hover:bg-primary-foreground/90 rounded-xl px-12 py-7 text-base font-bold gap-2">
                        {{ __('visit_egypt.custom.cta') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right w-5 h-5">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </button>
                </a></div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-14"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('visit_egypt.trust.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('visit_egypt.trust.title') }}</h2></div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        class="text-center bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-lg transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0s;">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-shield-check w-6 h-6 text-primary">
                                <path
                                    d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-foreground">{{ __('visit_egypt.trust.items.0') }}</h3></div>
                    <div
                        class="text-center bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-lg transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.08s;">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-award w-6 h-6 text-primary">
                                <path
                                    d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"></path>
                                <circle cx="12" cy="8" r="6"></circle>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-foreground">{{ __('visit_egypt.trust.items.1') }}</h3></div>
                    <div
                        class="text-center bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-lg transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.16s;">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-globe w-6 h-6 text-primary">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                                <path d="M2 12h20"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-foreground">{{ __('visit_egypt.trust.items.2') }}</h3></div>
                    <div
                        class="text-center bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-lg transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.24s;">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-users w-6 h-6 text-primary">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-foreground">{{ __('visit_egypt.trust.items.3') }}</h3></div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-muted/40">
            <div class="container mx-auto max-w-3xl">
                <div class="text-center mb-12"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('visit_egypt.faq.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('visit_egypt.faq.title') }}</h2></div>

                @include('sections.faq', ['faqs' => $egyptFaqs])
            </div>
        </section>
        <section class="relative py-28 overflow-hidden"><img src="{{ asset('kidana-home-assets/egypt-pyramids.jpg') }}"
                                                             alt=""
                                                             class="absolute inset-0 w-full h-full object-cover">
            <div
                class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/90 to-[hsl(var(--secondary))]/90"></div>
            <div class="relative z-10 container mx-auto max-w-3xl text-center text-primary-foreground px-4"><h2
                    class="text-3xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">{{ __('visit_egypt.final_cta.title') }}</h2>
                <p class="text-primary-foreground/85 text-base md:text-lg mb-10">{{ __('visit_egypt.final_cta.description') }}</p>
                <div class="flex flex-col sm:flex-row gap-5 justify-center"><a href="{{ route('book-now') }}">
                        <button
                            class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-12 py-7 text-base font-bold gap-2">
                            {{ __('visit_egypt.final_cta.book_now') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-arrow-right w-5 h-5">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </button>
                    </a><a href="{{ route('contact') }}">
                        <button
                            class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border h-10 bg-primary-foreground border-primary-foreground/20 text-secondary hover:bg-primary hover:text-primary-foreground hover:border-primary rounded-xl px-12 py-7 text-base font-bold gap-2 transition-all">
                            {{ __('visit_egypt.final_cta.contact') }}
                        </button>
                    </a></div>
            </div>
        </section>
        <a href="https://wa.me/201033455433" target="_blank" rel="noopener noreferrer" aria-label="{{ __('visit_egypt.floating_whatsapp') }}"
           class="fixed bottom-6 right-6 z-40 w-14 h-14 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-2xl hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-message-circle w-7 h-7">
                <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
            </svg>
        </a></div>

@endsection
