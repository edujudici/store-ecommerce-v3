<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MercadoLivreProduct>
 */
class MercadoLivreProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'mep_id' => $this->faker->randomNumber(9),
            'mep_item_id' => $this->faker->randomNumber(9),
            'mep_title' => $this->faker->word,
            'mep_price' => $this->faker->randomFloat(2, 1, 100),
            'mep_permalink' => $this->faker->url,
            'mep_secure_thumbnail' => 'http://via.placeholder.com/220x240',
        ];
    }
}
