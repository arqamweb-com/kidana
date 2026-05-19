@extends('layout.master')
@section('title', __('services.index.title'))
@section('meta_description', __('services.index.meta_description'))

@section('content')
    <section class="relative h-[40vh] min-h-[320px] flex items-center justify-center overflow-hidden">
        <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=1200&amp;q=80" alt="{{ __('services.index.hero_alt') }}"
            class="absolute inset-0 w-full h-full object-cover">
        <div
            class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/80 via-[hsl(var(--hero-overlay))]/50 to-[hsl(var(--hero-overlay))]/30">
        </div>
        <div class="relative z-10 text-center text-primary-foreground px-4">
            <p class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('services.index.eyebrow') }}</p>
            <h1 class="text-4xl md:text-6xl font-bold">{{ __('services.index.title') }}</h1>
        </div>
    </section>

    <section class="section-padding bg-background">
        <div class="container mx-auto">
            @if ($services->isEmpty())
                <div class="rounded-3xl border border-dashed border-border/70 bg-muted/40 px-8 py-14 text-center">
                    <h2 class="text-2xl font-bold text-foreground">{{ __('services.index.empty_title') }}</h2>
                    <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
                        {{ __('services.index.empty_description') }}
                    </p>
                </div>
            @else
                <div class="grid gap-7 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($services as $service)
                        <a href="{{ route('services.show', ['service' => $service->slug]) }}"
                            class="group relative block h-80 overflow-hidden rounded-2xl border border-border/50 transition-all duration-500 hover:-translate-y-1 hover:shadow-xl">


                            @php
                                $serviceImage = $service->hero_image ?: $service->image_url;
                                $serviceImageUrl = blank($serviceImage)
                                    ? 'https://images.unsplash.com/photo-1503220317375-aaad61436b1b?w=1200&q=80'
                                    : (\Illuminate\Support\Str::startsWith($serviceImage, ['http://', 'https://'])
                                        ? $serviceImage
                                        : \Illuminate\Support\Facades\Storage::url($serviceImage));
                            @endphp


                            <img src="{{ $serviceImageUrl }}" alt="{{ $service->name }}"
                                class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">


                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/95 via-[hsl(var(--hero-overlay))]/45 to-[hsl(var(--hero-overlay))]/15">
                            </div>
                            <div class="absolute inset-x-0 bottom-0 p-7">
                                <h2 class="text-2xl font-bold text-primary-foreground">
                                    {{ $service->hero_title ?: $service->name }}
                                </h2>
                                <p class="mt-2 line-clamp-2 text-sm text-primary-foreground/75">
                                    {{ $service->hero_subtitle ?: $service->description }}
                                </p>
                                <span
                                    class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-primary-foreground">
                                    {{ __('services.index.view_service') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                        <path d="M5 12h14" />
                                        <path d="m12 5 7 7-7 7" />
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
