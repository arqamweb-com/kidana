<?php

namespace App\Console\Commands;

use App\Services\FawryPaymentStatusSyncer;
use App\Services\FawryService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('fawry:sync-payments')]
#[Description('Pull pending Fawry payment statuses and update local bookings.')]
class FawrySyncPayments extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(FawryService $fawry, FawryPaymentStatusSyncer $syncer): int
    {
        if (! $fawry->isConfigured()) {
            $this->error('Fawry payment is not configured.');

            return self::FAILURE;
        }

        $result = $syncer->sync(
            onSynced: fn ($payment) => $this->line("Synced payment #{$payment->id} ({$payment->merchant_ref_number})."),
            onFailed: fn ($payment) => $this->warn("Failed to sync payment #{$payment->id} ({$payment->merchant_ref_number})."),
        );

        $this->info("Fawry sync finished. Synced: {$result['synced']}. Failed: {$result['failed']}.");

        return $result['failed'] > 0 ? self::FAILURE : self::SUCCESS;
    }
}
