<?php

use App\Http\Controllers\BookNowController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomPackageController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\DestinationIndexController;
use App\Http\Controllers\FawryWebhookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageBookingController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageSearchController;
use App\Http\Controllers\PackageShowController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\VisitEgyptController;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::redirect('/', '/'.config('app.locale'));

Route::post('/payment/webhook/fawry', [FawryWebhookController::class, 'handle'])
    ->name('payment.webhook')
    ->withoutMiddleware([PreventRequestForgery::class, VerifyCsrfToken::class]);

Route::get('/payment/callback', [PaymentController::class, 'callback'])
    ->name('payment.callback');

Route::prefix('{locale}')
    ->whereIn('locale', array_keys(config('locales.supported')))
    ->group(function (): void {
        Route::get('/', HomeController::class)->name('home');

        Route::get('/packages/search', PackageSearchController::class)->name('packages.search');

        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');
        Route::post('/services/{service:slug}/inquiry', [ServiceController::class, 'inquiry'])->name('services.inquiry')->middleware('throttle:5,1');

        Route::get('/destinations', DestinationIndexController::class)->name('destinations.index');
        Route::get('/destinations/{destination:slug}', DestinationController::class)->name('destinations.show');

        Route::get('/contact', [ContactController::class, 'show'])->name('contact');
        Route::post('/contact', [ContactController::class, 'store'])->name('contact.store')->middleware('throttle:5,1');

        Route::view('/about', 'pages.about')->name('about');

        Route::get('/packages', PackageController::class)->name('packages');
        Route::get('/packages/{package:slug}', PackageShowController::class)->name('packages.show');
        Route::get('/packages/{package:slug}/request', [PackageBookingController::class, 'requestForm'])->name('packages.request');
        Route::post('/packages/{package:slug}/request', [PackageBookingController::class, 'storeRequest'])->name('packages.request.store');
        Route::get('/packages/{package:slug}/checkout', [PackageBookingController::class, 'checkoutForm'])->name('packages.checkout');
        Route::post('/packages/{package:slug}/checkout', [PackageBookingController::class, 'startCheckout'])->name('packages.checkout.store');
        Route::get('/bookings/{booking}/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
        Route::get('/bookings/{booking}/result', [PackageBookingController::class, 'result'])->name('bookings.result');

        Route::view('/umrah-plus', 'pages.umrah-plus')->name('umrah-plus');

        Route::get('/visit-egypt', VisitEgyptController::class)->name('visit-egypt');

        Route::get('/book-now', BookNowController::class)->name('book-now');
        Route::post('/book-now/custom-package', [CustomPackageController::class, 'store'])
            ->name('book-now.custom-package')
            ->middleware('throttle:5,1');

        Route::view('/terms-and-conditions', 'pages.terms-and-conditions')->name('terms-and-conditions');
        Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy-policy');
        Route::view('/refund-policy', 'pages.refund-policy')->name('refund-policy');
    });
