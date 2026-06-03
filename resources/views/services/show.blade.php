@extends('layout.master')

@section('title', $service->name)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags((string) $service->description), 155))

@section('content')
    @php
        $stats = collect($service->stats ?? []);
        $benefits = collect($service->benefits ?? []);
        $steps = collect($service->steps ?? []);
        $gallery = collect($service->gallery ?? []);
        $galleryImages = $gallery->filter(fn ($item): bool => filled(data_get($item, 'image')))->values();
        $testimonials = $service->testimonials;
        $renderBenefitIcon = static function (?string $icon): string {
            $iconName = filled($icon) ? $icon : 'heroicon-o-check-circle';

            try {
                return svg($iconName, 'w-6 h-6 text-primary group-hover:text-primary-foreground transition-colors')->toHtml();
            } catch (\Throwable) {
                return svg('heroicon-o-check-circle', 'w-6 h-6 text-primary group-hover:text-primary-foreground transition-colors')->toHtml();
            }
        };
        $resolveImageUrl = static function (?string $imagePath, string $fallback): string {
            if (blank($imagePath)) {
                return $fallback;
            }

            if (\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://'])) {
                return $imagePath;
            }

            return \Illuminate\Support\Facades\Storage::url($imagePath);
        };
        $heroImageUrl = $resolveImageUrl(
            $service->hero_image ?: $service->image_url,
            'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=1200&amp;q=80',
        );
    @endphp

    <div class="min-h-screen">
        <section class="relative h-[80vh] min-h-[560px] flex items-center justify-center overflow-hidden"><img
                src="{{ $heroImageUrl }}" alt="{{ $service->name }}"
                class="absolute inset-0 w-full h-full object-cover scale-105">
            <div
                class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/80 via-[hsl(var(--hero-overlay))]/55 to-[hsl(var(--hero-overlay))]/85"></div>
            <div class="relative z-10 text-center text-primary-foreground px-4 max-w-4xl mx-auto animate-fade-in-up"><p
                    class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-5">{{ $service->hero_title ?: __('services.show.hero_eyebrow_fallback') }}</p>
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight"> {{ $service->hero_title ?: $service->name }}</h1>
                @if (filled($service->hero_description))
                    <p class="text-primary-foreground/85 text-base md:text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
                        {{ $service->hero_description }}
                    </p>
                @endif

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button
                        class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-10 py-7 text-sm font-semibold gap-2 shadow-[0_8px_30px_-4px_hsl(var(--primary)/0.5)]">
                        {{ __('services.show.request_package') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right w-4 h-4">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </button>
                    <a href="https://wa.me/201033455433" target="_blank" rel="noopener noreferrer">
                        <button
                            class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border h-10 bg-primary-foreground/10 backdrop-blur border-primary-foreground/30 text-primary-foreground hover:bg-primary-foreground hover:text-secondary rounded-xl px-10 py-7 text-sm font-semibold gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-message-circle w-4 h-4">
                                <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                            </svg>
                            {{ __('services.show.whatsapp') }}
                        </button>
                    </a></div>
                <x-breadcrumbs
                    variant="overlay"
                    align="center"
                    class="mt-10"
                    :items="[
                        ['label' => __('nav.services'), 'url' => route('services.index')],
                        ['label' => $service->name],
                    ]"
                />
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-4xl text-center animate-fade-in-up"><p
                    class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('services.show.intro_eyebrow') }}</p>
                <h2 class="text-3xl md:text-5xl font-bold text-foreground mb-8 leading-tight">{{ __('services.show.intro_title') }}</h2>
                <p class="text-muted-foreground text-base md:text-lg leading-relaxed">{{ __('services.show.intro_description') }}</p></div>
        </section>
        <section class="section-padding bg-muted/40">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('services.show.benefits_eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('services.show.benefits_title') }}</h2></div>
                @if ($benefits->isNotEmpty())
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($benefits as $benefit)
                            @php
                                $benefitIcon = data_get($benefit, 'icon') ?: 'heroicon-o-check-circle';
                                $benefitTitle = data_get($benefit, 'title');
                                $benefitDescription = data_get($benefit, 'desc');
                            @endphp

                            <div
                                class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                                <div
                                    class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-5 group-hover:bg-primary group-hover:scale-110 transition-all">
                                    {!! $renderBenefitIcon($benefitIcon) !!}
                                </div>

                                @if ($benefitTitle)
                                    <h3 class="text-lg font-bold text-foreground mb-2">{{ $benefitTitle }}</h3>
                                @endif

                                @if ($benefitDescription)
                                    <p class="text-sm text-muted-foreground leading-relaxed">{{ $benefitDescription }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-3xl border border-dashed border-border/70 bg-card px-8 py-14 text-center">
                        <h3 class="text-2xl font-bold text-foreground">{{ __('services.show.benefits_empty_title') }}</h3>
                        <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
                            {{ __('services.show.benefits_empty_description') }}
                        </p>
                    </div>
                @endif
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('services.show.offerings_eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('services.show.offerings_title') }}</h2></div>

                @if ($servicePackages->isEmpty())
                    <div class="rounded-3xl border border-dashed border-border/70 bg-muted/40 px-8 py-14 text-center">
                        <h3 class="text-2xl font-bold text-foreground">{{ __('services.show.packages_empty_title') }}</h3>
                        <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
                            {{ __('services.show.packages_empty_description') }}
                        </p>
                    </div>
                @else
                    @include('sections.packages-grid', ['packages' => $servicePackages, 'resolveImageUrl' => $resolveImageUrl])
                @endif
            </div>
        </section>
        <section class="section-padding bg-muted/40">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('services.show.gallery_eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('services.show.gallery_title') }}</h2></div>
                @if ($galleryImages->isNotEmpty())
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 lg:grid-cols-6">
                        @foreach ($galleryImages as $galleryImage)
                            @php
                                $isFeaturedImage = $loop->index < 2;
                                $galleryImageUrl = $resolveImageUrl(data_get($galleryImage, 'image'), '');
                                $galleryImageCaption = data_get($galleryImage, 'caption');
                            @endphp

                            <div
                                class="group relative overflow-hidden rounded-2xl {{ $isFeaturedImage ? 'lg:col-span-3' : 'lg:col-span-2' }}">
                                <img src="{{ $galleryImageUrl }}"
                                     alt="{{ $galleryImageCaption ?: __('services.show.gallery_image_alt', ['service' => $service->name, 'number' => $loop->iteration]) }}"
                                     loading="lazy"
                                     class="w-full object-cover {{ $isFeaturedImage ? 'aspect-[16/10]' : 'aspect-[4/3]' }} group-hover:scale-110 transition-transform duration-700">

                                @if (filled($galleryImageCaption))
                                    <div
                                        class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-secondary/80 to-transparent p-5 pt-12">
                                        <p class="text-sm font-semibold text-primary-foreground">
                                            {{ $galleryImageCaption }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-3xl border border-dashed border-border/70 bg-card px-8 py-14 text-center">
                        <h3 class="text-2xl font-bold text-foreground">{{ __('services.show.gallery_empty_title') }}</h3>
                        <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
                            {{ __('services.show.gallery_empty_description') }}
                        </p>
                    </div>
                @endif
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('services.show.process_eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('services.show.process_title') }}</h2></div>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 relative">
                    <div class="text-center relative">
                        <div
                            class="relative inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary/10 mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-package w-8 h-8 text-primary">
                                <path
                                    d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                                <path d="M12 22V12"></path>
                                <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"></path>
                                <path d="m7.5 4.27 9 5.15"></path>
                            </svg>
                            <span
                                class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-primary text-primary-foreground text-sm font-bold flex items-center justify-center">1</span>
                        </div>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('services.show.process_steps.0.title') }}</h3>
                        <p class="text-sm text-muted-foreground">{{ __('services.show.process_steps.0.description') }}</p></div>
                    <div class="text-center relative">
                        <div
                            class="relative inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary/10 mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-send w-8 h-8 text-primary">
                                <path
                                    d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"></path>
                                <path d="m21.854 2.147-10.94 10.939"></path>
                            </svg>
                            <span
                                class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-primary text-primary-foreground text-sm font-bold flex items-center justify-center">2</span>
                        </div>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('services.show.process_steps.1.title') }}</h3>
                        <p class="text-sm text-muted-foreground">{{ __('services.show.process_steps.1.description') }}</p></div>
                    <div class="text-center relative">
                        <div
                            class="relative inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary/10 mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-phone-call w-8 h-8 text-primary">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                <path d="M14.05 2a9 9 0 0 1 8 7.94"></path>
                                <path d="M14.05 6A5 5 0 0 1 18 10"></path>
                            </svg>
                            <span
                                class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-primary text-primary-foreground text-sm font-bold flex items-center justify-center">3</span>
                        </div>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('services.show.process_steps.2.title') }}</h3>
                        <p class="text-sm text-muted-foreground">{{ __('services.show.process_steps.2.description') }}</p></div>
                    <div class="text-center relative">
                        <div
                            class="relative inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary/10 mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-plane w-8 h-8 text-primary">
                                <path
                                    d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"></path>
                            </svg>
                            <span
                                class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-primary text-primary-foreground text-sm font-bold flex items-center justify-center">4</span>
                        </div>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('services.show.process_steps.3.title') }}</h3>
                        <p class="text-sm text-muted-foreground">{{ __('services.show.process_steps.3.description') }}</p></div>
                </div>
            </div>
        </section>
        <section class="py-20 bg-secondary text-secondary-foreground">
            <div class="container mx-auto max-w-6xl px-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-primary mb-2">15+</div>
                        <div class="text-sm md:text-base text-secondary-foreground/85 uppercase tracking-wider">
                            {{ __('services.show.stats.years_experience') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-primary mb-2">25,000+</div>
                        <div class="text-sm md:text-base text-secondary-foreground/85 uppercase tracking-wider">
                            {{ __('services.show.stats.travelers_served') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-primary mb-2">50+</div>
                        <div class="text-sm md:text-base text-secondary-foreground/85 uppercase tracking-wider">
                            {{ __('services.show.stats.trusted_partners') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-primary mb-2">100%</div>
                        <div class="text-sm md:text-base text-secondary-foreground/85 uppercase tracking-wider">
                            {{ __('services.show.stats.client_satisfaction') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-5xl">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('services.show.testimonials_eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('services.show.testimonials_title') }}</h2></div>
                @include('sections.testimonials.index', ['style' => 'style-2', 'testimonials' => $testimonials])
            </div>
        </section>
        <section id="booking-form" class="section-padding bg-muted/40">
            <div class="container mx-auto max-w-3xl">
                <div class="text-center mb-12"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('services.show.inquiry_eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground mb-4">{{ __('services.show.inquiry_title') }}</h2>
                    <p class="text-muted-foreground text-base md:text-lg">{{ __('services.show.inquiry_description') }}</p></div>
                <form
                    action="{{ route('services.inquiry', ['locale' => app()->getLocale(), 'service' => $service->slug]) }}"
                    method="POST"
                    class="bg-card rounded-2xl p-8 md:p-10 border border-border/60 shadow-xl space-y-5">
                    @csrf

                    @if ($inquirySent)
                        <div class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300">
                            {{ __('services.show.form.success') }}
                        </div>
                    @endif

                    <div class="grid md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="name">{{ __('services.show.form.name') }}</label>
                            <input name="name" id="name" value="{{ old('name') }}" maxlength="100"
                                placeholder="{{ __('services.show.form.name_placeholder') }}"
                                class="flex w-full rounded-md border bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm h-12 {{ $errors->has('name') ? 'border-red-400' : 'border-input' }}">
                            @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="phone">{{ __('services.show.form.phone') }}</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" maxlength="30"
                                placeholder="{{ __('services.show.form.phone_placeholder') }}"
                                class="flex w-full rounded-md border bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm h-12 {{ $errors->has('phone') ? 'border-red-400' : 'border-input' }}">
                            @error('phone') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="email">{{ __('services.show.form.email') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" maxlength="255"
                            placeholder="{{ __('services.show.form.email_placeholder') }}"
                            class="flex w-full rounded-md border bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm h-12 {{ $errors->has('email') ? 'border-red-400' : 'border-input' }}">
                        @error('email') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="date">{{ __('services.show.form.travel_date') }}</label>
                            <input type="date" name="travel_date" id="date" value="{{ old('travel_date') }}"
                                class="flex w-full rounded-md border bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm h-12 {{ $errors->has('travel_date') ? 'border-red-400' : 'border-input' }}">
                            @error('travel_date') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="people">{{ __('services.show.form.people') }}</label>
                            <input type="number" name="people" id="people" value="{{ old('people') }}" min="1"
                                placeholder="{{ __('services.show.form.people_placeholder') }}"
                                class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm h-12">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="message">{{ __('services.show.form.message') }}</label>
                        <textarea name="message" id="message" maxlength="1000"
                            placeholder="{{ __('services.show.form.message_placeholder') }}"
                            class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 min-h-[120px]">{{ old('message') }}</textarea>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 h-10 px-4 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl w-full py-7 text-base font-semibold gap-2 shadow-[0_8px_30px_-4px_hsl(var(--primary)/0.5)]">
                        {{ __('services.show.form.submit') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right w-5 h-5 rtl:rotate-180">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </section>
        <section class="relative py-28 overflow-hidden"><img
                src="https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=1920&amp;q=80" alt=""
                class="absolute inset-0 w-full h-full object-cover">
            <div
                class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/90 to-[hsl(var(--secondary))]/90"></div>
            <div class="relative z-10 container mx-auto px-4 text-center max-w-3xl"><h2
                    class="text-3xl md:text-5xl lg:text-6xl font-bold text-primary-foreground mb-6 leading-tight">{{ __('services.show.cta_title') }}</h2>
                <p class="text-primary-foreground/80 text-lg mb-10">{{ __('services.show.cta_description') }}</p>
                <div class="flex flex-col sm:flex-row gap-5 justify-center">
                    <button
                        class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-12 py-7 text-base font-bold gap-2 shadow-[0_8px_30px_-4px_hsl(var(--primary)/0.5)]">
                        {{ __('services.show.book_now') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right w-5 h-5">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </button>
                    <a href="https://wa.me/201033455433" target="_blank" rel="noopener noreferrer">
                        <button
                            class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border h-10 bg-primary-foreground border-primary-foreground/20 text-secondary hover:bg-primary hover:text-primary-foreground hover:border-primary rounded-xl px-12 py-7 text-base font-bold gap-2 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-message-circle w-5 h-5">
                                <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                            </svg>
                            {{ __('services.show.whatsapp') }}
                        </button>
                    </a>
                </div>
            </div>
        </section>
    </div>

@endsection
