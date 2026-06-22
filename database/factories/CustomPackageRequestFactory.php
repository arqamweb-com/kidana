<?php

namespace Database\Factories;

use App\Enum\CustomPackageRequestStatus;
use App\Models\CustomPackageRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomPackageRequest>
 */
class CustomPackageRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'destination' => fake()->randomElement(['Egypt', 'Turkey', 'Maldives']),
            'travel_type' => fake()->randomElement(['Guided Tours', 'Umrah', 'Leisure']),
            'travelers' => fake()->numberBetween(1, 8),
            'travel_date' => fake()->dateTimeBetween('+1 month', '+6 months'),
            'budget' => fake()->randomElement(['Under EGP 100,000', 'EGP 100,000 – 200,000', 'Above EGP 200,000']),
            'accommodation' => fake()->randomElement(['3-Star', '4-Star', '5-Star', 'Luxury']),
            'duration' => fake()->randomElement(['1 – 5 Days', '6 – 9 Days', '10+ Days']),
            'notes' => fake()->optional()->sentence(),
            'status' => CustomPackageRequestStatus::New,
            'locale' => 'en',
        ];
    }
}
