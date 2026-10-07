<?php

namespace App\Http\Controllers\Api\V1;

use App\Enum\BookingSource;
use App\Enum\PackageOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureIdempotentBooking;
use App\Http\Requests\Api\V1\StoreBookingRequest;
use App\Http\Resources\Api\V1\BookingResource;
use App\Mail\ApiBookingReceived;
use App\Models\ApiClient;
use App\Models\Booking;
use App\Services\Bookings\BookingService;
use App\Services\FawryService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function __construct(protected BookingService $bookings) {}

    public function store(StoreBookingRequest $request, FawryService $fawry): JsonResponse
    {
        $client = $request->client();

        abort_if($request->paysWithFawry() && ! $fawry->isConfigured(), 503, __('packages.booking.payment_not_configured'));

        $attributes = [
            'source' => BookingSource::Api,
            'api_client_id' => $client->id,
            'external_reference' => $request->validated('external_reference'),
            'idempotency_key' => (string) $request->header(EnsureIdempotentBooking::HEADER),
        ];

        try {
            $booking = $this->createBooking($request, $attributes);
        } catch (UniqueConstraintViolationException) {
            // A concurrent request with the same Idempotency-Key won the race.
            $booking = $this->findClientBooking($client, ['idempotency_key' => $attributes['idempotency_key']]);

            return (new BookingResource($booking))->response()->header('Idempotent-Replayed', 'true');
        }

        Mail::to(config('mail.contact_address', config('mail.from.address')))
            ->queue(new ApiBookingReceived($booking));

        return (new BookingResource($booking->loadMissing('package', 'hotel', 'hotelRoom', 'payment')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, string $reference): BookingResource
    {
        return new BookingResource($this->findClientBooking($request->user(), ['reference' => $reference]));
    }

    public function cancel(Request $request, string $reference): BookingResource
    {
        $booking = $this->findClientBooking($request->user(), ['reference' => $reference]);

        return new BookingResource($this->bookings->cancelBooking($booking));
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createBooking(StoreBookingRequest $request, array $attributes): Booking
    {
        $locale = app()->getLocale();
        $orderAction = $request->paysWithFawry() ? PackageOrderAction::FawryPayment : PackageOrderAction::CustomForm;

        if ($request->isHotelRoom()) {
            return $this->bookings->createHotelBooking($request->stay(), $request->customer(), $locale, $attributes, $orderAction);
        }

        $package = $request->package();
        $attributes['total_amount'] = $request->client()->priceFor($package->price);

        return $orderAction === PackageOrderAction::FawryPayment
            ? $this->bookings->createFawryBooking($package, $request->customer(), $locale, $attributes)
            : $this->bookings->createCustomBooking($package, $request->customer(), $locale, $attributes);
    }

    /**
     * @param  array<string, string>  $where
     */
    private function findClientBooking(ApiClient $client, array $where): Booking
    {
        return Booking::query()
            ->whereBelongsTo($client, 'apiClient')
            ->where($where)
            ->with('package', 'hotel', 'hotelRoom', 'payment')
            ->firstOrFail();
    }
}
