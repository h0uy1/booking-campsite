<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Price>
 */
class PriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tent_id' => \App\Models\Tent::factory(),
            'price_weekday' => $this->faker->randomFloat(2, 50, 200),
            'price_weekend' => $this->faker->randomFloat(2, 80, 250),
            'capacity' => $this->faker->numberBetween(2, 6),
            'adult_price' => $this->faker->randomFloat(2, 10, 50),
            'child_price' => $this->faker->randomFloat(2, 5, 25),
        ];
    }
}
