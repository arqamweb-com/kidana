<?php

namespace App\Jobs;

use App\Enum\BookingStatus;
use App\Http\Resources\Api\V1\BookingResource;
use App\Models\Booking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Tells a partner that one of its API bookings changed status. The body is signed with the
 * partner's webhook secret: X-Kidana-Signature = sha256 HMAC of "{timestamp}.{body}".
 */
class SendApiClientWebhook implements ShouldQueue
{
    use Queueable;

    public int $tries = 6;

    public int $timeout = 30;

    public function __construct(
        public Booking $booking,
        public BookingStatus $status,
    ) {}

    /**
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [60, 300, 900, 3600, 21600];
    }

    public function handle(): void
    {
        $client = $this->booking->apiClient;

        if ($client === null || ! $client->is_active || blank($client->webhook_url)) {
            return;
        }

        $this->booking->loadMissing('package', 'hotel', 'hotelRoom', 'payment');

        $body = json_encode([
            'event' => 'booking.status_changed',
            'status' => $this->status->value,
            'occurred_at' => now()->toIso8601String(),
            'data' => (new BookingResource($this->booking))->resolve(),
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $timestamp = (string) now()->timestamp;

        Http::withHeaders([
            'X-Kidana-Event' => 'booking.status_changed',
            'X-Kidana-Timestamp' => $timestamp,
            'X-Kidana-Signature' => 'sha256='.hash_hmac('sha256', $timestamp.'.'.$body, (string) $client->webhook_secret),
        ])
            ->connectTimeout(5)
            ->timeout(10)
            ->withBody($body, 'application/json')
            ->post($client->webhook_url)
            ->throw();
    }

    public function failed(?Throwable $exception): void
    {
        Log::warning('Partner webhook delivery failed.', [
            'booking_id' => $this->booking->id,
            'api_client_id' => $this->booking->api_client_id,
            'status' => $this->status->value,
            'error' => $exception?->getMessage(),
        ]);
    }
}
