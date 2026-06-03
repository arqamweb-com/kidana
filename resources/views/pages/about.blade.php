@extends('layout.master')
@section('title', __('about.title'))
@section('meta_description', __('about.meta_description'))
@section('content')
    <div class="min-h-screen">
        <section class="relative h-[80vh] min-h-[560px] flex items-center justify-center overflow-hidden"><img
                src="{{ asset('kidana-home-assets/egypt-cairo.jpg') }}" alt="{{ __('about.hero_alt') }}"
                class="absolute inset-0 w-full h-full object-cover scale-105">
            <div
                class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/85 via-[hsl(var(--hero-overlay))]/55 to-[hsl(var(--hero-overlay))]/90"></div>
            <div class="relative z-10 text-center text-primary-foreground px-4 max-w-4xl mx-auto animate-fade-in-up"><p
                    class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-5">{{ __('about.eyebrow') }}</p>
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">{{ __('about.hero_title') }}</h1>
                <p class="text-primary-foreground/85 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">{{ __('about.hero_subtitle') }}</p>
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
                                <span>{{ __('nav.home') }}</span></a></li>
                        <li class="flex items-center gap-1.5 md:gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-chevron-right w-3.5 h-3.5 text-primary-foreground/50 rtl:rotate-180">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                            <span aria-current="page" class="font-semibold text-primary-foreground">{{ __('about.title') }}</span></li>
                    </ol>
                </nav>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-4xl text-center animate-fade-in-up"><p
                    class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('about.who_we_are.eyebrow') }}</p>
                <h2 class="text-3xl md:text-5xl font-bold text-foreground mb-8 leading-tight">{{ __('about.who_we_are.title') }}</h2>
                <div
                    class="space-y-5 text-muted-foreground text-base md:text-lg leading-relaxed text-left md:text-center">
                    <p>{{ __('about.who_we_are.p1') }}</p>
                    <p>{{ __('about.who_we_are.p2') }}</p>
                    <p>{{ __('about.who_we_are.p3') }}</p>
                </div>
            </div>
        </section>
        <section class="section-padding bg-muted/40">
            <div class="container mx-auto max-w-6xl">
                <div class="grid md:grid-cols-2 gap-14 items-center">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl animate-fade-in-up"><img
                            src="{{ asset('kidana-home-assets/medina.jpg') }}" alt="Our journey"
                            class="w-full h-full object-cover aspect-[4/5]">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/40 to-transparent"></div>
                    </div>
                    <div class="animate-fade-in-up" style="animation-delay: 0.15s;"><p
                            class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('about.journey.eyebrow') }}</p>
                        <h2 class="text-3xl md:text-5xl font-bold text-foreground mb-8 leading-tight">{{ __('about.journey.title') }}</h2>
                        <div class="space-y-5 text-muted-foreground text-base leading-relaxed">
                            <p>{{ __('about.journey.p1') }}</p>
                            <p>{{ __('about.journey.p2') }}</p>
                            <p>{{ __('about.journey.p3') }}</p>
                            <p>{{ __('about.journey.p4') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-6xl">
                <div class="grid md:grid-cols-2 gap-8">
                    <div
                        class="relative bg-card rounded-3xl p-10 md:p-12 border border-border/60 shadow-xl overflow-hidden group animate-fade-in-up">
                        <div
                            class="absolute -right-10 -top-10 w-44 h-44 rounded-full bg-primary/10 group-hover:scale-110 transition-transform"></div>
                        <div class="relative">
                            <div
                                class="w-16 h-16 rounded-2xl bg-primary text-primary-foreground flex items-center justify-center mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="lucide lucide-target w-7 h-7">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <circle cx="12" cy="12" r="6"></circle>
                                    <circle cx="12" cy="12" r="2"></circle>
                                </svg>
                            </div>
                            <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-3">{{ __('about.mission.eyebrow') }}</p>
                            <h3 class="text-2xl md:text-3xl font-bold text-foreground mb-5">{{ __('about.mission.title') }}</h3>
                            <p class="text-muted-foreground text-base leading-relaxed">{{ __('about.mission.description') }}</p></div>
                    </div>
                    <div
                        class="relative bg-secondary text-secondary-foreground rounded-3xl p-10 md:p-12 shadow-xl overflow-hidden group animate-fade-in-up"
                        style="animation-delay: 0.15s;">
                        <div
                            class="absolute -right-10 -top-10 w-44 h-44 rounded-full bg-primary/20 group-hover:scale-110 transition-transform"></div>
                        <div class="relative">
                            <div
                                class="w-16 h-16 rounded-2xl bg-primary text-primary-foreground flex items-center justify-center mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="lucide lucide-eye w-7 h-7">
                                    <path
                                        d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </div>
                            <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-3">{{ __('about.vision.eyebrow') }}</p>
                            <h3 class="text-2xl md:text-3xl font-bold mb-5">{{ __('about.vision.title') }}</h3>
                            <p class="text-secondary-foreground/85 text-base leading-relaxed">{{ __('about.vision.description') }}</p></div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-muted/40">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('about.why.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('about.why.title') }}</h2></div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-5 animate-fade-in-up"
                        style="animation-delay: 0s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-award w-6 h-6 text-primary group-hover:text-primary-foreground transition-colors">
                                <path
                                    d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"></path>
                                <circle cx="12" cy="8" r="6"></circle>
                            </svg>
                        </div>
                        <h3 class="text-base md:text-lg font-bold text-foreground">{{ __('about.why.features.experience') }}</h3></div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-5 animate-fade-in-up"
                        style="animation-delay: 0.08s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-shield-check w-6 h-6 text-primary group-hover:text-primary-foreground transition-colors">
                                <path
                                    d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-base md:text-lg font-bold text-foreground">{{ __('about.why.features.licensed') }}</h3></div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-5 animate-fade-in-up"
                        style="animation-delay: 0.16s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-globe w-6 h-6 text-primary group-hover:text-primary-foreground transition-colors">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                                <path d="M2 12h20"></path>
                            </svg>
                        </div>
                        <h3 class="text-base md:text-lg font-bold text-foreground">{{ __('about.why.features.partnerships') }}</h3></div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-5 animate-fade-in-up"
                        style="animation-delay: 0.24s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:scale-110 transition-all">
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
                        <h3 class="text-base md:text-lg font-bold text-foreground">{{ __('about.why.features.hajj_expertise') }}</h3></div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-5 animate-fade-in-up"
                        style="animation-delay: 0.32s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-star w-6 h-6 text-primary group-hover:text-primary-foreground transition-colors">
                                <path
                                    d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base md:text-lg font-bold text-foreground">{{ __('about.why.features.luxury') }}</h3></div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-5 animate-fade-in-up"
                        style="animation-delay: 0.4s;">
                        <div
                            class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:scale-110 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="lucide lucide-heart w-6 h-6 text-primary group-hover:text-primary-foreground transition-colors">
                                <path
                                    d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base md:text-lg font-bold text-foreground">{{ __('about.why.features.personalized') }}</h3>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('about.values.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('about.values.title') }}</h2></div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div
                        class="text-center bg-card rounded-2xl p-10 border border-border/50 hover:border-primary/40 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0s;">
                        <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-lightbulb w-7 h-7 text-primary">
                                <path
                                    d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"></path>
                                <path d="M9 18h6"></path>
                                <path d="M10 22h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-foreground mb-3">{{ __('about.values.proactivity.title') }}</h3>
                        <p class="text-muted-foreground text-sm leading-relaxed">{{ __('about.values.proactivity.description') }}</p></div>
                    <div
                        class="text-center bg-card rounded-2xl p-10 border border-border/50 hover:border-primary/40 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.12s;">
                        <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-shield-check w-7 h-7 text-primary">
                                <path
                                    d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-foreground mb-3">{{ __('about.values.professionalism.title') }}</h3>
                        <p class="text-muted-foreground text-sm leading-relaxed">{{ __('about.values.professionalism.description') }}</p></div>
                    <div
                        class="text-center bg-card rounded-2xl p-10 border border-border/50 hover:border-primary/40 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.24s;">
                        <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-hand-heart w-7 h-7 text-primary">
                                <path d="M11 14h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16"></path>
                                <path
                                    d="m7 20 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"></path>
                                <path d="m2 15 6 6"></path>
                                <path
                                    d="M19.5 8.5c.7-.7 1.5-1.6 1.5-2.7A2.73 2.73 0 0 0 16 4a2.78 2.78 0 0 0-5 1.8c0 1.2.8 2 1.5 2.8L16 12Z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-foreground mb-3">{{ __('about.values.care.title') }}</h3>
                        <p class="text-muted-foreground text-sm leading-relaxed">{{ __('about.values.care.description') }}</p></div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-muted/40">
            <div class="container mx-auto max-w-3xl text-center animate-fade-in-up">
                <div
                    class="w-16 h-16 rounded-2xl bg-secondary text-secondary-foreground flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-shield-check w-7 h-7">
                        <path
                            d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                </div>
                <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('about.commitment.eyebrow') }}</p>
                <h2 class="text-3xl md:text-5xl font-bold text-foreground mb-8 leading-tight">{{ __('about.commitment.title') }}</h2>
                <p class="text-muted-foreground text-base md:text-lg leading-relaxed">{{ __('about.commitment.description') }}</p></div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('about.network.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground mb-5">{{ __('about.network.title') }}</h2>
                    <p class="text-muted-foreground text-base md:text-lg max-w-2xl mx-auto">{{ __('about.network.description') }}</p></div>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0s;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-globe w-7 h-7 text-primary mb-4">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                            <path d="M2 12h20"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('about.network.saudi.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('about.network.saudi.description') }}</p></div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.1s;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-globe w-7 h-7 text-primary mb-4">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                            <path d="M2 12h20"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('about.network.uae.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('about.network.uae.description') }}</p>
                    </div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.2s;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-globe w-7 h-7 text-primary mb-4">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                            <path d="M2 12h20"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('about.network.indonesia.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('about.network.indonesia.description') }}</p></div>
                    <div
                        class="group bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up"
                        style="animation-delay: 0.3s;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-globe w-7 h-7 text-primary mb-4">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                            <path d="M2 12h20"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('about.network.egypt.title') }}</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ __('about.network.egypt.description') }}</p></div>
                </div>
                <div class="grid grid-cols-3 gap-4 md:gap-6 mt-12">
                    <div class="overflow-hidden rounded-2xl group"><img src="{{ asset('kidana-home-assets/saudi-mecca.jpg') }}"
                                                                        alt="Partner region" loading="lazy"
                                                                        class="w-full h-full object-cover aspect-[4/3] group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="overflow-hidden rounded-2xl group"><img src="{{ asset('kidana-home-assets/jakarta-indonesia.jpg') }}"
                                                                        alt="Partner region" loading="lazy"
                                                                        class="w-full h-full object-cover aspect-[4/3] group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="overflow-hidden rounded-2xl group"><img src="{{ asset('kidana-home-assets/egypt-cairo.jpg') }}"
                                                                        alt="Partner region" loading="lazy"
                                                                        class="w-full h-full object-cover aspect-[4/3] group-hover:scale-110 transition-transform duration-700">
                    </div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-muted/40">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-16"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('about.leadership.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('about.leadership.title') }}</h2></div>
                <div class="grid md:grid-cols-2 gap-6">
                    <div
                        class="group bg-card rounded-2xl p-8 border border-border/50 hover:border-primary/40 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex gap-6 animate-fade-in-up"
                        style="animation-delay: 0s;">
                        <div
                            class="shrink-0 w-20 h-20 rounded-2xl bg-gradient-to-br from-primary to-secondary text-primary-foreground flex items-center justify-center text-xl font-bold shadow-lg">
                            RR
                        </div>
                        <div><h3 class="text-lg md:text-xl font-bold text-foreground mb-1">Reda Rashwan</h3>
                            <p class="text-primary text-sm font-semibold tracking-wide uppercase mb-3">{{ __('about.leadership.reda.title') }}</p>
                            <p class="text-sm text-muted-foreground leading-relaxed">{{ __('about.leadership.reda.description') }}</p></div>
                    </div>
                    <div
                        class="group bg-card rounded-2xl p-8 border border-border/50 hover:border-primary/40 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex gap-6 animate-fade-in-up"
                        style="animation-delay: 0.1s;">
                        <div
                            class="shrink-0 w-20 h-20 rounded-2xl bg-gradient-to-br from-primary to-secondary text-primary-foreground flex items-center justify-center text-xl font-bold shadow-lg">
                            AG
                        </div>
                        <div><h3 class="text-lg md:text-xl font-bold text-foreground mb-1">Akram Gad</h3>
                            <p class="text-primary text-sm font-semibold tracking-wide uppercase mb-3">{{ __('about.leadership.akram.title') }}</p>
                            <p class="text-sm text-muted-foreground leading-relaxed">{{ __('about.leadership.akram.description') }}</p></div>
                    </div>
                    <div
                        class="group bg-card rounded-2xl p-8 border border-border/50 hover:border-primary/40 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex gap-6 animate-fade-in-up"
                        style="animation-delay: 0.2s;">
                        <div
                            class="shrink-0 w-20 h-20 rounded-2xl bg-gradient-to-br from-primary to-secondary text-primary-foreground flex items-center justify-center text-xl font-bold shadow-lg">
                            AS
                        </div>
                        <div><h3 class="text-lg md:text-xl font-bold text-foreground mb-1">Ahmed El Shafai</h3>
                            <p class="text-primary text-sm font-semibold tracking-wide uppercase mb-3">{{ __('about.leadership.ahmed.title') }}</p>
                            <p class="text-sm text-muted-foreground leading-relaxed">{{ __('about.leadership.ahmed.description') }}</p>
                        </div>
                    </div>
                    <div
                        class="group bg-card rounded-2xl p-8 border border-border/50 hover:border-primary/40 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex gap-6 animate-fade-in-up"
                        style="animation-delay: 0.3s;">
                        <div
                            class="shrink-0 w-20 h-20 rounded-2xl bg-gradient-to-br from-primary to-secondary text-primary-foreground flex items-center justify-center text-xl font-bold shadow-lg">
                            NA
                        </div>
                        <div><h3 class="text-lg md:text-xl font-bold text-foreground mb-1">Niazy Abdelkader</h3>
                            <p class="text-primary text-sm font-semibold tracking-wide uppercase mb-3">{{ __('about.leadership.niazy.title') }}</p>
                            <p class="text-sm text-muted-foreground leading-relaxed">{{ __('about.leadership.niazy.description') }}</p></div>
                    </div>
                </div>
            </div>
        </section>
        <section class="relative py-28 overflow-hidden"><img src="{{ asset('kidana-home-assets/nile-cruise.jpg') }}" alt=""
                                                             class="absolute inset-0 w-full h-full object-cover">
            <div
                class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/90 to-[hsl(var(--secondary))]/90"></div>
            <div class="relative z-10 container mx-auto max-w-3xl text-center text-primary-foreground px-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-compass w-12 h-12 mx-auto mb-6 opacity-90">
                    <path
                        d="m16.24 7.76-1.804 5.411a2 2 0 0 1-1.265 1.265L7.76 16.24l1.804-5.411a2 2 0 0 1 1.265-1.265z"></path>
                    <circle cx="12" cy="12" r="10"></circle>
                </svg>
                <h2 class="text-3xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">{{ __('about.cta.title') }}</h2>
                <p class="text-primary-foreground/85 text-base md:text-lg mb-10 leading-relaxed">{{ __('about.cta.description') }}</p>
                <div class="flex flex-col sm:flex-row gap-5 justify-center">
                    <a href="{{ route('services.index') }}"
                        class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 h-10 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-12 py-7 text-base font-bold gap-2">
                        {{ __('about.cta.services_btn') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right w-5 h-5 rtl:rotate-180">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('contact') }}"
                        class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 border h-10 bg-primary-foreground border-primary-foreground/20 text-secondary hover:bg-primary hover:text-primary-foreground hover:border-primary rounded-xl px-12 py-7 text-base font-bold gap-2 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-message-circle w-5 h-5">
                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                        </svg>
                        {{ __('about.cta.contact_btn') }}
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection
