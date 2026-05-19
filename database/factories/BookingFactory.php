<?php

namespace Database\Factories;

use App\Enum\BookingStatus;
use App\Enum\PackageOrderAction;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'package_id' => Package::factory(),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_mobile' => '010'.fake()->numerify('########'),
            'guests' => fake()->numberBetween(1, 4),
            'travel_date' => now()->addMonth()->toDateString(),
            'message' => fake()->sentence(),
            'locale' => 'en',
            'type' => PackageOrderAction::CustomForm,
            'status' => BookingStatus::Pending,
            'total_amount' => fake()->randomFloat(2, 1500, 12000),
            'currency' => 'EGP',
        ];
    }
}
