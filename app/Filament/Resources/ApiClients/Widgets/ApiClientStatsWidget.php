<?php

namespace App\Filament\Resources\ApiClients\Widgets;

use App\Enum\BookingStatus;
use App\Models\ApiClient;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

class ApiClientStatsWidget extends StatsOverviewWidget
{
    public ?Model $record = null;

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        /** @var ApiClient|null $client */
        $client = $this->record;

        if ($client === null) {
            return [];
        }

        $bookings = $client->bookings()
            ->toBase()
            ->selectRaw('count(*) as total')
            ->selectRaw('sum(case when status = ? then 1 else 0 end) as pending', [BookingStatus::Pending->value])
            ->selectRaw('sum(case when status = ? then total_amount else 0 end) as paid_amount', [BookingStatus::Paid->value])
            ->first();

        $requests = $client->requestLogs()
            ->where('created_at', '>=', now()->subDay())
            ->toBase()
            ->selectRaw('count(*) as total')
            ->selectRaw('sum(case when status >= 400 then 1 else 0 end) as failed')
            ->first();

        return [
            Stat::make('Bookings', (int) $bookings->total)
                ->description((int) $bookings->pending.' pending')
                ->color('primary'),
            Stat::make('Paid revenue', Number::currency((float) $bookings->paid_amount, 'EGP'))
                ->color('success'),
            Stat::make('Requests (24h)', (int) $requests->total)
                ->description((int) $requests->failed.' failed')
                ->color((int) $requests->failed > 0 ? 'warning' : 'gray'),
        ];
    }
}
