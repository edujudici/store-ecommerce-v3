<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductExclusive>
 */
class ProductExclusiveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'pre_id' => $this->faker->randomNumber(9),
            'pro_sku' => $this->faker->randomNumber(9),
        ];
    }
}
