<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoadHistory>
 */
class LoadHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'loh_id' => $this->faker->randomNumber(9),
            'loh_total' => $this->faker->randomFloat(2, 1, 100),
            'loh_account_title' => $this->faker->word,
            'created_at' => $this->faker->date(),
        ];
    }
}
