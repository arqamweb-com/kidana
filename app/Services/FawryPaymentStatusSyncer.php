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
     * @param  int  $limit  Maximum payments to check per run (prevents API flooding)
     * @param  int  $maxAgeHours  Skip payments older than this many hours
     * @return array{synced: int, failed: int}
     */
    public function sync(?callable $onSynced = null, ?callable $onFailed = null, int $limit = 100, int $maxAgeHours = 48): array
    {
        $synced = 0;
        $failed = 0;
        $processed = 0;

        $this->pendingPaymentsQuery($maxAgeHours)
            ->chunkById(25, function ($payments) use (&$synced, &$failed, &$processed, $limit, $onSynced, $onFailed): bool {
                foreach ($payments as $payment) {
                    if ($processed >= $limit) {
                        return false;
                    }

                    $processed++;

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

                return true;
            });

        return [
            'synced' => $synced,
            'failed' => $failed,
        ];
    }

    protected function pendingPaymentsQuery(int $maxAgeHours = 48)
    {
        return Payment::query()
            ->where('provider', 'fawry')
            ->whereIn('status', ['pending', 'created', 'unpaid', 'processing'])
            ->where('created_at', '>=', now()->subHours($maxAgeHours))
            ->where(function ($query): void {
                $query
                    ->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->with('booking.package')
            ->orderBy('id');
    }
}
