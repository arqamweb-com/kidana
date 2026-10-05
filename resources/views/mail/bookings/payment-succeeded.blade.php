<x-mail::message>
# {{ __('packages.booking.mail_heading') }}

{{ __('packages.booking.mail_body', ['package' => $booking->bookedItemName()]) }}

**{{ __('packages.booking.booking_number') }}:** #{{ $booking->id }}  
@if ($booking->isHotelBooking())
**{{ __('hotels.booking.check_in') }}:** {{ $booking->check_in?->toDateString() }}  
**{{ __('hotels.booking.check_out') }}:** {{ $booking->check_out?->toDateString() }}  
**{{ __('hotels.booking.rooms') }}:** {{ $booking->rooms }}  
@endif
**{{ __('packages.booking.amount') }}:** {{ $payment?->currency }} {{ number_format((float) $payment?->amount, 2) }}

{{ __('packages.booking.mail_footer') }}

{{ config('app.name') }}
</x-mail::message>
