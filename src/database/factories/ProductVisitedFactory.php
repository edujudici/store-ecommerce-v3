<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVisited>
 */
class ProductVisitedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'prv_id' => $this->faker->randomNumber(9),
            'pro_sku' => $this->faker->randomNumber(9),
            'prv_visited' => $this->faker->randomNumber(2),
        ];
    }
}
