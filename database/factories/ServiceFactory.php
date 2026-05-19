<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
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
                'en' => fake()->words(2, true),
                'es' => fake()->words(2, true),
            ],
            'slug' => fake()->unique()->slug(),
            'description' => [
                'en' => fake()->sentence(),
                'es' => fake()->sentence(),
            ],
            'icon' => null,
            'image_url' => fake()->imageUrl(1200, 800, 'travel'),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
