<?php

namespace App\Filament\Resources\Payments\Pages;

use App\Filament\Resources\Payments\PaymentResource;
use App\Services\FawryPaymentStatusSyncer;
use App\Services\FawryService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncFawryPayments')
                ->label('Sync Fawry payments')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->modalHeading('Sync pending Fawry payments?')
                ->modalDescription('This will ask Fawry for the latest status of pending payments and update related bookings.')
                ->action(function (FawryService $fawry, FawryPaymentStatusSyncer $syncer): void {
                    if (! $fawry->isConfigured()) {
                        Notification::make()
                            ->title('Fawry is not configured')
                            ->body('Add FAWRY_MERCHANT_CODE and FAWRY_SECRET_KEY or FAWRY_SECURE_KEY, then clear config cache.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $result = $syncer->sync();

                    $notification = Notification::make()
                        ->title('Fawry sync finished')
                        ->body("Synced: {$result['synced']}. Failed: {$result['failed']}.");

                    if ($result['failed'] > 0) {
                        $notification->warning();
                    } else {
                        $notification->success();
                    }

                    $notification->send();
                }),
            CreateAction::make(),
        ];
    }
}
