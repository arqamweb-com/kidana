<?php

use App\Models\ApiClient;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

const LEGACY_HERO_URL = 'https://www.sharm-club.com/assets/images/cities/aswan/philae/trojan-kiosk-at-philae.jpg';
const LEGACY_GALLERY_URL = 'https://sharm-club.com/assets/images/cities/cairo/pyramids.jpg';

beforeEach(function () {
    Storage::fake('public');
});

function fakeLegacyImageHost(): void
{
    Http::fake([
        'sharm-club.com/assets/images/missing/*' => Http::response('Not found', 404),
        'www.sharm-club.com/assets/images/html/*' => Http::response('<html></html>', 200, ['Content-Type' => 'text/html']),
        'www.sharm-club.com/assets/images/untyped-html/*' => Http::response('<html><body>Page</body></html>', 200, ['Content-Type' => '']),
        'www.sharm-club.com/assets/images/untyped-webp/*' => Http::response(base64_decode('UklGRhoAAABXRUJQVlA4TA0AAAAvAAAAEAcQERGIiP4HAA=='), 200, ['Content-Type' => '']),
        '*' => Http::response('fake-jpeg-bytes', 200, ['Content-Type' => 'image/jpeg']),
    ]);
}

function legacyImagePackage(array $attributes = []): Package
{
    return Package::factory()->create([
        'image_url' => LEGACY_HERO_URL,
        'gallery' => [
            'en' => [
                ['image' => LEGACY_HERO_URL, 'caption' => 'Trojan kiosk'],
                ['image' => LEGACY_GALLERY_URL, 'caption' => 'Pyramids'],
            ],
            'ar' => [
                ['image' => LEGACY_HERO_URL, 'caption' => 'كشك تراجان'],
            ],
        ],
        ...$attributes,
    ]);
}

test('it moves the hero and gallery images in every locale to the public disk', function () {
    fakeLegacyImageHost();
    $package = legacyImagePackage();

    $this->artisan('packages:localize-legacy-images')->assertSuccessful();

    $package->refresh();
    $heroPath = 'packages/legacy/cities/aswan/philae/trojan-kiosk-at-philae.jpg';

    expect($package->image_url)->toBe($heroPath)
        ->and($package->getTranslation('gallery', 'en'))->toBe([
            ['image' => $heroPath, 'caption' => 'Trojan kiosk'],
            ['image' => 'packages/legacy/cities/cairo/pyramids.jpg', 'caption' => 'Pyramids'],
        ])
        ->and($package->getTranslation('gallery', 'ar'))->toBe([
            ['image' => $heroPath, 'caption' => 'كشك تراجان'],
        ]);

    Storage::disk('public')->assertExists([$heroPath, 'packages/legacy/cities/cairo/pyramids.jpg']);

    expect(DB::table('packages')->where('id', $package->id)->value('gallery'))
        ->toContain('كشك تراجان')
        ->not->toContain('\/');
});

test('an image shared by several packages is downloaded once', function () {
    fakeLegacyImageHost();
    legacyImagePackage();
    legacyImagePackage();

    $this->artisan('packages:localize-legacy-images')->assertSuccessful();

    Http::assertSentCount(2);
});

test('failed downloads keep the original url and are reported', function (string $url) {
    fakeLegacyImageHost();
    $package = legacyImagePackage(['image_url' => $url]);

    $this->artisan('packages:localize-legacy-images')
        ->expectsOutputToContain('could not be downloaded')
        ->assertFailed();

    $package->refresh();

    expect($package->image_url)->toBe($url)
        ->and($package->getTranslation('gallery', 'en')[1]['image'])->toBe('packages/legacy/cities/cairo/pyramids.jpg');
})->with([
    'not found' => 'https://www.sharm-club.com/assets/images/missing/gone.jpg',
    'not an image' => 'https://www.sharm-club.com/assets/images/html/page.jpg',
    'html without a content type' => 'https://www.sharm-club.com/assets/images/untyped-html/page.webp',
]);

test('an image served without a content type is accepted when its bytes are an image', function () {
    fakeLegacyImageHost();
    $package = legacyImagePackage(['image_url' => 'https://www.sharm-club.com/assets/images/untyped-webp/tour.webp']);

    $this->artisan('packages:localize-legacy-images')->assertSuccessful();

    expect($package->refresh()->image_url)->toBe('packages/legacy/untyped-webp/tour.webp');
    Storage::disk('public')->assertExists('packages/legacy/untyped-webp/tour.webp');
});

