<?php

use App\Enum\BookingSource;
use App\Enum\BookingStatus;
use App\Jobs\SendApiClientWebhook;
use App\Models\ApiClient;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

test('a status change on an api booking queues a partner webhook', function () {
    $booking = Booking::factory()->create([
        'source' => BookingSource::Api,
        'api_client_id' => ApiClient::factory()->withWebhook()->create()->id,
    ]);
    Queue::fake();

    $booking->update(['status' => BookingStatus::Paid]);
    $booking->update(['message' => 'No status change']);

    Queue::assertPushed(SendApiClientWebhook::class, 1);
    Queue::assertPushed(SendApiClientWebhook::class, fn (SendApiClientWebhook $job): bool => $job->booking->is($booking)
        && $job->status === BookingStatus::Paid);
});

test('website bookings never trigger partner webhooks', function () {
    $booking = Booking::factory()->create();
    Queue::fake();

    $booking->update(['status' => BookingStatus::Paid]);

    Queue::assertNothingPushed();
});

test('the webhook is posted with an hmac signature of the timestamp and body', function () {
    Http::preventStrayRequests();
    Http::fake(['partner.test/*' => Http::response()]);

    $client = ApiClient::factory()->withWebhook()->create(['webhook_secret' => 'shh']);
    $booking = Booking::factory()->create(['source' => BookingSource::Api, 'api_client_id' => $client->id, 'status' => BookingStatus::Cancelled]);

    (new SendApiClientWebhook($booking, BookingStatus::Cancelled))->handle();

    Http::assertSent(function (Request $request) use ($booking): bool {
        $expected = 'sha256='.hash_hmac('sha256', $request->header('X-Kidana-Timestamp')[0].'.'.$request->body(), 'shh');

        return $request->url() === 'https://partner.test/webhooks/kidana'
            && $request->header('X-Kidana-Signature')[0] === $expected
            && $request['event'] === 'booking.status_changed'
            && $request['status'] === 'cancelled'
            && $request['data']['reference'] === $booking->reference;
    });
});

test('a failed webhook delivery throws so the queue retries it', function () {
    Http::fake(['partner.test/*' => Http::response(status: 500)]);

    $client = ApiClient::factory()->withWebhook()->create();
    $booking = Booking::factory()->create(['source' => BookingSource::Api, 'api_client_id' => $client->id]);

    (new SendApiClientWebhook($booking, BookingStatus::Paid))->handle();
})->throws(RequestException::class);

test('no webhook is sent when the client has no webhook url', function () {
    Http::preventStrayRequests();
    Http::fake();

    $booking = Booking::factory()->create(['source' => BookingSource::Api, 'api_client_id' => ApiClient::factory()->create()->id]);

    (new SendApiClientWebhook($booking, BookingStatus::Paid))->handle();

    Http::assertNothingSent();
});
