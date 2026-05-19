@php
    $isRtl = config('locales.supported.' . app()->getLocale() . '.direction') === 'rtl';
    $previousChevronPath = $isRtl ? 'm9 18 6-6-6-6' : 'm15 18-6-6 6-6';
    $nextChevronPath = $isRtl ? 'm15 18-6-6 6-6' : 'm9 18 6-6-6-6';
@endphp

<div data-reveal data-testimonials-slider class="max-w-4xl mx-auto opacity-0">
    @if ($testimonials->isNotEmpty())
        <div class="relative overflow-hidden rounded-3xl bg-card premium-shadow border border-border/30">
            @foreach ($testimonials as $testimonial)
                @php
                    $testimonialHtml = new \Illuminate\Support\HtmlString($testimonial->testimonial ?? '');
                @endphp

                <article
                    data-testimonial-slide
                    class="{{ $loop->first ? 'relative opacity-100 translate-x-0' : 'absolute inset-0 opacity-0 translate-x-6 pointer-events-none' }} p-16 transition-all duration-700 md:p-24">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round"
                         class="lucide lucide-quote w-20 h-20 text-primary/8 absolute top-10 start-12">
                        <path
                            d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z">
                        </path>
                        <path
                            d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z">
                        </path>
                    </svg>
                    <div class="text-center relative z-10">
                        <div class="flex justify-center gap-1.5 mb-8">
                            @for ($star = 0; $star < 5; $star++)
                                <span class="text-primary text-2xl">★</span>
                            @endfor
                        </div>
                        <div
                            class="mx-auto mb-14 max-w-3xl space-y-4 text-xl font-light leading-relaxed text-foreground md:text-2xl lg:text-[1.7rem] [&_a]:font-medium [&_a]:text-primary [&_a]:underline [&_a]:underline-offset-4 [&_em]:italic [&_li]:ms-5 [&_li]:list-disc [&_p]:m-0 [&_p]:italic [&_strong]:font-semibold">
                            {{ $testimonialHtml }}
                        </div>
                        <div class="w-14 h-[2px] bg-primary/30 mx-auto mb-7"></div>
                        <p class="font-bold text-foreground text-xl">{{ $testimonial->name }}</p>
                        @if ($testimonial->position)
                            <p class="text-muted-foreground text-sm mt-2">{{ $testimonial->position }}</p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
        <div class="flex justify-center items-center gap-5 mt-12">
            <button type="button" data-testimonial-prev
                    class="w-12 h-12 rounded-full bg-card border border-border flex items-center justify-center hover:bg-primary hover:text-primary-foreground hover:border-primary transition-all duration-300 premium-shadow hover:shadow-xl hover:-translate-y-0.5"
                    aria-label="{{ __('home.testimonials.previous') }}">
                <svg
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-chevron-left w-5 h-5">
                    <path d="{{ $previousChevronPath }}"></path>
                </svg>
            </button>
            <div class="flex gap-2.5">
                @foreach ($testimonials as $testimonial)
                    <button type="button"
                            data-testimonial-indicator
                            aria-label="{{ __('home.testimonials.go_to', ['number' => $loop->iteration]) }}"
                            class="h-2.5 rounded-full transition-all duration-500 {{ $loop->first ? 'bg-primary w-9' : 'bg-border w-2.5 hover:bg-primary/40' }}"></button>
                @endforeach
            </div>
            <button type="button" data-testimonial-next
                    class="w-12 h-12 rounded-full bg-card border border-border flex items-center justify-center hover:bg-primary hover:text-primary-foreground hover:border-primary transition-all duration-300 premium-shadow hover:shadow-xl hover:-translate-y-0.5"
                    aria-label="{{ __('home.testimonials.next') }}">
                <svg
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-chevron-right w-5 h-5">
                    <path d="{{ $nextChevronPath }}"></path>
                </svg>
            </button>
        </div>
    @else
        <div class="rounded-3xl border border-dashed border-border/70 bg-card px-8 py-14 text-center">
            <h3 class="text-2xl font-bold text-foreground">{{ __('home.testimonials.empty_title') }}</h3>
            <p class="mx-auto mt-4 max-w-xl text-sm leading-relaxed text-muted-foreground md:text-base">
                {{ __('home.testimonials.empty_description') }}
            </p>
        </div>
    @endif
</div>
