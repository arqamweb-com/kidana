<?php

namespace App\Http\Controllers;

use App\Enum\PackageOrderAction;
use App\Http\Requests\HotelStayRequest;
use App\Http\Requests\StoreHotelBookingRequest;
use App\Models\Hotel;
use App\Services\Bookings\BookingService;
use App\Services\FawryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class HotelBookingController extends Controller
{
    public function create(HotelStayRequest $request, string $locale, Hotel $hotel): View
    {
        abort_unless($hotel->is_active, 404);

        return view('hotels.booking-form', [
            'hotel' => $hotel,
            'stay' => $request->stay(),
        ]);
    }

    public function store(StoreHotelBookingRequest $request, string $locale, Hotel $hotel, BookingService $bookings, FawryService $fawry): View|RedirectResponse
    {
        abort_unless($hotel->is_active, 404);

        $paysWithFawry = $hotel->order_action === PackageOrderAction::FawryPayment;

        if ($paysWithFawry && ! $fawry->isConfigured()) {
            throw ValidationException::withMessages([
                'payment' => __('packages.booking.payment_not_configured'),
            ]);
        }

        $booking = $bookings->createHotelBooking($request->stay(), $request->validated(), $locale);

        if ($paysWithFawry) {
            return redirect()->route('payment.checkout', [
                'locale' => $locale,
                'booking' => $booking,
            ]);
        }

        return view('packages.booking-result', [
            'booking' => $booking,
            'payment' => null,
            'status' => 'custom_request_received',
        ]);
    }
}
