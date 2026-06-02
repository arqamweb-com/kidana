<footer class="bg-foreground text-primary-foreground/70 py-20">
    <div class="container mx-auto px-4 md:px-8">
        <div class="grid md:grid-cols-5 gap-10 mb-14">
            <div class="md:col-span-1"><img src="https://www.arqamweb.com/wp-content/uploads/2026/04/Logo-Web.png"
                    alt="Kidana Travel" class="h-11 w-auto mb-5 brightness-0 invert">
                <p class="text-sm leading-relaxed">{{ __('footer.description') }}</p>
            </div>
            <div>
                <h4 class="font-bold text-primary-foreground mb-5 text-sm tracking-wide uppercase">{{ __('footer.quick_links') }}
                </h4>
                <div class="flex flex-col gap-3 text-sm">
                    <a href="{{ route('home') }}" class="hover:text-primary transition-colors duration-300">{{ __('nav.home') }}</a>
                    <a href="{{ route('services.index') }}" class="hover:text-primary transition-colors duration-300">{{ __('nav.services') }}</a>
                    <a href="{{ route('packages') }}" class="hover:text-primary transition-colors duration-300">{{ __('nav.packages') }}</a>
                    <a href="{{ route('about') }}" class="hover:text-primary transition-colors duration-300">{{ __('nav.about') }}</a>
                    <a href="{{ route('contact') }}" class="hover:text-primary transition-colors duration-300">{{ __('nav.contact') }}</a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-primary-foreground mb-5 text-sm tracking-wide uppercase">{{ __('footer.services.title') }}
                </h4>
                <div class="flex flex-col gap-3 text-sm">
                    @foreach ($footerServices as $service)
                        <a href="{{ route('services.show', ['service' => $service->slug]) }}"
                           class="hover:text-primary transition-colors duration-300">{{ $service->name }}</a>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 class="font-bold text-primary-foreground mb-5 text-sm tracking-wide uppercase">
                    {{ __('footer.destinations.title') }}</h4>
                <div class="flex flex-col gap-3 text-sm">
                    @foreach ($footerDestinations as $destination)
                        <a href="{{ route('destinations.show', ['destination' => $destination->slug]) }}"
                           class="hover:text-primary transition-colors duration-300">{{ $destination->name }}</a>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 class="font-bold text-primary-foreground mb-5 text-sm tracking-wide uppercase">{{ __('footer.contact') }}
                </h4>
                <div class="flex flex-col gap-4 text-sm"><span class="flex items-start gap-3"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-map-pin w-4 h-4 shrink-0 mt-0.5">
                            <path
                                d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                            </path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg><span>{{ __('footer.address') }}</span></span><a href="tel:+201033455433"
                        class="flex items-center gap-3 hover:text-primary transition-colors duration-300"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-phone w-4 h-4 shrink-0">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                            </path>
                        </svg> +201033455433</a><a href="mailto:info@kidanatravel.com"
                        class="flex items-center gap-3 hover:text-primary transition-colors duration-300"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-mail w-4 h-4 shrink-0">
                            <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        </svg> info@kidanatravel.com</a></div>
            </div>
        </div>
        <div class="border-t pt-10 text-center text-sm" style="border-top-color: #ffffff1a">
            <div class="mb-6 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs tracking-wide">
                <a class="hover:text-primary transition-colors duration-300"
                   href="{{ route('terms-and-conditions') }}">{{ __('nav.terms_and_conditions') }}</a>
                <span class="text-primary-foreground/30">|</span>
                <a class="hover:text-primary transition-colors duration-300"
                   href="{{ route('privacy-policy') }}">{{ __('nav.privacy_policy') }}</a>
                <span class="text-primary-foreground/30">|</span>
                <a class="hover:text-primary transition-colors duration-300"
                   href="{{ route('refund-policy') }}">{{ __('nav.refund_policy') }}</a>
            </div>
            <div
                class="mb-5 flex flex-wrap items-center justify-center gap-x-3 gap-y-2 text-xs tracking-[0.15em] uppercase text-primary-foreground/60">
                <span><span class="text-primary-foreground/80 font-semibold">{{ __('footer.tourism_license') }}</span>
                    00000000</span><span class="text-primary-foreground/30">|</span><span><span
                        class="text-primary-foreground/80 font-semibold">{{ __('footer.commercial_registration') }}</span>
                    00000000</span><span class="text-primary-foreground/30">|</span><span><span
                        class="text-primary-foreground/80 font-semibold">{{ __('footer.tax_id') }}</span> 00000000</span>
            </div>©
            {{ __('footer.copyright') }} <a href="https://www.arqamweb.com"
                target="_blank" rel="noopener noreferrer"
                class="text-primary hover:underline transition-colors duration-300">Arqam Web</a>.
        </div>
    </div>
</footer>
