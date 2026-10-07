<?php

namespace Database\Factories;

use App\Enum\ApiAbility;
use App\Enum\ApiPaymentMode;
use App\Models\ApiClient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApiClient>
 */
class ApiClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'contact_email' => fake()->companyEmail(),
            'is_active' => true,
            'markup_percent' => 0,
            'payment_mode' => ApiPaymentMode::PartnerCollect,
            'allowed_abilities' => ApiAbility::values(),
            'webhook_url' => null,
            'webhook_secret' => 'test-webhook-secret',
            'ip_allowlist' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }

    public function fawryPayment(): static
    {
        return $this->state(fn (array $attributes): array => [
            'payment_mode' => ApiPaymentMode::Fawry,
        ]);
    }

    public function withMarkup(int|float $percent): static
    {
        return $this->state(fn (array $attributes): array => [
            'markup_percent' => $percent,
        ]);
    }

    public function withWebhook(string $url = 'https://partner.test/webhooks/kidana'): static
    {
        return $this->state(fn (array $attributes): array => [
            'webhook_url' => $url,
        ]);
    }
}
