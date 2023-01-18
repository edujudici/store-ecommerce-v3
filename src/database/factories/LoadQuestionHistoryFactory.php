<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoadQuestionHistory>
 */
class LoadQuestionHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'lqh_id' => $this->faker->randomNumber(9),
            'lqh_total' => $this->faker->randomNumber(2),
            'lqh_total_sync' => $this->faker->randomNumber(2),
            'lqh_account_id' => $this->faker->randomNumber(9),
            'lqh_account_title' => $this->faker->title,
        ];
    }
}
