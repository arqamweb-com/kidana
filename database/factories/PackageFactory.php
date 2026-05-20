<?php

namespace Database\Factories;

use App\Enum\PackageOrderAction;
use App\Models\Destination;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Package>
 */
class PackageFactory extends Factory
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
                'en' => fake()->words(3, true),
                'ar' => fake()->words(3, true),
            ],
            'slug' => fake()->unique()->slug(),
            'description' => [
                'en' => fake()->sentence(),
                'ar' => fake()->sentence(),
            ],
            'destination_id' => Destination::factory(),
            'location_label' => fake()->city(),
            'price' => fake()->numberBetween(1500, 12000),
            'order_action' => PackageOrderAction::CustomForm,
            'start_date' => now()->addDays(fake()->numberBetween(5, 20))->startOfDay(),
            'end_date' => now()->addDays(fake()->numberBetween(21, 35))->startOfDay(),
            'max_guests' => fake()->numberBetween(1, 8),
            'image_url' => fake()->imageUrl(1200, 800, 'travel'),
            'features' => [
                fake()->sentence(3),
                fake()->sentence(3),
            ],
            'tags' => [
                'featured',
                'umrah',
            ],
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
