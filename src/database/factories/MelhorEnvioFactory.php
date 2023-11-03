<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MelhorEnvio>
 */
class MelhorEnvioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'mee_token_type' => 'Bearer',
            'mee_expires_in' => $this->faker->randomNumber(9),
            'mee_access_token' => $this->faker->word,
            'mee_refresh_token' => $this->faker->word,
            'mee_authorize_code' => $this->faker->word,
        ];
    }
}
