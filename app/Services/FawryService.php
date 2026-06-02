<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FawryService
{
    protected string $merchantCode;

    protected string $secureKey;

    protected bool $isSandbox;

    public function __construct(protected HttpFactory $http)
    {
        $this->merchantCode = (string) config('fawry.merchant_code');
        $this->secureKey = (string) config('fawry.secure_key');
        $this->isSandbox = config('fawry.mode') === 'sandbox';
    }

    public function isConfigured(): bool
    {
        return filled($this->merchantCode) && filled($this->secureKey);
    }

    /**
     * Pull the current Fawry payment status using Get Payment Status V2.
     *
     * @return array<string, mixed>
     */
    public function getPaymentStatus(Payment $payment): array
    {
        $merchantRefNumber = $payment->merchant_ref_number;
        $query = [
            'merchantCode' => $this->merchantCode,
            'merchantRefNumber' => $merchantRefNumber,
            'signature' => $this->statusInquirySignature($merchantRefNumber),
        ];

        Log::info('Sending Fawry payment status inquiry.', [
            'payment_id' => $payment->id,
            'merchant_ref_number' => $merchantRefNumber,
            'endpoint' => config('fawry.status_endpoint'),
        ]);

        $response = $this->http
            ->acceptJson()
            ->timeout(10)
            ->connectTimeout(5)
            ->retry(2, 200)
            ->get((string) config('fawry.status_endpoint'), $query)
            ->throw();

        $payload = $response->json();

        if (! is_array($payload)) {
            $payload = [
                'raw' => $response->body(),
            ];
        }

        Log::info('Fawry payment status inquiry received.', [
            'payment_id' => $payment->id,
            'merchant_ref_number' => $merchantRefNumber,
            'order_status' => $payload['orderStatus'] ?? $payload['paymentStatus'] ?? $payload['payment_status'] ?? null,
            'status_code' => $payload['statusCode'] ?? null,
            'request_id' => $payload['requestId'] ?? $payload['requestUID'] ?? null,
        ]);

        return $payload;
    }

    public function statusInquirySignature(string $merchantRefNumber): string
    {
        return hash('sha256', $this->merchantCode.$merchantRefNumber.$this->secureKey);
    }

    /**
     * Build the charge request object passed to the FawryPay JS plugin.
     *
     * @param  array{
     *     order_id?: string,
     *     return_url?: string,
     *     webhook_url?: string,
     *     customer: array{name?: string, email?: string, mobile?: string, profile_id?: string},
     *     items: array<int, array{id: string|int, description?: string, price: int|float|string, quantity: int|string}>
     * }  $params
     * @return array<string, mixed>
     */
    public function buildChargeRequest(array $params): array
    {
        $merchantRefNum = $params['order_id'] ?? Str::uuid()->toString();
        $returnUrl = $params['return_url'] ?? route('payment.callback');
        $webhookUrl = $params['webhook_url'] ?? route('payment.webhook');
        $customer = $params['customer'];
        $items = $params['items'];

        $signature = $this->generateSignature(
            $merchantRefNum,
            (string) ($customer['profile_id'] ?? ''),
            $returnUrl,
            $items
        );

        return [
            'merchantCode' => $this->merchantCode,
            'merchantRefNum' => $merchantRefNum,
            'customerMobile' => $customer['mobile'] ?? '',
            'customerEmail' => $customer['email'] ?? '',
            'customerName' => $customer['name'] ?? '',
            'customerProfileId' => (string) ($customer['profile_id'] ?? ''),
            'chargeItems' => $this->formatItems($items),
            'returnUrl' => $returnUrl,
            'orderWebHookUrl' => $webhookUrl,
            'authCaptureModePayment' => false,
            'signature' => $signature,
        ];
    }

    /**
     * Generate SHA-256 signature.
     *
     * Formula:
     * merchantCode + merchantRefNum + customerProfileId + returnUrl
     * + (itemId + quantity + price per item, sorted by itemId)
     * + secureKey
     *
     * @param  array<int, array{id: string|int, price: int|float|string, quantity: int|string}>  $items
     */
    public function generateSignature(
        string $merchantRefNum,
        string $customerProfileId,
        string $returnUrl,
        array $items
    ): string {
        usort($items, fn (array $a, array $b): int => strcmp((string) $a['id'], (string) $b['id']));

        $signaturePayload = $this->merchantCode.$merchantRefNum.$customerProfileId.$returnUrl;

        foreach ($items as $item) {
            $price = number_format((float) $item['price'], 2, '.', '');
            $signaturePayload .= $item['id'].$item['quantity'].$price;
        }

        $signaturePayload .= $this->secureKey;

        return hash('sha256', $signaturePayload);
    }

    /**
     * Verify the signature on the callback/webhook POST from Fawry.
     *
     * Formula:
     * referenceNumber + merchantRefNum + paymentAmount + orderAmount
     * + orderStatus + paymentMethod + fawryFees + shippingFees
     * + authNumber + customerMail + customerMobile + secureKey
     *
     * @param  array<string, mixed>  $data
     */
    public function verifyCallbackSignature(array $data): bool
    {
        $referenceNumber = (string) ($data['referenceNumber'] ?? $data['fawryRefNumber'] ?? '');

        $merchantRefNumber = (string)(
            $data['merchantRefNumber']
            ?? $data['merchantRefNum']
            ?? ''
        );

        $paymentAmount = number_format((float) ($data['paymentAmount'] ?? 0), 2, '.', '');
        $orderAmount = number_format((float) ($data['orderAmount'] ?? 0), 2, '.', '');

        $orderStatus = (string) ($data['orderStatus'] ?? '');
        $paymentMethod = (string) ($data['paymentMethod'] ?? '');

        $fawryFees = isset($data['fawryFees'])
            ? number_format((float)$data['fawryFees'], 2, '.', '')
            : '';

        $shippingFees = isset($data['shippingFees'])
            ? number_format((float)$data['shippingFees'], 2, '.', '')
            : '';

        $authNumber = (string) ($data['authNumber'] ?? '');

        $customerMail = (string)(
            $data['customerMail']
            ?? $data['customerEmail']
            ?? ''
        );

        $customerMobile = (string) ($data['customerMobile'] ?? '');

        $providedSignature = (string)(
            $data['signature']
            ?? $data['messageSignature']
            ?? ''
        );

        if ($providedSignature === '') {
            return false;
        }

        $signaturePayload =
            $referenceNumber
            . $merchantRefNumber
            . $paymentAmount
            . $orderAmount
            . $orderStatus
            . $paymentMethod
            . $fawryFees
            . $shippingFees
            . $authNumber
            . $customerMail
            . $customerMobile
            . $this->secureKey;

        return hash_equals(
            strtolower($providedSignature),
            hash('sha256', $signaturePayload)
        );
    }

    public function getJsUrl(): string
    {
        return $this->isSandbox ? config('fawry.sandbox_js') : config('fawry.production_js');
    }

    public function getCssUrl(): string
    {
        return $this->isSandbox ? config('fawry.sandbox_css') : config('fawry.production_css');
    }

    /**
     * @param  array<int, array{id: string|int, description?: string, price: int|float|string, quantity: int|string}>  $items
     * @return array<int, array{itemId: string, description: string, price: float, quantity: int}>
     */
    private function formatItems(array $items): array
    {
        return array_map(fn (array $item): array => [
            'itemId' => (string) $item['id'],
            'description' => $item['description'] ?? '',
            'price' => (float) $item['price'],
            'quantity' => (int) $item['quantity'],
        ], $items);
    }
}
