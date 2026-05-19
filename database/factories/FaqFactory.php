<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Faq>
 */
class FaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => [
                'en' => fake()->sentence(),
                'ar' => fake()->sentence(),
            ],
            'answer' => [
                'en' => fake()->paragraph(),
                'ar' => fake()->paragraph(),
            ],
            'tags' => [
                'home',
            ],
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
