<?php

namespace App\Http\Resources\Api\V1;

use App\Enum\BookingStatus;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Booking
 */
class BookingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'reference' => $this->reference,
            'external_reference' => $this->external_reference,
            'status' => $this->status->value,
            'kind' => $this->isHotelBooking() ? 'hotel_room' : 'package',
            'package' => $this->when(! $this->isHotelBooking(), fn (): array => [
                'slug' => $this->package?->slug,
                'name' => $this->package?->name,
            ]),
            'hotel' => $this->when($this->isHotelBooking(), fn (): array => [
                'slug' => $this->hotel?->slug,
                'name' => $this->hotel?->name,
                'room_id' => $this->hotel_room_id,
                'room_name' => $this->hotelRoom?->name,
                'check_in' => $this->check_in?->toDateString(),
                'check_out' => $this->check_out?->toDateString(),
                'nights' => $this->nights,
                'rooms' => $this->rooms,
                'adults' => $this->adults,
                'children' => $this->children,
            ]),
            'travel_date' => $this->travel_date?->toDateString(),
            'guests' => $this->guests,
            'customer' => [
                'name' => $this->customer_name,
                'email' => $this->customer_email,
                'mobile' => $this->customer_mobile,
            ],
            'message' => $this->message,
            'total_amount' => (float) $this->total_amount,
            'currency' => $this->currency,
            'payment_url' => $this->when(
                $this->status === BookingStatus::AwaitingPayment && $this->payment !== null,
                fn (): string => route('payment.checkout', ['locale' => $this->locale, 'booking' => $this->resource]),
            ),
            'paid_at' => $this->paid_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
