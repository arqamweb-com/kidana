@extends('layout.master')
@section('title', __('about.title'))
@section('meta_description', __('about.meta_description'))
@section('content')
    <section class="relative h-[40vh] min-h-80 flex items-center justify-center overflow-hidden"><img
            src="https://images.unsplash.com/photo-1572252009286-268acec5ca0a?w=1200&amp;q=80" alt="{{ __('about.hero_alt') }}"
            class="absolute inset-0 w-full h-full object-cover">
        <div
            class="absolute inset-0 bg-linear-to-t from-[hsl(var(--hero-overlay))]/80 via-[hsl(var(--hero-overlay))]/50 to-[hsl(var(--hero-overlay))]/30">
        </div>
        <div class="relative z-10 text-center text-primary-foreground px-4">
            <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('about.eyebrow') }}</p>
            <h1 class="text-4xl md:text-6xl font-bold">{{ __('about.title') }}</h1>
        </div>
    </section>
    <section class="section-padding bg-background">
        <div class="container mx-auto max-w-5xl">
            <div class="space-y-8">
                @foreach (__('about.intro') as $paragraph)
                    <p class="text-muted-foreground text-lg leading-relaxed">{{ $paragraph }}</p>
                @endforeach
                <div class="grid md:grid-cols-2 gap-6 mt-12">
                    <div class="p-6 rounded-xl bg-muted/50 border border-border/40">
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('about.mission.title') }}</h3>
                        <p class="text-muted-foreground text-sm leading-relaxed">{{ __('about.mission.description') }}</p>
                    </div>
                    <div class="p-6 rounded-xl bg-muted/50 border border-border/40">
                        <h3 class="text-lg font-bold text-foreground mb-2">{{ __('about.vision.title') }}</h3>
                        <p class="text-muted-foreground text-sm leading-relaxed">{{ __('about.vision.description') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
