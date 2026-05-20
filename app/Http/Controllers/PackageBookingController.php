<?php

namespace App\Http\Controllers;

use App\Enum\PackageOrderAction;
use App\Http\Requests\StorePackageBookingRequest;
use App\Models\Booking;
use App\Models\Package;
use App\Services\Bookings\BookingService;
use App\Services\FawryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class PackageBookingController extends Controller
{
    public function requestForm(string $locale, Package $package): View
    {
        $this->ensureActive($package);

        return view('packages.booking-form', [
            'package' => $package,
            'mode' => PackageOrderAction::CustomForm,
        ]);
    }

    public function storeRequest(StorePackageBookingRequest $request, string $locale, Package $package, BookingService $bookings): View
    {
        $this->ensureActive($package);

        $booking = $bookings->createCustomBooking($package, $request->validated(), $locale);

        return view('packages.booking-result', [
            'booking' => $booking->loadMissing('package'),
            'payment' => null,
            'status' => 'custom_request_received',
        ]);
    }

    public function checkoutForm(string $locale, Package $package): View
    {
        $this->ensureActive($package);

        return view('packages.booking-form', [
            'package' => $package,
            'mode' => PackageOrderAction::FawryPayment,
        ]);
    }

    public function startCheckout(StorePackageBookingRequest $request, string $locale, Package $package, BookingService $bookings, FawryService $fawry): RedirectResponse
    {
        $this->ensureActive($package);

        if (! $fawry->isConfigured()) {
            throw ValidationException::withMessages([
                'payment' => __('packages.booking.payment_not_configured'),
            ]);
        }

        $booking = $bookings->createFawryBooking($package, $request->validated(), $locale);

        return redirect()->route('payment.checkout', [
            'locale' => $locale,
            'booking' => $booking,
        ]);
    }

    public function result(string $locale, Booking $booking): View
    {
        abort_unless($booking->locale === $locale, 404);

        return view('packages.booking-result', [
            'booking' => $booking->loadMissing('package'),
            'payment' => $booking->payment,
            'status' => 'payment_result',
        ]);
    }

    protected function ensureActive(Package $package): void
    {
        abort_unless($package->is_active, 404);
    }
}
