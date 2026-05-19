@props([
    'items' => [],
    'variant' => 'default',
    'align' => 'start',
])

@php
    $isRtl = config('locales.supported.' . app()->getLocale() . '.direction') === 'rtl';
    $chevronPath = $isRtl ? 'm15 18-6-6 6-6' : 'm9 18 6-6-6-6';
    $isOverlay = $variant === 'overlay';
    $alignment = [
        'start' => 'justify-start',
        'center' => 'justify-center',
        'end' => 'justify-end',
    ][$align] ?? 'justify-start';
    $baseTextClass = $isOverlay ? 'text-primary-foreground/75' : 'text-muted-foreground';
    $linkClass = $isOverlay
        ? 'hover:text-primary-foreground focus-visible:text-primary-foreground'
        : 'hover:text-foreground focus-visible:text-foreground';
    $currentClass = $isOverlay ? 'text-primary-foreground' : 'text-foreground';
    $separatorClass = $isOverlay ? 'text-primary-foreground/45' : 'text-muted-foreground/55';
@endphp

<nav {{ $attributes->class("text-xs font-medium tracking-wide {$baseTextClass} md:text-sm") }} aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-1.5 {{ $alignment }} md:gap-2">
        <li>
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-1.5 rounded-full transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 {{ $linkClass }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="h-3.5 w-3.5">
                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
                    <path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                </svg>
                <span>{{ __('nav.home') }}</span>
            </a>
        </li>

        @foreach ($items as $item)
            @php
                $label = $item['label'] ?? '';
                $url = $item['url'] ?? null;
                $isCurrent = $loop->last || blank($url);
            @endphp

            <li class="flex items-center gap-1.5 md:gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="h-3.5 w-3.5 {{ $separatorClass }}" aria-hidden="true">
                    <path d="{{ $chevronPath }}"></path>
                </svg>

                @if ($isCurrent)
                    <span aria-current="page" class="font-semibold {{ $currentClass }}">{{ $label }}</span>
                @else
                    <a href="{{ $url }}"
                       class="rounded-full transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 {{ $linkClass }}">
                        {{ $label }}
                    </a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
