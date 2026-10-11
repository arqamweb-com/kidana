<?php

use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

function legacyBrandPackage(array $attributes = []): Package
{
    return Package::factory()->create([
        'description' => [
            'en' => "<p>Our rep meets you with the sign 'Sharm-club.com'. A sharm-club representative will drive you.</p>",
            'ar' => "<p>سيقابلك مندوبنا حاملاً لافتة 'sharm-club.com'. مندوب sharm-club يستقبل الضيوف.</p>",
            'fr' => '<p>Accueil avec une pancarte « sharm-club.com ». Le représentant de Sharm Club vous attend.</p>',
        ],
        'image_url' => 'https://www.sharm-club.com/assets/images/cities/cairo/cairo-city-view.jpg',
        'gallery' => [
            'en' => [['image' => 'https://www.sharm-club.com/assets/images/cities/cairo/pyramids.jpg', 'caption' => 'Pyramids']],
            'ar' => [['image' => 'https://www.sharm-club.com/assets/images/cities/cairo/pyramids.jpg', 'caption' => 'الأهرامات']],
        ],
        ...$attributes,
    ]);
}

test('it rewrites the sign and company name text in every locale', function () {
    $package = legacyBrandPackage();

    $this->artisan('content:rebrand-legacy-text')->assertSuccessful();

    $package->refresh();

    expect($package->getTranslation('description', 'en'))
        ->toBe("<p>Our rep meets you with the sign 'kidanatravel.com'. A Kidana Travel representative will drive you.</p>")
        ->and($package->getTranslation('description', 'ar'))
        ->toBe("<p>سيقابلك مندوبنا حاملاً لافتة 'kidanatravel.com'. مندوب Kidana Travel يستقبل الضيوف.</p>")
        ->and($package->getTranslation('description', 'fr'))
        ->toBe('<p>Accueil avec une pancarte « kidanatravel.com ». Le représentant de Kidana Travel vous attend.</p>');

    $rawDescription = DB::table('packages')->where('id', $package->id)->value('description');

    expect($rawDescription)
        ->toContain('سيقابلك')
        ->not->toContain('\u')
        ->not->toContain('\/');
});

test('it leaves urls inside the description untouched', function () {
    $package = legacyBrandPackage([
        'description' => [
            'en' => '<p>Meet sharm-club staff.</p><img src="https://www.sharm-club.com/assets/images/x.jpg"><a href="//sharm-club.com/tours">Tours</a>',
        ],
    ]);

    $this->artisan('content:rebrand-legacy-text')->assertSuccessful();

    expect($package->refresh()->getTranslation('description', 'en'))
        ->toBe('<p>Meet Kidana Travel staff.</p><img src="https://www.sharm-club.com/assets/images/x.jpg"><a href="//sharm-club.com/tours">Tours</a>');
});

test('it never modifies the image url or the gallery', function () {
    $package = legacyBrandPackage();
    $before = DB::table('packages')->where('id', $package->id)->first(['image_url', 'gallery']);

    $this->artisan('content:rebrand-legacy-text')->assertSuccessful();

    $after = DB::table('packages')->where('id', $package->id)->first(['image_url', 'gallery']);

    expect($after->image_url)->toBe($before->image_url)
        ->and($after->gallery)->toBe($before->gallery);
});

test('a dry run writes nothing', function () {
    $package = legacyBrandPackage();
    $before = DB::table('packages')->where('id', $package->id)->first();

    $this->artisan('content:rebrand-legacy-text', ['--dry-run' => true])
        ->expectsOutputToContain('Dry run: 6 replacement(s) in 1 column value(s) across 1 row(s). Nothing was written.')
        ->assertSuccessful();

    expect(DB::table('packages')->where('id', $package->id)->first())->toEqual($before);
});

test('a second run finds nothing left to change', function () {
    legacyBrandPackage();

    $this->artisan('content:rebrand-legacy-text')
        ->expectsOutputToContain('Done: 6 replacement(s) in 1 column value(s) across 1 row(s).')
        ->assertSuccessful();

    $this->artisan('content:rebrand-legacy-text')
        ->expectsOutputToContain('Done: 0 replacement(s) in 0 column value(s) across 0 row(s).')
        ->assertSuccessful();
});

test('mentions outside package descriptions are reported but not changed', function () {
    $package = legacyBrandPackage(['description' => ['en' => 'Clean text']]);
    $package->setTranslation('location_label', 'en', 'Sharm Club office')->save();

    $this->artisan('content:rebrand-legacy-text')
        ->expectsOutputToContain('Found 1 column value(s) outside packages.description')
        ->assertSuccessful();

    expect($package->refresh()->getTranslation('location_label', 'en'))->toBe('Sharm Club office');
});
