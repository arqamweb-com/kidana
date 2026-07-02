<?php

test('umrah plus page renders with local assets and functional content', function () {
    config(['session.driver' => 'array']);

    $response = $this->get(route('umrah-plus'));

    $response->assertSuccessful();
    $response->assertSee('Experience');
    $response->assertSee('Umrah Plus');
    $response->assertSee('Frequently Asked Questions');
    $response->assertSee('Can I choose any destination before Umrah?');
    $response->assertSee('kidana-home-assets/kaaba-umrah.jpg');
    $response->assertSee('kidana-home-assets/umrah-plus-egypt.jpg');
    expect(substr_count($response->getContent(), 'container mx-auto max-w-6xl'))->toBeGreaterThanOrEqual(9);
    $response->assertSee('mx-auto max-w-4xl text-center', false);
    $response->assertSee('mx-auto max-w-3xl text-center', false);
    $response->assertDontSee('src="/assets', false);
    $response->assertDontSee('radix-', false);
});

test('umrah plus page renders arabic static copy', function () {
    config(['session.driver' => 'array']);

    $response = $this->get('/ar/umrah-plus');

    $response->assertSuccessful();
    $response->assertSee('عمرة بلس');
    $response->assertSee('ما هي عمرة بلس؟');
    $response->assertSee('الأسئلة الشائعة');
});
