<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
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
                'en' => fake()->name(),
                'ar' => fake()->name(),
            ],
            'testimonial' => [
                'en' => fake()->paragraph(),
                'ar' => fake()->paragraph(),
            ],
            'position' => [
                'en' => fake()->jobTitle(),
                'ar' => fake()->jobTitle(),
            ],
            'tags' => [
                'home',
            ],
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
