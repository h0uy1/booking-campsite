<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tent>
 */
class TentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'pricing_type' => $this->faker->randomElement(['person', 'base']),
            'image' => null, // or $this->faker->imageUrl() if you want fake images
        ];
    }
}
