<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MercadoLivre>
 */
class MercadoLivreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'mel_id' => $this->faker->randomNumber(9),
            'mel_title' => $this->faker->word,
            'mel_code_tg' => $this->faker->word,
            'mel_access_token' => $this->faker->word,
            'mel_token_type' => 'bearer',
            'mel_expires_in' => $this->faker->randomNumber(9),
            'mel_scope' => $this->faker->url,
            'mel_user_id' => $this->faker->randomNumber(9),
            'mel_refresh_token' => $this->faker->word,
            'mel_after_sales_message' => $this->faker->word,
            'mel_after_sales_enabled' => $this->faker->boolean,
        ];
    }
}
