<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MercadoLivreUser>
 */
class MercadoLivreUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'meu_id' => $this->faker->randomNumber(9),
            'meu_user_id' => $this->faker->randomNumber(9),
            'meu_nickname' => $this->faker->name,
            'meu_registration_date' => $this->faker->dateTime(),
            'meu_address_city' => $this->faker->city,
            'meu_address_state' => 'BR-SP',
            'meu_points' => $this->faker->randomFloat(2, 1, 100),
            'meu_permalink' => $this->faker->url,
            'meu_level_id' => $this->faker->word,
            'meu_power_seller_status' => $this->faker->word,
            'meu_transactions_canceled' => $this->faker->randomNumber(1),
            'meu_transactions_completed' => $this->faker->randomNumber(1),
            'meu_transactions_total' => $this->faker->randomNumber(1),
        ];
    }
}
