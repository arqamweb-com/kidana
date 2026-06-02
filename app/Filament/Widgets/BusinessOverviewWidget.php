<?php

namespace App\Filament\Widgets;

use App\Enum\BookingStatus;
use App\Enum\PaymentStatus;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Payment;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class BusinessOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected ?string $heading = 'Business overview';

    protected ?string $pollingInterval = null;

    protected function getColumns(): int|array|null
    {
        return 4;
    }

    protected function getStats(): array
    {
        return [
            $this->revenueThisMonth(),
            $this->bookingsThisWeek(),
            $this->pendingPayments(),
            $this->upcomingDepartures(),
        ];
    }

    private function revenueThisMonth(): Stat
    {
        $thisMonth = (float) Payment::query()
            ->where('status', PaymentStatus::Paid->value)
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $lastMonth = (float) Payment::query()
            ->where('status', PaymentStatus::Paid->value)
            ->whereMonth('paid_at', now()->subMonthNoOverflow()->month)
            ->whereYear('paid_at', now()->subMonthNoOverflow()->year)
            ->sum('amount');

        if ($lastMonth > 0) {
            $change = (int) round((($thisMonth - $lastMonth) / $lastMonth) * 100);
        } else {
            $change = $thisMonth > 0 ? 100 : 0;
        }

        $isUp = $change >= 0;

        return Stat::make('Revenue · this month', 'EGP '.Number::format((int) $thisMonth))
            ->description(($isUp ? '+' : '').$change.'% vs last month')
            ->descriptionIcon($isUp ? Heroicon::OutlinedArrowTrendingUp : Heroicon::OutlinedArrowTrendingDown)
            ->color($isUp ? 'success' : 'danger');
    }

    private function bookingsThisWeek(): Stat
    {
        $start = now()->startOfWeek();
        $end = now()->endOfWeek();

        $total = Booking::query()->whereBetween('created_at', [$start, $end])->count();

        $confirmed = Booking::query()
            ->whereBetween('created_at', [$start, $end])
            ->where('status', BookingStatus::Paid->value)
            ->count();

        $new = Booking::query()
            ->whereBetween('created_at', [$start, $end])
            ->where('status', BookingStatus::Pending->value)
            ->count();

        return Stat::make('Bookings · this week', Number::format($total))
            ->description("{$confirmed} confirmed · {$new} new")
            ->descriptionIcon(Heroicon::OutlinedCalendarDays)
            ->color('primary');
    }

    private function pendingPayments(): Stat
    {
        $count = Payment::query()
            ->where('status', PaymentStatus::Pending->value)
            ->count();

        return Stat::make('Pending payments', Number::format($count))
            ->description('needs follow-up')
            ->descriptionIcon(Heroicon::OutlinedClock)
            ->color('warning');
    }

    private function upcomingDepartures(): Stat
    {
        $packageIds = Package::query()
            ->active()
            ->whereBetween('start_date', [now()->toDateString(), now()->addDays(30)->toDateString()])
            ->pluck('id');

        $count = $packageIds->count();

        // pax = sum of guests on confirmed bookings for these packages
        $pax = (int) Booking::query()
            ->whereIn('package_id', $packageIds)
            ->whereIn('status', [BookingStatus::Paid->value, BookingStatus::AwaitingPayment->value])
            ->sum('guests');

        $description = 'next 30 days'.($pax > 0 ? ' · '.Number::format($pax).' pax' : '');

        return Stat::make('Upcoming departures', Number::format($count))
            ->description($description)
            ->descriptionIcon(Heroicon::OutlinedPaperAirplane)
            ->color($count > 0 ? 'success' : 'gray');
    }
}
