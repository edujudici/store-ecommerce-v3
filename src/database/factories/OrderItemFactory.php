<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ori_id' => $this->faker->randomNumber(9),
            'ord_id' => $this->faker->randomNumber(9),
            'ori_pro_id' => $this->faker->randomNumber(9),
            'ori_pro_sku' => $this->faker->randomNumber(9),
            'ori_amount' => $this->faker->randomNumber(5),
            'ori_price' => $this->faker->randomFloat(2, 1, 100),
            'ori_title' => $this->faker->sentence,
        ];
    }
}