test('images on other hosts and relative paths are left alone', function () {
    fakeLegacyImageHost();
    $package = Package::factory()->create([
        'image_url' => 'https://images.unsplash.com/photo-1.jpg',
        'gallery' => ['en' => [['image' => 'packages/gallery/local.jpg', 'caption' => 'Local']]],
    ]);

    $this->artisan('packages:localize-legacy-images')
        ->expectsOutputToContain('No images left to localize.')
        ->assertSuccessful();

    Http::assertNothingSent();

    expect($package->refresh()->image_url)->toBe('https://images.unsplash.com/photo-1.jpg')
        ->and($package->getTranslation('gallery', 'en'))->toBe([['image' => 'packages/gallery/local.jpg', 'caption' => 'Local']]);
});

test('a dry run downloads and writes nothing', function () {
    fakeLegacyImageHost();
    $package = legacyImagePackage();
    $before = DB::table('packages')->where('id', $package->id)->first();

    $this->artisan('packages:localize-legacy-images', ['--dry-run' => true])
        ->expectsOutputToContain('Dry run: 2 of 2 unique image URL(s) used by 1 package(s).')
        ->assertSuccessful();

    Http::assertNothingSent();
    Storage::disk('public')->assertDirectoryEmpty('/');
    expect(DB::table('packages')->where('id', $package->id)->first())->toEqual($before);
});

test('the limit option processes only the first urls and a second run resumes', function () {
    fakeLegacyImageHost();
    $package = legacyImagePackage();

    $this->artisan('packages:localize-legacy-images', ['--limit' => 1])->assertSuccessful();

    expect($package->refresh()->image_url)->toBe('packages/legacy/cities/aswan/philae/trojan-kiosk-at-philae.jpg')
        ->and($package->getTranslation('gallery', 'en')[1]['image'])->toBe(LEGACY_GALLERY_URL);

    $this->artisan('packages:localize-legacy-images')->assertSuccessful();
    $this->artisan('packages:localize-legacy-images')
        ->expectsOutputToContain('No images left to localize.')
        ->assertSuccessful();

    Http::assertSentCount(2);
});

test('files already on disk are not downloaded again', function () {
    fakeLegacyImageHost();
    Storage::disk('public')->put('packages/legacy/cities/aswan/philae/trojan-kiosk-at-philae.jpg', 'existing');
    Storage::disk('public')->put('packages/legacy/cities/cairo/pyramids.jpg', 'existing');
    $package = legacyImagePackage();

    $this->artisan('packages:localize-legacy-images')->assertSuccessful();

    Http::assertNothingSent();
    expect($package->refresh()->image_url)->toBe('packages/legacy/cities/aswan/philae/trojan-kiosk-at-philae.jpg');
});

test('the package api returns resolved gallery image urls', function () {
    Sanctum::actingAs(ApiClient::factory()->create(), ['*']);
    Package::factory()->create([
        'slug' => 'philae',
        'gallery' => ['en' => [
            ['image' => 'packages/legacy/cities/aswan/philae.jpg', 'caption' => 'Philae'],
            ['image' => 'https://images.unsplash.com/photo-1.jpg', 'caption' => ''],
        ]],
    ]);

    $this->getJson(route('api.v1.packages.show', 'philae'))
        ->assertSuccessful()
        ->assertJsonPath('data.gallery', [
            Storage::disk('public')->url('packages/legacy/cities/aswan/philae.jpg'),
            'https://images.unsplash.com/photo-1.jpg',
        ]);
});

test('relative legacy image paths render on the package page', function () {
    Package::factory()->create([
        'slug' => 'philae',
        'image_url' => 'packages/legacy/cities/aswan/philae.jpg',
        'gallery' => ['en' => [['image' => 'packages/legacy/cities/aswan/kiosk.jpg', 'caption' => 'Kiosk']]],
    ]);

    $this->get(route('packages.show', ['package' => 'philae']))
        ->assertSuccessful()
        ->assertSee(Storage::url('packages/legacy/cities/aswan/philae.jpg'), false)
        ->assertSee(Storage::url('packages/legacy/cities/aswan/kiosk.jpg'), false);
});
