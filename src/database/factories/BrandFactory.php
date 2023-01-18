<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'bra_id' => $this->faker->randomNumber(9),
            'bra_title' => $this->faker->sentence,
            'bra_image' => 'https://via.placeholder.com/110x70',
        ];
    }
}
