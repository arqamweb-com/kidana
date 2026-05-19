<?php

namespace App\Services\Fawry;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Arr;

class FawryClient
{
    public function __construct(private readonly HttpFactory $http) {}

    public function isConfigured(): bool
    {
        return filled(config('fawry.merchant_code')) && filled(config('fawry.secure_key'));
    }

    /**
     * @return array<string, mixed>
     */
    public function createHostedCheckout(Booking $booking, Payment $payment): array
    {
        $payload = $this->buildHostedCheckoutPayload($booking, $payment);

        $response = $this->http
            ->acceptJson()
            ->asJson()
            ->timeout(10)
            ->connectTimeout(5)
            ->retry(2, 200)
            ->post((string) config('fawry.endpoint'), $payload)
            ->throw();

        $json = $response->json();

        if (is_string($json) && filter_var($json, FILTER_VALIDATE_URL)) {
            return [
                'paymentUrl' => $json,
            ];
        }

        if (is_array($json)) {
            return [
                ...$json,
                'paymentUrl' => $this->paymentUrlFromResponse($json),
            ];
        }

        $body = trim($response->body());
        $decodedBody = json_decode($body, true);

        if (is_string($decodedBody) && filter_var($decodedBody, FILTER_VALIDATE_URL)) {
            return [
                'paymentUrl' => $decodedBody,
                'raw' => $body,
            ];
        }

        return [
            'paymentUrl' => filter_var($body, FILTER_VALIDATE_URL) ? $body : null,
            'raw' => $body,
        ];
    }

    public function notificationSignatureMatches(array $payload): bool
    {
        $providedSignature = (string) (Arr::get($payload, 'messageSignature') ?: Arr::get($payload, 'signature'));

        if (blank($providedSignature)) {
            return false;
        }

        return hash_equals(strtolower($providedSignature), $this->notificationSignature($payload));
    }

    public function chargeResponseSignatureMatches(array $payload): bool
    {
        $providedSignature = (string) Arr::get($payload, 'signature');

        if (blank($providedSignature)) {
            return false;
        }

        return hash_equals(strtolower($providedSignature), $this->chargeResponseSignature($payload));
    }

    /**
     * @return array<string, mixed>
     */
    public function buildHostedCheckoutPayload(Booking $booking, Payment $payment): array
    {
        $amount = $this->formatAmount($payment->amount);
        $paymentMethod = (string) config('fawry.payment_method');
        $merchantCode = (string) config('fawry.merchant_code');
        $customerProfileId = (string) $booking->id;
        $returnUrl = route('bookings.result', [
            'locale' => $booking->locale,
            'booking' => $booking,
        ]);
        $itemId = (string) $booking->package_id;
        $quantity = '1';

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
                    'itemId' => $itemId,
                    'description' => (string) $booking->package->name,
                    'price' => $amount,
                    'quantity' => $quantity,
                ],
            ],
            'paymentMethod' => $paymentMethod,
            'returnUrl' => $returnUrl,
            'orderWebHookUrl' => route('payments.fawry.webhook'),
            'authCaptureModePayment' => false,
            'description' => (string) $booking->package->name,
            'signature' => hash('sha256', $merchantCode.$payment->merchant_ref_number.$customerProfileId.$returnUrl.$itemId.$quantity.$amount.(string) config('fawry.secure_key')),
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

    protected function chargeResponseSignature(array $payload): string
    {
        $signaturePayload = implode('', [
            (string) Arr::get($payload, 'referenceNumber', ''),
            (string) Arr::get($payload, 'merchantRefNumber', Arr::get($payload, 'merchantRefNum', '')),
            $this->formatAmount(Arr::get($payload, 'paymentAmount', Arr::get($payload, 'amount', 0))),
            $this->formatAmount(Arr::get($payload, 'orderAmount', Arr::get($payload, 'amount', 0))),
            (string) Arr::get($payload, 'orderStatus', ''),
            (string) Arr::get($payload, 'paymentMethod', ''),
            filled(Arr::get($payload, 'fawryFees')) ? $this->formatAmount(Arr::get($payload, 'fawryFees')) : '',
            filled(Arr::get($payload, 'shippingFees')) ? $this->formatAmount(Arr::get($payload, 'shippingFees')) : '',
            (string) Arr::get($payload, 'authNumber', ''),
            (string) Arr::get($payload, 'customerMail', ''),
            (string) Arr::get($payload, 'customerMobile', ''),
            (string) config('fawry.secure_key'),
        ]);

        return hash('sha256', $signaturePayload);
    }

    /**
     * @param  array<string, mixed>  $response
     */
    protected function paymentUrlFromResponse(array $response): ?string
    {
        foreach (['paymentUrl', 'redirectUrl', 'url', 'checkoutUrl', 'nextAction.redirectUrl'] as $key) {
            $url = Arr::get($response, $key);

            if (is_string($url) && filter_var($url, FILTER_VALIDATE_URL)) {
                return $url;
            }
        }

        return null;
    }
}
