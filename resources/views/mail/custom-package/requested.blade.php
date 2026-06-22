<x-mail::message>
# New Custom Package Request

**Name:** {{ $senderName }}

**Email:** {{ $senderEmail }}

@if ($senderPhone)
**Phone:** {{ $senderPhone }}

@endif
---

@foreach ($details as $label => $value)
@if (filled($value))
**{{ $label }}:** {{ $value }}

@endif
@endforeach

{{ config('app.name') }}
</x-mail::message>
