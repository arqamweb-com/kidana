@extends('layout.master')

@section('title', 'Destinations')
@section('meta_description', 'Explore active Kidana Travel destinations and find packages for your next journey.')

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
    @endphp

    <section class="relative h-[40vh] min-h-[320px] flex items-center justify-center overflow-hidden">
        <img
            src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=1600&q=80"
            alt="Destinations"
            class="absolute inset-0 h-full w-full object-cover">
        <div
            class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/85 via-[hsl(var(--hero-overlay))]/55 to-[hsl(var(--hero-overlay))]/25">
        </div>
        <div class="relative z-10 px-4 text-center text-primary-foreground">
            <p class="mb-4 text-xs font-semibold uppercase tracking-[0.3em] text-primary">Explore</p>
            <h1 class="text-4xl font-bold md:text-6xl">All Destinations</h1>
            <p class="mx-auto mt-5 max-w-2xl text-sm leading-relaxed text-primary-foreground/75 md:text-base">
                Browse every active destination available from the dashboard.
            </p>
        </div>
    </section>

    <section class="section-padding bg-card">
        <div class="container mx-auto">
            <div class="mb-12 text-center">
                <p class="text-muted-foreground">
                    {{ $destinations->count() }} destination{{ $destinations->count() === 1 ? '' : 's' }} available now
                </p>
            </div>

            @if ($destinations->isEmpty())
                <div class="rounded-3xl border border-dashed border-border/70 bg-background/70 px-8 py-14 text-center">
                    <h3 class="text-2xl font-bold text-foreground">No destinations available yet</h3>
                    <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
                        Add active destinations from the dashboard and they will appear here automatically.
                    </p>
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($destinations as $destination)
                        @php
                            $destinationImageUrl = $resolveImageUrl(
                                $destination->image_url,
                                asset('kidana-home-assets/egypt-pyramids.jpg'),
                            );
                        @endphp

                        <a href="{{ route('destinations.show', ['destination' => $destination->slug]) }}"
                           class="group relative h-80 overflow-hidden rounded-2xl premium-shadow transition-all duration-500 hover:-translate-y-1 hover:premium-shadow-hover">
                            <img
                                src="{{ $destinationImageUrl }}"
                                alt="{{ $destination->name }}"
                                loading="lazy"
                                class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/80 via-[hsl(var(--hero-overlay))]/10 to-transparent transition-all duration-500 group-hover:from-[hsl(var(--hero-overlay))]/90 group-hover:via-[hsl(var(--hero-overlay))]/30">
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-6 transition-all duration-500 group-hover:pb-8">
                                <h2 class="text-xl font-bold text-primary-foreground transition-all duration-300 group-hover:text-primary">
                                    {{ $destination->name }}
                                </h2>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
