@php
    $faqItems = collect($faqs ?? ($package ?? null)?->faqs ?? [])
        ->filter(fn ($faq): bool => filled($faq->title) || filled($faq->answer))
        ->values();
@endphp

@if ($faqItems->isNotEmpty())
    <div class="space-y-3" data-package-accordion>
        @foreach ($faqItems as $faq)
            @php
                $isOpen = $loop->first;
                $triggerId = 'package-faq-trigger-' . $loop->iteration;
                $panelId = 'package-faq-panel-' . $loop->iteration;
            @endphp

            <div data-package-accordion-item
                 data-state="{{ $isOpen ? 'open' : 'closed' }}"
                 class="bg-card rounded-2xl border border-border/60 px-6 shadow-sm">
                <h3 class="flex">
                    <button type="button"
                            id="{{ $triggerId }}"
                            aria-controls="{{ $panelId }}"
                            aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                            data-package-accordion-trigger
                            data-state="{{ $isOpen ? 'open' : 'closed' }}"
                            class="flex flex-1 items-center justify-between gap-4 py-5 text-left text-base md:text-lg font-semibold text-foreground transition-all hover:no-underline">
                        <span>{{ $faq->title }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="h-4 w-4 shrink-0 transition-transform duration-200 {{ $isOpen ? 'rotate-180' : '' }}">
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>
                    </button>
                </h3>
                <div id="{{ $panelId }}"
                     role="region"
                     aria-labelledby="{{ $triggerId }}"
                     data-package-accordion-panel
                     data-state="{{ $isOpen ? 'open' : 'closed' }}"
                     class="{{ $isOpen ? '' : 'hidden' }} border-t border-border/60 pb-6 pt-5 text-sm leading-relaxed text-muted-foreground md:text-base">
                    {{ $faq->answer }}
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="rounded-3xl border border-dashed border-border/70 bg-card px-8 py-12 text-center">
        <h3 class="text-2xl font-bold text-foreground">No FAQs available yet</h3>
        <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
            Attach active FAQs to this package from the dashboard and they will appear here automatically.
        </p>
    </div>
@endif
