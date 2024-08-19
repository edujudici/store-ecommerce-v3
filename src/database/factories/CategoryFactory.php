<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'cat_id' => $this->faker->randomNumber(9),
            'cat_title' => $this->faker->sentence(),
            'cat_image' => 'https://via.placeholder.com/350x190',
            'cat_id_secondary' => $this->faker->randomNumber(9),
            'cat_seller_id' => $this->faker->randomNumber(9),
            'cat_visible' => true,
        ];
    }
}
