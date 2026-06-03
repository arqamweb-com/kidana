<x-mail::message>
# {{ __('services.show.form.mail_heading') }}

**{{ __('services.show.form.mail_service') }}:** {{ $serviceName }}

**{{ __('contact.mail.from') }}:** {{ $senderName }}

**{{ __('contact.mail.email') }}:** {{ $senderEmail }}

**{{ __('contact.mail.phone') }}:** {{ $senderPhone }}

@if ($travelDate)
**{{ __('services.show.form.travel_date') }}:** {{ $travelDate }}

@endif
@if ($people)
**{{ __('services.show.form.people') }}:** {{ $people }}

@endif
@if ($body)
---

{{ $body }}

@endif
{{ config('app.name') }}
</x-mail::message>
