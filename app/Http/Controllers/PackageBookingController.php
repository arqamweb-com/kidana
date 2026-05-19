<?php

namespace App\Http\Controllers;

use App\Enum\PackageOrderAction;
use App\Http\Requests\StorePackageBookingRequest;
use App\Models\Booking;
use App\Models\Package;
use App\Services\Bookings\BookingPaymentService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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

    public function storeRequest(StorePackageBookingRequest $request, string $locale, Package $package, BookingPaymentService $bookings): View
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

    public function startCheckout(StorePackageBookingRequest $request, string $locale, Package $package, BookingPaymentService $bookings): RedirectResponse|View
    {
        $this->ensureActive($package);

        $result = $bookings->createFawryPayment($package, $request->validated(), $locale);
        $paymentUrl = Arr::get($result['response'], 'paymentUrl');

        if (filled($paymentUrl)) {
            return redirect()->away((string) $paymentUrl);
        }

        return view('packages.booking-result', [
            'booking' => $result['booking']->loadMissing('package'),
            'payment' => $result['payment'],
            'status' => 'payment_reference_created',
        ]);
    }

    public function result(Request $request, string $locale, Booking $booking, BookingPaymentService $bookings): View
    {
        abort_unless($booking->locale === $locale, 404);

        if ($request->filled('orderStatus')) {
            $bookings->applyFawryReturnResponse($request->query());
            $booking->refresh();
        }

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
