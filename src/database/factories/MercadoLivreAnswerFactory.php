<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MercadoLivreAnswer>
 */
class MercadoLivreAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'mea_id' => $this->faker->randomNumber(9),
            'mea_description' => $this->faker->sentence,
        ];
    }
}
