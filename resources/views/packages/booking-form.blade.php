@extends('layout.master')

@section('title', $mode === \App\Enum\PackageOrderAction::FawryPayment ? __('packages.booking.checkout_title') : __('packages.booking.request_title'))
@section('meta_description', __('packages.booking.meta_description', ['package' => $package->name]))

@section('content')
    <section class="section-padding bg-background">
        <div class="container mx-auto max-w-5xl">
            <x-breadcrumbs
                class="mb-8"
                :items="[
                    ['label' => __('nav.packages'), 'url' => route('packages')],
                    ['label' => $package->name, 'url' => route('packages.show', ['package' => $package->slug])],
                    ['label' => $mode === \App\Enum\PackageOrderAction::FawryPayment ? __('packages.booking.checkout') : __('packages.booking.request')],
                ]"
            />

            <div class="grid gap-8 lg:grid-cols-[1fr_0.7fr]">
                <form
                    method="POST"
                    action="{{ $mode === \App\Enum\PackageOrderAction::FawryPayment ? route('packages.checkout.store', ['package' => $package->slug]) : route('packages.request.store', ['package' => $package->slug]) }}"
                    class="rounded-2xl border border-border/50 bg-card p-6 shadow-sm md:p-8"
                >
                    @csrf

                    <div class="mb-7">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">
                            {{ $mode === \App\Enum\PackageOrderAction::FawryPayment ? __('packages.booking.checkout') : __('packages.booking.request') }}
                        </p>
                        <h1 class="mt-3 text-3xl font-bold text-foreground">
                            {{ $mode === \App\Enum\PackageOrderAction::FawryPayment ? __('packages.booking.checkout_heading') : __('packages.booking.request_heading') }}
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
                            <input name="customer_name" value="{{ old('customer_name') }}" required
                                   class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                        </label>

                        <label class="space-y-2">
                            <span class="text-sm font-semibold text-foreground">{{ __('packages.booking.mobile') }}</span>
                            <input name="customer_mobile" value="{{ old('customer_mobile') }}" required inputmode="tel" placeholder="01xxxxxxxxx"
                                   class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                        </label>

                        <label class="space-y-2">
                            <span class="text-sm font-semibold text-foreground">{{ __('packages.booking.email') }}</span>
                            <input name="customer_email" value="{{ old('customer_email') }}" type="email" required
                                   class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                        </label>

                        <label class="space-y-2">
                            <span class="text-sm font-semibold text-foreground">{{ __('packages.booking.guests') }}</span>
                            <input name="guests" value="{{ old('guests') }}" type="number" min="1"
                                   class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                        </label>

                        <label class="space-y-2 md:col-span-2">
                            <span class="text-sm font-semibold text-foreground">{{ __('packages.booking.travel_date') }}</span>
                            <input name="travel_date" value="{{ old('travel_date') }}" type="date"
                                   class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                        </label>

                        <label class="space-y-2 md:col-span-2">
                            <span class="text-sm font-semibold text-foreground">{{ __('packages.booking.message') }}</span>
                            <textarea name="message" rows="5"
                                      class="w-full resize-none rounded-xl border border-border bg-background px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">{{ old('message') }}</textarea>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="mt-7 inline-flex w-full items-center justify-center rounded-xl bg-primary px-6 py-4 text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90"
                    >
                        {{ $mode === \App\Enum\PackageOrderAction::FawryPayment ? __('packages.booking.pay_with_fawry') : __('packages.booking.submit_request') }}
                    </button>
                </form>

                <aside class="h-fit rounded-2xl border border-border/50 bg-muted/40 p-6">
                    <p class="text-sm font-semibold text-muted-foreground">{{ __('packages.booking.summary') }}</p>
                    <h2 class="mt-3 text-2xl font-bold text-foreground">{{ $package->name }}</h2>
                    <dl class="mt-6 space-y-4 text-sm">
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-muted-foreground">{{ __('packages.show.starting_from') }}</dt>
                            <dd class="font-bold text-foreground">
                                @if($package->price > 0)
                                    EGP {{ number_format((float) $package->price, 2) }}
                                @else
                                    {{ __('packages.show.on_request') }}
                                @endif
                            </dd>
                        </div>
                        @if ($package->days)
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-muted-foreground">{{ __('packages.show.duration') }}</dt>
                                <dd class="font-bold text-foreground">{{ __('packages.show.days', ['count' => $package->days]) }}</dd>
                            </div>
                        @endif
                    </dl>
                </aside>
            </div>
        </div>
    </section>
@endsection
