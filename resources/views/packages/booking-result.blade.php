@extends('layout.master')

@section('title', __('packages.booking.result_title'))
@section('meta_description', __('packages.booking.result_meta_description'))

@section('content')
    @php
        $isPaid = $payment?->status === \App\Enum\PaymentStatus::Paid || $booking->status === \App\Enum\BookingStatus::Paid;
        $isFailed = in_array($payment?->status, [
            \App\Enum\PaymentStatus::Failed,
            \App\Enum\PaymentStatus::Expired,
            \App\Enum\PaymentStatus::Cancelled,
        ], true);
    @endphp

    <section class="section-padding bg-background">
        <div class="container mx-auto max-w-3xl">
            <div class="rounded-2xl border border-border/50 bg-card p-8 text-center shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">
                    {{ __('packages.booking.result_eyebrow') }}
                </p>
                <h1 class="mt-3 text-3xl font-bold text-foreground">
                    @if ($status === 'custom_request_received')
                        {{ __('packages.booking.request_received_title') }}
                    @elseif ($isPaid)
                        {{ __('packages.booking.payment_success_title') }}
                    @elseif ($isFailed)
                        {{ __('packages.booking.payment_failed_title') }}
                    @else
                        {{ __('packages.booking.payment_pending_title') }}
                    @endif
                </h1>
                <p class="mx-auto mt-4 max-w-xl text-sm leading-6 text-muted-foreground">
                    @if ($status === 'custom_request_received')
                        {{ __('packages.booking.request_received_description') }}
                    @elseif ($isPaid)
                        {{ __('packages.booking.payment_success_description') }}
                    @elseif ($isFailed)
                        {{ __('packages.booking.payment_failed_description') }}
                    @else
                        {{ __('packages.booking.payment_pending_description') }}
                    @endif
                </p>

                <dl class="mx-auto mt-8 max-w-lg divide-y divide-border/60 rounded-xl border border-border/60 bg-background text-start">
                    <div class="flex justify-between gap-4 px-5 py-4">
                        <dt class="text-sm text-muted-foreground">{{ __('packages.booking.booking_number') }}</dt>
                        <dd class="text-sm font-bold text-foreground">#{{ $booking->id }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 px-5 py-4">
                        <dt class="text-sm text-muted-foreground">{{ __('packages.booking.package') }}</dt>
                        <dd class="text-sm font-bold text-foreground">{{ $booking->package->name }}</dd>
                    </div>
                    @if ($payment)
                        <div class="flex justify-between gap-4 px-5 py-4">
                            <dt class="text-sm text-muted-foreground">{{ __('packages.booking.fawry_reference') }}</dt>
                            <dd class="text-sm font-bold text-foreground">{{ $payment->fawry_reference_number ?: $payment->merchant_ref_number }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 px-5 py-4">
                            <dt class="text-sm text-muted-foreground">{{ __('packages.booking.amount') }}</dt>
                            <dd class="text-sm font-bold text-foreground">{{ $payment->currency }} {{ number_format((float) $payment->amount, 2) }}</dd>
                        </div>
                    @endif
                </dl>

                <a href="{{ route('packages.show', ['package' => $booking->package->slug]) }}"
                   class="mt-8 inline-flex items-center justify-center rounded-xl border border-border bg-background px-6 py-3 text-sm font-semibold text-foreground transition-colors hover:border-primary hover:text-primary">
                    {{ __('packages.booking.back_to_package') }}
                </a>
            </div>
        </div>
    </section>
@endsection
