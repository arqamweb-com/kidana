<x-mail::message>
# {{ __('contact.mail.heading') }}

**{{ __('contact.mail.from') }}:** {{ $senderName }}

**{{ __('contact.mail.email') }}:** {{ $senderEmail }}

@if ($senderPhone)
**{{ __('contact.mail.phone') }}:** {{ $senderPhone }}

@endif
---

{{ $body }}

{{ config('app.name') }}
</x-mail::message>
