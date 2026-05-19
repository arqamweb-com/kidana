<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;

test('root redirects to default locale prefix', function () {
    $this->get('/')
        ->assertRedirect('/en');
});

test('locale prefix is applied to web requests', function () {
    Route::middleware('web')->get('/{locale}/locale-test-current', fn (): string => app()->getLocale())
        ->whereIn('locale', array_keys(config('locales.supported')));

    $this->get('/ar/locale-test-current')
        ->assertOk()
        ->assertSee('ar');
});

test('unsupported locale prefix returns not found', function () {
    Route::middleware('web')->get('/{locale}/locale-test-current', fn (): string => app()->getLocale())
        ->whereIn('locale', array_keys(config('locales.supported')));

    $this->get('/de/locale-test-current')
        ->assertNotFound();
});

test('static navigation and footer copy is translated by locale prefix', function () {
    $this->get('/ar/contact')
        ->assertOk()
        ->assertSee('الرئيسية')
        ->assertSee('روابط سريعة');
});

test('arabic pages render right to left direction', function () {
    $this->get('/ar/contact')
        ->assertOk()
        ->assertSee('dir="rtl"', false);

    $this->get('/en/contact')
        ->assertOk()
        ->assertSee('dir="ltr"', false);
});

test('home translation keys are available for every supported locale', function () {
    $expectedKeys = array_keys(Arr::dot(require lang_path('en/home.php')));

    foreach (array_keys(config('locales.supported')) as $locale) {
        $localeKeys = array_keys(Arr::dot(require lang_path("{$locale}/home.php")));

        expect($localeKeys)->toEqualCanonicalizing($expectedKeys);
    }
});

test('services translation keys are available for every supported locale', function () {
    $expectedKeys = array_keys(Arr::dot(require lang_path('en/services.php')));

    foreach (array_keys(config('locales.supported')) as $locale) {
        $localeKeys = array_keys(Arr::dot(require lang_path("{$locale}/services.php")));

        expect($localeKeys)->toEqualCanonicalizing($expectedKeys);
    }
});

test('packages translation keys are available for every supported locale', function () {
    $expectedKeys = array_keys(Arr::dot(require lang_path('en/packages.php')));

    foreach (array_keys(config('locales.supported')) as $locale) {
        $localeKeys = array_keys(Arr::dot(require lang_path("{$locale}/packages.php")));

        expect($localeKeys)->toEqualCanonicalizing($expectedKeys);
    }
});

test('umrah plus translation keys are available for every supported locale', function () {
    $expectedKeys = array_keys(Arr::dot(require lang_path('en/umrah-plus.php')));

    foreach (array_keys(config('locales.supported')) as $locale) {
        $localeKeys = array_keys(Arr::dot(require lang_path("{$locale}/umrah-plus.php")));

        expect($localeKeys)->toEqualCanonicalizing($expectedKeys);
    }
});

test('about translation keys are available for every supported locale', function () {
    $expectedKeys = array_keys(Arr::dot(require lang_path('en/about.php')));

    foreach (array_keys(config('locales.supported')) as $locale) {
        $localeKeys = array_keys(Arr::dot(require lang_path("{$locale}/about.php")));

        expect($localeKeys)->toEqualCanonicalizing($expectedKeys);
    }
});

test('about page renders arabic static copy', function () {
    $this->get('/ar/about')
        ->assertOk()
        ->assertSee('من نحن')
        ->assertSee('رسالتنا')
        ->assertSee('رؤيتنا');
});

test('contact translation keys are available for every supported locale', function () {
    $expectedKeys = array_keys(Arr::dot(require lang_path('en/contact.php')));

    foreach (array_keys(config('locales.supported')) as $locale) {
        $localeKeys = array_keys(Arr::dot(require lang_path("{$locale}/contact.php")));

        expect($localeKeys)->toEqualCanonicalizing($expectedKeys);
    }
});

test('contact page renders arabic static copy', function () {
    $this->get('/ar/contact')
        ->assertOk()
        ->assertSee('تواصل معنا')
        ->assertSee('يسعدنا تواصلك معنا')
        ->assertSee('إرسال الرسالة');
});
