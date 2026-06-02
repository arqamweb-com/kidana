@extends('layout.master')
@section('title', __('home.title'))
@section('meta_description', 'Kidana Travel offers premium Hajj, Umrah, and international travel packages with curated destinations and dedicated support.')
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

        $isRtl = config('locales.supported.' . app()->getLocale() . '.direction') === 'rtl';
        $forwardArrowPath = $isRtl ? 'm15 18-6-6 6-6' : 'm12 5 7 7-7 7';
        $previousChevronPath = $isRtl ? 'm9 18 6-6-6-6' : 'm15 18-6-6 6-6';
        $nextChevronPath = $isRtl ? 'm15 18-6-6 6-6' : 'm9 18 6-6-6-6';
    @endphp

    <section id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <div data-hero-bg
             class="absolute inset-0 bg-cover bg-center bg-no-repeat scale-105 transition-opacity duration-1800 ease-in-out"
             style="background-image: url(https://www.arqamweb.com/wp-content/uploads/2026/04/Slide-1.jpg); opacity: 1;">
        </div>
        <div data-hero-bg
             class="absolute inset-0 bg-cover bg-center bg-no-repeat scale-105 transition-opacity duration-1800 ease-in-out"
             style="background-image: url(https://www.arqamweb.com/wp-content/uploads/2026/04/Slide-2-1.jpg); opacity: 0;">
        </div>
        <div data-hero-bg
             class="absolute inset-0 bg-cover bg-center bg-no-repeat scale-105 transition-opacity duration-1800 ease-in-out"
             style="background-image: url(https://www.arqamweb.com/wp-content/uploads/2026/04/Slide-3-2.jpg); opacity: 0;">
        </div>
        <div data-hero-bg
             class="absolute inset-0 bg-cover bg-center bg-no-repeat scale-105 transition-opacity duration-1800 ease-in-out"
             style="background-image: url(https://www.arqamweb.com/wp-content/uploads/2026/04/Slide-4.jpg); opacity: 0;">
        </div>
        <div
            class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/90 via-[hsl(var(--hero-overlay))]/65 to-[hsl(var(--hero-overlay))]/95">
        </div>
        <div
            class="relative z-10 container mx-auto px-4 md:px-8 flex flex-col items-center justify-center text-center py-32">
            <div class="max-w-4xl mx-auto mb-10 min-h-[260px] md:min-h-[300px]">
                <p
                    class="text-secondary font-semibold tracking-[0.3em] uppercase text-xs md:text-sm mb-5 animate-fade-in">
                    {{ __('home.hero.eyebrow') }}</p>
                <div data-hero-content class="relative translate-y-0 opacity-100 transition-all duration-700">
                    <h1
                        class="text-4xl md:text-6xl lg:text-7xl font-bold text-primary-foreground leading-[1.08] mb-6">
                        {{ __('home.hero.slides.0.before') }} <span
                            class="text-primary">{{ __('home.hero.slides.0.highlight') }}</span> {{ __('home.hero.slides.0.after') }}
                    </h1>
                    <p class="text-base md:text-xl text-primary-foreground/75 max-w-2xl mx-auto leading-relaxed">
                        {{ __('home.hero.slides.0.description') }}</p>
                </div>
                <div data-hero-content
                     class="transition-all duration-700 opacity-0 translate-y-4 absolute inset-x-0 pointer-events-none">
                    <h1
                        class="text-4xl md:text-6xl lg:text-7xl font-bold text-primary-foreground leading-[1.08] mb-6">
                        <span class="text-primary">{{ __('home.hero.slides.1.highlight') }}</span>
                        {{ __('home.hero.slides.1.after') }}
                    </h1>
                    <p class="text-base md:text-xl text-primary-foreground/75 max-w-2xl mx-auto leading-relaxed">
                        {{ __('home.hero.slides.1.description') }}</p>
                </div>
                <div data-hero-content
                     class="transition-all duration-700 opacity-0 translate-y-4 absolute inset-x-0 pointer-events-none">
                    <h1
                        class="text-4xl md:text-6xl lg:text-7xl font-bold text-primary-foreground leading-[1.08] mb-6">
                        {{ __('home.hero.slides.2.before') }} <span
                            class="text-primary">{{ __('home.hero.slides.2.highlight') }}</span> {{ __('home.hero.slides.2.after') }}
                    </h1>
                    <p class="text-base md:text-xl text-primary-foreground/75 max-w-2xl mx-auto leading-relaxed">
                        {{ __('home.hero.slides.2.description') }}</p>
                </div>
                <div data-hero-content
                     class="transition-all duration-700 opacity-0 translate-y-4 absolute inset-x-0 pointer-events-none">
                    <h1
                        class="text-4xl md:text-6xl lg:text-7xl font-bold text-primary-foreground leading-[1.08] mb-6">
                        {{ __('home.hero.slides.3.before') }} <span
                            class="text-primary">{{ __('home.hero.slides.3.highlight') }}</span> {{ __('home.hero.slides.3.after') }}
                    </h1>
                    <p class="text-base md:text-xl text-primary-foreground/75 max-w-2xl mx-auto leading-relaxed">
                        {{ __('home.hero.slides.3.description') }}</p>
                </div>
            </div>
            <form action="<?php echo e(route('packages.search')); ?>" method="GET"
                  class="w-full max-w-4xl rounded-2xl border border-primary-foreground/8 bg-background/60 p-5 shadow-[0_20px_70px_-15px_rgba(0,0,0,0.5)] backdrop-blur-2xl animate-fade-in-up">
                <div class="flex flex-col items-stretch gap-3 md:flex-row">
                    <div
                        class="flex flex-1 items-center gap-3 rounded-xl border border-border/20 bg-background/95 px-5 py-5 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="lucide lucide-search w-5 h-5 text-primary shrink-0">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                        <input name="destination" list="package-destinations"
                               placeholder="{{ __('home.hero.form.destination_placeholder') }}"
                               class="w-full bg-transparent text-sm font-medium text-foreground outline-none placeholder:text-muted-foreground/50">
                        <datalist id="package-destinations">
                            @foreach ($destinations as $destination)
                                <option value="{{ $destination->slug }}">{{ $destination->name }}</option>
                            @endforeach
                        </datalist>
                    </div>
                    <div
                        class="flex flex-1 items-center gap-3 rounded-xl border border-border/20 bg-background/95 px-5 py-5 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="lucide lucide-calendar w-5 h-5 text-primary shrink-0">
                            <path d="M8 2v4"></path>
                            <path d="M16 2v4"></path>
                            <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                            <path d="M3 10h18"></path>
                        </svg>
                        <input type="date" name="travel_date" aria-label="{{ __('home.hero.form.travel_date') }}"
                               class="w-full bg-transparent text-sm font-medium text-foreground outline-none [color-scheme:light] cursor-pointer">
                    </div>
                    <div
                        class="flex items-center gap-3 rounded-xl border border-border/20 bg-background/95 px-5 py-5 shadow-sm md:w-44">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="lucide lucide-users w-5 h-5 text-primary shrink-0">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <input type="number" name="guests" min="1"
                               placeholder="{{ __('home.hero.form.guests_placeholder') }}"
                               class="w-full bg-transparent text-sm font-medium text-foreground outline-none placeholder:text-muted-foreground/50">
                    </div>
                    <button type="submit"
                            class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-12 py-5 text-sm font-bold gap-2 shrink-0 shadow-[0_8px_24px_-4px_hsl(var(--primary)/0.5)] h-auto self-stretch cursor-pointer">
                        {{ __('home.hero.form.search') }}
                        <svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4">
                            <path d="M5 12h14"></path>
                            <path d="{{ $forwardArrowPath }}"></path>
                        </svg>
                    </button>
                </div>
            </form>
            <div class="flex justify-center gap-3 mt-10">
                <button data-hero-indicator aria-label="{{ __('home.hero.indicators.go_to_slide', ['number' => 1]) }}"
                        class="h-1.5 rounded-full transition-all duration-500 w-10 bg-primary cursor-pointer"></button>
                <button
                    data-hero-indicator aria-label="{{ __('home.hero.indicators.go_to_slide', ['number' => 2]) }}"
                    class="h-1.5 rounded-full transition-all duration-500 w-5 bg-primary-foreground/40 hover:bg-primary-foreground/70 cursor-pointer"></button>
                <button
                    data-hero-indicator aria-label="{{ __('home.hero.indicators.go_to_slide', ['number' => 3]) }}"
                    class="h-1.5 rounded-full transition-all duration-500 w-5 bg-primary-foreground/40 hover:bg-primary-foreground/70 cursor-pointer"></button>
                <button
                    data-hero-indicator aria-label="{{ __('home.hero.indicators.go_to_slide', ['number' => 4]) }}"
                    class="h-1.5 rounded-full transition-all duration-500 w-5 bg-primary-foreground/40 hover:bg-primary-foreground/70 cursor-pointer"></button>
            </div>
            <div class="flex justify-center gap-16 md:gap-28 mt-16 animate-fade-in">
                <div class="text-center group">
                    <p
                        class="text-3xl md:text-5xl font-bold text-primary tracking-tight transition-transform duration-300 group-hover:scale-110">
                        10K+</p>
                    <p
                        class="text-[11px] md:text-xs text-primary-foreground/60 mt-3 font-semibold tracking-[0.2em] uppercase">
                        {{ __('home.hero.stats.happy_travelers') }}</p>
                </div>
                <div class="text-center group">
                    <p
                        class="text-3xl md:text-5xl font-bold text-primary tracking-tight transition-transform duration-300 group-hover:scale-110">
                        50+</p>
                    <p
                        class="text-[11px] md:text-xs text-primary-foreground/60 mt-3 font-semibold tracking-[0.2em] uppercase">
                        {{ __('home.hero.stats.destinations') }}</p>
                </div>
                <div class="text-center group">
                    <p
                        class="text-3xl md:text-5xl font-bold text-primary tracking-tight transition-transform duration-300 group-hover:scale-110">
                        15+</p>
                    <p
                        class="text-[11px] md:text-xs text-primary-foreground/60 mt-3 font-semibold tracking-[0.2em] uppercase">
                        {{ __('home.hero.stats.years_experience') }}</p>
                </div>
            </div>
        </div>
    </section>
    <section class="py-20 bg-muted/40 border-t border-border/40">
        <div class="container mx-auto px-4 md:px-8">
            <div class="text-center mb-12 animate-fade-in-up">
                <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('home.trust.eyebrow') }}</p>
                <h2 class="text-2xl md:text-4xl font-bold text-foreground">{{ __('home.trust.title') }}
                </h2>
                <p class="text-muted-foreground mt-4 max-w-xl mx-auto text-sm md:text-base">{{ __('home.trust.description') }}</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                <div
                    class="bg-card rounded-2xl p-7 border border-border/50 text-center hover:border-primary/40 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                    style="animation-delay: 0s;">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-shield-check w-6 h-6 text-primary">
                            <path
                                d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z">
                            </path>
                            <path d="m9 12 2 2 4-4"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-muted-foreground mb-2">{{ __('home.trust.tourism_license') }}</p>
                    <p class="text-lg font-bold text-foreground">XXXXX</p>
                </div>
                <div
                    class="bg-card rounded-2xl p-7 border border-border/50 text-center hover:border-primary/40 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                    style="animation-delay: 0.1s;">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-file-check w-6 h-6 text-primary">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                            <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                            <path d="m9 15 2 2 4-4"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-muted-foreground mb-2">
                        {{ __('home.trust.commercial_registration') }}</p>
                    <p class="text-lg font-bold text-foreground">XXXXX</p>
                </div>
                <div
                    class="bg-card rounded-2xl p-7 border border-border/50 text-center hover:border-primary/40 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                    style="animation-delay: 0.2s;">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-receipt w-6 h-6 text-primary">
                            <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z">
                            </path>
                            <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
                            <path d="M12 17.5v-11"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-muted-foreground mb-2">{{ __('home.trust.tax_id') }}
                    </p>
                    <p class="text-lg font-bold text-foreground">XXXXX</p>
                </div>
            </div>
        </div>
    </section>
    <section id="services" class="section-padding bg-card">
        <div class="container mx-auto">
            <div class="text-center mb-20">
                <p class="text-primary font-semibold tracking-[0.25em] uppercase text-xs mb-4">{{ __('home.services.eyebrow') }}</p>
                <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('home.services.title') }}</h2>
            </div>
            <div class="grid md:grid-cols-2 gap-7">
                @forelse ($services as $service)
                    @php
                        $serviceImageUrl = $resolveImageUrl(
                            $service->image_url ?: $service->hero_image,
                            'https://images.unsplash.com/photo-1503220317375-aaad61436b1b?w=1200&q=80',
                        );
                    @endphp

                    <a href="{{ route('services.show', ['service' => $service->slug]) }}"
                       class="group relative block h-72 cursor-pointer overflow-hidden rounded-2xl premium-shadow transition-all duration-500 hover:premium-shadow-hover animate-fade-in-up md:h-80"
                       style="animation-delay: {{ $loop->index * 0.15 }}s;">
                        <img
                            src="{{ $serviceImageUrl }}"
                            alt="{{ $service->name }}" loading="lazy"
                            class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/95 via-[hsl(var(--hero-overlay))]/40 to-[hsl(var(--hero-overlay))]/10 transition-all duration-500 group-hover:from-[hsl(var(--hero-overlay))]/95 group-hover:via-[hsl(var(--hero-overlay))]/50">
                        </div>
                        <div class="absolute inset-x-0 bottom-0 p-7 md:p-9">
                            <h3 class="mb-2 text-xl font-bold text-primary-foreground md:text-2xl">
                                {{ $service->name }}
                            </h3>
                            <p
                                class="max-w-md translate-y-4 text-sm leading-relaxed text-primary-foreground/70 opacity-0 transition-all duration-500 group-hover:translate-y-0 group-hover:opacity-100">
                                {{ $service->description ?: __('home.services.fallback_description') }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div
                        class="col-span-full rounded-3xl border border-dashed border-border/70 bg-muted/40 px-8 py-14 text-center">
                        <h3 class="text-2xl font-bold text-foreground">{{ __('home.services.empty_title') }}</h3>
                        <p
                            class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
                            {{ __('home.services.empty_description') }}
                        </p>
                    </div>
                @endforelse
            </div>
            <div class="text-center mt-14"><a href="{{ route('services.index') }}"
                                              class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border bg-background h-10 rounded-xl px-12 py-7 text-sm font-semibold gap-2 border-primary text-primary hover:bg-primary hover:text-primary-foreground transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-1 hover:scale-[1.02]">{{ __('home.common.view_all_services') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4">
                        <path d="M5 12h14"></path>
                        <path d="{{ $forwardArrowPath }}"></path>
                    </svg>
                </a></div>
        </div>
    </section>
    <section id="packages" class="section-padding bg-muted/50">
        <div class="container mx-auto">
            <div class="text-center mb-20">
                <p class="text-primary font-semibold tracking-[0.25em] uppercase text-xs mb-4">{{ __('home.packages.eyebrow') }}</p>
                <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('home.packages.title') }}</h2>
            </div>

            @include('sections.packages-grid')

            <div class="text-center mt-14">
                <button
                    class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border bg-background h-10 rounded-xl px-12 py-7 text-sm font-semibold gap-2 border-primary text-primary hover:bg-primary hover:text-primary-foreground transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-1 hover:scale-[1.02]">
                    {{ __('home.common.view_all_packages') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4">
                        <path d="M5 12h14"></path>
                        <path d="{{ $forwardArrowPath }}"></path>
                    </svg>
                </button>
            </div>
        </div>
    </section>
    <section class="section-padding bg-card">
        <div class="container mx-auto">
            <div class="text-center mb-20">
                <p class="text-primary font-semibold tracking-[0.25em] uppercase text-xs mb-4">{{ __('home.process.eyebrow') }}</p>
                <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('home.process.title') }}</h2>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-14">
                <div class="relative text-center p-8 animate-fade-in-up" style="animation-delay: 0s;">
                    <div
                        class="w-28 h-28 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-8 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-package w-12 h-12 text-primary">
                            <path
                                d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z">
                            </path>
                            <path d="M12 22V12"></path>
                            <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"></path>
                            <path d="m7.5 4.27 9 5.15"></path>
                        </svg>
                        <span
                            class="absolute -top-1 -end-1 w-9 h-9 rounded-full bg-primary text-primary-foreground text-sm font-bold flex items-center justify-center shadow-md">1</span>
                    </div>
                    <h3 class="text-base font-bold text-foreground mb-3">{{ __('home.process.steps.0.title') }}</h3>
                    <p class="text-muted-foreground text-sm leading-relaxed">{{ __('home.process.steps.0.description') }}</p>
                    <div class="hidden lg:block absolute top-24 -end-7 w-14 h-[2px] bg-border"></div>
                </div>
                <div class="relative text-center p-8 animate-fade-in-up" style="animation-delay: 0.15s;">
                    <div
                        class="w-28 h-28 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-8 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-send w-12 h-12 text-primary">
                            <path
                                d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z">
                            </path>
                            <path d="m21.854 2.147-10.94 10.939"></path>
                        </svg>
                        <span
                            class="absolute -top-1 -end-1 w-9 h-9 rounded-full bg-primary text-primary-foreground text-sm font-bold flex items-center justify-center shadow-md">2</span>
                    </div>
                    <h3 class="text-base font-bold text-foreground mb-3">{{ __('home.process.steps.1.title') }}</h3>
                    <p class="text-muted-foreground text-sm leading-relaxed">{{ __('home.process.steps.1.description') }}</p>
                    <div class="hidden lg:block absolute top-24 -end-7 w-14 h-[2px] bg-border"></div>
                </div>
                <div class="relative text-center p-8 animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div
                        class="w-28 h-28 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-8 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-phone-call w-12 h-12 text-primary">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                            </path>
                            <path d="M14.05 2a9 9 0 0 1 8 7.94"></path>
                            <path d="M14.05 6A5 5 0 0 1 18 10"></path>
                        </svg>
                        <span
                            class="absolute -top-1 -end-1 w-9 h-9 rounded-full bg-primary text-primary-foreground text-sm font-bold flex items-center justify-center shadow-md">3</span>
                    </div>
                    <h3 class="text-base font-bold text-foreground mb-3">{{ __('home.process.steps.2.title') }}</h3>
                    <p class="text-muted-foreground text-sm leading-relaxed">{{ __('home.process.steps.2.description') }}</p>
                    <div class="hidden lg:block absolute top-24 -end-7 w-14 h-[2px] bg-border"></div>
                </div>
                <div class="relative text-center p-8 animate-fade-in-up" style="animation-delay: 0.45s;">
                    <div
                        class="w-28 h-28 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-8 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-plane w-12 h-12 text-primary">
                            <path
                                d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z">
                            </path>
                        </svg>
                        <span
                            class="absolute -top-1 -end-1 w-9 h-9 rounded-full bg-primary text-primary-foreground text-sm font-bold flex items-center justify-center shadow-md">4</span>
                    </div>
                    <h3 class="text-base font-bold text-foreground mb-3">{{ __('home.process.steps.3.title') }}</h3>
                    <p class="text-muted-foreground text-sm leading-relaxed">{{ __('home.process.steps.3.description') }}</p>
                </div>
            </div>
        </div>
    </section>
    <section id="umrah-plus" class="section-padding bg-secondary">
        <div class="container mx-auto">
            <div class="grid lg:grid-cols-2 gap-14 lg:gap-24 items-center">
                <div class="relative animate-slide-in-left">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl"><img
                            src="https://www.arqamweb.com/wp-content/uploads/2026/04/umrah-1.jpg" alt=""
                            aria-hidden="true" class="block w-full h-auto invisible"><img
                            src="https://www.arqamweb.com/wp-content/uploads/2026/04/umrah-1.jpg"
                            alt="{{ __('home.umrah_plus.image_alt', ['number' => 1]) }}" loading="lazy"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-[1500ms] ease-in-out opacity-0"><img
                            src="https://www.arqamweb.com/wp-content/uploads/2026/04/umrah-2.jpg"
                            alt="{{ __('home.umrah_plus.image_alt', ['number' => 2]) }}" loading="lazy"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-[1500ms] ease-in-out opacity-0"><img
                            src="https://www.arqamweb.com/wp-content/uploads/2026/04/umrah-3.jpg"
                            alt="{{ __('home.umrah_plus.image_alt', ['number' => 3]) }}" loading="lazy"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-[1500ms] ease-in-out opacity-100">
                        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                            <button
                                aria-label="{{ __('home.umrah_plus.image_indicator', ['number' => 1]) }}"
                                class="h-1.5 rounded-full transition-all duration-500 w-4 bg-white/60 hover:bg-white/90"></button>
                            <button
                                aria-label="{{ __('home.umrah_plus.image_indicator', ['number' => 2]) }}"
                                class="h-1.5 rounded-full transition-all duration-500 w-4 bg-white/60 hover:bg-white/90"></button>
                            <button
                                aria-label="{{ __('home.umrah_plus.image_indicator', ['number' => 3]) }}"
                                class="h-1.5 rounded-full transition-all duration-500 w-8 bg-primary"></button>
                        </div>
                    </div>
                    <div
                        class="absolute -bottom-6 -end-6 bg-primary text-primary-foreground rounded-2xl p-6 shadow-2xl hidden md:block z-20">
                        <p class="text-3xl font-bold">{{ __('home.umrah_plus.badge_title') }}</p>
                        <p class="text-sm text-primary-foreground/80">{{ __('home.umrah_plus.badge_subtitle') }}</p>
                    </div>
                </div>
                <div class="animate-slide-in-right">
                    <p class="text-primary font-semibold tracking-[0.25em] uppercase text-xs mb-4">{{ __('home.umrah_plus.eyebrow') }}</p>
                    <h2
                        class="text-3xl md:text-4xl lg:text-5xl font-bold text-secondary-foreground mb-7 leading-[1.15]">
                        {{ __('home.umrah_plus.title') }}</h2>
                    <p class="text-secondary-foreground/80 leading-relaxed mb-10 text-base md:text-lg">{{ __('home.umrah_plus.description') }}</p>
                    <div class="space-y-5 mb-12">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-7 h-7 rounded-full bg-primary/20 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="lucide lucide-check w-4 h-4 text-primary">
                                    <path d="M20 6 9 17l-5-5"></path>
                                </svg>
                            </div>
                            <span
                                class="text-secondary-foreground/90 text-sm md:text-base font-medium">{{ __('home.umrah_plus.bullets.0') }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-7 h-7 rounded-full bg-primary/20 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="lucide lucide-check w-4 h-4 text-primary">
                                    <path d="M20 6 9 17l-5-5"></path>
                                </svg>
                            </div>
                            <span
                                class="text-secondary-foreground/90 text-sm md:text-base font-medium">{{ __('home.umrah_plus.bullets.1') }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-7 h-7 rounded-full bg-primary/20 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="lucide lucide-check w-4 h-4 text-primary">
                                    <path d="M20 6 9 17l-5-5"></path>
                                </svg>
                            </div>
                            <span
                                class="text-secondary-foreground/90 text-sm md:text-base font-medium">{{ __('home.umrah_plus.bullets.2') }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-7 h-7 rounded-full bg-primary/20 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="lucide lucide-check w-4 h-4 text-primary">
                                    <path d="M20 6 9 17l-5-5"></path>
                                </svg>
                            </div>
                            <span
                                class="text-secondary-foreground/90 text-sm md:text-base font-medium">{{ __('home.umrah_plus.bullets.3') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('umrah-plus') }}"
                       class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-14 py-8 text-base font-bold gap-2 shadow-[0_8px_30px_-4px_hsl(var(--primary)/0.5)]">
                        {{ __('home.umrah_plus.cta') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right w-5 h-5">
                            <path d="M5 12h14"></path>
                            <path d="{{ $forwardArrowPath }}"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="section-padding bg-card">
        <div class="container mx-auto">
            <div class="text-center mb-20">
                <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('home.destinations.eyebrow') }}</p>
                <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('home.destinations.title') }}</h2>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse ($destinations as $destination)
                    @php
                        $destinationImageUrl = $resolveImageUrl(
                            $destination->image_url,
                            asset('kidana-home-assets/egypt-pyramids.jpg'),
                        );
                    @endphp

                    <a href="{{ route('destinations.show', ['destination' => $destination->slug]) }}"
                       class="group relative h-80 cursor-pointer overflow-hidden rounded-2xl premium-shadow transition-all duration-500 hover:-translate-y-1 hover:premium-shadow-hover animate-fade-in-up"
                       style="animation-delay: {{ $loop->index * 0.12 }}s;">
                        <img
                            src="{{ $destinationImageUrl }}"
                            alt="{{ $destination->name }}"
                            loading="lazy"
                            class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/80 via-[hsl(var(--hero-overlay))]/10 to-transparent transition-all duration-500 group-hover:from-[hsl(var(--hero-overlay))]/90 group-hover:via-[hsl(var(--hero-overlay))]/30">
                        </div>
                        <div class="absolute inset-x-0 bottom-0 p-6 transition-all duration-500 group-hover:pb-8">
                            <h3
                                class="text-xl font-bold text-primary-foreground transition-all duration-300 group-hover:text-primary">
                                {{ $destination->name }}</h3>
                        </div>
                    </a>
                @empty
                    <div
                        class="col-span-full rounded-2xl border border-dashed border-border bg-background/70 p-8 text-center">
                        <p class="text-sm font-medium text-muted-foreground">{{ __('home.destinations.empty') }}</p>
                    </div>
                @endforelse
            </div>
            <div class="text-center mt-14">
                <a href="{{ route('destinations.index') }}"
                   class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border bg-background h-10 rounded-xl px-12 py-7 text-sm font-semibold gap-2 border-primary text-primary hover:bg-primary hover:text-primary-foreground transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-1 hover:scale-[1.02]">
                    {{ __('home.common.view_all_destinations') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4">
                        <path d="M5 12h14"></path>
                        <path d="{{ $forwardArrowPath }}"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    <section class="relative py-36 md:py-48 overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
             style="background-image: url(https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?w=1920&amp;q=80);">
        </div>
        <div
            class="absolute inset-0 bg-gradient-to-r from-[hsl(var(--hero-overlay))]/95 via-[hsl(var(--hero-overlay))]/80 to-[hsl(var(--secondary))]/85">
        </div>
        <div class="relative z-10 container mx-auto px-4 md:px-8 text-center">
            <div class="max-w-3xl mx-auto animate-fade-in-up">
                <p class="text-secondary font-semibold tracking-[0.3em] uppercase text-xs mb-5">{{ __('home.difference.eyebrow') }}</p>
                <h2 class="text-3xl md:text-5xl lg:text-6xl font-bold text-primary-foreground mb-8 leading-tight">
                    {{ __('home.difference.title') }}</h2>
                <p class="text-primary-foreground/70 text-lg md:text-xl mb-14 max-w-2xl mx-auto leading-relaxed">
                    {{ __('home.difference.description') }}</p>
                <button
                    class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-14 py-8 text-base font-bold gap-2 shadow-[0_8px_30px_-4px_hsl(var(--primary)/0.5)] hover:-translate-y-1 hover:scale-[1.02] transition-all duration-300">
                    {{ __('home.common.start_your_journey') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right w-5 h-5">
                        <path d="M5 12h14"></path>
                        <path d="{{ $forwardArrowPath }}"></path>
                    </svg>
                </button>
            </div>
        </div>
    </section>
    <section id="about" class="section-padding bg-card">
        <div class="container mx-auto">
            <div class="text-center mb-20">
                <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('home.why.eyebrow') }}</p>
                <h2 class="text-3xl md:text-5xl font-bold text-foreground mb-6">{{ __('home.why.title') }}</h2>
                <p class="text-muted-foreground max-w-xl mx-auto text-sm md:text-base leading-relaxed">{{ __('home.why.description') }}</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div data-reveal
                     class="group text-center p-10 rounded-2xl bg-background border border-border/40 premium-shadow transition-all duration-300 hover:premium-shadow-hover hover:-translate-y-2 hover:scale-[1.01] opacity-0"
                     style="animation-delay: 0s;">
                    <div
                        class="w-16 h-16 rounded-2xl bg-accent flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/10 transition-all duration-300 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-shield w-7 h-7 text-secondary group-hover:text-primary transition-colors duration-300">
                            <path
                                d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-foreground mb-3">{{ __('home.why.items.0.title') }}</h3>
                    <p class="text-muted-foreground text-sm leading-relaxed">{{ __('home.why.items.0.description') }}</p>
                </div>
                <div data-reveal
                     class="group text-center p-10 rounded-2xl bg-background border border-border/40 premium-shadow transition-all duration-300 hover:premium-shadow-hover hover:-translate-y-2 hover:scale-[1.01] opacity-0"
                     style="animation-delay: 0.1s;">
                    <div
                        class="w-16 h-16 rounded-2xl bg-accent flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/10 transition-all duration-300 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-star w-7 h-7 text-secondary group-hover:text-primary transition-colors duration-300">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-foreground mb-3">{{ __('home.why.items.1.title') }}</h3>
                    <p class="text-muted-foreground text-sm leading-relaxed">{{ __('home.why.items.1.description') }}</p>
                </div>
                <div data-reveal
                     class="group text-center p-10 rounded-2xl bg-background border border-border/40 premium-shadow transition-all duration-300 hover:premium-shadow-hover hover:-translate-y-2 hover:scale-[1.01] opacity-0"
                     style="animation-delay: 0.2s;">
                    <div
                        class="w-16 h-16 rounded-2xl bg-accent flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/10 transition-all duration-300 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-headphones w-7 h-7 text-secondary group-hover:text-primary transition-colors duration-300">
                            <path
                                d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-foreground mb-3">{{ __('home.why.items.2.title') }}</h3>
                    <p class="text-muted-foreground text-sm leading-relaxed">{{ __('home.why.items.2.description') }}</p>
                </div>
                <div data-reveal
                     class="group text-center p-10 rounded-2xl bg-background border border-border/40 premium-shadow transition-all duration-300 hover:premium-shadow-hover hover:-translate-y-2 hover:scale-[1.01] opacity-0"
                     style="animation-delay: 0.3s;">
                    <div
                        class="w-16 h-16 rounded-2xl bg-accent flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/10 transition-all duration-300 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-globe w-7 h-7 text-secondary group-hover:text-primary transition-colors duration-300">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                            <path d="M2 12h20"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-foreground mb-3">{{ __('home.why.items.3.title') }}</h3>
                    <p class="text-muted-foreground text-sm leading-relaxed">{{ __('home.why.items.3.description') }}</p>
                </div>
                <div data-reveal
                     class="group text-center p-10 rounded-2xl bg-background border border-border/40 premium-shadow transition-all duration-300 hover:premium-shadow-hover hover:-translate-y-2 hover:scale-[1.01] opacity-0"
                     style="animation-delay: 0.4s;">
                    <div
                        class="w-16 h-16 rounded-2xl bg-accent flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/10 transition-all duration-300 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-award w-7 h-7 text-secondary group-hover:text-primary transition-colors duration-300">
                            <path
                                d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526">
                            </path>
                            <circle cx="12" cy="8" r="6"></circle>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-foreground mb-3">{{ __('home.why.items.4.title') }}</h3>
                    <p class="text-muted-foreground text-sm leading-relaxed">{{ __('home.why.items.4.description') }}</p>
                </div>
                <div data-reveal
                     class="group text-center p-10 rounded-2xl bg-background border border-border/40 premium-shadow transition-all duration-300 hover:premium-shadow-hover hover:-translate-y-2 hover:scale-[1.01] opacity-0"
                     style="animation-delay: 0.5s;">
                    <div
                        class="w-16 h-16 rounded-2xl bg-accent flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/10 transition-all duration-300 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-heart w-7 h-7 text-secondary group-hover:text-primary transition-colors duration-300">
                            <path
                                d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-foreground mb-3">{{ __('home.why.items.5.title') }}</h3>
                    <p class="text-muted-foreground text-sm leading-relaxed">{{ __('home.why.items.5.description') }}</p>
                </div>
            </div>
            <div class="text-center mt-14">
                <button
                    class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border bg-background h-10 rounded-xl px-12 py-7 text-sm font-semibold gap-2 border-primary text-primary hover:bg-primary hover:text-primary-foreground transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-1 hover:scale-[1.02]">
                    {{ __('home.common.read_more_about_us') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4">
                        <path d="M5 12h14"></path>
                        <path d="{{ $forwardArrowPath }}"></path>
                    </svg>
                </button>
            </div>
        </div>
    </section>
    <section class="section-padding bg-muted/40">
        <div class="container mx-auto">
            <div class="text-center mb-20">
                <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('home.testimonials.eyebrow') }}</p>
                <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('home.testimonials.title') }}</h2>
            </div>
            @include('sections.testimonials.index')
            <div class="text-center mt-14">
                <button
                    class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border bg-background h-10 rounded-xl px-12 py-7 text-sm font-semibold gap-2 border-primary text-primary hover:bg-primary hover:text-primary-foreground transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-1 hover:scale-[1.02]">
                    {{ __('home.common.view_all_testimonials') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4">
                        <path d="M5 12h14"></path>
                        <path d="{{ $forwardArrowPath }}"></path>
                    </svg>
                </button>
            </div>
        </div>
    </section>
    <section class="section-padding bg-secondary/10">
        <div class="container mx-auto">
            <div class="text-center mb-20">
                <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('home.presence.eyebrow') }}</p>
                <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('home.presence.title') }}</h2>
                <p class="text-muted-foreground mt-5 max-w-xl mx-auto text-sm md:text-base leading-relaxed">
                    {{ __('home.presence.description') }}</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @forelse ($offices as $office)
                    <div data-reveal
                         class="group bg-card rounded-2xl overflow-hidden border border-border/40 premium-shadow transition-all duration-300 hover:premium-shadow-hover hover:-translate-y-1 opacity-0"
                         style="animation-delay: {{ $loop->index * 0.15 }}s;">
                        <div class="relative h-48 overflow-hidden"><img
                                src="{{ $resolveImageUrl($office->image_url, asset('kidana-home-assets/egypt-cairo.jpg')) }}"
                                alt="{{ $office->name }}"
                                loading="lazy"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/60 to-transparent">
                            </div>
                        </div>
                        <div class="p-8 text-center">
                            <h3 class="text-xl font-bold text-foreground mb-1">{{ $office->name }}</h3>
                            @if ($office->location)
                                <p class="text-primary text-sm font-medium flex items-center justify-center gap-1 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round"
                                         class="lucide lucide-map-pin w-3.5 h-3.5">
                                        <path
                                            d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                                        </path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    {{ $office->location }}
                                </p>
                            @endif
                            @if ($office->description)
                                <p class="text-muted-foreground text-sm leading-relaxed">{{ $office->description }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full rounded-3xl border border-dashed border-border/70 bg-card px-8 py-14 text-center">
                        <h3 class="text-2xl font-bold text-foreground">{{ __('home.presence.empty_title') }}</h3>
                        <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
                            {{ __('home.presence.empty_description') }}
                        </p>
                    </div>
                @endforelse
            </div>
            <div class="text-center mt-14">
                <a href="{{ route('about') }}">
                    <button
                        class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border bg-background h-10 rounded-xl px-12 py-7 text-sm font-semibold gap-2 border-primary text-primary hover:bg-primary hover:text-primary-foreground transition-all duration-300 shadow-md hover:shadow-xl hover:-translate-y-1 hover:scale-[1.02]">
                        {{ __('home.common.read_more_about_us') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right w-4 h-4">
                            <path d="M5 12h14"></path>
                            <path d="{{ $forwardArrowPath }}"></path>
                        </svg>
                    </button>
                </a></div>
        </div>
    </section>
    <section class="section-padding bg-card border-y border-border/30">
        <div class="container mx-auto">
            <div class="text-center mb-16">
                <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('home.partners.eyebrow') }}</p>
                <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('home.partners.title') }}</h2>
                <p class="text-muted-foreground mt-5 max-w-xl mx-auto text-sm md:text-base leading-relaxed">{{ __('home.partners.description') }}</p>
            </div>
            <div data-reveal data-partners-slider class="relative max-w-5xl mx-auto opacity-0">
                @if ($partners->isNotEmpty())
                    <div data-partners-viewport class="overflow-hidden px-16 md:px-24">
                        <div data-partners-track
                             class="flex items-center justify-center gap-14 md:gap-20 transition-transform duration-500 ease-out will-change-transform">
                            @foreach ($partners as $partner)
                                <div data-partner-slide
                                     class="group flex items-center justify-center h-28 w-36 md:w-48 shrink-0">
                                    @if ($partner->image_url)
                                        <img
                                            src="{{ $resolveImageUrl($partner->image_url, asset('kidana-home-assets/egypt-cairo.jpg')) }}"
                                            alt="{{ $partner->name }}"
                                            class="h-20 md:h-24 w-auto object-contain opacity-80 group-hover:opacity-100 transition-all duration-500"
                                            loading="lazy">
                                    @else
                                        <span
                                            class="text-center text-lg font-bold text-muted-foreground transition-colors duration-300 group-hover:text-foreground">
                                            {{ $partner->name }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if ($partners->count() > 1)
                        <button type="button" data-partners-prev
                                class="absolute -start-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-card border border-border/50 flex items-center justify-center hover:bg-primary hover:text-primary-foreground hover:border-primary transition-all duration-300 premium-shadow hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                aria-label="{{ __('home.partners.previous') }}">
                            <svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-chevron-left w-5 h-5">
                                <path d="{{ $previousChevronPath }}"></path>
                            </svg>
                        </button>
                        <button type="button" data-partners-next
                                class="absolute -end-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-card border border-border/50 flex items-center justify-center hover:bg-primary hover:text-primary-foreground hover:border-primary transition-all duration-300 premium-shadow hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                aria-label="{{ __('home.partners.next') }}">
                            <svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-chevron-right w-5 h-5">
                                <path d="{{ $nextChevronPath }}"></path>
                            </svg>
                        </button>
                    @endif
                @else
                    <div class="rounded-3xl border border-dashed border-border/70 bg-muted/30 px-8 py-14 text-center">
                        <h3 class="text-2xl font-bold text-foreground">{{ __('home.partners.empty_title') }}</h3>
                        <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
                            {{ __('home.partners.empty_description') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <section id="contact" class="relative py-36 md:py-48 overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
             style="background-image: url(https://images.unsplash.com/photo-1580418827493-f2b22c0a76cb?w=1920&amp;q=80);">
        </div>
        <div
            class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/95 via-[hsl(var(--hero-overlay))]/85 to-[hsl(var(--secondary))]/90">
        </div>
        <div class="relative z-10 container mx-auto px-4 md:px-8 text-center">
            <div data-reveal class="max-w-3xl mx-auto opacity-0">
                <h2 class="text-3xl md:text-5xl lg:text-6xl font-bold text-primary-foreground mb-8 leading-tight">
                    {{ __('home.contact.title') }}</h2>
                <p class="text-primary-foreground/70 text-lg md:text-xl mb-14 max-w-xl mx-auto leading-relaxed">
                    {{ __('home.contact.description') }}</p>
                <div class="flex flex-col sm:flex-row justify-center gap-5">
                    <button
                        class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-14 py-8 text-base font-bold gap-2 shadow-[0_8px_30px_-4px_hsl(var(--primary)/0.5)]">
                        {{ __('home.contact.inquiry') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right w-5 h-5">
                            <path d="M5 12h14"></path>
                            <path d="{{ $forwardArrowPath }}"></path>
                        </svg>
                    </button>
                    <a href="https://wa.me/201033455433" target="_blank" rel="noopener noreferrer">
                        <button
                            class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border h-10 bg-primary-foreground border-primary-foreground/20 text-secondary hover:bg-primary hover:text-primary-foreground hover:border-primary rounded-xl px-14 py-8 text-base font-bold gap-2 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">
                            <svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-message-circle w-5 h-5">
                                <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                            </svg>
                            {{ __('home.contact.whatsapp') }}
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
