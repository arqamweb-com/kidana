@php
    $testimonialsStyle = $style ?? 'style-1';
    $testimonialsStyle = preg_match('/^style-[A-Za-z0-9_-]+$/', $testimonialsStyle) === 1 ? $testimonialsStyle : 'style-1';
    $testimonials = $testimonials ?? $homeTestimonials ?? collect();
    $testimonials = $testimonials instanceof \Illuminate\Support\Collection ? $testimonials : collect($testimonials);
@endphp

@include("sections.testimonials.styles.{$testimonialsStyle}")
