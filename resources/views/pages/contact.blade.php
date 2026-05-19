@extends('layout.master')
@section('title', __('contact.title'))
@section('meta_description', __('contact.meta_description'))
@section('content')
    <div class="min-h-screen">
        <section class="relative h-[40vh] min-h-[320px] flex items-center justify-center overflow-hidden"><img
                src="https://images.unsplash.com/photo-1572252009286-268acec5ca0a?w=1200&amp;q=80" alt="{{ __('contact.hero_alt') }}"
                class="absolute inset-0 w-full h-full object-cover">
            <div
                class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/80 via-[hsl(var(--hero-overlay))]/50 to-[hsl(var(--hero-overlay))]/30">
            </div>
            <div class="relative z-10 text-center text-primary-foreground px-4">
                <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('contact.eyebrow') }}</p>
                <h1 class="text-4xl md:text-6xl font-bold">{{ __('contact.title') }}</h1>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-5xl">
                <div class="grid md:grid-cols-2 gap-12">
                    <div>
                        <h2 class="text-2xl font-bold text-foreground mb-6">{{ __('contact.heading') }}</h2>
                        <p class="text-muted-foreground mb-8 leading-relaxed">{{ __('contact.description') }}</p>
                        <div class="space-y-5">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-map-pin w-5 h-5 text-primary">
                                        <path
                                            d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                                        </path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg></div>
                                <div>
                                    <p class="text-xs text-muted-foreground">{{ __('contact.details.address.label') }}</p>
                                    <p class="text-sm font-medium text-foreground">{{ __('contact.details.address.value') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-phone w-5 h-5 text-primary">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                        </path>
                                    </svg></div>
                                <div>
                                    <p class="text-xs text-muted-foreground">{{ __('contact.details.phone.label') }}</p>
                                    <p class="text-sm font-medium text-foreground">{{ __('contact.details.phone.value') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-mail w-5 h-5 text-primary">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </svg></div>
                                <div>
                                    <p class="text-xs text-muted-foreground">{{ __('contact.details.email.label') }}</p>
                                    <p class="text-sm font-medium text-foreground">{{ __('contact.details.email.value') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-clock w-5 h-5 text-primary">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg></div>
                                <div>
                                    <p class="text-xs text-muted-foreground">{{ __('contact.details.hours.label') }}</p>
                                    <p class="text-sm font-medium text-foreground">{{ __('contact.details.hours.value') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-muted/50 rounded-2xl p-8 border border-border/40">
                        <form class="space-y-5"><input type="text" placeholder="{{ __('contact.form.name') }}"
                                class="w-full px-4 py-3 rounded-xl bg-background border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"><input
                                type="email" placeholder="{{ __('contact.form.email') }}"
                                class="w-full px-4 py-3 rounded-xl bg-background border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"><input
                                type="tel" placeholder="{{ __('contact.form.phone') }}"
                                class="w-full px-4 py-3 rounded-xl bg-background border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                            <textarea placeholder="{{ __('contact.form.message') }}" rows="4"
                                class="w-full px-4 py-3 rounded-xl bg-background border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-none"></textarea><button
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 px-4 w-full btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl py-6 font-semibold">{{ __('contact.form.submit') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
