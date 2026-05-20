@extends('layout.master')

@section('title', __('packages.booking.checkout_title'))
@section('meta_description', __('packages.booking.result_meta_description'))

@push('head')
    <link rel="stylesheet" href="{{ $cssUrl }}">
@endpush

@section('content')
    <section class="section-padding bg-background">
        <div class="container mx-auto max-w-3xl">
            <x-breadcrumbs
                class="mb-8"
                :items="[
                    ['label' => __('nav.packages'), 'url' => route('packages')],
                    ['label' => $booking->package->name, 'url' => route('packages.show', ['package' => $booking->package->slug])],
                    ['label' => __('packages.booking.checkout')],
                ]"
            />

            <div class="rounded-2xl border border-border/50 bg-card p-6 text-center shadow-sm md:p-8">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">
                    {{ __('packages.booking.result_eyebrow') }}
                </p>
                <h1 class="mt-3 text-3xl font-bold text-foreground">
                    {{ __('packages.booking.checkout_heading') }}
                </h1>

                <dl class="mx-auto mt-8 max-w-lg divide-y divide-border/60 rounded-xl border border-border/60 bg-background text-start">
                    <div class="flex justify-between gap-4 px-5 py-4">
                        <dt class="text-sm text-muted-foreground">{{ __('packages.booking.booking_number') }}</dt>
                        <dd class="text-sm font-bold text-foreground">#{{ $booking->id }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 px-5 py-4">
                        <dt class="text-sm text-muted-foreground">{{ __('packages.booking.package') }}</dt>
                        <dd class="text-sm font-bold text-foreground">{{ $booking->package->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 px-5 py-4">
                        <dt class="text-sm text-muted-foreground">{{ __('packages.booking.amount') }}</dt>
                        <dd class="text-sm font-bold text-foreground">
                            EGP {{ number_format(collect($chargeRequest['chargeItems'])->sum(fn ($item): float => $item['price'] * $item['quantity']), 2) }}
                        </dd>
                    </div>
                </dl>

                <div class="mt-8">
                    <button
                        type="button"
                        onclick="startFawryCheckout()"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-primary px-6 py-4 text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90 md:w-auto md:min-w-56"
                    >
                        {{ __('packages.booking.pay_with_fawry') }}
                    </button>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ $jsUrl }}"></script>
    <script>
        const chargeRequest = @json($chargeRequest);

        function startFawryCheckout() {
            const configuration = {
                locale: '{{ app()->getLocale() === 'ar' ? 'ar' : 'en' }}',
                mode: DISPLAY_MODE.POPUP,
            };

            FawryPay.checkout(chargeRequest, configuration);
        }
    </script>
@endsection
