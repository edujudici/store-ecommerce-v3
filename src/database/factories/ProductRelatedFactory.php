<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductRelated>
 */
class ProductRelatedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'prr_id' => $this->faker->randomNumber(9),
            'pro_sku' => $this->faker->randomNumber(9),
            'pro_sku_related' => $this->faker->randomNumber(9),
            'prr_external' => false
        ];
    }
}
