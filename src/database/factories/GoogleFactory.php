<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Google>
 */
class GoogleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'goo_token_type' => 'Bearer',
            'goo_expires_in' => $this->faker->randomNumber(9),
            'goo_access_token' => $this->faker->word,
            'goo_refresh_token' => $this->faker->word,
            'goo_created' => $this->faker->randomNumber(9),
            'goo_scope' => $this->faker->word,
        ];
    }
}
