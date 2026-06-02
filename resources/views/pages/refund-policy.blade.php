@extends('layout.master')
@section('title', __('refund_policy.title'))
@section('meta_description', __('refund_policy.meta_description'))

@section('content')
    <div class="min-h-screen">
        <section class="relative h-[40vh] min-h-[320px] flex items-center justify-center overflow-hidden">
            <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=1600&q=80"
                 alt="{{ __('refund_policy.title') }}"
                 class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/80 via-[hsl(var(--hero-overlay))]/50 to-[hsl(var(--hero-overlay))]/30"></div>
            <div class="relative z-10 text-center text-primary-foreground px-4">
                <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('refund_policy.eyebrow') }}</p>
                <h1 class="text-4xl md:text-6xl font-bold">{{ __('refund_policy.title') }}</h1>
                <x-breadcrumbs
                    variant="overlay"
                    align="center"
                    class="mt-6"
                    :items="[
                        ['label' => __('refund_policy.title')],
                    ]"
                />
            </div>
        </section>

        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-5xl">
                <div class="max-w-3xl mx-auto">
                    <div class="mb-12 pb-10 border-b border-border/60">
                        @foreach(__('refund_policy.intro') as $paragraph)
                            <p class="text-muted-foreground leading-relaxed mb-4 last:mb-0">{{ $paragraph }}</p>
                        @endforeach
                    </div>

                    <div class="space-y-10">
                        @foreach(__('refund_policy.sections') as $index => $section)
                            <section class="group">
                                <h2 class="text-xl md:text-2xl font-bold text-foreground mb-4 flex items-baseline gap-3">
                                    <span class="text-primary text-sm font-semibold tracking-[0.2em]">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span>{{ $section['title'] }}</span>
                                </h2>
                                <div class="pl-0 md:pl-10">
                                    @foreach($section['paragraphs'] ?? [] as $paragraph)
                                        <p class="text-muted-foreground leading-relaxed mb-4 last:mb-0">{{ $paragraph }}</p>
                                    @endforeach
                                    @if(!empty($section['items']))
                                        <ul class="mt-3 space-y-2">
                                            @foreach($section['items'] as $item)
                                                <li class="flex items-start gap-3 text-muted-foreground">
                                                    <span class="mt-2 w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span>
                                                    <span class="leading-relaxed">{{ $item }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </section>
                        @endforeach
                    </div>

                    <div class="mt-14 pt-10 border-t border-border/60 text-center">
                        <p class="text-foreground font-semibold text-lg">Kidana Travel</p>
                        <p class="text-muted-foreground italic mt-1">{{ __('refund_policy.footer_tagline') }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
