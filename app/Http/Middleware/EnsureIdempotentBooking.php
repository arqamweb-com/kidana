<?php

namespace App\Http\Middleware;

use App\Http\Resources\Api\V1\BookingResource;
use App\Models\Booking;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Requires an Idempotency-Key header and replays the booking already created with that key,
 * so a partner retrying a timed-out request never creates a second booking.
 */
class EnsureIdempotentBooking
{
    public const HEADER = 'Idempotency-Key';

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = (string) $request->header(self::HEADER);

        if ($key === '' || mb_strlen($key) > 191) {
            throw ValidationException::withMessages([
                'idempotency_key' => 'The '.self::HEADER.' header is required and may not be longer than 191 characters.',
            ]);
        }

        $existing = Booking::query()
            ->whereBelongsTo($request->user(), 'apiClient')
            ->where('idempotency_key', $key)
            ->with('package', 'hotel', 'hotelRoom', 'payment')
            ->first();

        if ($existing !== null) {
            return (new BookingResource($existing))
                ->response()
                ->setStatusCode(200)
                ->header('Idempotent-Replayed', 'true');
        }

        return $next($request);
    }
}
