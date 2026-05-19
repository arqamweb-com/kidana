<?php

use App\Models\Testimonial;

test('testimonial styles render rich editor html', function (string $style) {
    $testimonials = collect([
        Testimonial::factory()->make([
            'name' => 'Nile Guest',
            'testimonial' => '<p>The trip was <strong>excellent</strong>.</p><ul><li>Well organized</li></ul>',
            'position' => 'Traveler',
        ]),
    ]);

    $response = $this->blade(
        '@include("sections.testimonials.index", ["style" => $style, "testimonials" => $testimonials])',
        [
            'style' => $style,
            'testimonials' => $testimonials,
        ],
    );

    $response->assertSee('<strong>excellent</strong>', false);
    $response->assertSee('<li>Well organized</li>', false);
    $response->assertDontSee('&lt;strong&gt;', false);
})->with([
    'style-1',
    'style-2',
]);
