<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductGoogleHistory>
 */
class ProductGoogleHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'pgh_id' => $this->faker->randomNumber(9),
            'pgh_total' => $this->faker->randomFloat(2, 1, 100),
            'pgh_account_title' => $this->faker->word,
            'created_at' => $this->faker->date(),
        ];
    }
}
