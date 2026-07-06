@extends('layout.master')
@section('title', __('book_now.title'))
@section('meta_description', __('book_now.meta_description'))
@section('content')

    <div class="min-h-screen">
        <section class="relative h-[60vh] min-h-[440px] flex items-center justify-center overflow-hidden">
            <img src="{{ asset('kidana-home-assets/saudi-mecca-C_VP-LnJ.jpg') }}" alt="{{ __('book_now.hero.image_alt') }}"
                 class="absolute inset-0 w-full h-full object-cover scale-105">
            <div
                class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/85 via-[hsl(var(--hero-overlay))]/55 to-[hsl(var(--hero-overlay))]/95"></div>
            <div class="relative z-10 text-center text-primary-foreground px-4 max-w-3xl mx-auto animate-fade-in-up"><p
                    class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-5">{{ __('book_now.hero.eyebrow') }}</p>
                <h1 class="text-4xl md:text-6xl font-bold mb-5 leading-tight">{{ __('book_now.hero.title_before') }} <span
                        class="text-primary">{{ __('book_now.hero.brand') }}</span></h1>
                <p class="text-primary-foreground/85 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">{{ __('book_now.hero.description') }}</p>
                <x-breadcrumbs
                    variant="overlay"
                    align="center"
                    class="mt-10"
                    :items="[
                        ['label' => __('nav.book_now')],
                    ]"
                />
            </div>
        </section>
        <section class="sticky top-[68px] z-30 bg-card/95 backdrop-blur border-b border-border/60 shadow-sm">
            <div class="container mx-auto px-4 py-4">
                <form action="{{ route('packages.search') }}" method="GET"
                      class="grid grid-cols-1 md:grid-cols-[1fr_1fr_1fr_auto] gap-3">
                    <div class="flex items-center gap-3 bg-background border border-border rounded-xl px-4 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-search w-4 h-4 text-primary shrink-0">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                        <input name="destination" list="book-now-destinations" value="{{ request('destination') }}"
                               placeholder="{{ __('book_now.search.destination_placeholder') }}" class="bg-transparent outline-none text-sm w-full">
                        <datalist id="book-now-destinations">
                            @foreach ($destinations as $destination)
                                <option value="{{ $destination->slug }}">{{ $destination->name }}</option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="flex items-center gap-3 bg-background border border-border rounded-xl px-4 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-calendar w-4 h-4 text-primary shrink-0">
                            <path d="M8 2v4"></path>
                            <path d="M16 2v4"></path>
                            <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                            <path d="M3 10h18"></path>
                        </svg>
                        <input type="date" name="travel_date" value="{{ request('travel_date') }}"
                               class="bg-transparent outline-none text-sm w-full [color-scheme:light] cursor-pointer"></div>
                    <div class="flex items-center gap-3 bg-background border border-border rounded-xl px-4 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-users w-4 h-4 text-primary shrink-0">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <input type="number" name="guests" min="1" max="50" value="{{ request('guests') }}"
                               placeholder="{{ __('book_now.search.travelers_placeholder') }}" class="bg-transparent outline-none text-sm w-full"></div>
                    <button type="submit"
                        class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 py-2 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-6 gap-2 h-auto cursor-pointer">
                        {{ __('book_now.search.submit') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right w-4 h-4">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-6xl">
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

                    $fallbackImage = asset('kidana-home-assets/egypt-pyramids.jpg');
                    $tabClass = 'inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 ring-offset-background transition-all data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 py-3 text-sm font-semibold cursor-pointer';
                    $selectClass = 'flex h-11 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 cursor-pointer';
                    $labelClass = 'font-medium text-xs uppercase tracking-wider text-muted-foreground mb-2 block';
                    $inputClass = 'flex h-11 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2';
                    $customActive = $errors->any() || session('custom_package_success');
                @endphp

                <div data-booknow-tabs>
                    <div role="tablist" aria-orientation="horizontal"
                         class="items-center justify-center rounded-md text-muted-foreground grid grid-cols-2 md:grid-cols-4 w-full max-w-3xl mx-auto h-auto p-1.5 mb-12 bg-muted">
                        <button type="button" role="tab" data-booknow-tab="packages"
                                aria-selected="{{ $customActive ? 'false' : 'true' }}"
                                data-state="{{ $customActive ? 'inactive' : 'active' }}" class="{{ $tabClass }}">{{ __('book_now.tabs.packages') }}
                        </button>
                        <button type="button" role="tab" data-booknow-tab="custom"
                                aria-selected="{{ $customActive ? 'true' : 'false' }}"
                                data-state="{{ $customActive ? 'active' : 'inactive' }}" class="{{ $tabClass }}">{{ __('book_now.tabs.custom') }}
                        </button>
                        <button type="button" role="tab" data-booknow-tab="services" aria-selected="false"
                                data-state="inactive" class="{{ $tabClass }}">{{ __('book_now.tabs.services') }}
                        </button>
                        <button type="button" role="tab" data-booknow-tab="destinations" aria-selected="false"
                                data-state="inactive" class="{{ $tabClass }}">{{ __('book_now.tabs.destinations') }}
                        </button>
                    </div>

                    <div data-booknow-panel="packages" role="tabpanel" @if ($customActive) hidden @endif class="mt-2 animate-fade-in">
                        <div class="bg-card rounded-2xl border border-border/60 p-5 mb-10 shadow-sm">
                            <div class="grid md:grid-cols-3 gap-4">
                                <div>
                                    <label class="{{ $labelClass }}">{{ __('book_now.filters.travel_type') }}</label>
                                    <select data-package-filter="type" class="{{ $selectClass }}">
                                        <option value="">{{ __('book_now.filters.all') }}</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->slug }}">{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="{{ $labelClass }}">{{ __('book_now.filters.budget_range') }}</label>
                                    <select data-package-filter="budget" class="{{ $selectClass }}">
                                        <option value="">{{ __('book_now.filters.any_budget') }}</option>
                                        <option value="0-100000">{{ __('book_now.filters.budget_under') }}</option>
                                        <option value="100000-200000">{{ __('book_now.filters.budget_mid') }}</option>
                                        <option value="200000-">{{ __('book_now.filters.budget_above') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="{{ $labelClass }}">{{ __('book_now.filters.duration') }}</label>
                                    <select data-package-filter="duration" class="{{ $selectClass }}">
                                        <option value="">{{ __('book_now.filters.any_duration') }}</option>
                                        <option value="0-5">{{ __('book_now.filters.duration_short') }}</option>
                                        <option value="6-9">{{ __('book_now.filters.duration_medium') }}</option>
                                        <option value="10-">{{ __('book_now.filters.duration_long') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" data-package-grid>
                            @forelse ($packages as $package)
                                @php
                                    $packageImageUrl = $resolveImageUrl($package->image_url, $fallbackImage);
                                    $packageTags = collect($package->tags ?? [])->filter()->take(2);
                                @endphp
                                <div class="book-now-package-card group flex flex-col bg-card rounded-2xl overflow-hidden border border-border/50 transition-all duration-300 hover:-translate-y-2 hover:scale-[1.01]"
                                     data-service="{{ $package->service?->slug }}"
                                     data-price="{{ (int) $package->price }}"
                                     data-days="{{ (int) $package->days }}"
                                     style="box-shadow: 0 4px 24px -6px hsl(var(--foreground) / 0.1), 0 12px 48px -12px hsl(var(--foreground) / 0.08);">
                                    <div class="relative h-56 overflow-hidden">
                                        <a href="{{ route('packages.show', ['package' => $package->slug]) }}"
                                           class="block w-full h-full">
                                            <img src="{{ $packageImageUrl }}" alt="{{ $package->name }}" loading="lazy"
                                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                        </a>
                                        @if ($packageTags->isNotEmpty())
                                            <div class="absolute top-4 left-4 flex flex-wrap gap-1.5">
                                                @foreach ($packageTags as $tag)
                                                    <span class="bg-primary-foreground/90 backdrop-blur text-secondary text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">{{ $tag }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if ($package->price > 0)
                                            <div class="absolute top-4 right-4 bg-primary text-primary-foreground text-sm font-bold px-4 py-2 rounded-xl shadow-lg">
                                                {{ __('book_now.packages.price_from', ['price' => number_format((float) $package->price, 0)]) }}
                                            </div>
                                        @else
                                            <div class="absolute top-4 right-4 bg-secondary text-secondary-foreground text-xs font-bold px-3 py-2 rounded-xl shadow-lg uppercase tracking-wider">
                                                {{ __('packages.show.on_request') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col flex-1 p-6">
                                        <h3 class="text-lg font-bold text-foreground mb-3">
                                            <a href="{{ route('packages.show', ['package' => $package->slug]) }}"
                                               class="transition-colors hover:text-primary">{{ $package->name }}</a>
                                        </h3>
                                        <div class="flex items-center gap-4 text-muted-foreground text-sm mb-4">
                                            @if (filled($package->location_label) || $package->destination?->name)
                                                <span class="flex items-center gap-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                         class="lucide lucide-map-pin w-4 h-4 text-secondary"><path
                                                            d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle
                                                            cx="12" cy="10" r="3"></circle></svg>
                                                    {{ $package->location_label ?: $package->destination?->name }}
                                                </span>
                                            @endif
                                            @if (filled($package->days))
                                                <span class="flex items-center gap-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                         class="lucide lucide-clock w-4 h-4 text-secondary"><circle
                                                            cx="12" cy="12" r="10"></circle><polyline
                                                            points="12 6 12 12 16 14"></polyline></svg>
                                                    {{ __('packages.card.days', ['count' => $package->days]) }}
                                                </span>
                                            @endif
                                        </div>
                                        @if ($package->description)
                                            <p class="text-muted-foreground text-sm leading-relaxed mb-5 flex-1 line-clamp-3">{{ $package->description }}</p>
                                        @endif
                                        <div class="flex gap-2">
                                            <a class="flex-1" href="{{ route('packages.show', ['package' => $package->slug]) }}">
                                                <button class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 w-full rounded-xl gap-1.5 text-xs font-semibold py-5 cursor-pointer">
                                                    {{ __('packages.card.view_details') }}
                                                </button>
                                            </a>
                                            <a class="flex-1" href="{{ route('packages.show', ['package' => $package->slug]) }}#pkg-cta">
                                                <button class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 px-4 w-full btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl gap-1.5 text-xs font-semibold py-5 cursor-pointer">
                                                    {{ __('book_now.packages.book_now') }}
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                         class="lucide lucide-arrow-right w-3.5 h-3.5">
                                                        <path d="M5 12h14"></path>
                                                        <path d="m12 5 7 7-7 7"></path>
                                                    </svg>
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full rounded-3xl border border-dashed border-border/70 bg-muted/40 px-8 py-14 text-center">
                                    <h3 class="text-2xl font-bold text-foreground">{{ __('packages.index.empty_title') }}</h3>
                                    <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">{{ __('packages.index.empty_description') }}</p>
                                </div>
                            @endforelse
                        </div>

                        <div data-package-empty class="hidden rounded-3xl border border-dashed border-border/70 bg-muted/40 px-8 py-14 text-center">
                            <h3 class="text-2xl font-bold text-foreground">{{ __('packages.index.filter_empty_title') }}</h3>
                            <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">{{ __('packages.index.filter_empty_description') }}</p>
                        </div>
                    </div>

                    <div data-booknow-panel="custom" role="tabpanel" @unless ($customActive) hidden @endunless class="mt-2 animate-fade-in">
                        <div class="bg-card rounded-3xl border border-border/60 p-6 md:p-10 shadow-sm max-w-3xl mx-auto">
                            <div class="text-center mb-8">
                                <h3 class="text-2xl font-bold text-foreground mb-2">{{ __('book_now.custom.title') }}</h3>
                                <p class="mx-auto max-w-xl text-sm leading-relaxed text-muted-foreground">
                                    {{ __('book_now.custom.description') }}
                                </p>
                            </div>

                            @if (session('custom_package_success'))
                                <div class="mb-8 rounded-xl border border-green-500/30 bg-green-500/10 px-5 py-4 text-sm font-medium text-green-700">
                                    {{ session('custom_package_success') }}
                                </div>
                            @endif

                            <div class="mb-10 flex items-center justify-center gap-2 md:gap-4" aria-hidden="true">
                                @foreach (__('book_now.custom.steps') as $stepIndex => $stepLabel)
                                    <div class="flex items-center gap-2 md:gap-4">
                                        <div data-custom-step-indicator="{{ $stepIndex + 1 }}"
                                             class="flex items-center gap-2 {{ $stepIndex === 0 ? 'text-primary' : 'text-muted-foreground' }}">
                                            <span data-custom-step-circle
                                                  class="flex h-9 w-9 items-center justify-center rounded-full border-2 text-sm font-bold transition-colors {{ $stepIndex === 0 ? 'border-primary bg-primary text-primary-foreground' : 'border-border bg-background' }}">{{ $stepIndex + 1 }}</span>
                                            <span class="hidden text-sm font-semibold sm:inline">{{ $stepLabel }}</span>
                                        </div>
                                        @if (! $loop->last)
                                            <span class="h-px w-6 bg-border md:w-12"></span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <form action="{{ route('book-now.custom-package', ['locale' => app()->getLocale()]) }}"
                                  method="POST" data-custom-form data-custom-start-step="{{ $errors->any() ? 3 : 1 }}">
                                @csrf

                                <div data-custom-step="1" class="space-y-5">
                                    <div class="grid md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="{{ $labelClass }}">{{ __('book_now.custom.destination') }}</label>
                                            <input type="text" name="destination" list="custom-destinations"
                                                   value="{{ old('destination') }}" placeholder="{{ __('book_now.custom.destination_placeholder') }}"
                                                   class="{{ $inputClass }}">
                                            <datalist id="custom-destinations">
                                                @foreach ($destinations as $destination)
                                                    <option value="{{ $destination->name }}"></option>
                                                @endforeach
                                            </datalist>
                                        </div>
                                        <div>
                                            <label class="{{ $labelClass }}">{{ __('book_now.custom.travel_type') }}</label>
                                            <select name="travel_type" class="{{ $selectClass }}">
                                                <option value="">{{ __('book_now.custom.any') }}</option>
                                                @foreach ($services as $service)
                                                    <option value="{{ $service->name }}" @selected(old('travel_type') === (string) $service->name)>{{ $service->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="{{ $labelClass }}">{{ __('book_now.custom.travelers') }}</label>
                                            <input type="number" name="travelers" min="1" max="100"
                                                   value="{{ old('travelers') }}" placeholder="2" class="{{ $inputClass }}">
                                        </div>
                                        <div>
                                            <label class="{{ $labelClass }}">{{ __('book_now.custom.preferred_date') }}</label>
                                            <input type="date" name="travel_date" value="{{ old('travel_date') }}"
                                                   class="{{ $inputClass }} [color-scheme:light] cursor-pointer">
                                        </div>
                                    </div>
                                </div>

                                <div data-custom-step="2" hidden class="space-y-5">
                                    <div class="grid md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="{{ $labelClass }}">{{ __('book_now.custom.budget_range') }}</label>
                                            <select name="budget" class="{{ $selectClass }}">
                                                <option value="">{{ __('book_now.filters.any_budget') }}</option>
                                                <option value="Under EGP 100,000" @selected(old('budget') === 'Under EGP 100,000')>{{ __('book_now.filters.budget_under') }}</option>
                                                <option value="EGP 100,000 – 200,000" @selected(old('budget') === 'EGP 100,000 – 200,000')>{{ __('book_now.filters.budget_mid') }}</option>
                                                <option value="Above EGP 200,000" @selected(old('budget') === 'Above EGP 200,000')>{{ __('book_now.filters.budget_above') }}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="{{ $labelClass }}">{{ __('book_now.custom.accommodation') }}</label>
                                            <select name="accommodation" class="{{ $selectClass }}">
                                                <option value="">{{ __('book_now.custom.any') }}</option>
                                                <option value="3-Star" @selected(old('accommodation') === '3-Star')>{{ __('book_now.custom.accommodations.3-Star') }}</option>
                                                <option value="4-Star" @selected(old('accommodation') === '4-Star')>{{ __('book_now.custom.accommodations.4-Star') }}</option>
                                                <option value="5-Star" @selected(old('accommodation') === '5-Star')>{{ __('book_now.custom.accommodations.5-Star') }}</option>
                                                <option value="Luxury" @selected(old('accommodation') === 'Luxury')>{{ __('book_now.custom.accommodations.Luxury') }}</option>
                                            </select>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="{{ $labelClass }}">{{ __('book_now.custom.duration') }}</label>
                                            <select name="duration" class="{{ $selectClass }}">
                                                <option value="">{{ __('book_now.filters.any_duration') }}</option>
                                                <option value="1 – 5 Days" @selected(old('duration') === '1 – 5 Days')>{{ __('book_now.filters.duration_short') }}</option>
                                                <option value="6 – 9 Days" @selected(old('duration') === '6 – 9 Days')>{{ __('book_now.filters.duration_medium') }}</option>
                                                <option value="10+ Days" @selected(old('duration') === '10+ Days')>{{ __('book_now.filters.duration_long') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div data-custom-step="3" hidden class="space-y-5">
                                    <div class="grid md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="{{ $labelClass }}">{{ __('book_now.custom.full_name') }}</label>
                                            <input type="text" name="name" value="{{ old('name') }}" required
                                                   placeholder="{{ __('book_now.custom.name_placeholder') }}"
                                                   class="{{ $inputClass }} {{ $errors->has('name') ? 'border-red-400' : '' }}">
                                            @error('name')
                                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="{{ $labelClass }}">{{ __('book_now.custom.email') }}</label>
                                            <input type="email" name="email" value="{{ old('email') }}" required
                                                   placeholder="{{ __('book_now.custom.email_placeholder') }}"
                                                   class="{{ $inputClass }} {{ $errors->has('email') ? 'border-red-400' : '' }}">
                                            @error('email')
                                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="{{ $labelClass }}">{{ __('book_now.custom.phone') }}</label>
                                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                                   placeholder="{{ __('book_now.custom.phone_placeholder') }}" class="{{ $inputClass }}">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="{{ $labelClass }}">{{ __('book_now.custom.notes') }}</label>
                                            <textarea name="notes" rows="4" placeholder="{{ __('book_now.custom.notes_placeholder') }}"
                                                      class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 resize-none">{{ old('notes') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 flex items-center justify-between gap-4">
                                    <button type="button" data-custom-back hidden
                                            class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-11 rounded-xl px-6 text-sm font-semibold gap-2 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" class="lucide lucide-arrow-left w-4 h-4">
                                            <path d="m12 19-7-7 7-7"></path>
                                            <path d="M19 12H5"></path>
                                        </svg>
                                        {{ __('book_now.custom.back') }}
                                    </button>
                                    <button type="button" data-custom-next
                                            class="ml-auto inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-11 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-8 text-sm font-semibold gap-2 cursor-pointer">
                                        {{ __('book_now.custom.next') }}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4">
                                            <path d="M5 12h14"></path>
                                            <path d="m12 5 7 7-7 7"></path>
                                        </svg>
                                    </button>
                                    <button type="submit" data-custom-submit hidden
                                            class="ml-auto inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-11 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-8 text-sm font-semibold gap-2 cursor-pointer">
                                        {{ __('book_now.custom.submit') }}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" class="lucide lucide-send w-4 h-4">
                                            <path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"></path>
                                            <path d="m21.854 2.147-10.94 10.939"></path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div data-booknow-panel="services" role="tabpanel" hidden class="mt-2 animate-fade-in">
                        <div class="grid md:grid-cols-2 gap-7">
                            @forelse ($services as $service)
                                @php
                                    $serviceImageUrl = $resolveImageUrl(
                                        $service->image_url ?: $service->hero_image,
                                        $fallbackImage,
                                    );
                                @endphp
                                <a href="{{ route('services.show', ['service' => $service->slug]) }}"
                                   class="group relative block h-72 cursor-pointer overflow-hidden rounded-2xl premium-shadow transition-all duration-500 hover:premium-shadow-hover md:h-80">
                                    <img src="{{ $serviceImageUrl }}" alt="{{ $service->name }}" loading="lazy"
                                         class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/95 via-[hsl(var(--hero-overlay))]/40 to-[hsl(var(--hero-overlay))]/10 transition-all duration-500 group-hover:via-[hsl(var(--hero-overlay))]/50"></div>
                                    <div class="absolute inset-x-0 bottom-0 p-7 md:p-9">
                                        <h3 class="mb-2 text-xl font-bold text-primary-foreground md:text-2xl">{{ $service->name }}</h3>
                                        <p class="max-w-md translate-y-4 text-sm leading-relaxed text-primary-foreground/70 opacity-0 transition-all duration-500 group-hover:translate-y-0 group-hover:opacity-100">{{ $service->description ?: __('home.services.fallback_description') }}</p>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-full rounded-3xl border border-dashed border-border/70 bg-muted/40 px-8 py-14 text-center">
                                    <h3 class="text-2xl font-bold text-foreground">{{ __('home.services.empty_title') }}</h3>
                                    <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">{{ __('home.services.empty_description') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div data-booknow-panel="destinations" role="tabpanel" hidden class="mt-2 animate-fade-in">
                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            @forelse ($destinations as $destination)
                                @php
                                    $destinationImageUrl = $resolveImageUrl($destination->image_url, $fallbackImage);
                                @endphp
                                <a href="{{ route('destinations.show', ['destination' => $destination->slug]) }}"
                                   class="group relative h-80 overflow-hidden rounded-2xl premium-shadow transition-all duration-500 hover:-translate-y-1 hover:premium-shadow-hover">
                                    <img src="{{ $destinationImageUrl }}" alt="{{ $destination->name }}" loading="lazy"
                                         class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-[hsl(var(--hero-overlay))]/80 via-[hsl(var(--hero-overlay))]/10 to-transparent transition-all duration-500 group-hover:from-[hsl(var(--hero-overlay))]/90 group-hover:via-[hsl(var(--hero-overlay))]/30"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-6 transition-all duration-500 group-hover:pb-8">
                                        <h3 class="text-xl font-bold text-primary-foreground transition-all duration-300 group-hover:text-primary">{{ $destination->name }}</h3>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-full rounded-3xl border border-dashed border-border/70 bg-muted/40 px-8 py-14 text-center">
                                    <h3 class="text-2xl font-bold text-foreground">{{ __('book_now.destinations.empty_title') }}</h3>
                                    <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">{{ __('book_now.destinations.empty_description') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-muted/40">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-14"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('book_now.trust.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('book_now.trust.title') }}</h2></div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        class="text-center bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-lg transition-all duration-300">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-shield-check w-6 h-6 text-primary">
                                <path
                                    d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-foreground">{{ __('book_now.trust.items.0') }}</h3></div>
                    <div
                        class="text-center bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-lg transition-all duration-300">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-credit-card w-6 h-6 text-primary">
                                <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                                <line x1="2" x2="22" y1="10" y2="10"></line>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-foreground">{{ __('book_now.trust.items.1') }}</h3></div>
                    <div
                        class="text-center bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-lg transition-all duration-300">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-sparkles w-6 h-6 text-primary">
                                <path
                                    d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path>
                                <path d="M20 3v4"></path>
                                <path d="M22 5h-4"></path>
                                <path d="M4 17v2"></path>
                                <path d="M5 18H3"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-foreground">{{ __('book_now.trust.items.2') }}</h3></div>
                    <div
                        class="text-center bg-card rounded-2xl p-7 border border-border/50 hover:border-primary/40 hover:shadow-lg transition-all duration-300">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-headphones w-6 h-6 text-primary">
                                <path
                                    d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-foreground">{{ __('book_now.trust.items.3') }}</h3></div>
                </div>
            </div>
        </section>
        <section class="section-padding bg-background">
            <div class="container mx-auto max-w-6xl">
                <div class="text-center mb-14"><p
                        class="text-primary font-semibold tracking-[0.3em] uppercase text-xs mb-4">{{ __('book_now.support.eyebrow') }}</p>
                    <h2 class="text-3xl md:text-5xl font-bold text-foreground">{{ __('book_now.support.title') }}</h2></div>
                <div class="grid md:grid-cols-3 gap-6"><a href="https://wa.me/201033455433" target="_blank"
                                                          rel="noopener noreferrer"
                                                          class="group bg-[#25D366] text-white rounded-2xl p-8 hover:shadow-2xl hover:-translate-y-1 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-message-circle w-9 h-9 mb-5">
                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                        </svg>
                        <h3 class="text-xl font-bold mb-2">{{ __('book_now.support.whatsapp_title') }}</h3>
                        <p class="text-sm text-white/85 mb-5">{{ __('book_now.support.whatsapp_description') }}</p><span
                            class="inline-flex items-center gap-2 text-sm font-semibold group-hover:gap-3 transition-all">{{ __('book_now.support.whatsapp_cta') }} <svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4"><path
                                    d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></span></a><a
                        href="tel:+201033455433"
                        class="group bg-card rounded-2xl p-8 border border-border/50 hover:border-primary/40 hover:shadow-2xl hover:-translate-y-1 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-phone-call w-9 h-9 text-primary mb-5">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            <path d="M14.05 2a9 9 0 0 1 8 7.94"></path>
                            <path d="M14.05 6A5 5 0 0 1 18 10"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-foreground mb-2">{{ __('book_now.support.call_title') }}</h3>
                        <p class="text-sm text-muted-foreground mb-5">{{ __('book_now.support.call_description') }}</p><span
                            class="inline-flex items-center gap-2 text-sm font-semibold text-primary group-hover:gap-3 transition-all">{{ __('book_now.support.call_cta') }} <svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4"><path
                                    d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></span></a><a
                        class="group bg-card rounded-2xl p-8 border border-border/50 hover:border-primary/40 hover:shadow-2xl hover:-translate-y-1 transition-all"
                        href="{{ route('contact') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-phone-incoming w-9 h-9 text-primary mb-5">
                            <polyline points="16 2 16 8 22 8"></polyline>
                            <line x1="22" x2="16" y1="2" y2="8"></line>
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-foreground mb-2">{{ __('book_now.support.callback_title') }}</h3>
                        <p class="text-sm text-muted-foreground mb-5">{{ __('book_now.support.callback_description') }}</p><span
                            class="inline-flex items-center gap-2 text-sm font-semibold text-primary group-hover:gap-3 transition-all">{{ __('book_now.support.callback_cta') }} <svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4"><path
                                    d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></span></a></div>
            </div>
        </section>
        <section class="relative py-28 overflow-hidden"><img src="{{ asset('kidana-home-assets/luxury-hotel-BtGo0Ucn.jpg') }}" alt=""
                                                             class="absolute inset-0 w-full h-full object-cover">
            <div
                class="absolute inset-0 bg-gradient-to-b from-[hsl(var(--hero-overlay))]/90 to-[hsl(var(--secondary))]/90"></div>
            <div class="relative z-10 container mx-auto max-w-3xl text-center text-primary-foreground px-4"><h2
                    class="text-3xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">{{ __('book_now.final_cta.title') }}</h2>
                <p class="text-primary-foreground/85 text-base md:text-lg mb-10">{{ __('book_now.final_cta.description') }}</p>
                <div class="flex flex-col sm:flex-row gap-5 justify-center">
                    <button
                        class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 btn-premium bg-primary text-primary-foreground hover:bg-primary/90 rounded-xl px-12 py-7 text-base font-bold gap-2">
                        {{ __('book_now.final_cta.book_now') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right w-5 h-5">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </button>
                    <a href="https://wa.me/201033455433" target="_blank" rel="noopener noreferrer">
                        <button
                            class="inline-flex items-center justify-center whitespace-nowrap ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border h-10 bg-primary-foreground border-primary-foreground/20 text-secondary hover:bg-primary hover:text-primary-foreground hover:border-primary rounded-xl px-12 py-7 text-base font-bold gap-2 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-message-circle w-5 h-5">
                                <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                            </svg>
                            {{ __('book_now.final_cta.chat') }}
                        </button>
                    </a></div>
            </div>
        </section>
    </div>
@endsection
