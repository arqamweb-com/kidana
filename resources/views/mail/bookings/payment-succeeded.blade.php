<x-mail::message>
# {{ __('packages.booking.mail_heading') }}

{{ __('packages.booking.mail_body', ['package' => $booking->package->name]) }}

**{{ __('packages.booking.booking_number') }}:** #{{ $booking->id }}  
**{{ __('packages.booking.amount') }}:** {{ $payment?->currency }} {{ number_format((float) $payment?->amount, 2) }}

{{ __('packages.booking.mail_footer') }}

{{ config('app.name') }}
</x-mail::message>
