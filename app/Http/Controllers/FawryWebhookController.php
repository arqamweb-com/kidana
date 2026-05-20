<?php

namespace App\Http\Controllers;

use App\Services\Bookings\BookingService;
use App\Services\FawryService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class FawryWebhookController extends Controller
{
    public function __construct(protected FawryService $fawry) {}

    public function handle(Request $request, BookingService $bookings): Response
    {
        $data = $request->all();

        Log::channel('daily')->info('[Fawry Webhook]', Arr::except($data, ['signature']));

        if (! $this->fawry->verifyCallbackSignature($data)) {
            Log::warning('[Fawry Webhook] Invalid signature', Arr::except($data, ['signature']));

            if (app()->environment('local')) {
                return response()->json([
                    'status' => 'received',
                    'signature' => 'invalid',
                ], 200);
            }

            return response('Unauthorized', 401);
        }

        try {
            $payment = $bookings->applyFawryStatus($data);
        } catch (ValidationException $exception) {
            Log::warning('[Fawry Webhook] Validation failed', [
                'errors' => $exception->errors(),
                'merchant_ref_number' => $data['merchantRefNumber'] ?? $data['merchantRefNum'] ?? null,
            ]);

            return response('Unprocessable entity', 422);
        }

        if (! $payment) {
            Log::warning('[Fawry Webhook] Booking payment not found', [
                'merchant_ref_number' => $data['merchantRefNumber'] ?? $data['merchantRefNum'] ?? null,
            ]);

            return response('Not found', 404);
        }

        return response('', 200);
    }
}
