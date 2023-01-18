<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feature>
 */
class FeatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'fea_id' => $this->faker->randomNumber(9),
            'fea_title' => $this->faker->word,
            'fea_description' => $this->faker->sentence,
            'fea_image' => 'http://via.placeholder.com/50X38',
        ];
    }
}
