<?php

namespace App\Services\Fawry;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Arr;

class FawryClient
{
    public function __construct(private readonly HttpFactory $http)
    {
    }

    public function isConfigured(): bool
    {
        return filled(config('fawry.merchant_code')) && filled(config('fawry.secure_key'));
    }

    /**
     * @return array<string, mixed>
     */
    public function createPaymentReference(Booking $booking, Payment $payment): array
    {
        $payload = $this->buildChargePayload($booking, $payment);

        $response = $this->http
            ->acceptJson()
            ->asJson()
            ->timeout(10)
            ->connectTimeout(5)
            ->retry(2, 200)
            ->post((string) config('fawry.endpoint'), $payload)
            ->throw()
            ->json();

        return is_array($response) ? $response : [];
    }

    public function notificationSignatureMatches(array $payload): bool
    {
        $providedSignature = (string) (Arr::get($payload, 'messageSignature') ?: Arr::get($payload, 'signature'));

        if (blank($providedSignature)) {
            return false;
        }

        return hash_equals(strtolower($providedSignature), $this->notificationSignature($payload));
    }

    /**
     * @return array<string, mixed>
     */
    public function buildChargePayload(Booking $booking, Payment $payment): array
    {
        $amount = $this->formatAmount($payment->amount);
        $paymentMethod = (string) config('fawry.payment_method');
        $merchantCode = (string) config('fawry.merchant_code');
        $customerProfileId = (string) $booking->id;

        return [
            'merchantCode' => $merchantCode,
            'customerName' => $booking->customer_name,
            'customerMobile' => $booking->customer_mobile,
            'customerEmail' => $booking->customer_email,
            'customerProfileId' => $customerProfileId,
            'merchantRefNum' => $payment->merchant_ref_number,
            'amount' => $amount,
            'paymentExpiry' => $payment->expires_at?->getTimestampMs(),
            'currencyCode' => $payment->currency,
            'language' => $booking->locale === 'ar' ? 'ar-eg' : 'en-gb',
            'chargeItems' => [
                [
                    'itemId' => (string) $booking->package_id,
                    'description' => (string) $booking->package->name,
                    'price' => $amount,
                    'quantity' => '1',
                ],
            ],
            'paymentMethod' => $paymentMethod,
            'description' => (string) $booking->package->name,
            'signature' => hash('sha256', $merchantCode.$payment->merchant_ref_number.$customerProfileId.$paymentMethod.$amount.(string) config('fawry.secure_key')),
        ];
    }

    public function formatAmount(mixed $amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }

    protected function notificationSignature(array $payload): string
    {
        $signaturePayload = implode('', [
            (string) Arr::get($payload, 'fawryRefNumber', Arr::get($payload, 'referenceNumber', '')),
            (string) Arr::get($payload, 'merchantRefNum', Arr::get($payload, 'merchantRefNumber', '')),
            $this->formatAmount(Arr::get($payload, 'paymentAmount', Arr::get($payload, 'amount', 0))),
            $this->formatAmount(Arr::get($payload, 'orderAmount', Arr::get($payload, 'amount', 0))),
            (string) Arr::get($payload, 'orderStatus', ''),
            (string) Arr::get($payload, 'paymentMethod', ''),
            (string) Arr::get($payload, 'paymentReferenceNumber', Arr::get($payload, 'paymentRefrenceNumber', '')),
            (string) config('fawry.secure_key'),
        ]);

        return hash('sha256', $signaturePayload);
    }
}
