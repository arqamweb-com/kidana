@extends('layout.master')
@section('title', __('umrah-plus.title'))
@section('meta_description', __('umrah-plus.meta_description'))
@section('content')
    @php
        $sectionContainer = 'container mx-auto max-w-6xl';
    @endphp

    <div class="min-h-screen">
        <section class="relative h-[88vh] min-h-[620px] flex items-center justify-center overflow-hidden"><img
                src="{{ asset('kidana-home-assets/kaaba-umrah.jpg') }}" alt="{{ __('umrah-plus.hero.image_alt') }}"
                class="absolute inset-0 w-full h-full object-cover scale-105"
                style="animation: 12s ease-in-out 0s infinite normal none running float;">
            <div
                class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/85 via-[hsl(var(--hero-overlay))]/55 to-[hsl(var(--hero-overlay))]/90"></div>
            <div class="relative z-10 text-center text-primary-foreground px-4 max-w-4xl mx-auto animate-fade-in-up"><p
                    class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-5">{{ __('umrah-plus.hero.eyebrow') }}</p>
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight"><span class="text-primary">{{ __('umrah-plus.hero.title_highlight') }}</span>
                    {{ __('umrah-plus.hero.title_middle') }} <span class="block">{{ __('umrah-plus.hero.title_last') }}</span></h1>
                <p class="text-primary-foreground/85 text-base md:text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
                    {{ __('umrah-plus.hero.description_before') }} <span class="text-primary font-semibold">{{ __('umrah-plus.hero.description_highlight') }}</span> {{ __('umrah-plus.hero.description_after') }}</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#custom-cta"
                       class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 h-10 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-10 py-7 text-sm font-semibold gap-2">
                        {{ __('umrah-plus.hero.start') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right w-4 h-4">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="https://wa.me/201033455433" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border h-10 bg-primary-foreground/10 backdrop-blur border-primary-foreground/30 text-primary-foreground hover:bg-primary-foreground hover:text-secondary rounded-xl px-10 py-7 text-sm font-semibold gap-2">
                        {{ __('umrah-plus.hero.customize') }}
                    </a>
                </div>
                <nav aria-label="Breadcrumb"
                     class="text-xs md:text-sm font-medium tracking-wide text-primary-foreground/80 mt-10">
                    <ol class="flex items-center justify-center flex-wrap gap-1.5 md:gap-2">
                        <li><a class="inline-flex items-center gap-1 transition-colors hover:text-primary-foreground"
                               href="{{ route('home') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="lucide lucide-house w-3.5 h-3.5">
                                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
                                    <path
                                        d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                </svg>
                                <span>{{ __('umrah-plus.hero.breadcrumb_home') }}</span></a></li>
                        <li class="flex items-center gap-1.5 md:gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-chevron-right w-3.5 h-3.5 text-primary-foreground/50">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                            <span aria-current="page" class="font-semibold text-primary-foreground">{{ __('umrah-plus.title') }}</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="{{ $sectionContainer }} animate-fade-in-up">
                <div class="mx-auto max-w-4xl text-center"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('umrah-plus.concept.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground mb-8 leading-tight">{{ __('umrah-plus.concept.title') }}</h2>
                    <div class="space-y-5 text-muted-foreground text-base md:text-lg leading-relaxed">
                        @foreach (__('umrah-plus.concept.paragraphs') as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-muted/40">
            <div class="{{ $sectionContainer }}">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('umrah-plus.audience.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('umrah-plus.audience.title') }}</h2></div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div
                        class="group bg-card rounded-2xl p-10 border border-border/50 hover:border-primary/40 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0s;">
                        <div
                            class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mb-6 group-hover:bg-primary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-plane w-7 h-7 text-primary group-hover:text-primary-foreground transition-colors">
                                <path
                                    d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-foreground mb-3">{{ __('umrah-plus.audience.items.0.title') }}</h3>
                        <p class="text-muted-foreground text-sm leading-relaxed">{{ __('umrah-plus.audience.items.0.description') }}</p></div>
                    <div
                        class="group bg-card rounded-2xl p-10 border border-border/50 hover:border-primary/40 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.12s;">
                        <div
                            class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mb-6 group-hover:bg-primary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-compass w-7 h-7 text-primary group-hover:text-primary-foreground transition-colors">
                                <path
                                    d="m16.24 7.76-1.804 5.411a2 2 0 0 1-1.265 1.265L7.76 16.24l1.804-5.411a2 2 0 0 1 1.265-1.265z"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-foreground mb-3">{{ __('umrah-plus.audience.items.1.title') }}</h3>
                        <p class="text-muted-foreground text-sm leading-relaxed">{{ __('umrah-plus.audience.items.1.description') }}</p></div>
                    <div
                        class="group bg-card rounded-2xl p-10 border border-border/50 hover:border-primary/40 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.24s;">
                        <div
                            class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mb-6 group-hover:bg-primary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-users w-7 h-7 text-primary group-hover:text-primary-foreground transition-colors">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-foreground mb-3">{{ __('umrah-plus.audience.items.2.title') }}</h3>
                        <p class="text-muted-foreground text-sm leading-relaxed">{{ __('umrah-plus.audience.items.2.description') }}</p></div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="{{ $sectionContainer }}">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('umrah-plus.experience.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('umrah-plus.experience.title') }}</h2></div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-secondary/10 flex items-center justify-center mb-5 group-hover:bg-secondary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-bed-double w-6 h-6 text-secondary group-hover:text-secondary-foreground transition-colors">
                                <path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"></path>
                                <path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"></path>
                                <path d="M12 4v6"></path>
                                <path d="M2 18h20"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('umrah-plus.experience.items.0.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('umrah-plus.experience.items.0.description') }}</p></div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.08s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-secondary/10 flex items-center justify-center mb-5 group-hover:bg-secondary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-map w-6 h-6 text-secondary group-hover:text-secondary-foreground transition-colors">
                                <path
                                    d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"></path>
                                <path d="M15 5.764v15"></path>
                                <path d="M9 3.236v15"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('umrah-plus.experience.items.1.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('umrah-plus.experience.items.1.description') }}</p></div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.16s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-secondary/10 flex items-center justify-center mb-5 group-hover:bg-secondary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-hand-heart w-6 h-6 text-secondary group-hover:text-secondary-foreground transition-colors">
                                <path d="M11 14h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16"></path>
                                <path
                                    d="m7 20 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"></path>
                                <path d="m2 15 6 6"></path>
                                <path
                                    d="M19.5 8.5c.7-.7 1.5-1.6 1.5-2.7A2.73 2.73 0 0 0 16 4a2.78 2.78 0 0 0-5 1.8c0 1.2.8 2 1.5 2.8L16 12Z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('umrah-plus.experience.items.2.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('umrah-plus.experience.items.2.description') }}</p></div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.24s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-secondary/10 flex items-center justify-center mb-5 group-hover:bg-secondary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-compass w-6 h-6 text-secondary group-hover:text-secondary-foreground transition-colors">
                                <path
                                    d="m16.24 7.76-1.804 5.411a2 2 0 0 1-1.265 1.265L7.76 16.24l1.804-5.411a2 2 0 0 1 1.265-1.265z"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('umrah-plus.experience.items.3.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('umrah-plus.experience.items.3.description') }}</p></div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.32s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-secondary/10 flex items-center justify-center mb-5 group-hover:bg-secondary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-sparkles w-6 h-6 text-secondary group-hover:text-secondary-foreground transition-colors">
                                <path
                                    d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path>
                                <path d="M20 3v4"></path>
                                <path d="M22 5h-4"></path>
                                <path d="M4 17v2"></path>
                                <path d="M5 18H3"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('umrah-plus.experience.items.4.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('umrah-plus.experience.items.4.description') }}</p></div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.4s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-secondary/10 flex items-center justify-center mb-5 group-hover:bg-secondary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-headphones w-6 h-6 text-secondary group-hover:text-secondary-foreground transition-colors">
                                <path
                                    d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('umrah-plus.experience.items.5.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('umrah-plus.experience.items.5.description') }}</p></div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-muted/40">
            <div class="{{ $sectionContainer }}">
                <div class="mx-auto max-w-4xl">
                    <div class="text-center mb-16"><p
                            class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('umrah-plus.itinerary.eyebrow') }}</p>
                        <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('umrah-plus.itinerary.title') }}</h2></div>
                    <div class="relative">
                        <div class="absolute left-6 md:left-1/2 md:-translate-x-1/2 top-0 bottom-0 w-[2px] bg-border"></div>
                        <div class="space-y-10">
                            <div class="relative md:grid md:grid-cols-2 md:gap-12 items-start animate-fade-in-up"
                                 style="animation-delay: 0s;">
                                <div class="md:text-right  pl-16 md:pl-0">
                                    <div
                                        class="bg-card rounded-2xl p-6 border border-border/60 shadow-sm hover:shadow-lg transition-shadow">
                                        <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-2">{{ __('umrah-plus.itinerary.items.0.day') }}</p>
                                        <h3 class="text-xl font-bold text-foreground mb-2">{{ __('umrah-plus.itinerary.items.0.title') }}</h3>
                                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('umrah-plus.itinerary.items.0.description') }}</p></div>
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
                                        <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-2">{{ __('umrah-plus.itinerary.items.1.day') }}</p>
                                        <h3 class="text-xl font-bold text-foreground mb-2">{{ __('umrah-plus.itinerary.items.1.title') }}</h3>
                                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('umrah-plus.itinerary.items.1.description') }}</p></div>
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
                                        <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-2">{{ __('umrah-plus.itinerary.items.2.day') }}</p>
                                        <h3 class="text-xl font-bold text-foreground mb-2">{{ __('umrah-plus.itinerary.items.2.title') }}</h3>
                                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('umrah-plus.itinerary.items.2.description') }}</p></div>
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
                                        <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-2">{{ __('umrah-plus.itinerary.items.3.day') }}</p>
                                        <h3 class="text-xl font-bold text-foreground mb-2">{{ __('umrah-plus.itinerary.items.3.title') }}</h3>
                                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('umrah-plus.itinerary.items.3.description') }}</p></div>
                                </div>
                                <div class="hidden md:block md:order-1"></div>
                                <span
                                    class="absolute left-4 md:left-1/2 md:-translate-x-1/2 top-6 w-5 h-5 rounded-full bg-primary border-4 border-background shadow-md"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="{{ $sectionContainer }}">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('umrah-plus.gallery.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('umrah-plus.gallery.title') }}</h2></div>
                <div class="space-y-4 md:space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <a href="{{ asset('kidana-home-assets/kaaba-umrah.jpg') }}" target="_blank"
                           rel="noopener noreferrer"
                           class="overflow-hidden rounded-2xl group cursor-zoom-in"
                           aria-label="{{ __('umrah-plus.gallery.open_image', ['number' => 1]) }}"><img
                                src="{{ asset('kidana-home-assets/kaaba-umrah.jpg') }}" alt="{{ __('umrah-plus.gallery.image_alt', ['number' => 1]) }}"
                                loading="lazy"
                                class="w-full h-full object-cover aspect-[16/10] group-hover:scale-110 transition-transform duration-700">
                        </a>
                        <a href="{{ asset('kidana-home-assets/umrah-plus-egypt.jpg') }}" target="_blank"
                           rel="noopener noreferrer"
                           class="overflow-hidden rounded-2xl group cursor-zoom-in"
                           aria-label="{{ __('umrah-plus.gallery.open_image', ['number' => 2]) }}"><img
                                src="{{ asset('kidana-home-assets/umrah-plus-egypt.jpg') }}" alt="{{ __('umrah-plus.gallery.image_alt', ['number' => 2]) }}"
                                loading="lazy"
                                class="w-full h-full object-cover aspect-[16/10] group-hover:scale-110 transition-transform duration-700">
                        </a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                        <a href="{{ asset('kidana-home-assets/nile-cruise.jpg') }}" target="_blank"
                           rel="noopener noreferrer"
                           class="overflow-hidden rounded-2xl group cursor-zoom-in"
                           aria-label="{{ __('umrah-plus.gallery.open_image', ['number' => 3]) }}"><img
                                src="{{ asset('kidana-home-assets/nile-cruise.jpg') }}" alt="{{ __('umrah-plus.gallery.image_alt', ['number' => 3]) }}"
                                loading="lazy"
                                class="w-full h-full object-cover aspect-[4/3] group-hover:scale-110 transition-transform duration-700">
                        </a>
                        <a href="{{ asset('kidana-home-assets/saudi-mecca.jpg') }}" target="_blank"
                           rel="noopener noreferrer"
                           class="overflow-hidden rounded-2xl group cursor-zoom-in"
                           aria-label="{{ __('umrah-plus.gallery.open_image', ['number' => 4]) }}"><img
                                src="{{ asset('kidana-home-assets/saudi-mecca.jpg') }}" alt="{{ __('umrah-plus.gallery.image_alt', ['number' => 4]) }}"
                                loading="lazy"
                                class="w-full h-full object-cover aspect-[4/3] group-hover:scale-110 transition-transform duration-700">
                        </a>
                        <a href="{{ asset('kidana-home-assets/istanbul.jpg') }}" target="_blank"
                           rel="noopener noreferrer"
                           class="overflow-hidden rounded-2xl group cursor-zoom-in"
                           aria-label="{{ __('umrah-plus.gallery.open_image', ['number' => 5]) }}"><img
                                src="{{ asset('kidana-home-assets/istanbul.jpg') }}" alt="{{ __('umrah-plus.gallery.image_alt', ['number' => 5]) }}" loading="lazy"
                                class="w-full h-full object-cover aspect-[4/3] group-hover:scale-110 transition-transform duration-700">
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section id="custom-cta" class="relative py-28 overflow-hidden"
                 style="background: linear-gradient(135deg, hsl(var(--primary)) 0%, hsl(var(--secondary)) 100%);">
            <div
                class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_30%_20%,white,transparent_50%)]"></div>
            <div class="relative z-10 {{ $sectionContainer }} text-primary-foreground px-4">
                <div class="mx-auto max-w-3xl text-center">
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
                    <h2 class="text-3xl md:text-5xl font-bold mb-6 leading-tight">{{ __('umrah-plus.custom.title') }}</h2>
                    <p class="text-primary-foreground/90 text-base md:text-lg mb-10 leading-relaxed">{{ __('umrah-plus.custom.description') }}</p><a href="https://wa.me/201033455433"
                                                                                        target="_blank"
                                                                                        rel="noopener noreferrer"
                                                                                        class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 h-10 btn-premium bg-primary-foreground text-secondary hover:bg-primary-foreground/90 rounded-xl px-12 py-7 text-base font-bold gap-2">
                        {{ __('umrah-plus.custom.cta') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right w-5 h-5">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </a></div>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="{{ $sectionContainer }}">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('umrah-plus.trust.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('umrah-plus.trust.title') }}</h2></div>
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
                        <h3 class="text-base font-bold text-foreground mb-2">{{ __('umrah-plus.trust.items.0.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('umrah-plus.trust.items.0.description') }}</p></div>
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
                        <h3 class="text-base font-bold text-foreground mb-2">{{ __('umrah-plus.trust.items.1.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('umrah-plus.trust.items.1.description') }}</p></div>
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
                        <h3 class="text-base font-bold text-foreground mb-2">{{ __('umrah-plus.trust.items.2.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('umrah-plus.trust.items.2.description') }}</p></div>
                    <div
                        class="text-center bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-lg transition-all duration-300">
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
                        <h3 class="text-base font-bold text-foreground mb-2">{{ __('umrah-plus.trust.items.3.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('umrah-plus.trust.items.3.description') }}</p></div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-muted/40">
            <div class="{{ $sectionContainer }}">
                <div class="mx-auto max-w-3xl">
                    <div class="text-center mb-12"><p
                            class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('umrah-plus.faq.eyebrow') }}</p>
                        <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('umrah-plus.faq.title') }}</h2></div>
                    <div class="space-y-4">
                    <details class="group bg-card rounded-2xl border border-border/60 px-6 shadow-sm">
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between py-5 text-left text-base font-semibold text-foreground md:text-lg">
                            {{ __('umrah-plus.faq.items.0.question') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform duration-200 group-open:rotate-180
">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </summary>
                        <p class="pb-5 text-sm leading-relaxed text-muted-foreground">
                            {{ __('umrah-plus.faq.items.0.answer') }}
                        </p>
                    </details>
                    <details class="group bg-card rounded-2xl border border-border/60 px-6 shadow-sm">
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between py-5 text-left text-base font-semibold text-foreground md:text-lg">
                            {{ __('umrah-plus.faq.items.1.question') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform duration-200 group-open:rotate-180
">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </summary>
                        <p class="pb-5 text-sm leading-relaxed text-muted-foreground">
                            {{ __('umrah-plus.faq.items.1.answer') }}
                        </p>
                    </details>
                    <details class="group bg-card rounded-2xl border border-border/60 px-6 shadow-sm">
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between py-5 text-left text-base font-semibold text-foreground md:text-lg">
                            {{ __('umrah-plus.faq.items.2.question') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform duration-200 group-open:rotate-180
">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </summary>
                        <p class="pb-5 text-sm leading-relaxed text-muted-foreground">
                            {{ __('umrah-plus.faq.items.2.answer') }}
                        </p>
                    </details>
                    <details class="group bg-card rounded-2xl border border-border/60 px-6 shadow-sm">
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between py-5 text-left text-base font-semibold text-foreground md:text-lg">
                            {{ __('umrah-plus.faq.items.3.question') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform duration-200 group-open:rotate-180
">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </summary>
                        <p class="pb-5 text-sm leading-relaxed text-muted-foreground">
                            {{ __('umrah-plus.faq.items.3.answer') }}
                        </p>
                    </details>
                    <details class="group bg-card rounded-2xl border border-border/60 px-6 shadow-sm">
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between py-5 text-left text-base font-semibold text-foreground md:text-lg">
                            {{ __('umrah-plus.faq.items.4.question') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform duration-200 group-open:rotate-180
">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </summary>
                        <p class="pb-5 text-sm leading-relaxed text-muted-foreground">
                            {{ __('umrah-plus.faq.items.4.answer') }}
                        </p>
                    </details>
                    </div>
                </div>
            </div>
        </section>
        <section class="relative py-28 overflow-hidden"><img
                src="{{ asset('kidana-home-assets/umrah-plus-egypt.jpg') }}" alt=""
                class="absolute inset-0 w-full h-full object-cover">
            <div
                class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/90 to-[hsl(var(--secondary))]/90"></div>
            <div class="relative z-10 {{ $sectionContainer }} text-primary-foreground px-4">
                <div class="mx-auto max-w-3xl text-center"><h2
                        class="text-3xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">{{ __('umrah-plus.final_cta.title') }}</h2>
                    <p class="text-primary-foreground/85 text-base md:text-lg mb-10">{{ __('umrah-plus.final_cta.description') }}</p>
                    <div class="flex flex-col sm:flex-row gap-5 justify-center"><a href="https://wa.me/201033455433"
                                                                                   target="_blank"
                                                                                   rel="noopener noreferrer"
                                                                                   class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 h-10 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-12 py-7 text-base font-bold gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-message-circle w-5 h-5">
                                <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                            </svg>
                            {{ __('umrah-plus.final_cta.whatsapp') }}
                        </a><a href="tel:+201033455433"
                               class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border h-10 bg-primary-foreground border-primary-foreground/20 text-secondary hover:bg-primary hover:text-primary-foreground hover:border-primary rounded-xl px-12 py-7 text-base font-bold gap-2 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-phone w-5 h-5">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            {{ __('umrah-plus.final_cta.call') }}
                        </a></div>
                </div>
            </div>
        </section>
        <a href="https://wa.me/201033455433" target="_blank" rel="noopener noreferrer" aria-label="{{ __('umrah-plus.floating_whatsapp') }}"
           class="fixed bottom-6 right-6 z-40 w-14 h-14 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-2xl hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-message-circle w-7 h-7">
                <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
            </svg>
        </a>
    </div>
@endsection
