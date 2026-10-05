<?php

namespace Database\Factories;

use App\Enum\HotelBoardBasis;
use App\Enum\PackageOrderAction;
use App\Models\Destination;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Hotel>
 */
class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => [
                'en' => fake()->company().' Hotel',
                'ar' => fake()->company().' Hotel',
            ],
            'slug' => fake()->unique()->slug(),
            'destination_id' => Destination::factory(),
            'description' => [
                'en' => fake()->paragraph(),
                'ar' => fake()->paragraph(),
            ],
            'address' => [
                'en' => fake()->address(),
                'ar' => fake()->address(),
            ],
            'stars' => fake()->numberBetween(3, 5),
            'board_basis' => HotelBoardBasis::BedAndBreakfast,
            'check_in_time' => '14:00',
            'check_out_time' => '12:00',
            'order_action' => PackageOrderAction::CustomForm,
            'image_url' => fake()->imageUrl(1200, 800, 'hotel'),
            'amenities' => [
                'en' => [
                    ['icon' => 'heroicon-o-wifi', 'title' => 'Free Wi-Fi'],
                ],
            ],
            'tags' => ['featured'],
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }

    public function fawryPayment(): static
    {
        return $this->state(fn (array $attributes): array => [
            'order_action' => PackageOrderAction::FawryPayment,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }
}
