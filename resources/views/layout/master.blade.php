@php
    $route = request()->route();
    $routeName = $route?->getName();
    $routeParameters = $route?->parameters() ?? [];
    $pageIdentifier = $routeName === 'home'
        ? 'home'
        : collect($routeParameters)
            ->first(fn ($parameter): bool => is_object($parameter) && filled($parameter->slug))
            ?->slug;

    $pageIdentifier ??= $routeName
        ? str_replace('.', '-', $routeName)
        : trim(request()->path(), '/');
@endphp

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ config('locales.supported.' . app()->getLocale() . '.direction', 'ltr') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description"
          content="@yield('meta_description', config('app.name') . ' premium travel packages and services.')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap">

    <link rel="canonical" href="@yield('canonical', url()->current())">
    @if ($routeName)
        @foreach (config('locales.supported', []) as $alternateLocale => $localeConfig)
            <link rel="alternate" hreflang="{{ $alternateLocale }}"
                  href="{{ route($routeName, array_merge($routeParameters, ['locale' => $alternateLocale])) }}">
        @endforeach
    @endif

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', config('app.name'))">
    <meta property="og:description"
          content="@yield('meta_description', config('app.name') . ' premium travel packages and services.')">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    @hasSection('seo_image')
        <meta property="og:image" content="@yield('seo_image')">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', config('app.name'))">
    <meta name="twitter:description"
          content="@yield('meta_description', config('app.name') . ' premium travel packages and services.')">
    @hasSection('seo_image')
        <meta name="twitter:image" content="@yield('seo_image')">
    @endif

    @stack('head')

    <title>@yield('title', config('app.name'))</title>
</head>

<body data-page="{{ $pageIdentifier }}">
@include('layout.navbar')

@yield('content')

@include('layout.footer')
</body>

</html>
