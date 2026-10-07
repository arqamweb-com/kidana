<x-mail::message>
# New Partner API Booking

**Partner:** {{ $booking->apiClient?->name }}

**Reference:** {{ $booking->reference }}

@if ($booking->external_reference)
**Partner reference:** {{ $booking->external_reference }}

@endif
**Booked item:** {{ $booking->bookedItemName() }}

@if ($booking->isHotelBooking())
**Stay:** {{ $booking->check_in?->toDateString() }} → {{ $booking->check_out?->toDateString() }} ({{ $booking->rooms }} room(s), {{ $booking->adults }} adult(s), {{ $booking->children }} child(ren))

@elseif ($booking->travel_date)
**Travel date:** {{ $booking->travel_date->toDateString() }}

@endif
**Status:** {{ $booking->status->value }}

**Total:** {{ number_format((float) $booking->total_amount, 2) }} {{ $booking->currency }}

---

**Customer:** {{ $booking->customer_name }}

**Email:** {{ $booking->customer_email }}

**Mobile:** {{ $booking->customer_mobile }}

<x-mail::button :url="\App\Filament\Resources\Bookings\BookingResource::getUrl('view', ['record' => $booking])">
View booking
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
