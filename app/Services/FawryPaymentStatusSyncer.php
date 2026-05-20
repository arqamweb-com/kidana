<?php

namespace App\Services;

use App\Models\Payment;
use App\Services\Bookings\BookingService;
use Illuminate\Support\Facades\Log;
use Throwable;

class FawryPaymentStatusSyncer
{
    public function __construct(
        protected FawryService $fawry,
        protected BookingService $bookings,
    ) {}

    /**
     * @return array{synced: int, failed: int}
     */
    public function sync(?callable $onSynced = null, ?callable $onFailed = null): array
    {
        $synced = 0;
        $failed = 0;

        $this->pendingPaymentsQuery()
            ->chunkById(50, function ($payments) use (&$synced, &$failed, $onSynced, $onFailed): void {
                foreach ($payments as $payment) {
                    try {
                        $payload = $this->fawry->getPaymentStatus($payment);
                        $this->bookings->applyFawryStatus($payload, 'response_payload');
                        $synced++;

                        if ($onSynced !== null) {
                            $onSynced($payment);
                        }
                    } catch (Throwable $exception) {
                        $failed++;

                        Log::warning('Fawry payment status inquiry failed.', [
                            'payment_id' => $payment->id,
                            'merchant_ref_number' => $payment->merchant_ref_number,
                            'exception' => $exception::class,
                            'message' => $exception->getMessage(),
                        ]);

                        if ($onFailed !== null) {
                            $onFailed($payment, $exception);
                        }
                    }
                }
            });

        return [
            'synced' => $synced,
            'failed' => $failed,
        ];
    }

    protected function pendingPaymentsQuery()
    {
        return Payment::query()
            ->where('provider', 'fawry')
            ->whereIn('status', ['pending', 'created', 'unpaid', 'processing'])
            ->where(function ($query): void {
                $query
                    ->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->with('booking.package')
            ->orderBy('id');
    }
}
