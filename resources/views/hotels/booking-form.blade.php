@extends('layout.master')

@php
    $paysWithFawry = $hotel->order_action === \App\Enum\PackageOrderAction::FawryPayment;
    $room = $stay['room'];
    $inputClasses = 'w-full rounded-xl border border-border bg-background px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30';
    $stayFields = [
        'room_id' => $room->id,
        'check_in' => $stay['check_in']->toDateString(),
        'check_out' => $stay['check_out']->toDateString(),
        'rooms' => $stay['rooms'],
        'adults' => $stay['adults'],
        'children' => $stay['children'],
    ];
@endphp

@section('title', $paysWithFawry ? __('hotels.booking.checkout_title') : __('hotels.booking.request_title'))
@section('meta_description', __('hotels.booking.meta_description', ['hotel' => $hotel->name]))

@section('content')
    <section class="section-padding bg-background">
        <div class="container mx-auto max-w-5xl">
            <x-breadcrumbs
                class="mb-8"
                :items="[
                    ['label' => __('nav.hotels'), 'url' => route('hotels')],
                    ['label' => $hotel->name, 'url' => route('hotels.show', ['hotel' => $hotel->slug])],
                    ['label' => $paysWithFawry ? __('hotels.booking.checkout') : __('hotels.booking.request')],
                ]"
            />

            <div class="grid gap-8 lg:grid-cols-[1fr_0.7fr]">
                <form method="POST" action="{{ route('hotels.book.store', ['hotel' => $hotel->slug]) }}"
                      class="rounded-2xl border border-border/50 bg-card p-6 shadow-sm md:p-8">
                    @csrf

                    @foreach ($stayFields as $field => $value)
                        <input type="hidden" name="{{ $field }}" value="{{ $value }}">
                    @endforeach

                    <div class="mb-7">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">
                            {{ $paysWithFawry ? __('hotels.booking.checkout') : __('hotels.booking.request') }}
                        </p>
                        <h1 class="mt-3 text-3xl font-bold text-foreground">
                            {{ $paysWithFawry ? __('hotels.booking.checkout_heading') : __('hotels.booking.request_heading') }}
                        </h1>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 rounded-xl border border-destructive/30 bg-destructive/10 px-4 py-3 text-sm text-destructive">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="grid gap-5 md:grid-cols-2">
                        <label class="space-y-2">
                            <span class="text-sm font-semibold text-foreground">{{ __('packages.booking.name') }}</span>
                            <input name="customer_name" value="{{ old('customer_name') }}" required autocomplete="name" class="{{ $inputClasses }}">
                        </label>

                        <label class="space-y-2">
                            <span class="text-sm font-semibold text-foreground">{{ __('packages.booking.mobile') }}</span>
                            <input name="customer_mobile" value="{{ old('customer_mobile') }}" required inputmode="tel" autocomplete="tel" placeholder="01xxxxxxxxx" class="{{ $inputClasses }}">
                        </label>

                        <label class="space-y-2 md:col-span-2">
                            <span class="text-sm font-semibold text-foreground">{{ __('packages.booking.email') }}</span>
                            <input name="customer_email" value="{{ old('customer_email') }}" type="email" required autocomplete="email" class="{{ $inputClasses }}">
                        </label>

                        <label class="space-y-2 md:col-span-2">
                            <span class="text-sm font-semibold text-foreground">{{ __('packages.booking.message') }}</span>
                            <textarea name="message" rows="4" class="{{ $inputClasses }} resize-none">{{ old('message') }}</textarea>
                        </label>
                    </div>

                    <button type="submit"
                            class="mt-7 inline-flex w-full items-center justify-center rounded-xl bg-primary px-6 py-4 text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90">
                        {{ $paysWithFawry ? __('hotels.booking.pay_with_fawry') : __('hotels.booking.submit_request') }}
                    </button>
                </form>

                <aside class="h-fit rounded-2xl border border-border/50 bg-muted/40 p-6">
                    <p class="text-sm font-semibold text-muted-foreground">{{ __('hotels.booking.summary') }}</p>
                    <h2 class="mt-3 text-2xl font-bold text-foreground">{{ $hotel->name }}</h2>
                    <p class="mt-1 text-sm text-muted-foreground">{{ $room->name }}</p>

                    <dl class="mt-6 space-y-4 text-sm">
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-muted-foreground">{{ __('hotels.booking.check_in') }}</dt>
                            <dd class="font-bold text-foreground">{{ $stay['check_in']->translatedFormat('D j M Y') }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-muted-foreground">{{ __('hotels.booking.check_out') }}</dt>
                            <dd class="font-bold text-foreground">{{ $stay['check_out']->translatedFormat('D j M Y') }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-muted-foreground">{{ __('hotels.booking.nights') }}</dt>
                            <dd class="font-bold text-foreground">{{ trans_choice('hotels.booking.nights_count', $stay['nights'], ['count' => $stay['nights']]) }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-muted-foreground">{{ __('hotels.booking.rooms') }}</dt>
                            <dd class="font-bold text-foreground">{{ $stay['rooms'] }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-muted-foreground">{{ __('hotels.booking.guests') }}</dt>
                            <dd class="font-bold text-foreground">{{ __('hotels.booking.guests_summary', ['adults' => $stay['adults'], 'children' => $stay['children']]) }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-muted-foreground">{{ __('hotels.booking.price_per_night') }}</dt>
                            <dd class="font-bold text-foreground">EGP {{ number_format((float) $room->price_per_night, 2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 border-t border-border/60 pt-4 text-base">
                            <dt class="font-semibold text-foreground">{{ __('hotels.booking.total') }}</dt>
                            <dd class="font-bold text-primary">EGP {{ number_format((float) $stay['total'], 2) }}</dd>
                        </div>
                    </dl>

                    <a href="{{ route('hotels.show', ['hotel' => $hotel->slug]) }}#availability"
                       class="mt-6 inline-flex text-sm font-semibold text-primary hover:underline">
                        {{ __('hotels.booking.change_stay') }}
                    </a>
                </aside>
            </div>
        </div>
    </section>
@endsection
