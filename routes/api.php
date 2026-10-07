<?php

use App\Enum\ApiAbility;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\HotelAvailabilityController;
use App\Http\Controllers\Api\V1\HotelController;
use App\Http\Controllers\Api\V1\PackageController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Middleware\EnsureApiClientIsAllowed;
use App\Http\Middleware\EnsureIdempotentBooking;
use App\Http\Middleware\LogApiRequest;
use App\Http\Middleware\SetApiLocale;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->name('api.v1.')
    ->middleware(['auth:sanctum', LogApiRequest::class, EnsureApiClientIsAllowed::class, 'throttle:partner-api', SetApiLocale::class])
    ->group(function (): void {
        Route::middleware('abilities:'.ApiAbility::CatalogRead->value)->group(function (): void {
            Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
            Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

            Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
            Route::get('/packages/{package:slug}', [PackageController::class, 'show'])->name('packages.show');

            Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
            Route::get('/hotels/{hotel:slug}', [HotelController::class, 'show'])->name('hotels.show');
            Route::get('/hotels/{hotel:slug}/availability', HotelAvailabilityController::class)->name('hotels.availability');
        });

        Route::middleware('abilities:'.ApiAbility::BookingsCreate->value)->group(function (): void {
            Route::post('/bookings', [BookingController::class, 'store'])
                ->middleware(EnsureIdempotentBooking::class)
                ->name('bookings.store');
            Route::post('/bookings/{reference}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
        });

        Route::get('/bookings/{reference}', [BookingController::class, 'show'])
            ->middleware('abilities:'.ApiAbility::BookingsRead->value)
            ->name('bookings.show');
    });
