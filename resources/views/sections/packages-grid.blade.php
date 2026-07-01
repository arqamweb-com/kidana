<div>
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

        $isRtl = config('locales.supported.' . app()->getLocale() . '.direction') === 'rtl';
        $forwardArrowPath = $isRtl ? 'm15 18-6-6 6-6' : 'm12 5 7 7-7 7';
    @endphp

    <div class="container mx-auto max-w-6xl">
        @if ($packages->isEmpty())
            <div class="rounded-3xl border border-dashed border-border/70 bg-muted/40 px-8 py-14 text-center">
                <h3 class="text-2xl font-bold text-foreground">{{ $emptyTitle ?? __('packages.card.empty_title') }}</h3>
                <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
                    {{ $emptyDescription ?? __('packages.card.empty_description') }}
                </p>
            </div>
        @else
            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($packages as $package)
                    @php
                        $packageImageUrl = $resolveImageUrl(
                            $package->image_url,
                            'https://images.unsplash.com/photo-1503220317375-aaad61436b1b?w=1200&q=80',
                        );
                    @endphp

                    <article
                        data-package-card
                        data-destination="{{ $package->destination?->slug }}"
                        class="group overflow-hidden rounded-3xl border border-border/50 bg-card transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_24px_80px_-30px_hsl(var(--foreground)/0.18)]">
                        <div class="relative h-64 overflow-hidden bg-muted">
                            <img src="{{ $packageImageUrl }}" alt="{{ $package->name }}"
                                 class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">

                            <div
                                class="absolute end-4 top-4 rounded-xl bg-primary px-4 py-2 text-sm font-bold text-primary-foreground shadow-[0_8px_24px_-8px_hsl(var(--primary)/0.6)]">
                                @if($package->price > 0)
                                    EGP {{ number_format((float) $package->price, 0) }}
                                @else
                                    {{ __('packages.show.on_request') }}
                                @endif
                            </div>

                            <div
                                class="inline-flex items-center border text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent hover:bg-primary/80 absolute top-4 start-4 bg-secondary text-secondary-foreground gap-1 px-3 py-1.5 rounded-lg shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="lucide lucide-star w-3 h-3 fill-current">
                                    <path
                                        d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                                </svg>
                                {{$package->location_label}}
                            </div>
                        </div>

                        <div class="p-7">
                            <div class="mb-4 flex flex-wrap items-center gap-2">
                                @if ($package->destination?->slug)
                                    <a href="{{ route('destinations.show', ['destination' => $package->destination->slug]) }}"
                                       class="rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-primary transition-colors hover:bg-primary hover:text-primary-foreground">
                                        {{ $package->destination->name }}
                                    </a>
                                @endif

                                @if ($package->service?->name)
                                    <span
                                        class="rounded-full bg-accent px-3 py-1 text-xs font-medium text-accent-foreground">
                                            {{ $package->service->name }}
                                        </span>
                                @endif
                            </div>

                            <h3 class="mb-3 text-xl font-bold text-foreground">{{ $package->name }}</h3>

                            <div class="flex items-center gap-4 text-muted-foreground text-sm mb-6">
                                @if ($package->destination?->name)
                                    <span class="flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                             height="24"
                                             viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round"
                                             class="lucide lucide-map-pin w-4 h-4 text-secondary"><path
                                                d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle
                                                cx="12" cy="10" r="3"></circle>
                                        </svg> {{ $package->destination->name }}</span>
                                @endif

                                @if(filled($package->days))
                                    <span class="flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             width="24"
                                             height="24"
                                             viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round"
                                             class="lucide lucide-clock w-4 h-4 text-secondary"><circle
                                                cx="12" cy="12" r="10"></circle><polyline
                                                points="12 6 12 12 16 14"></polyline>
                                        </svg> {{ __('packages.card.days', ['count' => $package->days]) }}
                                    </span>
                                @endif

                                @if(filled($package->max_guests))
                                    <span class="flex items-center gap-1.5">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="12" cy="8" r="2.5" stroke="currentColor"
                                                    stroke-linecap="round"/>
                                            <path
                                                d="M13.7679 6.5C13.9657 6.15743 14.2607 5.88121 14.6154 5.70625C14.9702 5.5313 15.3689 5.46548 15.7611 5.51711C16.1532 5.56874 16.5213 5.73551 16.8187 5.99632C17.1161 6.25713 17.3295 6.60028 17.4319 6.98236C17.5342 7.36445 17.521 7.76831 17.3939 8.14288C17.2667 8.51745 17.0313 8.8459 16.7175 9.08671C16.4037 9.32751 16.0255 9.46985 15.6308 9.49572C15.2361 9.52159 14.8426 9.42983 14.5 9.23205"
                                                stroke="currentColor"/>
                                            <path
                                                d="M10.2321 6.5C10.0343 6.15743 9.73935 5.88121 9.38458 5.70625C9.02981 5.5313 8.63113 5.46548 8.23895 5.51711C7.84677 5.56874 7.47871 5.73551 7.18131 5.99632C6.88391 6.25713 6.67053 6.60028 6.56815 6.98236C6.46577 7.36445 6.47899 7.76831 6.60614 8.14288C6.73329 8.51745 6.96866 8.8459 7.28248 9.08671C7.5963 9.32751 7.97448 9.46985 8.36919 9.49572C8.76391 9.52159 9.15743 9.42983 9.5 9.23205"
                                                stroke="currentColor"/>
                                            <path
                                                d="M12 12.5C16.0802 12.5 17.1335 15.8022 17.4054 17.507C17.4924 18.0524 17.0523 18.5 16.5 18.5H7.5C6.94771 18.5 6.50763 18.0524 6.59461 17.507C6.86649 15.8022 7.91976 12.5 12 12.5Z"
                                                stroke="currentColor" stroke-linecap="round"/>
                                            <path
                                                d="M19.2965 15.4162L18.8115 15.5377L19.2965 15.4162ZM13.0871 12.5859L12.7179 12.2488L12.0974 12.9283L13.0051 13.0791L13.0871 12.5859ZM17.1813 16.5L16.701 16.639L16.8055 17H17.1813V16.5ZM15.5 12C16.5277 12 17.2495 12.5027 17.7783 13.2069C18.3177 13.9253 18.6344 14.8306 18.8115 15.5377L19.7816 15.2948C19.5904 14.5315 19.2329 13.4787 18.578 12.6065C17.9126 11.7203 16.9202 11 15.5 11V12ZM13.4563 12.923C13.9567 12.375 14.6107 12 15.5 12V11C14.2828 11 13.3736 11.5306 12.7179 12.2488L13.4563 12.923ZM13.0051 13.0791C15.3056 13.4614 16.279 15.1801 16.701 16.639L17.6616 16.361C17.1905 14.7326 16.019 12.5663 13.1691 12.0927L13.0051 13.0791ZM18.395 16H17.1813V17H18.395V16ZM18.8115 15.5377C18.8653 15.7526 18.7075 16 18.395 16V17C19.2657 17 20.0152 16.2277 19.7816 15.2948L18.8115 15.5377Z"
                                                fill="currentColor"/>
                                            <path
                                                d="M10.9129 12.5859L10.9949 13.0791L11.9026 12.9283L11.2821 12.2488L10.9129 12.5859ZM4.70343 15.4162L5.18845 15.5377L4.70343 15.4162ZM6.81868 16.5V17H7.19453L7.29898 16.639L6.81868 16.5ZM8.49999 12C9.38931 12 10.0433 12.375 10.5436 12.923L11.2821 12.2488C10.6264 11.5306 9.71723 11 8.49999 11V12ZM5.18845 15.5377C5.36554 14.8306 5.68228 13.9253 6.22167 13.2069C6.75048 12.5027 7.47226 12 8.49999 12V11C7.0798 11 6.08743 11.7203 5.42199 12.6065C4.76713 13.4787 4.40955 14.5315 4.21841 15.2948L5.18845 15.5377ZM5.60498 16C5.29247 16 5.13465 15.7526 5.18845 15.5377L4.21841 15.2948C3.98477 16.2277 4.73424 17 5.60498 17V16ZM6.81868 16H5.60498V17H6.81868V16ZM7.29898 16.639C7.72104 15.1801 8.69435 13.4614 10.9949 13.0791L10.8309 12.0927C7.98101 12.5663 6.8095 14.7326 6.33838 16.361L7.29898 16.639Z"
                                                fill="currentColor"/>
                                        </svg> {{ $package->max_guests }}
                                     </span>
                                @endif
                            </div>

                            @if ($package->description)
                                <div class="mb-6 line-clamp-3 text-sm leading-relaxed text-muted-foreground">
                                    {!! $package->description !!}
                                </div>
                            @endif

                            <a href="{{ route('packages.show', ['package' => $package->slug]) }}"
                               class="inline-flex items-center justify-center whitespace-nowrap text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border bg-background h-10 px-4 w-full rounded-xl border-border hover:bg-primary hover:text-primary-foreground hover:border-primary transition-all duration-300 gap-2 font-semibold py-6 shadow-sm hover:shadow-lg hover:-translate-y-0.5">
                                {{ __('packages.card.view_details') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4">
                                    <path d="M5 12h14"></path>
                                    <path d="{{ $forwardArrowPath }}"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            @if (method_exists($packages, 'hasPages') && $packages->hasPages())
                <div class="mt-12">
                    {{ $packages->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
