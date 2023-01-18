<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderHistory>
 */
class OrderHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'orh_id' => $this->faker->randomNumber(9),
            'ord_id' => $this->faker->randomNumber(9),
            'orh_preference_id' => $this->faker->randomNumber(9),
            'orh_collection_status' => 'new',
        ];
    }
}
