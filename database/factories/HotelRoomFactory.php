<?php

namespace Database\Factories;

use App\Models\Hotel;
use App\Models\HotelRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HotelRoom>
 */
class HotelRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hotel_id' => Hotel::factory(),
            'name' => [
                'en' => fake()->randomElement(['Standard Room', 'Deluxe Room', 'Family Suite']),
                'ar' => fake()->randomElement(['غرفة قياسية', 'غرفة ديلوكس', 'جناح عائلي']),
            ],
            'description' => [
                'en' => fake()->sentence(),
            ],
            'bed_type' => [
                'en' => '1 King bed',
            ],
            'size_sqm' => fake()->numberBetween(20, 60),
            'max_adults' => 2,
            'max_children' => 1,
            'price_per_night' => fake()->numberBetween(1000, 5000),
            'quantity' => 5,
            'amenities' => ['Sea view', 'Balcony'],
            'is_active' => true,
            'sort_order' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }
}
