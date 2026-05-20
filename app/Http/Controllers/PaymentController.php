<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\Bookings\BookingService;
use App\Services\FawryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(protected FawryService $fawry) {}

    public function checkout(string $locale, Booking $booking): View
    {
        abort_unless($booking->locale === $locale, 404);

        $booking->loadMissing('package', 'payment');
        abort_unless($booking->payment !== null, 404);

        $chargeRequest = $this->fawry->buildChargeRequest([
            'order_id' => $booking->payment->merchant_ref_number,
            'return_url' => route('payment.callback'),
            'webhook_url' => route('payment.webhook'),
            'customer' => [
                'name' => $booking->customer_name,
                'email' => $booking->customer_email,
                'mobile' => $booking->customer_mobile,
                'profile_id' => (string) $booking->id,
            ],
            'items' => [
                [
                    'id' => (string) $booking->package_id,
                    'description' => (string) $booking->package->name,
                    'price' => $booking->payment->amount,
                    'quantity' => 1,
                ],
            ],
        ]);

        $booking->payment->update([
            'request_payload' => $chargeRequest,
        ]);

        return view('payment.checkout', [
            'booking' => $booking,
            'order' => $booking,
            'chargeRequest' => $chargeRequest,
            'jsUrl' => $this->fawry->getJsUrl(),
            'cssUrl' => $this->fawry->getCssUrl(),
        ]);
    }

    public function callback(Request $request, BookingService $bookings): RedirectResponse
    {
        $data = $request->query();

        abort_unless($this->fawry->verifyCallbackSignature($data), 403, 'Invalid Fawry signature');

        $payment = $bookings->applyFawryStatus($data, 'response_payload');
        abort_unless($payment !== null, 404);

        $booking = $payment->booking;
        $messageKey = $payment->status->value === 'paid' ? 'success' : 'error';

        $message = $payment->status->value === 'paid'
            ? __('payments.fawry.success')
            : __('payments.fawry.failed', [
                'reason' => $data['statusDescription'] ?? __('payments.fawry.unknown_error'),
            ]);

        return redirect()
            ->route('bookings.result', [
                'locale' => $booking->locale,
                'booking' => $booking,
            ])
            ->with($messageKey, $message);
    }
}
