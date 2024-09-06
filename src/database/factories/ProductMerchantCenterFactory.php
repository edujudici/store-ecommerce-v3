<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductMerchantCenter>
 */
class ProductMerchantCenterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'prm_id' => $this->faker->randomNumber(9),
            'pro_sku' => $this->faker->randomNumber(9),
        ];
    }
}
