<?php

namespace App\Http\Controllers;

use App\Services\Bookings\BookingPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FawryPaymentController extends Controller
{
    public function webhook(Request $request, BookingPaymentService $bookings): JsonResponse
    {
        $payment = $bookings->applyFawryNotification($request->all());

        return response()->json([
            'received' => true,
            'payment_id' => $payment->id,
        ]);
    }
}
