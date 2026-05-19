<?php

namespace Database\Factories;

use App\Enum\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'provider' => 'fawry',
            'merchant_ref_number' => 'BK-'.fake()->unique()->numerify('########'),
            'fawry_reference_number' => fake()->numerify('##########'),
            'payment_method' => 'PayAtFawry',
            'status' => PaymentStatus::Pending,
            'amount' => fake()->randomFloat(2, 1500, 12000),
            'currency' => 'EGP',
        ];
    }
}
