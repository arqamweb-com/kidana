@extends('layout.master')

@section('title', $destination->name)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags((string) $destination->description), 155))

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

        $destinationImageUrl = $resolveImageUrl(
            $destination->image_url,
            'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=1200&q=80',
        );
    @endphp

    <section class="relative h-[45vh] min-h-[360px] flex items-center justify-center overflow-hidden">
        <img src="{{ $destinationImageUrl }}" alt="{{ $destination->name }}" class="absolute inset-0 h-full w-full object-cover">
        <div
            class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/85 via-[hsl(var(--hero-overlay))]/55 to-[hsl(var(--hero-overlay))]/25">
        </div>
        <div class="relative z-10 mx-auto max-w-4xl px-4 text-center text-primary-foreground">
            <a href="{{ route('packages') }}"
                class="mb-6 inline-flex items-center gap-2 rounded-full border border-primary-foreground/30 bg-primary-foreground/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] transition-colors hover:bg-primary-foreground hover:text-secondary">
                <span>&larr;</span>
                <span>Back to packages</span>
            </a>
            <p class="mb-4 text-xs font-semibold uppercase tracking-[0.3em] text-primary">Destination</p>
            <h1 class="text-4xl font-bold leading-tight md:text-6xl">{{ $destination->name }}</h1>
        </div>
    </section>

    <section class="section-padding bg-background">
        <div class="mb-12 text-center">
            <p class="mx-auto max-w-2xl text-sm leading-relaxed text-muted-foreground md:text-base">
                Explore active travel packages available for {{ $destination->name }}.
            </p>
            <p class="mt-3 text-sm text-muted-foreground">
                {{ $packages->count() }} package{{ $packages->count() === 1 ? '' : 's' }} available now
            </p>
        </div>

        @include('sections.packages-grid', ['packages' => $packages])
    </section>
@endsection
