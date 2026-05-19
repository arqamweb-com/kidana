@php
    $supportedLocales = config('locales.supported', []);
    $currentLocale = app()->getLocale();
    $currentLocaleMeta = $supportedLocales[$currentLocale] ?? reset($supportedLocales);
    $currentRouteName = request()->route()?->getName();
    $currentRouteParameters = request()->route()?->parameters() ?? [];
    $currentQueryString = request()->getQueryString();
@endphp

<header data-home-header
        class="fixed inset-x-0 top-0 z-50 bg-card/95 py-2.5 shadow-lg backdrop-blur-xl transition-all duration-500">
    <div class="container mx-auto flex items-center justify-between px-4 md:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-2 cursor-pointer">
            <img src="https://www.arqamweb.com/wp-content/uploads/2026/04/Logo-Web.png" alt="Kidana Travel"
                 class="h-10 w-auto md:h-12">
        </a>

        <nav class="hidden items-center gap-7 lg:flex">
            <a href="{{ route('home') }}" data-nav-link
               class="relative inline-flex items-center gap-2 text-sm font-medium text-foreground transition-all duration-300 after:absolute after:bottom-[-4px] after:start-0 after:h-[2px] after:w-0 after:bg-primary after:transition-all after:duration-300 hover:text-primary hover:after:w-full">{{ __('nav.home') }}</a>

            <div data-dropdown class="relative">
                <button type="button" data-dropdown-trigger data-nav-trigger
                        class="flex items-center gap-1 text-sm font-medium text-foreground transition-all duration-300 hover:text-primary">
                    <span>{{ __('nav.services') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" class="h-3.5 w-3.5 transition-transform duration-300"
                         data-dropdown-icon>
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>
                </button>

                <div data-dropdown-panel
                     class="absolute start-0 top-full mt-3 hidden min-w-[220px] overflow-hidden rounded-xl border border-border/50 bg-card shadow-xl">
                    <a href="{{ route('services.index') }}"
                       class="block px-5 py-3 text-sm text-muted-foreground transition-colors duration-200 hover:bg-muted hover:text-foreground">{{ __('nav.all_services') }}</a>
                    @foreach ($navigationServices as $service)
                        <a href="{{ route('services.show', ['service' => $service->slug]) }}"
                           class="block px-5 py-3 text-sm text-muted-foreground transition-colors duration-200 hover:bg-muted hover:text-foreground">
                            {{ $service->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('packages') }}" data-nav-link
               class="relative inline-flex items-center gap-2 text-sm font-medium text-foreground transition-all duration-300 after:absolute after:bottom-[-4px] after:start-0 after:h-[2px] after:w-0 after:bg-primary after:transition-all after:duration-300 hover:text-primary hover:after:w-full">{{ __('nav.packages') }}</a>
            <a href="{{ route('umrah-plus') }}" data-nav-link
               class="relative inline-flex items-center gap-2 text-sm font-medium text-foreground transition-all duration-300 after:absolute after:bottom-[-4px] after:start-0 after:h-[2px] after:w-0 after:bg-primary after:transition-all after:duration-300 hover:text-primary hover:after:w-full">
                <span>{{ __('nav.umrah_plus') }}</span>
                <span
                    class="rounded-md bg-[#f05c01] px-1.5 py-0.5 text-[9px] font-bold uppercase leading-none tracking-wider text-white">{{ __('nav.new') }}</span>
            </a>
            <a href="{{ route('about') }}" data-nav-link
               class="relative inline-flex items-center gap-2 text-sm font-medium text-foreground transition-all duration-300 after:absolute after:bottom-[-4px] after:start-0 after:h-[2px] after:w-0 after:bg-primary after:transition-all after:duration-300 hover:text-primary hover:after:w-full">{{ __('nav.about') }}</a>
            <a href="{{ route('contact') }}" data-nav-link
               class="relative inline-flex items-center gap-2 text-sm font-medium text-foreground transition-all duration-300 after:absolute after:bottom-[-4px] after:start-0 after:h-[2px] after:w-0 after:bg-primary after:transition-all after:duration-300 hover:text-primary hover:after:w-full">{{ __('nav.contact') }}</a>
        </nav>

        <div class="hidden items-center gap-4 lg:flex">
            <div data-dropdown class="relative">
                <button type="button" data-dropdown-trigger data-language-trigger
                        class="flex items-center gap-1.5 rounded-lg border border-border/60 px-3 py-1.5 text-xs font-medium text-foreground transition-all duration-300 hover:border-primary/40">
                    <span>{{ $currentLocaleMeta['flag'] ?? '🌐' }}</span>
                    <span>{{ $currentLocaleMeta['short_label'] ?? strtoupper($currentLocale) }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" class="h-3 w-3 transition-transform duration-300" data-dropdown-icon>
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>
                </button>

                <div data-dropdown-panel
                     class="absolute end-0 top-full mt-2 hidden min-w-[160px] overflow-hidden rounded-xl border border-border/50 bg-card shadow-xl">
                    @foreach ($supportedLocales as $locale => $meta)
                        @php
                            $localeUrl = $currentRouteName
                                ? route($currentRouteName, array_replace($currentRouteParameters, ['locale' => $locale]))
                                : url($locale);

                            if (filled($currentQueryString)) {
                                $localeUrl .= '?' . $currentQueryString;
                            }
                        @endphp

                        <a href="{{ $localeUrl }}"
                           class="flex w-full items-center gap-2.5 px-4 py-2.5 text-start text-sm transition-colors duration-200 hover:bg-muted {{ $locale === $currentLocale ? 'font-medium text-foreground' : 'text-muted-foreground hover:text-foreground' }}">
                            <span class="text-base">{{ $meta['flag'] }}</span>
                            <span>{{ $meta['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <a href="tel:+201033455433"
               class="flex items-center gap-2 text-sm font-medium text-foreground transition-colors duration-300 hover:text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="h-4 w-4">
                    <path
                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                    </path>
                </svg>
                <span>+201033455433</span>
            </a>

            <a href="#packages"
               class="btn-premium inline-flex h-9 items-center justify-center gap-2 rounded-xl border-0 bg-primary px-7 text-sm font-semibold whitespace-nowrap text-primary-foreground transition-colors hover:bg-primary/90"
               style="background-color: rgb(50, 113, 107);">{{ __('nav.visit_egypt') }}</a>
            <a href="#contact"
               class="btn-premium inline-flex h-9 items-center justify-center gap-2 rounded-xl bg-primary px-7 text-sm font-semibold whitespace-nowrap text-primary-foreground transition-colors hover:bg-primary/90">{{ __('nav.book_now') }}</a>
        </div>

        <button type="button" data-mobile-menu-trigger class="text-foreground transition-colors lg:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                <line x1="4" x2="20" y1="12" y2="12"></line>
                <line x1="4" x2="20" y1="6" y2="6"></line>
                <line x1="4" x2="20" y1="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <div data-mobile-menu class="hidden border-t border-border bg-card/98 backdrop-blur-xl lg:hidden">
        <div class="container mx-auto flex flex-col gap-1 px-4 py-5">
            <a href="#home"
               class="inline-flex items-center gap-2 rounded-lg px-3 py-3 text-sm font-medium text-foreground transition-all duration-300 hover:bg-muted/50 hover:text-primary">{{ __('nav.home') }}</a>
            <div>
                <button type="button" data-mobile-services-trigger
                        class="flex w-full items-center justify-between rounded-lg px-3 py-3 text-sm font-medium text-foreground transition-all duration-300 hover:bg-muted/50 hover:text-primary">
                    <span>{{ __('nav.services') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" class="h-4 w-4 transition-transform duration-300"
                         data-mobile-services-icon>
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>
                </button>
                <div data-mobile-services-panel class="hidden flex-col gap-0.5 ps-4">
                    <a href="{{ route('services.index') }}"
                       class="rounded-lg px-3 py-2.5 text-sm text-muted-foreground transition-all duration-300 hover:bg-muted/50 hover:text-primary">{{ __('nav.all_services') }}</a>
                    @foreach ($navigationServices as $service)
                        <a href="{{ route('services.show', ['service' => $service->slug]) }}"
                           class="rounded-lg px-3 py-2.5 text-sm text-muted-foreground transition-all duration-300 hover:bg-muted/50 hover:text-primary">
                            {{ $service->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            <a href="#packages"
               class="inline-flex items-center gap-2 rounded-lg px-3 py-3 text-sm font-medium text-foreground transition-all duration-300 hover:bg-muted/50 hover:text-primary">{{ __('nav.packages') }}</a>
            <a href="#umrah-plus"
               class="inline-flex items-center gap-2 rounded-lg px-3 py-3 text-sm font-medium text-foreground transition-all duration-300 hover:bg-muted/50 hover:text-primary">
                <span>{{ __('nav.umrah_plus') }}</span>
                <span
                    class="rounded-md bg-[#f05c01] px-1.5 py-0.5 text-[9px] font-bold uppercase leading-none tracking-wider text-white">{{ __('nav.new') }}</span>
            </a>
            <a href="#about"
               class="inline-flex items-center gap-2 rounded-lg px-3 py-3 text-sm font-medium text-foreground transition-all duration-300 hover:bg-muted/50 hover:text-primary">{{ __('nav.about') }}</a>
            <a href="#contact"
               class="inline-flex items-center gap-2 rounded-lg px-3 py-3 text-sm font-medium text-foreground transition-all duration-300 hover:bg-muted/50 hover:text-primary">{{ __('nav.contact') }}</a>

            <div class="mt-3 flex items-center gap-2 px-3">
                @foreach ($supportedLocales as $locale => $meta)
                    @php
                        $localeUrl = $currentRouteName
                            ? route($currentRouteName, array_replace($currentRouteParameters, ['locale' => $locale]))
                            : url($locale);

                        if (filled($currentQueryString)) {
                            $localeUrl .= '?' . $currentQueryString;
                        }
                    @endphp

                    <a href="{{ $localeUrl }}"
                       class="rounded-lg border px-3 py-1.5 text-xs transition-all {{ $locale === $currentLocale ? 'border-primary bg-primary text-primary-foreground' : 'border-border text-muted-foreground hover:border-primary/40' }}">
                        {{ $meta['flag'] }} {{ $meta['short_label'] }}
                    </a>
                @endforeach
            </div>

            <a href="#packages"
               class="btn-premium mt-4 inline-flex h-10 items-center justify-center gap-2 rounded-xl border-0 bg-primary px-4 py-2 text-sm font-medium whitespace-nowrap text-primary-foreground transition-colors hover:bg-primary/90"
               style="background-color: rgb(50, 113, 107);">{{ __('nav.visit_egypt') }}</a>
            <a href="#contact"
               class="btn-premium mt-2 inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-medium whitespace-nowrap text-primary-foreground transition-colors hover:bg-primary/90">{{ __('nav.book_now') }}</a>
        </div>
    </div>
</header>
