<div>
    @if ($testimonials->isNotEmpty())
        <div class="grid md:grid-cols-2 gap-8">
            @foreach ($testimonials as $testimonial)
                @php
                    $testimonialHtml = new \Illuminate\Support\HtmlString($testimonial->testimonial ?? '');
                @endphp

                <article
                    class="bg-card rounded-2xl p-8 border border-border/60 shadow-sm hover:shadow-lg transition-shadow">
                    <div class="flex gap-1 mb-4">
                        @for ($star = 0; $star < 5; $star++)
                            <span class="text-primary text-2xl leading-none">★</span>
                        @endfor
                    </div>

                    <div
                        class="mb-6 space-y-3 text-base leading-relaxed text-foreground md:text-lg [&_a]:font-medium [&_a]:text-primary [&_a]:underline [&_a]:underline-offset-4 [&_em]:italic [&_li]:ms-5 [&_li]:list-disc [&_p]:m-0 [&_p]:italic [&_strong]:font-semibold">
                        {{ $testimonialHtml }}
                    </div>

                    <div>
                        <p class="font-bold text-foreground">{{ $testimonial->name }}</p>

                        @if ($testimonial->position)
                            <p class="text-sm text-muted-foreground">{{ $testimonial->position }}</p>
                        @endif
                    </div>
                </article>
            @endforeach
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
