<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserSession>
 */
class UserSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uss_id' => $this->faker->randomNumber(9),
            'user_id' => $this->faker->randomNumber(9),
            'uss_type' => 'favorite',
            'uss_json' => '{"type": "test"}',
        ];
    }
}
