<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSpecification>
 */
class ProductSpecificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'prs_id' => $this->faker->randomNumber(9),
            'pro_sku' => $this->faker->randomNumber(9),
            'prs_key' => $this->faker->word,
            'prs_value' => $this->faker->word,
        ];
    }
}
